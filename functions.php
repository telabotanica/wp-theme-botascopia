<?php
// adding "bs_" (botascopia) prefix to avoid overriding native wp functions

// ajout de la recherche sur les champs acf
require get_template_directory() . '/inc/custom-search-acf-wordpress.php';

// add theme supports
function bs_theme_supports() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('menus');
  register_nav_menu('main-menu', 'Menu principal');
}
add_action('after_setup_theme', 'bs_theme_supports');

// load css (and js, later if needed)
function bs_load_scripts() {
  wp_enqueue_style( 'style', get_stylesheet_uri());
}
add_action('wp_enqueue_scripts', 'bs_load_scripts' );

// auto export acf fields after each saved change
function bs_acf_export_json( $path ) {
  $path = get_stylesheet_directory() . '/acf-json';
  return $path;
}
add_filter('acf/settings/save_json', 'bs_acf_export_json');

function set_user_admin_bar_false_by_default($user_id) {
    update_user_meta( $user_id, 'show_admin_bar_front', 'false' );
    update_user_meta( $user_id, 'show_admin_bar_admin', 'false' );
}
add_action("user_register", "set_user_admin_bar_false_by_default", 10, 1);

add_filter( 'login_redirect', function( $url, $query, $user ) {
    return home_url();
}, 10, 3 );

add_action( 'set_pending', 'add_editor_meta' );

function add_editor_meta( $post_id ) {
    wp_update_post(array('ID' => $post_id, 'post_status' => 'pending'));

    update_post_meta( $post_id, 'Editor', 0 );
}
