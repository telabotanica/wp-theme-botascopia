<?php function botascopia_module_cover($data) {

  $defaults = [
    'image' => get_field('cover_image'),
    'title' => get_the_title(),
    'subtitle' => get_field('cover_subtitle'),
    'content' => false,
    'search' => false,
    'modifiers' => []
  ];

  $data = botascopia_styleguide_data($defaults, $data);
  $data->modifiers = botascopia_styleguide_modifiers_array('cover', $data->modifiers);

  // Définir une image au hasard si aucune n'est présente
  if ( empty( $data->image['url'] ) ) :
	
	  printf(
		  '<div class="%s" style="background: transparent linear-gradient(90deg, #000000BA , #00000095 , #00000069 , #00000000 ) no-repeat padding-box;">',
		  implode(' ', $data->modifiers)
	  );
  else:
	  printf(
		  '<div class="%s" style="background-image: url(%s);">',
		  implode(' ', $data->modifiers),
		  $data->image['url']
	  );
  
  
  endif;
    echo '<div class="layout-wrapper">';

//      if ($data->search) :
//        $data->search['autocomplete'] = false;
//        printf(
//          '<div class="cover-search-box">%s</div>',
//          get_botascopia_module('search-box', $data->search)
//        );
//      endif;

      printf(
        '<h1 class="cover-title">%s</h1>',
        $data->title
      );

      if ($data->subtitle) :
        printf(
          '<div class="cover-subtitle">%s</div>',
          $data->subtitle
        );
      endif;

      if ($data->content) :
        printf(
          '<div class="cover-content">%s</div>',
          $data->content
        );
      endif;

    echo '</div>';

//    botascopia_image_credits( $data->image, 'cover' );

  echo '</div>';

}
