<?php

    /**
     *	Backup plugin
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
                   '<a href="index.php?id=system&sub_id=backup">Backup</a>|box',
                   '1.0',            	
                   'Backup plugin',
                   'Awilum',           	
                   'http://awilum.webdevart.ru/',
                   'backupAdmin');

    
    // Get language file for this plugin
    getPluginLanguage('Backup', 'box');

    // Add hooks NAVIGATION
    addHook('admin_system_second_navigation','adminSecondNavigation',array('system',lang('backup_submenu'),'backup'));
    
    // Include Backup Admin
    getPluginAdmin('Backup', 'box');