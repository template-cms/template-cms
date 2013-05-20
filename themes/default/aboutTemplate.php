<!DOCTYPE html>
<html lang="en">
    <head>        
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
        <title><?php getSiteName();echo ' - ';getTitle();?></title>
        <meta name="description" content="<?php getDescription(); ?>" />
        <meta name="keywords" content="<?php getKeywords(); ?>" />
        <link rel="icon" href="<?php getSiteUrl(); ?>favicon.ico" type="image/x-icon" />
        <link rel="shortcut icon" href="<?php getSiteUrl(); ?>favicon.ico" type="image/x-icon" />
        <?php loadCSS('themes/default/css/','style.css'); ?>
        <?php runHook('theme_header'); ?>
        <!--[if IE]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <link rel="stylesheet" type="text/css" href="themes/default/css/iehacks.css" />
        <![endif]-->
    </head>
    <body>
        <div><br /></div>
    <header>
        <div id="logo">
            <hgroup>
                <h1><?php getSiteName(); ?></h1>
                <h2><?php getSiteSlogan(); ?></h2>
            </hgroup>
        </div>
        <nav>
            <ul>
                <?php getSiteMenu('mainmenu'); ?>
            </ul>
        </nav>
    </header>
    <div id="content">
        <section class="hpage">
            <article class="hentry">
            <div>
                <?php runHook('theme_pre_content'); ?>
            </div>

            <div>
                <?php getContent(); ?>
            </div>

            <div>
                <?php runHook('theme_post_content'); ?>
            </div>
            </article>
        </section>
    </div>
    <footer id="main-footer">
        <section id="footer">
            <div class="floatleft"><?php getBlock('footer-links'); ?></div>
            <div class="floatright"><?php runHook('theme_footer'); ?><?php getCopyright(); ?></div>
        </section>
    </footer>
</body>
</html>