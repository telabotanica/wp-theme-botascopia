<?php function botascopia_module_title($data) {

  $defaults = [
    'title' => 'Titre',
    'level' => 1,
    'suffix' => false,
    'href' => false,
    'target' => false,
    'modifiers' => []
  ];

  $data = botascopia_styleguide_data($defaults, $data);
  $data->modifiers = botascopia_styleguide_modifiers_array('title', $data->modifiers);

  if ( $data->suffix !== false ) :
    $data->title .= sprintf( '<span class="title-suffix">%s</span>', $data->suffix );
  endif;

  if ( $data->href ) :
    $data->title = sprintf(
      '<a href="%s" target="%s">%s</a>',
      $data->href,
      $data->target,
      $data->title
    );
  endif;

  printf(
    '<h%s class="%s">%s</h%s>',
    $data->level,
    implode(' ', $data->modifiers),
    $data->title,
    $data->level
  );

}
