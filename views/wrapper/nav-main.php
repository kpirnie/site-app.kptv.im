<!-- Navigation -->
<nav class="kptv-navbar">
    <div class="kptv-navbar-container">
        <div uk-navbar>
            <!-- Logo -->
            <div class="uk-navbar-left">
                <a href="index.html" class="uk-navbar-item kptv-logo">
                    <img src="/assets/images/kptv-logo.png" alt="KPTV Logo">
                    <span class="kptv-logo-text uk-visible@s">Stream Manager</span>
                </a>
            </div>
            
            <!-- Desktop Navigation -->
            <div class="uk-navbar-right uk-visible@m">
                <ul class="uk-navbar-nav kptv-nav">
                    <li class="uk-active"><a href="index.html">Home</a></li>
                    <li>
                        <a href="#">Info <span uk-navbar-parent-icon></span></a>
                        <div class="uk-navbar-dropdown">
                            <ul class="uk-nav uk-navbar-dropdown-nav uk-nav-parent-icon">
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
                        </div>
                    </li>
                    <li>
                        <a href="#">Your Streams <span uk-navbar-parent-icon></span></a>
                        <div class="uk-navbar-dropdown">
                            <ul class="uk-nav uk-navbar-dropdown-nav uk-nav-parent-icon">
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
                        </div>
                    </li>
                    <li>
                        <a href="#">Your Account <span uk-navbar-parent-icon></span></a>
                        <div class="uk-navbar-dropdown">
                            <ul class="uk-nav uk-navbar-dropdown-nav uk-nav-parent-icon">
                                <li><a href="#">Change Password</a></li>
                                <li><a href="#">Logout</a></li>
                                <li class="uk-nav-divider"></li>
                                <li class="uk-parent">
                                    <a href="#">User Management</a>
                                    <ul class="uk-nav-sub">
                                        <li><a href="#">All Users</a></li>
                                        <li><a href="#">Add User</a></li>
                                        <li><a href="#">Roles & Permissions</a></li>
                                    </ul>
                                </li>
                            </ul>
                        </div>
                    </li>
                    <li>
                        <a href="#">Admin <span uk-navbar-parent-icon></span></a>
                        <div class="uk-navbar-dropdown">
                            <ul class="uk-nav uk-navbar-dropdown-nav uk-nav-parent-icon">
                                <li><a href="#">Your Providers</a></li>
                                <li><a href="#">Your Filters</a></li>
                                <li class="uk-nav-divider"></li>
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
                        </div>
                    </li>
                </ul>
            </div>
            
            <!-- Mobile Toggle -->
            <div class="uk-navbar-right uk-hidden@m">
                <a class="uk-navbar-toggle kptv-navbar-toggle" uk-toggle="target: #kptv-mobile-nav">
                    <svg xmlns="http://www.w3.org/2000/svg" width="28" height="28" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                        <line x1="3" y1="12" x2="21" y2="12"></line>
                        <line x1="3" y1="6" x2="21" y2="6"></line>
                        <line x1="3" y1="18" x2="21" y2="18"></line>
                    </svg>
                </a>
            </div>
        </div>
    </div>
</nav>
