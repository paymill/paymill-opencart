function submitForm(button) {
    $("#paymillForm").append("<input type='hidden' name='button' value='" + button + "'/>");
    $("#paymillForm").submit();
}
