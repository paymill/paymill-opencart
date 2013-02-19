<?php
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/paymill/admin/controller/paymill.php';

class ControllerPaymentPaymilldirectdebit extends ControllerPaymentPaymill
{
    protected function getPaymentName()
    {
        return 'paymilldirectdebit';
    }
}
