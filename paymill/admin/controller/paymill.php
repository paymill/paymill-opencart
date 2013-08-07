<?php

require_once dirname(dirname(dirname(__FILE__))) . '/metadata.php';

/**
 * paymill
 *
 * @category   PayIntelligent
 * @copyright  Copyright (c) 2011 PayIntelligent GmbH (http://payintelligent.de)
 */
abstract class ControllerPaymentPaymill extends Controller
{

    protected $_version = "1.0.4";

    abstract protected function getPaymentName();

    public function getVersion()
    {
        $metadata = new metadata();
        return $metadata->getVersion();
    }

    public function index()
    {
        global $config;
        $this->language->load('payment/' . $this->getPaymentName());
        $this->document->setTitle($this->language->get('heading_title') . " (" . $this->getVersion() . ")");

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->load->model('setting/setting');

            $newConfig[$this->getPaymentName() . '_status'] = $this->request->post['paymill_status'];
            $newConfig[$this->getPaymentName() . '_publickey'] = trim($this->request->post['paymill_publickey']);
            $newConfig[$this->getPaymentName() . '_privatekey'] = trim($this->request->post['paymill_privatekey']);
            $newConfig[$this->getPaymentName() . '_sort_order'] = $this->request->post['paymill_sort_order'];
            $newConfig[$this->getPaymentName() . '_fast_checkout'] = $this->request->post['paymill_fast_checkout'];
            $newConfig[$this->getPaymentName() . '_logging'] = $this->request->post['paymill_logging'];
            $newConfig[$this->getPaymentName() . '_debugging'] = $this->request->post['paymill_debugging'];

            if ($this->getPaymentName() === "paymillcreditcard") {
                $newConfig[$this->getPaymentName() . '_different_amount'] = number_format($this->request->post['paymill_differnet_amount'], 2, '.', '');
            }else{
                $newConfig[$this->getPaymentName() . '_different_amount'] = number_format("0.00", 2, '.', '');
            }

            $this->model_setting_setting->editSetting($this->getPaymentName(), $newConfig);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->redirect($this->url->link('extension/payment', '&token=' . $this->session->data['token']));
        }

        $this->data['breadcrumbs'] = $this->getBreadcrumbs();
        $this->data['heading_title'] = $this->language->get('heading_title') . " (" . $this->getVersion() . ")";

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_payment'] = $this->language->get('text_payment');
        $this->data['text_success'] = $this->language->get('text_success');
        $this->data['text_paymill'] = $this->language->get('text_paymill');
        $this->data['text_sale'] = $this->language->get('text_sale');

        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_publickey'] = $this->language->get('entry_publickey');
        $this->data['entry_privatekey'] = $this->language->get('entry_privatekey');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $this->data['entry_fast_checkout'] = $this->language->get('entry_fast_checkout');
        $this->data['entry_logging'] = $this->language->get('entry_logging');
        $this->data['entry_debugging'] = $this->language->get('entry_debugging');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

        $this->data['action'] = $this->url->link('payment/' . $this->getPaymentName(), '&token=' . $this->session->data['token']);
        $this->data['cancel'] = $this->url->link('extension/payment', '&token=' . $this->session->data['token']);

        $this->data['paymill_status'] = $this->getConfigValue($this->getPaymentName() . '_status');
        $this->data['paymill_publickey'] = $this->getConfigValue($this->getPaymentName() . '_publickey');
        $this->data['paymill_privatekey'] = $this->getConfigValue($this->getPaymentName() . '_privatekey');
        $this->data['paymill_sort_order'] = $this->getConfigValue($this->getPaymentName() . '_sort_order');
        $this->data['paymill_fast_checkout'] = $this->getConfigValue($this->getPaymentName() . '_fast_checkout');
        $this->data['paymill_logging'] = $this->getConfigValue($this->getPaymentName() . '_logging');
        $this->data['paymill_debugging'] = $this->getConfigValue($this->getPaymentName() . '_debugging');
        $this->data['paymill_logfile'] = file_get_contents(dirname(dirname(dirname(__FILE__))) . '/log/log.txt');
        $this->data['paymill_payment'] = $this->getPaymentName();

        $this->data['paymill_different_amount'] = $this->getConfigValue($this->getPaymentName() . '_different_amount');
        if ($this->getPaymentName() === "paymillcreditcard") {
            $this->data['entry_different_amount'] = $this->language->get('entry_different_amount');
            $this->data['paymill_different_amount'] = $this->getConfigValue($this->getPaymentName() . '_different_amount');
        }

        $this->template = 'payment/' . $this->getPaymentName() . '.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
        $this->response->setOutput($this->render(true), $this->config->get('config_compression'));
    }

    protected function getBreadcrumbs()
    {
        $breadcrumbs = array();
        $breadcrumbs[] = array(
            'href' => $this->url->link('common/home', '&token=' . $this->session->data['token']),
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $breadcrumbs[] = array(
            'href' => $this->url->link('extension/payment', '&token=' . $this->session->data['token']),
            'text' => $this->language->get('text_payment'),
            'separator' => ' :: '
        );

        $breadcrumbs[] = array(
            'href' => $this->url->link('payment/' . $this->getPaymentName(), '&token=' . $this->session->data['token']),
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );
        return $breadcrumbs;
    }

    protected function getConfigValue($configField)
    {
        if (isset($this->request->post[$configField])) {
            return $this->request->post[$configField];
        } else {
            return $this->config->get($configField);
        }
    }

    protected function validate()
    {
        $error = false;
        if (!$this->user->hasPermission('modify', 'payment/' . $this->getPaymentName())) {
            $this->data['error_warning'] = $this->language->get('error_permission');
            $error = true;
        }

        if (isset($this->request->post['paymill_differnet_amount'])) {
            if (!is_numeric($this->request->post['paymill_differnet_amount'])) {
                $this->data['error_warning'] = $this->language->get('error_different_amount');
                $error = true;
            }
        }
        return !$error;
    }

    public function install()
    {
        $config[$this->getPaymentName() . '_status'] = '0';
        $config[$this->getPaymentName() . '_publickey'] = '';
        $config[$this->getPaymentName() . '_privatekey'] = '';
        $config[$this->getPaymentName() . '_sort_order'] = '1';
        $config[$this->getPaymentName() . '_fast_checkout'] = '0';
        $config[$this->getPaymentName() . '_different_amount'] = '0.00';
        $config[$this->getPaymentName() . '_logging'] = '1';
        $config[$this->getPaymentName() . '_debugging'] = '1';

        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting($this->getPaymentName(), $config);
    }

    public function uninstall()
    {

    }

}
