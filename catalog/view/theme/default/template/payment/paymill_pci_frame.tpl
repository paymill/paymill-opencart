<?php
    echo '<script type="text/javascript">';
    echo 'var PAYMILL_PUBLIC_KEY  = "' . $paymill_publickey . '"; '
    . 'var PAYMILL_CURRENCY  = "' . $paymill_currency . '"; '
    . 'var PAYMILL_AMOUNT  = ' . $paymill_amount * 100 . '; '
    . 'var PAYMILL_PAYMENT  = "' . $paymill_activepayment . '"; '
    . 'var PAYMILL_DEBUG  = "' . $paymill_debugging . '"; '
    . 'var PAYMILL_IMAGE  = "' . $paymill_image_folder . '"; '
    . 'var PAYMILL_TRANSLATION = ' . json_encode($paymill_javascript_error) . ';'
    . 'var PAYMILL_BRAND = ' . json_encode($paymill_icon) . '; ';
    echo '</script>';
?>
<script type="text/javascript" src="https://bridge.paymill.com/dss3"></script>
<script type="text/javascript" src="<?php echo $paymill_js; ?>/checkout.js"></script>
<script type = "text/javascript" >
    var paymilliFrameOptions = {
        labels: {
            number:     PAYMILL_TRANSLATION.paymill_cardnumber,
            cvc:        PAYMILL_TRANSLATION.paymill_cvc,
            cardholder: PAYMILL_TRANSLATION.paymill_cardholder,
            exp:        PAYMILL_TRANSLATION.paymill_expirydate
        },
        placeholders: {
            number:     '',
            cvc:        '',
            cardholder: '',
            exp_month:  '',
            exp_year:   ''
        },
        errors: {
            number:     PAYMILL_TRANSLATION.bridge["field_invalid_card_number"],
            cvc:        PAYMILL_TRANSLATION.bridge["field_invalid_card_cvc"],
            exp:        PAYMILL_TRANSLATION.bridge["field_invalid_card_exp"]
        }
    };
    <?php if($paymill_stylesheet){ ?>
        paymilliFrameOptions.stylesheet = '<?php echo $paymill_stylesheet; ?>';
    <?php } ?>

    function PaymillFrameResponseHandler(error, result)
    {
        if (error) {
            debug("iFrame load failed with " + error.apierror + error.message);
        } else {
            debug("iFrame successfully loaded");
        }
    }

    function paymillEmbedFrame()
    {
        PAYMILL_FASTCHECKOUT_CC_CHANGED = true;
        paymill.embedFrame('paymillFormContainer', paymilliFrameOptions,  PaymillFrameResponseHandler);
    }
    <?php if(!isset($paymill_prefilled['last4']) || !isset($paymill_prefilled['expire_date'])) { ?>
    paymillEmbedFrame();
    </script >
<?php } else { ?>
</script >
    <table id="paymillFastCheckoutTable" style="clear: both">
            <tr>
                <td><?php echo $paymill_cardnumber;?></td>
                <td id="paymillFcCardNumber" class="paymill-card-number-{$paymillBrand}"><?php echo '**********'.$paymill_prefilled['last4']?></td>
            </tr>
            <tr>
                <td><?php echo $paymill_cvc;?></td>
                <td>{$paymillCvc}</td>
            </tr>
                    <tr>
                    <td><?php echo $paymill_cardholder;?></td>
                    <td>{$paymillCardHolder}</td>
            </tr>
                    <tr>
                    <td><?php echo $paymill_expirydate;?></td>
                    <td>{$paymillMonth}/{$paymillYear}</td>
            </tr>
    </table>
<?php } ?>