<?php

    // Check if is user is logged in then set variables for welcome button
    if (isset($_SESSION['user_id'])) {
        $user_id = $_SESSION['user_id'];
        $user_login = $_SESSION['user_login'];
    } else {
        $user_id = '';
        $user_login = '';
    }

    // Add navigation hook
    addHook('admin_top_navigation', 'adminTopNavigation', array('system&sub_id=users&action=edit&user_id='.$user_id, lang('users_welcome').', <b>'.$user_login.'</b>'));        
    addHook('admin_system_second_navigation','adminSecondNavigation',array('system',lang('users_submenu'),'users'));

    /**
     * Users admin
     */
    function usersAdmin() {

        // Users roles
        $roles = array('admin' => lang('users_role_admin'),
                       'user'  => lang('users_role_user'));

        // Get users xml database
        $users_xml_db = getXMLdb('../data/users/users.xml');

        // Check for get actions
        // ---------------------------------------------
        if (isGet('action')) {

            // Switch get actions
            // ---------------------------------------------
            switch (get('action')) {

                // Add new user
                // ---------------------------------------------
                case "add":
                    $errors = array();
                    if (isPost('register')) {
                        $user_login = trim(post('login'));
                        $user_password = trim(post('password'));
                        if ($user_login == '')    $errors['users_empty_login']    = lang('users_empty_field');
                        if ($user_password == '') $errors['users_empty_password'] = lang('users_empty_field');
                        $user = selectXMLRecord($users_xml_db, "/root/user[login='".$user_login."']");
                        if ($user != null) $errors['users_this_user_alredy_exists'] = lang('users_this_user_alredy_exists');
                        
                        if (count($errors) == 0) {
                            insertXMLRecord($users_xml_db, 'user', array('login'    => safeName($user_login),
                                                                         'password' => encryptPassword(post('password')),
                                                                         'email'    => post('email'),
                                                                         'date_registered'=>time(),
                                                                         'role'     => post('role')));
                            redirect('index.php?id=system&sub_id=users');
                        }

                    }
                    include 'templates/backend/UsersAddTemplate.php';
                break;

                // Edit user
                // ---------------------------------------------
                case "edit":
                    // Get current user record
                    $user = selectXMLRecord($users_xml_db, "//user[@id='".get('user_id')."']");

                    if (isPost('edit_profile')) {
                        if (safeName(post('login')) != '') {                                       
                            updateXMLRecord($users_xml_db, 'user', post('user_id'), array('login'     => safeName(post('login')),
                                                                                          'firstname' => post('firstname'),
                                                                                          'lastname'  => post('lastname'),
                                                                                          'email'     => post('email'),
                                                                                          'icq'       => post('icq'),
                                                                                          'facebook'  => post('facebook'),
                                                                                          'vkontakte' => post('vkontakte'),
                                                                                          'skype'     => post('skype'),
                                                                                          'twitter'   => post('twitter'),
                                                                                          'role'      => post('role')));
                            flashMessage(lang('users_changes_are_saved'));
                        } else {
                            flashMessage(lang('users_empty_login'),'error');
                        }                        
                    }


                    if (isPost('edit_profile_password')) {
                        if (encryptPassword(post('old_password')) == post('real_old_password')) {
                            htmlPostText();
                            updateXMLRecord($users_xml_db, 'user', post('user_id'), array('password'=>encryptPassword(post('new_password'))));
                            flashMessage(lang('users_new_password_saved'));
                        } else {
                            flashMessage(lang('users_wrong_old_password'),'error');
                        }
                    }

                    include 'templates/backend/UsersEditTemplate.php';
                break;

                // Delete user
                // ---------------------------------------------
                case "delete":
                    deleteXMLRecord($users_xml_db, 'user', get('user_id'));
                    redirect('index.php?id=system&sub_id=users');
                break;
            }
        } else {
            // Get all records from users xml database
            $users = selectXMLRecord($users_xml_db, "//user",'all');
            // Include template
            include 'templates/backend/UsersTemplate.php';
        }
        
    }