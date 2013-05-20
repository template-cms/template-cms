<!-- Blocks_buttons -->
<div id="section-bar">
    <?php htmlButton(lang('blocks_add'),'?id=themes&sub_id=blocks&action=add_block'); ?>
    <?php runHook('admin_blocks_extra_buttons'); ?>
</div>
<!-- /Blocks_buttons -->

<div style="clear:both"></div>

<!-- Blocks_list -->
<table class="admin-table">
    <thead class="admin-table-header">
        <tr><td class="admin-table-field"><?php echo lang('blocks_blocks'); ?></td><td></td></tr>
    </thead>
    <tbody class="admin-table-content">
    <?php if (count($blocks_list) != 0) { foreach ($blocks_list as $block) { ?>
     <tr class="admin-table-tr">
        <td class="admin-table-titles admin-table-field">
            <?php echo htmlLink(fileName($block), 'index.php?id=themes&sub_id=blocks&action=edit_block&filename='.fileName($block)); ?>
        </td>
        <td class="admin-table-field" align="right">
            <?php htmlButtonEdit(lang('blocks_edit'), 'index.php?id=themes&sub_id=blocks&action=edit_block&filename='.fileName($block)); ?>
            <?php htmlButtonDelete(lang('blocks_delete'), 'index.php?id=themes&sub_id=blocks&action=delete_block&filename='.$block); ?>
        </td>
     </tr>
    <?php } } ?>
    </tbody>
</table>
<!-- /Blocks_list -->