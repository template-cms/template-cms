<?php

	/**
	 * Template CMS :: Installator	
	 */

    $admin_path = '';

    $language = 'en';

    // Set admin path false
    $admin = false;

    define('TEMPLATE_CMS_ACCESS',true);

    // Set default timezone
    $system_timezone = 'Kwajalein';
    
    // Include engine core
    include 'template_cms/Core.php';
    
    // Setting error display depending on debug mode or not
    // Get php version id
    if ( ! defined('PHP_VERSION_ID')){
        $version = PHP_VERSION;
        define('PHP_VERSION_ID', ($version{0} * 10000 + $version{2} * 100 + $version{4}));
    }

    if (TEMPLATE_CMS_DEBUG) {
        // If PHP version >= 5.3.1 then also ~E_DEPRECATED
        if (PHP_VERSION_ID >= 50300){
            error_reporting(E_ALL & ~E_NOTICE & ~E_DEPRECATED);
        } else {
            error_reporting(E_ALL ^ E_NOTICE);
        }
    }
    
    // Get array with the names of all modules compiled and loaded
    $php_modules = get_loaded_extensions();

    // Get site URL
    $site_url = 'http://'.$_SERVER["SERVER_NAME"].str_replace(array("index.php","install.php"),"",$_SERVER['PHP_SELF']);

    $RewriteBase = str_replace(array("index.php","install.php"),"",$_SERVER['PHP_SELF']);

    // Errors array
    $errors = array();

    // Directories to check
    $dir_array = array('themes','data');

    // If pressed <Install> button then try to install
    if (isset($_POST['install_submit'])) {
        if (empty($_POST['sitename']))          $errors['sitename'] = 'Site name is empty!';
        if (empty($_POST['siteurl']))           $errors['siteurl'] = 'Site url is empty!';
        if (empty($_POST['login']))             $errors['login'] = 'Login is empty!';
        if (empty($_POST['password']))          $errors['password'] = 'Password is empty!';
        if (empty($_POST['email']))             $errors['email'] = 'Email is empty!';
        if (trim($_POST['php']) !== '')         $errors['php'] = true;
        if (trim($_POST['simplexml']) !== '')   $errors['simplexml'] = true;
        if (trim($_POST['mod_rewrite']) !== '') $errors['mod_rewrite'] = true;
        
        // If errors is 0 then install cms
        if (count($errors) == 0) {
            
            // Write options.xml            
            file_put_contents('data/system/options.xml', '<?xml version="1.0" encoding="UTF-8"?><root><options><option_autoincrement>11</option_autoincrement></options><maintenance_status id="1"><value>off</value></maintenance_status><sitename id="2"><value>'.$_POST['sitename'].'</value></sitename><keywords id="3"><value>Site keywords</value></keywords><description id="4"><value>Site description</value></description><slogan id="5"><value>Super slogan here</value></slogan><defaultpage id="6"><value>home</value></defaultpage><siteurl id="7"><value>'.$_POST['siteurl'].'</value></siteurl><timezone id="8"><value>'.$_POST['timezone'].'</value></timezone><language id="9"><value>'.$language.'</value></language><maintenance_message id="10"><value>&amp;lt;h1&amp;gt;TemplateCMS :: Maintenance mode&amp;lt;/h1&amp;gt;</value></maintenance_message><theme_name id="11"><value>default</value></theme_name></root>');

            // Get users xml database
            $users_xml_db = getXMLdb('data/users/users.xml');

            // Insert new user with role = admin
            insertXMLRecord($users_xml_db, 'user', array('login'=>safeName(post('login')),
                                                         'password'=>encryptPassword(post('password')),
                                                         'email'=>post('email'),
                                                         'date_registered'=>time(),
                                                         'role'=>'admin'));

            // Write .htaccess
            $htaccess = file_get_contents('.htaccess');
            $save_htaccess_content = str_replace("/%siteurlhere%/",$RewriteBase,$htaccess);

            $handle = fopen ('.htaccess', "w");
            fwrite($handle,$save_htaccess_content);
            fclose($handle);

            // Installation done :)
            header("location: index.php?install=done");
        }
    }
?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title>Template CMS :: Install</title>
        <meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />
        <meta name="description" content="Template CMS install area" />
        <link rel="icon" href="<?php getSiteUrl(); ?>favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="<?php getSiteUrl(); ?>favicon.ico" type="image/x-icon" />
        <style>
            * {
                padding:0;
                margin:0;
            }
            html {
                height:100%;
            }

            body {
                background:#1B1B1D;
                color:#fff;
                font:normal 12px Verdana, Geneva, sans-serif;
                line-height:1.8em;
                margin:0;
                padding:0;
                text-align:left;
            }

            /* Main container
            ----------------------------------------------- */
            #container {                
                -moz-border-radius:3px;
                -webkit-border-radius:3px;
                background-color:#181819;
                border-radius:3px;
                font-size:small;
                font-size:small;
                padding:25px;
                left:50%;
                margin-left:-360px;
                margin-top:-250px;
                position:absolute;
                padding:25px 65px 25px 0;
                top:50%;
                width:670px;
            }

            input[type=submit] {
                -moz-border-radius:5px;
                -webkit-border-radius:5px;
                background: #fff;
                border: 1px solid #ccc;
                border-radius:3px;
                color: #707070;
                cursor:pointer;
                font-size: 11px;
                font-weight: bold;
                padding: 5px 12px;
            }

            table {
                width:100%;
            }
            
            td {
                padding:5px;
            }

            input[type=text],
            input[type=password] {
                border:1px solid #ccc;
                padding:5px;
                width:100%;
            }

            .login {
                text-transform: lowercase;
            }

            select {
                padding:5px;
            }

            .error {
                color:#FA7660;
            }

            .ok {
                color:#94FF94;
            }

            .warn {
                color: #F9FFA3;
            }
       </style>

    </head>
    <body>
        <!-- Block_wrapper -->
<?php
        if(PHP_VERSION_ID < 50200) {
            $errors['php'] = 'error';
        } else {
            $errors['php'] = '';
        }
       if(in_array('SimpleXML', $php_modules)) {
             $errors['simplexml'] = '';
        } else {
             $errors['simplexml'] = 'error';
        }

        if (function_exists('apache_get_modules')) {
            if(!in_array('mod_rewrite',apache_get_modules())) {
                $errors['mod_rewrite'] = 'error';
            } else {
                 $errors['mod_rewrite'] = '';
            }
        } else {
             $errors['mod_rewrite'] = '';
        }
?>
        <div id="container">
            <img src="<?php echo $site_url; ?>admin/templates/img/tcms2_installation.png" alt="" />
            <br /><br />
            <form action="install.php" method="post">
                <input type="hidden" name="php" value="<?php echo $errors['php']; ?>" />
                <input type="hidden" name="simplexml" value="<?php echo $errors['simplexml']; ?>" />
                <input type="hidden" name="mod_rewrite" value="<?php echo $errors['mod_rewrite']; ?>" />
                <table>
                    <tr>
                        <td width="100" align="right"><b>Site name</b></td><td><input name="sitename" type="text" value="<?php if(isset($_POST['sitename'])) echo toText($_POST['sitename']); ?>" /></td>
                    </tr>
                    <tr>
                        <td align="right"><b>Site url </b></td><td><input name="siteurl" type="text" value="<?php echo toText($site_url); ?>" /></td>
                    </tr>
                    <tr>
                        <td align="right"><b>Login</b></td><td><input class="login" name="login" value="<?php if(isset($_POST['login'])) echo toText($_POST['login']); ?>" type="text" /></td>
                    </tr>                 
                    <tr>
                        <td align="right"><b>Password</b></td><td><input name="password" type="password" /></td>
                    </tr>
                     <tr>
                        <td align="right"><b>Timezone</b></td>
                        <td>
                            <select name="timezone" style="width:102%">
                                <option value="Kwajalein">(GMT-12:00) International Date Line West</option>
                                <option value="Pacific/Samoa">(GMT-11:00) Midway Island, Samoa</option>
                                <option value="Pacific/Honolulu">(GMT-10:00) Hawaii</option>
                                <option value="America/Anchorage">(GMT-09:00) Alaska</option>
                                <option value="America/Los_Angeles">(GMT-08:00) Pacific Time (US &amp; Canada)</option>
                                <option value="America/Tijuana">(GMT-08:00) Tijuana, Baja California</option>
                                <option value="America/Denver">(GMT-07:00) Mountain Time (US &amp; Canada)</option>
                                <option value="America/Chihuahua">(GMT-07:00) Chihuahua, La Paz, Mazatlan</option>
                                <option value="America/Phoenix">(GMT-07:00) Arizona</option>
                                <option value="America/Regina">(GMT-06:00) Saskatchewan</option>
                                <option value="America/Tegucigalpa">(GMT-06:00) Central America</option>
                                <option value="America/Chicago">(GMT-06:00) Central Time (US &amp; Canada)</option>
                                <option value="America/Mexico_City">(GMT-06:00) Guadalajara, Mexico City, Monterrey</option>
                                <option value="America/New_York">(GMT-05:00) Eastern Time (US &amp; Canada)</option>
                                <option value="America/Bogota">(GMT-05:00) Bogota, Lima, Quito, Rio Branco</option>
                                <option value="America/Indiana/Indianapolis">(GMT-05:00) Indiana (East)</option>
                                <option value="America/Caracas">(GMT-04:30) Caracas</option>
                                <option value="America/Halifax">(GMT-04:00) Atlantic Time (Canada)</option>
                                <option value="America/Manaus">(GMT-04:00) Manaus</option>
                                <option value="America/Santiago">(GMT-04:00) Santiago</option>
                                <option value="America/La_Paz">(GMT-04:00) La Paz</option>
                                <option value="America/St_Johns">(GMT-03:30) Newfoundland</option>
                                <option value="America/Argentina/Buenos_Aires">(GMT-03:00) Buenos Aires</option>
                                <option value="America/Sao_Paulo">(GMT-03:00) Brasilia</option>
                                <option value="America/Godthab">(GMT-03:00) Greenland</option>
                                <option value="America/Montevideo">(GMT-03:00) Montevideo</option>
                                <option value="America/Argentina/Buenos_Aires">(GMT-03:00) Georgetown</option>
                                <option value="Atlantic/South_Georgia">(GMT-02:00) Mid-Atlantic</option>
                                <option value="Atlantic/Azores">(GMT-01:00) Azores</option>
                                <option value="Atlantic/Cape_Verde">(GMT-01:00) Cape Verde Is.</option>
                                <option value="Europe/London">(GMT) Greenwich Mean Time : Dublin, Edinburgh, Lisbon, London</option>
                                <option value="Atlantic/Reykjavik">(GMT) Monrovia, Reykjavik</option>
                                <option value="Africa/Casablanca">(GMT) Casablanca</option>
                                <option value="Europe/Belgrade">(GMT+01:00) Belgrade, Bratislava, Budapest, Ljubljana, Prague</option>
                                <option value="Europe/Sarajevo">(GMT+01:00) Sarajevo, Skopje, Warsaw, Zagreb</option>
                                <option value="Europe/Brussels">(GMT+01:00) Brussels, Copenhagen, Madrid, Paris</option>
                                <option value="Africa/Algiers">(GMT+01:00) West Central Africa</option>
                                <option value="Europe/Amsterdam">(GMT+01:00) Amsterdam, Berlin, Bern, Rome, Stockholm, Vienna</option>
                                <option value="Europe/Minsk">(GMT+02:00) Minsk</option>
                                <option value="Africa/Cairo">(GMT+02:00) Cairo</option>
                                <option value="Europe/Helsinki">(GMT+02:00) Helsinki, Kyiv, Riga, Sofia, Tallinn, Vilnius</option>
                                <option value="Europe/Athens">(GMT+02:00) Athens, Bucharest, Istanbul</option>
                                <option value="Asia/Jerusalem">(GMT+02:00) Jerusalem</option>
                                <option value="Asia/Amman">(GMT+02:00) Amman</option>
                                <option value="Asia/Beirut">(GMT+02:00) Beirut</option>
                                <option value="Africa/Windhoek">(GMT+02:00) Windhoek</option>
                                <option value="Africa/Harare">(GMT+02:00) Harare, Pretoria</option>
                                <option value="Asia/Kuwait">(GMT+03:00) Kuwait, Riyadh</option>
                                <option value="Asia/Baghdad">(GMT+03:00) Baghdad</option>
                                <option value="Africa/Nairobi">(GMT+03:00) Nairobi</option>
                                <option value="Asia/Tbilisi">(GMT+03:00) Tbilisi</option>
                                <option value="Europe/Moscow">(GMT+03:00) Moscow, St. Petersburg, Volgograd</option>
                                <option value="Asia/Tehran">(GMT+03:30) Tehran</option>
                                <option value="Asia/Muscat">(GMT+04:00) Abu Dhabi, Muscat</option>
                                <option value="Asia/Baku">(GMT+04:00) Baku</option>
                                <option value="Asia/Yerevan">(GMT+04:00) Yerevan</option>
                                <option value="Asia/Yekaterinburg">(GMT+05:00) Ekaterinburg</option>
                                <option value="Asia/Karachi">(GMT+05:00) Islamabad, Karachi</option>
                                <option value="Asia/Tashkent">(GMT+05:00) Tashkent</option>
                                <option value="Asia/Kolkata">(GMT+05:30) Chennai, Kolkata, Mumbai, New Delhi</option>
                                <option value="Asia/Colombo">(GMT+05:30) Sri Jayawardenepura</option>
                                <option value="Asia/Katmandu">(GMT+05:45) Kathmandu</option>
                                <option value="Asia/Dhaka">(GMT+06:00) Astana, Dhaka</option>
                                <option value="Asia/Novosibirsk">(GMT+06:00) Almaty, Novosibirsk</option>
                                <option value="Asia/Rangoon">(GMT+06:30) Yangon (Rangoon)</option>
                                <option value="Asia/Krasnoyarsk">(GMT+07:00) Krasnoyarsk</option>
                                <option value="Asia/Bangkok">(GMT+07:00) Bangkok, Hanoi, Jakarta</option>
                                <option value="Asia/Beijing">(GMT+08:00) Beijing, Chongqing, Hong Kong, Urumqi</option>
                                <option value="Asia/Ulaanbaatar">(GMT+08:00) Irkutsk, Ulaan Bataar</option>
                                <option value="Asia/Kuala_Lumpur">(GMT+08:00) Kuala Lumpur, Singapore</option>
                                <option value="Asia/Taipei">(GMT+08:00) Taipei</option>
                                <option value="Australia/Perth">(GMT+08:00) Perth</option>
                                <option value="Asia/Seoul">(GMT+09:00) Seoul</option>
                                <option value="Asia/Tokyo">(GMT+09:00) Osaka, Sapporo, Tokyo</option>
                                <option value="Asia/Yakutsk">(GMT+09:00) Yakutsk</option>
                                <option value="Australia/Darwin">(GMT+09:30) Darwin</option>
                                <option value="Australia/Adelaide">(GMT+09:30) Adelaide</option>
                                <option value="Australia/Sydney">(GMT+10:00) Canberra, Melbourne, Sydney</option>
                                <option value="Australia/Brisbane">(GMT+10:00) Brisbane</option>
                                <option value="Australia/Hobart">(GMT+10:00) Hobart</option>
                                <option value="Asia/Vladivostok">(GMT+10:00) Vladivostok</option>
                                <option value="Pacific/Guam">(GMT+10:00) Guam, Port Moresby</option>
                                <option value="Asia/Magadan">(GMT+11:00) Magadan, Solomon Is., New Caledonia</option>
                                <option value="Pacific/Fiji">(GMT+12:00) Fiji, Kamchatka, Marshall Is.</option>
                                <option value="Pacific/Auckland">(GMT+12:00) Auckland, Wellington</option>
                                <option value="Pacific/Tongatapu">(GMT+13:00) Nukualofa</option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td align="right"><b>Email</b></td><td><input name="email" value="<?php if(isset($_POST['email'])) echo toText($_POST['email']); ?>" type="text" /></td>
                    </tr>
                    <tr>
                        <td align="right"></td><td align="right"><input type="submit" name="install_submit" value="Install" /></td>
                    </tr>
                </table>
            </form>
            <div style="padding-left: 40px;float:left;">
            <ul>
            <?php
                if (in_array('SimpleXML', $php_modules)) {
                    echo '<span class="ok"><li>SimpleXML module</li></span>';
                } else {                    
                    echo '<span class="error"><li>SimpleXML module is required</li></span>';
                }

                if (function_exists('apache_get_modules')) {
                    if ( ! in_array('mod_rewrite',apache_get_modules())) {
                        echo '<span class="error"><li>Apache Mod Rewrite is required</li></span>';
                    } else {
                        echo '<span class="ok"><li>Apache Mod Rewrite</li></span>';
                    }
                } else {
                    echo '<span class="ok"><li>Apache Mod Rewrite</li></span>';
                }

                if (PHP_VERSION_ID < 50200) {
                    echo '<span class="error"><li>PHP 5.2 or greater is required</li></span>';
                    $errors['php'] = yes;
                } else {                        
                    echo '<span class="ok"><li>PHP Version '.PHP_VERSION.'</li></span>';
                }
                
                foreach ($dir_array as $dir) {
                    if (is_writable($dir.'/')) {
                        echo '<span class="ok"><li> Directory: <b>'.$dir.'</b> writable ['.checkDirPerm($dir.'/').']</li></span>';
                    } else {
                        echo '<span class="error"><li> Directory: <b>'.$dir.'</b> not writable ['.checkDirPerm($dir.'/').']</li></span>';
                    }
                }

                if (is_writable(__FILE__)){
                    echo '<span class="ok"><li>Install script writable</li></span>';
                } else {
                    echo '<span class="error"><li>Install script not writable</li></span>';
                }

                if (isset($errors['sitename']))  echo '<span class="error"><li>'.$errors['sitename'].'</li></span>';
                if (isset($errors['siteurl']))   echo '<span class="error"><li>'.$errors['siteurl'].'</li></span>';
                if (isset($errors['login']))     echo '<span class="error"><li>'.$errors['login'].'</li></span>';
                if (isset($errors['password']))  echo '<span class="error"><li>'.$errors['password'].'</li></span>';
                if (isset($errors['email']))     echo '<span class="error"><li>'.$errors['email'].'</li></span>';
            ?>
            </ul>
            </div>
        </div>

        <!-- /Block_wrapper -->
    </body>
</html>