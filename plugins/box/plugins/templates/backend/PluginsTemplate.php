<div>&nbsp;</div>
<!-- Plugins_box_list -->
<div style="border-bottom:1px solid #E9EAEA; margin-bottom:10px; width: 100%;">
    <div style="float: left; width: 8%; align:center; text-align: center;">
        <?php htmlImg($site_url.'plugins/box/plugins/img/plugin.png',array('style'=>'vertical-align: middle','alt'=>'plugin')); ?>
    </div>
    <div style="float: left; width: 90%;">
        <?php
            htmlHeading('Box plugins',2);
            foreach($plugins_info as $plugin) {
                if($plugin['privilege'] == 'box') {
                    echo $plugin['name'].' : ';
                }
            }
        ?>
        <?php htmlBr(1); ?>
        <b>Author:</b> <a href="<?php echo $plugin['author_uri']; ?>">
            <?php
                // Author of box plugins is Awilum                
                echo 'Awilum';
            ?>
        </a>
    </div>
    <div style="clear:both"></div>
</div>
<!-- /Plugins_box_list -->

<!-- Plugins_main_list -->
<?php
    foreach($plugins_info as $plugin) {
        if($plugin['privilege'] !== 'box') {
?>
<div style="border-bottom:1px solid #E9EAEA; margin-bottom:10px; width: 100%;">		
    <div style="float: left; width: 8%; align:center; text-align: center;">
        <?php htmlImg($site_url.'plugins/box/plugins/img/plugin.png',array('style'=>'vertical-align: middle','alt'=>'plugin')); ?>
    </div>
    <div style="float: left; width: 90%;">
            <?php htmlHeading($plugin['name'],2); ?>
            <?php echo $plugin['description']; ?>
        <div style="float: right; width: 10%;">
            <?php htmlButtonDelete(lang('plugins_delete'), 'index.php?id=plugins&delete_plugin='.$plugin['filename']); ?>
        </div>
            <?php htmlBr(1); ?>
        <b>Version:</b> <?php echo $plugin['version']; ?>
        <b>Author:</b> <a href="<?php echo $plugin['author_uri']; ?>"><?php echo $plugin['author']; ?></a>
    </div>
    <div style="clear:both"></div>
</div>
<?php }
    } 
?>
<!-- /Plugins_main_list -->