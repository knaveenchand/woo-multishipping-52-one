<?php
/**
 * Plugin Name: Multishipping in Product Page
 * Plugin URI: https://parishkaar.com/
 * Description: Set multiple shipping in product page and then add items of that product page to cart. Uses Woocommerce Multiple shipping plugin
 * Version: 1.0
 * Author: Naveen Chand K
 * Author URI: https://www.parishkaar.com
 * Woo: 12345:342928dfsfhsf8429842374wdf4234sfd
 * WC requires at least: 4.4
 * WC tested up to: 5.4.1
 * License: GPL v2 or later
 * License URI: https://www.gnu.org/licenses/gpl-2.0.html
 */

    // Start the session
session_start();

if ( ! defined( 'ABSPATH' ) ) {
    exit; // Exit if accessed directly
}

/**
 * Check if WooCommerce is active
 **/
if ( in_array( 'woocommerce/woocommerce.php', apply_filters( 'active_plugins', get_option( 'active_plugins' ) ) ) ) {
    
function mspp_enqueue_style() {
    wp_enqueue_style( 'mspp-styles', $mspp_client_css, false );
}


/** add woocommerce templates order it in your plugin
 * this is to create a custom cart and checkout templates
 * */
 add_filter( 'woocommerce_locate_template', 'knc_addon_plugin_template', 1, 3 );
   function knc_addon_plugin_template( $template, $template_name, $template_path ) {
     global $woocommerce;
     $_template = $template;
     if ( ! $template_path ) 
        $template_path = $woocommerce->template_url;
 
     $plugin_path  = untrailingslashit( plugin_dir_path( __FILE__ ) )  . '/template/woocommerce/';
 
    // Look within passed path within the theme - this is priority
    $template = locate_template(
    array(
      $template_path . $template_name,
      $template_name
    )
   );
 
   if( ! $template && file_exists( $plugin_path . $template_name ) )
    $template = $plugin_path . $template_name;
 
   if ( ! $template )
    $template = $_template;

   return $template;
}

//editing multishipping plugin template
add_filter( 'wc_get_template', 'knc_get_template', 10, 5 );
function knc_get_template( $located, $template_name, $args, $template_path, $default_path ) {    
    if ( 'shipping-address-table.php' == $template_name ) {
        $located = plugin_dir_path( __FILE__ ) . 'template/multi-shipping/shipping-address-table.php';
    }
    
    return $located;
}

//add unique key to each line item in the cart so we can use this later to add additional data

function knc_add_cart_item_data_uniqueid ( $cart_item_data, $product_id ) {
		  $distinctive_cart_item_key = md5( microtime() . rand() );
		  $cart_item_data['knc_distinctive_key'] = $distinctive_cart_item_key;
		  //TODO: implement capture of giftbusinesspersonal session variable
		  //$cart_item_data['giftbusinesspersonal'] = 'gift';
		  return $cart_item_data;
		}
add_filter('woocommerce_add_cart_item_data','knc_add_cart_item_data_uniqueid',11,2);


 
function mspp_enqueue_script() {
    
//     <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.js"></script>
// <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/jquery-modal/0.9.1/jquery.modal.min.css" />

    
  //my plugin scripts
    $mspp_client_js = plugin_dir_url( __FILE__ ) . 'mspp-client-js.js';
    $mspp_client_css = plugin_dir_url( __FILE__ ) . 'mspp-client-css.css';
    $mspp_jquerymodal_js = plugin_dir_url( __FILE__ ) .'jquerymodal/jquerymodal.js';
    $mspp_jquerymodal_css = plugin_dir_url( __FILE__ ) .'jquerymodal/jquerymodal.css';
    $mspp_client_tooltip_js = plugin_dir_url(__FILE__) .'mspp-client-tooltip.js';
    $mspp_tooltip_js = plugin_dir_url(__FILE__) .'tooltip/js/tooltipster.bundle.min.js';
    $mspp_tooltip_css = plugin_dir_url(__FILE__) .'tooltip/css/tooltipster.bundle.min.css';

    $mspp_tooltip_theme_css_noir = plugin_dir_url(__FILE__) .'tooltip/css/plugins/tooltipster/sideTip/themes/tooltipster-sideTip-noir.min.css';
    
    // $mspp_jquerymodal_js = plugin_dir_url( __FILE__ ) .'zebradialog/zebra_dialog.src.js';
    // $mspp_jquerymodal_css = plugin_dir_url( __FILE__ ) .'zebradialog/css/materialize/zebra_dialog.css';
    
    
    //enqueue style
    wp_enqueue_style( 'mspp-styles', $mspp_client_css, false );
    wp_enqueue_style('mspp-jquerymodal-styles', $mspp_jquerymodal_css, array(), '0.1.0', 'all');
    wp_enqueue_style('mspp-tooltip-styles', $mspp_tooltip_css, array(), '0.1.0', 'all');
    wp_enqueue_style('mspp-tooltip-theme-noir', $mspp_tooltip_theme_css_noir, array('mspp-tooltip-styles'), '0.1.0', 'all');

    
    //enqueue script
    wp_enqueue_script( 'mspp-js', $mspp_client_js, array('jquery'), '1.0' , true );
    wp_enqueue_script( 'mspp-jquerymodal-js', $mspp_jquerymodal_js, array('jquery'), '1.0' , true );
    wp_enqueue_script( 'mspp-tooltip-js', $mspp_tooltip_js, array('jquery'), '1.0' , false );
    wp_enqueue_script( 'mspp-client-tooltip-js', $mspp_client_tooltip_js, array('mspp-tooltip-js'), '1.0' , false );
    
	/** AJAX part: 
	 * The wp_localize_script allows us to output the ajax_url path for our 
	 * script to use.
	 * IMPORTANT! wp_localize_script() MUST be called after the script it is 
	 * being attached to has been registered using wp_register_script() or 
	 * wp_enqueue_script().
	 * And the handle must be the same (mspp-js):
	 * lets begin 
	 **/
	 
	 global $post;
	 
    wp_localize_script( 'mspp-js', 'msppfrontendajax', array( 'ajaxurl' => admin_url( 'admin-ajax.php' ), 'postID' => $post->ID ));

}
 
add_action( 'wp_enqueue_scripts', 'mspp_enqueue_script' );

//function to fetch shipping addresses
add_action( 'wp_ajax_mspp_setsession_checkoutas', 'mspp_setsession_checkoutas' );
add_action( 'wp_ajax_nopriv_mspp_setsession_checkoutas', 'mspp_setsession_checkoutas' );

function mspp_setsession_checkoutas(){
    $setsession_checkoutas = $_POST['setsession_checkoutas'];
    if ($setsession_checkoutas === "guest") {
    // $guestlogin_enabled = true;
    
    // if ( ! WC()->session->has_session() ) {
    //     WC()->session->set_customer_session_cookie( true );
    // }
    // WC()->session->set('guest_login_enabled', 'true');
    // $guest_login_preference = WC()->session->get( 'guest_login_enabled' );
    

// Set session variables
$_SESSION["guest_php_session"] = "true";

    
    echo json_encode("checkout as guest for this entire session is set as: ".  $_SESSION["guest_php_session"]);
    } else {
    echo json_encode("something went wrong");
    }
    wp_die();
}


//function to fetch shipping addresses
add_action( 'wp_ajax_mspp_save_address_addtocart', 'mspp_save_address_addtocart' );
add_action( 'wp_ajax_nopriv_mspp_save_address_addtocart', 'mspp_save_address_addtocart' );


function mspp_save_address_addtocart(){
    $address_is_new = $_POST['address_is_new'];
    $newaddress_formdata = $_POST['newaddress_formdata'];
    $productid = $_POST['productid'];
    
// check if user is guest 
if ($_SESSION["guest_php_session"] == "true") {
    // here is a guest user, let us add the product to cart first
    // and then add his address to cart session
    
    //first step: add to cart by getting the product id
    // global $product;
    // $currentproductid = $product->get_id();
    // WC()->cart->add_to_cart( $productid );
    
    if ( WC()->cart->add_to_cart( $productid ) ) {

			do_action( 'woocommerce_ajax_added_to_cart', $productid );

			if ( get_option( 'woocommerce_cart_redirect_after_add' ) == 'yes' ) {
				wc_add_to_cart_message( $productid );
			}
			
// $data = apply_filters( 'wc_add_to_cart_message', $message, $productid );

wc_add_notice( sprintf(
                '<a href="%s" class="button wc-forward">%s</a> %s' ,
                wc_get_cart_url(),
                __("View cart", "woocommerce"),
                __("Product has been added to your cart", "woocommerce")
            ), 'success' );
// wc_print_notices(); // Return printed notices to jQuery response.


} else {

			header( 'Content-Type: application/json; charset=utf-8' );

			// If there was an error adding to the cart, redirect to the product page to show any errors
			$data = array(
				'error' => true,
				'product_url' => apply_filters( 'woocommerce_cart_redirect_after_error', get_permalink( $productid ), $productid )
			);

// 			$woocommerce->set_messages();

			echo json_encode( $data );
		}

} else {
    // if user is logged in
   if ($address_is_new) {
        $idx = -1; // new address
        // $ret = mspp_save_address($newaddress_formdata, $idx);
    }
    if ($address_is_new == false){
        $idx = $newaddress_formdata;
        $ret = "address is not new. need to add to cart.";
    }    
}
    
    // echo json_encode("hello ");
    // die();
    
			// Return fragments
WC_AJAX::get_refreshed_fragments();
wp_die();


}

 /**
 * Add fragments for notices.
 */

function ajax_add_to_cart_add_fragments( $fragments ) {
    $all_notices  = WC()->session->get( 'wc_notices', array() );
    $notice_types = apply_filters( 'woocommerce_notice_types', array( 'error', 'success', 'notice' ) );

    ob_start();
    foreach ( $notice_types as $notice_type ) {
        if ( wc_notice_count( $notice_type ) > 0 ) {
            wc_get_template( "notices/{$notice_type}.php", array(
                'notices' => array_filter( $all_notices[ $notice_type ] ),
            ) );
        }
    }
    $fragments['notices_html'] = ob_get_clean();

    wc_clear_notices();

    return $fragments;
}
add_filter( 'woocommerce_add_to_cart_fragments', 'ajax_add_to_cart_add_fragments' );


function mspp_save_address($newaddress_formdata, $idx){

$params = array();
parse_str($newaddress_formdata, $params);

    $user       = wp_get_current_user();
	$updating   = false;
// 	$idx        = -1; //new address
	$address    = array();

    $addresses = get_user_meta( $user->ID, 'wc_other_addresses', true );

            if ( !is_array( $addresses ) ) {
                $addresses = array();
            }

            if ( $idx == -1 ) {
                $idx = count( $addresses );

                while ( array_key_exists( $idx, $addresses ) ) {
                    $idx++;
                }
            }

    $addresses[$idx]['shipping_first_name'] = $params['shipping_first_name'];
    $addresses[$idx]['shipping_last_name'] = $params['shipping_last_name'];
    $addresses[$idx]['shipping_company'] = $params['shipping_company'];
    $addresses[$idx]['shipping_country'] = $params['shipping_country'];
    $addresses[$idx]['shipping_address_1'] = $params['shipping_address_1'];
    $addresses[$idx]['shipping_address_2'] = $params['shipping_address_2'];
    $addresses[$idx]['shipping_city'] = $params['shipping_city'];
    $addresses[$idx]['shipping_state'] = $params['shipping_state'];
    $addresses[$idx]['shipping_postcode'] = $params['shipping_postcode'];
           

            update_user_meta( $user->ID, 'wc_other_addresses', $addresses );
return "new address added to address book.";
}

function mspp_get_addresses(){
$hello ="hello world";
echo json_encode($hello);
wp_die();
}

// $addresses_pulled = pull_user_addresses();

function pull_user_addresses(){
        if (class_exists('WC_MS_Address_Book')) {
      //do stuff that depends of WC_MS_Address_Book
    $current_user = wp_get_current_user();
    $userid_to_pull = $current_user->ID;
    $ret = get_user_addresses($userid_to_pull,true);    
    }
        return $ret;
}

//add_action('woocommerce_product_meta_start', 'myproductpagefunction');
function myproductpagefunction(){
    $current_user = wp_get_current_user();
    $userid_to_pull = $current_user->ID;
    
 $addresses = get_user_meta($userid_to_pull, 'wc_other_addresses', true);

ob_start();
    ?>
    <div id='placeorderdiv'>
        <table id='placeordertable'>
            <tr>
                <th>Quantity</th>
                <th>Ship to</th>
                <th>Gift Pack</th>
                <th></th>
            </tr>
            <tr>
                <td>
                    <select name="mspp_qty" id="mspp_qty">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    </select>
                </td>
                <td>
                   
                    <select name="mspp_addresses_1" id="mspp_addresses_z">
					<?php

					foreach ( $addresses as $addr_key => $address ) {
						$formatted = $address['shipping_first_name'] .' '. $address['shipping_last_name'] .',';
						$formatted .= ' '. $address['shipping_address_1'] .' '. $address['shipping_address_2'] .',';
						$formatted .= ' '. $address['shipping_city'] .', '. $address['shipping_state'];

						echo '<option value="'. $addr_key .'" '. selected( $address_key, $addr_key ) .'>'. $formatted .'</option>';
						$selected = '';
					}
					?>
                </td>
                <td>
                    <input type="checkbox" name="mspp_gift" value="Gift">
                    <label for="mspp_gift"> This is a gift.</label><br>
                </td>
                <td>
                </td>
            </tr>
        </table>
        <table id='addnewrowtable'>
            <tr>
                <td>
                    <span id='addnewrow'><i class="fa fa-plus" aria-hidden="true"></i> Add New Row </span>
                </td>
            </tr>
        </table>
        <button id='placeorderbtn' type='button'>Place Order</button>
        </div>
    <?php
    $getallhtml = ob_get_clean();
    echo $getallhtml;
}

/**
 * Function to add new shipping fields
 **/
 function mssp_add_new_address(){
    $user       = wp_get_current_user();
	$shipFields = WC()->countries->get_address_fields( 'US', 'shipping_' );
	$updating   = false;
	$idx        = -1;
	$address    = array();
	ob_start();
	?>
	<form class="mspp_addresses_form">
        <div id="addresses" class="address-column">
        <input type="text" name="xidx" value="<?php echo $idx; ?>" />

        <?php
        foreach ( $shipFields as $key => $field ) :
            $val = '';

            woocommerce_form_field( $key, $field, $val );
        endforeach;
        ?>
        </div>
    <div class="form-row">
        <input type="hidden" name="idx" value="<?php echo $idx; ?>" />
        <input type="hidden" name="mspp_shipping_account_address_action" value="save" />
        <input type="submit" name="mspp_set_addresses" value="<?php _e( 'Save Address', 'wc_shipping_multiple_address' ); ?>" class="button alt" />
    </div>
    </form>
<?php
$getformhtml = ob_get_clean();
echo $getformhtml;

 }

//add a field to the product page:
/**
 * Add a custom text input field to the product page
 */
function plugin_republic_add_text_field() { 
ob_start();
	include(plugin_dir_path( __FILE__ ) . 'templates/shipping-front-table.php');
	include(plugin_dir_path( __FILE__ ) . 'templates/modal10-login-options-screen.php');
	include(plugin_dir_path( __FILE__ ) . 'templates/modal11-loginform.php');
	include(plugin_dir_path( __FILE__ ) . 'templates/modal12-registerform.php');
// 	include(plugin_dir_path( __FILE__ ) . 'templates/modal20-shipping-screen.php');
// 	include(plugin_dir_path( __FILE__ ) . 'templates/modal20a-shipping-screen.php');
	include(plugin_dir_path( __FILE__ ) . 'templates/modal20b-shipping-screen.php');
	include(plugin_dir_path( __FILE__ ) . 'templates/modal30-gift-message-screen.php');
	include(plugin_dir_path( __FILE__ ) . 'templates/modal40-gift-wrap-screen.php');
	
	$includedhtml = ob_get_contents();
ob_end_clean();
	echo $includedhtml;
}
// add_action( 'woocommerce_before_add_to_cart_button', 'plugin_republic_add_text_field' );

// this action adds the table
// add_action( 'woocommerce_before_add_to_cart_form', 'plugin_republic_add_text_field' );

// woocommerce_after_add_to_cart_quantity

/**
 * Validate our custom text input field value
 */
function plugin_republic_add_to_cart_validation( $passed, $product_id, $quantity, $variation_id=null ) {
 if( $_POST['mspp_addresses'] == "")  {
 $passed = false;
 wc_add_notice( __( 'Select shipping address before adding to cart.', 'plugin-republic' ), 'error' );
 }
 return $passed;
}
// add_filter( 'woocommerce_add_to_cart_validation', 'plugin_republic_add_to_cart_validation', 10, 4 );

/**
 * Add custom cart item data
 */
function plugin_republic_add_cart_item_data( $cart_item_data, $product_id, $variation_id ) {
 if( isset( $_POST['mspp_addresses'] ) ) {
 $cart_item_data['mspp_addresses'] = sanitize_text_field( $_POST['mspp_addresses'] );
 }
 return $cart_item_data;
}
add_filter( 'woocommerce_add_cart_item_data', 'plugin_republic_add_cart_item_data', 10, 3 );

}

/** functions from woocommerce shipping multiple addresses plugin 
 * to save addresses against the item on submitting the form 
 * from shipping-addresses page
 * */
 
 //function 1: get real cart items
 
 function mspp_wcms_get_real_cart_items() {

    $items = array();

    foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {

        if ( !$cart_item['data']->needs_shipping() )
            continue;

        if ( isset($cart_item['bundled_by']) && !empty($cart_item['bundled_by']) )
            continue;

        if ( isset($cart_item['composite_parent']) && !empty($cart_item['composite_parent']) )
            continue;

        $items[$cart_item_key] = $cart_item;
    }

    return $items;
}
 
 //function 2: session functions... set, isset, delete
 
 function mspp_wcms_session_get( $name ) {

    if ( isset( WC()->session ) ) {
        // WC 2.0
        if ( isset( WC()->session->$name ) ) return WC()->session->$name;
    } else {
        // old style
        if ( isset( $_SESSION[ $name ] ) ) return $_SESSION[ $name ];
    }

    return null;
}

function mspp_wcms_session_isset( $name ) {

    if ( isset(WC()->session) ) {
        // WC 2.0
        return (isset( WC()->session->$name ));
    } else {
        return (isset( $_SESSION[$name] ));
    }
}

function mspp_wcms_session_set( $name, $value ) {

    if ( isset( WC()->session ) ) {
        // WC 2.0
        unset( WC()->session->$name );
        WC()->session->$name = $value;
    } else {
        // old style
        $_SESSION[ $name ] = $value;
    }
}

function mspp_wcms_session_delete( $name ) {

    if ( isset( WC()->session ) ) {
        // WC 2.0
        unset( WC()->session->$name );
    } else {
        // old style
        unset( $_SESSION[ $name ] );
    }
}
 
//function 3: getting user addresses
   function mspp_get_user_addresses( $user ) {
        if (! $user instanceof WP_User ) {
            $user = new WP_User( $user );
        }

        if ($user->ID != 0) {
            $addresses = get_user_meta($user->ID, 'wc_other_addresses', true);

            if (! $addresses) {
                $addresses = array();
            }

	       // if ( $include_default ) {
		      //  $default_address = $this->get_user_default_address( $user->ID );

		      //  if ( $default_address['address_1'] && $default_address['postcode'] ) {
			     //   $addresses += array( $default_address );
		      //  }
	       // }
        } else {
            // guest address - using sessions to store the address
            $addresses = ( mspp_wcms_session_isset('user_addresses') ) ? mspp_wcms_session_get('user_addresses') : array();
        }

        return mspp_array_sort( $addresses, 'shipping_first_name' );
    }

//arraysort function for above function

    function mspp_array_sort($array, $on, $order=SORT_ASC) {
        $new_array = array();
        $sortable_array = array();

        if (count($array) > 0) {
            foreach ($array as $k => $v) {
                if (is_array($v)) {
                    foreach ($v as $k2 => $v2) {
                        if ($k2 == $on) {
                            $sortable_array[$k] = $v2;
                        }
                    }
                } else {
                    $sortable_array[$k] = $v;
                }
            }

            switch ($order) {
                case SORT_ASC:
                    asort( $sortable_array, SORT_NATURAL | SORT_FLAG_CASE );
                    break;
                case SORT_DESC:
                    arsort( $sortable_array, SORT_NATURAL | SORT_FLAG_CASE  );
                    break;
            }

            foreach ($sortable_array as $k => $v) {
                $new_array[$k] = $array[$k];
            }
        }

        return $new_array;
    }

//clear cache transients

function mspp_clear_packages_cache() {

			WC()->cart->calculate_totals();
			$packages = WC()->cart->get_shipping_packages();

			foreach ( $packages as $idx => $package ) {
				$package_hash = 'wc_ship_' . md5( wp_json_encode( $package ) );
				delete_transient( $package_hash );
			}
		}

//saving from custom cart
function knc_mspp_save_addresses() {
    
    $mcart_items = mspp_wcms_get_real_cart_items();
    
    // if(empty($cart_items)) {
    //     mspp_wcms_session_delete('address_relationships');
    //     mspp_wcms_session_delete('cart_item_addresses');
    //     mspp_wcms_session_delete('wcms_item_address');
    // }
    
    //check of knc_mspp_shipping_address_action is set and is equal to save inside the form save button
    if (isset($_POST['knc_mspp_shipping_address_action']) && $_POST['knc_mspp_shipping_address_action'] == 'save' ) {
        
        $cart       = WC()->cart;
        $checkout   = WC()->checkout;

        $user_addresses = mspp_get_user_addresses( get_current_user_id() );
        $fields = WC()->countries->get_address_fields( WC()->countries->get_base_country(), 'shipping_' );
        
            //         mspp_wcms_session_set( 'cart_item_addresses', $data );
            // mspp_wcms_session_set( 'address_relationships', $rel );
        $data   = array();
        $rel    = array();
        
                $itemaddresskey = $_POST['itemaddresskey'];
                $knc_mspp_item = $_POST['knc_mspp_item'];
                $qtycounter = $_POST['qtycounter'];
                $giftbusinesspersonal = $_POST['giftbusinesspersonal'];
                
                // print_r($itemaddresskey);
                // echo("<br>");
                // print_r($knc_mspp_item);
                // echo("<br>");
                // print_r($qtycounter);
                // echo("<br>");
                // print_r($giftbusinesspersonal);
                // echo("<br>");
        
        //check if address is set and package type is set. if not return
        if (($itemaddresskey == "select-recipient") || ($itemaddresskey == "add-new-recipient") || ($giftbusinesspersonal == "initial")) {
                    return;
                }
                
        //check if product id and variation id is same as previously updated product.
        
        $thisproduct_id = $mcart_items[$knc_mspp_item]['product_id'];
        $thisvariation_id = $mcart_items[$knc_mspp_item]['variation_id'];
        
        foreach ($mcart_items as $matchitemkey => $matchitem) {
            
            if ( ($mcart_items[$matchitemkey]['product_id'] == $thisproduct_id) && ($mcart_items[$matchitemkey]['variation_id'] == $thisvariation_id) && isset($mcart_items[$matchitemkey]['address_assigned']) && isset($mcart_items[$matchitemkey]['giftbusinesspersonal']) && ($mcart_items[$matchitemkey]['key'] != $mcart_items[$knc_mspp_item]['key']) && ($mcart_items[$matchitemkey]['address_assigned'] == $itemaddresskey) && ($mcart_items[$matchitemkey]['giftbusinesspersonal'] == $giftbusinesspersonal) ) {
                
                $newqty = ($mcart_items[$matchitemkey]['quantity'] *1) + ($qtycounter * 1);
                
            //remove the cart item after qty is increased for exsiting similar item
                WC()->cart->remove_cart_item($mcart_items[$knc_mspp_item]['key']);
                
                //increase qty to existing cart item
                WC()->cart->set_quantity( $mcart_items[$matchitemkey]['key'], ($newqty*1));

            }
            
        }
        
        //handler for delete requests
        //TODO: ln 142 from multiple shipping plugin wcms-address-book.php
        
        //handler for quantities update
        // handler for quantities update
        
        // if (isset($_POST['qtycounter'])) {
        //     $cart->set_quantity( $knc_mspp_item, $_POST['qtycounter']);
        // }
        
                // foreach ( $items as $cart_key => $item ) {
                    
                //     $newqty = $item['qty'];
                //     foreach ($newqty as $idx => $itemqty) {
                //         $cart->set_quantity( $cart_key, $itemqty);
                //     }
                // }
        //handling address
                
                // $itemaddresskey = $_POST['itemaddresskey'];
                // $knc_mspp_item = $_POST['knc_mspp_item'];
                
                    $cart_items = mspp_wcms_get_real_cart_items();
                
                    $product_id = $cart_items[$knc_mspp_item]['product_id'];
                    $sig        = $knc_mspp_item .'_'. $product_id .'_';
                    $_sig       = '';
                  
                  
                  foreach ($cart_items as $itemkey => $item) {
                      if ($itemkey == $knc_mspp_item) {
                     $cart_items[$knc_mspp_item]['address_assigned'] = $itemaddresskey;
                        $cart_items[$knc_mspp_item]['quantity'] = $qtycounter;
                    $cart_items[$knc_mspp_item]['giftbusinesspersonal'] = $giftbusinesspersonal;
                      }
                      if(isset($cart_items[$itemkey]['address_assigned'])) {
                         $address_id = $cart_items[$itemkey]['address_assigned'];
                         $user_address = $user_addresses[ $address_id ];                         
                          
                         $i = 1;
                          for ($m = 0; $m < $cart_items[$itemkey]['quantity']; $m++){
                              
                            $rel[$cart_items[$itemkey]['address_assigned']][] = $itemkey;
                              
                                while ( isset($data['shipping_first_name_'. $sig . $i]) ) {
                                $i++;
                                }
                                $_sig = $sig . $i;

                                if ( $fields ) foreach ( $fields as $key => $field ) :
                                $data[$key .'_'. $_sig] = $user_address[ $key ];
                                endforeach;
                              
                          }
                      }

                  }
                     
                
                    
                    // $cart_items[$knc_mspp_item]['address_assigned'] = $itemaddresskey;
                    // $cart_items[$knc_mspp_item]['giftbusinesspersonal'] = $giftbusinesspersonal;
                    
                    WC()->cart->set_cart_contents($cart_items);
                    
                    if (isset($qtycounter)) {
                        
                        // $cart_items[$knc_mspp_item]['quantity'] = $qtycounter;
                        //   WC()->cart->set_quantity( $knc_mspp_item, $qtycounter);
                          
                      }                   
        $cart_address_ids_session = (array)mspp_wcms_session_get( 'cart_address_ids' );
        if ( !empty($_sig) && !mspp_wcms_session_isset( 'cart_address_ids' ) || ! in_array($_sig, $cart_address_ids_session) ) {
            $cart_address_sigs_session = mspp_wcms_session_get( 'cart_address_sigs' );
            $cart_address_sigs_session[$_sig] = $itemaddresskey;
            mspp_wcms_session_set( 'cart_address_sigs', $cart_address_sigs_session);
        }

        
            mspp_wcms_session_set( 'cart_item_addresses', $data );
            mspp_wcms_session_set( 'address_relationships', $rel );
            mspp_wcms_session_set( 'wcms_item_addresses', $rel );
            
            
   // print_r($rel);
        
    } //post[knc_mspp_shipping_address_action]


    mspp_clear_packages_cache();
        if (isset($_POST['mspp_proceed_to_checkout'])) {
                $next_url = wc_get_checkout_url();
                wp_redirect($next_url);
                exit;
        }

}
/** the above function gets executed just before 
 * wordpress decides which template to load.
 * so using the template_redirect hook to achieve this.
 * */
add_action( 'template_redirect', 'knc_mspp_save_addresses' );


