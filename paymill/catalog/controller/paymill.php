<?php

require_once dirname(dirname(dirname(__FILE__))) . '/lib/Services/Paymill/PaymentProcessor.php';
require_once dirname(dirname(dirname(__FILE__))) . '/lib/Services/Paymill/LoggingInterface.php';

/**
 * paymill
 *
 * @category   PayIntelligent
 * @copyright  Copyright (c) 2011 PayIntelligent GmbH (http://payintelligent.de)
 */
abstract class ControllerPaymentPaymill extends Controller implements Services_Paymill_LoggingInterface
{

    abstract protected function getPaymentName();

    abstract protected function getDatabaseName();

    public function index()
    {
        global $config;
        $this->baseUrl = preg_replace("/\/index\.php/", "", $this->request->server['SCRIPT_NAME']);
        $this->load->model('checkout/order');
        $this->order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $amount = $this->currency->format($this->order_info['total'], $this->order_info['currency_code'], false, false);
        $this->data['paymill_amount'] = number_format(($amount), 2, '.', '');
        $this->data['paymill_currency'] = $this->order_info['currency_code'];
        $this->data['paymill_fullname'] = $this->order_info['firstname'] . ' ' . $this->order_info['lastname'];
        $this->data['paymill_css'] = $this->baseUrl . '/catalog/view/theme/default/stylesheet/paymill_styles.css';
        $this->data['paymill_publickey'] = trim($this->config->get($this->getPaymentName() . '_publickey'));
        $this->data['paymill_debugging'] = $this->config->get($this->getPaymentName() . '_debugging');
        $this->data['button_confirm'] = $this->language->get('button_confirm');

        $this->language->load('payment/' . $this->getPaymentName());
        $this->data['paymill_accountholder'] = $this->language->get('paymill_accountholder');
        $this->data['paymill_accountnumber'] = $this->language->get('paymill_accountnumber');
        $this->data['paymill_banknumber'] = $this->language->get('paymill_banknumber');
        $this->data['paymill_cardholder'] = $this->language->get('paymill_cardholder');
        $this->data['paymill_cardnumber'] = $this->language->get('paymill_cardnumber');
        $this->data['paymill_cvc'] = $this->language->get('paymill_cvc');
        $this->data['paymill_birthday'] = $this->language->get('paymill_birthday');
        $this->data['paymill_description'] = $this->language->get('paymill_description');
        $this->data['paymill_paymilllabel_cc'] = $this->language->get('paymill_paymilllabel_cc');
        $this->data['paymill_paymilllabel_elv'] = $this->language->get('paymill_paymilllabel_elv');

        $table = $this->getDatabaseName();

        $fastCheckout = false;
        if ($this->customer->getId() != null) {
            $row = $this->db->query("SELECT COUNT(*) AS `Matches` FROM $table WHERE `userId`=" . $this->customer->getId());
            $fastCheckout = $row->row['Matches'] == 1;
        }

        if ($fastCheckout && $this->config->get($this->getPaymentName() . '_fast_checkout')) {
            $this->data['paymill_paymentname'] = $this->getPaymentName();
            $this->template = 'default/template/payment/paymillfastcheckout.tpl';
        } else {
            $this->template = 'default/template/payment/' . $this->getPaymentName() . '.tpl';
        }

        $this->render();
    }

    public function confirm()
    {
        // read transaction token from session
        $paymillToken = $this->request->post['paymillToken'];

        // check if token present
        if (empty($paymillToken)) {
            $this->log("No paymill token was provided. Redirect to payments page.");
            $this->redirect($this->url->link('checkout/checkout'));
        } else {
            $this->log("Start processing payment with token " . $paymillToken);
            $this->load->model('checkout/order');
            $this->order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

            $amount = $this->currency->format($this->order_info['total'], $this->order_info['currency_code'], false, false);
            $amount = number_format(($amount), 2, '.', '') * 100;

            $paymentProcessor = new Services_Paymill_PaymentProcessor();
            $paymentProcessor->setToken($paymillToken);
            $paymentProcessor->setAmount((int)$amount);
            $paymentProcessor->setPrivateKey(trim($this->config->get($this->getPaymentName() . '_privatekey')));
            $paymentProcessor->setApiUrl('https://api.paymill.com/v2/');
            $paymentProcessor->setCurrency($this->order_info['currency_code']);
            $paymentProcessor->setDescription($this->config->get('config_name') . " " . $this->order_info['email']);
            $paymentProcessor->setEmail($this->order_info['email']);
            $paymentProcessor->setLogger($this);
            $paymentProcessor->setName($this->order_info['lastname'] . ', ' . $this->order_info['firstname']);

            if ($this->customer->getId() != null) {
                $table = $this->getDatabaseName();
                $row = $this->db->query("SELECT `clientId`, `paymentId` FROM $table WHERE `userId`=" . $this->customer->getId());
                if ($row->num_rows === 1 && $this->config->get($this->getPaymentName() . '_fast_checkout')) {
                    $paymentProcessor->setPaymentId($row->row['paymentId']);
                    $paymentProcessor->setClientId($row->row['clientId']);
                }
            }

            // process the payment
            $result = $paymentProcessor->processPayment();
            $this->log(
                    "Payment processing resulted in: "
                    . ($result ? "Success" : "Fail")
            );

            // finish the order if payment was sucessfully processed
            if ($result === true) {
                $this->log("Finish order.");
                $this->_saveUserData($this->customer->getId(), $paymentProcessor->getClientId(), $paymentProcessor->getPaymentId());
                $this->model_checkout_order->confirm(
                        $this->session->data['order_id'], $this->config->get('config_complete_status_id'), '', true
                );
                $this->redirect($this->url->link('checkout/success'));
            } else {
                $this->session->data['error_message'] = 'An error occured while processing your payment';
                $this->redirect($this->url->link('payment/' . $this->getPaymentName() . '/error'));
            }
        }
    }

    private function _saveUserData($userId, $clientId, $paymentId)
    {
        $table = $this->getDatabaseName();
        try {
            $this->db->query("REPLACE INTO `$table` (`userId`, `clientId`, `paymentId`) VALUES($userId, '$clientId', '$paymentId')");
            $this->log("Userdata stored.");
        } catch (Exception $exception) {
            $this->log("Error while saving Userdata: " . $exception->getMessage());
        }
    }

    /**
     * Logger for events
     * @return void
     */
    public function log($message, $debuginfo = null)
    {
        $logfile = dirname(dirname(dirname(dirname(__FILE__)))) . '/paymill/log/log.txt';
        if (is_writable($logfile) && $this->config->get($this->getPaymentName() . '_logging')) {
            $handle = fopen($logfile, 'a'); //
            fwrite($handle, "[" . date(DATE_RFC822) . "] " . $message . "\n");
            fclose($handle);
        }
    }

    /**
     * Shows the Errorpage and a message for the customer
     * @param string $message
     */
    public function error()
    {
        global $config;
        $this->language->load('payment/' . $this->getPaymentName());
        $this->data['heading_title'] = $this->language->get('heading_title');
        $this->data['button_viewcart'] = $this->language->get('button_viewcart');
        $this->data['cart'] = $this->url->link('checkout/cart');

        $this->data['error_message'] = $this->session->data['error_message'];
        $this->template = 'default/template/payment/paymill_error.tpl';
        if (file_exists(DIR_TEMPLATE . $this->config->get('config_template') . '/template/payment/paymill_error.tpl')) {
            $this->template = $this->config->get('config_template') . '/template/payment/paymill_error.tpl';
        }

        $this->children = array(
            'common/column_right',
            'common/footer',
            'common/column_left',
            'common/header',
            'payment/' . $this->session->data['payment_method']['code']
        );

        $this->response->setOutput($this->render());
    }

}
