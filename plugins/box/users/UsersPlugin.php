<?php

    /**
     *	Users plugin
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
                   '<a href="index.php?id=system&sub_id=users">Users</a>|box',
                   '1.0',
                   'Users plugin',
                   'Awilum',
                   'http://awilum.webdevart.ru/',
                   'usersAdmin');
				
    // Include language file for this plugin
    getPluginLanguage('Users', 'box');
	
    // Include Users Admin
    getPluginAdmin('Users', 'box');