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
        <form id='paymill_form' action="index.php?route=payment/paymillcreditcard/confirm" method="POST">
            <div class="error" style="display: none">
                <ul id="errors"></ul>
            </div>
            <div class="debit">
                <p>
                    <img src="catalog/view/theme/default/image/payment/paymill_icon_mastercard.png" />
                    <img src="catalog/view/theme/default/image/payment/paymill_icon_visa.png" />
                </p>
                <p class="none">
                    <label><?php echo $paymill_cardholder;?></label>
                    <input id="account-holder" type="text" size="20" class="paymill_text" value="<?php echo $paymill_fullname;?>"/>
                </p>
                <p class="none">
                    <label><?php echo $paymill_cardnumber;?></label>
                    <input id="card-number" type="text" size="20" class="paymill_text" />
                </p>
                <p class="none">
                    <label><?php echo $paymill_cvc;?></label>
                    <input id="card-cvc" type="text" size="4" class="paymill_text" />
                </p>
                <p class="none">
                    <label><?php echo $paymill_birthday;?></label>
                    <input id="card-expiry-year" type="text" style="width: 60px; display: inline-block;" class="paymill_text" />
                    <input id="card-expiry-month" type="text" style="width: 30px; display: inline-block;" class="paymill_text" />
                </p>
                <p class="description"><?php echo $paymill_description;?></p>
                <p><div class="paymill_powered"><div class="paymill_credits"><?php echo $paymill_paymilllabel;?> powered by <a href="http://www.paymill.de" target="_blank">Paymill</a></div></div></p>
            </div>
            <div class="buttons">
                <a class="button" id="paymillCreditcardSubmit">
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
    function validateCC() {
        debugCC("Paymill handler triggered");
        var errors = $("#errors");
        errors.parent().hide();
        errors.html("");
        var result = true;
        if (!paymill.validateCardNumber($('#card-number').val())) {
          errors.append("<li>Bitte geben Sie eine gültige Kartennummer ein</li>");
          result = false;
        }
        if (!paymill. validateCvc($('#card-cvc').val())) {
          errors.append("<li>Bitte geben sie einen gültigen Sicherheitscode ein (Rückseite der Karte).</li>");
          result = false;
        }
        if (!paymill.validateExpiry($('#card-expiry-month').val(), $('#card-expiry-year').val())) {
          errors.append("<li>Das Ablaufdatum der Karte ist ungültig.</li>");
          result = false;
        }
        if (!result) {
            errors.parent().show();
        }else{
            debugCC("Validations successful");
        }
        return result;
    }
    $(document).ready(function() {
            debugCC('<?php echo ($paymill_amount + $paymill_tolerance) * 100;?>');
            $("#paymillCreditcardSubmit").click(function(event) {
                if (validateCC()) {
                    try {
                        paymill.createToken({
                            number: $('#card-number').val(),
                            cardholder: $('#account-holder').val(),
                            exp_month: $('#card-expiry-month').val(),
                            exp_year: $('#card-expiry-year').val(),
                            cvc: $('#card-cvc').val(),
                            amount_int: <?php echo ($paymill_amount + $paymill_tolerance) * 100;?>,
                            currency: '<?php echo $paymill_currency;?>'
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
        debugCC("Started Paymill response handler");
        if (error) {
            debugCC("API returned error:" + error.apierror);
            alert("API returned error:" + error.apierror);
        } else {
            debugCC("Received token from Paymill API: " + result.token);
            var form = $("#paymill_form");
            var token = result.token;
            form.append("<input type='hidden' name='paymillToken' value='" + token + "'/>");
            form.get(0).submit();
        }
    }
    function debugCC(message){
        <?php if($paymill_debugging){?>
            console.log("[PaymillCC] " + message);
        <?php }?>
    }
</script>