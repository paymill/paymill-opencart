/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function() {
    console.log('TEST');
    $("#paymill_submit").click(function(event) {
        if (validate()) {
            try {
                var params;
                if(PAYMILL_PAYMENT == "paymillcreditcard"){
                    params = {
                        number: $('#paymill_card_number').val(),
                        cardholder: $('#paymill_account_holder').val(),
                        exp_month: $('#paymill_card_expiry_month').val(),
                        exp_year: $('#paymill_card_expiry_year').val(),
                        cvc: $('#paymill_card_cvc').val(),
                        amount_int: PAYMILL_AMOUNT,
                        currency: PAYMILL_CURRENCY
                    };
                }else if(PAYMILL_PAYMENT == "paymilldirectdebit"){
                    params = {
                        number: $('#paymill_accountnumber').val(),
                        bank: $('#paymill_banknumber').val(),
                        accountholder: $('#paymill_accountholder').val()
                    };
                }
                paymill.createToken(params, PaymillResponseHandler);
            } catch (e) {
                alert("Ein Fehler ist aufgetreten: " + e);
            }
        }else{
            $('html, body').animate({
                scrollTop: $("#paymill_errors").offset().top - 100
            }, 1000);
        }
        return false;
    });
});

function validate() {
    debug("Paymill handler triggered");
    var errors = $("#paymill_errors");
    errors.parent().hide();
    errors.html("");
    var result = true;
    if(PAYMILL_PAYMENT == "paymillcreditcard"){
        if (!paymill.validateCardNumber($('#paymill_card_number').val())) {
            errors.append("<li>Bitte geben Sie eine gültige Kartennummer ein</li>");
            result = false;
        }
        if (!paymill. validateCvc($('#paymill_card_cvc').val())) {
            errors.append("<li>Bitte geben sie einen gültigen Sicherheitscode ein (Rückseite der Karte).</li>");
            result = false;
        }
        if (!paymill.validateExpiry($('#paymill_card_expiry_month').val(), $('#paymill_card_expiry_year').val())) {
            errors.append("<li>Das Ablaufdatum der Karte ist ungültig.</li>");
            result = false;
        }
    }else if(PAYMILL_PAYMENT == "paymilldirectdebit"){
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
    }
    if (!result) {
        errors.parent().show();
    }else{
        debug("Validations successful");
    }
    return result;
}

function PaymillResponseHandler(error, result) {
    debug("Started Paymill response handler");
    if (error) {
        debug("API returned error:" + error.apierror);
        alert("API returned error:" + error.apierror);
    } else {
        debug("Received token from Paymill API: " + result.token);
        var form = $("#paymill_form");
        var token = result.token;
        form.append("<input type='hidden' name='paymillToken' value='" + token + "'/>");
        form.get(0).submit();
    }
}

function debug(message){
    console.log(PAYMILL_DEBUG);
    if(PAYMILL_DEBUG){
        if(PAYMILL_PAYMENT == "paymillcreditcard"){
            console.log("[PaymillCC] " + message);
        }else if(PAYMILL_PAYMENT == "paymilldirectdebit"){
            console.log("[PaymillELV] " + message);
        }
    }
}