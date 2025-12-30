<?php
/**
 * sidebar.php
 * 
 * No direct access allowed!
 * 
 * @since 8.4
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package KP Library
 * 
 */

// define the primary app path if not already defined
/*defined( 'KPTV_PATH' ) || die( 'Direct Access is not allowed!' );

use KPT\KPT;
use KPT\Router;

// get the route we're in
$_route = Router::getCurrentRoute( );

// get ther user id for the export
$user_for_export = KPTV::encrypt( ( KPTV_User::get_current_user( ) -> id ) ?? 0 );
*/
?>
<div class="uk-width-auto uk-visible@m uk-flex uk-flex-column">
    <aside class="kptv-sidebar uk-flex-1">
        <ul class="uk-nav uk-nav-default uk-nav-parent-icon kptv-sidebar-nav" uk-nav>
            <li class="uk-nav-header">STREAM MANAGER</li>
            <li>
                <a href="#">
                    <span uk-icon="server"></span>
                    Your Providers
                </a>
            </li>
            <li>
                <a href="#">
                    <span uk-icon="settings"></span>
                    Your Filters
                </a>
            </li>
            <li class="uk-parent uk-open">
                <a href="#">
                    <span uk-icon="tv"></span>
                    Live Streams
                </a>
                <ul class="uk-nav-sub">
                    <li class="uk-active"><a href="streams.html">Active Streams</a></li>
                    <li><a href="#">Inactive Streams</a></li>
                    <li><a href="#">Export Streams</a></li>
                </ul>
            </li>
            <li class="uk-parent">
                <a href="#">
                    <span uk-icon="album"></span>
                    Series Streams
                </a>
                <ul class="uk-nav-sub">
                    <li class="uk-active"><a href="streams.html">Active Streams</a></li>
                    <li><a href="#">Inactive Streams</a></li>
                    <li><a href="#">Export Streams</a></li>
                </ul>
            </li>
            <li class="uk-parent">
                <a href="#">
                    <span uk-icon="video-camera"></span>
                    VOD Streams
                </a>
                <ul class="uk-nav-sub">
                    <li class="uk-active"><a href="streams.html">Active Streams</a></li>
                    <li><a href="#">Inactive Streams</a></li>
                    <li><a href="#">Export Streams</a></li>
                </ul>
            </li>
            <li>
                <a href="#">
                    <span uk-icon="nut"></span>
                    Other Streams
                </a>
            </li>
            <li>
                <a href="#">
                    <span uk-icon="minus-circle"></span>
                    Missing Streams
                </a>
            </li>
            <li class="uk-nav-divider"></li>
            <li class="uk-nav-header">ACCOUNT MANAGER</li>
            <li class="uk-parent">
                <a href="#">
                    <span uk-icon="user"></span>
                    Your Account
                </a>
                <ul class="uk-nav-sub">
                    <li><a href="#">General</a></li>
                    <li><a href="#">Cache</a></li>
                    <li><a href="#">Database</a></li>
                </ul>
            </li>
        </ul>
    </aside>
</div>
