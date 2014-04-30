function submitForm(button) {
    $("#paymillForm").append("<input type='hidden' name='button' value='" + button + "'/>");
    $("#paymillForm").submit();
}

function ChangePage() {
    $("#paymillForm").append("<input type='hidden' name='page' value='" +  $("#paymillGoToPage").val() + "'/>");
    submitForm("search");
}

function showDetails(info){
    info = decodeURIComponent(info);
    info = info.replace(/\+/g, "&nbsp;");
    info = info.replace(/\n/g, "<br>");
    $("#paymillDetailContent").html(info);
}