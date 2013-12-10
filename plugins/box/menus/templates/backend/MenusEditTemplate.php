<?php
    htmlFormOpen('?id=themes&sub_id=menus');
    htmlFormInput(array('value'=>basename(get('filename'),'.xml'),'name'=>'edit_menus_name','size'=>'30'), lang('menus_name'));
    htmlFormHidden('edit_menus_old_name', basename(get('filename'),'.xml'));
    htmlNbsp(2);
    htmlFormClose(true, array('name'=>'submit_edit_menus_name','value'=>lang('menu_rename')));
?>

<div style="clear:both">&nbsp;</div>

<table class="admin-table">
    <thead class="admin-table-header">
        <tr><td class="admin-table-field"><?php echo lang('menu_menu'); ?> ( <?php echo basename(get('filename'),'.xml');?> )</td><td align="center"><?php echo lang('menu_order'); ?></td><td></td></tr>
    </thead>
    <tbody class="admin-table-content">
    <?php if (count($menus_records) > 0) { foreach ($menus_records as $menu) { ?>
    <tr class="admin-table-tr">
        <td class="admin-table-titles admin-table-field">
            <a href="<?php echo findUrl($menu['menu_link']); ?>" target="_blank"><?php echo $menu['menu_name']; ?></a>
        </td>
        <td class="admin-table-field date" align="center">
            <?php echo $menu['menu_order']; ?>
        </td>
        <td class="admin-table-field" align="right">
            <?php htmlButtonEdit(lang('menu_edit'), 'index.php?id=themes&sub_id=menus&action=edit_menus&filename='.get('filename').'&edit='.$menu['id']); ?>
            <?php htmlButtonDelete(lang('menu_delete'), 'index.php?id=themes&sub_id=menus&action=edit_menus&filename='.get('filename').'&delete='.$menu['id']); ?>
        </td>
     </tr>
    <?php } } ?>
    </tbody>
</table>
<?php
    if(isGet('edit')) {
?>
<p style="float:right">
<?php htmlBr();htmlButton(lang('menu_add'),'?id=themes&sub_id=menus&action=edit_menus&filename='.get('filename')); ?>
</p>
<?php
        htmlFormOpen('index.php?id=themes&sub_id=menus&action=edit_menus&filename='.get('filename').'&edit='.get('edit'));
        $xml_db = getXMLdb($menus_path.get('filename'));
        $menu_xml = selectXMLRecord($xml_db, "//menu[@id='".(int)get('edit')."']");
        if (isPost('submit_edit_menu')) {
            $menu_link = ltrim(post('edit_menu_link'), '/');
            if ($_SESSION['token'] != trim(post('token')) || $_SESSION['tk_esp'] < $start_time)  $err = 'token error'; else $err = '';
            if (!$err)  updateXMLRecord($xml_db, 'menu',(int)get('edit'), array('menu_order'=>post('edit_menu_order'),
                                                                    'menu_name'=>post('edit_menu_name'),
                                                                    'menu_link'=>$menu_link,
                                                                    'menu_target'=>post('edit_menu_target')));
            redirect('index.php?id=themes&sub_id=menus&action=edit_menus&filename='.get('filename'));
        } else {
            $_SESSION['tk_esp'] = $start_time + $token_expire;
            $_SESSION['token']  = md5($start_time.$_SESSION['user_id'].sha1($start_time.$_SESSION['user_login']));
        }
?>
<?php htmlAdminHeading(lang('menu_edit'))?>
<?php if($menu_xml !== null) { ?>
<table width="800px" align="center">
    <tr>
        <td width="230px">
            <?php htmlFormInput(array('value'=>toText($menu_xml->menu_name),'name'=>'edit_menu_name','size'=>'30'), lang('menu_name')); ?>
        </td>
        <td width="230px">
            <?php htmlFormInput(array('value'=>toText($menu_xml->menu_link),'name'=>'edit_menu_link','size'=>'30'), lang('menu_link')); ?>
        </td>
        <td width="230px">
            <?php htmlSelect($options, array('name'=>'edit_menu_target','style'=>'width:200px;'),lang('menu_target'),$menu_xml->menu_target); ?>
        </td>
        <td width="110px">
            <?php htmlFormInput(array('value'=>toText($menu_xml->menu_order),'name'=>'edit_menu_order','size'=>'3'), lang('menu_order')); ?>
        </td>
        <?php  if(isset($err) && $err) {?>
        <td width="200px">
            <?php echo $err; ?>
        </td>
        <?php }?>
    </tr>
    <tr>
        <td style="vertical-align: middle;">
            <?php htmlFormHidden('token', $_SESSION['token']); htmlFormClose(true, array('name'=>'submit_edit_menu','value'=>lang('menu_save'))); ?>
        </td>
    </tr>
</table>
<?php } ?>
<?php
    } else {
        $_SESSION['tk_esp'] = $start_time + $token_expire;
        $_SESSION['token']  = md5($start_time.$_SESSION['user_id'].sha1($start_time.$_SESSION['user_login']));      
        htmlFormOpen('index.php?id=themes&sub_id=menus&action=edit_menus&filename='.get('filename'));
?>
<?php htmlAdminHeading(lang('menu_add'))?>
<table width="800px" align="center">
    <tr>
        <td width="230px">
            <?php htmlFormInput(array('name'=>'add_menu_name','size'=>'30'), lang('menu_name')); ?>
        </td>            
        <td width="230px">
            <?php htmlFormInput(array('name'=>'add_menu_link','size'=>'30'), lang('menu_link')); ?>
        </td>
        <td width="230px">
            <?php htmlSelect($options, array('name'=>'add_menu_target','style'=>'width:200px;'),lang('menu_target')); ?>
        </td>
        <td width="110px">
            <?php htmlFormInput(array('name'=>'add_menu_order','size'=>'3'),lang('menu_order')); ?>
        </td>
        <?php  if(isset($err) && $err) {?>
        <td width="200px">
            <?php echo $err; ?>
        </td>
        <?php }?>
    </tr>
    <tr>
        <td style="vertical-align: middle;">
            <?php htmlFormHidden('token', $_SESSION['token']); htmlFormClose(true, array('name'=>'submit_add_menu','value'=>lang('menu_save'))); ?>
        </td>
    </tr>
</table>
<?php } ?>