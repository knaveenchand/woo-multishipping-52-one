	<?php 
	
	foreach ($contents as $contents_lineitem_key => $contents_lineitem) {
	    
	    //address_assigned and giftbsinesspersonal are two cart item data
	    //they need to be inserted via a filter
	   // add_filter('woocommerce_add_cart_item_data','knc_add_cart_item_data_uniqueid',11,2) with this array sample:
	    //$cart_item_data['giftbusinesspersonal'] = 'gift';
	    //do this only when address assigned data and giftbusinesspersonal data are fully saved.

	    
	    if ( (!isset($contents_lineitem['address_assigned'])) || (!isset($contents_lineitem['giftbusinesspersonal'])) ) {
	        $new_assignment_workshop[]=$contents_lineitem_key;
	    } else {
	        
	        if (!empty($relations)) {
	            foreach ($relations as $sorting_relkey =>$sorting_relvalue) {
	                foreach ($sorting_relvalue as $sorting_relitem_key => $sorting_relitem_value) {
	           $giftbusinesspersonal = $contents[$sorting_relitem_value]['giftbusinesspersonal'];
	            
	            $sorted_relations[$sorting_relkey][$giftbusinesspersonal][$sorting_relitem_key] = $sorting_relitem_value;

	                }
         	    
	            	}

	        }


	    }
	}
	
	//get the table for new assignment workshop
?>

<?php 
if (!empty($new_assignment_workshop)) {
foreach ($new_assignment_workshop as $new_assgin_relkey => $new_assign_relvalue) { ?>

<form class="assignmentworkshop" id="assignment-<?php echo $new_assign_relkey; ?>" method="post" action="">
<table class="wc-shipping-multiple-addresses shop_table cart" cellspacing="0">
		<thead>
			<tr>
				<!--<th class="product-image"></th>-->
				<!--<th class="product-quantity"></th>-->
				<!--<th class="product-name"></th>-->
				<!--<th class="product-unitprice"></th>-->
				<!--<th class="product-totalprice"></th>-->
				<!--<th class="remove-item"></th>-->
				
				<th colspan="6">
				        Tell Us How To Ship This Product
				</th>
				<?php do_action('wc_ms_address_table_head'); ?>
		
			</tr>
		</thead>
		<tbody>
		    
		    <tr>
		        <td class="firstrowtd">
		            <?php 
		            $new_assignment_itemmeta = get_post_meta($contents[$new_assign_relvalue]['product_id']);
		        echo wp_get_attachment_image($new_assignment_itemmeta['_thumbnail_id'][0], 'full' ); 
		        ?>
		        </td>
		        
		        <td class="firstrowtd">
		            <?php
		            $selectedqty = $contents[$new_assign_relvalue]['quantity'];
		            ?>
		            <div class="qtynumber">
		                <span class="qtyminus">-</span>
		                <input type="text" class="qtycounter" name="qtycounter" value="<?php echo $selectedqty; ?>"/>
		                <span class="qtyplus">+</span>
		              </div>
		              <div>
		                  
		              </div>
		           
		        </td>
		        
		        <td class="firstrowtd">
		            <?php echo get_the_title($contents[$new_assign_relvalue]['product_id']); 
		            ?>
		        </td>
		        
		        <td class="firstrowtd"><span class="unitprice">
		            <?php 
		            $unitprice = ($contents[$new_assign_relvalue]['line_total']) / ($contents[$new_assign_relvalue]['quantity']);
		            echo wc_price($unitprice);
		            ?>
		            </span>
		        </td>
		        
		        <td class="firstrowtd">
		            <span class="linetotal">
		            <?php echo wc_price($contents[$new_assign_relvalue]['line_total']);?>
		            </span>
		        </td>
		        
				<td class="firstrowtd"><input type="submit" name="delete_line" class="button delete-line-item" data-key="<?php echo $key; ?>" data-index="<?php echo $x; ?>" value="<?php _e('x', 'wc_shipping_multiple_address'); ?>" /></td>

		    </tr>
		    
		    <tr>
		        <td colspan="2">
		           Select Recipient: 
		        </td>
		        <td colspan="4">
		          <select name="itemaddresskey" class="address-select">
		              
		              <option value='select-recipient'>Select</option>
					<?php
					
					$assigned_addresskey = $contents[$new_assign_relvalue]['assigned_addresskey'];
					
					foreach ( $addresses as $addr_key => $address ) {
						$formatted = $address['shipping_first_name'] .' '. $address['shipping_last_name'] .',';
						$formatted .= ' '. $address['shipping_address_1'] .' '. $address['shipping_address_2'] .',';
						$formatted .= ' '. $address['shipping_city'] .', '. $address['shipping_state'];

						echo '<option value="'. $addr_key .'" '. selected( $assigned_addresskey, $addr_key ) .'>'. $formatted .'</option>';
						$selected = '';
					}
					?>
					<option value='add-new-recipient'>Add New Recipient</option>
					</select>

		        </td>
		    </tr>
		    <tr>
		        <td colspan="2">
		            Select Package type: 
		        </td>
		        <td colspan="4">
		            <?php
		            $gift_img_url_workshop = "https://52eaststudio.com/wp-content/uploads/2021/07/mspp_giftboxicon.png";
    $business_img_url_workshop = "https://52eaststudio.com/wp-content/uploads/2021/07/mspp_shippingboxicon.png";
    $personal_img_url_workshop = "https://52eaststudio.com/wp-content/uploads/2021/07/mspp_happyfaceicon.png";
		            ?>
		            Gift - Business - Personal
		            <div id="thumb_images_<?php echo $new_assign_relvalue; ?>" class="gallerycontainer" >
<div class="packthumbnail" id="gift-<?php echo $new_assign_relvalue;?>" data-package="gift">
     <img src="https://52eaststudio.com/wp-content/uploads/2021/07/mspp_giftboxicon.png" class="mspptooltip-gift-assignment mspptooltip tooltipstered" width="75px" height="75px" border="0" />
</div>
<div class="packthumbnail" id="business-<?php echo $new_assign_relvalue;?>" data-package="business">
     <img src="https://52eaststudio.com/wp-content/uploads/2021/07/mspp_shippingboxicon.png" class="mspptooltip-business-assignment mspptooltip tooltipstered" width="75px" height="75px" border="0" />
</div>
<div class="packthumbnail" id="self-<?php echo $new_assign_relvalue;?>" data-package="self">
     <img src="https://52eaststudio.com/wp-content/uploads/2021/07/mspp_happyfaceicon.png" class="mspptooltip tooltipstered mspptooltip-self-assignment" width="75px" height="75px" border="0" />
</div>
		 <input type="hidden" name="giftbusinesspersonal" value="initial"/>

</div>
		        </td>
		    </tr>
            <tr>
		        <td colspan="6" class="lastrowtd">
		            <input type="hidden" name="knc_mspp_item" value="<?php echo $new_assign_relvalue; ?>"/>
		            <input type="hidden" name="knc_mspp_shipping_address_action" value="save" />
		            <div class="set-shipping-addresses">
		                <input class="button alt" type="submit" name="knc_mspp_set_addresses" value="Add to Recipient Package" />
		                </div>
		        </td>
		    </tr>		    
		</tbody>
	</table>	
</form>
<? 
} // foreach new_assignment_workshop
} // if new_assignment_workshop array is not empty
?>


	
	<?php 
	
$mspp_packages = WC()->cart->get_shipping_packages();
$mspp_shipping_packages = WC()->shipping->get_packages();

// echo "<br>Packages: <br>";
// print_r($mspp_packages);
// echo "<br>shipping packages<br>";
// print_r($mspp_shipping_packages);
			
// 	echo "<br>NEW ASSIGNMENT_WORKSHOP:<br>";
// 	print_r($new_assignment_workshop);
// 	echo "<br>ASSIGNMENT_WORKSHOP:<br>";
// 	print_r($assignment_workshop);
// 	echo "<br>SORTED_RELATIONS:<br>";
// 	print_r($sorted_relations);
// 	echo "<br>CONTENTS:<br>";
// 	print_r($contents);
// 	echo "<br>ADDRESSES:<br>";
// 	print_r($addresses);
// 	echo "<br>RELATIONS<br>";
// 	print_r($relations);
	?>

	
<?php 

if (!empty($sorted_relations)) {
     $packid = 1;
   
foreach ($sorted_relations as $sorted_relkey => $sorted_relvalue) { 

foreach ($sorted_relvalue as $sorted_relpackageskey => $sorted_relpackagesvalue) {
    
    $sorted_relpackagesvalue = array_unique($sorted_relpackagesvalue);
// echo "<br>UNIQUE SORTED RELVALUE <br>";
// print_r($sorted_relpackagesvalue);
    
    $gift_img_url = "https://52eaststudio.com/wp-content/uploads/2021/07/mspp_giftboxicon.png";
    $business_img_url = "https://52eaststudio.com/wp-content/uploads/2021/07/mspp_shippingboxicon.png";
    $personal_img_url = "https://52eaststudio.com/wp-content/uploads/2021/07/mspp_happyfaceicon.png";
    if ($sorted_relpackageskey == "gift") {
        $pack_img_url = $gift_img_url;
    } 
    if ($sorted_relpackageskey == "business") {
        $pack_img_url = $business_img_url;
    } 
    if ($sorted_relpackageskey == "personal") {
        $pack_img_url = $personal_img_url;
    } 
    $displayrecipient_name = $addresses[$sorted_relkey]['shipping_first_name'] .", " .$addresses[$sorted_relkey]['shipping_last_name'];
    
    $displayrecipient_address = $addresses[$sorted_relkey]['shipping_address_1'] .", ".$addresses[$sorted_relkey]['shipping_address_2'] .", ".$addresses[$sorted_relkey]['shipping_city'] .", " .$addresses[$sorted_relkey]['shipping_state'] .", " .$addresses[$sorted_relkey]['shipping_postcode'];
    
    ?>
    
<form class="assignmentworkshop" id="sorted-<?php echo $sorted_relkey; ?>" method="post" action="">
    <fieldset>
        <legend>Package # <?php echo $packid; ?> <img src='<?php echo $pack_img_url; ?>' style="width:20px; display:inline;" class="mspptooltip" id="mspptooltip-gift"></legend>
        <div><div style="border: 1px solid #F5F5F3; background-color: #F5F5F3; min-height: 45px; min-width:90px; float:left;"><div style="font-size:16px; float:left; margin: 10px;"><?php echo $displayrecipient_name; ?></div><div style="font-size:12px; float:left; margin: 10px;"><?php echo $displayrecipient_address; ?></div></div><button>Edit</button style="float:left;"></div>
 <table class="wc-shipping-multiple-addresses shop_table cart" cellspacing="0">
		<tbody>
		    <?php foreach ($sorted_relpackagesvalue as $relpackages_item_key => $relpackages_item_value) { ?>
		    <tr>
		        <td>
		            <?php 
		            $new_sorted_itemmeta = get_post_meta($contents[$relpackages_item_value]['product_id']);
		            $thumbnailimg_attachment_id = get_post_thumbnail_id( $contents[$relpackages_item_value]['product_id']);
$url = wp_get_attachment_image_src($thumbnailimg_attachment_id);

if ( $url ) : ?>
    <img src="<?php echo $url[0]; ?>" width="50px" />
<?php endif; ?>
		  
		  <td>	            
		      <select name="items[<?php echo $relpackages_item_value; ?>][qty][]">
		            <?php 
		            for($i=1; $i<=10; $i++)
		            {
		                if ($i ==  $contents[$relpackages_item_value]['quantity']) {
		                echo "<option value=".$i." selected>".$i."</option>";
		                    
		                } else {
		                echo "<option value=".$i.">".$i."</option>";
		                }
		            }

		            ?>
		            
		        </select>
	</td>
		            
		        <td>
		            <?php 
		            echo get_the_title($contents[$relpackages_item_value]['product_id']); ?> </td>
		        <td>
		            <?php 
		            $unitprice = ($contents[$relpackages_item_value]['line_total']) / ($contents[$relpackages_item_value]['quantity']);
		            echo wc_price($unitprice);
		            ?>
		        </td>
		        <td>
		           <?php 
		            echo wc_price($contents[$relpackages_item_value]['line_total']);
		            ?>
		        </td>
		        <td>
		           [x]
		        </td>
		    </tr>
		    <?php } ?>

		</tbody>
		    </table>
    </fieldset>
</form>
<?php $packid++; ?>
    
<?php
} //end foreach sorted_relvalue

 } //end foreach sorted_relations loop 
 ?>
 <form method="post" action="" id="proceed_to_checkout">
 <input type="submit" name="mspp_proceed_to_checkout" class="button" value="Proceed to Checkout" />
 </form>
 
<?php
 
} //if not empty
?>

<form method="post" action="" id="address_form">

	<?php

	// set the address fields
	foreach ( $addresses as $x => $addr ) {
		if ( empty( $addr ) )
			continue;

		$address_fields = WC()->countries->get_address_fields( $addr['shipping_country'], 'shipping_' );

		$address = array();
		$formatted_address = false;

		foreach ( $address_fields as $field_name => $field ) {
			$addr_key = str_replace('shipping_', '', $field_name);
			$address[$addr_key] = ( isset($addr[$field_name]) ) ? $addr[$field_name] : '';
		}

		if (! empty($address) ) {
			$formatted_address = wcms_get_formatted_address( $address );
			$json_address      = wp_json_encode( $address );
			$json_address      = function_exists( 'wc_esc_json' ) ? wc_esc_json( $json_address ) : _wp_specialchars( $json_address, ENT_QUOTES, 'UTF-8', true );
		}

		if ( ! $formatted_address )
			continue;
		?>
		<div style="display: none;">
			<?php
			do_action('woocommerce_after_checkout_shipping_form', $checkout);
			?>
		<input type="hidden" name="addresses[]" value="<?php echo $x; ?>" />
		<textarea style="display:none;"><?php echo $json_address; ?></textarea>
		</div>
		<?php
	}
	$ms_settings = get_option( 'woocommerce_multiple_shipping_settings', array() );

	$add_url = add_query_arg( 'address-form', '1' );
	?>

	<div>
		<a class="h2-link" href="<?php echo $add_url; ?>"><?php _e('Add a new shipping address', 'wc_shipping_multiple_address'); ?></a>

		<?php
		if ( isset($ms_settings['cart_duplication']) && $ms_settings['cart_duplication'] != 'no' ):
			$dupe_url = add_query_arg( 'duplicate-form', '1' );
		?>
			<div style="float: right;">
				<a class="h2-link" href="<?php echo $dupe_url; ?>"><?php _e('Duplicate Cart', 'wc_shipping_multiple_address'); ?></a>
				<img class="help_tip" title="<?php _e('Duplicating your cart will allow you to ship the exact same cart contents to multiple locations. This will also increase the price of your purchase.', 'wc_shipping_multiple_address'); ?>" src="<?php echo WC()->plugin_url(); ?>/assets/images/help.png" height="16" width="16">
			</div>
		<?php
		endif;
		?>
	</div>
	


	<table class="wc-shipping-multiple-addresses shop_table cart" cellspacing="0" style="display:none;">
		<thead>
			<tr>
				<th class="product-name" width="20%"><?php _e( 'Product', 'wc_shipping_multiple_address' ); ?></th>
				<th class="product-quantity" width="20%"><?php _e( 'Quantity', 'wc_shipping_multiple_address' ); ?></th>
				<?php do_action('wc_ms_address_table_head'); ?>
				<th class="shipping-address" width="30%"><?php _e( 'Shipping Address', 'wc_shipping_multiple_address' ); ?></th>
				<th class="remove-item" width="20%">&nbsp;</th>
			</tr>
		</thead>
		<tbody>
		<?php

		foreach ($contents as $key => $value):
			$_product   = $value['data'];
			$pid        = $value['product_id'];

			if (! $_product->needs_shipping() ) continue;

			for ( $x = 0; $x < $value['quantity']; $x++ ):
		?>
			<tr>
				<td>
				<?php
				echo apply_filters( 'wcms_product_title', get_the_title($value['product_id']), $value );

				if ( defined( 'WC_VERSION' ) && version_compare( WC_VERSION, '3.0.0', '>' ) ) {
					if ( is_array( $value['variation'] ) && ! empty( $value['variation'] ) ) {
						echo ' (' . implode( ',',  array_values( $value['variation'] ) ) . ') ';
					}
				} else {
					echo WC_MS_Compatibility::get_item_data( $value );
				}
				?>
				</td>
				<td>
					<?php

					//$qty = array_count_values($relations[$x]);
					$product_quantity = woocommerce_quantity_input( array(
							'input_name'  => "items[{$key}][qty][]",
							'input_value' => 1,
							'max_value'   => $_product->backorders_allowed() ? '' : $_product->get_stock_quantity(),
						), $_product, false );
					echo $product_quantity;
					?>
				</td>
				<?php
				$address_key = "";
				$option_selected = false;
				foreach ( $addresses as $addr_key => $address ) {
					if ( !$option_selected && isset($relations[ $addr_key ]) ) {
						$rel_key = array_search( $key, $relations[ $addr_key ] );
						if ( $rel_key !== false ) {
							$option_selected = true;
							$address_key = $addr_key;
							unset( $relations[ $addr_key ][ $rel_key ] );
						}
					}
				}
				do_action( 'wc_ms_multiple_address_table_row', $key, $value, $address_key );
				?>
				<td>
					<select name="items[<?php echo $key; ?>][address][]" class="address-select">
					<?php

					foreach ( $addresses as $addr_key => $address ) {
						$formatted = $address['shipping_first_name'] .' '. $address['shipping_last_name'] .',';
						$formatted .= ' '. $address['shipping_address_1'] .' '. $address['shipping_address_2'] .',';
						$formatted .= ' '. $address['shipping_city'] .', '. $address['shipping_state'];

						echo '<option value="'. $addr_key .'" '. selected( $address_key, $addr_key ) .'>'. $formatted .'</option>';
						$selected = '';
					}
					?>
					</select>

				</td>
				<td><input type="submit" name="delete_line" class="button delete-line-item" data-key="<?php echo $key; ?>" data-index="<?php echo $x; ?>" value="<?php _e('x', 'wc_shipping_multiple_address'); ?>" /></td>
			</tr>
		<?php
			endfor;
		endforeach;
		?>
		</tbody>
	</table>

	<div class="form-row">
		<input type="hidden" name="delete[index]" id="delete_index" value="" />
		<input type="hidden" name="delete[key]" id="delete_key" value="" />
		<input type="hidden" name="shipping_type" value="item" />
		<input type="hidden" name="shipping_address_action" value="save" />

		<div class="update-shipping-addresses">
			<input type="submit" name="update_quantities" class="button" value="<?php _e('Update', 'wc_shipping_multiple_address'); ?>" />
		</div>

		<div class="set-shipping-addresses">
			<input class="button alt" type="submit" name="set_addresses" value="<?php echo __('Save Addresses and Continue', 'wc_shipping_multiple_address'); ?>" />
		</div>

	</div>

	<div class="clear"></div>

	<small>
		<?php _e('Please note: To send a single item to more than one person, you must change the quantity of that item to match the number of people you\'re sending it to, then click the Update button.', 'wc_shipping_multiple_address'); ?>
	</small>

</form>
<?php if ( $user->ID == 0 ): ?>
	<div id="address_form_template" style="display: none;">
		<form id="add_address_form">
			<div class="shipping_address address_block" id="shipping_address">
				<?php do_action('woocommerce_before_checkout_shipping_form', $checkout); ?>

				<div class="address-column">
					<?php
					foreach ($shipFields as $key => $field) :
						$val    = '';
						$key    = 'address['. $key .']';
						$id     = rtrim( str_replace( '[', '_', $key ), ']' );
						$field['return'] = true;

						echo str_replace( 'id="'. $key .'"', 'id="'. $id .'"', woocommerce_form_field( $key, $field, $val ) );
					endforeach;

					do_action('woocommerce_after_checkout_shipping_form', $checkout);
					?>
					<input type="hidden" name="id" id="address_id" value="" />
				</div>

			</div>

			<input type="hidden" name="return" value="list" />
			<input type="submit" class="button" id="use_address" value="<?php _e('Use this address', 'wc_shipping_multiple_address'); ?>" />
		</form>
	</div>
<?php else: ?>
	<div id="address_form_template" style="display: none;">
		<form id="add_address_form">
			<div class="shipping_address address_block" id="shipping_address">
				<?php do_action('woocommerce_before_checkout_shipping_form', $checkout); ?>

				<div class="address-column">
					<?php
					foreach ($shipFields as $key => $field) :
						$val = '';
						$key = 'address['. $key .']';

						woocommerce_form_field( $key, $field, $val );
					endforeach;

					do_action('woocommerce_after_checkout_shipping_form', $checkout);
					?>
				</div>
			</div>

			<input type="hidden" name="return" value="list" />
			<input type="submit" id="save_address" class="button" value="<?php _e('Save Address', 'wc_shipping_multiple_address'); ?>" />
		</form>
	</div>
<?php endif; ?>
