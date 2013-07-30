/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
$(document).ready(function() {
    $('#paymill_card_number').keyup(function() {
        var brand = paymill.cardType($('#paymill_card_number').val());
        brand = brand.toLowerCase();
        switch(brand){
            case 'visa':
                $('#paymill_card_icon').html('<img src="'+ PAYMILL_IMAGE +'/32x20_visa.png" >');
                $('#paymill_card_icon').show();
                break;
            case 'mastercard':
                $('#paymill_card_icon').html('<img src="'+ PAYMILL_IMAGE +'/32x20_mastercard.png" >');
                $('#paymill_card_icon').show();
                break;
            case 'american express':
                $('#paymill_card_icon').html('<img src="'+ PAYMILL_IMAGE +'/32x20_amex.png" >');
                $('#paymill_card_icon').show();
                break;
            case 'jcb':
                $('#paymill_card_icon').html('<img src="'+ PAYMILL_IMAGE +'/32x20_jcb.png" >');
                $('#paymill_card_icon').show();
                break;
            case 'maestro':
                $('#paymill_card_icon').html('<img src="'+ PAYMILL_IMAGE +'/32x20_maestro.png" >');
                $('#paymill_card_icon').show();
                break;
            case 'diners club':
                $('#paymill_card_icon').html('<img src="'+ PAYMILL_IMAGE +'/32x20_dinersclub.png" >');
                $('#paymill_card_icon').show();
                break;
            case 'discover':
                $('#paymill_card_icon').html('<img src="'+ PAYMILL_IMAGE +'/32x20_discover.png" >');
                $('#paymill_card_icon').show();
                break;
            case 'unionpay':
                $('#paymill_card_icon').html('<img src="'+ PAYMILL_IMAGE +'/32x20_unionpay.png" >');
                $('#paymill_card_icon').show();
                break;
            case 'unknown':
            default:
                $('#paymill_card_icon').hide();
                break;
        }
        $('#paymill_card_icon').css('position','absolute');
        $('#paymill_card_icon :first-child').css({
            'position':'relative',
            'left':'195px',
            'top':'3px'
        });
    });

    $("#paymill_submit").click(function(event) {
        if(PAYMILL_FASTCHECKOUT != 1){
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
        }else{
            result = new Object();
            result.token = 'dummyToken';
            PaymillResponseHandler(null,result);
        }
    });

    $('#paymill_form :input').focus(function(event){
        if($(this).attr('id') == 'paymill_card_number'){
            $('#paymill_card_cvc').val('');
        }
        $(this).val('');
        PAYMILL_FASTCHECKOUT = 0;
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
        form.append("<input type='hidden' name='paymillFastcheckout' value='" + PAYMILL_FASTCHECKOUT + "'/>");
        form.get(0).submit();
    }
}

function debug(message){
    if(PAYMILL_DEBUG){
        if(PAYMILL_PAYMENT == "paymillcreditcard"){
            console.log("[PaymillCC] " + message);
        }else if(PAYMILL_PAYMENT == "paymilldirectdebit"){
            console.log("[PaymillELV] " + message);
        }
    }
}