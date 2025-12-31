<?php
/**
 * User Management View
 * 
 * @since 8.4
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package KP Library
 */

defined('KPTV_PATH') || die('Direct Access is not allowed!');

// make sure we've got our namespaces...
use KPT\DataTables\DataTables;

// Check if user is logged in and is an admin
$currentUser = KPTV_User::get_current_user( );
if ( ! $currentUser || $currentUser -> role != 99 ) {
    // it's not, dump out and redirect with a message
    KPTV::message_with_redirect( '/', 'danger', 'You do not have permission to access this page.' );
    return;
}

$formFields = [];

// Configure database via constructor
$dbconf = [
    'server' => DB_SERVER,
    'schema' => DB_SCHEMA,
    'username' => DB_USER,
    'password' => DB_PASS,
    'charset' => 'utf8mb4',
    'collation' => 'utf8mb4_unicode_ci',
];

// fire up the datatables class
$dt = new DataTables( $dbconf );

// configure the datatable
$dt -> table( 'kptv_users' )
    -> tableClass( 'uk-table uk-table-divider uk-table-small uk-margin-bottom' )
    -> primaryKey( 'id' )
    -> perPage( 25 )
    -> pageSizeOptions( [25, 50, 100, 250], true ) // true includes "ALL" option
    -> columns( [
        'id' => 'ID',
        'u_active' => ['type' => 'boolean', 'label' => 'Active'],
        'u_role' => [
            'type' => 'select',
            'label' => 'Role',
            'options' => [
                '99' => 'Administrator',
                '1' => 'User'
            ]
        ],
        'u_fname' => 'F. Name',
        'u_lname' => 'L. Name',
        'u_email' => 'Email',
        'last_login' => 'Last Login'
    ] )
    -> inlineEditable( ['u_active',] )
    -> sortable( ['u_role', 'u_fname', 'u_lname', 'u_email', 'last_login'] )
    -> defaultSort( 'last_login', 'ASC' )
    -> bulkActions( false )
    -> addForm( 'Add a Stream', $formFields, class: 'uk-grid-small uk-grid' )
    -> editForm( 'Update a Stream', $formFields, class: 'uk-grid-small uk-grid' );


// Handle AJAX requests (before any HTML output)
if ( isset( $_POST['action'] ) || isset( $_GET['action'] ) ) {
    $dt -> handleAjax( );
}

// pull in the header
KPTV::pull_header( );
?>
<h2 class="kptv-heading uk-heading-bullet">User Admin</h2>
<div class="uk-border-bottom">
    <?php

    // pull in the control panel
    KPTV::include_view( 'common/control-panel', [ 'dt' => $dt ] );
    ?>
</div>
<div class="uk-margin">
    <?php

    // write out the datatable component
    echo $dt -> renderDataTableComponent( );
    ?>
</div>
<div class="uk-border-top">
    <?php

    // pull in the control panel
    KPTV::include_view( 'common/control-panel', [ 'dt' => $dt ] );
    ?>
</div>
<?php

// pull in the footer
KPTV::pull_footer( );

// clean up
unset( $dt, $formFields, $dbconf );
