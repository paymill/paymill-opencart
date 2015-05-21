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
    paymill.embedFrame('paymill_form', {}, PaymillFrameResponseHandler);
}

function PaymillResponseHandler(error, result)
{

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