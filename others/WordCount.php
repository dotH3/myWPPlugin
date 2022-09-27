<?php

/*
    Plugin Name: WordCount
    Description: This is my personal plugin
    Version: 1.0
    Author: JB
    Author URI: https://pornhub.com
    Text Domain: wcpdomain
    Domain Path: /languages
*/

class WordCountAndTimePlugin {

    function __construct() {
        add_action( 'admin_menu', array($this, 'adminPage'));
        add_action('admin_init', array($this, 'settings'));
        add_filter('the_content', array($this, 'isWrap'));
        // add_action('init', array($this, 'languages'));
    }

    function language(){
        // load_plugin_textdomain( 'wcpdomain', false, dirname(plugin_basename( __FILE__ )) . '/languages' );
    }

    function isWrap($content) {
        if((is_main_query() AND is_single()) AND (
            get_option( 'wcp_wordcount', '1' ) OR
            get_option( 'wcp_charactercount', '1' ) OR 
            get_option( 'wcp_readtime', '1' )
        )) {
            return $this->createHTML($content);
        }
        return $content;
    }

    function createHTML($content) {
        $html = '<h3>' . esc_html(get_option( 'wcp_headline', 'Post Stadistics' )) . '</h3><p>';
        
        if(get_option( 'wcp_wordcount', '1' ) OR get_option( 'wcp_wordcount', '1' )){$wordCount = str_word_count(strip_tags($content));}
        
        if(get_option( 'wcp_wordcount', '1' )) {
            $html .= __('Esta publicacion tiene', 'wcpdomain') . ' ' . $wordCount . ' palabras. <br>';
        }
        if(get_option( 'wcp_charactercount', '1' )) {
            $html .= 'Esta publicacion tiene' . ' ' . strlen(strip_tags($content)) . ' caracteres. <br>';
        }
        if(get_option( 'wcp_readtime', '1' )) {
            $html .= 'Esta publicacion te tomara' . ' ' . round($wordCount/125) . ' minuto(s). <br>';
        }

        $html .= '</p>';

        if(get_option( 'wcp_location', '0' ) == 0) {return $html . $content;}
        return $content . $html;
    }


    //

    
    function settings() {

        // TEMPLATE:
        // add_settings_section( id, title, callback, page )
        // add_settings_field( id, title, callback, page, section, args )
        // register_setting( option_group, option_name, sanitize_callback )

        add_settings_section( 'wcp_first_section', null, null, 'word-count-settings-page');
        
        add_settings_field( 'wcp_location', 'Display Location', array($this, 'locationHTML'), 'word-count-settings-page', 'wcp_first_section');
        register_setting( 'wordcountplugin', 'wcp_location', array('sanitize_callback' => array($this, 'sanitizeLocation'), 'default' => '0'));
        
        add_settings_field( 'wcp_headline', 'Headlines Text', array($this, 'headlineHTML'), 'word-count-settings-page', 'wcp_first_section');
        register_setting( 'wordcountplugin', 'wcp_headline', array('sanitize_callback' => 'sanitize_text_field', 'default' => 'Post Stadistics'));
      
        add_settings_field( 'wcp_wordcount', 'Word Count', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_wordcount'));
        register_setting( 'wordcountplugin', 'wcp_wordcount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));
      
        add_settings_field( 'wcp_charactercount', 'Character Cunt', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_charactercount'));
        register_setting( 'wordcountplugin', 'wcp_charactercount', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));
       
        add_settings_field( 'wcp_readtime', 'Read Time', array($this, 'checkboxHTML'), 'word-count-settings-page', 'wcp_first_section', array('theName' => 'wcp_readtime'));
        register_setting( 'wordcountplugin', 'wcp_readtime', array('sanitize_callback' => 'sanitize_text_field', 'default' => '1'));
    }

    function sanitizeLocation($input) {
        if($input != '0' AND $input != '1') {
            add_settings_error( 'wcp_location', 'wcp_location_error', 'Esta opcion no es adminitida' );
            return get_option('wcp_location');
        }
        return $input;
    }

    // Formulario
    function locationHTML() { ?>
        <select name="wcp_location">
            <option value="0" <?php selected( get_option( 'wcp_location' ), '0' ) ?>>Begining of post</option>
            <option value="1" <?php selected( get_option( 'wcp_location' ), '1' ) ?>>End of post</option>
        </select>
    <?php }

    function headlineHTML() { ?>
        <input type="text" name="wcp_headline" value="<?php echo esc_attr( get_option( 'wcp_headline' ) ) ?>">
    <?php }

    function checkboxHTML($args) { ?>
        <input type="checkbox" name="<?php echo $args['theName'] ?>" value='1' <?php checked(get_option($args['theName']), '1') ?>
    <?php }

    //////////////
    

    // Creamos la pagina de configuracion en el panel admin
    function adminPage() {    
        add_options_page( 'Word Count Settings', __('Word Count', 'wcpdomain'), 'manage_options', 'word-count-settings-page', array($this, 'ourHTML'));
    }

    // Esta es la pagina del panel admin
    function ourHTML() { ?>
        <div class="wrap"> 
            <h1>Word Count</h1>
            <form action="options.php" method='POST'>
             <?php
                settings_fields( 'wordcountplugin' );
                do_settings_sections( 'word-count-settings-page' );
                submit_button( )
             ?>
            </form>
        </div>
    <?php }

}

$wordCountAndTimePlugin = new WordCountAndTimePlugin();

?>