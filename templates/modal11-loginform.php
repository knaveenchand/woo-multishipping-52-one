<div id="login-form-modal" class="modal">
    <div style="text-align:center;"><h2>Login</h2>
    <?php
    global $wp;
$current_url = home_url( add_query_arg( array('mspp'=>'true'), $wp->request ) );
if ( ! is_user_logged_in() ) { // Display WordPress login form:
    $args = array(
        'redirect' => $current_url, 
        'form_id' => 'loginform-custom',
        'label_username' => __( 'Username' ),
        'label_password' => __( 'Password' ),
        'label_remember' => __( 'Remember Me' ),
        'label_log_in' => __( 'Log In' ),
        'remember' => true
    );
    wp_login_form( $args );
} else { // If logged in:
    wp_loginout( home_url() ); // Display "Log Out" link.
    // echo " | ";
    // wp_register('', ''); // Display "Site Admin" link.
}
    ?>
</div>
</div>
