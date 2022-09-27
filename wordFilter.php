<?php

/*
  Plugin Name: Word Filter
  Description: This is my personal plugin
  Version: 1.0
  Author: JB
  Author URI: https://pornhub.com
*/

if( ! defined( 'ABSPATH' ) ) exit;

class OurWordFilterPlugin {
    function __construct() {
        add_action('admin_menu', array($this, 'ourMenu'));
    }
    
    function ourMenu() {
        add_menu_page('Words To Filter', 'Word Filter', 'manage_options', 'ourwordfilter', array($this, 'wordFilterPage'), 'dashicons-smiley', 100);
        add_submenu_page( 'ourwordfilter', 'Word To Filter', 'Words List', 'manage_options', 'ourwordfilter', array($this, 'wordFilterPage') );
        add_submenu_page( 'ourwordfilter', 'Word Filter Optiones', 'Options', 'manage_options', 'word-filter-options', array($this, 'optionsSubPage') );
    }

    function wordFilterPage() { ?>
        Hola !  
    <?php }

    function optionsSubPage() { ?>
        options
    <?php }

}

$ourWordFilterPlugin = new OurWordFilterPlugin();
