<?php echo $header; ?>

<script type="text/javascript" >
    $('document').ready(function(){
        $('#paymill_capture').click(function(){
            $.ajax({
                url: "<?php echo $url_capture;?>",
                success: function(result){
                    console.log(result);
                }
            });
        });
        $('#paymill_refund').click(function(){
            $.ajax({
                url: "<?php echo $url_refund;?>",
                success: function(result){
                    console.log(result);
                }
            });
        });
    });
</script>

<div id="content">
    <div class="breadcrumb" align="left">

    </div>
        <input type='hidden' name='page' value='<?php echo $paymillPage; ?>'/>
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

            </div>
        </div>
</div>
<?php echo $footer; ?>