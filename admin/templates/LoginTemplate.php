<?php  if (!defined('TEMPLATE_CMS_ACCESS')) exit('No direct script access allowed'); ?>

<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml" lang="en" xml:lang="en">
    <head>
        <title>Template CMS :: Admin</title>
        <meta http-equiv="content-type" content="application/xhtml+xml; charset=utf-8" />
        <meta name="description" content="Template CMS admin area" />
        <link rel="icon" href="<?php getSiteUrl(); ?>favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="<?php getSiteUrl(); ?>favicon.ico" type="image/x-icon" />
        <link rel="stylesheet" href="templates/css/admin_login.css" media="all" type="text/css" />
    </head>
    <body>
        <!-- Block_wrapper -->

        <div id="container">
            <div id="logo"><img src="templates/img/tcms2.png" alt="" /></div>
            <form action="" method="post">
                <table>
                    <tr>
                        <td align="right"><b><?php echo lang('users_login')?></b></td><td><input name="login" type="text" /></td>
                    </tr>
                    <tr>
                        <td align="right"><b><?php echo lang('users_password')?></b></td><td><input name="password" type="password" /></td>
                    </tr>
                    <tr>
                        <td></td><td><span class="error"><?php echo $login_error; ?></span></td>
                    </tr>
                    <tr>
                        <td></td><td align="right"><input type="submit" name="login_submit" value="<?php echo lang('users_enter')?>" /></td>
                    </tr>
                </table>
            </form>
        </div>

        <!-- /Block_wrapper -->
    </body>
</html>