<?php 
namespace MSO\Includes\Pages;

class Admin {

    protected $page_slug = 'mso-store-order';

    /**
     * Construct Function
     */
    public function __construct() {

        add_action( 'admin_menu', [ $this, 'admin_menu' ] );

        add_action( 'admin_enqueue_scripts', [ $this, 'register_scripts_styles' ] );
    }

    public function register_scripts_styles($hook) {

        wp_register_script( 'mso-admin-store', MSO_PLUGIN_URL . 'includes/assets/js/store-order.js', ['jquery'], rand(), true );
        wp_enqueue_script( 'mso-admin-store' );

        if(str_contains($hook, $this->page_slug ) === false){            
            return;
        }

        $this->load_scripts();
        $this->load_styles();
    }

    /**
     * Load Scripts
     *
     * @return void
     */
    public function load_scripts() {
        wp_register_script( 'mso-admin-main', MSO_PLUGIN_URL . 'assets/js/main.js', [], rand(), true );
        

        wp_enqueue_script( 'mso-admin-main' );

        wp_localize_script( 'mso-admin-main', 'msoAdminLocalizer', [
            'adminUrl'  => admin_url( '/' ),
            'ajaxUrl'   => admin_url( 'admin-ajax.php' ),
            'apiUrl'    => home_url( '/wp-json' ),
        ] );
    }

    public function load_styles() {
        wp_register_style( 'mso-admin-style', MSO_PLUGIN_URL . 'assets/css/main.css' );

        wp_enqueue_style( 'mso-admin-style' );
    }

    /**
     * Register Menu Page
     * @since 1.0.0
     */
    public function admin_menu() {
        global $submenu;

        $capability = 'manage_options';

        add_menu_page(
            __( 'Store Order', 'mso' ),
            __( 'Store Order', 'mso' ),
            $capability,
            $this->page_slug,
            [ $this, 'menu_page_template' ],
            'dashicons-buddicons-replies'
        );

        if( current_user_can( $capability )  ) {
            $submenu[ $this->page_slug ][] = [ __( 'Store Order', 'mso' ), $capability, 'admin.php?page=' . $this->page_slug . '#/'];
        }
    }


    /**
     * Render Admin Page
     * @since 1.0.0
     */
    public function menu_page_template() {
        echo '<div class="wrap">
            <h2>Store Order</h2>
            <div id="mso-admin-app"></div>
        </div>';
    }

}
