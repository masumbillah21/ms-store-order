<?php
namespace MSO\Includes\Woocommerce;

use WP_REST_Controller;

class StoreWebHook extends WP_REST_Controller  {

    protected $namespace;
    protected $rest_base;

    protected $token_key;

    protected $whitelisted_domains;

            

    public function __construct() {
        $this->namespace = 'mso/v1';
        $this->rest_base = 'store-order';
        $this->token_key = get_option( 'mso_token_key', true );
        $this->whitelisted_domains = explode(',', get_option('mso_whitelisted_domains', true));
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

        $order_id = $request->get_param('order_id');
        
        $data = (new OrderData($order_id))->processOrderData();

        $response = [
            'data' => $data,
        ];

        return rest_ensure_response( $response );
    }

    /**
     * Get items permission check
     */
    public function get_items_permission_check( $request ) {
        $origin = $request->get_header('Origin');
        $token = $request->get_header('token');
       
        return in_array($origin, $this->whitelisted_domains) && $token == $this->token_key;
    }

    /**
     * Create item response
     */
    public function create_items( $request ) {

        $order_id = $request->get_param('order_id');
        $order_status = $request->get_param('order_status');
        $hub_notes = $request->get_param('hub_notes');

        $order = wc_get_order( $order_id );

        $res = '';

        if ( $order ) {
            $order->update_status( $order_status );
            $order->add_order_note( $hub_notes );

            $res = 'Order updated successfully.';
        } else {
            $res = 'Order not found!';
        }

        $response = [
            'response' => $res,
        ];

        return rest_ensure_response( $response );
        
    }

    /**
     * Create item permission check
     */
    public function create_items_permission_check( $request ) {

        $origin = $request->get_header('Origin');
        $token = $request->get_header('token');

        return in_array($origin, $this->whitelisted_domains) && $token == $this->token_key;

    }

    /**
     * Retrives the query parameters for the items collection
     */
    public function get_collection_params() {
        return [];
    }

}