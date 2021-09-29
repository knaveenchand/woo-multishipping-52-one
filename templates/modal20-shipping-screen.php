<?php
$current_user   = wp_get_current_user();
$userid_to_pull = $current_user->ID;
$addresses      = get_user_meta($userid_to_pull, 'wc_other_addresses', true);
$shipFields     = WC()->countries->get_address_fields( 'US', 'shipping_' );
$updating       = false;
$idx            = -1;
$address        = array();

?>
<div id="modal20-shipping-screen" class="modal">
    <div style="text-align:center; margin-bottom:30px;"><h2>Who Are You Shipping To?</h2></div>
<?php 
    if ( is_user_logged_in() ) { 
    // Display Addresses if logged in user:
    echo "<div style='margin-bottom:30px;'><p>Select From Your Address Book Or Ship To A New Address</p><hr>";
    echo "<select id='mspp_addresses' name='mspp_addresses'>";
    		echo '<option value="selectaddress">Select a Shipping Address</option>';
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
        foreach ( $shipFields as $key => $field ) :
            $val = '';

            woocommerce_form_field( $key, $field, $val );
        endforeach;
        ?>
        </div>
    <div class="form-row">
        <input type="hidden" name="idx" value="<?php echo $idx; ?>" />
        <input type="hidden" name="shipping_account_address_action" value="save" />
        <input type="submit" name="set_addresses" id="mspp_set_address_btn" value="<?php _e( 'Save Address', 'wc_shipping_multiple_address' ); ?>" class="button alt" />
    </div>
    </form>
        <?php 
        global $product;

echo apply_filters( 'woocommerce_loop_add_to_cart_link',
    sprintf( '<a href="%s" id="mspp_shipme_add_to_cart" style="display:none;" rel="nofollow" data-product_id="%s" data-product_sku="%s" class="button %s product_type_%s">%s</a>',
        esc_url( $product->add_to_cart_url() ),
        esc_attr( $product->get_id() ),
        esc_attr( $product->get_sku() ),
        $product->is_purchasable() ? 'add_to_cart_button' : '',
        esc_attr( $product->get_type() ),
        esc_html( $product->add_to_cart_text() )
    ),
$product );
        ?>
</div>
