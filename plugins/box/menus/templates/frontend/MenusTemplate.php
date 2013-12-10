<?php

    if (count($menus_records) > 0) {
        foreach ($menus_records as $menu) {
            if(($sub_id = (int)$menu['sort']) < 100) continue;
            // if($sub_id > 10000)   $subsub[(int)($sub_id/100)-((int)($sub_id/10000)*100)][] = $menu;
            $sub[(int)($sub_id/100)][] = $menu;
        }
        // echo '<pre>'; print_r($sub); print_r($subsub); echo '</pre>';
        foreach ($menus_records as $menu) {
          if((int)$menu['sort'] < 100){
            render_menu_list_item($menu, $data, $sub);
          }
        }
    }