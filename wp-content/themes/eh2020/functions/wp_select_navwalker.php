<?php

class Walker_Nav_Menu_Dropdown extends Walker_Nav_Menu
{

    // don't output children opening tag (`<ul>`)
    public function start_lvl(&$output, $depth = 0, $args = array())
    {
    }

    // don't output children closing tag    
    public function end_lvl(&$output, $depth = 0, $args = array())
    {
    }

    public function start_el(&$output, $item, $depth = 0, $args = array(), $id = 0)
    {

        /** This filter is documented in wp-includes/post-template.php */
        $title = apply_filters('the_title', $item->title, $item->ID);

        /**
         * Filters a menu item's title.
         *
         * @since WP 4.4.0
         *
         * @param string   $title The menu item's title.
         * @param WP_Post  $item  The current menu item.
         * @param stdClass $args  An object of wp_nav_menu() arguments.
         * @param int      $depth Depth of menu item. Used for padding.
         */
        $title = apply_filters('nav_menu_item_title', $title, $item, $args, $depth);

        $icon = strtolower(get_field('country_flag', $item));

        // add spacing to the title based on the current depth
        $item->title =  "<span class='flag-icon flag-icon-" . $icon . " mr-2'></span><span>" . str_repeat("&nbsp;", $depth * 4) . $title . "</span>";

        // call the prototype and replace the <li> tag
        // from the generated markup...
        parent::start_el($output, $item, $depth, $args);

        $output = str_replace('<li', '<option', $output);
    }

    // replace closing </li> with the closing option tag
    public function end_el(&$output, $item, $depth = 0, $args = array())
    {
        $output .= "</option>\n";
    }
}
