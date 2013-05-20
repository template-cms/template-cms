<ul>
<?php 
	foreach ($pages as $page) {		
		if (trim($page['parent']) !== '') $parent = $page['parent'].'/'; else $parent = '';
		if (trim($page['parent']) !== '') { echo '<ul>'; }
		echo '<li><a href="'.getSiteUrl(false).$parent.$page['slug'].'" target="_blank">'.$page['title'].'</a></li>';
		if (trim($page['parent']) !== '') { echo '</ul>'; }
	}
?>
</ul>