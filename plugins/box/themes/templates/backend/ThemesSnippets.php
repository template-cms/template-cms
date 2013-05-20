<div id="snippets">
<div id="snippets-box">
<?php
    $count_menus = 0;
    foreach($menus_list as $menus) {        
        echo '<span class="code">&lt;?php getSiteMenu(\''.basename($menus,'.xml').'\');?&gt;</span>';
        if($count_menus > 4) {
            htmlBr(2);
            $count_menus = 1;
        }
        $count_menus++;
    }
    htmlBr(2);
    $count_blocks = 1;
    foreach($blocks_list as $blocks) {        
        if($count_blocks > 4) {
            htmlBr(2);
            $count_blocks = 1;
        }
        echo '<span class="code">&lt;?php getBlock(\''.basename($blocks,'.php').'\');?&gt;</span>';
        $count_blocks++;
    }
    htmlBr(2);
?>
</div>
<div id="snippets-toggle"><?php echo lang('themes_snippets'); ?></div>
</div>
<br />