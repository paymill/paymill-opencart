<?php echo $header; ?>

<link rel="stylesheet" type="text/css" href="<?php echo $paymillCSS; ?>" />
<script type="text/javascript" src="<?php echo $paymillJS; ?>"></script>
<div id="content">
    <div class="breadcrumb" align="left">
        <?php foreach ($breadcrumbs as $breadcrumb) { ?>
        <?php echo $breadcrumb['separator']; ?><a href="<?php echo $breadcrumb['href']; ?>"><?php echo $breadcrumb['text']; ?></a>
        <?php } ?>
    </div>
    <form method="POST" action="<?php echo $paymillAction;?>" enctype="multipart/form-data" id="paymillForm">
        <input type='hidden' name='page' value='<?php echo $paymillPage; ?>'/>
        <div class="box">
            <div class="left"></div>
            <div class="right"></div>
            <div class="heading">
                <h1>
                    <img src="view/image/payment.png" alt="payment icon"/>
                    <?php echo $headingTitle; ?>
                </h1>

                <div class="buttons">
                    <input type="search" name="searchValue" value="<?php echo $paymillInputSearch;?>">
                    <input type="checkbox" name="connectedSearch" <?php if($paymillCheckboxConnectedSearch === "on"){ echo "checked"; }?> > Include connected Logs
                           <a onclick="submitForm('search');" class="button">
                        <span><?php echo $button_search; ?></span>
                    </a>
                    <a onclick="submitForm('delete');" class="button">
                        <span><?php echo $button_delete; ?></span>
                    </a>
                </div>
            </div>
            <div class="content">
                <?php for($i=0;$i < $paymillMaxPages; $i++){
                echo "<a onclick='ChangePage($i);' class='button'>";
                echo "<span>";
                echo $i+1;
                echo "</span>";
                echo "</a>";
                } ?>
                <span id="paymillDetail">

                </span>
                <table class="list">
                    <thead>
                        <tr>
                            <td width="1" style="text-align: center;">
                                <input type="checkbox" onclick="$('input[name*=\'selected\']').attr('checked', this.checked);">
                            </td>
                            <td class="left">Datum</td>
                            <td class="left">Identifier</td>
                            <td class="left">Message</td>
                            <td class="left">Debug</td>
                        </tr>
                    </thead>
                    <?php foreach($paymillEntries as $id => $row){ ?>
                    <tr>
                        <td style="text-align: center;">
                            <input type="checkbox" name="selected[]" value="<?php echo $row['id']; ?>">
                        </td>
                        <td class="left"><?php echo $row['date']; ?></td>
                        <td class="left"><?php echo $row['identifier']; ?></td>
                        <td class="left"><?php echo $row['message']; ?></td>
                        <?php if(strlen($row['debug']) > 200){ ?>
                        <td class="left">
                            <?php
                            echo "<a onclick='showDetails(\"".urlencode($row['debug'])."\");' class='button'>";
                            echo "<span>TEST</span>";
                            echo "</a>";
                            ?>
                        </td>
                        <?php }else{ ?>
                        <td><pre><?php echo $row['debug']; ?></pre></td>
                        <?php } ?>
                    </tr>
                    <?php } ?>
                </table>
            </div>
        </div>
    </form>
</div>
<?php echo $footer; ?>