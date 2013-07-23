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
            <div class="buttons"><a onclick="$('#form').submit();" class="button"><span><?php echo $button_save; ?></span></a><a onclick="location = '<?php echo $cancel; ?>';" class="button"><span><?php echo $button_cancel; ?></span></a></div>
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
                        <td><?php echo $entry_publickey; ?></td>
                        <td>
                            <input type="text" name="paymill_publickey" value="<?php echo $paymill_publickey; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_privatekey; ?></td>
                        <td>
                            <input type="text" name="paymill_privatekey" value="<?php echo $paymill_privatekey; ?>" />
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
                        <td><?php echo $entry_different_amount; ?></td>
                        <td><input type="text" name="paymill_differnet_amount" value="<?php echo $paymill_different_amount; ?>" size="9" /></td>
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
                        <td colspan="2">
                            <textarea style="width:1000px;" rows="15" readonly /><?php echo $paymill_logfile; ?></textarea>
                        </td>
                    </tr>
                </table>
            </form>
        </div>
    </div>
</div>
<?php echo $footer; ?>