<?php
require_once 'inc/walker.php';

function botascopia_module_header($data) {
  // $header_small can be set be true before calling get_header()
  // in a template file to force a small header (without use cases navigation)
  global $header_small;

  $defaults = [
    'image' => get_field('cover_image'),
    'title' => get_the_title(),
    'subtitle' => get_field('cover_subtitle'),
    'content' => false,
    'search' => false,
    'modifiers' => []
  ];

  $data = botascopia_styleguide_data($defaults, $data);
  $data->modifiers = botascopia_styleguide_modifiers_array('header', $data->modifiers);
  if ( $header_small === true ) $data->modifiers[] = 'is-small';

  printf(
    '<header class="%s" role="banner">',
    implode(' ', $data->modifiers)
  );

    echo '<div class="header-fixed">';

      // Logo

      $logo_element = ( is_front_page() && is_home() ) ? 'h1' : 'div';

      printf(
        '<%s class="header-logo"><a href="%s" rel="home" title="Accueil">%s</a></%s>',
        $logo_element,
        esc_url( home_url( '/' ) ),
        sprintf(
          '<img src="%s" alt="Botascopia" />',
          get_template_directory_uri() . '/images/logo-botascopia.png'
        ),
        $logo_element
      );

//      printf(
//        '<button type="button" class="header-toggle">%s%s</button>',
//        __( 'Menu', 'botascopia' ),
//        get_botascopia_module('icon', ['icon' => 'menu'])
//      );

//      printf(
//        '<button type="button" class="header-toggle is-hidden">%s%s</button>',
//        __( 'Fermer', 'botascopia' ),
//        get_botascopia_module('icon', ['icon' => 'close'])
//      );

      // Menu secondaire
//
//      if ( has_nav_menu('secondary') ) :
//
//        printf(
//          '<nav class="header-nav" role="navigation" aria-label="%s">',
//          esc_attr__( 'Menu secondaire', 'botascopia' )
//        );
//          wp_nav_menu( [
//            'container'      => false,
//            'theme_location' => 'secondary',
//            'menu_class'     => 'header-nav-items',
//            'depth'          => 2,
//            'walker'         => new HeaderNavWalker(),
//            'items_wrap'     => '<ul id="%1$s" class="%2$s" role="menubar">%3$s</ul>'
//           ] );
//        echo '</nav>';
//
//      endif;
      
  // Menu principal
//	if ( has_nav_menu('principal')):
//	wp_nav_menu([
//					'theme_location' => 'principal',
//					'depth' => 1,
//					'menu_class'      => 'header-links',
//				]);
//endif;
  if ( has_nav_menu('principal') && $header_small !== true ) :
	  printf(
		  '<nav class="header-nav-usecases" role="navigation" aria-label="%s">',
		  esc_attr__( 'Menu principal', 'telabotanica' )
	  );
  
      wp_nav_menu( [
        'theme_location'  => 'principal',
        'menu_class'      => 'header-nav-usecases-items',
        'depth'            => 1,
      ] );
    echo '</nav>';

  endif;
	
	// Utilisateur
		  if ( is_user_logged_in() ) :
			$current_user = wp_get_current_user();
            $avatar_url = get_avatar($current_user->ID, 52, '', 'user avatar');
            ?>
              <div class="header-login">
<!--                  <a href="--><?php //echo admin_url( 'user-edit.php?user_id=' . $current_user->ID, 'http' ); ?><!--"  -->
                  <div href="<?php echo admin_url( 'user-edit.php?user_id=' . $current_user->ID, 'http' ); ?>"
                     title="editer compte" class="header-login-link">
                    <div class="header-links-item-text">
                        <div class="header-login-display-name">
							<?php echo $current_user->display_name ?>
                        </div>
                        <div class="header-login-role">
							(<?php echo $current_user->roles[0] ?>)
                        </div>
                    </div>
                      <div>
                          <span class="header-links-item-user-avatar"><?php echo $avatar_url ?></span>
                      </div>
                  </div>
<!--                  <a href="--><?php //echo admin_url( 'user-edit.php?user_id=' . $current_user->ID, 'http' ); ?><!--"></a>-->
              </div>
		  <?php else :
          echo '<div class="header-login">';
			  the_botascopia_module('button', [
				  'tag' => 'a',
				  'href' => wp_login_url( get_permalink() ),
			  __( 'Connexion', 'botascopia' ),
				  'text' => 'Se connecter',
				  'title' => 'Se connecter',
				  'modifiers' => 'green-button',
			  ]);
    echo '</div>';
		  endif;

  echo '</header>';
}
