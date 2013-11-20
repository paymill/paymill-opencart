/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var prefilled = new Array();
$(document).ready(function() {
    prefilled = getFormData(prefilled, true);
    $('#paymill_card_number').keyup(function() {
        var brand = paymill.cardType($('#paymill_card_number').val());
        brand = brand.toLowerCase();
        $("#paymill_card_number")[0].className = $("#paymill_card_number")[0].className.replace(/paymill-card-number-.*/g, '');
        if (brand !== 'unknown') {
            if (brand === 'american express') {
                brand = 'amex';
            }
            $('#paymill_card_number').addClass("paymill-card-number-" + brand);
        }
    });

    $("#paymill_form").submit(function(e) {
        if (!$("input[name=paymillToken]")) {
            e.preventDefault();
        }

        $("#paymill_submit").attr('disabled', true);
        var formdata = new Array();
        formdata = getFormData(formdata, false);

        if (prefilled.toString() === formdata.toString()) {
            $("#paymill_form").append("<input type='hidden' name='paymillFastcheckout' value='" + true + "'/>");
            result = new Object();
            result.token = 'dummyToken';
            PaymillResponseHandler(null, result);
        } else {
            if (validate()) {
                $("#paymill_form").append("<input type='hidden' name='paymillFastcheckout' value='" + false + "'/>");
                try {
                    var params;
                    if (PAYMILL_PAYMENT === "paymillcreditcard") {
                        params = {
                            number: $('#paymill_card_number').val(),
                            cardholder: $('#paymill_card_holder').val(),
                            exp_month: $('#paymill_card_expiry_month').val(),
                            exp_year: $('#paymill_card_expiry_year').val(),
                            cvc: $('#paymill_card_cvc').val(),
                            amount_int: PAYMILL_AMOUNT,
                            currency: PAYMILL_CURRENCY
                        };
                    } else if (PAYMILL_PAYMENT === "paymilldirectdebit") {
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
            }
        }
        return false;
    });
});


function getFormData(array, ignoreEmptyValues) {
    $('#paymill_form :input').not(':[type=hidden]').each(function() {
        if ($(this).val() === "" && ignoreEmptyValues) {
            return;
        }
        array.push($(this).val());
    });
    return array;
}

function validate() {
    debug("Paymill handler triggered");
    $(".field-error").removeClass('field-error').animate(300);
    var result = true;
    var field = new Array();
    var message = '';
    if (PAYMILL_PAYMENT === "paymillcreditcard") {
        if (paymill.cardType($('#paymill_card_number').val()).toLowerCase() === 'maestro' && (!$('#paymill_card_cvc').val() || $('#paymill_card_cvc').val() === "000")) {
            $('#paymill_card_cvc').val('000');
        } else if (!paymill.validateCvc($('#paymill_card_cvc').val())) {
            field.push($('#paymill_card_cvc'));
            message = PAYMILL_TRANSLATION.paymill_card_cvc;
            result = false;
        }
        if (!paymill.validateHolder($('#paymill_card_holder').val())) {
            field.push($('#paymill_card_holder'));
            message = PAYMILL_TRANSLATION.paymill_card_holder;
            result = false;
        }
        if (!paymill.validateExpiry($('#paymill_card_expiry_month').val(), $('#paymill_card_expiry_year').val())) {
            field.push($('#paymill_card_expiry_month'));
            message = PAYMILL_TRANSLATION.paymill_card_expiry_date;
            result = false;
        }
        if (!paymill.validateCardNumber($('#paymill_card_number').val())) {
            field.push($('#paymill_card_number'));
            message = PAYMILL_TRANSLATION.paymill_card_number;
            result = false;
        }
    } else if (PAYMILL_PAYMENT === "paymilldirectdebit") {
        if (!paymill.validateHolder($('#paymill_accountholder').val())) {
            field.push($('#paymill_accountholder'));
            message = PAYMILL_TRANSLATION.paymill_accountholder;
            result = false;
        }
        if (!paymill.validateBankCode($('#paymill_banknumber').val())) {
            field.push($('#paymill_banknumber'));
            message = PAYMILL_TRANSLATION.paymill_banknumber;
            result = false;
        }
        if (!paymill.validateAccountNumber($('#paymill_accountnumber').val())) {
            field.push($('#paymill_accountnumber'));
            message = PAYMILL_TRANSLATION.paymill_accountnumber;
            result = false;
        }
    }
    if (!result) {
        for (var i = 0; i < field.length; i++) {
            field[i].addClass('field-error');
        }
        $("#paymill_submit").removeAttr('disabled');
        $(".paymill_error").html(message);
        $(".paymill_error").show(500);
    } else {
        $(".paymill_error").hide(800);
        debug("Validations successful");
    }
    return result;
}

function PaymillResponseHandler(error, result) {
    debug("Started Paymill response handler");
    if (error) {
        $("#paymill_submit").removeAttr('disabled');
        debug("API returned error:" + error.apierror);
        alert("API returned error:" + error.apierror);
        $(".checkout-heading").children('a :last').click(); //click on step5 Modify
    } else {
        debug("Received token from Paymill API: " + result.token);
        var form = $("#paymill_form");
        var token = result.token;
        form.append("<input type='hidden' name='paymillToken' value='" + token + "'/>");
        form.get(0).submit();
    }
}

function debug(message) {
    if (PAYMILL_DEBUG) {
        if (PAYMILL_PAYMENT === "paymillcreditcard") {
            console.log("[PaymillCC] " + message);
        } else if (PAYMILL_PAYMENT === "paymilldirectdebit") {
            console.log("[PaymillELV] " + message);
        }
    }
}