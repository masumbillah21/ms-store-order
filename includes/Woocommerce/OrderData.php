<?php

namespace MSO\Includes\Woocommerce;

class OrderData
{

    protected $order;
    protected $order_id;

    public function __construct($order_id){
        $this->order_id = $order_id;
        $this->order = wc_get_order($order_id);
    }

    public function processOrderData(){

        $data = array(
            "order_id" => $this->order_id,
            "customer_name" => $this->order->get_billing_first_name() . ' ' . $this->order->get_billing_last_name(),
            "customer_email" => $this->order->get_billing_email(),
            "order_status" => $this->order->get_status(),
            "order_date" => date('Y-m-d H:i:s', strtotime($this->order->get_date_created())),
            "shipping_date" => date('Y-m-d H:i:s', strtotime($this->order->get_date_created() . '+ 14 days')),
            "order_notes" => $this->order->get_customer_note(),
        );

        return $data;
    }
}