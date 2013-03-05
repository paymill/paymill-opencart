<?php
/**
* paymill
*
* @category   PayIntelligent
* @copyright  Copyright (c) 2011 PayIntelligent GmbH (http://payintelligent.de)
*/
?>
<link rel="stylesheet" type="text/css" href="<?php echo $paymill_css; ?>" />
<?php if (isset($error)) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>

<div class="center">
    <form id='paymill_form' action="index.php?route=payment/<?php echo $paymill_paymentname;?>/confirm" method="POST">
        <input type="hidden" name="paymillToken" value="DummyToken">
        <a class="button" onclick="document.getElementById('paymill_form').submit();">
            <span><?php echo $button_confirm; ?></span>
        </a>
    </form>
</div>
