<?php

namespace MSO\Includes\Woocommerce;

use MSO\Includes\Woocommerce\OrderData;

class ConnectWithHub {

    protected $huburl;
    protected $hubtoken;

    protected $origin;

    public function __construct(){

        $this->hubtoken = get_option( 'mso_hub_token_key', true );
        $this->huburl = get_option('mso_webhook_url', true);

        $this->origin = str_replace( ['https://', 'http://'], '', get_site_url() );

        add_action('woocommerce_order_status_changed', [$this, 'mso_trigger_on_order_status_changed'], 10, 3);
        add_action("wp_ajax_mso_api_order", [$this, "mso_hub_api_call_from_button"], 10, 1);

    }


    private function mso_hub_api_call_to_post($order_id){
        $data = new OrderData($order_id);

        $body = $data->processOrderData();

        $res = wp_remote_post($this->huburl, array(
            'method' => 'POST',
            'headers' => array(
                'Content-Type' => 'application/json',
                'Token' => $this->hubtoken,
                'Origin' => $this->origin
            ),
            'body' => json_encode($body),
        ));

        $order = wc_get_order($order_id);

        // Check for errors
        if (is_wp_error($res)) {
            $order->update_meta_data( 'hub_request_error', $res->get_error_message() );
        } else {
            $order->update_meta_data( 'hub_status', true );
        }
        $order->save();
    }

    private function mso_hub_api_call_to_get($order_id){

        $response = wp_remote_get($this->huburl.'?order_id='.$order_id, array(
            'headers' => array(
                'Content-Type' => 'application/json',
                'Token' => $this->hubtoken,
                'Origin' => $this->origin
            ),
        ));

        // Handle the response
        $order = wc_get_order($order_id);
        if (is_wp_error($response)) {
            $order->update_meta_data( 'hub_request_error', $response->get_error_message() );
        } else {
            $response_body = wp_remote_retrieve_body( $response );
            $order->update_meta_data( 'hub_status', true );

            $res = json_decode($response_body);
            $this->mso_update_order_data($res->data);
        }

        $order->save();
    }

    private function mso_update_order_data($data){
        $order = wc_get_order( $data->order_id );

        if ( $order ) {
            $order->update_status( $data->order_status );
            $order->add_order_note( $data->hub_notes );
        }
    }


    public function mso_trigger_on_order_status_changed($order_id, $old_status, $new_status){

        if($old_status != $new_status){

            $this->mso_hub_api_call_to_post($order_id);

        }

    }

    // Trigger On order create/update on button click
    public function mso_hub_api_call_from_button(){
        if($_SERVER["REQUEST_METHOD"] == "POST"){
            $post = [];

            wp_parse_str( $_POST['mso_api_order'], $post );
    
            extract($post);

            if($type == 'save'){
                $this->mso_hub_api_call_to_post($id);
            } elseif ($type == 'update') {
               $this->mso_hub_api_call_to_get($id);
            }

            wp_send_json_success( __('Data Procced', 'mso') );
        }
        
    }

    


}
