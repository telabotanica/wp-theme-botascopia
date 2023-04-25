<?php function botascopia_module_icon($data) {
  if ( isset($data->color) ) {
    $data->color = 'icon-color-' . $data->color;
  } else {
    $data->color = '';
  }
	$url = get_template_directory_uri().'/assets/icons/';
	echo sprintf(
		'<svg aria-hidden="true" role="img" class="icon icon-%s %s">
		<use xlink:href="#icon-%s"></use></svg>',
		$data->icon,
		$data->color,
		$data->icon
	);
 
}
