<h2>Customized Cart</h2>
<?php
// get the most recently added item to the cart

global $woocommerce;

//get cart items
$items = $woocommerce->cart->get_cart();

$ids = array();
foreach($items as $item => $values) { 
        $_product = $values['data']->post; 
        //push each id into array
        $ids[] = $_product->ID; 
} 

//get last product id
$last_product_id = end($ids);

echo "most recently added item: " .$last_product_id;

?>