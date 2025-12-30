<?php
/**
 * views/home.php
 * 
 * No direct access allowed!
 * 
 * @since 8.3
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package KP Library
 * 
 */

// define the primary app path if not already defined
defined( 'KPTV_PATH' ) || die( 'Direct Access is not allowed!' );

use KPT\KPT;
use KPT\Cache;

// pull in the header
KPTV::pull_header( );

?>

<!-- Page Header -->
<div class="uk-margin-bottom">
    <h1 class="kptv-heading kptv-page-title-lg"><span uk-icon="icon: thumbnails; ratio: 1.25"></span> Dashboard</h1>
    <p class="kptv-text-gray kptv-mb-0">Welcome back to KPTV Stream Manager</p>
</div>

<!-- Stats Grid -->
<div uk-grid class="uk-grid-small uk-child-width-1-2 uk-child-width-1-4@m uk-margin-bottom">
    <div>
        <div class="kptv-card kptv-stat-card">
            <div class="kptv-stat-value">7,320</div>
            <div class="kptv-stat-label">Total Streams</div>
        </div>
    </div>
    <div>
        <div class="kptv-card kptv-stat-card success">
            <div class="kptv-stat-value">5,847</div>
            <div class="kptv-stat-label">Active</div>
        </div>
    </div>
    <div>
        <div class="kptv-card kptv-stat-card danger">
            <div class="kptv-stat-value">1,473</div>
            <div class="kptv-stat-label">Inactive</div>
        </div>
    </div>
    <div>
        <div class="kptv-card kptv-stat-card warning">
            <div class="kptv-stat-value">3</div>
            <div class="kptv-stat-label">Providers</div>
        </div>
    </div>
</div>

<!-- Two Column Layout -->
<div uk-grid class="uk-grid-small uk-child-width-1-1 uk-child-width-1-2@m uk-margin-bottom">
    
    <!-- Providers Card -->
    <div>
        <div class="kptv-card">
            <div class="kptv-card-title">
                <span uk-icon="server"></span>
                Your Providers
            </div>
            
            <ul class="uk-list uk-list-divider kptv-list-divided">
                <li>
                    <div class="uk-flex uk-flex-between uk-flex-middle">
                        <span>Prada/Infinity</span>
                        <span class="kptv-badge kptv-badge-success">Online</span>
                    </div>
                </li>
                <li>
                    <div class="uk-flex uk-flex-between uk-flex-middle">
                        <span>Mine</span>
                        <span class="kptv-badge kptv-badge-success">Online</span>
                    </div>
                </li>
                <li>
                    <div class="uk-flex uk-flex-between uk-flex-middle">
                        <span>Backup Provider</span>
                        <span class="kptv-badge kptv-badge-warning">Standby</span>
                    </div>
                </li>
            </ul>
            
        </div>
    </div>
    
    <!-- System Info Card -->
    <div>
        <div class="kptv-card">
            <div class="kptv-card-title">
                <span uk-icon="info"></span>
                System Info
            </div>
            
            <ul class="uk-list kptv-list-info">
                <li>
                    <span class="kptv-text-gray">Version:</span> 
                    <span class="kptv-text-cyan">2.1.0</span>
                </li>
                <li>
                    <span class="kptv-text-gray">Last Sync:</span> 
                    <span class="kptv-text-cyan">2 hours ago</span>
                </li>
                <li>
                    <span class="kptv-text-gray">Cache Status:</span> 
                    <span class="kptv-text-success">Healthy</span>
                </li>
                <li>
                    <span class="kptv-text-gray">Database:</span> 
                    <span class="kptv-text-success">Connected</span>
                </li>
            </ul>
        </div>
    </div>
</div>

<!-- Page Header -->
<div class="uk-flex uk-flex-between uk-flex-middle uk-flex-wrap kptv-page-header">
    <div>
        <h1 class="kptv-heading kptv-page-title">Active Streams</h1>
        <p class="kptv-text-gray kptv-mb-0">Manage your active IPTV streams</p>
    </div>
    <div>
        <a href="#" class="kptv-btn kptv-btn-primary">
            <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                stroke="currentColor" stroke-width="2">
                <line x1="12" y1="5" x2="12" y2="19"></line>
                <line x1="5" y1="12" x2="19" y2="12"></line>
            </svg>
            Add Stream
        </a>
    </div>
</div>

<!-- Filters Card -->
<div class="kptv-card">
    <div uk-grid class="uk-grid-small uk-flex-middle">
        <div class="uk-width-1-1 uk-width-1-3@m">
            <div class="kptv-search">
                <svg class="kptv-search-icon" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24"
                    fill="none" stroke="currentColor" stroke-width="2">
                    <circle cx="11" cy="11" r="8"></circle>
                    <line x1="21" y1="21" x2="16.65" y2="16.65"></line>
                </svg>
                <input type="text" class="kptv-input" placeholder="Search streams..."
                    data-target=".streams-table">
            </div>
        </div>
        <div class="uk-width-1-1 uk-width-auto@m">
            <div class="kptv-per-page">
                <span class="kptv-text-gray kptv-text-sm">Per Page:</span>
                <button class="kptv-per-page-btn active" data-perpage="25">25</button>
                <button class="kptv-per-page-btn" data-perpage="50">50</button>
                <button class="kptv-per-page-btn" data-perpage="100">100</button>
                <button class="kptv-per-page-btn" data-perpage="250">250</button>
                <button class="kptv-per-page-btn" data-perpage="all">ALL</button>
            </div>
        </div>
        <div class="uk-width-expand@m uk-text-right">
            <span class="kptv-record-count kptv-text-gray kptv-text-sm">Showing 1 to 25 of 7320
                records</span>
        </div>
    </div>
</div>

<!-- Streams Table -->
<div class="kptv-card">
    <div class="kptv-table-responsive">
        <table class="kptv-table streams-table">
            <thead>
                <tr>
                    <th class="col-checkbox"><input type="checkbox" class="uk-checkbox"></th>
                    <th class="col-act">ACT</th>
                    <th class="col-ch" data-sort="ch">CH</th>
                    <th data-sort="name">NAME</th>
                    <th class="uk-visible@m">ORIG. NAME</th>
                    <th class="uk-visible@l">TVG ID</th>
                    <th data-sort="provider">PROVIDER</th>
                    <th class="uk-visible@s col-logo">LOGO</th>
                    <th class="col-actions">ACTIONS</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="checkbox" class="uk-checkbox"></td>
                    <td><span class="kptv-text-danger">✕</span></td>
                    <td>0</td>
                    <td>9</td>
                    <td class="uk-visible@m">9</td>
                    <td class="uk-visible@l">—</td>
                    <td>Prada/Infinity</td>
                    <td class="uk-visible@s"><span class="kptv-text-gray">No image</span></td>
                    <td>
                        <div class="kptv-actions">
                            <a href="#" class="kptv-action-btn" uk-tooltip="Play"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                </svg></a>
                            <a href="#" class="kptv-action-btn" uk-tooltip="Copy URL"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path
                                        d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71">
                                    </path>
                                    <path
                                        d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71">
                                    </path>
                                </svg></a>
                            <a href="#" class="kptv-action-btn" uk-tooltip="EPG"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                    <line x1="8" y1="21" x2="16" y2="21"></line>
                                    <line x1="12" y1="17" x2="12" y2="21"></line>
                                </svg></a>
                            <a href="#" class="kptv-action-btn" uk-tooltip="Settings"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="3"></circle>
                                    <path
                                        d="M19.4 15a1.65 1.65 0 0 0 .33 1.82l.06.06a2 2 0 0 1 0 2.83 2 2 0 0 1-2.83 0l-.06-.06a1.65 1.65 0 0 0-1.82-.33 1.65 1.65 0 0 0-1 1.51V21a2 2 0 0 1-2 2 2 2 0 0 1-2-2v-.09A1.65 1.65 0 0 0 9 19.4a1.65 1.65 0 0 0-1.82.33l-.06.06a2 2 0 0 1-2.83 0 2 2 0 0 1 0-2.83l.06-.06a1.65 1.65 0 0 0 .33-1.82 1.65 1.65 0 0 0-1.51-1H3a2 2 0 0 1-2-2 2 2 0 0 1 2-2h.09A1.65 1.65 0 0 0 4.6 9a1.65 1.65 0 0 0-.33-1.82l-.06-.06a2 2 0 0 1 0-2.83 2 2 0 0 1 2.83 0l.06.06a1.65 1.65 0 0 0 1.82.33H9a1.65 1.65 0 0 0 1-1.51V3a2 2 0 0 1 2-2 2 2 0 0 1 2 2v.09a1.65 1.65 0 0 0 1 1.51 1.65 1.65 0 0 0 1.82-.33l.06-.06a2 2 0 0 1 2.83 0 2 2 0 0 1 0 2.83l-.06.06a1.65 1.65 0 0 0-.33 1.82V9a1.65 1.65 0 0 0 1.51 1H21a2 2 0 0 1 2 2 2 2 0 0 1-2 2h-.09a1.65 1.65 0 0 0-1.51 1z">
                                    </path>
                                </svg></a>
                            <a href="#" class="kptv-action-btn" uk-tooltip="Edit"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path
                                        d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                    </path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                    </path>
                                </svg></a>
                            <a href="#" class="kptv-action-btn danger" uk-tooltip="Delete"
                                data-confirm="Are you sure you want to delete this stream?"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path
                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                    </path>
                                </svg></a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="uk-checkbox"></td>
                    <td><span class="kptv-text-danger">✕</span></td>
                    <td>0</td>
                    <td>Baddies USA</td>
                    <td class="uk-visible@m">Baddies USA</td>
                    <td class="uk-visible@l">—</td>
                    <td>Prada/Infinity</td>
                    <td class="uk-visible@s"><img src="https://via.placeholder.com/40"
                            class="kptv-logo-thumb" alt=""></td>
                    <td>
                        <div class="kptv-actions">
                            <a href="#" class="kptv-action-btn" uk-tooltip="Play"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                </svg></a>
                            <a href="#" class="kptv-action-btn" uk-tooltip="Copy URL"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path
                                        d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71">
                                    </path>
                                    <path
                                        d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71">
                                    </path>
                                </svg></a>
                            <a href="#" class="kptv-action-btn" uk-tooltip="EPG"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                    <line x1="8" y1="21" x2="16" y2="21"></line>
                                    <line x1="12" y1="17" x2="12" y2="21"></line>
                                </svg></a>
                            <a href="#" class="kptv-action-btn" uk-tooltip="Settings"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg></a>
                            <a href="#" class="kptv-action-btn" uk-tooltip="Edit"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path
                                        d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                    </path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                    </path>
                                </svg></a>
                            <a href="#" class="kptv-action-btn danger" uk-tooltip="Delete"
                                data-confirm="Delete?"><svg xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path
                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                    </path>
                                </svg></a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="uk-checkbox"></td>
                    <td><span class="kptv-text-danger">✕</span></td>
                    <td>0</td>
                    <td>EN| I Am Not Okay with This</td>
                    <td class="uk-visible@m">EN| I Am Not Okay with This</td>
                    <td class="uk-visible@l">—</td>
                    <td>Mine</td>
                    <td class="uk-visible@s"><img src="https://via.placeholder.com/40"
                            class="kptv-logo-thumb" alt=""></td>
                    <td>
                        <div class="kptv-actions">
                            <a href="#" class="kptv-action-btn" uk-tooltip="Play"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                </svg></a>
                            <a href="#" class="kptv-action-btn" uk-tooltip="Copy URL"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path
                                        d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71">
                                    </path>
                                    <path
                                        d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71">
                                    </path>
                                </svg></a>
                            <a href="#" class="kptv-action-btn" uk-tooltip="EPG"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                    <line x1="8" y1="21" x2="16" y2="21"></line>
                                    <line x1="12" y1="17" x2="12" y2="21"></line>
                                </svg></a>
                            <a href="#" class="kptv-action-btn" uk-tooltip="Settings"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg></a>
                            <a href="#" class="kptv-action-btn" uk-tooltip="Edit"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path
                                        d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                    </path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                    </path>
                                </svg></a>
                            <a href="#" class="kptv-action-btn danger" uk-tooltip="Delete"
                                data-confirm="Delete?"><svg xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path
                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                    </path>
                                </svg></a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="uk-checkbox"></td>
                    <td><span class="kptv-text-danger">✕</span></td>
                    <td>0</td>
                    <td>EN| I Dream of Jeannie</td>
                    <td class="uk-visible@m">EN| I Dream of Jeannie</td>
                    <td class="uk-visible@l">—</td>
                    <td>Mine</td>
                    <td class="uk-visible@s"><span class="kptv-text-gray">Image</span></td>
                    <td>
                        <div class="kptv-actions">
                            <a href="#" class="kptv-action-btn" uk-tooltip="Play"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                </svg></a>
                            <a href="#" class="kptv-action-btn" uk-tooltip="Copy URL"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path
                                        d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71">
                                    </path>
                                    <path
                                        d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71">
                                    </path>
                                </svg></a>
                            <a href="#" class="kptv-action-btn" uk-tooltip="EPG"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                    <line x1="8" y1="21" x2="16" y2="21"></line>
                                    <line x1="12" y1="17" x2="12" y2="21"></line>
                                </svg></a>
                            <a href="#" class="kptv-action-btn" uk-tooltip="Settings"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg></a>
                            <a href="#" class="kptv-action-btn" uk-tooltip="Edit"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path
                                        d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                    </path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                    </path>
                                </svg></a>
                            <a href="#" class="kptv-action-btn danger" uk-tooltip="Delete"
                                data-confirm="Delete?"><svg xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path
                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                    </path>
                                </svg></a>
                        </div>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox" class="uk-checkbox"></td>
                    <td><span class="kptv-text-danger">✕</span></td>
                    <td>0</td>
                    <td>EN| I Hate Suzie</td>
                    <td class="uk-visible@m">EN| I Hate Suzie</td>
                    <td class="uk-visible@l">—</td>
                    <td>Mine</td>
                    <td class="uk-visible@s"><img src="https://via.placeholder.com/40"
                            class="kptv-logo-thumb" alt=""></td>
                    <td>
                        <div class="kptv-actions">
                            <a href="#" class="kptv-action-btn" uk-tooltip="Play"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <polygon points="5 3 19 12 5 21 5 3"></polygon>
                                </svg></a>
                            <a href="#" class="kptv-action-btn" uk-tooltip="Copy URL"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path
                                        d="M10 13a5 5 0 0 0 7.54.54l3-3a5 5 0 0 0-7.07-7.07l-1.72 1.71">
                                    </path>
                                    <path
                                        d="M14 11a5 5 0 0 0-7.54-.54l-3 3a5 5 0 0 0 7.07 7.07l1.71-1.71">
                                    </path>
                                </svg></a>
                            <a href="#" class="kptv-action-btn" uk-tooltip="EPG"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <rect x="2" y="3" width="20" height="14" rx="2" ry="2"></rect>
                                    <line x1="8" y1="21" x2="16" y2="21"></line>
                                    <line x1="12" y1="17" x2="12" y2="21"></line>
                                </svg></a>
                            <a href="#" class="kptv-action-btn" uk-tooltip="Settings"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <circle cx="12" cy="12" r="3"></circle>
                                </svg></a>
                            <a href="#" class="kptv-action-btn" uk-tooltip="Edit"><svg
                                    xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="none"
                                    stroke="currentColor" stroke-width="2">
                                    <path
                                        d="M11 4H4a2 2 0 0 0-2 2v14a2 2 0 0 0 2 2h14a2 2 0 0 0 2-2v-7">
                                    </path>
                                    <path d="M18.5 2.5a2.121 2.121 0 0 1 3 3L12 15l-4 1 1-4 9.5-9.5z">
                                    </path>
                                </svg></a>
                            <a href="#" class="kptv-action-btn danger" uk-tooltip="Delete"
                                data-confirm="Delete?"><svg xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 24 24" fill="none" stroke="currentColor"
                                    stroke-width="2">
                                    <polyline points="3 6 5 6 21 6"></polyline>
                                    <path
                                        d="M19 6v14a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2V6m3 0V4a2 2 0 0 1 2-2h4a2 2 0 0 1 2 2v2">
                                    </path>
                                </svg></a>
                        </div>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>

    <!-- Pagination -->
    <div
        class="uk-flex uk-flex-between uk-flex-middle uk-flex-wrap uk-margin-top kptv-pagination-wrapper">
        <div class="kptv-pagination">
            <a href="#">«</a>
            <a href="#">‹</a>
            <span class="active">1</span>
            <a href="#">2</a>
            <a href="#">...</a>
            <a href="#">293</a>
            <a href="#">›</a>
            <a href="#">»</a>
        </div>
    </div>
</div>

<?php

// pull in the footer
KPTV::pull_footer( );
