<?php
    /**
     * Plugin Name: myCarousel
     */

    if( ! defined( 'ABSPATH' ) ) exit;

    class MyCarousel {
        function __construct() {
            add_action('admin_menu', array($this, 'adminMenu'));
            add_filter('the_content', array($this, 'myFilter'));
            // add_filter('the_content', array($this, 'test'));
        }

        function myFilter($content) {
            function output() {
                echo ''?>
                    <img src="<?php esc_attr( get_option() ) ?>" alt="">
                    <p><? get_option(  ) ?></p>
                <?php
            }
            return output() . $content;
        }
        
        function mainPageAssets() {
            wp_enqueue_style( 'filterAdminCss', plugin_dir_url( __FILE__ ) . 'style.css');
        }
        
        function adminMenu() {
            $mainPageHook = add_menu_page( 'Custom Carousel Page', 'My Carousel', 'manage_options', 'custom-carousel', array($this, 'adminPageHTML'), 'dashicons-smiley', 100 );
            // Load CSS
            add_action("load-{$mainPageHook}", array($this, 'mainPageAssets'));
        }
        function adminPageHTML() { ?>
            <div class="wrap">
                <h1>My Custom Carousel</h1>
                <form method="POST">
                    <label for="plugin_carousel_img_text">Link de las imagenes a ocupar en el carousel. <strong>Separados por una "," (COMA)</strong></label>
                    <div class="flex-container">
                        <textarea name="plugin_carousel_img_text" id="plugin_carousel_img_text"><?php echo esc_textarea(get_option('plugin_carousel_img_text')) ?></textarea>
                    </div>
                    <input type="submit" name="submit" id="submit" class="button button-primary" value="Save Changes">
                </form>
            </div>
        <?php }


    }

    $myCarousel = new MyCarousel();

?>