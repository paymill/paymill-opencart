<?php echo $header; ?>

<link rel="stylesheet" type="text/css" href="<?php echo $paymillCSS; ?>" />
<script type="text/javascript" src="<?php echo $paymillJS; ?>"></script>
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
                    Order <?php echo $data_orderId; ?>
                </h1>

                <div class="buttons">
                    <input type='number' id='orderNumber' >
                </div>
            </div>
            <div class="content">

            </div>
        </div>
</div>
<?php echo $footer; ?>