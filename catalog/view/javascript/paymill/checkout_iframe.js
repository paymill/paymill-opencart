function PaymillFrameResponseHandler(error)
{
    if (error) {
        debug("iFrame load failed with " + error.apierror + error.message);
    } else {
        debug("iFrame successfully loaded");
    }
}

function paymillEmbedFrame()
{
    paymill.embedFrame('paymillContainer', {}, PaymillFrameResponseHandler);
}

function PaymillResponseHandler(error, result)
{
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
        var name = '';
        form.append("<input type='hidden' name='paymillToken' value='" + token + "'/>");

        //form.get(0).submit();
    }
}

function debug(message) {
    if (PAYMILL_DEBUG) {
        console.log("[PaymillCC] " + message);
    }
}

function paymillEmbedFastcheckoutTable()
{
    var tableFragment = document.createDocumentFragment();

    var tr = document.createElement('tr');
    var textTd = document.createElement('td');
    var valueTd = document.createElement('td');

    tr.appendChild(textTd);
    tr.appendChild(valueTd);
    tableFragment.appendChild(tr);

    var table = document.createElement('table');
    table.appendChild();
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
