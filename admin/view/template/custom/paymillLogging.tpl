<?php echo $header; ?>
<link rel="stylesheet" type="text/css" href="<?php echo $paymillCSS; ?>" />
<div id="content">
    <div class="breadcrumb" align="left">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <div class="box">

    </div>
    <div class="left"></div>
    <div class="right"></div>
    <div class="heading">
        <h1>
            <img src="view/image/payment.png" alt="payment icon"/>
            <?php echo $headingTitle; ?>
        </h1>
    </div>
    <div class="content">
        <?php var_dump($paymillTotal); ?>
        <table class="list">
            <thead>
            <tr>
                <th>Identifier</th>
                <th>Message</th>
                <th>Debug</th>
            </tr>
            </thead>
            <?php foreach($paymillEntries as $id => $row){ ?>
            <tr>
                <td><?php echo $row['identifier']; ?></td>
                <td><?php echo $row['message']; ?></td>
                <?php if(strlen($row['debug']) > 100){ ?>
                <td>INSERT SEE MORE LINK HERE</td>
                <?php }else{ ?>
                <td><pre><?php echo $row['debug']; ?></pre></td>
                <?php } ?>
            </tr>
            <?php } ?>
        </table>
    </div>
</div>
<?php echo $footer; ?>