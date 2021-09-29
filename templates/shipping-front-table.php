<?php 

    // WC()->session->set('guest_login_enabled', 'true');
    
// if ( ! WC()->session->has_session() ) {
//         WC()->session->set_customer_session_cookie( true );
// }

// $guest_login_preference = WC()->session->get( 'guest_login_enabled' );
// echo "login preference: " .$guest_login_preference;

$guest_login_preference = $_SESSION["guest_php_session"];

if ( (! is_user_logged_in()) and ($guest_login_preference == 'true') ) {
    $firstscreen = "#modal20a-shipping-screen";
}
if (is_user_logged_in()) {
    $firstscreen = "#modal20a-shipping-screen";
}
if ( (! is_user_logged_in()) and (!($guest_login_preference == 'true')) ) {
    $firstscreen = "#modal10-login-options-screen";
}

// if ( ! is_user_logged_in() and !($guest_login_preference === 'true') ) {
//     $firstscreen = "#modal10-login-options-screen";
// } else {
//     $firstscreen = "#modal20a-shipping-screen";
// }

?>
	<div id='shippingoptionsdiv'>
        <table id='shippingoptionstable'>
            <tr>
                <th></th>
                <th></th>
            </tr>
            <tr id='sendgiftrow'>
                <td class='sendgiftrow'>
                <img src='https://52eaststudio.com/wp-content/uploads/2021/07/mspp_giftboxicon.png' style="width:20px;" class="mspptooltip" id="mspptooltip-gift">
                   
                </td>
                <td>
                    <a href="<?php echo $firstscreen; ?>" rel="modal:open" id="mspp_gift_ship">Send as Gift</a>
                </td>
            </tr>
            <tr id='shiptoclientsrow'>
                <td class='sendgiftrow'>
                   <img src='https://52eaststudio.com/wp-content/uploads/2021/07/mspp_shippingboxicon.png' style="width:20px;" class="mspptooltip" id="mspptooltip-business"> 
                </td>
                <td>
                    <a href="<?php echo $firstscreen; ?>" rel="modal:open" id="mspp_business_ship">Ship to Clients/Patients/Employees</a>
                </td>
            </tr>
            <tr id='shiptome'>
                <td>
                   <img src='https://52eaststudio.com/wp-content/uploads/2021/07/mspp_happyfaceicon.png' style="width:20px;" class="mspptooltip" id="mspptooltip-self">
                </td>
                <td>
                    <a href="<?php echo $firstscreen; ?>" rel="modal:open" id="mspp_self_ship">Ship it to ME!</a>
                </td>
            </tr>
        </table>
        <p><strong>Order Management Made Easy</strong></p>
        <p>Gift, work and personal orders are all organized by recipient to make ordering easy every time.</p>
        <p>Create an account to keep all your addresses and past orders easily accessible for every order.</p>
        <p>You can log in or create an account once you add an initial item to the cart.</p>
        </div>