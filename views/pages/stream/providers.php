<?php

/**
 * Providers View - Refactored to use modular system
 * 
 * @since 8.4
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package KP Library
 */

defined('KPTV_PATH') || die('Direct Access is not allowed!');

// setup the user id
$userId = KPTV_User::get_current_user()->id;

// setup the user string
$userForExport = KPTV::encrypt($userId);

// Configure database via constructor
$dbconf = (array) KPTV::get_setting('database');

// fire up the datatables class
$dt = new \KPT\DataTables\DataTables($dbconf);

// setup the form fields
$formFieldsConfig = KPTV::view_configs('providers', userId: $userId)->form;

// setup the row actions - extract from view_configs
$rowActionsConfig = KPTV::view_configs('providers', userForExport: $userForExport)->row;

// configure the datatable
$dt->table('kptv_stream_providers')
    ->tableClass('uk-table uk-table-divider uk-table-small uk-margin-bottom')
    ->where([
        [ // unless specified as OR, it should always be AND
            'field' => 'u_id',
            'comparison' => '=', // =, !=, >, <, <>, <=, >=, LIKE, NOT LIKE, IN, NOT IN, REGEXP
            'value' => $userId
        ],
    ])
    ->primaryKey('id')
    ->columns([
        'id' => 'ID',
        'sp_should_filter' => ['type' => 'boolean', 'label' => 'Filter'],
        'sp_priority' => [
            'label' => 'Priority',
        ],
        'sp_name' => 'Name',
        'sp_cnx_limit' => 'Connections',
    ])
    ->columnClasses([
        'sp_priority' => 'uk-min-width',
        'sp_cnx_limit' => 'uk-min-width',
        'sp_should_filter' => 'uk-min-width',
        'id' => 'hide-col'
    ])
    ->sortable(['sp_priority', 'sp_name', 'sp_should_filter'])
    ->defaultSort('sp_priority', 'ASC')
    ->inlineEditable(['sp_priority', 'sp_name', 'sp_cnx_limit', 'sp_should_filter'])
    ->perPage(25)
    ->pageSizeOptions([25, 50, 100, 250], true) // true includes "ALL" option
    ->bulkActions(true)
    ->addForm('Add a Provider', $formFieldsConfig, class: 'uk-grid-small uk-grid')
    ->editForm('Update a Provider', $formFieldsConfig, class: 'uk-grid-small uk-grid')
    ->actionGroups(array_merge($rowActionsConfig, [['edit']]));

// Handle AJAX requests (before any HTML output)
if (isset($_POST['action']) || isset($_GET['action'])) {
    $dt->handleAjax();
}

// pull in the header
KPTV::pull_header();
?>
<h2 class="kptv-heading uk-heading-bullet">Stream Providers</h2>
<div class="uk-border-bottom">
    <?php

    // pull in the control panel
    KPTV::include_view('common/control-panel', ['dt' => $dt]);
    ?>
</div>
<div class="uk-margin the-datatable">
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
unset($dt, $formFields, $dbconf);
