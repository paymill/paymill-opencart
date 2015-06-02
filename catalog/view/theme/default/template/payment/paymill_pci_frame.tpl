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
            <?php if(!$paymill_buttonSolution){ ?>
            <div class="buttons">
                <input type="button" class="button paymill_confirm_button" id="paymill_submit" value="<?php echo $button_confirm; ?>">
            </div>
            <?php } ?>
        </form>
</div>