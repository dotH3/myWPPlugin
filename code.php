<?php
    /**
     * Plugin Name: myWPPlugin - Carousel
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
            add_submenu_page( 'custom-carousel', 'Upload Image Page', 'Upload', 'manage_options', 'upload-img', array($this, 'adminSubPageHTML'));
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

        function adminSubPageHTML() { ?>
            <div class="wrap">
		        <h1>Ejemplo de subida de archivo</h1>
		        <br>
                <form enctype="multipart/form-data" method="post">
                    Selecciona algún archivo: <input name="upload-file" type="file" /> <hr>
                    <input type="submit" value="Enviar archivo" />
                </form>
                <script>
                    ()=>{
                        console.log("<?php 
                            global $wp_filesystem;
                            WP_Filesystem();
                            // $content_directory = $wp_filesystem->wp_content_dir() . 'uploads/archivos-subidos/';
                            $content_directory = $wp_filesystem->wp_content_dir();
                            echo $content_directory;
                        ?>");
                    }
                </script>
	        </div>

            <?php
            if(isset($_FILES['upload-file'])) {
                global $wp_filesystem;
                WP_Filesystem();

                $name_file = $_FILES['upload-file']['name'];
                $tmp_name = $_FILES['upload-file']['tmp_name'];
                $allow_extensions = ['png', 'jpg'];

                // File type validation
		        $path_parts = pathinfo($name_file);
		        $ext = $path_parts['extension'];

                if ( ! in_array($ext, $allow_extensions) ) {
                    echo "Error - File type not allowed";
                    return;
                }

                // $content_directory = $wp_filesystem->wp_content_dir(). 'uploads/';
                $content_directory = $wp_filesystem->wp_content_dir() . 'uploads/archivos-subidos/';
                $wp_filesystem->mkdir( $content_directory );
                
                if( move_uploaded_file( $tmp_name, $content_directory . $name_file ) ) {
                    echo "File was successfully uploaded";
                } else {
                    echo "The file was not uploaded";
                }

            }
        }


    }

    $myCarousel = new MyCarousel();

?>