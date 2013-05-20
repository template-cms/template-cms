<div>&nbsp;</div>

<!-- Plugins_list -->
<?php
    foreach($plugins_to_intall as $plug) {        
    $plugin_xml = getXML($plug['path']);  
?>
<div style="border-bottom:1px solid #E9EAEA; margin-bottom:10px; width: 100%;">
    <div style="float: left; width: 8%; align:center; text-align: center;">
        <?php htmlImg($site_url.'plugins/box/plugins/img/plugin.png',array('style'=>'vertical-align: middle','alt'=>'plugin')); ?>
    </div>
    <div style="float: left; width: 90%;">
            <?php htmlHeading($plugin_xml->plugin_name,2); ?>
            <?php echo $plugin_xml->plugin_description; ?>
        <div style="float: right; width: 25%;">
            <?php htmlButtonEdit(lang('plugins_install_do'), 'index.php?id=plugins&sub_id=pluginsinstaller&install='.$plug['plugin']); ?>
            <?php htmlButtonDelete(lang('plugins_delete'), 'index.php?id=plugins&sub_id=pluginsinstaller&delete_plugin_from_server='.lowercase(basename($plug['path'],'Plugin.xml'))); ?>
        </div>
            <?php htmlBr(1); ?>
        <b>Version:</b> <?php echo $plugin_xml->plugin_version; ?>
        <b>Author:</b> <a href="<?php echo $plugin_xml->plugin_author_uri; ?>"><?php echo $plugin_xml->plugin_author; ?></a>
    </div>
    <div style="clear:both"></div>
</div>
<?php } ?>
<!-- /Plugins_list -->