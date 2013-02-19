<?php
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/paymill/catalog/model/paymill.php';

class ModelPaymentPaymillcreditcard extends ModelPaymentPaymill
{

    protected function getPaymentName()
    {
        return 'paymillcreditcard';
    }

}
