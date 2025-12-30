<!-- Mobile Offcanvas Navigation -->
<div id="kptv-mobile-nav" uk-offcanvas="overlay: true; mode: push" class="kptv-offcanvas">
    <div class="uk-offcanvas-bar">
        <button class="uk-offcanvas-close" type="button" uk-close></button>
        
        <div class="kptv-logo kptv-offcanvas-logo">
            <img src="/assets/images/kptv-logo.png" alt="KPTV Logo">
            <span class="kptv-logo-text">KPTV</span>
        </div>
        
        <ul class="uk-nav uk-nav-default uk-nav-parent-icon kptv-offcanvas-nav" uk-nav>
            <li class="uk-active"><a href="index.html">Home</a></li>
            <li class="uk-parent">
                <a href="#">Info</a>
                <ul class="uk-nav-sub">
                    <li><a href="#">System Status</a></li>
                    <li><a href="#">Server Info</a></li>
                    <li class="uk-parent">
                        <a href="#">Documentation</a>
                        <ul class="uk-nav-sub">
                            <li><a href="#">Getting Started</a></li>
                            <li><a href="#">API Reference</a></li>
                            <li class="uk-parent">
                                <a href="#">Advanced</a>
                                <ul class="uk-nav-sub">
                                    <li><a href="#">Webhooks</a></li>
                                    <li><a href="#">Integrations</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="uk-parent">
                <a href="#">Your Streams</a>
                <ul class="uk-nav-sub">
                    <li class="uk-parent">
                        <a href="#">Live Streams</a>
                        <ul class="uk-nav-sub">
                            <li><a href="streams.html">Active Streams</a></li>
                            <li><a href="#">Inactive Streams</a></li>
                        </ul>
                    </li>
                    <li class="uk-parent">
                        <a href="#">Series Streams</a>
                        <ul class="uk-nav-sub">
                            <li><a href="#">Active Series</a></li>
                            <li><a href="#">Inactive Series</a></li>
                        </ul>
                    </li>
                    <li><a href="#">Export Playlist</a></li>
                    <li><a href="#">Other Streams</a></li>
                    <li><a href="#">Missing Streams</a></li>
                </ul>
            </li>
            <li class="uk-parent">
                <a href="#">Your Account</a>
                <ul class="uk-nav-sub">
                    <li><a href="#">Change Password</a></li>
                    <li><a href="#">Logout</a></li>
                    <li class="uk-parent">
                        <a href="#">User Management</a>
                        <ul class="uk-nav-sub">
                            <li><a href="#">All Users</a></li>
                            <li><a href="#">Add User</a></li>
                            <li><a href="#">Roles & Permissions</a></li>
                        </ul>
                    </li>
                </ul>
            </li>
            <li class="uk-parent">
                <a href="#">Admin</a>
                <ul class="uk-nav-sub">
                    <li><a href="#">Your Providers</a></li>
                    <li><a href="#">Your Filters</a></li>
                    <li class="uk-parent">
                        <a href="#">Settings</a>
                        <ul class="uk-nav-sub">
                            <li><a href="#">General</a></li>
                            <li><a href="#">Appearance</a></li>
                            <li><a href="#">Cache</a></li>
                            <li class="uk-parent">
                                <a href="#">Advanced</a>
                                <ul class="uk-nav-sub">
                                    <li><a href="#">Database</a></li>
                                    <li><a href="#">Logs</a></li>
                                    <li><a href="#">Debug</a></li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                </ul>
            </li>
        </ul>
    </div>
</div>