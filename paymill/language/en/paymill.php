<?php

// Heading
$_['heading_title'] = 'PAYMILL';
$_['button_viewcart'] = 'Show Cart';

// Entry
$_['entry_status'] = 'Plugin Activated:';
$_['entry_publickey'] = 'Public Key:';
$_['entry_privatekey'] = 'Private Key:';
$_['entry_apiurl'] = 'ApiUrl:';
$_['entry_sort_order'] = 'Sort Order:';
$_['entry_different_amount'] = '3D Secure Tolerance:';
$_['entry_fast_checkout'] = 'Fast Checkout:';
$_['entry_label'] = 'Show Label:';
$_['entry_logging'] = 'Logging:';
$_['entry_debugging'] = 'Debug:';

//Text
$_['button_logging'] = 'Logging';
$_['button_search'] = 'Search';
$_['text_payment'] = 'Payment';
$_['text_success'] = 'Success: You have modified your Paymill account details!';
$_['paymill_accountholder'] = 'Accountholder *';
$_['paymill_accountnumber'] = 'Accountnumber *';
$_['paymill_banknumber'] = 'Banknumber *';

$_['paymill_cardholder'] = 'Cardholder *';
$_['paymill_cardnumber'] = 'Creditcardnumber *';
$_['paymill_cvc'] = 'CVC *';
$_['paymill_birthday'] = 'Valid until (MM/YYYY) *';

$_['paymill_description'] = "Fields marked with * are required";
$_['paymill_paymilllabel_cc'] = 'Secure creditcard payments';
$_['paymill_paymilllabel_elv'] = 'Directdebit payments';

$_['text_paymilldirectdebit'] = '<img src="view/image/payment/paymill_logo.jpeg" alt="Paymill" title="Paymill" style="border: 1px solid #EEEEEE; hight:27px; width:96px;" />';
$_['text_payment_paymilldirectdebit_title'] = 'PAYMILL Direct Debit';
$_['text_payment_paymilldirectdebit_detail'] = 'PAYMILL Direct Debit';

$_['text_paymillcreditcard'] = '<img src="view/image/payment/paymill_logo.jpeg" alt="Paymill" title="Paymill" style="border: 1px solid #EEEEEE; hight:27px; width:96px;" />';
$_['text_payment_paymillcreditcard_title'] = 'PAYMILL Credit Card';
$_['text_payment_paymillcreditcard_detail'] = 'PAYMILL Credit Card';
// Error
$_['error_permission'] = 'Warning: You do not have permission to modify payment PAYMILL!';
$_['error_different_amount'] = 'Warning: 3DSecure tolerance must be a valid number';


$_['date_month'] = array(
    "January", "February", "March", "April", "May", "June", "July", "August", "September", "October", "November", "December"
);

// JavaScript Errormessages
$_['error_javascript'] = array(
    "paymill_card_number" => "Please enter a valid cardnumber.",
    "paymill_card_cvc" => "Please enter a valid securecode (back of card).",
    "paymill_card_expiry_date" => "The expirydate is invalid.",
    "paymill_card_holder" => "Please enter the cardholders name.",
    "paymill_accountholder" => "Please enter the accountholders name.",
    "paymill_accountnumber" => "Please enter a valid accountnumber.",
    "paymill_banknumber" => "Please enter a valid bankcode."
);