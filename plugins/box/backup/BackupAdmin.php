<?php


    /**
     * Backup admin
     */
    function backupAdmin() {

        $backups_path = '../data/backups/';

        $backups_list = array();

        // Create backup
        // ---------------------------------------------
        if (isPost('create_backup')) {
            
            $_dirs = listOfDirs('../data/');

            $zip = Zip::factory();

            foreach ($_dirs as $dir) if ($dir !== 'backups') $zip->readDir('../data/'.$dir.'/');

            $zip->archive($backups_path.dateFormat(time(), "Y-m-d-H-i-s").'.zip');

        }
        
        // Delete backup
        // ---------------------------------------------
        if (get('sub_id') == 'backup') {
            if (get('delete_file')) {
                deleteFile($backups_path.get('delete_file'));
                redirect(getSiteUrl(false).'admin/index.php?id=system&sub_id=backup');
            }
        }

        if (isGet('download')) {            
            fileDownload('../data/backups/'.get('download'));
        }

        // Get backup list
        $backups_list = listFiles($backups_path, '.zip');

        // note: styles based on Filesmanger Plugin
        include 'templates/backend/BackupTemplate.php';
    }