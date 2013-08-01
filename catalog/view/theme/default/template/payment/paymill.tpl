<?php
/**
* paymill
*
* @category   PayIntelligent
* @copyright  Copyright (c) 2011 PayIntelligent GmbH (http://payintelligent.de)
*/
?>

<script type="text/javascript">
    var PAYMILL_PUBLIC_KEY = "<?php echo $paymill_publickey; ?>";
    var PAYMILL_CURRENCY = "<?php echo $paymill_currency;?>";
    var PAYMILL_AMOUNT = "<?php echo $paymill_amount * 100;?>";
    var PAYMILL_PAYMENT = "<?php echo $paymill_activepayment;?>";
    var PAYMILL_DEBUG = "<?php echo $paymill_debugging;?>";
    var PAYMILL_IMAGE = "<?php echo $paymill_image_folder;?>";
    var PAYMILL_FASTCHECKOUT = "<? echo isset($paymill_prefilled['id'])?1:0;?>";
</script>
<script type="text/javascript" src="https://bridge.paymill.com/"></script>
<script type="text/javascript" src="<?php echo $paymill_js; ?>"></script>
<link rel="stylesheet" type="text/css" href="<?php echo $paymill_css; ?>" />
<?php if (isset($error)) { ?>
<div class="warning"><?php echo $error; ?></div>
<?php } ?>

<div class="right">
    <form id='paymill_form' action="<?php echo $paymill_form_action; ?>" method="POST">
        <div class="error" style="display: none">
            <ul id="paymill_errors"></ul>
        </div>
        <div class="debit">
            <?php if($paymill_activepayment === 'paymillcreditcard'){ ?>
            <p class="none">
                <label><?php echo $paymill_cardholder;?></label>
                <input id="paymill_account_holder" type="text" size="20" class="paymill_text" value="<?php echo $paymill_prefilled['card_holder']?:$paymill_fullname;?>"/>
            </p>
            <p class="none">
                <label><?php echo $paymill_cardnumber;?></label>
                <input id="paymill_card_number" type="text" size="20" class="paymill_text" value="<?php echo $paymill_prefilled['last4']? '**********'.$paymill_prefilled['last4']:'';?>"/>
                <span id="paymill_card_icon"></span>
            </p>
            <p class="none">
                <label><?php echo $paymill_cvc;?></label>
                <input id="paymill_card_cvc" type="text" size="4" class="paymill_text" value="<?php echo isset($paymill_prefilled['id'])?'***':'' ?>"/>
            </p>
            <p class="none">
                <label><?php echo $paymill_birthday;?></label>
                <select id="paymill_card_expiry_year" style="width: 60px; display: inline-block;" class="paymill_text">
                <?php
                $prefilled = $paymill_prefilled['expire_year']?:null;
                foreach($paymill_form_year as $year){
                    if(!is_null($prefilled) && $prefilled == $year){
                        echo "<option value=$year selected>$year</option>";
                    }else{
                        echo "<option value=$year>$year</option>";
                    }
                }
                ?>
                </select>
                <select id="paymill_card_expiry_month" style="width: 50px; display: inline-block;" class="paymill_text">
                <?php
                $prefilled = $paymill_prefilled['expire_month']?:null;
                foreach($paymill_form_month as $month){
                    if(!is_null($prefilled) && $prefilled == $month){
                        echo "<option value=$month selected>$month</option>";
                    }else{
                        echo "<option value=$month>$month</option>";
                    }
                }
                ?>
                </select>
            </p>
            <p class="description"><?php echo $paymill_description;?></p>
            <p>
            <div class="paymill_powered">
                <div class="paymill_credits">
                    <?php echo $paymill_paymilllabel_cc;?> powered by <a href="http://www.paymill.de" target="_blank">PAYMILL</a>
                </div>
            </div>
            </p>
            <?php }elseif($paymill_activepayment === 'paymilldirectdebit'){ ?>
            <p class="none">
                <label><?php echo $paymill_accountholder;?></label>
                <input id="paymill_accountholder" type="text" size="20" class="paymill_text" value="<?php echo $paymill_prefilled['holder']?:$paymill_fullname; ?>"/>
            </p>
            <p class="none">
                <label><?php echo $paymill_accountnumber;?></label>
                <input id="paymill_accountnumber" type="text" size="20" class="paymill_text" value="<?php echo $paymill_prefilled['account']?:''; ?>" />
            </p>
            <p class="none">
                <label><?php echo $paymill_banknumber;?></label>
                <input id="paymill_banknumber" type="text" size="20" class="paymill_text" value="<?php echo $paymill_prefilled['code']?:''; ?>" />
            </p>
            <p class="description"><?php echo $paymill_description;?></p>
            <p>
            <div class="paymill_powered">
                <div class="paymill_credits">
                    <?php echo $paymill_paymilllabel_elv;?> powered by <a href="http://www.paymill.de" target="_blank">PAYMILL</a>
                </div>
            </div>
            </p>
            <?php } ?>
        </div>
        <div class="buttons">
            <a class="button" id="paymill_submit">
                <span><?php echo $button_confirm; ?></span>
            </a>
        </div>
    </form>
</div>