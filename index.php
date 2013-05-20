<?php

    /**
     *	Main CMS module
     *
     *	@package TemplateCMS 
     *	@author Romanenko Sergey / Awilum [awilum@msn.com]
     *	@copyright 2011 - 2012 Romanenko Sergey / Awilum
     *	@version $Id$
     *	@since 2.0
     *  @license http://opensource.org/licenses/gpl-license.php GNU Public License
     *  TemplateCMS is free software. This version may have been modified pursuant
     *  to the GNU General Public License, and as distributed it includes or
     *  is derivative of works licensed under the GNU General Public License or
     *  other free or open source software licenses.
     *  See COPYING.txt for copyright notices and details.
     */

    // First check for installer then go
    if (file_exists('install.php')) {
        if (isset($_GET['install'])) {
            if ($_GET['install'] == 'done') {
                // Try to delete install file if not delete manually
                @unlink('install.php');
                // Redirect to main page
                header('location: index.php');
            }
        } else {            
            include 'install.php';
        }
    } else {

        // Start system timer
        $start_time = microtime(true);

        // Initialize session 
        session_start();

        // Set admin path false
        $admin = false;

        // Set TemplateCMS access
        define('TEMPLATE_CMS_ACCESS', true);

        // Include engine core
        include 'template_cms/Core.php';       

        // Check for maintenance mod        
        if ('on' == $options_xml['xml_object']->maintenance_status->value) {  
            // Set maintenance mode for all except admin        
            if ( ! isset($_SESSION['admin']) || $_SESSION['admin'] !== true) {
                die(toHtml($options_xml['xml_object']->maintenance_message->value));        
            }
        }        
        
        runHook('frontend_pre_render');

        // Display template
        loadTemplate('themes/'.getSiteTheme(false).'/'.getTemplate().'Template.php');        

        runHook('frontend_post_render');

        // Flush (send) the output buffer and turn off output buffering
        ob_end_flush();
        
    }