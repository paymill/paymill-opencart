<?php
require_once dirname(dirname(dirname(dirname(__FILE__)))).'/paymill/admin/controller/paymill.php';

class ControllerPaymentPaymilldirectdebit extends ControllerPaymentPaymill
{
    protected function getPaymentName()
    {
        return 'paymilldirectdebit';
    }

    public function install()
    {
        $this->db->query("CREATE TABLE IF NOT EXISTS `paymill_dd_userdata` (
            `userId` int(11) NOT NULL,
            `clientId` text NOT NULL,
            `paymentId` text NOT NULL,
            PRIMARY KEY (`userId`)
        )");
        parent::install();
    }

}