<?php

    // Add hooks
    addHook('admin_main_navigation','adminNavigation',array('themes',lang('themes_menu')));
    addHook('admin_themes_second_navigation','adminSecondNavigation',array('themes',lang('themes_submenu')));
    addHook('admin_header','themesAdminHeaders');

    /**
     * Themes plugin admin headers
     */
    function themesAdminHeaders() {
        echo '<style>
                #snippets {
                    margin-left:10px;
                    margin-top: -16px;
                }
                #snippets-toggle {
                    -webkit-border-bottom-right-radius: 5px;
                    -webkit-border-bottom-left-radius: 5px;

                    -moz-border-radius-bottomright: 5px;
                    -moz-border-radius-bottomleft: 5px;

                    border-bottom-right-radius: 5px;
                    border-bottom-left-radius: 5px
                    
                    
                    background:#EEEEEE;
                    border:1px solid #ccc;
                    cursor:pointer;
                    font-size: 0.8em;
                    text-align:center;
                }
                #snippets-toggle:hover {
                    background:#E9EAEA;
                }
                #snippets-box {                    
                    border-left:1px solid #ccc;
                    border-right:1px solid #ccc;
                    display:none;
                    padding-top:15px;
                }
              </style>
              <script>
               $().ready(function() {
                    $("#snippets-toggle").click(function() {                        
                        $("#snippets-box").slideToggle("slow");                        
                    });                    
               });
              </script>';
    }

    /**
     * Themes plugin admin
     */
    function themesAdmin() {

        $current_theme = getSiteTheme(false);

        // Init vsrs
        $themes_folders = array();
        $themes_templates = array();
        $themes_styles = array();

        $menus_list = array();
        $blocks_list = array();

        $menus_path = '../' . TEMPLATE_CMS_DATA_PATH . 'menus/';
        $blocks_path = '../' . TEMPLATE_CMS_DATA_PATH . 'blocks/';
        
        $menus_list = listFiles($menus_path,'.xml');
        $blocks_list = listFiles($blocks_path,'.php');

        $errors = array();
        

        // Add theme option
        addOption(array('theme_name' => 'default'));

        // Save theme
        if (isPost('save_theme')) {
            updateOption('theme_name',post('themes'));
            redirect('index.php?id=themes');
        }

        // Its mean that you can add your own actions for this plugin
        runHook('admin_themes_extra_actions');

        // For CSRF token
        $start_time   = time();
        $token_expire = 600;
        
        // Check for get actions
        // ---------------------------------------------
        if (isGet('action')) {
            
            // Switch actions
            // ---------------------------------------------
            switch (get('action')) {

                // Clone template
                // ---------------------------------------------
                case "clone_template":
                    $rand_name = basename(get('file'),'Template.php').'_t_'.date("Ymd_His");
                    $orig_file = loadFile(TEMPLATE_CMS_THEMES_PATH.$current_theme.'/'.get('file'));                    
                    createFile(TEMPLATE_CMS_THEMES_PATH.$current_theme.'/'.$rand_name.'Template.php', $orig_file);
                    redirect('index.php?id=themes');
                break;

                // Delete css
                // ---------------------------------------------
                case "delete_css":
                    deleteFile(TEMPLATE_CMS_THEMES_PATH.$current_theme.'/css/'.get('file'));
                    redirect('index.php?id=themes');
                break;

                // Delete template
                // ---------------------------------------------
                case "delete_template":
                    deleteFile(TEMPLATE_CMS_THEMES_PATH.$current_theme.'/'.get('file'));
                    redirect('index.php?id=themes');
                break;

                // Add template
                // ---------------------------------------------
                case "add_template":
                    if (isPost('add_template') || isPost('add_template_and_exit')) {
                        if ($_SESSION['token'] != trim(post('token')) || $_SESSION['tk_esp'] < $start_time)  $errors['themes_empty_name'] = 'token error';
                        if (trim(post('themes_editor_name')) == '') $errors['themes_empty_name'] = lang('themes_empty_field');
                        if (file_exists(TEMPLATE_CMS_THEMES_PATH.$current_theme.'/'.safeName(post('themes_editor_name').'Template.php'))) $errors['themes_template_exists'] = lang('themes_template_exists');

                        if (count($errors) == 0) {
                            createFile(TEMPLATE_CMS_THEMES_PATH.$current_theme.'/'.safeName(post('themes_editor_name')).'Template.php', post('themes_editor'));
                            if (isPost('add_template_and_exit')) {
                                redirect('index.php?id=themes');    
                            } else {
                                redirect('index.php?id=themes&action=edit_template&file='.safeName(post('themes_editor_name')).'Template.php');                                    
                            }                
                        }
                    }
                    // Save fields
                    if (isPost('themes_editor_name')) $post_name = post('themes_editor_name'); else $post_name = '';
                    if (isPost('themes_editor')) $post_themes_editor = post('themes_editor'); else $post_themes_editor = '';
                    $_SESSION['tk_esp'] = $start_time + $token_expire;
                    $_SESSION['token']  = md5($start_time.$_SESSION['user_id'].sha1($start_time.$_SESSION['user_login']));
                    include 'templates/backend/ThemesAddTemplate.php';
                break;

                // Edit template
                // ---------------------------------------------
                case "edit_template":                    
                    if (isPost('edit_template') || isPost('edit_template_and_exit')) {
                        if ($_SESSION['token'] != trim(post('token')) || $_SESSION['tk_esp'] < $start_time)  $errors['themes_empty_name'] = 'token error';
                        if (trim(post('themes_editor_name')) == '') $errors['themes_empty_name'] = lang('themes_empty_field');
                        if ((file_exists(TEMPLATE_CMS_THEMES_PATH.$current_theme.'/'.safeName(post('themes_editor_name').'Template.php'))) and (safeName(post('old_name')) !== safeName(post('themes_editor_name')))) $errors['themes_template_exists'] = lang('themes_template_exists');

                        // Save fields
                        if (isPost('themes_editor')) $template_to_edit = post('themes_editor'); else $template_to_edit = '';
                        if (count($errors) == 0) {
                            createFile(TEMPLATE_CMS_THEMES_PATH.$current_theme.'/'.safeName(post('themes_editor_name')).'Template.php', post('themes_editor'),TEMPLATE_CMS_THEMES_PATH.$current_theme.'/'.post('old_name').'Template.php');
                            if (isPost('edit_template_and_exit')) {
                                redirect('index.php?id=themes');    
                            } else {
                                redirect('index.php?id=themes&action=edit_template&file='.safeName(post('themes_editor_name')).'Template.php');                                    
                            }                
                        }
                    }                    
                    if (isPost('themes_editor_name')) $post_name = post('themes_editor_name'); else $post_name = basename(get('file'),'Template.php');
                    $template_to_edit = loadFile(TEMPLATE_CMS_THEMES_PATH.$current_theme.'/'.get('file'));
                    $_SESSION['tk_esp'] = $start_time + $token_expire;
                    $_SESSION['token']  = md5($start_time.$_SESSION['user_id'].sha1($start_time.$_SESSION['user_login']));
                    include 'templates/backend/ThemesEditTemplate.php';
                break;

                // Add css
                // ---------------------------------------------
                case "add_css":                    
                    if (isPost('add_css') || isPost('add_css_and_exit')) {
                        if ($_SESSION['token'] != trim(post('token')) || $_SESSION['tk_esp'] < $start_time)  $errors['themes_empty_name'] = 'token error';
                        if (trim(post('themes_editor_name')) == '') $errors['themes_empty_name'] = lang('themes_empty_field');
                        if (file_exists(TEMPLATE_CMS_THEMES_PATH.$current_theme.'/css/'.safeName(post('themes_editor_name').'.css'))) $errors['themes_css_exists'] = lang('themes_css_exists');

                        // Save fields
                        if (isPost('themes_editor')) $post_themes_editor = post('themes_editor'); else $post_themes_editor = '';
                        if (count($errors) == 0) {
                            createFile(TEMPLATE_CMS_THEMES_PATH.$current_theme.'/css/'.safeName(post('themes_editor_name')).'.css', post('themes_editor'));
                            
                            if (isPost('add_css_and_exit')) {
                                redirect('index.php?id=themes');    
                            } else {
                                redirect('index.php?id=themes&action=edit_css&file='.safeName(post('themes_editor_name')).'.css');                                    
                            }                

                        }
                    }
                    // Save fields
                    if (isPost('themes_editor_name')) $post_name = post('themes_editor_name'); else $post_name = '';
                    if (isPost('themes_editor')) $post_themes_editor = post('themes_editor'); else $post_themes_editor = '';
                    $_SESSION['tk_esp'] = $start_time + $token_expire;
                    $_SESSION['token']  = md5($start_time.$_SESSION['user_id'].sha1($start_time.$_SESSION['user_login']));
                    include 'templates/backend/ThemesAddCSSTemplate.php';
                break;

                // Clone css
                // ---------------------------------------------
                case "clone_css":
                    $rand_name = basename(get('file'),'.css').'_t_'.date("Ymd_His");
                    $orig_file = loadFile(TEMPLATE_CMS_THEMES_PATH.$current_theme.'/css/'.get('file'));                    
                    createFile(TEMPLATE_CMS_THEMES_PATH.$current_theme.'/css/'.$rand_name.'.css', $orig_file);
                    redirect('index.php?id=themes');
                break;

                // Edit css
                // ---------------------------------------------
                case "edit_css":                                        
                        if (isPost('edit_css') || isPost('edit_css_and_exit')) {
                        if ($_SESSION['token'] != trim(post('token')) || $_SESSION['tk_esp'] < $start_time)  $errors['themes_empty_name'] = 'token error';
                        if (trim(post('themes_editor_name')) == '') $errors['themes_empty_name'] = lang('themes_empty_field');
                        if ((file_exists(TEMPLATE_CMS_THEMES_PATH.$current_theme.'/css/'.safeName(post('themes_editor_name').'.css'))) and (safeName(post('old_name')) !== safeName(post('themes_editor_name')))) $errors['themes_css_exists'] = lang('themes_css_exists');

                        // Save fields
                        if (isPost('themes_editor')) $template_to_edit = post('themes_editor'); else $template_to_edit = '';
                        if (count($errors) == 0) {
                            createFile(TEMPLATE_CMS_THEMES_PATH.$current_theme.'/css/'.safeName(post('themes_editor_name')).'.css', post('themes_editor'),TEMPLATE_CMS_THEMES_PATH.$current_theme.'/css/'.post('old_name').'.css');
                            
                            if (isPost('edit_css_and_exit')) {
                                redirect('index.php?id=themes');    
                            } else {
                                redirect('index.php?id=themes&action=edit_css&file='.safeName(post('themes_editor_name')).'.css');                                    
                            }                
                        }
                    }
                    if (isPost('themes_editor_name')) $post_name = post('themes_editor_name'); else $post_name = basename(get('file'),'.css');
                    $css_to_edit = loadFile(TEMPLATE_CMS_THEMES_PATH.$current_theme.'/css/'.get('file'));
                    $_SESSION['tk_esp'] = $start_time + $token_expire;
                    $_SESSION['token']  = md5($start_time.$_SESSION['user_id'].sha1($start_time.$_SESSION['user_login']));
                    include 'templates/backend/ThemesEditCSSTemplate.php';
                break;
            }
        } else {

            // Get all themes folders
            $_themes_folders = listOfDirs(TEMPLATE_CMS_THEMES_PATH); 
                       
            // Create an array of valid themes folders
            foreach ($_themes_folders as $folder) {
                if (fileExists(TEMPLATE_CMS_THEMES_PATH.$folder.'/'.'indexTemplate.php')) $themes_folders[] = $folder;
            }

            // Get all templates in current theme folder
            $themes_templates_files = listFiles(TEMPLATE_CMS_THEMES_PATH.$current_theme, 'Template.php');

            // Get all styles in current theme folder
            $themes_styles_files = listFiles(TEMPLATE_CMS_THEMES_PATH.$current_theme.'/css/', '.css');

            foreach ($themes_templates_files as $templates_files) {
                $pos = strpos($templates_files, 'minify');
                if ( ! ($pos !== false)) {
                    $themes_templates[] = $templates_files;
                }
            }

            foreach ($themes_styles_files as $styles_files) {
                $pos = strpos($styles_files, 'minify');
                if ( ! ($pos !== false)) {
                    $themes_styles[] = $styles_files;
                }
            }
     
            // Display themes template
            include 'templates/backend/ThemesTemplate.php';
        }
    }
