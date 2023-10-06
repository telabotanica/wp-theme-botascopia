<?php function botascopia_module_title($data) {

  $defaults = [
    'title' => 'Titre',
    'level' => 1,
    'suffix' => false,
    'href' => false,
    'target' => false,
    'modifiers' => [],
	'extra_attributes' => []
  ];

  $data = botascopia_styleguide_data($defaults, $data);
  $data->modifiers = botascopia_styleguide_modifiers_array('title', $data->modifiers);
  
	$attributes = '';
	
	foreach ($data->extra_attributes as $name => $value) {
		$attributes .= sprintf('%s="%s" ', $name, $value);
	}

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
    '<h%s class="%s" %s>%s</h%s>',
    $data->level,
    implode(' ', $data->modifiers),
	$attributes,
    $data->title,
    $data->level
  );

}
