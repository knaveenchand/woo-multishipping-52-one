<?php
$current_user   = wp_get_current_user();
$userid_to_pull = $current_user->ID;
$addresses      = get_user_meta($userid_to_pull, 'wc_other_addresses', true);
$shipFields     = WC()->countries->get_address_fields( 'US', 'shipping_' );
$shipFields['shipping_country']['priority'] = '100';
// print_r($shipFields);
$updating       = false;
$idx            = -1;
$address        = array();

?>
<div id="modal20a-shipping-screen" class="modal" style="min-width:85%;">
    
    <div class="mspp_row">
        <div class="mspp_column" id="shipping_address_block">
            <div class="mspp_card">
            <div style="text-align:center;">
                <h4>Who Are You Shipping To?</h4>
            </div>
<?php 
    if ( is_user_logged_in() ) { 
    // Display Addresses if logged in user:
    echo "<div style='margin-bottom:30px;'><p class='popup-center';>Select a shipping address or add a new address from the dropdown.</p>";
    echo "<select id='mspp_addresses' name='mspp_addresses'>";
    		echo '<option value="selectaddress" selected>Select a Shipping Address</option>';
		foreach ( $addresses as $addr_key => $address ) {
			$formatted = $address['shipping_first_name'] .' '. $address['shipping_last_name'] .',';
			$formatted .= ' '. $address['shipping_address_1'] .' '. $address['shipping_address_2'] .',';
			$formatted .= ' '. $address['shipping_city'] .', '. $address['shipping_state'];

			echo '<option value="'. $addr_key .'" '. selected( $address_key, $addr_key ) .'>'. $formatted .'</option>';
			$selected = '';
		}
		echo '<option value="addnewaddress">Add New Address</option>';
		echo "</select>";
		echo "</div>";

    }
		?>
	<form method="post" class="mspp_addresses_form" id="mspp_addresses_form" style="display: <?php if (is_user_logged_in()) echo 'none'; else echo 'block'; ?>">
        <div id="addresses" class="address-column">
        <?php
        unset($shipFields['shipping_company']);
        unset($shipFields['shipping_address_2']);
        $shipFields['shipping_country']['label'] = "Country";
        
    // order the keys for your custom ordering or delete the ones you don't need
$shippingfieldsOrder=array(
    "shipping_first_name",
    "shipping_last_name",
    "shipping_address_1",
    "shipping_address_2",
    "shipping_city",
    "shipping_state",
    "shipping_postcode",
    "shipping_country",
);

$c = array();
foreach($shippingfieldsOrder as $index) {
    $c[$index] = $shipFields[$index];
}


// foreach ($mybillingfields as $key) :
//  woocommerce_form_field( $key, $shipFields['shipping'][$key], '' );
// endforeach;

        
        foreach ( $c as $key => $field ) :
            $val = '';

            woocommerce_form_field( $key, $field, $val );
        endforeach;

        ?>
        </div>
    <div class="form-row">
        <div class="inlinelabel">
<input type="checkbox" id="sendall_to_this_address" name="sendall_to_this_address" value="yes">
  <label for="sendall_to_this_address"> Will all products in this order go to the same recipient?</label>            
        </div>

        <input type="hidden" name="idx" value="<?php echo $idx; ?>" />
        <input type="hidden" name="shipping_account_address_action" value="save" />
        <input type="submit" name="set_addresses" id="mspp_set_address_btn" style="display:none;" value="<?php _e( 'Save Address', 'wc_shipping_multiple_address' ); ?>" class="button alt" />
    </div>
    </form>
        </div>
        </div>
      <!--Gift Message block and Gift Wrap Block - now moved to cart  -->
   <!--     <div class="mspp_column"  id="gift_message_block">-->
   <!--         <div class="mspp_card">-->
   <!--            <div style="text-align:center;">-->
   <!--             <h4>Add a Gift Message</h4>-->
   <!--         </div>-->
   <!--           <form method="post" class="mspp_gift_message_form" id="mspp_gift_message_form">-->
   <!--    <p>-->
   <!--     <label for="mspp_gift_message_from_field" class="">From name&nbsp;<abbr class="required"-->
   <!--             title="required">*</abbr></label>-->
   <!--             <input type="text" name="mspp_gift_message_from_field"-->
   <!--             id="mspp_gift_message_from_field" placeholder=""-->
   <!--             value="">-->
   <!--     </p>-->
        
   <!--         <p>-->
   <!--<label for="mspp_gift_message_field" class="">Gift Message&nbsp;</label>-->
   <!--             <textarea name="mspp_gift_message_field"-->
   <!--             id="mspp_gift_message_field" rows="4" cols="20"></textarea>-->
   <!--         </p>-->
   <!--     <p class='popup-center';>Gift messages will be displayed on the packing slip included in your shipment. <br>-->
   <!--     No prices will show on the packing slip.</p>-->
   <!--           </form>-->
   <!--         </div>-->
   <!--     </div>-->
  
   <!--     <div class="mspp_column" id="gift_sticker_block">-->
   <!--         <div class="mspp_card">-->
   <!--            <div style="text-align:center; display:block;">-->
   <!--             <h4>Add a Gift Sticker</h4>-->
   <!--         <img src="https://52eaststudio.com/wp-content/uploads/2021/06/5-Gift-Tag-Option.png" style="width: 200px; display:block; margin-left: auto; margin-right: auto;"/>-->
   <!--         <h4>$1</h4>-->
   <!--     <p class='popup-center';>Gift stickers are attached to the outside of the shipping box. <br>-->
   <!--     The To and From fields will be filled by hand.</p>-->
   <!--     </div>-->
   <!--         </div>-->
   <!--     </div>-->
        
</div>

<footer>
          <div class="mspp_card_action" style="text-align: right;">
          <?php 
        global $product;
        $productprice_plus_wrap = $product->get_price() + 1;
        
        $mspp_sprintf_addtocart_btn = sprintf( '<a href="#" id="mspp_address_and_addtocart_btn" rel="nofollow" data-addtocarturl="%s" data-product_id="%s" data-product_sku="%s" class="button %s product_type_%s">%s</a>',
        esc_url( $product->add_to_cart_url() ),
        esc_attr( $product->get_id() ),
        esc_attr( $product->get_sku() ),
        $product->is_purchasable() ? 'add_to_cart_button' : '',
        esc_attr( $product->get_type() ),
        esc_html( $product->add_to_cart_text() )
    );

//         // Add product to cart with the custom cart item data
// WC()->cart->add_to_cart( $product_id, '1', '0', array(), $custom_data );
        ?>
        <form id="mspp_productdata_form">
        <input type="hidden" name="mspp_product_id" value="<?php echo $idx; ?>" />
        <?php echo $mspp_sprintf_addtocart_btn; ?>
        </form>
<?php

// echo apply_filters( 'woocommerce_loop_add_to_cart_link',
//     sprintf( '<a href="#" id="mspp_shipme_add_to_cart" rel="nofollow" data-addtocarturl="%s" data-product_id="%s" data-product_sku="%s" class="button %s product_type_%s">%s</a>',
//         esc_url( $product->add_to_cart_url() ),
//         esc_attr( $product->get_id() ),
//         esc_attr( $product->get_sku() ),
//         $product->is_purchasable() ? 'add_to_cart_button' : '',
//         esc_attr( $product->get_type() ),
//         esc_html( $product->add_to_cart_text() )
//     ),
// $product );

?>
</div>
</footer>
    
</div>
