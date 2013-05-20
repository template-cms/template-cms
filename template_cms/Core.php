<?php  if (!defined('TEMPLATE_CMS_ACCESS')) exit('No direct script access allowed');

    /**
     *	Template CMS - Content Management System. [template-cms.ru]
     *	Copyright (C) 2011 Romanenko Sergey / Awilum [awilum@msn.com]
     *
     *  Site: [template-cms.ru] and [template-cms.org]
     * 
     *	Main Template CMS engine module. Core module.
     *
     *	@package TemplateCMS
     *	@author Romanenko Sergey / Awilum
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


    // If Admin ask engine then create path for admin
    if (isset($admin)) { if ($admin) $admin_path = '../'; else $admin_path = ''; } else { $admin_path = ''; }

    
    // Include common engine config file
    include 'config/Common.php';   
    
    // Template CMS requires PHP 5.2.0 or greater
    if (version_compare(PHP_VERSION, "5.2.0", "<")) {
        exit("Template CMS requires PHP 5.2.0 or greater.");
    }   

    // Setting error display depending on debug mode or not
    if (TEMPLATE_CMS_DEBUG) {
        // If PHP version >= 5.3.1 then also E_DEPRECATED
        if ((version_compare(PHP_VERSION, "5.3.1", ">="))){
            error_reporting(E_ALL | E_NOTICE | E_DEPRECATED);
        } else {
            error_reporting(E_ALL ^ E_NOTICE);
        }
    } else {
        // Turn off all error reporting
        error_reporting(0);    
    }
    

    // Compress HTML with gzip
    if (TEMPLATE_CMS_GZIP) {
        if ( ! ob_start("ob_gzhandler")) ob_start();
    } else {
        ob_start();
    }

    // Send default header and set internal encoding
    header('Content-Type: text/html; charset=UTF-8');    
    function_exists('mb_language') AND mb_language('uni');
    function_exists('mb_regex_encoding') AND mb_regex_encoding('UTF-8');
    function_exists('mb_internal_encoding') AND mb_internal_encoding('UTF-8');

    // Include security module
    include TEMPLATE_CMS_ENGINE_PATH.'Security.php'; 

    // Include filesystem module
    include TEMPLATE_CMS_ENGINE_PATH.'Filesystem.php';

    // Include ZIP module
    if ($admin) include TEMPLATE_CMS_ENGINE_PATH.'Zip.php';
    
    // Include XML DB API
    include TEMPLATE_CMS_ENGINE_PATH.'XML.php';    

    // Include system preload module
    include TEMPLATE_CMS_ENGINE_PATH.'Preload.php';

    // Include Helpers module
    include TEMPLATE_CMS_ENGINE_PATH.'Helpers.php';

    // Init helpers
    initHelpers(TEMPLATE_CMS_HELPERS_PATH);

    // Sanitize URL to prevent XSS - Cross-site scripting
    runSanitizeURL();

    // Include Plugin API
    include TEMPLATE_CMS_ENGINE_PATH.'Plugins.php';
   
    // Include Shortcodes API
    include TEMPLATE_CMS_ENGINE_PATH.'Shortcodes.php';

    // Include Plugins default filters
    include $admin_path.'template_cms/config/PluginsFilters.php';

    // Include Plugins default hooks
    include $admin_path.'template_cms/config/PluginsHooks.php';

    // Include Uri module
    include TEMPLATE_CMS_ENGINE_PATH.'Uri.php';  

    // Options API module
    include TEMPLATE_CMS_ENGINE_PATH.'Options.php';  

    // Init plugins
    initPlugins($admin_path.TEMPLATE_CMS_DATA_PATH.'plugins/');

    // Include Templates module.
    include TEMPLATE_CMS_ENGINE_PATH.'Templates.php';