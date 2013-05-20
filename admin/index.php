<?php

    /**
     *	Admin module
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

    // Initialize session data
    session_start();

    // Start system timer
    $start_time = microtime(true);

    // Set admin path true
    define('TEMPLATE_CMS_ACCESS',true);

    // Admin access true
    $admin = true;

    // Include engine core
    include '../template_cms/Core.php';


    // Errors var when users login failed
    $login_error = '';

    // Admin login
    if (isset($_POST['login_submit'])) {        
        // Sleep TEMPLATE_CMS_LOGIN_SLEEP seconds for blocking Brute Force Attacks
        sleep(TEMPLATE_CMS_LOGIN_SLEEP);
        // Convert html from $_POST to plain text.
        htmlPostText();        
        // Get users database
        $user_xml_db = getXMLdb('../data/users/users.xml');
        $user = selectXMLRecord($user_xml_db, "/root/user[login='".trim(post('login'))."']");
        if ($user !== null) {
            if ($user->login == post('login')) {
                if (trim($user->password) == encryptPassword(post('password'))) {
                    if ($user->role == 'admin') {
                        $_SESSION['admin']      = true;
                        $_SESSION['user_id']    = (int)$user['id'];
                        $_SESSION['user_login'] = (string)$user->login;
                        $_SESSION['user_role']  = (string)$user->role;
                        redirect('index.php');
                    }
                } else {
                    $login_error = lang('users_login_error');
                }
            } 
        } else {
            $login_error = lang('users_login_error');
        }
    }

    // If admin user is login = true then set is_admin = true
    if (isset($_SESSION['admin'])) {
        if ($_SESSION['admin'] == true) {
            $is_admin = true;
        }
    } else {
        $is_admin = false;
    }

    // Logout user from system
    if (isGet('logout')) {
        if (get('logout') == 'do') {
            session_destroy();
        }
    }

    // If is admin then load admin area
    if ($is_admin) {        
        // If id is empty then redirect to default plugin PAGES
        if (isGet('id')) {            
            if (isGet('sub_id')) {
                $area = get('sub_id');
            } else {
                $area = get('id');
            }
        } else {
            redirect('index.php?id=pages');
        }

        $plugins_registered = getPluginInfo();
        foreach ($plugins_registered as $plugin) {
            $plugins_registered_areas[] = $plugin['id'];
        }

        // Show plugins admin area only for registered plugins
        if (in_array($area, $plugins_registered_areas)) {
            $plugin_admin_area = true;
        } else {
            $plugin_admin_area = false;
        }

        runHook('admin_pre_render');

        // Display admin template
        include 'templates/AdminTemplate.php';

        runHook('admin_post_render');

    } else {
        
        // Display login template
        include 'templates/LoginTemplate.php';

    }
    
    // Flush (send) the output buffer and turn off output buffering
    ob_end_flush();