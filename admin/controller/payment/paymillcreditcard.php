<?php
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/paymill/admin/controller/paymill.php';

class ControllerPaymentPaymillcreditcard extends ControllerPaymentPaymill
{
    protected function getPaymentName()
    {
        return 'paymillcreditcard';
    }
}
