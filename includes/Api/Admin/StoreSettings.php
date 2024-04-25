<?php
namespace MSO\Includes\Api\Admin;

use WP_REST_Controller;

class StoreSettings extends WP_REST_Controller  {

    protected $namespace;
    protected $rest_base;

    protected $current_user;

    protected $create_perssions;

    public function __construct() {
        $this->namespace = 'mso/v1';
        $this->rest_base = 'store-settings';
        $this->current_user = wp_get_current_user();
        $this->create_perssions = [
            'administrator',
            'shop_manager',
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
            'token_key' => get_option( 'mso_token_key', true ),
            'whitelisted_domains'  => get_option( 'mso_whitelisted_domains', true ),
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
        $token_key = isset( $request['token_key'] ) ? sanitize_text_field( $request['token_key'] ): '';
        $whitelisted_domains  = isset( $request['whitelisted_domains'] ) ? sanitize_text_field( $request['whitelisted_domains'] )  : '';
        
        // Save option data into WordPress
        update_option( 'mso_token_key', $token_key );
        update_option( 'mso_whitelisted_domains', $whitelisted_domains );

        $response = [
            'token_key' => $token_key,
            'whitelisted_domains'  => $whitelisted_domains,
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