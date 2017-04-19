<?php
function add_menu_start_depth_option($items, $args) {
 
    $parent_class = array('current-menu-parent');
    $current_class = array('current-menu-item');
 
    //calculate item children, parents and depth
    $depth = 1;
    $parent_ids = array();
    $parent_keys = array();
 
    foreach ($items as $key => $item) {
        if ($item->menu_item_parent && ($item->menu_item_parent == $items[$key-1]->ID)) {
            $depth++;
            array_push($parent_ids,$items[$key-1]->ID);
            array_push($parent_keys,$key-1);
        } elseif (count($parent_ids) && ($item->menu_item_parent != $parent_ids[count($parent_ids)-1])) {
            while ($parent_ids && ($item->menu_item_parent != $parent_ids[count($parent_ids)-1])) {
                $depth--;
                array_pop($parent_ids);
                array_pop($parent_keys);
            }
        }
 
        if ($parent_ids) {
            if (!isset($items[$key]->parent_ids)) {
                $items[$key]->parent_ids = array();
            }
 
            if (!isset($items[$key]->parent_keys)) {
                $items[$key]->parent_keys = array();
            }
 
            $items[$key]->parent_ids = $parent_ids;
            $items[$key]->parent_keys = $parent_keys;
        }
        $items[$key]->depth = $depth;
        foreach($parent_keys as $k) {
            if (!isset($items[$k]->child_ids)) {
                $items[$k]->child_ids = array();
            }   
            if (!isset($items[$k]->child_keys)) {
                $items[$k]->child_keys = array();
            }
            $items[$k]->child_ids[] = $item->ID;
            $items[$k]->child_keys[] = $key;
        }
    }
 
    //if start_level >= 2 then remove unnecessary items
    if (!isset($args->start_level) || intval($args->start_level) < 2) {
        return $items;
    } else {
        $filtered_items = $items;
 
        //find current item
        $current_item = count($filtered_items);
        while ($current_item) {
            if (!is_array($filtered_items[$current_item]->classes)) {
                $filtered_items[$current_item]->classes = array();
            }
            if (count(array_intersect($filtered_items[$current_item]->classes, $current_class))) {
                break;
            }
            $current_item--;
        }
 
        //find parent item if current item not found
        if (!$current_item) {
            $current_item = count($filtered_items);
            while ($current_item) {
                if (count(array_intersect($filtered_items[$current_item]->classes, $parent_class))) {
                    break;
                }
                $current_item--;
            }
        }
 
        //if active item found and active item depth not too small to calculate it's submenu on this level
        if ($current_item && $filtered_items[$current_item]->depth >= ($args->start_level -1)) {
            //find active parent with level = start_level -1;
            $parent_item = $current_item;
            if ($filtered_items[$parent_item]->depth >= $args->start_level) {
                $parent_item = $filtered_items[$parent_item]->parent_keys[$args->start_level-2];
            }
 
            //save
            $child_ids = $filtered_items[$parent_item]->child_ids;
            $child_ids = $child_ids ? $child_ids : array();
 
            //remove unnecessary items
            foreach ($filtered_items as $key => $item) {
                if (($item->depth < $args->start_level) || (!in_array($item->ID, $child_ids) && ($key != $current_item))) {
                    unset($filtered_items[$key]);
                }
            }
 
            //reorder item keys
            if (count($filtered_items)) {
                $reordered_items = array();
                $key = 0;
                foreach ($filtered_items as $item) {
                    $key++;
                    $reordered_items[$key] = $item;
                }
 
                return $reordered_items;
            }
        }
 
        $args->items_wrap = '';
        return array();
    }
}
add_filter('wp_nav_menu_objects', 'add_menu_start_depth_option', 10, 2);
