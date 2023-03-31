<?php function botascopia_module_button($data) {

  $defaults = [
    'tag' => 'a',
    'href' => '',
    'target' => '',
    'text' => 'Bouton',
    'title' => '',
    'icon_before' => false,
    'icon_after' => false,
    'modifiers' => [],
    'extra_attributes' => []
  ];

  $data = botascopia_styleguide_data($defaults, $data);
  $data->modifiers = botascopia_styleguide_modifiers_array('button', $data->modifiers);

  if ( $data->modifiers && in_array('back', $data->modifiers) ) $data->icon_before = 'arrow-left';

  $attributes = '';

  foreach ($data->extra_attributes as $name => $value) {
    $attributes .= sprintf('%s="%s" ', $name, $value);
  }

  if (!empty($data->href)) {
    $attributes .= sprintf('href="%s"', $data->href);
  } else {
    $data->tag = 'button';
  }

  if (!empty($data->target)) {
    $attributes .= sprintf('target="%s"', $data->target);
  }

  if (!empty($data->title)) {
    $attributes .= sprintf('title="%s"', $data->title);
  }
	
  if ( !empty($data->icon_before)) {
	$iconTypeBefore = getType($data->icon_before);
	if ($iconTypeBefore == 'array' && key_exists('color', $data->icon_before)) {
		$iconBefore = $data->icon_before['icon'];
		$colorBefore = $data->icon_before['color'];
	} elseif ($iconTypeBefore == 'array') {
		$iconBefore = $data->icon_before['icon'];
		$colorBefore = '';
	} else {
		$iconBefore = $data->icon_before;
		$colorBefore = '';
	}
  }

  if ( !empty($data->icon_after)) {
	$iconTypeAfter = getType($data->icon_after);
	if ($iconTypeAfter == 'array' && key_exists('color', $data->icon_after)) {
		$iconAfter = $data->icon_after['icon'];
		$colorAfter = $data->icon_after['color'];
	} elseif ($iconTypeAfter == 'array') {
		$iconAfter = $data->icon_after['icon'];
		$colorAfter = '';
	} else {
		$iconAfter = $data->icon_after;
		$colorAfter = '';
	}
  }

  printf(
    '<%s %s class="%s">%s<span class="button-text">%s</span>%s</%s>',
    $data->tag,
    $attributes,
    implode(' ',$data->modifiers),
    $data->icon_before ? get_botascopia_module('icon', ['icon' => $iconBefore, 'color' => $colorBefore]) : '',
    $data->text,
    $data->icon_after ? get_botascopia_module('icon', ['icon' => $iconAfter, 'color' => $colorAfter]) : '',
    $data->tag
  );
  
}
