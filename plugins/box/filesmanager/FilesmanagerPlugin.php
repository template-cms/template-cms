<?php

    /**
     *	Files manager plugin
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
                    '<a href="index.php?id=pages&sub_id=filesmanager">FilesManager</a>|box',
                    '1.0',
                    'Simple file manger for TemplateCMS :)',
                    'Awilum',
                    'http://awilum.webdevart.ru/',
                    'filesmanagerAdmin');

 
    // Get language file for this plugin
    getPluginLanguage('Filesmanager', 'box');

    // Include Admin
    getPluginAdmin('Filesmanager', 'box');