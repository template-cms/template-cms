<!-- Backups_list -->
<table border="0" cellspacing="5" cellpadding="5" class="filesmanager-main">
<?php if(count($backups_list) > 0) { foreach ($backups_list as $backup) { ?>
<tr class="filesmanager-tr">
	<td class="filesmanager-td">
        <a style="text-decoration:none;" href="<?php echo getSiteUrl(false).'admin/index.php?id=system&sub_id=backup&download='.$backup; ?>"><div class="file-ext">zip</div></a>
	</td>
	<td valign="top" class="filesmanager-td" width="600px">
            <p><?php htmlLink($backup, getSiteUrl(false).'admin/index.php?id=system&sub_id=backup&download='.$backup);?> <span class="filesize"><?php echo byteFormat(filesize('../data/backups/'.$backup)); ?></span> </p>
            <p><?php echo dateFormat(fileLastChange($backups_path.$backup)); ?></p>
	</td>
	<td  class="filesmanager-td">
            <?php htmlButtonDelete(lang('backup_delete'), 'index.php?id=system&sub_id=backup&delete_file='.$backup); ?>
	</td>
</tr>
<?php } } ?>
</table>
<!-- /Backups_list -->

<!-- Backups_create -->
<div id="filesmanager-upload">
    <?php htmlFormOpen('index.php?id=system&sub_id=backup'); ?>    
    <?php htmlFormClose(true,array('name'=>'create_backup','value'=>lang('backup_create'))); ?>
</div>
<!-- /Backups_create -->

<div style="clear:both"></div>
