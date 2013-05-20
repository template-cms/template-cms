<?php

    /**
     *	Plugins manger plugin
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 - 2012 Romanenko Sergey / Awilum
     *	@version 1.0
     *
     */


    // Register plugin
    registerPlugin(getPluginId(__FILE__),
                   getPluginFilename(__FILE__),
                   '<a href="index.php?id=plugins">Plugins</a>|box',
                   '1.0',
                   'Plugins manager plugin :)',
                   'Awilum',
                   'http://awilum.webdevart.ru/',
                   'pluginsAdmin');


    // Get language file for this plugin
    getPluginLanguage('Plugins', 'box');
    
    // Add hooks
    addHook('admin_main_navigation','adminNavigation',array('plugins',lang('plugins_menu')));
    addHook('admin_plugins_second_navigation','adminSecondNavigation',array('plugins',lang('plugins_submenu')));
	
    // Include Admin
    getPluginAdmin('Plugins', 'box');