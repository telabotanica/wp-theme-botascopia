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
  // TODO: add skip links ?>
  <!-- <a class="skip-link screen-reader-text" href="#content"><?php _e( 'Skip to content', 'telabotanica' ); ?></a> -->
  <?
  global $header_small;

  $defaults = [
    'image' => get_field('cover_image'),
    'title' => get_the_title(),
    'subtitle' => get_field('cover_subtitle'),
    'content' => false,
    'search' => false,
    'modifiers' => []
  ];

  printf(
    '<header class="%s" role="banner">',
    implode(' ', $data->modifiers)
  );

    echo '<div class="header-fixed">';

      // Logo

      $logo_element = ( is_front_page() && is_home() ) ? 'h1' : 'div';

      printf(
        '<%s class="header-logo"><a href="%s" rel="home">%s</a></%s>',
        $logo_element,
        esc_url( home_url( '/' ) ),
        sprintf(
          '<img src="%s" alt="Tela Botanica" />',
          get_template_directory_uri() . '/modules/header/logo.svg'
        ),
        $logo_element
      );


      echo '<ul class="header-links">';


      // Utilisateur

      if ( is_user_logged_in() ) {
        $current_user = wp_get_current_user();
        $avatar_url = bp_core_fetch_avatar( [
          'item_id' => $current_user->ID,
          'html' => false
        ] ); ?>
        <li class="header-links-item header-links-item-user">
          <a href="<?php echo bp_loggedin_user_domain(); ?>">
            <span class="header-links-item-text">
              <span class="header-links-item-user-name"><?php echo $current_user->display_name; ?></span>
              <span class="header-links-item-user-avatar" style="background-image: url(<?php echo $avatar_url ?>);"></span>
            </span>
          </a>
        </li>
      <?php } else {
        printf(
          '<li class="header-links-item header-links-item-login"><a href="%s"><span class="header-links-item-text">%s</span></a></li>',
          wp_login_url( get_permalink() ),
          __( 'Connexion', 'telabotanica' )
        );
      }


    echo '</ul>';
  echo '</div>';


  printf(
    '<div class="header-container"></div><div class="header-submenu-container"><button class="header-submenu-back">%s%s</button><div class="header-submenu-container-nav"></div></div>',
    get_telabotanica_module('icon', ['icon' => 'arrow-left']),
    __( 'Retour', 'telabotanica' )
  );

  echo '</header>'?>

    <div id="content" class="site-content">
