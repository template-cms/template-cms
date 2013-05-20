<script>
    $().ready(function(){
        $('#debugger-button').click( function(){
            $('#debugger').slideToggle("slow");
            $('#system-content').slideToggle("slow");
        });
    });
</script>

<?php if($old_cms_version_message) { ?>
    <div class="message-warning">
        <?php echo lang('system_old'); ?>
        <?php echo $api_common->TEMPLATE_CMS_VERSION; ?>
        <a href="http://template-cms.ru/download/system"><?php echo lang('system_download_latest'); ?></a>
    </div>
<?php } ?>

<div id="section-bar">
    <?php htmlBr(1); ?>
    <?php htmlButton(lang('system_sitemap_create'), 'index.php?id=system&sitemap=create');   ?>
    <?php    
        if('off' == getOption('maintenance_status')) {
            htmlButton(lang('system_maintenance_on'), 'index.php?id=system&maintenance=on');
        } else {
            htmlButton(lang('system_maintenance_off'), 'index.php?id=system&maintenance=off', lang('system_maintenance_off'), true, 'btn text-pink');
        }
        echo'<span id="debugger-button" class="btn"><a href="" onclick="return false">'.lang('system_core').'</a></span>';
    ?>

    <?php runHook('admin_system_extra_buttons'); ?>
</div>
 <br /> <br />
 <div id="system-content">
 <?php
    htmlAdminHeading(lang('system_site_settings'));    
    htmlFormOpen('index.php?id=system');
    htmlFormInput(array('value'=>getOption('sitename'),'name'=>'site_name'),lang('system_sitename'));
    htmlFormInput(array('value'=>getOption('description'),'name'=>'site_description'),lang('system_description'));
    htmlFormInput(array('value'=>getOption('keywords'),'name'=>'site_keywords'),lang('system_keywords'));
    htmlFormInput(array('value'=>getOption('slogan'),'name'=>'site_slogan'),lang('system_siteslogan'));
    htmlSelect($pages_array,array('style'=>'width:200px;','name'=>'site_default_page'),lang('system_defpage'),getOption('defaultpage'));
    htmlBr(2);
    htmlFormClose(true,array('value'=>lang('system_save'),'name'=>'edit_main_settings'));
    htmlBr(1);    
    htmlAdminHeading(lang('system_system_settings'));
    htmlFormOpen('index.php?id=system');
    htmlFormInput(array('value'=>getOption('siteurl'),'name'=>'system_url'),lang('system_url'));
    htmlSelect($timezone_array,array('style'=>'width:400px;','name'=>'system_timezone'),lang('system_timezone'),getOption('timezone'));
    htmlMemo('site_maintenance_message', array('style'=>'width:500px;height:50px;'), getOption('maintenance_message'), lang('system_maintenance'));
    htmlSelect($languages_array,array('style'=>'width:100px;','name'=>'system_language'),lang('system_language'),getOption('language'));    
    htmlBr(2);
    htmlFormClose(true,array('value'=>lang('system_save'),'name'=>'edit_system_settings'));

    // Its mean that you can add your own actions for this plugin
    runHook('admin_system_extra_template_actions');    
?>
</div>
<div id="debugger">
    <p>System</p><hr><br />
    <p><?php echo lang('system_version'); ?>: <?php echo TEMPLATE_CMS_VERSION; ?></p>
    <p><?php echo lang('system_version_id'); ?>: <?php echo TEMPLATE_CMS_VERSION_ID; ?></p>
    <p><?php echo lang('system_gzip'); ?>: <?php if (TEMPLATE_CMS_GZIP) { echo lang('system_debuging_on'); }else{ echo lang('system_debuging_off'); } ?> </p>
    <p><?php echo lang('system_debuging'); ?>: <?php if (TEMPLATE_CMS_DEBUG) { echo lang('system_debuging_on'); }else{ echo lang('system_debuging_off'); } ?> </p>
    <br />
    <p>Plugin API</p><hr><br />
    <p><?php echo lang('system_plugins_active'); ?>: <?php echo count($plugins); ?> <ul><?php foreach ($plugins as $plugin) echo '<li>'.$plugin['id'].'</li>';; ?></ul></p>
    <p><?php echo lang('system_hooks_registered'); ?>: <?php echo count($hooks); ?> <ul><?php foreach ($hooks as $key => $hook) echo '['.$hooks[$key]['hook'].']<br />'; ?> </ul></p>
    <p><?php echo lang('system_filters_registered'); ?>: <?php echo count($filters); ?>
    <ul>
    <?php
        foreach ($filters as $k => $filter) {
            echo '['.$k.']';
            foreach ($filter as $key => $f) {
                foreach ($f as $s) {
                    echo '<li>'.$s['function'].'</li>';
                }
            }
        }
    ?>
    </ul>
    </p>
</div>