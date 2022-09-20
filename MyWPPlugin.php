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

        // TEMPLATE:
        // add_settings_section( id, title, callback, page )
        // add_settings_field( id, title, callback, page, section, args )
        // register_setting( option_group, option_name, sanitize_callback )

        add_settings_section( 'wcp_first_section', null, null, 'word-count-settings-page');
        // location
        add_settings_field( 'wcp_location', 'Display Location', array($this, 'locationHTML'), 'word-count-settings-page', 'wcp_first_section');
        register_setting( 'wordcountplugin', 'wcp_location', array('sanitize_callback' => 'sanitize_text_field', 'default' => '0'));
        // headline
        add_settings_field( 'wcp_headline', 'Headlines Text', array($this, 'headlineHTML'), 'word-count-settings-page', 'wcp_first_section');
        register_setting( 'wordcountplugin', 'wcp_headline', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'Post Stadistics'));
        // wordcount
        add_settings_field( 'wcp_wordcount', 'Word Count', array($this, 'wordcountHTML'), 'word-count-settings-page', 'wcp_first_section');
        register_setting( 'wordcountplugin', 'wcp_wordcount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));
        // 
        add_settings_field( 'wcp_charactercount', 'Character Cunt', array($this, 'charactercountHTML'), 'word-count-settings-page', 'wcp_first_section');
        register_setting( 'wordcountplugin', 'wcp_charactercount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));
        // 
        add_settings_field( 'wcp_readtime', 'Read Time', array($this, 'readtimeHTML'), 'word-count-settings-page', 'wcp_first_section');
        register_setting( 'wordcountplugin', 'wcp_readtime', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));
    }

    // Formulario (wcp_location)
    function locationHTML() {?>
        <select name="wcp_location">
            <option value="0" <?php selected( get_option( 'wcp_location' ), '0' ) ?>>Begining of post</option>
            <option value="1" <?php selected( get_option( 'wcp_location' ), '1' ) ?>>End of post</option>
        </select>
    <?php }

    // Formulario (wcp_headlines)
    function headlineHTML() {?>
        <input type="text" name="wcp_headline" value="<?php echo esc_attr( get_option( 'wcp_headline' ) ) ?>">
    <?php }

    // Formulario (wcp_wordcount)
    function wordcountHTML() {?>
        <input type="checkbox" name="wcp_wordcount" value='1' <?php checked(get_option('wcp_wordcount'), '1') ?>>
    <?php }

    // 
    function charactercountHTML() {?>
        <input type="checkbox" name="wcp_charactercount" value='1' <?php checked(get_option('wcp_charactercount'), '1') ?>>
    <?php }
    // 
    function readtimeHTML() {?>
        <input type="checkbox" name="wcp_readtime" value='1' <?php checked(get_option('wcp_readtime'), '1') ?>>
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