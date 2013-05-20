<?php

    // Add hooks
    addHook('admin_themes_second_navigation','adminSecondNavigation',array('themes',lang('menu_submenu'),'menus'));

    /**
     * Menus admin function
     */
    function menusAdmin() {
        
        // Init Vars
        $menus_list = array();
        $menu_list = array();
        $menus_path = '../' . TEMPLATE_CMS_DATA_PATH . 'menus/';        
        $menus_records = array();

        // Create new menus database
        if (isPost('submit_add_menus')) {
            if (trim(post('add_menus_name')) == '') $save = 'temp-'.rand(1,10000); else $save = post('add_menus_name');
            createXMLdb($menus_path.$save);
        }

        // Rename menus database file
        if (isPost('submit_edit_menus_name')) {
            if (trim(post('edit_menus_name')) == '') $save = 'temp-'.rand(1,10000); else $save = post('edit_menus_name');
            renameFile($menus_path.post('edit_menus_old_name').'.xml', $menus_path.$save.'.xml');
        }

      
        // Check for get actions
        // ---------------------------------------------
        if (isGet('action')) {
            
            // Switch actions
            // ---------------------------------------------
            switch (get('action')) {

                // Edit
                // ---------------------------------------------
                case "edit_menus":
                    
                    // Get xml database
                    $xml_db = getXMLdb($menus_path.get('filename'));

                    // Array of targets
                    $options = array('', '_blank', '_parent', '_top');

                    // Array of order numbers
                    $order_num = range(0, 20);

                    // Delete xml record
                    if (isGet('delete')) {                        
                        deleteXMLRecord($xml_db, 'menu', (int)get('delete'));
                        redirect('?id=themes&sub_id=menus&action=edit_menus&filename='.get('filename'));
                    }

                    // Insert xml record
                    if (isPost('submit_add_menu')) {
                        $menu_link = ltrim(post('add_menu_link'), '/');
                        insertXMLRecord($xml_db, 'menu', array('menu_order'  => post('add_menu_order'),
                                                               'menu_name'   => post('add_menu_name'),
                                                               'menu_link'   => $menu_link,
                                                               'menu_target' => post('add_menu_target')));
                    }

                    if ($xml_db) {
                        // Get records from menus database
                        $records = selectXMLRecord($xml_db, "//menu",'all');
                        // Get fields from array of menus records
                        $menus_records = selectXMLfields($records, array('menu_name', 'menu_link', 'menu_target', 'menu_order'), 'menu_order', 'ASC');                    
                        include 'templates/backend/MenusEditTemplate.php';
                    }
                break;

                // Delete
                // ---------------------------------------------
                case "delete_menus":
                    deleteFile($menus_path.get('filename'));
                    redirect('?id=themes&sub_id=menus');
                break;
            }
        } else {            
            $menus_list = listFiles($menus_path,'.xml');
            // Display template for all menus
            include 'templates/backend/MenusTemplate.php';
        }
    }