<!-- Pages_buttons -->
<div id="section-bar">
    <?php htmlButton(lang('pages_add'),'?id=pages&action=add_page'); ?>
    <?php runHook('admin_pages_extra_buttons'); ?>
</div>
<!-- /Pages_buttons -->

<div style="clear:both"></div>

<!-- Pages_list -->
<table class="admin-table">
    <thead class="admin-table-header">
        <tr><td class="admin-table-field"><?php echo lang('pages_pages'); ?></td><td class="admin-table-field" align="center"><?php echo lang('pages_date'); ?></td><td></td></tr>
    </thead>
    <tbody class="admin-table-content">
    <?php
	    if (count($pages) != 0) { 
                foreach ($pages as $page) {
                    if ($page['parent'] != '') { $dash = htmlArrow('right',false).'&nbsp;&nbsp;'; } else { $dash = ""; }
     ?>
     <?php if ($page['parent'] == '') $parent_style='style="background:#F2F2F2;"'; else $parent_style = ''; ?>
     <tr <?php echo $parent_style; ?> class="admin-table-tr">        
        <td  class="admin-table-titles admin-table-field">
            <?php
                if ($page['parent'] != '') {
                    $parent = $page['parent'].'/';
                } else {
                    $parent = '';
                }
            ?>
            <?php
                if ($page['parent'] != '') echo '&nbsp;';
            ?>
            <?php
                if($page['slug'] == 'error404') {
                    $error404_span_begin = '<span class="error404-link">';
                    $error404_span_end = '</span>';
                } else {
                    $error404_span_begin = '';
                    $error404_span_end = '';
                }
            ?>
            <?php echo $error404_span_begin.$dash; htmlLink(toText($page['title']), $site_url.$parent.$page['slug'],'_blank').$error404_span_end; ?>
            
        </td>
        <td class="admin-table-field date" align="center">
            <?php echo dateFormat($page['date'],"j.n.Y"); ?>
        </td>
        <td class="admin-table-field" align="right">
            <?php
                if ($page['parent'] == '' && $page['slug'] != 'error404') {
                    htmlButton('+','?id=pages&action=add_page&parent_page='.$page['slug'],lang('pages_add'),true,'btn-plus');
                }
            ?>            
            <?php if($page['slug'] != 'error404') { ?>
            <?php htmlButtonEdit('+1', 'index.php?id=pages&action=clone_page&filename='.$page['slug']); ?>
            <?php } ?>            
            <?php htmlButtonEdit(lang('pages_edit'), 'index.php?id=pages&action=edit_page&filename='.$page['slug']); ?>
            <?php if($page['slug'] == 'error404') { ?>
                <?php htmlButtonDelete(lang('pages_delete'),'','',true,false); ?>
            <?php } else { ?>
                <?php htmlButtonDelete(lang('pages_delete'), 'index.php?id=pages&action=delete_page&filename='.$page['slug']); ?>
            <?php } ?>
        </td>
     </tr> 
    <?php
            } 
        }
    ?>
    </tbody>
</table>
<!-- /Pages_list -->