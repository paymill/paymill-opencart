<?php echo $header; ?>

<script type="text/javascript" >
    $('document').ready(function(){
        $('#paymill_capture').click(function(){
            $.ajax({
                url: "<?php echo $url_capture;?>",
                success: function(result){
                    $( "#dialog-message" ).html("<?php echo $text_capture_failure; ?>");
                    if(result === 'OK'){
                        $( "#dialog-message" ).html("<?php echo $text_capture_success; ?>");
                    }
                    $( "#dialog-message" ).dialog({
                        title:'Capture',
                        modal: true,
                        buttons: {
                          Ok: function() {
                            $( this ).dialog( "close" );
                          }
                        }
                    });
                }
            });
        });
        $('#paymill_refund').click(function(){
            $.ajax({
                url: "<?php echo $url_refund;?>",
                success: function(result){
                    $( "#dialog-message" ).html("<?php echo $text_refund_failure; ?>");
                    if(result === 'OK'){
                        $( "#dialog-message" ).html("<?php echo $text_refund_success; ?>");
                    }
                    $( "#dialog-message" ).dialog({
                        title:'Refund',
                        modal: true,
                        buttons: {
                          Ok: function() {
                            $( this ).dialog( "close" );
                          }
                        }
                    });
                }
            });
        });
    });
</script>

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
                    Order #<?php echo $data_orderId; ?>
                </h1>

                <div class="buttons">
                    <a id="paymill_capture" class="button">Capture</a>
                    <a id="paymill_refund" class="button">Refund</a>
                </div>
            </div>
            <div class="content">
                <table class="form">
                    <tr>
                        <td><?php echo $text_order_id; ?></td>
                        <td><?php echo $data_orderId; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $text_store_name; ?></td>
                        <td><?php echo $data_storename; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $text_firstname; ?></td>
                        <td><?php echo $data_customer_firstname; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $text_lastname; ?></td>
                        <td><?php echo $data_customer_lastname; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $text_email; ?></td>
                        <td><?php echo $data_customer_email; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $column_total; ?></td>
                        <td><?php echo $data_order_total; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $text_date_added; ?></td>
                        <td><?php echo $data_order_date_added; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $text_payment_method; ?></td>
                        <td><?php echo $data_order_payment_method; ?></td>
                    </tr>
                    <tr>
                        <td><?php echo $text_order_status; ?></td>
                        <td><?php echo $data_order_status; ?></td>
                    </tr>
                </table>
            </div>
        </div>
</div>
<div id="dialog-message"></div>
<?php echo $footer; ?>