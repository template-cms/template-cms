<?php

    /**
     *	Themes plugin
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
                   '<a href="index.php?id=themes">Themes</a>|box',
                   '1.0',          
                   'Themes managment plugin',
                   'Awilum',           
                   'http://awilum.webdevart.ru/',
                   'themesAdmin');    


    // Get language file for this plugin
    getPluginLanguage('Themes', 'box');

    // Include Admin
    getPluginAdmin('Themes', 'box');