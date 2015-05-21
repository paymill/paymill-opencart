<?php

require_once dirname(dirname(dirname(__FILE__))) . '/lib/Services/Paymill/PaymentProcessor.php';
require_once dirname(dirname(dirname(__FILE__))) . '/lib/Services/Paymill/LoggingInterface.php';
require_once dirname(dirname(dirname(__FILE__))) . '/lib/Services/Paymill/Clients.php';
require_once dirname(dirname(dirname(__FILE__))) . '/lib/Services/Paymill/Payments.php';
require_once dirname(dirname(dirname(__FILE__))) . '/lib/Services/Paymill/Transactions.php';
require_once dirname(dirname(dirname(__FILE__))) . '/metadata.php';

/**
 * paymill
 *
 * @category   PayIntelligent
 * @copyright  Copyright (c) 2011 PayIntelligent GmbH (http://payintelligent.de)
 */
abstract class ControllerPaymentPaymill extends Controller implements Services_Paymill_LoggingInterface
{

    protected $_logId;
    protected $_response_codes = array(
        '10001' => 'General undefined response.',
        '10002' => 'Still waiting on something.',
        '20000' => 'General success response.',
        '40000' => 'General problem with data.',
        '40001' => 'General problem with payment data.',
        '40100' => 'Problem with credit card data.',
        '40101' => 'Problem with cvv.',
        '40102' => 'Card expired or not yet valid.',
        '40103' => 'Limit exceeded.',
        '40104' => 'Card invalid.',
        '40105' => 'Expiry date not valid.',
        '40106' => 'Credit card brand required.',
        '40200' => 'Problem with bank account data.',
        '40201' => 'Bank account data combination mismatch.',
        '40202' => 'User authentication failed.',
        '40300' => 'Problem with 3d secure data.',
        '40301' => 'Currency / amount mismatch',
        '40400' => 'Problem with input data.',
        '40401' => 'Amount too low or zero.',
        '40402' => 'Usage field too long.',
        '40403' => 'Currency not allowed.',
        '50000' => 'General problem with backend.',
        '50001' => 'Country blacklisted.',
        '50100' => 'Technical error with credit card.',
        '50101' => 'Error limit exceeded.',
        '50102' => 'Card declined by authorization system.',
        '50103' => 'Manipulation or stolen card.',
        '50104' => 'Card restricted.',
        '50105' => 'Invalid card configuration data.',
        '50200' => 'Technical error with bank account.',
        '50201' => 'Card blacklisted.',
        '50300' => 'Technical error with 3D secure.',
        '50400' => 'Decline because of risk issues.',
        '50500' => 'General timeout.',
        '50501' => 'Timeout on side of the acquirer.',
        '50502' => 'Risk management transaction timeout.',
        '50600' => 'Duplicate transaction.'
    );

    abstract protected function getPaymentName();

    abstract protected function getDatabaseName();

    public function getVersion()
    {
        $metadata = new metadata();
        return $metadata->getVersion();
    }

    public function index()
    {
        global $config;
        $this->baseUrl = preg_replace("/\/index\.php/", "", $this->request->server['SCRIPT_NAME']);
        $this->load->model('checkout/order');
        $this->order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);
        $amount = $this->currency->format($this->order_info['total'], $this->order_info['currency_code'], false, false);
	
        $this->data['paymill_amount'] = $amount;
        $this->data['paymill_currency'] = $this->order_info['currency_code'];
        $this->data['paymill_fullname'] = $this->order_info['firstname'] . ' ' . $this->order_info['lastname'];
        $this->data['paymill_css'] = $this->baseUrl . '/catalog/view/theme/default/stylesheet/paymill_styles.css';
        $this->data['paymill_image_folder'] = $this->baseUrl . '/catalog/view/theme/default/image/payment';
        $this->data['paymill_js'] = $this->baseUrl . '/catalog/view/javascript/paymill/';
        $this->data['paymill_publickey'] = trim($this->config->get($this->getPaymentName() . '_publickey'));
        $this->data['paymill_debugging'] = $this->config->get($this->getPaymentName() . '_debugging');
        $this->data['paymill_buttonSolution'] = $this->config->get($this->getPaymentName() . '_buttonSolution');
        $this->data['button_confirm'] = $this->language->get('button_confirm');

        $this->language->load('payment/' . $this->getPaymentName());
        $this->data['paymill_accountholder'] = $this->language->get('paymill_accountholder');
        $this->data['paymill_accountnumber'] = $this->language->get('paymill_accountnumber');
        $this->data['paymill_banknumber'] = $this->language->get('paymill_banknumber');
        $this->data['paymill_iban'] = $this->language->get('paymill_iban');
        $this->data['paymill_bic'] = $this->language->get('paymill_bic');
        $this->data['paymill_cardholder'] = $this->language->get('paymill_cardholder');
        $this->data['paymill_cardnumber'] = $this->language->get('paymill_cardnumber');
        $this->data['paymill_cvc'] = $this->language->get('paymill_cvc');
        $this->data['paymill_expirydate'] = $this->language->get('paymill_expirydate');
        $this->data['paymill_description'] = $this->language->get('paymill_description');
        $this->data['paymill_paymilllabel_cc'] = $this->language->get('paymill_paymilllabel_cc');
        $this->data['paymill_paymilllabel_elv'] = $this->language->get('paymill_paymilllabel_elv');
        $this->data['paymill_icon_text'] = $this->language->get('paymill_icon_text');

        $this->data['paymill_error'] = isset($this->session->data['error_message']) ? $this->session->data['error_message'] : null;
        $this->data['paymill_javascript_error'] = $this->language->get('error_javascript');
        $this->data['paymill_icon_visa'] = $this->config->get($this->getPaymentName() . '_icon_visa');
        $this->data['paymill_icon_master'] = $this->config->get($this->getPaymentName() . '_icon_master');
        $this->data['paymill_icon_amex'] = $this->config->get($this->getPaymentName() . '_icon_amex');
        $this->data['paymill_icon_jcb'] = $this->config->get($this->getPaymentName() . '_icon_jcb');
        $this->data['paymill_icon_maestro'] = $this->config->get($this->getPaymentName() . '_icon_maestro');
        $this->data['paymill_icon_diners_club'] = $this->config->get($this->getPaymentName() . '_icon_diners_club');
        $this->data['paymill_icon_discover'] = $this->config->get($this->getPaymentName() . '_icon_discover');
        $this->data['paymill_icon_china_unionpay'] = $this->config->get($this->getPaymentName() . '_icon_china_unionpay');
        $this->data['paymill_icon_dankort'] = $this->config->get($this->getPaymentName() . '_icon_dankort');
        $this->data['paymill_icon_carta_si'] = $this->config->get($this->getPaymentName() . '_icon_carta_si');
        $this->data['paymill_icon_carte_bleue'] = $this->config->get($this->getPaymentName() . '_icon_carte_bleue');
        $this->data['paymill_icon'] = $this->showCreditcardIcons();


        $table = $this->getDatabaseName();

        $payment = null;
        if ($this->customer->getId() != null) {
            $row = $this->db->query("SELECT `paymentID` FROM $table WHERE `userId`=" . $this->customer->getId());
            if (!empty($row->row['paymentID'])) {
                $privateKey = trim($this->config->get($this->getPaymentName() . '_privatekey'));
                $paymentObject = new Services_Paymill_Payments($privateKey, 'https://api.paymill.com/v2/');
                $payment = $paymentObject->getOne($row->row['paymentID']);
            }
        }
        if (isset($payment['expire_month'])) {
            $payment['expire_month'] = $payment['expire_month'] <= 9 ? '0' . $payment['expire_month'] : $payment['expire_month'];
            $payment['expire_date'] = $payment['expire_month'] . "/" . $payment['expire_year'];
        } else {
            $payment['expire_date'] = null;
        }

        $this->data['paymill_prefilled'] = $payment;

        if ($this->getPaymentName() == 'paymillcreditcard') {
            $this->data['paymill_form_action'] = "index.php?route=payment/paymillcreditcard/confirm";
        } elseif ($this->getPaymentName() == 'paymilldirectdebit') {
            $this->data['paymill_form_action'] = "index.php?route=payment/paymilldirectdebit/confirm";
        }

        $this->data['paymill_activepayment'] = $this->getPaymentName();
        if($this->getPaymentName() == "paymillcreditcard" && !$this->config->get($this->getPaymentName() . '_pci')) {
            $this->data['paymill_load_frame_fastcheckout'] = false;
            if(isset($payment['last4']) && isset($payment['expire_date'])) {
                $this->data['paymill_load_frame_fastcheckout'] = true;
            }
            $this->template = 'default/template/payment/paymill_pci_frame.tpl';
            if (file_exists($this->config->get('config_template') . '/template/payment/paymill_frame.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/payment/paymill_frame.tpl';
            }
        } else {
            $this->template = 'default/template/payment/paymill.tpl';
            if (file_exists($this->config->get('config_template') . '/template/payment/paymill.tpl')) {
                $this->template = $this->config->get('config_template') . '/template/payment/paymill.tpl';
            }
        }
        $this->render();
    }

    private function showCreditcardIcons()
    {
        $shouldBe = array("amex", "carta-si", "carte-bleue", "dankort", "diners-club", "discover", "jcb", "maestro", "mastercard", "china-unionpay", "visa");
        $result = array();
        $result[] = $this->config->get($this->getPaymentName() . '_icon_amex') ? 'amex' : '';
        $result[] = $this->config->get($this->getPaymentName() . '_icon_carta_si') ? 'carta-si' : '';
        $result[] = $this->config->get($this->getPaymentName() . '_icon_carte_bleue') ? 'carte-bleue' : '';
        $result[] = $this->config->get($this->getPaymentName() . '_icon_dankort') ? 'dankort' : '';
        $result[] = $this->config->get($this->getPaymentName() . '_icon_diners_club') ? 'diners-club' : '';
        $result[] = $this->config->get($this->getPaymentName() . '_icon_discover') ? 'discover' : '';
        $result[] = $this->config->get($this->getPaymentName() . '_icon_jcb') ? 'jcb' : '';
        $result[] = $this->config->get($this->getPaymentName() . '_icon_maestro') ? 'maestro' : '';
        $result[] = $this->config->get($this->getPaymentName() . '_icon_master') ? 'mastercard' : '';
        $result[] = $this->config->get($this->getPaymentName() . '_icon_china_unionpay') ? 'china-unionpay' : '';
        $result[] = $this->config->get($this->getPaymentName() . '_icon_visa') ? 'visa' : '';

        $arrayLength = count(array_diff($shouldBe, $result));
        return ($arrayLength === 0 || $arrayLength === 11) ? $shouldBe : $result;
    }

    public function confirm()
    {
        $preauth = (bool)$this->config->get($this->getPaymentName() . '_preauth');

	// read transaction token from session
        if (isset($this->request->post['paymillToken'])) {
            $paymillToken = $this->request->post['paymillToken'];
        }
        if (isset($this->request->post['paymillFastcheckout'])) {
            $fastcheckout = $this->request->post['paymillFastcheckout'];
        }
        if (isset($this->request->post['paymillName'])) {
            $name = $this->request->post['paymillName'];
        }

        $this->_logId = time();
        $this->language->load('payment/' . $this->getPaymentName());
        // check if token present
        if (empty($paymillToken)) {
            $this->log("No paymill token was provided. Redirect to payments page.", '');
            $this->redirect($this->url->link('checkout/checkout'));
        } else {
            $this->log("Start processing payment with token.", $paymillToken);
            $this->load->model('checkout/order');
            $this->order_info = $this->model_checkout_order->getOrder($this->session->data['order_id']);

            $amountRaw = $this->currency->format($this->order_info['total'], $this->order_info['currency_code'], false, false);
            $amount = number_format($amountRaw, 2, '.', '') * 100;

            $source = $this->getVersion() . "_opencart_" . VERSION;
            $privateKey = trim($this->config->get($this->getPaymentName() . '_privatekey'));

            $paymentProcessor = new Services_Paymill_PaymentProcessor();
            $paymentProcessor->setToken($paymillToken);
            $paymentProcessor->setAmount((int) $amount);
            $paymentProcessor->setPrivateKey($privateKey);
            $paymentProcessor->setApiUrl('https://api.paymill.com/v2/');
            $paymentProcessor->setCurrency($this->order_info['currency_code']);
            $paymentProcessor->setDescription(substr("OrderID:" . $this->session->data['order_id'] . " " . $this->order_info['email'],0,128));
            $paymentProcessor->setEmail($this->order_info['email']);
            $paymentProcessor->setLogger($this);
            $paymentProcessor->setName($name);
            $paymentProcessor->setSource($source);

            if ($this->customer->getId() != null) {
                $table = $this->getDatabaseName();
                $row = $this->db->query("SELECT `clientId`, `paymentId` FROM $table WHERE `userId`=" . $this->customer->getId());
                if ($row->num_rows === 1) {
                    if ($fastcheckout === "true") {
                        $paymentID = empty($row->row['paymentId']) ? null : $row->row['paymentId'];
                        $paymentProcessor->setPaymentId($paymentID);
                    }

                    $clientObject = new Services_Paymill_Clients($privateKey, 'https://api.paymill.com/v2/');
                    $client = $clientObject->getOne($row->row['clientId']);
                    $paymentProcessor->setClientId($row->row['clientId']);
                    if (array_key_exists('email', $client)) {
                        if ($client['email'] !== $this->order_info['email']) {
                            $clientObject->update(array(
                                'id' => $row->row['clientId'],
                                'email' => $this->order_info['email'],
                            ));
                            $this->log("Client-mail has been changed. Client updated", $this->order_info['email']);
                        }
                    }
                }
            }
            $captureNow = !$preauth;
            // process the payment
                $result = $paymentProcessor->processPayment($captureNow);
            $this->log(
                "Payment processing resulted in: "
                , ($result ? "Success" : "Fail")
            );


            if(!$captureNow){
                $preauthId = $paymentProcessor->getPreauthId();
                $transId = '';
            }else{
                $preauthId = '';
                $transId = $paymentProcessor->getTransactionId();
            }

            $comment = '';
            if ($this->getPaymentName() == 'paymilldirectdebit') {
                $daysUntil = (int) $this->config->get($this->getPaymentName() . '_sepa_date');
                $comment = $this->language->get('paymill_infotext_sepa') . ": ";
                $comment .= date("d.m.Y", strtotime("+ $daysUntil DAYS"));
            }

            // finish the order if payment was sucessfully processed
            if ($result === true) {
                $this->log("Finish order.", '');
                $this->_saveUserData($this->customer->getId(), $paymentProcessor->getClientId(), $paymentProcessor->getPaymentId());

                $this->model_checkout_order->confirm(
                    $this->session->data['order_id'], $this->config->get('config_order_status_id'), $comment, true
                );
                $this->_updateOrderComment($this->session->data['order_id'], $comment);
                $this->_saveOrderDetails($this->session->data['order_id'], $transId, $preauthId);
                $this->redirect($this->url->link('checkout/success'));
            } else {
                $responseCode = array_key_exists($paymentProcessor->getErrorCode(), $this->_response_codes) ? $this->_response_codes[$paymentProcessor->getErrorCode()] : 'unknown error';
                $this->session->data['error_message'] = 'An error occured while processing your payment: ' . $responseCode;
                $this->redirect($this->url->link('payment/' . $this->getPaymentName() . '/error'));
            }
        }
    }

    private function _saveUserData($userId, $clientId, $paymentId)
    {
        $table = $this->getDatabaseName();
        try {
            if ($userId != null) {
                $row = $this->db->query("SELECT `clientId`, `paymentId` FROM $table WHERE `userId`=" . $userId);
                $dataAvailable = $row->num_rows === 1;
                if (!$dataAvailable) {
                    if ($this->config->get($this->getPaymentName() . '_fast_checkout')) {
                        $this->db->query("REPLACE INTO `$table` (`userId`, `clientId`, `paymentId`) VALUES($userId, '$clientId', '$paymentId')");
                    } else {
                        $this->db->query("REPLACE INTO `$table` (`userId`, `clientId`) VALUES($userId, '$clientId')");
                    }
                } else {
                    if ($this->config->get($this->getPaymentName() . '_fast_checkout')) {
                        $this->db->query("UPDATE `$table` SET `clientId`='$clientId', `paymentId`='$paymentId';");
                    } else {
                        $this->db->query("UPDATE `$table` SET `clientId`='$clientId';");
                    }
                }
                $this->log("Userdata stored.", '');
            }
        } catch (Exception $exception) {
            $this->log("Error while saving Userdata: " . $exception->getMessage());
        }
    }

    private function _saveOrderDetails($orderId, $transId, $preauthId) {
        $orderId = $this->db->escape($orderId);
        $preauthId = $this->db->escape($preauthId);
        $this->db->query("INSERT INTO `" . DB_PREFIX . "pigmbh_paymill_orders` (`order_id`,`transaction_id`,`preauth_id`) VALUES ('" . $orderId . "', '" . $transId . "','".$preauthId."')");
    }

    /**
     * adds payday timestamp to the order comment
     */
    private function _updateOrderComment($orderId, $comment)
    {
        $result = $this->db->query("SELECT `comment` FROM `" . DB_PREFIX . "order` WHERE `order_id`=" . mysql_real_escape_string($orderId));
        $this->db->query("UPDATE `" . DB_PREFIX . "order` SET `comment`='" . mysql_real_escape_string($result->row['comment']) . "\n" . $comment . "' WHERE `order_id`=" . mysql_real_escape_string($orderId));
    }

    /**
     * Logger for events
     * @return void
     */
    public function log($message, $debuginfo)
    {
        if ($this->config->get($this->getPaymentName() . '_logging')) {
            $this->db->query("INSERT INTO `" . DB_PREFIX . "pigmbh_paymill_logging` (`identifier`,`debug`,`message`) VALUES ('" . $this->_logId . "', '" . $this->db->escape($debuginfo) . "', '" . $this->db->escape($message) . "')");
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
        if (file_exists($this->config->get('config_template') . '/template/payment/paymill_error.tpl')) {
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

    /**
     * Listens
     *
     */
    public function webHookEndpoint()
    {
        global $config;
        $this->_logId = time();
        $request = json_decode(file_get_contents('php://input'), true);
        $this->log('WebHookValidation', var_export($this->validateNotification($request), true));
        $this->log('WebHookBody', var_export($request, true));

        if ($this->validateNotification($request)) {
            $order_new_status = '11'; //status refunded according to vanilla-installation
            $order_id = $this->getOrderIdFromNotification($request['event']['event_resource']['transaction']['description']);
            $this->log('WebHook UpdateOrderId', var_export($order_id, true));
            $this->load->model('checkout/order');
            $this->model_checkout_order->update($order_id, $order_new_status);
        }
    }

    private function validateNotification($notification)
    {
        if (isset($notification) && !empty($notification)) {
            // Check eventtype
            if (isset($notification['event']['event_type'])) {
                if ($notification['event']['event_type'] == 'refunded.succeeded') {
                    $id = null;
                    if (isset($notification['event']['event_resource']['transaction']['id'])) {
                        $id = $notification['event']['event_resource']['transaction']['id'];
                    }
                    $privateKey = trim($this->config->get($this->getPaymentName() . '_privatekey'));
                    $transactionObject = new Services_Paymill_Transactions($privateKey, 'https://api.paymill.com/v2/');
                    $result = $transactionObject->getOne($id);
                    return $result['id'] === $id;
                }
            }
        }
        return false;
    }

    private function getOrderIdFromNotification($transactionDescription)
    {
        $regexPattern = '/OrderID:(\d+)/ix';
        $matches = array();
        if (preg_match($regexPattern, $transactionDescription, $matches)) {
            return $matches[1];
        }
        return false;
    }

}
