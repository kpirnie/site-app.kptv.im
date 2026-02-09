<?php

/**
 * Missing Streams View
 * 
 * @since 8.4
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package KP Library
 */

defined('KPTV_PATH') || die('Direct Access is not allowed!');

// setup the user id
$userId = KPTV_User::get_current_user()->id;

// Configure database via constructor
$dbconf = (array) KPTV::get_setting('database');

// fire up the datatables class
$dt = new KPT\DataTables($dbconf);

// setup the form fields
$formFields = KPTV::view_configs('epg', userId: $userId)->form;

// configure the datatable
$dt->table('kptv_stream_epgs')
    ->theme('uikit', true)
    ->tableClass('uk-table uk-table-divider uk-table-small uk-margin-bottom')
    ->where([
        [ // unless specified as OR, it should always be AND
            'field' => 'u_id',
            'comparison' => '=', // =, !=, >, <, <>, <=, >=, LIKE, NOT LIKE, IN, NOT IN, REGEXP
            'value' => $userId
        ],
    ])
    ->columns([
        'id' => 'ID',
        'se_active' => ['type' => 'boolean', 'label' => 'Active'],
        'se_name' => 'Name',
        'se_source' => 'Source',
    ])
    ->columnClasses([
        'id' => 'hide-col',
        'se_active' => 'uk-min-width',
        'se_name' => 'uk-min-width',
    ])
    ->sortable(['se_active', 'se_source', 'se_name',])
    ->defaultSort('se_active', 'ASC')
    ->inlineEditable(['se_active', 'se_source', 'se_name',])
    ->bulkActions(true)
    ->actions('end', true, true, [])
    ->addForm('Add an EPG', $formFields, class: 'uk-grid-small uk-grid')
    ->editForm('Update an EPG', $formFields, class: 'uk-grid-small uk-grid');

// Handle AJAX requests (before any HTML output)
if (isset($_POST['action']) || isset($_GET['action'])) {
    $dt->handleAjax();
}

// pull in the header
KPTV::pull_header();
?>
<h2 class="kptv-heading uk-heading-bullet">EPG Sources</h2>
<div class="uk-border-bottom">
    <?php

    // pull in the control panel
    KPTV::include_view('common/control-panel', ['dt' => $dt]);
    ?>
</div>
<div class="uk-margin">
    <?php

    // write out the datatable component
    echo $dt->renderDataTableComponent();
    ?>
</div>
<div class="uk-border-top">
    <?php

    // pull in the control panel
    KPTV::include_view('common/control-panel', ['dt' => $dt]);
    ?>
</div>
<?php

// pull in the footer
KPTV::pull_footer();

// clean up
unset($dt, $formFields, $actionGroups, $bulkActions, $dbconf);
