<?php if($parent) { ?>
	<a href="<?php echo getSiteUrl(false).$page->parent; ?>"><?php echo $parent_page->title; ?></a><span>&rarr;</span><a href="<?php echo getSiteUrl(false).$page->parent.'/'.$page->slug; ?>"><?php echo $page->title; ?></a>
<?php } else { ?>
	<a href="<?php echo getSiteUrl(false).$page->slug; ?>"><?php echo $page->title; ?></a>	
<?php } ?>