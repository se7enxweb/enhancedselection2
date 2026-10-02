<?php
/**
 * The code of extension/enhancedselection2/bin/php/migrate_to_database.php, moved into a class (#207 stage 1). The file extension/enhancedselection2/bin/php/migrate_to_database.php is one call to it.
 * Guide: doc/bc/6.0/cli_cronjob_view_abstractions.md
 * @description Migrate the enhanced selection datatype to the version that stores its data in a database table
 */

namespace Exponential\Command\Extension\Enhancedselection2
{

class MigrateToDatabase extends \Exponential\Runnable\Command
{
    public function run()
    {
        // the script's variables were globals; functions of the script read them with "global"
        foreach ( array( 'attribute', 'attributeCount', 'attributes', 'cli', 'db', 'identifier', 'identifiers', 'limit', 'offset', 'sckEnhancedSelection', 'script' ) as $__name )
            ${$__name} = &$GLOBALS[$__name];
        unset( $__name );

        $cli = $this->cli();

        $script = $this->script(
            array(
                'description' => "Migrates sckenhancedselection datatype to version which stores content object data to database table.",
                'use-session' => true,
                'use-modules' => false,
                'use-extensions' => true
            )
        );

        $script->startup();
        $this->options();
        $script->initialize();

        $cli->warning( "This script will NOT republish objects, but rather update ALL versions" );
        $cli->warning( "of content objects. If you do not wish to do that, you have" );
        $cli->warning( "15 seconds to cancel the script! (press Ctrl-C)\n" );
        sleep( 15 );

        $db = \eZDB::instance();

        $offset = 0;
        $limit = 50;

        $attributeCount = (int)\eZPersistentObject::count(
            \eZContentObjectAttribute::definition(),
            array(
                'data_type_string' => 'sckenhancedselection'
            )
        );

        while ( $offset < $attributeCount )
        {
            \eZContentObject::clearCache();

            /** @var eZContentObjectAttribute[] $attributes */
            $attributes = \eZPersistentObject::fetchObjectList(
                \eZContentObjectAttribute::definition(),
                null,
                array(
                    'data_type_string' => 'sckenhancedselection'
                ),
                null,
                array(
                    'offset' => $offset,
                    'length' => $limit
                )
            );

            foreach ( $attributes as $attribute )
            {
                \SckEnhancedSelection::removeByAttribute(
                    $attribute->attribute( 'id' ),
                    $attribute->attribute( 'version' )
                );

                $identifiers = unserialize( (string)$attribute->attribute( 'data_text' ) );
                if ( is_array( $identifiers ) && !empty( $identifiers ) )
                {
                    foreach ( $identifiers as $identifier )
                    {
                        $sckEnhancedSelection = new \SckEnhancedSelection(
                            array(
                                'contentobject_attribute_id' => $attribute->attribute( 'id' ),
                                'contentobject_attribute_version' => $attribute->attribute( 'version' ),
                                'identifier' => $identifier
                            )
                        );

                        $sckEnhancedSelection->store();
                    }
                }

                $attribute->setAttribute( 'data_text', null );
                $attribute->store();

                $cli->output( "Converted attribute #{$attribute->attribute( 'id' )} in version {$attribute->attribute( 'version' )}" );
            }

            unset( $attributes );
            $offset += $limit;
        }

        $cli->output( "\nDone!" );
        $cli->output( "\nFor changes to take effect, please clear the caches, reindex your content and so on.\n" );

        $script->shutdown( 0 );
    }
}

}
