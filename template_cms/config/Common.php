<?php  if (!defined('TEMPLATE_CMS_ACCESS')) exit('No direct script access allowed');

    // Template CMS constants
    define('TEMPLATE_CMS_DEBUG',false);
    define('TEMPLATE_CMS_GZIP',false);
    define('TEMPLATE_CMS_GZIP_STYLES',false);
    define('TEMPLATE_CMS_VERSION','2.1.1');
    define('TEMPLATE_CMS_VERSION_ID',20101);
    define('TEMPLATE_CMS_SITEURL','http://template-cms.ru');    
    define('TEMPLATE_CMS_MAIN_PATH',$admin_path.'template_cms/');
    define('TEMPLATE_CMS_THEMES_PATH',$admin_path.'themes/');
    define('TEMPLATE_CMS_PLUGINS_PATH',$admin_path.'plugins/');
    define('TEMPLATE_CMS_BOX_PLUGINS_PATH',$admin_path.'plugins/box/');
    define('TEMPLATE_CMS_HELPERS_PATH',$admin_path.'template_cms/helpers/');
    define('TEMPLATE_CMS_ENGINE_PATH',$admin_path.'template_cms/engine/');
    define('TEMPLATE_CMS_DATA_PATH','data/');
    define('TEMPLATE_CMS_USERS_PATH',$admin_path.TEMPLATE_CMS_DATA_PATH.'users/');
    define('TEMPLATE_CMS_LOGIN_SLEEP',1);
    define('TEMPLATE_CMS_PASSWORD_SALT','YOUR_SALT_HERE');
    define('TEMPLATE_CMS_DATE_FORMAT','Y-m-d / H:i:s');
    define('TEMPLATE_CMS_EVAL_PHP',false);