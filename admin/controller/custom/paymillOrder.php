<?php

require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/paymill/lib/Services/Paymill/LoggingInterface.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/paymill/lib/Services/Paymill/Preauthorizations.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/paymill/lib/Services/Paymill/Transactions.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/paymill/lib/Services/Paymill/Refunds.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/paymill/lib/Services/Paymill/PaymentProcessor.php';
require_once dirname(dirname(dirname(dirname(__FILE__)))) . '/paymill/metadata.php';

class ControllercustompaymillOrder extends Controller implements Services_Paymill_LoggingInterface
{

    /**
     * @var Services_Paymill_PaymentProcessor
     */
    private $paymillProcessor;

    /**
     * @var Services_Paymill_Preauthorizations
     */
    private $paymillPreauth;

    /**
     * @var Services_Paymill_Transactions
     */
    private $paymillTransaction;

    /**
     * @var Services_Paymill_Refunds
     */
    private $paymillRefund;

    /**
     * @var string
     */
    private $apiEndpoint = 'https://api.paymill.com/v2/';

    private $logId;

    public function init(){
        $this->logId = time();
        $key = $this->config->get('paymillcreditcard_privatekey');
        $this->paymillProcessor = new Services_Paymill_PaymentProcessor($key, $this->apiEndpoint);
        $this->paymillPreauth = new Services_Paymill_Preauthorizations($key, $this->apiEndpoint);
        $this->paymillTransaction = new Services_Paymill_Transactions($key, $this->apiEndpoint);
        $this->paymillRefund = new Services_Paymill_Refunds($key, $this->apiEndpoint);

        $metadata = new metadata();
        $source = $metadata->getVersion() . "_opencart_" . VERSION;
        $this->paymillProcessor->setSource($source);
        $this->paymillProcessor->setLogger($this);
    }

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
        $this->load->model('sale/order');
        $this->load->model('localisation/order_status');
        $this->load->language('sale/order');
        $this->load->language('custom/paymillOrder');
        $this->template = 'custom/paymillOrder.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $orderId = $this->getPost('orderId', 0);

        $order_info = $this->model_sale_order->getOrder($orderId);
        $this->data['data_orderId'] = '-';
        $this->data['data_storename'] = '-';
        $this->data['data_customer_firstname'] = '-';
        $this->data['data_customer_lastname'] = '-';
        $this->data['data_customer_email'] = '-';
        $this->data['data_order_total'] = '-';
        $this->data['data_order_date_added'] = '-';
        $this->data['data_order_payment_method'] = '-';
        $this->data['data_order_status'] = '-';

        $this->data['text_order_id'] = $this->language->get('text_order_id');
        $this->data['text_store_name'] = $this->language->get('text_store_name');
        $this->data['text_firstname'] = $this->language->get('text_firstname');
        $this->data['text_lastname'] = $this->language->get('text_lastname');
        $this->data['text_email'] = $this->language->get('text_email');
        $this->data['column_total'] = $this->language->get('column_total');
        $this->data['text_date_added'] = $this->language->get('text_date_added');
        $this->data['text_payment_method'] = $this->language->get('text_payment_method');
        $this->data['text_order_status'] = $this->language->get('text_order_status');
        $this->data['text_capture_success'] = $this->language->get('capture_success');
        $this->data['text_capture_failure'] = $this->language->get('capture_failure');
        $this->data['text_refund_success'] = $this->language->get('refund_success');
        $this->data['text_refund_failure'] = $this->language->get('refund_failure');

        if($order_info){
            $this->data['data_orderId'] = $orderId;
            $this->data['data_storename'] = $order_info['store_name'];
            $this->data['data_customer_firstname'] = $order_info['firstname'];
            $this->data['data_customer_lastname'] = $order_info['lastname'];
            $this->data['data_customer_email'] = $order_info['email'];
            $this->data['data_order_total'] = $this->currency->format($order_info['total'], $order_info['currency_code'], $order_info['currency_value']);
            $this->data['data_order_date_added'] = date($this->language->get('date_format_short'), strtotime($order_info['date_added']));
            $this->data['data_order_payment_method'] = $order_info['payment_method'];
            $order_status_info = $this->model_localisation_order_status->getOrderStatus($order_info['order_status_id']);
            $this->data['data_order_status'] = $order_status_info ? $order_status_info['name']:'';
        }

        $url_capture = $this->url->link('custom/paymillOrder/capture', '', 'SSL');
        $this->data['url_capture'] = $url_capture .'&token=' . $this->session->data['token'] . '&orderId='.$orderId;

        $url_refund = $this->url->link('custom/paymillOrder/refund', '', 'SSL');
        $this->data['url_refund'] = $url_refund .'&token=' . $this->session->data['token'] . '&orderId='.$orderId;

        $this->response->setOutput($this->render());
    }

    public function capture(){
        $result = false;
        $orderId = $this->getPost('orderId', 0);
        $details = $this->getOrderDetails($orderId);
        if(!is_null($details) && array_key_exists('preauth_id', $details) && empty($details['transaction_id'])){
            $result = $this->proceedCapture($details['preauth_id']);
        }
        echo $result ? 'OK' : 'NOK';
    }

    private function proceedCapture($preauth_id){
        $result = false;
        $this->init();
        $orderId = $this->getPost('orderId', 0);
        $preauth = $this->paymillPreauth->getOne($preauth_id);
        if(is_array($preauth)){
            $this->paymillProcessor->setAmount($preauth['amount']);
            $this->paymillProcessor->setCurrency($preauth['currency']);
            $this->paymillProcessor->setPreauthId($preauth_id);
            $this->paymillProcessor->setDescription('Capture '. $preauth_id);
            try{
                $result = $this->paymillProcessor->capture();
                $this->log('Capture successfully', $this->paymillProcessor->getTransactionId());
                $this->saveTransactionId($orderId, $this->paymillProcessor->getTransactionId());
            } catch (Exception $ex) {
                $result = false;
            }
        }

        return $result;
    }

    public function refund(){
        $result = false;
        $orderId = $this->getPost('orderId', 0);
        $details = $this->getOrderDetails($orderId);
        if(!is_null($details) && array_key_exists('transaction_id', $details)){
            $result = $this->proceedRefund($details['transaction_id']);
        }
        echo $result ? 'OK' : 'NOK';
    }

    private function proceedRefund($transactionId){
        $result = false;
        $this->init();
        $transaction = $this->paymillTransaction->getOne($transactionId);
        $this->log('Transaction used for Refund', var_export($transaction, true));
        if(is_array($transaction)){
            try{
                $result = $this->paymillRefund->create(array(
                    'transactionId' => $transactionId,
                    'params' => array(
                        'amount' => $transaction['origin_amount']
                    )
                ));
                $this->log('Refund successfully', $transaction['id']);
            } catch (Exception $ex) {
                $result = false;
            }
        }
        return $result;
    }

    private function getOrderDetails($orderId){
        $where = 'WHERE order_id ='. $this->db->escape($orderId);
        $result = $this->db->query('SELECT * FROM `' . DB_PREFIX . 'pigmbh_paymill_orders` ' . $where);
        if($result->num_rows === 1){
            return $result->row;
        }
    }

    private function saveTransactionId($orderId, $id){
        $where = 'WHERE order_id ='. $this->db->escape($orderId);
        $result = $this->db->query('UPDATE `'.DB_PREFIX.'pigmbh_paymill_orders` SET (`transaction_id` ='.$this->db->escape($id).') ' . $where);
        if($result->num_rows === 1){
            return $result->row;
        }
    }

    /**
     * Logger for events
     * @return void
     */
    public function log($message, $debuginfo)
    {
        if ($this->config->get('paymillcreditcard_logging')) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "pigmbh_paymill_logging` (`identifier`,`debug`,`message`) VALUES ('" . $this->logId . "', '" . $this->db->escape($debuginfo) . "', '" . $this->db->escape($message) . "')");
        }
    }

}
