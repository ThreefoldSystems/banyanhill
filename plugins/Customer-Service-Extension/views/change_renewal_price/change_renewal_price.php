<?php
?>
<div class="tfs_css_header_modal">Change Price</div>
<div class='csd_ext_notice_msg_modal tfs_css_success_msg_modal'></div>
<div class="csd_ext_response_content">
    <?php if ( !empty($rate) ) {
        echo '<p>Your current price for this item is $' . $rate . '</p>';
    } ?>
    <p>
        Please click the button below to renew now for the discounted price of $<?php echo !empty( $price ) ? $price : '' ; ?>.
    </p>

    <div class="csd_ext_button_container">
        <button class="tfs_css_button csd_ext_button" id="csd_ext_renewal_price_confirm"
                data-url="<?php echo !empty( $url ) ? $url : '' ; ?>">
            Renew Now
        </button>
    </div>
</div>
