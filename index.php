<?php 

/**
 * Plugin Name:       MS Store Order
 * Plugin URI:        http://masum-billah.com
 * Description:       To connect hub through API To Update Orders
 * Version:           1.0.0
 * Requires at least: 5.2
 * Requires PHP:      7.2
 * Author:            H M Masum Billah
 * Author URI:        http://masum-billah.com
 * Text Domain:       mso
 * Domain Path:       /languages
 */
if ( ! defined( 'ABSPATH' ) ) { exit; }

if ( defined( 'XMLRPC_REQUEST' ) || defined( 'REST_REQUEST' ) || ( defined( 'WP_INSTALLING' ) && WP_INSTALLING ) || wp_doing_ajax() ) {
    @ini_set( 'display_errors', 1 );
}

require_once __DIR__ . '/autoload.php';

use MSO\Includes\Api\Api;
use MSO\Includes\Pages\Admin;
use MSO\Includes\Woocommerce\WooInit;
use MSO\Includes\Woocommerce\ConnectWithHub;

final class MSOInit {

    /**
     * Define Plugin Version
     */
    const VERSION = '1.0.0';

    /**
     * Construct Function
     */
    public function __construct() {
        $this->plugin_constants();
        add_action( 'init', [$this, 'mso_textdomain_load'] );
        register_activation_hook( __FILE__, [ $this, 'activate' ] );
        register_deactivation_hook( __FILE__, [ $this, 'deactivate' ] );
        add_action( 'plugins_loaded', [ $this, 'init_plugin' ] );
        add_filter( 'plugin_action_links_' . plugin_basename(__FILE__), [$this, 'mso_api_settings'] );
    }

    /**
     * Plugin Constants
     * @since 1.0.0
     */
    public function plugin_constants() {
        define( 'MSO_VERSION', self::VERSION );
        define( 'MSO_PLUGIN_PATH', trailingslashit( plugin_dir_path( __FILE__ ) ) );
        define( 'MSO_PLUGIN_URL', trailingslashit( plugins_url( '', __FILE__ ) ) );
        define( 'MSO_NONCE', 'b?le*;K7.T2jk_*(+3&[G[xAc8O~Fv)2T/Zk9N:GKBkn$piN0.N%N~X91VbCn@.4' );
    }
    
 
/**
 * Load plugin textdomain.
 */
    public function mso_textdomain_load() {
        load_plugin_textdomain( 'mso', false, dirname( plugin_basename( __FILE__ ) ) . '/languages' ); 
    }

    public function mso_api_settings( array $links ) {
        $url = get_admin_url() . "admin.php?page=mso-store-order";
        $settings_link = '<a href="' . $url . '">' . __('Settings', 'waw') . '</a>';
        $links[] = $settings_link;
        return $links;
    }

    

    /**
     * Singletone Instance
     * @since 1.0.0
     */
    public static function init() {
        static $instance = false;

        if( !$instance ) {
            $instance = new self();
        }

        return $instance;
    }

    /**
     * On Plugin Activation
     * @since 1.0.0
     */
    public function activate() {
        $is_installed = get_option( 'mso_is_installed' );

        if( ! $is_installed ) {
            update_option( 'mso_is_installed', time() );
        }

        update_option( 'mso_is_installed', MSO_VERSION );
    }

    /**
     * On Plugin De-actiavtion
     * @since 1.0.0
     */
    public function deactivate() {

        if( get_option( 'mso_is_installed' ) ) {
            delete_option( 'mso_is_installed' );
        }
    }

    /**
     * Init Plugin
     * @since 1.0.0
     */
    public function init_plugin() {
        new WooInit();

        if (!function_exists('is_plugin_active')) {
            include_once(ABSPATH . 'wp-admin/includes/plugin.php');
        }
        
        if(is_plugin_active( 'woocommerce/woocommerce.php' )){
            new Admin();
            new Api();
            new ConnectWithHub();
        }else{
            deactivate_plugins( 'ms-store-order/index.php' );
        }
        
    }

}

/**
 * Initialize Main Plugin
 * @since 1.0.0
 */
function mso_init() {
    return MSOInit::init();
}
mso_init();

