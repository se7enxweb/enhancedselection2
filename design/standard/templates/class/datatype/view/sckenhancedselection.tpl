{let content=$class_attribute.content
     i18n_context="extension/enhancedselection2/class/view"
     field_input_type = "Select (single choice)"}

<label>{"Option list"|i18n( 'extension/enhancedselection2/class/view' )}:</label>
<table class="list" cellspacing="0">
    <tr>
        <th style="width: 1%;">&nbsp;</th>
        <th>{"Name"|i18n( 'extension/enhancedselection2/class/view' )}</th>
        <th>{"Identifier"|i18n( 'extension/enhancedselection2/class/view' )}</th>
    </tr>

    {section var=option loop=$content.options}
    <tr>
        <td>{$option.number}.</td>
        <td>{first_set($option.item.name|wash,"&nbsp;")}</td>
        <td>{first_set($option.item.identifier|wash,"&nbsp;")}</td>
    </tr>
    {/section}
</table>

{if $content.is_expanded}
    {if $content.is_multiselect}
        {set $field_input_type = 'Checkboxes'|i18n( 'extension/enhancedselection2/class/view' )}
    {else}
        {set $field_input_type = 'Radio buttons'|i18n( 'extension/enhancedselection2/class/view' )}
    {/if}
{else}
    {if $content.is_multiselect}
        {set $field_input_type = 'Select (multiple choices)'|i18n( 'extension/enhancedselection2/class/view' )}
    {else}
        {set $field_input_type = 'Select (single choice)'|i18n( 'extension/enhancedselection2/class/view' )}
    {/if}
{/if}
<div class="block">
    <legend>{"Field input settings"|i18n( 'extension/enhancedselection2/class/view' )}</legend>
    <p><strong>{"Selected input format"|i18n( 'extension/enhancedselection2/class/view' )}:</strong> {$field_input_type}</p>

    <div class="element">
        <label>{"Expand choices"|i18n( 'extension/enhancedselection2/class/view' )}:</label>
        <p>{cond($content.is_expanded,"Yes"|i18n( 'extension/enhancedselection2/class/view' ),"No"|i18n( 'extension/enhancedselection2/class/view' ))}</p>
    </div>

    <div class="element">
        <label>{"Multiple choice"|i18n( 'extension/enhancedselection2/class/view' )}:</label>
        <p>{cond($content.is_multiselect,"Yes"|i18n( 'extension/enhancedselection2/class/view' ),"No"|i18n( 'extension/enhancedselection2/class/view' ))}</p>
    </div>
</div>

<div class="block">
    <legend>{"Other settings"|i18n( 'extension/enhancedselection2/class/view' )}</legend>
    <div class="element">
        <label>{"Delimiter"|i18n( 'extension/enhancedselection2/class/view' )}:</label>
        <p style="white-space: pre;">'{$content.delimiter|wash}'</p>
    </div>

    <div class="break"></div>

    <div class="element">
        <label>{"Database query"|i18n( 'extension/enhancedselection2/class/view' )}:</label>
        <p>{$content.query|wash|nl2br}</p>
    </div>
</div>

{/let}
