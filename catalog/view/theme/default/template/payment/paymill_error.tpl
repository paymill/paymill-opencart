<?php echo $header; ?><?php echo $column_left; ?><?php echo $column_right; ?>
<div id="content">
    <div class="top">
        <div class="left"></div>
        <div class="right"></div>
        <div class="center">
            <h1><?php echo $heading_title; ?></h1>
        </div>
    </div>
    <div class="middle">
        <div class="buttons">
            <table>
                <tr>
                <center>
                    <?php echo $error_message; ?>
                </center>
                </tr>
                <tr>
                    <td align="right"><a onclick="location = '<?php echo str_replace('&', '&amp;', $cart); ?>'" class="button"><span><?php echo $button_viewcart; ?></span></a></td>
                </tr>
            </table>
        </div>
    </div>
    <div class="bottom">
        <div class="left"></div>
        <div class="right"></div>
        <div class="center"></div>
    </div>
</div>
<?php echo $footer; ?>