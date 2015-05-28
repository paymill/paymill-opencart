<?php
    echo '<script type="text/javascript">';
    echo 'var PAYMILL_PUBLIC_KEY  = "' . $paymill_publickey . '"; '
    . 'var PAYMILL_CURRENCY  = "' . $paymill_currency . '"; '
    . 'var PAYMILL_AMOUNT  = ' . $paymill_amount * 100 . '; '
    . 'var PAYMILL_PAYMENT  = "' . $paymill_activepayment . '"; '
    . 'var PAYMILL_DEBUG  = "' . $paymill_debugging . '"; '
    . 'var PAYMILL_IMAGE  = "' . $paymill_image_folder . '"; '
    . 'var PAYMILL_TRANSLATION = ' . json_encode($paymill_javascript_error) . ';'
    . 'var PAYMILL_FASTCHECKOUT_DATA = ' . json_encode($paymill_prefilled) . ';'
    . 'var PAYMILL_FASTCHECKOUT_ENABLED = "' . $paymill_load_frame_fastcheckout . '";'
    . 'var PAYMILL_BRAND = ' . json_encode($paymill_icon) . '; ';
    echo '</script>';
?>

<script type="text/javascript" src="<?php echo $paymill_js; ?>/checkout_iframe.js"></script>
<div class="right">
        <form id='paymill_form' action="<?php echo $paymill_form_action; ?>" method="POST">
            <?php if($paymill_buttonSolution){ ?>
            <div class="buttons">
                <input type="submit" class="button paymill_confirm_button" id="paymill_submit" value="<?php echo $button_confirm; ?>">
            </div>
            <?php } ?>
            <div class="debit paymill_relative" id="paymillContainer">
                <span class="paymill_loading_layer">
                    <img src="<?php echo $paymill_image_folder. '/ajax-loader.gif';?>">
                </span>
                <script type="text/javascript">
                    <!--
                    toggleLoading('show');
                    var url = "https://bridge.paymill.com/dss3";
                    $.getScript( url, function() {
                        paymillEmbedFrame();
                        toggleLoading('hide');
                    }); //-->
                </script>
            </div>
            <?php if(!$paymill_buttonSolution){ ?>
            <div class="buttons">
                <input type="submit" class="button paymill_confirm_button" id="paymill_submit" value="<?php echo $button_confirm; ?>">
            </div>
            <?php } ?>
        </form>
        <script type="text/javascript">
            <!--
            $("#paymill_form").submit(function(e) {
                if (!$("input[name=paymillToken]")) {
                    console.log("disable");
                    e.preventDefault();
                }
                toggleLoading('show');
                $("#paymill_submit").attr('disabled', true);
                paymill.createTokenViaFrame({amount_int: PAYMILL_AMOUNT, currency: PAYMILL_CURRENCY}, PaymillResponseHandler);
                return false;
            }); //-->
        </script>
</div>