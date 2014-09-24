<?php

class ControllercustompaymillOrder extends Controller
{

    private function getPost($name, $default = null)
    {
        $value = $default;
        if (isset($this->request->request[$name])) {
            $value = $this->request->request[$name];
        }
        return $value;
    }

    public function index()
    {
        $this->template = 'custom/paymillOrder.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $orderId = $this->getPost('orderId', 0);

        $order_info = $this->model_sale_order->getOrder($orderId);
        if($order_info){
            $this->data['data_orderId'] = $orderId;
            $this->data['data_storename'] = $order_info['store_name'];
            $this->data['data_customer_name'] = $order_info['firstname'] . ' ' . $order_info['lastname'];
            $this->data['data_customer_email'] = $order_info['email'];
            $this->data['data_order_total'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']);
            $this->data['data_order_date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
            $this->data['data_order_payment_method'] = $order_info['payment_method'];
            $order_status_info = $this->model_localisation_order_status->getOrderStatus($order_info['order_status_id']);
            $this->data['data_order_status'] = $order_status_info ? $order_status_info['name']:'';

        }


        $this->response->setOutput($this->render());
    }

}
