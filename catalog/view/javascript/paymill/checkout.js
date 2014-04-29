/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
var prefilled = new Array();
$(document).ready(function() {
    prefilled = getFormData(prefilled, true);
    var SepaObj = new Sepa('dummySEPA');
    $('#paymill_card_number').keyup(function() {
        $("#paymill_card_number")[0].className = $("#paymill_card_number")[0].className.replace(/paymill-card-number-.*/g, '');
        var cardnumber = $('#paymill_card_number').val();
        var detector = new BrandDetection();
        var brand = detector.detect(cardnumber);
        if (brand !== 'unknown') {
            $('#paymill_card_number').addClass("paymill-card-number-" + brand);
            if (!detector.validate(cardnumber)) {
                console.log();
                $('#paymill_card_number').addClass("paymill-card-number-grayscale");
            }
        }
    });

    $('#paymill_card_expiry_date').keyup(function() {
        var expiryDate = $("#paymill_card_expiry_date").val();
        if (expiryDate.match(/^.{2}$/)) {
            expiryDate += "/";
            $("#paymill_card_expiry_date").val(expiryDate);
        }
    });

    $("#paymill_form").submit(function(e) {
        if (!$("input[name=paymillToken]")) {
            e.preventDefault();
        }
        toggleLoading('show');
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
                if (isSepa() && PAYMILL_PAYMENT === "paymilldirectdebit") {
                    SepaObj.popUp('sepaCallback');
                } else {
                    $("#paymill_form").append("<input type='hidden' name='paymillFastcheckout' value='" + false + "'/>");
                    try {
                        var params;
                        if (PAYMILL_PAYMENT === "paymillcreditcard") {
                            params = {
                                number: $('#paymill_card_number').val(),
                                cardholder: $('#paymill_card_holder').val(),
                                exp_month: $("#paymill_card_expiry_date").val().split("/")[0],
                                exp_year: $("#paymill_card_expiry_date").val().split("/")[1],
                                cvc: $('#paymill_card_cvc').val(),
                                amount_int: PAYMILL_AMOUNT,
                                currency: PAYMILL_CURRENCY
                            };
                        } else if (PAYMILL_PAYMENT === "paymilldirectdebit") {
                            params = {
                                number: $('#paymill_iban').val(),
                                bank: $('#paymill_bic').val(),
                                accountholder: $('#paymill_accountholder').val()
                            };
                        }
                        paymill.createToken(params, PaymillResponseHandler);
                    } catch (e) {
                        alert("Ein Fehler ist aufgetreten: " + e);
                    }
                }
            }
            toggleLoading('hide');
        }
        return false;
    });

    if (PAYMILL_PAYMENT === "paymilldirectdebit") {
        $('#paymill_iban').keyup(function() {
            var iban = $('#paymill_iban').val();
            if (!iban.match(/^DE/)) {
                var newVal = "DE";
                if (iban.match(/^.{2}(.*)/)) {
                    newVal += iban.match(/^.{2}(.*)/)[1];
                }
                if (isSepa()) {
                    $('#paymill_iban').val(newVal);
                }
            }
        });
    }
});

function sepaCallback(success)
{
    if (success) {
        $("#paymill_form").append("<input type='hidden' name='paymillFastcheckout' value='" + false + "'/>");
        var params = {
            iban: $('#paymill_iban').val(),
            bic: $('#paymill_bic').val(),
            accountholder: $('#paymill_accountholder').val()
        };
        paymill.createToken(params, PaymillResponseHandler);
    } else {
        $("#paymill_submit").removeAttr('disabled');
        $(".paymill_error").html(PAYMILL_TRANSLATION.paymill_invalid_mandate_checkbox);
        $(".paymill_error").show(500);
    }
}

function getFormData(array, ignoreEmptyValues) {
    $('#paymill_form :input').not('[type=hidden]').each(function() {
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
        if (!paymill.validateExpiry($("#paymill_card_expiry_date").val().split("/")[0], $("#paymill_card_expiry_date").val().split("/")[1])) {
            field.push($('#paymill_card_expiry_date'));
            message = PAYMILL_TRANSLATION.paymill_card_expiry_date;
            result = false;
        }
        if (!paymill.validateCardNumber($('#paymill_card_number').val())) {
            field.push($('#paymill_card_number'));
            message = PAYMILL_TRANSLATION.paymill_card_number;
            result = false;
        }
    } else if (PAYMILL_PAYMENT === "paymilldirectdebit") {
        if (isSepa()) {
            var iban = new Iban();
            if (!iban.validate($('#paymill_iban').val())) {
                field.push($('#paymill_iban'));
                message = PAYMILL_TRANSLATION.paymill_iban;
                result = false;
            }
            if ($('#paymill_bic').val().length !== 9 && $('#paymill_bic').val().length !== 11) {
                field.push($('#paymill_bic'));
                message = PAYMILL_TRANSLATION.paymill_bic;
                result = false;
            }
        } else {
            if (!paymill.validateBankCode($('#paymill_bic').val())) {
                field.push($('#paymill_bic'));
                message = PAYMILL_TRANSLATION.paymill_banknumber;
                result = false;
            }
            if (!paymill.validateAccountNumber($('#paymill_iban').val())) {
                field.push($('#paymill_iban'));
                message = PAYMILL_TRANSLATION.paymill_accountnumber;
                result = false;
            }
        }
        if (!paymill.validateHolder($('#paymill_accountholder').val())) {
            field.push($('#paymill_accountholder'));
            message = PAYMILL_TRANSLATION.paymill_accountholder;
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

function isSepa() {
    var reg = new RegExp(/^\D{2}/);
    return reg.test($('#paymill_iban').val());
}

function PaymillResponseHandler(error, result) {
    debug("Started Paymill response handler");
    if (error) {
        toggleLoading('hide');
        $("#paymill_submit").removeAttr('disabled');
        if (PAYMILL_TRANSLATION.bridge.hasOwnProperty(error.apierror)) {
            alert("API returned error:" + PAYMILL_TRANSLATION.bridge[error.apierror]);
            debug("API returned error:" + PAYMILL_TRANSLATION.bridge[error.apierror]);
        } else {
            alert("API returned error(raw):" + error.apierror);
        }
        debug("API returned error:" + error.apierror);
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

function toggleLoading(newStatus) {
    debug("ToggleLoadingwheel: " + newStatus);
    switch (newStatus) {
        case 'show':
            // show waitingwheel disable input
            $('#paymill_form').find('input').attr('disabled', true);
            $('.paymill_loading_layer').show();
            break;
        case 'hide':
            // hide waitingwheel enable input
            $('#paymill_form').find('input').attr('disabled', false);
            $('.paymill_loading_layer').hide();
            break;
    }

}