<?php  if (!defined('TEMPLATE_CMS_ACCESS')) exit('No direct script access allowed'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
<head>
    <title>Template CMS :: Admin</title>
    <meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />    
    <meta name="description" content="Template CMS admin area" />
    <link rel="icon" href="<?php getSiteUrl(); ?>favicon.ico" type="image/x-icon" />
    <link rel="shortcut icon" href="<?php getSiteUrl(); ?>favicon.ico" type="image/x-icon" />
    <link rel="stylesheet" href="templates/css/style.css" media="all" type="text/css" />
    <script type="text/javascript" src="templates/js/jquery.js"></script>
    <script type="text/javascript" src="templates/js/template-cms.js"></script>
    <?php runHook('admin_header'); ?>
</head>
<body>
    <!-- Block_wrapper -->
    <div id="header-inside-buttons">

        <!-- Top navigation for third party plugins -->
        <?php runHook('admin_top_navigation'); ?>
        <!-- / -->

        <!-- Top navigation for system plugin only -->
        <?php runHook('admin_top_system_navigation'); ?>
        <!-- / --> 
       
    </div>    
    <!-- Block_header -->
    <div id="header">
        <div id="header-inside">

            <!-- Block_logo -->
            <div id="logo">
                <img src="templates/img/tcms2.png" alt="" />
            </div>
            <!-- /Block_logo -->

            <!-- Block_menu -->
            <ul id="menu" >
                <?php runHook('admin_main_navigation'); ?>
            </ul>
            <!-- /Block_menu -->

            <!-- Block_sub_menu -->
            <ul id="sub-menu">
                <?php runHook('admin_'.get('id').'_second_navigation'); ?>
            </ul>
            <!-- /Block_sub_menu -->

        </div>
    </div>
    <!-- /Block_header -->

    <div style="clear:both;"></div>

    <div>
        <?php runHook('admin_pre_template'); ?>
    </div>

    <!-- Block_content -->
    <div id="content">
        <?php
            if ($plugin_admin_area) {
                call_user_func_array($plugins_info[$area]['admin_data'],array());
            } else {
                echo '<div class="message-error">'.lang('plugins_does_not_exist').'</div>';
            }
        ?>
    </div>
    <!-- /Block_content -->

    <div>
        <?php runHook('admin_post_template'); ?>
    </div>

    <!-- Block_footer -->
    <div id="footer">
        <?php getCopyright(); ?>
        <!-- <?php getElapsedTime(); ?> -->
        <!-- <?php getMemoryUsage(); ?> -->
        <?php runHook('admin_footer'); ?>
    </div>
    <!-- Block_footer -->

    <!-- /Block_wrapper -->
</body>
</html>