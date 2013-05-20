<?php

    if (count($menus_records) > 0) {
        foreach ($menus_records as $menu) {
            $pos = strpos($menu['menu_link'], 'http://');
            if ($pos === false) {
                $link = $site_url.$menu['menu_link'];
            } else {
                $link = $menu['menu_link'];
            }

            echo '<li><a href="'.$link.'"';
            if (isset($data[1])) {
                $child_link = explode("/",$menu['menu_link']);
                if (isset($child_link[1])) {
                    if (in_array($child_link[1],$data)) {
                        echo ' class="current" ';
                    }
                }
            }

            if ($data[0] !== '') {
                if (in_array($menu['menu_link'],$data)) {
                    echo ' class="current" ';
                }
            } else {
                if ($defpage == trim($menu['menu_link'])) {
                    echo ' class="current" ';
                }
            }

            if (trim($menu['menu_target']) !== '') {
                echo ' target="'.$menu['menu_target'].'" ';
            }

            echo '>'.$menu['menu_name'].'</a></li>'."\n";
        }
    }