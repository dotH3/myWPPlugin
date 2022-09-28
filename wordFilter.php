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
        $mainPageHook = add_menu_page('Words To Filter', 'Word Filter', 'manage_options', 'ourwordfilter', array($this, 'wordFilterPage'), 'dashicons-smiley', 100);
        add_submenu_page( 'ourwordfilter', 'Word To Filter', 'Words List', 'manage_options', 'ourwordfilter', array($this, 'wordFilterPage') );
        add_submenu_page( 'ourwordfilter', 'Word Filter Optiones', 'Options', 'manage_options', 'word-filter-options', array($this, 'optionsSubPage') );
        add_action("load-{$mainPageHook}", array($this, 'mainPageAssets'));
    }

    function mainPageAssets() {
        wp_enqueue_style( 'filterAdminCss', plugin_dir_url( __FILE__ ) . 'style.css');
    }

    function handleForm() {
    if (wp_verify_nonce($_POST['ourNonce'], 'saveFilterWords') AND current_user_can('manage_options')) {
      update_option('plugin_words_to_filter', sanitize_text_field($_POST['plugin_words_to_filter'])); ?>
      <div class="updated">
        <p>Your filtered words were saved.</p>
      </div>
    <?php } else { ?>
      <div class="error">
        <p>Sorry, you do not have permission to perform that action.</p>
      </div>
    <?php } 
  }

    function wordFilterPage() { ?>
        <div class="wrap">
            <h1>Word Filter</h1>
            <?php if($_POST['justsubmitted'] == 'true') $this->handleForm() ?>
            <form method="POST">
                <input type="hidden" name="justsubmitted" value="true">
                <?php 
                // wp_nonce_field( action, name, referer, echo )
                wp_nonce_field('saveFilterWords', 'ourNonce') 
                ?>
                <label for="plugin_word_to_filter"><p>Enter <strong>"," (Coma)</strong> to separate the words</p></label>
                <div class="word-filter__flex-container">
                    <textarea name="plugin_words_to_filter" id="plugin_words_to_filter">
                        <?php echo esc_textarea(get_option('plugin_words_to_filter')) ?>
                    </textarea>
                </div>
                <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
            </form>
        </div>
    <?php }

    function optionsSubPage() { ?>
        options
    <?php }

}

$ourWordFilterPlugin = new OurWordFilterPlugin();
