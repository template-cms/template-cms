<?php


    /**
     * Blocks admin function
     */
    function blocksAdmin() {

        // Init vars
        $blocks_path = '../' . TEMPLATE_CMS_DATA_PATH . 'blocks/';
        $blocks_list = array();
        $errors = array();
        
        // Check for get actions
        // ---------------------------------------------
        if (isGet('action')) {

            // Switch actions
            // ---------------------------------------------
            switch (get('action')) {

                // Add block
                // ---------------------------------------------
                case "add_block":
                    if (isPost('add_blocks') || isPost('add_blocks_and_exit')) {
                        if (trim(post('blocks_editor_name')) == '') $errors['blocks_empty_name'] = lang('themes_empty_field');
                        if (file_exists($blocks_path.safeName(post('blocks_editor_name').'.php'))) $errors['blocks_exists'] = lang('blocks_exists');

                        if (count($errors) == 0) {                            
                            createFile($blocks_path.safeName(post('blocks_editor_name')).'.php', post('blocks_editor'));
                            
                            if (isPost('add_blocks_and_exit')) {
                                redirect('index.php?id=themes&sub_id=blocks');    
                            } else {
                                redirect('index.php?id=themes&sub_id=blocks&action=edit_block&filename='.safeName(post('blocks_editor_name')));                                    
                            }                                  
                        }
                    }
                    // Save fields
                    if (isPost('blocks_editor_name')) $post_name = post('blocks_editor_name'); else $post_name = '';
                    if (isPost('blocks_editor')) $blocks_data = post('blocks_editor'); else $blocks_data = '';
                    include 'templates/backend/BlocksAddTemplate.php';
                break;

                // Edit block
                // ---------------------------------------------
                case "edit_block":                                    
                    // Save current block action                                        
                    if (isPost('edit_blocks') || isPost('edit_blocks_and_exit') ) {                                                    
                        if (trim(post('blocks_editor_name')) == '') $errors['blocks_empty_name'] = lang('themes_empty_field');
                        if ((file_exists($blocks_path.safeName(post('blocks_editor_name').'.php'))) and (safeName(post('blocks_old_name')) !== safeName(post('blocks_editor_name')))) $errors['blocks_exists'] = lang('blocks_exists');


                        // Save fields
                        if (isPost('blocks_editor')) $blocks_data = post('blocks_editor'); else $blocks_data = '';
                        if (count($errors) == 0) {
                            createFile($blocks_path.safeName(post('blocks_editor_name')).'.php', post('blocks_editor'), $blocks_path.post('blocks_old_name').'.php');

                            if (isPost('edit_blocks_and_exit')) {
                                redirect('index.php?id=themes&sub_id=blocks');    
                            } else {
                                redirect('index.php?id=themes&sub_id=blocks&action=edit_block&filename='.safeName(post('blocks_editor_name')));                                    
                            }                        
                        }            
                    }
                    if (isPost('blocks_editor_name')) $post_name = post('blocks_editor_name'); else $post_name = fileName(get('filename'));
                    $blocks_data = loadFile($blocks_path.get('filename').'.php');                    
                    include 'templates/backend/BlocksEditTemplate.php';
                break;

                // Delete block
                // ---------------------------------------------
                case "delete_block":
                    deleteFile($blocks_path.get('filename'));
                    redirect('index.php?id=themes&sub_id=blocks');
                break;
            }
        } else {
            // Display template for all blocks
            $blocks_list = listFiles($blocks_path,'.php');
            include 'templates/backend/BlocksTemplate.php';
        }
    }