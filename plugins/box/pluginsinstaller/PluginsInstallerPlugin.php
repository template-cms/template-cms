<?php

    /**
     *	Plugin installer plugin
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
                   '<a href="index.php?id=plugins&sub_id=pluginsinstaller">PluginsInstaller</a>|box',
                   '1.0',
                   'Plugins installer plugin',
                   'Awilum',
                   'http://awilum.webdevart.ru/',
                   'pluginsInstallerAdmin');
				
    // Include language file for this plugin
    getPluginLanguage('PluginsInstaller', 'box');
	
    // Add hooks NAVIGATION
    addHook('admin_plugins_second_navigation','adminSecondNavigation',array('plugins',lang('plugins_installer_submenu'),'pluginsinstaller'));
	
    // Include Plugi installer Admin
    getPluginAdmin('PluginsInstaller', 'box');