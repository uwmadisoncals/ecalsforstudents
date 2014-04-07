<?php

//reference http://codex.wordpress.org/AJAX_in_Plugins

$errors = array();
nocache_headers();

$user_login = 'admin';
$user_pass = 'y0ugr0w3r!';
$using_cookie = FALSE;

//echo "here";

if ( $_POST ) {
	//$user_login = $_POST['log'];
	//$user_login = sanitize_user( $user_login );
	//$user_pass  = $_POST['pwd'];
	//$rememberme = $_POST['rememberme'];
} else {
//	$cookie_login = wp_get_cookie_login();
//	if ( ! empty($cookie_login) ) {
//		$using_cookie = true;
//		$user_login = $cookie_login['login'];
//		$user_pass = $cookie_login['password'];
//	}
}

/*do_action_ref_array('wp_authenticate', array(&$user_login, &$user_pass));

if ( $user_login && $user_pass && empty( $errors ) ) {
	$user = new WP_User(0, $user_login);

	if ( wp_login($user_login, $user_pass, $using_cookie) ) {
		if ( !$using_cookie )
			wp_setcookie($user_login, $user_pass, false, '', '', $rememberme);
		do_action('wp_login', $user_login);
		return "logged in";
		exit();
	} else {
		if ( $using_cookie )
			$errors['expiredsession'] = __('Your session has expired.');
			
		return "not logged in";
	}
}
*/

//submit data

	//validate login using dummy user
		//catch errors
		
	//create new post
		//catch errors
		
	///notify admin of new submission
		//catch errors
		
	//logout dummy user
		//catch errors
		
//notify errors or success


?>