<!-- Filesmanger_path -->
<div style="background:#E9EAEA; width:595px;-webkit-border-radius: 5px;
-moz-border-radius: 5px;
border-radius: 5px; padding-left:5px;">
    <?php
        $path_parts = explode ('/',$path);
        $s = '';
        foreach($path_parts as $p) {
            $s .= $p.'/';
            echo '<span style="color:#ccc;">/</span> <a href="index.php?id=pages&sub_id=filesmanager&path='.$s.'">'.$p.'</a>  ';     
        }    
    ?>
</div>
<!-- /Filesmanger_path -->

<!-- Filesmanager_directories -->
<table border="0" cellspacing="5" cellpadding="5" class="filesmanager-main">
<?php if(isset($dir_list)) foreach ($dir_list as $dir) { ?>
<tr class="filesmanager-tr">

    <td class="filesmanager-td">                    
             <a style="text-decoration:none;" href="<?php echo 'index.php?id=pages&sub_id=filesmanager&path='.$path.$dir.'/'; ?>"><div class="file-ext">DIR</div></a>
    </td>
    <td valign="top" class="filesmanager-td" width="600px">
            <p><?php htmlLink($dir,'index.php?id=pages&sub_id=filesmanager&path='.$path.$dir.'/');?></p>            
            <p><?php echo lang('filesmanager_directory'); ?></p>
    </td>
    <td  class="filesmanager-td">                       
        <?php htmlButtonDelete(lang('filesmanager_delete'), 'index.php?id=pages&sub_id=filesmanager&delete_dir='.$dir.'&path='.$path); ?>
    </td>

</tr>
<?php } ?>
<!-- /Filesmanager_directories -->

<!-- Filesmanager_files -->
<?php if(isset($files_list)) foreach ($files_list as $file) { ?>
<?php $ext = fileExt($file); ?>
<tr class="filesmanager-tr">
<?php if(!in_array($ext,$forbidden_types)) { ?>
	<td class="filesmanager-td">					
             <a style="text-decoration:none;" target="_blank" href="<?php echo $site_url.'data/'.$path.$file; ?>"><div class="file-ext"><?php echo $ext; ?></div></a>
	</td>
	<td valign="top" class="filesmanager-td" width="600px">
            <p><?php htmlLink(basename($file),$site_url.'data/'.$path.$file,'_blank');?> <span class="filesize"><?php echo byteFormat(filesize($files_path.'/'.$file)); ?></span></p>
            <p><?php echo dateFormat(fileLastChange($files_path.'/'.$file)); ?></p>
	</td>
    <td  class="filesmanager-td">                       
        <?php htmlButtonDelete(lang('filesmanager_delete'), 'index.php?id=pages&sub_id=filesmanager&delete_file='.$file.'&path='.$path); ?>
    </td>
<?php } ?>
</tr>
<?php } ?>
</table>
<!-- /Filesmanager_files -->

<!-- Filesmanager_uload_files_form -->
<div id="filesmanager-upload">
    <?php htmlFormOpen('index.php?id=pages&sub_id=filesmanager&path='.$path,'post',true); ?>
    <?php htmlFormFile('file',25); htmlBr(2); ?>
    <?php htmlFormClose(true,array('name'=>'upload_file','value'=>lang('filesmanager_upload'))); ?>
</div>
<!-- /Filesmanager_uload_files_form -->

<div style="clear:both"></div>