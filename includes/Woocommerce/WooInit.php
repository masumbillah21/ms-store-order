<?php
namespace MSO\Includes\Woocommerce;

class WooInit{

    public function __construct(){
        add_action( 'admin_notices', [ $this, 'mso_api_check_woocommerce_activation' ] );
        add_filter( 'manage_edit-shop_order_columns', [$this, 'mso_api_order_column'], 20 );
        add_action( 'manage_shop_order_posts_custom_column' , [$this, 'mso_api_orders_list_column_content'], 20, 2 );
    }

    public function mso_api_check_woocommerce_activation() {
		if ( !class_exists( 'woocommerce' ) ) { 

			echo '<div class="error">
                    <p><strong>'.__('Store Order requires the WooCommerce plugin to be installed and active. You can download ', 'mso') .'<a href="/wp-admin/plugin-install.php?s=WooCommerce&tab=search&type=term" target="_blank">WooCommerce</a> here.</strong></p>
                </div>';
			
		}
	}

    public function mso_api_order_column($columns)
    {
        $reordered_columns = array();

        // Inserting columns to a specific location
        foreach( $columns as $key => $column){
            $reordered_columns[$key] = $column;
            if( $key ==  'order_status' ){
                // Inserting after "Status" column
                $reordered_columns['api-call'] = __( 'Sync','mso');
                $reordered_columns['api-error'] = __( 'API Error','mso');

            }
        }
        return $reordered_columns;
    }

    public function mso_api_orders_list_column_content( $column, $post_id )
    {

        switch ( $column )
        {
            case 'api-call' :
                // Get custom post meta data
                $api_status = get_post_meta( $post_id, 'hub_status', true );

                if(!empty($api_status) && $api_status == true)
                    echo '<button type="button" class="button call_api_order button-primary"  data-order-id="'.$post_id.'" data-action="'.admin_url( 'admin-ajax.php' ).'" data-type="update">Update Order</button>';
                else
                    echo '<button type="button" class="button call_api_order button-primary" data-order-id="'.$post_id.'" data-action="'.admin_url( 'admin-ajax.php' ).'" data-type="save">Send Order</button>';

                break;
            case 'api-error':
                $api_error = get_post_meta( $post_id, 'hub_request_error', true );

                if(!empty($api_error))
                    echo $api_error;
                break;
        }
    }
}