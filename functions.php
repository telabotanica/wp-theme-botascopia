<?php
  
function register_my_menu(){
  register_nav_menu( 'main-menu', 'Menu principal' );
}
add_action( 'after_setup_theme', 'register_my_menu' );

// auto export acf fields after each saved change
function acf_export_json( $path ) {
  $path = get_stylesheet_directory() . '/acf-json';
  return $path;
}
add_filter( 'acf/settings/save_json', 'acf_export_json' );

?>
