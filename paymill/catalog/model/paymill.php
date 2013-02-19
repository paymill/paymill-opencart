<?php

/**
 * paymill
 *
 * @category   PayIntelligent
 * @copyright  Copyright (c) 2011 PayIntelligent GmbH (http://payintelligent.de)
 */
abstract class ModelPaymentPaymill extends Model
{

    abstract protected function getPaymentName();

    public function getMethod()
    {
        $this->load->language('payment/' . $this->getPaymentName());
        $method_data = array();
        if ($this->config->get($this->getPaymentName() . '_status')) {
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
