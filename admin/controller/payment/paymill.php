<?php

/**
 * paymill
 *
 * @category   PayIntelligent
 * @package    Expression package is undefined on line 6, column 18 in Templates/Scripting/PHPClass.php.
 * @copyright  Copyright (c) 2011 PayIntelligent GmbH (http://payintelligent.de)
 */
class ControllerPaymentPaymill extends Controller
{

    public function index()
    {
        global $config;
        $this->language->load('payment/paymill');
        $this->document->setTitle($this->language->get('heading_title'));

        if (($this->request->server['REQUEST_METHOD'] == 'POST') && $this->validate()) {
            $this->load->model('setting/setting');
            $this->model_setting_setting->editSetting('paymill', $this->request->post);
            $this->session->data['success'] = $this->language->get('text_success');
            $this->redirect(HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token']);
        }

        $this->data['breadcrumbs'] = $this->getBreadcrumbs();
        $this->data['heading_title'] = $this->language->get('heading_title');

        $this->data['text_enabled'] = $this->language->get('text_enabled');
        $this->data['text_disabled'] = $this->language->get('text_disabled');
        $this->data['text_payment'] = $this->language->get('text_payment');
        $this->data['text_success'] = $this->language->get('text_success');
        $this->data['text_paymill'] = $this->language->get('text_paymill');
        $this->data['text_sale'] = $this->language->get('text_sale');

        $this->data['entry_status'] = $this->language->get('entry_status');
        $this->data['entry_sandbox'] = $this->language->get('entry_sandbox');
        $this->data['entry_publickey'] = $this->language->get('entry_publickey');
        $this->data['entry_privatekey'] = $this->language->get('entry_privatekey');
        $this->data['entry_apiurl'] = $this->language->get('entry_apiurl');
        $this->data['entry_bridgeurl'] = $this->language->get('entry_bridgeurl');
        $this->data['entry_sort_order'] = $this->language->get('entry_sort_order');
        $this->data['entry_logging'] = $this->language->get('entry_logging');
        $this->data['entry_debugging'] = $this->language->get('entry_debugging');

        $this->data['button_save'] = $this->language->get('button_save');
        $this->data['button_cancel'] = $this->language->get('button_cancel');

        $this->data['action'] = HTTPS_SERVER . 'index.php?route=payment/paymill&token=' . $this->session->data['token'];
        $this->data['cancel'] = HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'];

        $this->data['paymill_status'] = $this->getConfigValue('paymill_status');
        $this->data['paymill_sandbox'] = $this->getConfigValue('paymill_sandbox');
        $this->data['paymill_publickey'] = $this->getConfigValue('paymill_publickey');
        $this->data['paymill_privatekey'] = $this->getConfigValue('paymill_privatekey');
        $this->data['paymill_apiurl'] = $this->getConfigValue('paymill_apiurl');
        $this->data['paymill_bridgeurl'] = $this->getConfigValue('paymill_bridgeurl');
        $this->data['paymill_sort_order'] = $this->getConfigValue('paymill_sort_order');
        $this->data['paymill_logging'] = $this->getConfigValue('paymill_logging');
        $this->data['paymill_debugging'] = $this->getConfigValue('paymill_debugging');

        $this->template = 'payment/paymill.tpl';
        $this->children = array(
            'common/header',
            'common/footer'
        );
        $this->response->setOutput($this->render(true), $this->config->get('config_compression'));
    }

    private function getBreadcrumbs()
    {
        $breadcrumbs = array();
        $breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'index.php?route=common/home&token=' . $this->session->data['token'],
            'text' => $this->language->get('text_home'),
            'separator' => FALSE
        );

        $breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'index.php?route=extension/payment&token=' . $this->session->data['token'],
            'text' => $this->language->get('text_payment'),
            'separator' => ' :: '
        );

        $breadcrumbs[] = array(
            'href' => HTTPS_SERVER . 'index.php?route=payment/clickandbuy&token=' . $this->session->data['token'],
            'text' => $this->language->get('heading_title'),
            'separator' => ' :: '
        );
        return $breadcrumbs;
    }

    private function getConfigValue($configField)
    {
        if (isset($this->request->post[$configField])) {
            return $this->request->post[$configField];
        } else {
            return $this->config->get($configField);
        }
    }

    private function validate()
    {

        if (!$this->user->hasPermission('modify', 'payment/paymill')) {
            $this->error['warning'] = $this->language->get('error_permission');
        }

        if (!$this->error) {
            return true;
        } else {
            return false;
        }
    }

    public function install()
    {
        $config['paymill_status'] = '0';
        $config['paymill_sandbox'] = '1';
        $config['paymill_publickey'] = '';
        $config['paymill_privatekey'] = '';
        $config['paymill_apiurl'] = 'https://api.paymill.de/v2/';
        $config['paymill_bridgeurl'] = 'https://bridge.paymill.de/';
        $config['paymill_sort_order'] = '1';
        $config['paymill_logging'] = '1';
        $config['paymill_debugging'] = '1';

        $this->load->model('setting/setting');
        $this->model_setting_setting->editSetting('paymill', $config);
    }

    public function uninstall()
    {

    }

}
