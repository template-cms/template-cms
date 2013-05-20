<!-- Menus_add_form -->
<?php
    htmlFormOpen('?id=themes&sub_id=menus');
    htmlFormInput(array('name'=>'add_menus_name','size'=>'30'), lang('menus_name'));
    htmlNbsp(2);
    htmlFormClose(true, array('name'=>'submit_add_menus','value'=>lang('menu_add')));
?>
<!-- /Menus_add_form -->

<div style="clear:both">&nbsp;</div>

<!-- Menus_list -->
<table class="admin-table">
    <thead class="admin-table-header">
        <tr><td class="admin-table-field"><?php echo lang('menu_menu'); ?></td><td></td></tr>
    </thead>
    <tbody class="admin-table-content">
    <?php foreach ($menus_list as $menus) { ?>
     <tr class="admin-table-tr">
        <td class="admin-table-titles admin-table-field">
            <?php echo htmlLink(basename($menus,'.xml'), 'index.php?id=themes&sub_id=menus&action=edit_menus&filename='.$menus); ?>
        </td>
        <td class="admin-table-field" align="right">
            <?php htmlButtonEdit(lang('menu_edit'), 'index.php?id=themes&sub_id=menus&action=edit_menus&filename='.$menus); ?>
            <?php htmlButtonDelete(lang('menu_delete'), 'index.php?id=themes&sub_id=menus&action=delete_menus&filename='.$menus); ?>
        </td>
     </tr>
    <?php } ?>
    </tbody>
</table>
<!-- /Menus_list -->