<?php
    /**
     * Plugin Name: myWPPlugin - Carousel
     */

    if( ! defined( 'ABSPATH' ) ) exit;

    class MyCarousel {
        function __construct() {
            add_action('admin_menu', array($this, 'adminMenu'));
            add_filter('the_content', array($this, 'myFilter'));
            add_filter('the_content', array($this, 'myFilter'));

            add_action( 'wp_enqueue_scripts', array($this, 'bootstrap_css'));
        }

        function myFilter($content) {
            // return ;
            function output() {
                ?>
                    <script>    
                        window.onload = ()=>{
                            const data = "<?php echo esc_textarea(get_option('plugin_carousel_img_list')) ?>";
                            const rest = data.split(',');
                            console.log(rest);
                            rest.forEach(el => {
                                const element = document.createElement('img');
                                const list = document.getElementById('h3');
                                element.src = el;
                                list.appendChild(element);
                                // console.log(el)
                            });
                        }

                    </script>

                    <div id='h3'>
                        <h3>
                            Plugin is running!
                        </h3>
                    </div>
                <?php 
            }
            return output() . $content;
        }
        
        function mainPageAssets() {
            wp_enqueue_style( 'filterAdminCss', plugin_dir_url( __FILE__ ) . 'style.css');
        }
        
        function bootstrap_css() {
            wp_enqueue_style( 'bootstrap_css', 
                              'https://cdn.jsdelivr.net/npm/bootstrap@5.2.2/dist/css/bootstrap.min.css', 
                              array(), 
                              '4.1.3'
                              ); 
        }
        
        function adminMenu() {
            $mainPageHook = add_menu_page( 'Custom Carousel Page', 'My Carousel', 'manage_options', 'custom-carousel', array($this, 'adminPageHTML'), 'dashicons-smiley', 100 );
            // Load CSS
            add_submenu_page( 'custom-carousel', 'Upload Image Page', 'Upload', 'manage_options', 'upload-img', array($this, 'adminSubPageHTML'));
            add_action("load-{$mainPageHook}", array($this, 'mainPageAssets'));
        }
        
        function handleForm() {
            if (wp_verify_nonce($_POST['ourNonce'], 'plugin_carousel_img_list') AND current_user_can('manage_options')) {
                update_option('plugin_carousel_img_list', sanitize_text_field($_POST['plugin_carousel_img_list'])); ?>
                <div class="updated">
                <p>Your filtered words were saved.</p>
                </div>
            <?php } else { ?>
                <div class="error">
                <p>Sorry, you do not have permission to perform that action.</p>
                </div>
            <?php } 
        }

        function adminPageHTML() { ?>
            <div class="wrap">
                <h1>My Custom Carousel</h1>
                <?php 
                    if($_POST['justsubmitted'] == 'true') {
                        $this->handleForm();
                    } 
                ?>
                <form method="POST">
                    <input type="hidden" name="justsubmitted" value="true">
                    <?php 
                        // wp_nonce_field( action, name, referer, echo )
                        wp_nonce_field('plugin_carousel_img_list', 'ourNonce') 
                    ?>
                    <label for="plugin_carousel_img_list">Link de las imagenes a ocupar en el carousel. <strong>Separados por una "," (COMA)</strong></label>
                    <div class="flex-container">
                        <textarea name="plugin_carousel_img_list" id="plugin_carousel_img_list"><?php echo esc_textarea(get_option('plugin_carousel_img_list')) ?></textarea>
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