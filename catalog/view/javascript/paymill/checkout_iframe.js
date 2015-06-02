function PaymillFrameResponseHandler(error)
{
    if (error) {
        debug("iFrame load failed with " + error.apierror + error.message);
    } else {
        debug("iFrame successfully loaded");
    }
}

function PaymillResponseHandler(error, result)
{
    debug("Started Paymill response handler");
    if (error) {
        $('.paymill_loading_layer').hide();
        $("#paymill_submit").removeAttr('disabled');
        if (PAYMILL_TRANSLATION.bridge.hasOwnProperty(error.apierror)) {
            alert("API returned error:" + PAYMILL_TRANSLATION.bridge[error.apierror]);
            debug("API returned error:" + PAYMILL_TRANSLATION.bridge[error.apierror]);
        } else {
            alert("API returned error(raw):" + error.apierror);
            debug("API returned error:" + error.apierror);
        }
    } else {
        debug("Received token from Paymill API: " + result.token);
        var form = $("#paymill_form");
        var token = result.token;
        var name = '';
        form.append("<input type='hidden' name='paymillToken' value='" + token + "'/>");

        form.get(0).submit();
    }
}

function debug(message) {
    if (PAYMILL_DEBUG) {
        console.log("[PaymillCC] " + message);
    }
}

function paymillEmbedFastcheckout()
{
    var tableFragment = document.createDocumentFragment();

    tableFragment.appendChild(paymillTableTr(PAYMILL_TRANSLATION_FIELDS['cardnumber'],"***********" + PAYMILL_FASTCHECKOUT_DATA['last4']));
    tableFragment.appendChild(paymillTableTr(PAYMILL_TRANSLATION_FIELDS['expire_date'],PAYMILL_FASTCHECKOUT_DATA['expire_date']));
    tableFragment.appendChild(paymillTableTr(PAYMILL_TRANSLATION_FIELDS['cardholder'],PAYMILL_FASTCHECKOUT_DATA['card_holder']));
    tableFragment.appendChild(paymillTableTr(PAYMILL_TRANSLATION_FIELDS['cvc'], "***"));
    tableFragment.appendChild(paymillTableTr('', "<input type='button' class='button' id='paymill_change' value='" +PAYMILL_TRANSLATION_FIELDS['changebutton'] + "'>"));

    var table = document.createElement('table');
    table.setAttribute("id","paymillTable");
    table.appendChild(tableFragment);

    var container = document.getElementById('paymillContainer');
    container.appendChild(table);

    $("#paymill_change").click(function(e) {
        $("#paymillTable").remove();
        PAYMILL_FASTCHECKOUT_ENABLED = '0';
        paymillEmbedFrame();
    });
    $('.paymill_loading_layer').hide();
}

function paymillTableTr(text,value) {
    var tr = document.createElement('tr');
    var textTd = document.createElement('td');
    var valueTd = document.createElement('td');

    textTd.innerHTML = text;
    valueTd.innerHTML = value;

    tr.appendChild(textTd);
    tr.appendChild(valueTd);

    return tr;
}

$(document).ready(function() {
    $("#paymill_submit").click(function(e) {
        $('.paymill_loading_layer').show();
        $("#paymill_submit").attr('disabled', true);
        if(PAYMILL_FASTCHECKOUT_ENABLED == '1') {
            $("#paymill_form").append("<input type='hidden' name='paymillFastcheckout' value='" + true + "'/>");
            result = new Object();
            result.token = 'dummyToken';
            PaymillResponseHandler(null, result);
        } else {
            paymill.createTokenViaFrame({amount_int: PAYMILL_AMOUNT, currency: PAYMILL_CURRENCY}, PaymillResponseHandler);
        }
    });

    if(PAYMILL_FASTCHECKOUT_ENABLED == '1') {
        paymillEmbedFastcheckout();
    } else {
        paymillEmbedFrame();
    }
});

function paymillEmbedFrame() {
    $('.paymill_loading_layer').show();
    var url = "https://bridge.paymill.com/dss3";
    $.getScript( url, function() {
        paymill.embedFrame('paymillContainer', {lang: PAYMILL_TRANSLATION_FIELDS['lang']}, PaymillFrameResponseHandler);
        $('.paymill_loading_layer').hide();
    });
}
