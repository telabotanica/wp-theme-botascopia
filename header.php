<?php
/**
 * Header
 */
acf_form_head();
?><!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <?php if ( is_singular() && pings_open( get_queried_object() ) ) : ?>
        <link rel="pingback" href="<?php bloginfo( 'pingback_url' ); ?>">
    <?php endif; ?>

    <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>
<?php



// Utilisateur

if ( is_user_logged_in() ) {
    $current_user = wp_get_current_user();
    echo $current_user->display_name;
} else {
    echo wp_login_url( get_permalink() );
}
?>

<div id="content" class="site-content">
