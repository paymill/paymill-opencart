<?php
/**
* paymill
*
* @category   PayIntelligent
* @copyright  Copyright (c) 2011 PayIntelligent GmbH (http://payintelligent.de)
*/
?>
<?php
echo '<script type="text/javascript">';
echo 'var PAYMILL_PUBLIC_KEY  = "' . $paymill_publickey . '"; '
. 'var PAYMILL_CURRENCY  = "' . $paymill_currency . '"; '
. 'var PAYMILL_AMOUNT  = ' . $paymill_amount * 100 . '; '
. 'var PAYMILL_PAYMENT  = "' . $paymill_activepayment . '"; '
. 'var PAYMILL_DEBUG  = "' . $paymill_debugging . '"; '
. 'var PAYMILL_IMAGE  = "' . $paymill_image_folder . '"; '
. 'var PAYMILL_TRANSLATION = ' . json_encode($paymill_javascript_error) . '; ';
echo '</script>';
?>
<script type="text/javascript" src="https://bridge.paymill.com/"></script>
<script type="text/javascript" src="<?php echo $paymill_js; ?>/Sepa.js"></script>
<script type="text/javascript" src="<?php echo $paymill_js; ?>/Iban.js"></script>
<script type="text/javascript" src="<?php echo $paymill_js; ?>/BrandDetection.js"></script>
<script type="text/javascript" src="<?php echo $paymill_js; ?>/checkout.js"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $paymill_css; ?>" />

<div class="right">
    <form id='paymill_form' action="<?php echo $paymill_form_action; ?>" method="POST">
        <?php if($paymill_buttonSolution){ ?>
        <div class="buttons">
            <input type="submit" class="button paymill_confirm_button" id="paymill_submit" value="<?php echo $button_confirm; ?>">
        </div>
        <?php } ?>
        <div class='paymill_error warning' style="display: none"></div>
        <div class="debit paymill_relative">
            <span class="paymill_loading_layer">
                <img src="<?php echo $paymill_image_folder. '/ajax-loader.gif';?>">
            </span>
            <?php if($paymill_activepayment === 'paymillcreditcard'){ ?>
            <fieldset>
                <label for="paymill_card_number" class="field-left"><?php echo $paymill_cardnumber;?>*</label>
                <input id="paymill_card_number" type="text" class="field-left" value="<?php echo isset($paymill_prefilled['last4'])? '**********'.$paymill_prefilled['last4']:'';?>"/>
                <label for="paymill_card_expiry_date" class="field-right"><?php echo $paymill_expirydate;?>*</label>
                <input id="paymill_card_expiry_date" type="text" class="field-right" value="<?php echo isset($paymill_prefilled['expire_date'])? $paymill_prefilled['expire_date']:'MM/YYYY';?>"/>
            </fieldset>
            <fieldset>
                <label for="paymill_card_holder" class="field-left"><?php echo $paymill_cardholder;?>*</label>
                <input id="paymill_card_holder" type="text" class="field-left" value="<?php echo isset($paymill_prefilled['card_holder'])?$paymill_prefilled['card_holder']:$paymill_fullname;?>"/>
                <label for="paymill_card_cvc" class="field-right"><?php echo $paymill_cvc;?>*</label>
                <input id="paymill_card_cvc" type="text" class="field-right" value="<?php echo isset($paymill_prefilled['id'])?'***':'' ?>"/>
            </fieldset>
            <p class="description"><?php echo $paymill_description;?></p>
            <div class='paymill_icons'>
                <?php
                if($paymill_icon){
                echo "<p style='margin-bottom:0px;'>$paymill_icon_text:</p>";
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
                }
                ?>
            </div>
            <?php }elseif($paymill_activepayment === 'paymilldirectdebit'){ ?>
            <fieldset>
                <label for="paymill_iban" class="field-left"><?php echo $paymill_accountnumber . " / " . $paymill_iban;?>*</label>
                <input id="paymill_iban" type="text" size="20" class="field-left" value="<?php echo isset($paymill_prefilled['iban'])?$paymill_prefilled['iban']:isset($paymill_prefilled['account'])?$paymill_prefilled['account']:''; ?>" />
                <label for="paymill_bic" class="field-right"><?php echo $paymill_banknumber . " / " .$paymill_bic;?>*</label>
                <input id="paymill_bic" type="text" size="20" class="field-right" value="<?php echo isset($paymill_prefilled['bic'])?$paymill_prefilled['bic']:isset($paymill_prefilled['code'])?$paymill_prefilled['code']:''; ?>" />
            </fieldset>
            <fieldset>
                <label for="paymill_accountholder" class="field-full"><?php echo $paymill_accountholder;?>*</label>
                <input id="paymill_accountholder" type="text" size="20" class="field-full" value="<?php echo isset($paymill_prefilled['holder'])?$paymill_prefilled['holder']:$paymill_fullname; ?>"/>
            </fieldset>
            <p class="description"><?php echo $paymill_description;?></p>
            <?php } ?>
        </div>
        <?php if(!$paymill_buttonSolution){ ?>
        <div class="buttons">
            <input type="submit" class="button paymill_confirm_button" id="paymill_submit" value="<?php echo $button_confirm; ?>">
        </div>
        <?php } ?>
    </form>
</div>