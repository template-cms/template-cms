<?php


    addHook('admin_header','pagesAdminHeaders');

    /**
     * Pages plugin admin headers
     */
    function pagesAdminHeaders() {
        echo '<style>
                #pages-other {
                    margin-left:10px;
                    width:780px;
                }
                #pages-other-toggle {
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
                #pages-other-toggle:hover {
                    background:#E9EAEA;
                }
                #pages-other-box {                    
                    border-left:1px solid #ccc;
                    border-right:1px solid #ccc;
                    display:none;
                    padding:15px;
                }

              .error-none {display:none;}

              </style>
              <script>
               $().ready(function() {
                    $("#pages-other-toggle").click(function() {                        
                        $("#pages-other-box").slideToggle("slow");                        
                    });                      
               });
              </script>';
    }
	
    /**
     * Pages admin function
     */
    function pagesAdmin() {

        $current_theme = getSiteTheme(false);
        $site_url = getSiteUrl(false);

        $pages_path = '../'.TEMPLATE_CMS_DATA_PATH.'pages/';
        $templates_path = TEMPLATE_CMS_THEMES_PATH.$current_theme.'/';
        $space = '-';

        $errors = array();     

        // Get users xml database
        $users_xml_db = getXMLdb('../'.TEMPLATE_CMS_DATA_PATH.'users/users.xml');

        // Get current user record
        $user = selectXMLRecord($users_xml_db, "//user[@id='".$_SESSION['user_id']."']");

        // Page author
        if (isset($user->firstname) && trim($user->firstname) !== '') {
            if (trim($user->lastname) !== '') $lastname = ' '.$user->lastname; else $lastname = '';
            $author = $user->firstname.$lastname;
        } else {
            $author = $_SESSION['user_login'];
        }
      
        // Date
        $years   = range(2000, 2032);
        $month   = range(1, 12);
        $days    = range(1, 31);
        $hours   = range(0, 23);
        $minutes = range(0, 59);
        $seconds = range(0, 59);   

        // Check for get actions
        // ---------------------------------------------
        if (isGet('action')) {

            // Switch actions
            // ---------------------------------------------
            switch (get('action')) {

                // Clone page
                // ---------------------------------------------
                case "clone_page":
                    $rand_file_name = get('filename').'_t_'.date("Ymd_His");                    
                    
                    $orig_file = getXML($pages_path.get('filename').'.xml');
                    
                    $rand_title = $orig_file->title.'_t_'.date("Ymd_His");
                    
                    // Prepare content before saving
                    $content = '<?xml version="1.0" encoding="UTF-8"?>';
                    $content .= '<root>';
                    $content .= '<slug>' . toText($rand_file_name) . '</slug>';
                    $content .= '<template>' . $orig_file->template . '</template>';
                    $content .= '<parent>' . $orig_file->parent . '</parent>';
                    $content .= '<title>' . toText($rand_title) . '</title>';
                    $content .= '<description>' . toText($orig_file->description) . '</description>';
                    $content .= '<keywords>' . toText($orig_file->keywords) . '</keywords>';
                    $content .= '<content>' . toText($orig_file->content) . '</content>';
                    $content .= '<date>' . $orig_file->date . '</date>';
                    $content .= '<author>'.$orig_file->author.'</author>';
                    $content .= '</root>';

                    createFile($pages_path.$rand_file_name.'.xml', $content);
                    createSitemap();
                    redirect('index.php?id=pages');
                break;
                
                // Add page
                // ---------------------------------------------
                case "add_page":

                    // Add page                    
                    if (isPost('add_page') || isPost('add_page_and_exit')) {

                        // Convert html to text automatically
                        htmlPostText();

                        // Get pages parent
                        if (post('pages') == '-none-') {
                            $parent_page = '';
                        } else {
                            $parent_page = post('pages');
                        }


                        // Validate
                        //--------------
                        if (trim(post('page_name')) == '') $errors['pages_empty_name'] = lang('pages_empty_field');
                        if (file_exists($pages_path.safeName(post('page_name'), '-', true).'.xml')) $errors['pages_exists'] = lang('pages_exists');

                        if (trim(post('page_title')) == '') $errors['pages_empty_title'] = lang('pages_empty_field');

                        $date = mktime(post('hour'),post('minute'),post('second'),post('month'),post('day'),post('year'));

                        // If no errors then try to save
                        if (count($errors) == 0) {
                            // Prepare content before saving
                            $content = '<?xml version="1.0" encoding="UTF-8"?>';
                            $content .= '<root>';
                            $content .= '<slug>' . safeName(post('page_name'), '-', true) . '</slug>';
                            $content .= '<template>' . post('templates') . '</template>';
                            $content .= '<parent>' . $parent_page . '</parent>';
                            $content .= '<title>' . post('page_title') . '</title>';
                            $content .= '<description>' . post('page_description') . '</description>';
                            $content .= '<keywords>' . post('page_keywords') . '</keywords>';
                            $content .= '<content>' . post('editor') . '</content>';
                            $content .= '<date>' . $date . '</date>';
                            $content .= '<author>'.$author.'</author>';
                            $content .= '</root>';

                            // Create page xml file
                            createFile($pages_path . safeName(post('page_name'), '-', true) . '.xml', $content);                            
                            createSitemap();

                            if(isPost('add_page_and_exit')) {
                                redirect('index.php?id=pages');    
                            } else {
                                redirect('index.php?id=pages&action=edit_page&filename='.safeName(post('page_name'), '-', true));                                    
                            }        
                        }
                    }

                    // Get all pages
                    $pages_list = listFiles($pages_path, '.xml');
                    $pages_array[] = '-none-';
                    // Foreach pages find page whithout parent                		
                    foreach ($pages_list as $page) {
                        $cur_page = getXML($pages_path . $page);
                        if (isset($cur_page->parent)) {
                            $c_p = $cur_page->parent;
                        } else {
                            $c_p = '';
                        }
                        if ($c_p == '')
                            // error404 is system "constant" and no child for it
                            if(basename($page, '.xml') !== 'error404') {
                                $pages_array[] = basename($page, '.xml');
                            }
                    }

                    $templates_list = listFiles($templates_path,'Template.php');

                    foreach ($templates_list as $file) {
                        $pos = strpos($file, 'minify');
                        if ( ! ($pos !== false)) {
                            $templates_array[] = basename($file,'Template.php');
                        }
                    }
                                        

                    // Save fields
                    if (isPost('pages')) $parent_page = post('pages'); else $parent_page = '';
                    if (isPost('page_name')) $post_name = post('page_name'); else $post_name = '';
                    if (isPost('page_title')) $post_title = post('page_title'); else $post_title = '';
                    if (isPost('page_keywords')) $post_keywords = post('page_keywords'); else $post_keywords = '';
                    if (isPost('page_description')) $post_description = post('page_description'); else $post_description = '';
                    if (isPost('editor')) $post_content = post('editor'); else $post_content = '';
                    if (isPost('templates')) $post_template = post('templates'); else $post_template = 'index';
                    if (isPost('parent_page')) {
                        $post_template = post('pages');
                    } else {
                        if(isGet('parent_page')) {
                            $parent_page = trim(get('parent_page'));
                        }
                    }
                    //--------------

                    $date = explode('-',dateFormat(time(),'Y-m-d-H-i-s'));

                    include 'templates/backend/PagesAddTemplate.php';
                break;

                // Edit page
                // ---------------------------------------------
                case "edit_page":

                    if (isPost('edit_page') || isPost('edit_page_and_exit')) {

                        // Convert html to text automatically
                        htmlPostText();       
       

                        // Get pages parent
                        if (post('pages') == '-none-') {
                            $parent_page = '';
                        } else {
                            $parent_page = post('pages');
                        }
                        // Save field
                        $post_parent = post('pages');
                        

                        // Validate
                        //--------------
                        if (trim(post('page_name')) == '') $errors['pages_empty_name'] = lang('pages_empty_field');        
                        if ((file_exists($pages_path.safeName(post('page_name'), '-', true).'.xml')) and (safeName(post('page_old_name'), '-', true) !== safeName(post('page_name'), '-', true))) $errors['pages_exists'] = lang('pages_exists');
                        if (trim(post('page_title')) == '') $errors['pages_empty_title'] = lang('pages_empty_field');

                        // Save fields
                        if (isPost('page_name')) $post_name = post('page_name'); else $post_name = '';
                        if (isPost('page_title')) $post_title = post('page_title'); else $post_title = '';
                        if (isPost('page_keywords')) $post_keywords = post('page_keywords'); else $post_keywords = '';
                        if (isPost('page_description')) $post_description = post('page_description'); else $post_description = '';
                        if (isPost('editor')) $post_content = toText(post('editor')); else $post_content = '';
                        if (isPost('templates')) $post_template = post('templates'); else $post_template = 'index';
                        //--------------

                        // Get date
                        $date = mktime(post('hour'),post('minute'),post('second'),post('month'),post('day'),post('year'));

                        if (count($errors) == 0) {
                            // Prepare content before saving
                            $content = '<?xml version="1.0" encoding="UTF-8"?>';
                            $content .= '<root>';
                            $content .= '<slug>' . safeName(post('page_name'), '-', true) . '</slug>';
                            $content .= '<template>' . post('templates') . '</template>';
                            $content .= '<parent>' . $parent_page . '</parent>';
                            $content .= '<title>' . post('page_title') . '</title>';
                            $content .= '<description>' . post('page_description') . '</description>';
                            $content .= '<keywords>' . post('page_keywords') . '</keywords>';
                            $content .= '<content>' . post('editor') . '</content>';
                            $content .= '<date>' . $date . '</date>';
                            $content .= '<author>'.$author.'</author>';
                            $content .= '</root>';

                            // Update parents in all childrens
                            if ((safeName(post('page_name'), '-', true)) !== (safeName(post('page_old_name'), '-', true)) and (post('old_parent') == '')) {

                                // Get array of pages
                                $pages_list = listFiles($pages_path,'.xml');

                                // Init pages array
                                $pages_array = array();

                                // Init counter
                                $count = 0;

                                // Go through all pages and find childrens for this parent
                                foreach ($pages_list as $file) {
                                    $data = getXML($pages_path.$file);

                                    if (($data->parent) == (translitIt(trim(post('page_old_name'))))) {
                                        $pages_array[$count]['slug']        = $data->slug;
                                        $pages_array[$count]['template']    = $data->template;
                                        $pages_array[$count]['parent']      = $data->parent;
                                        $pages_array[$count]['new_parent']  = translitIt(trim(post('page_name')));
                                        $pages_array[$count]['title']       = $data->title;
                                        $pages_array[$count]['description'] = $data->description;
                                        $pages_array[$count]['keywords']    = $data->keywords;
                                        $pages_array[$count]['content']     = $data->content;
                                        $pages_array[$count]['date']        = $data->date;
                                    }
                                    $count++;
                                }

                                if (count($pages_array) > 0) {
                                    foreach ($pages_array as $pages) {
                                        // Prepare content before saving
                                        $child_content = '<?xml version="1.0" encoding="UTF-8"?>';
                                        $child_content .= '<root>';
                                        $child_content .= '<slug>' . str_replace(" ", $space, safeName($pages['slug'], '-', true)) . '</slug>';
                                        $child_content .= '<template>' . $pages['template'] . '</template>';
                                        $child_content .= '<parent>' . safeName($pages['new_parent'], '-', true) . '</parent>';
                                        $child_content .= '<title>' . toText($pages['title']) . '</title>';
                                        $child_content .= '<description>' . toText($pages['description']) . '</description>';
                                        $child_content .= '<keywords>' . toText($pages['keywords']) . '</keywords>';
                                        $child_content .= '<content>' . toText($pages['content']) . '</content>';
                                        $child_content .= '<date>' . $pages['date'] . '</date>';
                                        $child_content .= '<author>'.$pages['author'].'</author>';
                                        $child_content .= '</root>';

                                        createFile($pages_path . safeName($pages['slug'], '-', true) . '.xml', $child_content, $pages_path . trim($pages['slug']) .'.xml');
                                    }
                                }
                                // Create page xml file
                                createFile($pages_path . safeName(post('page_name'), '-', true) . '.xml', $content, $pages_path . trim(post('page_old_name')) .'.xml');
                                createSitemap();
                            } else {
                                // Create page xml file
                                createFile($pages_path . safeName(post('page_name'), '-', true) . '.xml', $content, $pages_path . trim(post('page_old_name')) .'.xml');
                                createSitemap();
                            }
                            if (isPost('edit_page_and_exit')) {
                                redirect('index.php?id=pages');    
                            } else {
                                redirect('index.php?id=pages&action=edit_page&filename='.safeName(post('page_name'), '-', true));                                    
                            }        
                        }
                    }


                    // Get all pages
                    $pages_list = listFiles($pages_path,'.xml');

                    
                    // Init childrens pages array
                    $childrens_array = array();

                    // Init childrens counter
                    $childrens_count = 0;

                    // Go through all pages and find if this page have childrens count them
                    // Parent with childrens can not become a child of another parent :)
                    foreach ($pages_list as $file) {
                        $data = getXML($pages_path.$file);                        
                        if (($data->parent) == (get('filename'))) {
                            $childrens_count++;
                        }
                    }                  

                    $pages_array[] = '-none-';
                    
                    // Foreach pages find page whith out parent                		
                    foreach ($pages_list as $page) {
                        $cur_page = getXML($pages_path . $page);
                        if ($cur_page->parent != '') {
                            $c_p = $cur_page->parent;
                        } else {
                            $c_p = '';
                        }
                        if ((empty($c_p)) and (basename($page, '.xml') !== get('filename'))) {
                            // If this page dont have childrens then add page to array
                            if ($childrens_count == 0) {
                                // error404 is system "constant" and no child for it
                                if (basename($page, '.xml') !== 'error404') {
                                    $pages_array[] = basename($page, '.xml');
                                }
                            }
                        }
                    }

                    
                    $templates_list = listFiles($templates_path,'Template.php');

                    foreach ($templates_list as $file) {
                        $pos = strpos($file, 'minify');
                        if ( ! ($pos !== false)) {
                            $templates_array[] = basename($file,'Template.php');
                        }
                    }


                    // Get page to edit
                    $xml = getXML('../'.TEMPLATE_CMS_DATA_PATH.'pages/'.get('filename').'.xml');

                    if ($xml) {
                        // Safe fields or load fields
                        if (isPost('page_name')) $slug_to_edit = post('page_name'); else $slug_to_edit = $xml->slug;
                        if (isPost('page_title')) $title_to_edit = post('page_title'); else $title_to_edit = $xml->title;
                        if (isPost('page_description')) $description_to_edit = post('page_description'); else $description_to_edit = $xml->description;
                        if (isPost('page_keywords')) $keywords_to_edit = post('page_keywords'); else $keywords_to_edit = $xml->keywords;
                        if (isPost('editor')) $to_edit = post('editor'); else $to_edit = toText($xml->content);

                        if (isPost('pages')) {
                            // Get pages parent
                            if (post('pages') == '-none-') {
                                $parent_page = '';
                            } else {
                                $parent_page = post('pages');
                            }
                            // Save field
                            $parent_page = post('pages');
                        } else {
                            $parent_page = $xml->parent;
                        }
                        if (isPost('templates')) $template = post('templates'); else $template = $xml->template; 

                        $date = explode('-',dateFormat($xml->date,'Y-m-d-H-i-s'));
                    
                        include 'templates/backend/PagesEditTemplate.php';
                    }
                    
                break;

                // Delete page
                // ---------------------------------------------
                case "delete_page":
                    
                    // Get array of pages
                    $pages_list = listFiles($pages_path,'.xml');

                    // Init pages array
                    $pages_array = array();

                    // Init counter
                    $count = 0;
                    

                    // Go through all pages and find childrens for this parent
                    // Reset the parent of childrens
                    foreach ($pages_list as $file) {
                        $data = getXML($pages_path.$file);

                        if (($data->parent) == (get('filename'))) {
                            $pages_array[$count]['slug']        = $data->slug;
                            $pages_array[$count]['template']    = $data->template;
                            $pages_array[$count]['parent']      = $data->parent;
                            $pages_array[$count]['new_parent']  = ''; // No parent!
                            $pages_array[$count]['title']       = $data->title;
                            $pages_array[$count]['description'] = $data->description;
                            $pages_array[$count]['keywords']    = $data->keywords;
                            $pages_array[$count]['content']     = $data->content;
                            $pages_array[$count]['date']        = $data->date;
                        }
                        $count++;
                    }

                    // If this parent found the children, then overwrite these pages zeroed parents
                    if (count($pages_array) > 0) {
                        foreach ($pages_array as $pages) {
                            // Prepare content before saving
                            $child_content = '<?xml version="1.0" encoding="UTF-8"?>';
                            $child_content .= '<root>';
                            $child_content .= '<slug>' . str_replace(" ", $space, translitIt(trim($pages['slug']))) . '</slug>';
                            $child_content .= '<template>' . toText($pages['template']) . '</template>';
                            $child_content .= '<parent>' . toText($pages['new_parent']) . '</parent>';
                            $child_content .= '<title>' . toText($pages['title']) . '</title>';
                            $child_content .= '<description>' . toText($pages['description']) . '</description>';
                            $child_content .= '<keywords>' . toText($pages['keywords']) . '</keywords>';
                            $child_content .= '<content>' . toText($pages['content']) . '</content>';
                            $child_content .= '<date>' . $pages['date'] . '</date>';
                            $child_content .= '<author>'.$pages['author'].'</author>';
                            $child_content .= '</root>';

                            createFile($pages_path . trim($pages['slug']) . '.xml', $child_content, $pages_path . trim($pages['slug']) .'.xml');
                        }
                    }

                    // Delete file from pages folder
                    deleteFile($pages_path . get('filename') . '.xml');

                    createSitemap();
                    
                    redirect('index.php?id=pages');
                break;
            }
            // Its mean that you can add your own actions for this plugin
            runHook('admin_pages_extra_actions');
        } else { // Load main template
            
            $pages_list = listFiles($pages_path,'.xml');

            $pages_array = array();

            $count = 0;
            foreach ($pages_list as $file) {
                $data = getXML($pages_path . $file);
                $pages_array[$count]['title']  = html_entity_decode($data->title, ENT_QUOTES, 'UTF-8');
                $pages_array[$count]['parent'] = $data->parent;
                $pages_array[$count]['date']   = $data->date;
                $pages_array[$count]['slug']   = $data->slug;

                if (isset($data->parent)) {
                    $c_p = $data->parent;
                } else {
                    $c_p = '';
                }

                if ($c_p != '') {
                    $parent_data = getXML($pages_path . $data->parent . '.xml');
                    if (isset($parent_data->title)) {
                        $parent_title = $parent_data->title;
                    } else {
                        $parent_title = '';
                    }
                    $pages_array[$count]['sort'] = $parent_title . ' ' . $data->title;                    
                } else {
                    $pages_array[$count]['sort'] = $data->title;
                }
                $parent_title = '';
                $count++;                
                
            }

            $pages = subval_sort($pages_array, 'sort');

            // Display all pages template
            include 'templates/backend/PagesTemplate.php';
        }
    }