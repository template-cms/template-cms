<?php  if (!defined('TEMPLATE_CMS_ACCESS')) exit('No direct script access allowed');

    /**
     *	Filesystem module
     *
     *	@package TemplateCMS
     *  @subpackage Engine
     *	@author Romanenko Sergey / Awilum
     *	@copyright 2011 - 2012 Romanenko Sergey / Awilum
     *	@version $Id$
     *	@since 2.0
     *  @license http://opensource.org/licenses/gpl-license.php GNU Public License
     *  TemplateCMS is free software. This version may have been modified pursuant
     *  to the GNU General Public License, and as distributed it includes or
     *  is derivative of works licensed under the GNU General Public License or
     *  other free or open source software licenses.
     *  See COPYING.txt for copyright notices and details.
     *  @filesource 
     */
	 
    $mime_types = array(
        'aac'        => 'audio/aac',
        'atom'       => 'application/atom+xml',
        'avi'        => 'video/avi',
        'bmp'        => 'image/x-ms-bmp',
        'c'          => 'text/x-c',
        'class'      => 'application/octet-stream',
        'css'        => 'text/css',
        'csv'        => 'text/csv',
        'deb'        => 'application/x-deb',
        'dll'        => 'application/x-msdownload',
        'dmg'        => 'application/x-apple-diskimage',
        'doc'        => 'application/msword',
        'docx'       => 'application/vnd.openxmlformats-officedocument.wordprocessingml.document',
        'exe'        => 'application/octet-stream',
        'flv'        => 'video/x-flv',
        'gif'        => 'image/gif',
        'gz'         => 'application/x-gzip',
        'h'          => 'text/x-c',
        'htm'        => 'text/html',
        'html'       => 'text/html',
        'ini'        => 'text/plain',
        'jar'        => 'application/java-archive',
        'java'       => 'text/x-java',
        'jpeg'       => 'image/jpeg',
        'jpg'        => 'image/jpeg',
        'js'         => 'text/javascript',
        'json'       => 'application/json',
        'mid'        => 'audio/midi',
        'midi'       => 'audio/midi',
        'mka'        => 'audio/x-matroska',
        'mkv'        => 'video/x-matroska',
        'mp3'        => 'audio/mpeg',
        'mp4'        => 'application/mp4',
        'mpeg'       => 'video/mpeg',
        'mpg'        => 'video/mpeg',
        'odt'        => 'application/vnd.oasis.opendocument.text',
        'ogg'        => 'audio/ogg',
        'pdf'        => 'application/pdf',
        'php'        => 'text/x-php',
        'png'        => 'image/png',
        'psd'        => 'image/vnd.adobe.photoshop',
        'py'         => 'application/x-python',
        'ra'         => 'audio/vnd.rn-realaudio',
        'ram'        => 'audio/vnd.rn-realaudio',
        'rar'        => 'application/x-rar-compressed',
        'rss'        => 'application/rss+xml',
        'safariextz' => 'application/x-safari-extension',
        'sh'         => 'text/x-shellscript',
        'shtml'      => 'text/html',
        'swf'        => 'application/x-shockwave-flash',
        'tar'        => 'application/x-tar',
        'tif'        => 'image/tiff',
        'tiff'       => 'image/tiff',
        'torrent'    => 'application/x-bittorrent',
        'txt'        => 'text/plain',
        'wav'        => 'audio/wav',
        'webp'       => 'image/webp',
        'wma'        => 'audio/x-ms-wma',
        'xls'        => 'application/vnd.ms-excel',
        'xml'        => 'text/xml',
        'zip'        => 'application/zip',
    );


     /**
      * Get list of files in directory (1 level)
      *
      * @param string $dir Directory to scan
      * @param mixed $type Files types
      * @return boolean
      */
     function listFiles($dir, $type = null) {
        $files = array();
        if (is_dir($dir)) {
        $dir = opendir ($dir);
            while (false !== ($file = readdir($dir))) {                
                if (is_array($type)) {    
                    $file_ext = substr(strrchr($file, '.'), 1);                                     
                    if (in_array($file_ext, $type)) {
                        if (strpos($file, $file_ext, 1)) { 
                            $files[] = $file;
                        }                          
                    }
                } else {
                    if (($file !=".") && ($file !="..")) {
                        if (isset($type)) {
                            if (strpos($file, $type, 1)) {
                                $files[] = $file;
                            }
                        } else {
                            $files[] = $file;
                        }
                    }
                }
            }
            closedir($dir);
            return $files;
        } else {
            return false;
        }
     }


     /**
      * Get list of files in directory recursive
      *
      * @param string $folder Folder
      * @param mixed $type Files types
      * @return array
      */
     function listOfFiles($folder, $type=null) {
        $data = array();
        if (is_dir($folder)) {
            $iterator = new RecursiveDirectoryIterator($folder);
            foreach (new RecursiveIteratorIterator($iterator) as $file) {            
                if (is_array($type)) {    
                    $file_ext = substr(strrchr($file->getFilename(), '.'), 1);                                     
                    if (in_array($file_ext, $type)) {                                                       
                        if (strpos($file->getFilename(), $file_ext, 1)) {                        
                            $data[] = $file->getFilename();
                        }                                
                    }
                } else {
                    if (strpos($file->getFilename(), $type, 1)) {
                        $data[] = $file->getFilename();
                    }
                }
            }
            return $data;
        } else {
            return false;
        }        
     }


    /**
     * Returns true if the File exists.
     *
     * @param string $filename The file name
     * @return boolean
     */
    function fileExists($filename) {
       return (file_exists($filename) && is_file($filename)); 
    }


    /**
     * Create new file
     *
     * @param string $filename The file name
     * @param string $content Content to save
     * @param string $old_filename Old filename turn on rename mod
     * @return boolean
     */
    function createFile($filename, $content, $old_filename=null) {
        
        $space = '-';
		$dir_name = dirname($filename);

		// Rename mod
        if ( ! empty($old_filename)) {
            $new_cl_space = str_replace(" ", $space, $filename);
            $new = translitIt($new_cl_space);
            if ($old_filename !== $new) {
                rename($old_filename,$new);
                $save_filename = $filename;
            } else {
                $save_filename = $filename;
            }                            
        } else {
            $save_filename = $filename;
        }
                
        // Create safe filename        
        $save_cl_space = str_replace(" ", $space, $save_filename);
        $save_file_name = translitIt($save_cl_space);
        
        // Check that the folder exists and is writable
        if ( ! is_dir($dir_name))      return false;
        if ( ! is_writable($dir_name)) return false;
        
        // Write file
        return file_put_contents($save_file_name, $content, LOCK_EX);              
    }


    /**
     * Delete current file
     *
     * @param string $filename The file name
     * @return boolean
     */
    function deleteFile($filename) {
        if ( ! is_dir($filename)) {
            return unlink($filename);
        }
        return false;
    }


    /**
     * Rename file
     *
     * @param string $from Original file location
     * @param string $to Desitination location of the file
     * @return boolean
     */
    function renameFile($from, $to) {
        if ( ! fileExists($to)) {
            return rename($from, $to);
        }
        return false;
    }


    /**
     * Load file if exists
     *
     * @param $filename The file name
     * @return boolean
     */
    function loadFile($filename) {
        if (fileExists($filename)) {
            return file_get_contents($filename);
        }
    }


    /**
     * Copy file
     *
     * @param string $from Original file location
     * @param string $to Desitination location of the file
     * @return boolean
     */
    function copyFile($from, $to) {
        if ( ! fileExists($from) || fileExists($to)) {
            return false;
        }
        return copy($from, $to);
    }


    /**
     * Get time(in Unix timestamp) the file was last changed 
     *
     * @param string $filename The file name
     * @return boolean
     */    
    function fileLastChange($filename) {
        if (fileExists($filename)) {
            return filemtime($filename);
        }
        return false;
    }   


    /**
     * Get last access time
     *
     * @param string $filename The file name
     * @return boolean
     */
    function fileLastAccess($filename) {
        if (fileExists($filename)) {
            return fileatime($filename);
        }
        return false;              
    }


    /**
     * Get the File extension.
     *
     * @param string $filename The file name
     * @return string
     */
    function fileExt($filename){
        return substr(strrchr($filename, '.'), 1);
    }


    /**
     * Get the File name
     *
     * @param string $filename The file name
     * @return string
     */
    function fileName($filename) {
        return basename($filename,'.'.fileExt($filename));
    }


    /**
     * Creates a directory
     *
     * @param string $dir Name of directory to create
     * @return boolean
     */
    function createDir($dir) {		
        if (is_dir($dir)) {
            return false;
        }
        return mkdir($dir, 0775);
    }


    /**
     * Delete directory
     *
     * @param string $dir Name of directory to delete
     */
    function deleteDir($dir) { 
        if (is_dir($dir)) { 
            $objects = scandir($dir); 
            foreach ($objects as $object) { 
                if ($object != '.' && $object != '..') { 
                    if (filetype($dir.'/'.$object) == 'dir') {
                        deleteDir($dir.'/'.$object);     
                    } else {
                        unlink($dir.'/'.$object);    
                    }
                } 
            } 
            reset($objects); 
            rmdir($dir); 
        } 
     } 


    /**
     * Check dir permission
     *
     * @param string $dir Directory to check
     * @return string
     */
    function checkDirPerm($dir) {
        clearstatcache();
        return substr(sprintf('%o', fileperms($dir)), -4);
    }


    /**
     * Get list of directories
     *
     * @param string $dir Directory
     */
    function listOfDirs($dir){        
        $ignored_directory[] = '.'; 
        $ignored_directory[] = '..';
        if (is_dir($dir)){
            if ($dir_handle = opendir($dir)){
                while (($directory = readdir($dir_handle)) !== false){
                    if ( ! (array_search($directory,$ignored_directory) > -1)){
                        if (filetype($dir.$directory) == "dir"){
                            $directory_list[] = $directory;
                        }
                    }
                }
            closedir($dir_handle);
            }
        }
        return($directory_list);
    }


    /**
     * Forces a file to be downloaded.
     *
     *  <code>
     *      fileDownload('filename.txt');
     *  </code>
     *         
     * @param string  $file          Full path to file
     * @param string  $content_type  Content type of the file
     * @param string  $filename      Filename of the download
     * @param integer $kbps          Max download speed in KiB/s
     */
    function fileDownload($file, $content_type = null, $filename = null, $kbps = 0) {
        
        // Redefine vars
        $file         = (string) $file;
        $content_type = ($content_type === null) ? null : (string) $content_type;
        $filename     = ($filename === null) ? null : (string) $filename;
        $kbps         = (int)    $kbps;
        
        // Check that the file exists and that its readable
        if (file_exists($file) === false || is_readable($file) === false) {
            throw new RuntimeException(vsprintf("%s(): Failed to open stream.", array(__METHOD__)));
        } 
        
        // Empty output buffers
        while (ob_get_level() > 0) ob_end_clean();

        // Send headers            
        if ($content_type === null) $content_type = fileMime($file);
        
        if ($filename === null) $filename = basename($file);
        

        header('Content-type: ' . $content_type);
        header('Content-Disposition: attachment; filename="' . $filename . '"');
        header('Content-Length: ' . filesize($file));

        // Read file and write it to the output
        set_time_limit(0);

        if ($kbps === 0) {
            
            readfile($file);

        } else {

            $handle = fopen($file, 'r');

            while ( ! feof($handle) && !connection_aborted()) {
                
                $s = microtime(true);

                echo fread($handle, round($kbps * 1024));

                if (($wait = 1e6 - (microtime(true) - $s)) > 0) usleep($wait);                    
                
            }

            fclose($handle);
        }

        exit();
    }


    /**
     * Returns the mime type of a file. Returns false if the mime type is not found.
     *
     *  <code>
     *      echo fileMime('filename.txt');
     *  </code>
     *  
     * @param   string  $file  Full path to the file
     * @param   boolean $guess Set to false to disable mime type guessing
     * @return  string
     */        
    function fileMime($file, $guess = true) {
        
        global $mime_types;

        // Redefine vars
        $file  = (string) $file;
        $guess = (bool)   $guess;

        // Get mime using the file information functions
        if (function_exists('finfo_open')) {                
            
            $info = finfo_open(FILEINFO_MIME_TYPE);
            
            $mime = finfo_file($info, $file);
            
            finfo_close($info);
            
            return $mime;

        } else {

            // Just guess mime by using the file extension
            if($guess === true) {                    

                $mime_types = $mime_types;                    

                $extension = pathinfo($file, PATHINFO_EXTENSION);

                return isset($mimeTypes[$extension]) ? $mimeTypes[$extension] : false;
            } else {
                return false;
            }
        }
    }