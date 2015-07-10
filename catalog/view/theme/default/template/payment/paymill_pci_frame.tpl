<?php
    echo '<script type="text/javascript">';
    echo 'var PAYMILL_PUBLIC_KEY  = "' . $paymill_publickey . '"; '
    . 'var PAYMILL_CURRENCY  = "' . $paymill_currency . '"; '
    . 'var PAYMILL_AMOUNT  = ' . $paymill_amount * 100 . '; '
    . 'var PAYMILL_PAYMENT  = "' . $paymill_activepayment . '"; '
    . 'var PAYMILL_DEBUG  = "' . $paymill_debugging . '"; '
    . 'var PAYMILL_IMAGE  = "' . $paymill_image_folder . '"; '
    . 'var PAYMILL_TRANSLATION = ' . json_encode($paymill_javascript_error) . ';'
    . 'var PAYMILL_TRANSLATION_FIELDS = ' . json_encode($paymill_translation_fields) . ';'
    . 'var PAYMILL_FASTCHECKOUT_DATA = ' . json_encode($paymill_prefilled) . ';'
    . 'var PAYMILL_FASTCHECKOUT_ENABLED = "' . $paymill_load_frame_fastcheckout . '";'
    . 'var PAYMILL_BRAND = ' . json_encode($paymill_icon) . '; ';
    echo '</script>';
?>

<script type="text/javascript" src="<?php echo $paymill_js; ?>/checkout_iframe.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $paymill_iframe_css; ?>" />
<div class="right">
        <form id='paymill_form' action="<?php echo $paymill_form_action; ?>" method="POST">
            <?php if($paymill_buttonSolution){ ?>
            <div class="buttons">
                <input type="button" class="button paymill_confirm_button" id="paymill_submit" value="<?php echo $button_confirm; ?>">
            </div>
            <?php } ?>
            <div class="debit paymill_relative paymillClearfix" id="paymillContainer">
                <span class="paymill_loading_layer">
                    <img src="<?php echo $paymill_image_folder. '/ajax-loader.gif';?>">
                </span>
            </div>
            <div class='paymill_icons' id="paymillIconsPaymentForm">
                <?php
                if($paymill_icon_visa){ echo "<img src=\"$paymill_image_folder/32x20_visa.png\">"; }
                if($paymill_icon_master){ echo "<img src=\"$paymill_image_folder/32x20_mastercard.png\">"; }
                if($paymill_icon_amex){ echo "<img src=\"$paymill_image_folder/32x20_amex.png\">"; }
                if($paymill_icon_jcb){ echo "<img src=\"$paymill_image_folder/32x20_jcb.png\">"; }
                if($paymill_icon_maestro){ echo "<img src=\"$paymill_image_folder/32x20_maestro.png\">"; }
                if($paymill_icon_diners_club){ echo "<img src=\"$paymill_image_folder/32x20_dinersclub.png\">"; }
                if($paymill_icon_discover){ echo "<img src=\"$paymill_image_folder/32x20_discover.png\">"; }
                if($paymill_icon_china_unionpay){ echo "<img src=\"$paymill_image_folder/32x20_unionpay.png\">"; }
                if($paymill_icon_dankort){ echo "<img src=\"$paymill_image_folder/32x20_dankort.png\">"; }
                if($paymill_icon_carta_si){ echo "<img src=\"$paymill_image_folder/32x20_carta-si.png\">"; }
                if($paymill_icon_carte_bleue){ echo "<img src=\"$paymill_image_folder/32x20_carte-bleue.png\">"; }
                ?>
            </div>
            <?php if(!$paymill_buttonSolution){ ?>
            <div class="buttons">
                <input type="button" class="button paymill_confirm_button" id="paymill_submit" value="<?php echo $button_confirm; ?>">
            </div>
            <?php } ?>
        </form>
</div>