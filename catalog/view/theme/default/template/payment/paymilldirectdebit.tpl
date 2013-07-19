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
<div class="right">
        <form id='paymill_form' action="index.php?route=payment/paymilldirectdebit/confirm" method="POST">
            <div class="error" style="display: none">
                <ul id="errors"></ul>
            </div>
            <div class="debit">
                <p class="none">
                    <label><?php echo $paymill_accountholder;?></label>
                    <input id="paymill_accountholder" type="text" size="20" class="paymill_text" />
                </p>
                <p class="none">
                    <label><?php echo $paymill_accountnumber;?></label>
                    <input id="paymill_accountnumber" type="text" size="20" class="paymill_text" />
                </p>
                <p class="none">
                    <label><?php echo $paymill_banknumber;?></label>
                    <input id="paymill_banknumber" type="text" size="20" class="paymill_text" />
                </p>
                <p class="description"><?php echo $paymill_description;?></p>
                <p>
                    <div class="paymill_powered">
                        <div class="paymill_credits"><?php echo $paymill_paymilllabel_elv;?> powered by <a href="http://www.paymill.de" target="_blank">PAYMILL</a></div>
                    </div>
                </p>
            </div>
            <div class="buttons">
                <a class="button" id="paymillDirectdebitSubmit">
                    <span><?php echo $button_confirm; ?></span>
                </a>
            </div>
        </form>
    </div>
<script type="text/javascript">
  var PAYMILL_PUBLIC_KEY = "<?php echo $paymill_publickey; ?>";
</script>
<script type="text/javascript" src="https://bridge.paymill.com/"></script>
<script type="text/javascript">
    function validateELV() {
        debugELV("Paymill handler triggered");
        var errors = $("#errors");
        errors.parent().hide();
        errors.html("");
        var result = true;
        if (!$('#paymill_accountholder').val()) {
          errors.append("<li>Bitte geben Sie den Kontoinhaber an.</li>");
          result = false;
        }
        if (!paymill.validateAccountNumber($('#paymill_accountnumber').val())) {
          errors.append("<li>Bitte geben Sie eine g&uuml;ltige Kontonummer ein.</li>");
          result = false;
        }
        if (!paymill.validateBankCode($('#paymill_banknumber').val())) {
          errors.append("<li>Bitte geben Sie eine g&uuml;ltige BLZ ein.</li>");
          result = false;
        }
        if (!result) {
            errors.parent().show();
        }else{
            debugELV("Validations successful");
        }
        return result;
    }
    $(document).ready(function() {
        $("#paymillDirectdebitSubmit").click(function(event) {
                if (validateELV()) {
                    try {
                        paymill.createToken({
                            number: $('#paymill_accountnumber').val(),
                            bank: $('#paymill_banknumber').val(),
                            accountholder: $('#paymill_accountholder').val()
                        }, PaymillResponseHandler);
                    } catch (e) {
                        alert("Ein Fehler ist aufgetreten: " + e);
                    }
                }else{
                    $('html, body').animate({
                        scrollTop: $("#errors").offset().top - 100
                    }, 1000);
                }
                return false;
        });
    });
    function PaymillResponseHandler(error, result) {
        debugELV("Started Paymill response handler");
        if (error) {
            debugELV("API returned error:" + error.apierror);
            alert("API returned error:" + error.apierror);
        } else {
            debugELV("Received token from Paymill API: " + result.token);
            var form = $("#paymill_form");
            var token = result.token;
            form.append("<input type='hidden' name='paymillToken' value='" + token + "'/>");
            form.get(0).submit();
        }
    }
    function debugELV(message){
        <?php if($paymill_debugging){?>
            console.log("[PaymillELV] " + message);
        <?php }?>
    }
</script>