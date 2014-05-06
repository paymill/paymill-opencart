<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb" align="left">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <div style="text-align:center; margin:5px;color:red;font-size: large;">
        <?php echo isset($error_warning)?$error_warning:''; ?>
    </div>
    <div class="box">
        <div class="left"></div>
        <div class="right"></div>
        <div class="heading">
            <h1>
                <img src="view/image/payment.png" alt="payment icon"/>
                <?php echo $heading_title; ?>
            </h1>
            <div class="buttons">
                <a onclick="$('#form').submit();" class="button">
                    <span><?php echo $button_save; ?></span>
                </a>
                <a onclick="location = '<?php echo $logging; ?>';" class="button">
                    <span><?php echo $button_logging; ?></span>
                </a>
                <a onclick="location = '<?php echo $cancel; ?>';" class="button">
                    <span><?php echo $button_cancel; ?></span>
                </a>
            </div>
        </div>
        <div class="content">
            <form action="<?php echo $action; ?>" method="post" enctype="multipart/form-data" id="form">
                <table class="form">
                    <tr>
                        <td><?php echo $entry_status; ?></td>
                        <td>
                            <select name="paymill_status">
                                <option value="1" <?php if ($paymill_status) { echo 'selected="selected"';}?>> <?php echo $text_enabled; ?></option>
                                <option value="0" <?php if (!$paymill_status) { echo 'selected="selected"';}?>> <?php echo $text_disabled; ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_privatekey; ?></td>
                        <td>
                            <input type="text" name="paymill_privatekey" value="<?php echo $paymill_privatekey; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_publickey; ?></td>
                        <td>
                            <input type="text" name="paymill_publickey" value="<?php echo $paymill_publickey; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_sort_order; ?></td>
                        <td><input type="text" name="paymill_sort_order" value="<?php echo $paymill_sort_order; ?>" size="1" /></td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_fast_checkout; ?></td>
                        <td>
                            <select name="paymill_fast_checkout">
                                <option value="1" <?php if ($paymill_fast_checkout) { echo 'selected="selected"';}?>> <?php echo $text_enabled; ?></option>
                                <option value="0" <?php if (!$paymill_fast_checkout) { echo 'selected="selected"';}?>> <?php echo $text_disabled; ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_logging; ?></td>
                        <td>
                            <select name="paymill_logging">
                                <option value="1" <?php if ($paymill_logging) { echo 'selected="selected"';}?>> <?php echo $text_enabled; ?></option>
                                <option value="0" <?php if (!$paymill_logging) { echo 'selected="selected"';}?>> <?php echo $text_disabled; ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_debugging; ?></td>
                        <td>
                            <select name="paymill_debugging">
                                <option value="1" <?php if ($paymill_debugging) { echo 'selected="selected"';}?>> <?php echo $text_enabled; ?></option>
                                <option value="0" <?php if (!$paymill_debugging) { echo 'selected="selected"';}?>> <?php echo $text_disabled; ?></option>
                            </select>
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_buttonSolution; ?></td>
                        <td>
                            <select name="paymill_buttonSolution">
                                <option value="1" <?php if ($paymill_buttonSolution) { echo 'selected="selected"';}?>> <?php echo $text_enabled; ?></option>
                                <option value="0" <?php if (!$paymill_buttonSolution) { echo 'selected="selected"';}?>> <?php echo $text_disabled; ?></option>
                            </select>
                        </td>
                    </tr>
                    <?php if($paymill_payment === 'paymilldirectdebit'){ ?>
                    <input type="hidden" value="0" name="icon_visa">
                    <input type="hidden" value="0" name="icon_master">
                    <input type="hidden" value="0" name="icon_amex">
                    <input type="hidden" value="0" name="icon_jcb">
                    <input type="hidden" value="0" name="icon_maestro">
                    <input type="hidden" value="0" name="icon_diners_club">
                    <input type="hidden" value="0" name="icon_discover">
                    <input type="hidden" value="0" name="icon_china_unionpay">
                    <input type="hidden" value="0" name="icon_dankort">
                    <input type="hidden" value="0" name="icon_carta_si">
                    <input type="hidden" value="0" name="icon_carte_bleue">
                    <?php } else { ?>
                    <tr>
                        <td><?php echo $entry_specific_creditcard; ?></td>
                        <td>
                            <input type="checkbox" value="1" name="icon_visa" <?php if($paymill_icon_visa){ echo 'checked';}?>><img src="<?php echo $paymill_image_folder; ?>/32x20_visa.png">
                            <input type="checkbox" value="1" name="icon_master" <?php if($paymill_icon_master){ echo 'checked';}?>><img src="<?php echo $paymill_image_folder; ?>/32x20_mastercard.png"><br>
                            <input type="checkbox" value="1" name="icon_amex" <?php if($paymill_icon_amex){ echo 'checked';}?>><img src="<?php echo $paymill_image_folder; ?>/32x20_amex.png">
                            <input type="checkbox" value="1" name="icon_jcb" <?php if($paymill_icon_jcb){ echo 'checked';}?>><img src="<?php echo $paymill_image_folder; ?>/32x20_jcb.png"><br>
                            <input type="checkbox" value="1" name="icon_maestro" <?php if($paymill_icon_maestro){ echo 'checked';}?>><img src="<?php echo $paymill_image_folder; ?>/32x20_maestro.png">
                            <input type="checkbox" value="1" name="icon_diners_club" <?php if($paymill_icon_diners_club){ echo 'checked';}?>><img src="<?php echo $paymill_image_folder; ?>/32x20_dinersclub.png"><br>
                            <input type="checkbox" value="1" name="icon_discover" <?php if($paymill_icon_discover){ echo 'checked';}?>><img src="<?php echo $paymill_image_folder; ?>/32x20_discover.png">
                            <input type="checkbox" value="1" name="icon_china_unionpay" <?php if($paymill_icon_china_unionpay){ echo 'checked';}?>><img src="<?php echo $paymill_image_folder; ?>/32x20_unionpay.png"><br>
                            <input type="checkbox" value="1" name="icon_dankort" <?php if($paymill_icon_dankort){ echo 'checked';}?>><img src="<?php echo $paymill_image_folder; ?>/32x20_dankort.png">
                            <input type="checkbox" value="1" name="icon_carta_si" <?php if($paymill_icon_carta_si){ echo 'checked';}?>><img src="<?php echo $paymill_image_folder; ?>/32x20_carta-si.png"><br>
                            <input type="checkbox" value="1" name="icon_carte_bleue" <?php if($paymill_icon_carte_bleue){ echo 'checked';}?>><img src="<?php echo $paymill_image_folder; ?>/32x20_carte-bleue.png">
                        </td>
                    </tr>
                    <?php } ?>
                </table>
            </form>
        </div>
    </div>
</div>
<?php echo $footer; ?>