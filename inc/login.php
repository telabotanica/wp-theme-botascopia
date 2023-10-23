<?php
// Personnalisation de la page de login

function telabotanica_login_logo_url() {
  return home_url();
}
add_filter( 'login_headerurl', 'telabotanica_login_logo_url' );

function telabotanica_login_logo_url_title() {
  return 'Tela Botanica';
}
add_filter( 'login_headertext', 'telabotanica_login_logo_url_title' );

function telabotanica_login_stylesheet() {
  wp_enqueue_style( 'custom-login', get_stylesheet_directory_uri() . '/dist/bundle.css' );
  wp_enqueue_script( 'telabotanica-script', get_template_directory_uri() . '/dist/bundle.js', [ 'jquery' ], null, true );
}
add_action( 'login_enqueue_scripts', 'telabotanica_login_stylesheet' );

// On remplace l'url d'inscription par le site tela
function custom_register_url($register_url, $redirect = null, $force_reauth = false) {
	// Modifier l'URL d'inscription selon vos besoins
	$custom_register_url = 'https://www.tela-botanica.org/inscription/';
	$redirect = home_url( '/wp-login.php' );
	
	// Ajouter un paramètre de redirection à l'URL d'inscription si nécessaire
	if (!empty($redirect)) {
		$custom_register_url = add_query_arg('redirect_to', urlencode($redirect), $custom_register_url);
	}
	
	return esc_url($custom_register_url);
}
add_filter('register_url', 'custom_register_url', 10, 3);

// On remplace l'url de mot de passe oublié par le site tela
function custom_lostpassword_url($lostpassword_url, $redirect = '') {
	// Modifier l'URL du mot de passe oublié selon vos besoins
	$custom_lostpassword_url = 'https://www.tela-botanica.org/wp-login.php?action=lostpassword';
	$redirect = home_url( '/wp-login.php' );
	
	// Ajouter un paramètre de redirection à l'URL du mot de passe oublié si nécessaire
	if (!empty($redirect)) {
		$custom_lostpassword_url = add_query_arg('redirect_to', urlencode($redirect), $custom_lostpassword_url);
	}
	
	return esc_url($custom_lostpassword_url);
}
add_filter('lostpassword_url', 'custom_lostpassword_url', 10, 2);
