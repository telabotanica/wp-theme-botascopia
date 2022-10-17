<?php

function bs_theme_supports() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('menus');
  register_nav_menu('main-menu', 'Menu principal');
}

function load_scripts() {
    wp_enqueue_style( 'style', get_stylesheet_uri());
}


function bs_document_title_parts($title) {
    unset($title['tagline']);
    return $title;
}

// auto export acf fields after each saved change
function acf_export_json($path) {
    $path = get_stylesheet_directory().'/acf-json';
    return $path;
}

add_action('wp_enqueue_scripts', 'load_scripts' );
add_action('after_setup_theme', 'bs_theme_supports');
add_filter('document_title_parts' , 'bs_document_title_parts');
add_filter('acf/settings/save_json', 'acf_export_json');
