<?php

class SckEnhancedSelection extends eZPersistentObject
{
    /**
     * Returns the definition array
     *
     * @return array
     */
    static function definition()
    {
        return array(
            'fields' => array(
                'contentobject_attribute_id' => array(
                    'name' => 'ContentObjectAttributeID',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => true
                ),
                'contentobject_attribute_version' => array(
                    'name' => 'ContentObjectAttributeVersion',
                    'datatype' => 'integer',
                    'default' => 0,
                    'required' => true
                ),
                'identifier' => array(
                    'name' => 'Identifier',
                    'datatype' => 'string',
                    'default' => '',
                    'required' => true
                )
            ),
            'keys' => array( 'contentobject_attribute_id', 'contentobject_attribute_version', 'identifier' ),
            'class_name' => 'SckEnhancedSelection',
            'sort' => array( 'contentobject_attribute_id' => 'asc', 'contentobject_attribute_version' => 'asc', 'identifier' => 'asc' ),
            'name' => 'sckenhancedselection'
        );
    }

    /**
     * Returns the specific record from the table
     *
     * @param int $contentObjectAttributeId
     * @param int $contentObjectAttributeVersion
     * @param string $identifier
     *
     * @return SckEnhancedSelection
     */
    static function fetch( $contentObjectAttributeId, $contentObjectAttributeVersion, $identifier )
    {
        if ( !self::attributeKey( $contentObjectAttributeId, $contentObjectAttributeVersion ) )
            return null;

        return eZPersistentObject::fetchObject(
            self::definition(),
            null,
            array(
                'contentobject_attribute_id' => $contentObjectAttributeId,
                'contentobject_attribute_version' => $contentObjectAttributeVersion,
                'identifier' => $identifier
            )
        );
    }

    /**
     * Returns records from the table by attribute
     *
     * @param int $contentObjectAttributeId
     * @param int $contentObjectAttributeVersion
     *
     * @return SckEnhancedSelection[]
     */
    static function fetchByAttribute( $contentObjectAttributeId, $contentObjectAttributeVersion )
    {
        if ( !self::attributeKey( $contentObjectAttributeId, $contentObjectAttributeVersion ) )
            return array();

        $result = eZPersistentObject::fetchObjectList(
            self::definition(),
            null,
            array(
                'contentobject_attribute_id' => $contentObjectAttributeId,
                'contentobject_attribute_version' => $contentObjectAttributeVersion
            )
        );

        if ( is_array( $result ) && !empty( $result ) )
        {
            return $result;
        }

        return array();
    }

    /**
     * Returns count of records in the table by attribute
     *
     * @param int $contentObjectAttributeId
     * @param int $contentObjectAttributeVersion
     *
     * @return int
     */
    static function countByAttribute( $contentObjectAttributeId, $contentObjectAttributeVersion )
    {
        if ( !self::attributeKey( $contentObjectAttributeId, $contentObjectAttributeVersion ) )
            return 0;

        return eZPersistentObject::count(
            self::definition(),
            array(
                'contentobject_attribute_id' => $contentObjectAttributeId,
                'contentobject_attribute_version' => $contentObjectAttributeVersion
            )
        );
    }

    /**
     * Removes the data from the table by attribute
     *
     * @param int $contentObjectAttributeId
     * @param int $contentObjectAttributeVersion
     */
    static function removeByAttribute( $contentObjectAttributeId, $contentObjectAttributeVersion = null )
    {
        $versionForCheck = $contentObjectAttributeVersion === null ? 0 : $contentObjectAttributeVersion;
        if ( !self::attributeKey( $contentObjectAttributeId, $versionForCheck ) )
            return;

        $conditions = array(
            'contentobject_attribute_id' => $contentObjectAttributeId
        );

        if ( $contentObjectAttributeVersion !== null )
        {
            $conditions['contentobject_attribute_version'] = $contentObjectAttributeVersion;
        }

        eZPersistentObject::removeObject(
            self::definition(),
            $conditions
        );
    }

    /**
     * Normalizes an attribute id and version to integers, in place. False when
     * the attribute has no id yet (not stored): nothing can be stored for it,
     * and PostgreSQL refuses to compare an integer column with ''.
     */
    protected static function attributeKey( &$contentObjectAttributeId, &$contentObjectAttributeVersion )
    {
        $contentObjectAttributeId = (int)$contentObjectAttributeId;
        $contentObjectAttributeVersion = (int)$contentObjectAttributeVersion;
        return $contentObjectAttributeId > 0;
    }
}
