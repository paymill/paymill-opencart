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
        if (isset($this->request->post[$name])) {
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

        //Get Post Vars
        $connectedSearch = $this->getPost("connectedSearch", "off");
        $searchValue = $this->getPost("searchValue", "");
        $actualPage = (int) $this->getPost("page", 0);
        $selectedIds = $this->getPost("selected");

        if($actualPage <= 0){
            $actualPage = 1;
    }

        if ($this->getPost("button", "search") === "delete" && is_array($selectedIds)) {
            $this->model_custom_paymillLogging->deleteEntries($selectedIds);
}


        $this->model_custom_paymillLogging->setSearchValue($searchValue);
        $this->model_custom_paymillLogging->setConnectedSearch($connectedSearch);
        $this->data['paymillEntries'] = $this->model_custom_paymillLogging->getEntries($actualPage);
        $this->data['paymillInputSearch'] = $searchValue;
        $this->data['paymillCheckboxConnectedSearch'] = $connectedSearch;

        $maxPages = (int)floor($this->model_custom_paymillLogging->getTotal() / $this->model_custom_paymillLogging->getPageSize());

        $this->data['paymillPage'] = $actualPage;
        $this->data['paymillPageMax'] = $maxPages;

        $this->baseUrl = preg_replace("/\/index\.php/", "", $this->request->server['SCRIPT_NAME']);
        $this->data['breadcrumbs'] = $this->_getBreadcrumbs();
        $this->data['paymillCSS'] = $this->baseUrl . '/../catalog/view/theme/default/stylesheet/paymill_styles.css';
        $this->data['paymillJS'] = $this->baseUrl . '/../catalog/view/javascript/paymill/loggingOverview.js';
        $this->data['paymillAction'] = $this->url->link('custom/paymillLogging', 'token=' . $this->session->data['token'], 'SSL');

        $this->_loadTranslation();
        $this->response->setOutput($this->render());
    }

    protected function _getBreadcrumbs()
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

    private function _loadTranslation()
    {
        $this->document->setTitle($this->language->get('headingTitle'));
        $this->data['button_delete'] = $this->language->get('button_delete');
        $this->data['button_search'] = $this->language->get('button_search');
        $this->data['headingTitle'] = $this->language->get('headingTitle');
        $this->data['paymillTableHeadDate'] = $this->language->get('paymillTableHeadDate');
        $this->data['paymillTableHeadID'] = $this->language->get('paymillTableHeadID');
        $this->data['paymillTableHeadMessage'] = $this->language->get('paymillTableHeadMessage');
        $this->data['paymillTableHeadDebug'] = $this->language->get('paymillTableHeadDebug');
        $this->data['paymillTableHeadDetail'] = $this->language->get('paymillTableHeadDetail');
        $this->data['paymillTableShowDetails'] = $this->language->get('paymillTableShowDetails');
        $this->data['paymillCheckboxConnectedSearch'] = $this->language->get('paymillCheckboxConnectedSearch');
    }

}
