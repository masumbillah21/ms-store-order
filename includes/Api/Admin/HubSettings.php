<?php
namespace MSO\Includes\Api\Admin;

use WP_REST_Controller;

class HubSettings extends WP_REST_Controller  {

    protected $namespace;
    protected $rest_base;

    protected $current_user;

    protected $create_perssions;

    public function __construct() {
        $this->namespace = 'mso/v1';
        $this->rest_base = 'hub-settings';
        $this->current_user = wp_get_current_user();
        $this->create_perssions = [
            'administrator',
            'customer_support',
        ];
    }

    /**
     * Register Routes
     */
    public function register_routes() {
        register_rest_route(
            $this->namespace,
            '/' . $this->rest_base,
            [
                [
                    'methods'             => \WP_REST_Server::READABLE,
                    'callback'            => [ $this, 'get_items' ],
                    'permission_callback' => [ $this, 'get_items_permission_check' ],
                    'args'                => $this->get_collection_params()
                ],
                [
                    'methods'             => \WP_REST_Server::CREATABLE,
                    'callback'            => [ $this, 'create_items' ],
                    'permission_callback' => [ $this, 'create_items_permission_check' ],
                    'args'                => $this->get_endpoint_args_for_item_schema(true )
                ]
            ]
        );
    }

    /**
     * Get items response
     */
    public function get_items( $request ) {
        
        $response = [
            'hub_token_key' => get_option( 'mso_hub_token_key', true ),
            'webhook_url'  => get_option( 'mso_webhook_url', true ),
        ];

        return rest_ensure_response( $response );
    }

    /**
     * Get items permission check
     */
    public function get_items_permission_check( $request ) {
        if ( array_intersect( $this->create_perssions, $this->current_user->roles ) ) {
            return true;
        }

        return false;
    }

    /**
     * Create item response
     */
    public function create_items( $request ) {

        // Data validation
        $hub_token_key = isset( $request['hub_token_key'] ) ? sanitize_text_field( $request['hub_token_key'] ): '';
        $webhook_url  = isset( $request['webhook_url'] ) ? sanitize_text_field( $request['webhook_url'] )  : '';
        
        // Save option data into WordPress
        update_option( 'mso_hub_token_key', $hub_token_key );
        update_option( 'mso_webhook_url', $webhook_url );

        $response = [
            'hub_token_key' => $hub_token_key,
            'webhook_url'  => $webhook_url,
            'message' => 'Settings saved successfully',
        ];

        return rest_ensure_response( $response );
        
    }

    /**
     * Create item permission check
     */
    public function create_items_permission_check( $request ) {
        if ( array_intersect( $this->create_perssions, $this->current_user->roles ) ) {
            return true;
        }

        return false;
    }

    /**
     * Retrives the query parameters for the items collection
     */
    public function get_collection_params() {
        return [];
    }

}