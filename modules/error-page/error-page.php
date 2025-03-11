<?php function botascopia_module_error_page($data) {

  $defaults = [
    'type' => 404,
    'title' => __('Erreur', 'botascopia'),
    'text' => false,
    'button' => [
      'href' => site_url(),
      'text' => __( "Retour à l'accueil", 'botascopia' )
    ],
    'modifiers' => []
  ];

  $data = botascopia_styleguide_data($defaults, $data);
  $data->modifiers = botascopia_styleguide_modifiers_array('error-page', $data->modifiers);

  printf(
    '<div class="%s">',
    implode(' ', $data->modifiers)
  );

    printf(
      '<a href="%s" class="error-page-logo"><img src="%s" alt="Botascopia" /></a>',
      site_url(),
      get_template_directory_uri() . '/images/logo-botascopia.png'
    );

    printf(
      '<div class="error-page-image"><img src="%s" alt="%s" /></div>',
      get_template_directory_uri() . '/modules/error-page/' . $data->type . '.jpg',
      $data->type
    );

    printf(
      '<h1 class="error-page-title">%s</h1>',
      $data->title
    );

    if ( $data->text ) :
      printf(
        '<p class="error-page-text">%s</p>',
        $data->text
      );
    endif;

    the_botascopia_module('button', $data->button);

  echo '</div>';
}
