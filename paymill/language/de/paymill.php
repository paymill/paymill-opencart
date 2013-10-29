<?php

// Heading
$_['heading_title'] = 'PAYMILL';
$_['button_viewcart'] = 'Warenkorb anzeigen';

// Entry
$_['entry_status'] = 'Plugin aktiv:';
$_['entry_publickey'] = 'Public Key:';
$_['entry_privatekey'] = 'Private Key:';
$_['entry_sort_order'] = 'Sortier Reihenfolge:';
$_['entry_different_amount'] = '3D Secure Toleranz:';
$_['entry_fast_checkout'] = 'Fast Checkout:';
$_['entry_label'] = 'Label anzeigen:';
$_['entry_logging'] = 'Logging:';
$_['entry_debugging'] = 'Debug:';

//Text
$_['button_logging'] = 'Logging';
$_['text_payment'] = 'Payment';
$_['text_success'] = 'Erfolg: Sie haben ihre PAYMILL Account Details angepasst!';
$_['paymill_accountholder'] = 'Kontoinhaber *';
$_['paymill_accountnumber'] = 'Kontonummer *';
$_['paymill_banknumber'] = 'Bankleitzahl *';

$_['paymill_cardholder'] = 'Kreditkartenbesitzer *';
$_['paymill_cardnumber'] = 'Kreditkarten-nummer *';
$_['paymill_cvc'] = 'CVC *';
$_['paymill_birthday'] = 'Gültig bis (MM/YYYY) *';

$_['paymill_description'] = "Die mit einem * markierten Felder sind Pflichtfelder.";
$_['paymill_paymilllabel_cc'] = 'Sichere Kreditkartenzahlung';
$_['paymill_paymilllabel_elv'] = 'ELV';

$_['text_paymilldirectdebit'] = '<img src="view/image/payment/paymill_logo.jpeg" alt="Paymill" title="Paymill" style="border: 1px solid #EEEEEE; hight:27px; width:96px;" />';
$_['text_payment_paymilldirectdebit_title'] = 'ELV';
$_['text_payment_paymilldirectdebit_detail'] = 'ELV';

$_['text_paymillcreditcard'] = '<img src="view/image/payment/paymill_logo.jpeg" alt="Paymill" title="Paymill" style="border: 1px solid #EEEEEE; hight:27px; width:96px;" />';
$_['text_payment_paymillcreditcard_title'] = 'Kreditkartenzahlung';
$_['text_payment_paymillcreditcard_detail'] = 'Kreditkartenzahlung';
// Error
$_['error_permission'] = 'Warnung: Sie haben nicht die Rechte PAYMILL anzupassen!';
$_['error_different_amount'] = 'Warning: 3DSecure Toleranz muss eine valide Zahl sein!';

$_['date_month'] = array(
    "Januar", "Februar", "M&auml;rz", "April", "Mai", "Juni", "Juli", "August", "September", "Oktober", "November", "Dezember"
);

// JavaScript Errormessages
$_['error_javascript'] = array(
    "paymill_card_number" => "Bitte geben Sie eine g&uuml;ltige Kartennummer ein",
    "paymill_card_cvc" => "Bitte geben sie einen g&uuml;ltigen Sicherheitscode ein (R&uuml;ckseite der Karte).",
    "paymill_card_expiry_date" => "Das Ablaufdatum der Karte ist ung&uuml;ltig.",
    "paymill_card_holder" => "Bitte geben Sie den Karteninhaber an.",
    "paymill_accountholder" => "Bitte geben Sie den Kontoinhaber an.",
    "paymill_accountnumber" => "Bitte geben Sie eine g&uuml;ltige Kontonummer ein.",
    "paymill_banknumber" => "Bitte geben Sie eine g&uuml;ltige BLZ ein."
);