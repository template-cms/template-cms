<?php


    // Add hooks
    addHook('admin_pages_second_navigation','adminSecondNavigation',array('pages',lang('filesmanager_submenu'),'filesmanager'));
    addHook('admin_header','filesmanagerHeaders');
	
    /**
     * Files manager headers
     */
    function filesmanagerHeaders() {
        echo '
                <style type="text/css">
                    .filesmanager-main {
                        color:#737373;
                        float:left;
                        width:600px;
                    }

                    .filesmanager-tr {
                        border-bottom:1px solid #f2f2f2;
                    }

                    .filesmanager-tr:hover {
                        background:#FBF4DF;
                    }

                    .filesmanager-td {
                        padding-left:5px;
                        padding-right:5px;
                        padding-top:5px;
                        padding-bottom:5px;
                    }

                    #filesmanager-upload {
                        border:1px solid #DDD;
                        float:right;
                        margin-left:20px;
                        padding:10px 20px;
                        width:250px;
                    }

                    input.file {
                        position: relative;
                        text-align: right;
                        -moz-opacity:0 ;
                        filter:alpha(opacity: 0);
                        opacity: 0;
                        z-index: 2;
                    }
                    .file-ext {
                        -moz-border-radius:3px;
                        -webkit-border-radius:3px;
                        background:#F2F2F2;
                        border-radius:3px;
                        border: 1px solid #ccc;
                        color:#4E4131;
                        font-weight:bold;
                        padding:10px;
                        text-align:center;
                        line-height:10px;
                    }
                    .file-ext:hover {
                        background:#E5DED7;
                        color:#000;
                    }

                </style>
            ';
    }
    

    /**
     * Filesmanager admin function
     */
    function filesmanagerAdmin() {

        // Array of forbidden types
        $forbidden_types = array('php','htaccess','html','htm');        

        // Get Site url
        $site_url = getSiteUrl(false);

        // Init vars
        if (isGet('path')) $path = get('path'); else $path = 'files/';

        // Set default path value if path is empty
        if ($path == '') {
            $path = 'files/';
            redirect($site_url.'admin/index.php?id=pages&sub_id=filesmanager&path='.$path);
        }

        $files_path = '../'.TEMPLATE_CMS_DATA_PATH.$path;        
        $files_list = array();        

        // Get information about current path
        $_list = fdir($files_path);

        // Get files
        if (isset($_list['files'])) {
            foreach ($_list['files'] as $files) {
                $files_list[] = $files;
            }
        }

        // Get dirs
        if (isset($_list['dirs'])) {
            foreach ($_list['dirs'] as $dirs) {
                $dir_list[] = $dirs;
            }
        }
        
        // Delete file
        // ---------------------------------------------
        if (get('sub_id') == 'filesmanager') {
            if (get('delete_file')) {
                deleteFile($files_path.get('delete_file'));
                redirect($site_url.'admin/index.php?id=pages&sub_id=filesmanager&path='.$path);
            }
        }

        // Delete dir
        // ---------------------------------------------
        if (get('sub_id') == 'filesmanager') {
            if (get('delete_dir')) {
                deleteDir($files_path.get('delete_dir'));                
                redirect($site_url.'admin/index.php?id=pages&sub_id=filesmanager&path='.$path);
            }
        }

        // Upload file
        // ---------------------------------------------
        if (isPost('upload_file')) {
            if ($_FILES['file']) {
                if ( ! in_array(fileExt($_FILES['file']['name']),$forbidden_types)) {                    
                    move_uploaded_file($_FILES['file']['tmp_name'],$files_path.safeName(fileName($_FILES['file']['name'], fileExt($_FILES['file']['name'])), '-', true).'.'.fileExt($_FILES['file']['name']));
                    redirect($site_url.'admin/index.php?id=pages&sub_id=filesmanager&path='.$path);                    
                }
            }
        }

        // Display Files manager template
        include 'templates/backend/FilesmanagerTemplate.php';
    }


    /**
     * Get directories and files in current path
     */
     function fdir($dir, $type = null) {
        $files = array();
        $c = 0;
        $_dir = $dir;
        if (is_dir($dir)) {
        $dir = opendir ($dir);
            while (false !== ($file = readdir($dir))) {                               
                if (($file !=".") && ($file !="..")) {                
                    $c++;
                    if (is_dir($_dir.$file)) {
                        $files['dirs'][$c] = $file;
                    } else {
                        $files['files'][$c] = $file;
                    }
                }
            }
            closedir($dir);
            return $files;
        } else {
            return false;
        }
     }