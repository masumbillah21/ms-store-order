<?php
namespace MSO\Includes\Api;

use WP_REST_Controller;
use MSO\Includes\Api\CORSHandler;
use MSO\Includes\Api\Admin\HubSettings;
use MSO\Includes\Woocommerce\SendToHub;
use MSO\Includes\Api\Admin\StoreSettings;
use MSO\Includes\Woocommerce\StoreWebHook;

/**
 * Rest API Handler
 */
class Api extends WP_REST_Controller {

    /**
     * Construct Function
     */
    public function __construct() {
        add_action( 'rest_api_init', [ $this, 'register_routes' ] );
    }

    /**
     * Register API routes
     */
    public function register_routes() {
        ( new StoreSettings() )->register_routes();
        ( new HubSettings() )->register_routes();
        ( new StoreWebHook() )->register_routes();
    }

}