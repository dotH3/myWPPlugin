<?php

/*
    Plugin Name: MyWPPlugin
    Description: This is my personal plugin
    Version: 1.0
    Author: JB
    Author URI: https://pornhub.com
*/

class WordCountAndTimePlugin {

    // Este lleva la batuta
    function __construct() {
        add_action( 'admin_menu', array($this, 'adminPage'));
        add_action('admin_init', array($this, 'settings'));
    }
    
    // Configuraciones en la DB (Campos demas)
    function settings() {
        // add_settings_section( id, title, callback, page )
        add_settings_section( 'wpc_first_section', null, null, 'word-count-settings-page');
        // add_settings_field( id, title, callback, page, section, args )
        add_settings_field( 'wpc_location', 'Display Location', array($this, 'locationHTML'), 'word-count-settings-page', 'wpc_first_section');
        // register_setting( option_group, option_name, sanitize_callback )
        register_setting( 'wordcountplugin', 'wpc_location', array('sanitize_callback' => 'sanitize_text_field', 'default' => '0'));
    }

    // Mierdas del formulario
    function locationHTML() {?>
        <select name="wpc_location">
            <option value="0">Begining of post</option>
            <option value="1">End of post</option>
        </select>
    <?php }
    

    // Creamos la pagina de configuracion en el panel admin
    function adminPage() {    
        // add_options_page( page_title, menu_title, capability, menu_slug, function )
        add_options_page( 'Word Count Settings', 'Word Count', 'manage_options', 'word-count-settings-page', array($this, 'ourHTML'));
    }

    // Esta es la pagina del panel admin
    function ourHTML() { ?>
        <div class="wrap"> 
            <h1>Word Count</h1>
            <form action="options.php" method='POST'>
             <?php
                settings_fields( 'wordcountplugin' );
                do_settings_sections( 'word-count-settings-page' );
                // submit_button( text, type, name, wrap, other_attributes )
                submit_button( )
             ?>
            </form>
        </div>
    <?php }

}

$wordCountAndTimePlugin = new WordCountAndTimePlugin();

?>