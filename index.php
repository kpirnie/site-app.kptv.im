<?php
/**
 * index.php
 * 
 * this is our route processor
 * for the entire application
 * 
 * @since 8.4
 * @author Kevin Pirnie <me@kpirnie.com>
 * @package KP Library
 * 
 */

// now include our main class
require_once __DIR__ . '/controllers/main.php';
exit;




include 'views/wrapper/header.php';

?>
<!-- Main Layout with Sidebar -->
<div uk-grid class="uk-grid-collapse uk-flex-1" uk-height-viewport="expand: true">
    
    <!-- Sidebar -->
    <?php
        include 'views/wrapper/sidebar.php';
    ?>

    <!-- Main Content -->
    <div class="uk-width-expand">
        <main class="kptv-main">

            <!-- home -->
            <?php
                include 'views/pages/home.php';
            ?>

        </main>
    </div>
</div>
<?php

include 'views/wrapper/footer.php';
