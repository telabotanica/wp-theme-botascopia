<?php function botascopia_module_card_fiche($data) {
	
	$defaults = [
		'href' => '#',
		'image' => get_template_directory_uri() . '/images/logo-botascopia@2x.png',
		'name' => 'nom de la plante',
		'species' => 'espèce de la plante',
		'icon' => ['icon' => 'star-outline', 'color' => 'blanc'],
		'popup' => '',
		'id' => '',
		'modifiers' => [],
		'extra_attributes' => []
	];
	
	$data = botascopia_styleguide_data($defaults, $data);
	$data->modifiers = botascopia_styleguide_modifiers_array('card', $data->modifiers);
	
	$attributes = '';
	
	foreach ($data->extra_attributes as $name => $value) {
		$attributes .= sprintf('%s="%s" ', $name, $value);
	}
	
	echo '<div class="card-fiche ' . implode(' ', $data->modifiers) . '" >';
	
	echo sprintf('<a href="%s" class="%s" '.$attributes.'>',
				 $data->href,
				 $data->popup
	);
	
	echo sprintf(
		'<img src="%s" class="card-fiche-image" alt="image-plante" title="%s"/>',
		$data->image,
		$data->name,
	);
	echo '<div class="card-fiche-body">';

	echo sprintf('
		<span class="card-fiche-title">%s</span>
		<span class="card-fiche-espece">%s</span>',
			$data->name,
			$data->species
	);
	
	echo '</a></div>';
	
	echo sprintf('
		<div class="card-fiche-icon" %s id="%s">%s</div>',
				 $attributes,
				 $data->id,
				 get_botascopia_module('icon', $data->icon)
	);
	
	echo '</div>';
}