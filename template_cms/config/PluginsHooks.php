<?php

    /**
     * Main admin navigation
     * @param string $id  Plugin id
     * @param string $txt Plugin name
     * @global boolean $is_admin
     */
    function adminNavigation($id, $txt) {
        global $is_admin;

        // Show admin navigation only for admin
        if ($is_admin) {
            $class = "";
            if (get('id') == $id) {
                $class='class="current"';
            }
            if (isset($plugin)) $idTwo = '&plugin='.$plugin;
            echo '<li><a href="?id='.$id.'" '.$class.'>'.$txt.'</a></li>';
        }
    }

     /**
      * Second admin navigation
      * @param string $id  Plugin id
      * @param string $txt Plugin name
      * @param string $sub_id Plugin sub_id
      * @global boolean $is_admin
      */
    function adminSecondNavigation($id, $txt, $sub_id=null) {
        global $is_admin;

        // Show admin navigation only for admin
        if ($is_admin) {
            $class = "";
            if (get('sub_id') == $sub_id) {
                $class='class="sub-current"';
            }
            if (isset($sub_id)) $idTwo = '&sub_id='.$sub_id; else $idTwo = '';
            echo '<li><a href="?id='.$id.''.$idTwo.'" '.$class.'>'.$txt.'</a></li>';
        }
    }

    /**
     * Top admin navigation
     * @param string $id  Plugin id
     * @param string $txt Plugin name
     * @global boolean $is_admin
     */
    function adminTopNavigation($id, $txt) {
        global $is_admin;

        // Show admin top navigation only for admin
        if ($is_admin) {
            $class = "";
            if (get('id') == $id) {
                $class='class="top-navigation-current"';
            }
            if (isset($plugin)) $idTwo = '&plugin='.$plugin;
            echo '<a href="?id='.$id.'" '.$class.'>'.$txt.'</a>&nbsp;';
        }
    }