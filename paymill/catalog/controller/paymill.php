<?php

/**
 * paymill
 *
 * @category   PayIntelligent
 * @copyright  Copyright (c) 2011 PayIntelligent GmbH (http://payintelligent.de)
 */
abstract class ControllerPaymentPaymill extends Controller
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
        $this->data['paymill_publickey'] = $this->config->get($this->getPaymentName() . '_publickey');
        $this->data['paymill_bridgeurl'] = $this->config->get($this->getPaymentName() . '_bridgeurl');
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
        $this->data['paymill_paymilllabel'] = $this->language->get('paymill_paymilllabel');

        $table = $this->getDatabaseName();
        $row = $this->db->query("SELECT COUNT(*)AS `Matches` FROM $table WHERE `userId`=" . $this->customer->getId());
        if($row->row['Matches'] == 1 && $this->config->get($this->getPaymentName() . '_fast_checkout')){
            $this->data['paymill_paymentname'] = $this->getPaymentName();
            $this->template = 'default/template/payment/paymillfastcheckout.tpl';
        }else{
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

            if (preg_match("/\/v2\//", $this->config->get($this->getPaymentName() . '_apiurl'))) {
                $libBase = dirname(dirname(dirname(__FILE__))) . '/v2/lib/';
                $libVersion = 'v2';
            } else {
                $libBase = dirname(dirname(dirname(__FILE__))) . '/v2/lib/';
                $libVersion = 'v1';
            }

            $amount = $this->currency->format($this->order_info['total'], $this->order_info['currency_code'], false, false);
            $amount = number_format(($amount), 2, '.', '');

            $data = array(
                'userId' => $this->customer->getId(),
                'libVersion' => $libVersion,
                'token' => $paymillToken,
                'amount' => $amount * 100,
                'currency' => $this->order_info['currency_code'],
                'name' => $this->order_info['lastname'] . ', ' . $this->order_info['firstname'],
                'email' => $this->order_info['email'],
                'description' => $this->config->get('config_name') . " " . $this->order_info['email'],
                'libBase' => $libBase,
                'privateKey' => $this->config->get($this->getPaymentName() . '_privatekey'),
                'apiUrl' => $this->config->get($this->getPaymentName() . '_apiurl'),
                'loggerCallback' => array('ControllerPaymentPaymill', 'log')
            );

            $table = $this->getDatabaseName();
            $row = $this->db->query("SELECT `clientId`, `paymentId` FROM $table WHERE `userId`=" . $this->customer->getId());
            if ($row->num_rows === 1 && $this->config->get($this->getPaymentName() . '_fast_checkout')) {
                $data['clientId'] = $row->row['clientId'];
                $data['paymentId'] = $row->row['paymentId'];
            }

            // process the payment
            $result = $this->processPayment($data);
            $this->log(
                    "Payment processing resulted in: "
                    . ($result ? "Success" : "Fail")
            );

            // finish the order if payment was sucessfully processed
            if ($result === true) {
                $this->log("Finish order.");
                $this->model_checkout_order->confirm(
                        $this->session->data['order_id'], 5, '', true
                );
                $this->redirect($this->url->link('checkout/success'));
            } else {
                $this->session->data['error_message'] = 'An error occured while processing your payment';
                $this->redirect($this->url->link('payment/' . $this->getPaymentName() . '/error'));
            }
        }
    }

    /**
     * Processes the payment against the paymill API
     * @param $params array The settings array
     * @return boolean
     */
    private function processPayment($params)
    {

        // setup the logger
        $logger = $params['loggerCallback'];

        // reformat paramters
        $params['currency'] = strtolower($params['currency']);

        // setup client params
        $clientParams = array(
            'email' => $params['email'],
            'description' => $params['name']
        );

        // setup credit card params
        $creditcardParams = array(
            'token' => $params['token']
        );

        // setup transaction params
        $transactionParams = array(
            'amount' => $params['amount'],
            'currency' => $params['currency'],
            'description' => $params['description']
        );

        require_once $params['libBase'] . 'Services/Paymill/Transactions.php';
        require_once $params['libBase'] . 'Services/Paymill/Clients.php';

        $clientsObject = new Services_Paymill_Clients(
                        $params['privateKey'], $params['apiUrl']
        );
        $transactionsObject = new Services_Paymill_Transactions(
                        $params['privateKey'], $params['apiUrl']
        );

        // In the PHP-Wrapper version v1 an explicit creditcard object exists.
        // This was replaced by a payments object in v2.
        if ($params['libVersion'] == 'v1') {
            require_once $params['libBase'] . 'Services/Paymill/Creditcards.php';
            $creditcardsObject = new Services_Paymill_Creditcards(
                            $params['privateKey'], $params['apiUrl']
            );
        } elseif ($params['libVersion'] == 'v2') {
            require_once $params['libBase'] . 'Services/Paymill/Payments.php';
            $creditcardsObject = new Services_Paymill_Payments(
                            $params['privateKey'], $params['apiUrl']
            );
        }

        // perform conection to the Paymill API and trigger the payment
        try {
            if (!array_key_exists('clientId', $params)) {
                // create client
                $client = $clientsObject->create($clientParams);
                if (!isset($client['id'])) {
                    call_user_func_array($logger, array("No client created" . var_export($client, true)));
                    return false;
                } else {
                    call_user_func_array($logger, array("Client created: " . $client['id']));
                }
                // create card
                $creditcardParams['client'] = $client['id'];
            } else {
                call_user_func_array($logger, array("Client using: " . $params['clientId']));
                $creditcardParams['client'] = $params['clientId'];
            }


            if (!array_key_exists('paymentId', $params)) {
                // create card
                $creditcard = $creditcardsObject->create($creditcardParams);
                if (!isset($creditcard['id'])) {
                    call_user_func_array($logger, array("No creditcard created: " . var_export($creditcard, true)));
                    return false;
                } else {
                    call_user_func_array($logger, array("Creditcard created: " . $creditcard['id']));
                }
                $transactionParams['payment'] = $creditcard['id'];
            } else {
                call_user_func_array($logger, array("Creditcard using: " . $params['paymentId']));
                $transactionParams['payment'] = $params['paymentId'];
            }
            $transaction = $transactionsObject->create($transactionParams);
            if (isset($transaction['data']['response_code'])) {
                call_user_func_array($logger, array("An Error occured: " . var_export($transaction, true)));
                return false;
            }

            if (!isset($transaction['id'])) {
                call_user_func_array($logger, array("No transaction created" . var_export($transaction, true)));
                return false;
            } else {
                call_user_func_array($logger, array("Transaction created: " . $transaction['id']));
            }

            // check result
            if (is_array($transaction) && array_key_exists('status', $transaction)) {
                if ($transaction['status'] == "closed") {
                    // transaction was successfully issued
                    if (!empty($params['userId'])) {
                        $this->_saveUserData($params['userId'], $creditcardParams['client'], $transactionParams['payment']);
                    }
                    return true;
                } elseif ($transaction['status'] == "open") {
                    // transaction was issued but status is open for any reason
                    call_user_func_array($logger, array("Status is open."));
                    return false;
                } else {
                    // another error occured
                    call_user_func_array($logger, array("Unknown error." . var_export($transaction, true)));
                    return false;
                }
            } else {
                // another error occured
                call_user_func_array($logger, array("Transaction could not be issued."));
                return false;
            }
        } catch (Services_Paymill_Exception $ex) {
            // paymill wrapper threw an exception
            call_user_func_array($logger, array("Exception thrown from paymill wrapper: " . $ex->getMessage()));
            return false;
        }

        return true;
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
    public function log($message)
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
