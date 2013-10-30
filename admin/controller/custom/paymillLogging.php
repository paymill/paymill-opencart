<?php

/**
 * paymillLogging
 *
 * @category   PayIntelligent
 * @package    Expression package is undefined on line 6, column 18 in Templates/Scripting/PHPClass.php.
 * @copyright  Copyright (c) 2011 PayIntelligent GmbH (http://payintelligent.de)
 */
class ControllercustompaymillLogging extends Controller
{

    private function getPost($name, $default = null)
    {
        $value = $default;
        if(isset($this->request->post[$name])){
            $value = $this->request->post[$name];
        }
        return $value;
    }

    public function index()
    {
        $this->load->model('custom/paymillLogging');
        $this->template = 'custom/paymillLogging.tpl';
        $this->language->load('custom/paymillLogging');
        $this->children = array(
            'common/header',
            'common/footer'
        );

        $connectedSearch = $this->getPost("connectedSearch", "off");
        $page = 0;


        $this->data['paymillDebug'][] = $this->getPost("button", "search");
        $this->data['paymillCheckboxConnectedSearch'] = $connectedSearch;
        $searchValue = $this->getPost("searchValue", "");

        $this->document->setTitle($this->language->get('headingTitle'));
        $this->baseUrl = preg_replace("/\/index\.php/", "", $this->request->server['SCRIPT_NAME']);

        $this->data['breadcrumbs'] = $this->getBreadcrumbs();
        $this->data['button_delete'] = $this->language->get('button_delete');
        $this->data['button_search'] = $this->language->get('button_search');
        $this->data['headingTitle'] = $this->language->get('headingTitle');
        $this->data['paymillTotal'] = $this->model_custom_paymillLogging->getTotal();
        $this->data['paymillEntries'] = $this->model_custom_paymillLogging->getEntries($page, $searchValue , $connectedSearch);
        $this->data['paymillCSS'] = $this->baseUrl . '/../catalog/view/theme/default/stylesheet/paymill_styles.css';
        $this->data['paymillJS'] = $this->baseUrl . '/../catalog/view/javascript/paymill/loggingOverview.js';

        $this->data['paymillAction'] = $this->url->link('custom/paymillLogging', 'token=' . $this->session->data['token'], 'SSL');

        $this->response->setOutput($this->render());
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
            'href' => $this->url->link('custom/paymillLogging', '&token=' . $this->session->data['token']),
            'text' => $this->language->get('headingTitle'),
            'separator' => ' :: '
        );
        return $breadcrumbs;
    }

}