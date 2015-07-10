<?php

/**
 * @copyright  Copyright (c) 2015 PAYMILL GmbH (http://www.paymill.com)
 */
abstract class ModelPaymentPaymill extends Model
{

    abstract protected function getPaymentName();

    public function getMethod()
    {
        $this->load->language('payment/' . $this->getPaymentName());
        $method_data = array();
        $publicKey = $this->config->get($this->getPaymentName() . '_publickey');
        $privateKey = $this->config->get($this->getPaymentName() . '_privatekey');
        
        if ($this->config->get($this->getPaymentName() . '_status') && !(empty($publicKey) || empty($privateKey))) {
            $method_data = array(
                'code' => $this->getPaymentName(),
                'title' => $this->language->get('text_payment_' . $this->getPaymentName() . '_title'),
                'title_detail' => $this->language->get('text_payment_' . $this->getPaymentName() . '_detail'),
                'sort_order' => $this->config->get($this->getPaymentName() . '_sort_order')
            );
        }

        return $method_data;
    }

}
