<?php
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/paymill/catalog/controller/paymill.php';

class ControllerPaymentPaymilldirectdebit extends ControllerPaymentPaymill
{
    protected function getPaymentName()
    {
        return 'paymilldirectdebit';
    }
}
