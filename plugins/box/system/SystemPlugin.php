<?php

    /**
     *	System plugin
     *	@package TemplateCMS
     *  @subpackage Plugins
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 - 2012 Romanenko Sergey / Awilum
     *	@version 1.0
     *
     */


    // Register plugin
    registerPlugin( getPluginId(__FILE__),
                    getPluginFilename(__FILE__),
                    '<a href="index.php?id=system">System</a>|box',
                    '1.0',			
                    'TemplateCMS System plugin',
                    'Awilum',			
                    'http://awilum.webdevart.ru/',		
                    'systemAdmin');	  	        


    // Get language file for this plugin
    getPluginLanguage('System', 'box');

    // Include Admin
    getPluginAdmin('System', 'box');