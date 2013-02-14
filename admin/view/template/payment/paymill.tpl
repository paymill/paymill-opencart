<?php
/**
* This program is free software; you can redistribute it and/or modify
* it under the terms of the GNU General Public License.
*
* This program is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* @module clickandbuy
* @copyright (C) 2010 PayIntelligent GmbH
* @license GNU General Public License
*/
?>

<?php echo $header; ?>
<div id="content">
    <div class="breadcrumb" align="left">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
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
                        <td><?php echo $entry_sandbox; ?></td>
                        <td>
                            <select name="paymill_sandbox">
                                <option value="1" <?php if ($paymill_sandbox) { echo 'selected="selected"';}?>> <?php echo $text_enabled; ?></option>
                                <option value="0" <?php if (!$paymill_sandbox) { echo 'selected="selected"';}?>> <?php echo $text_disabled; ?></option>
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
                        <td><?php echo $entry_apiurl; ?></td>
                        <td>
                            <input type="text" name="paymill_apiurl" value="<?php echo $paymill_apiurl; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_bridgeurl; ?></td>
                        <td>
                            <input type="text" name="paymill_bridgeurl" value="<?php echo $paymill_bridgeurl; ?>" />
                        </td>
                    </tr>
                    <tr>
                        <td><?php echo $entry_sort_order; ?></td>
                        <td><input type="text" name="paymill_sort_order" value="<?php echo $paymill_sort_order; ?>" size="1" /></td>
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
                </table>
            </form>
        </div>
    </div>
</div>
<?php echo $footer; ?>