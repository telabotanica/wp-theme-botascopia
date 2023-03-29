<?php function botascopia_module_card_fiche($data) {
	
	$defaults = [
		'href' => '#',
		'image' => get_field('card_image'),
		'name' => 'nom de la plante',
		'species' => 'espèce de la plante',
		'icon' => ['icon' => 'star-outline', 'color' => 'blanc'],
		'modifiers' => []
	];
	
	$data = botascopia_styleguide_data($defaults, $data);
	$data->modifiers = botascopia_styleguide_modifiers_array('card', $data->modifiers);
	
	// Définir une image au hasard si aucune n'est présente
	if (empty($data->image['url'])) :
		$data->image = [
		'url' => get_template_directory_uri() . '/images/logo-botascopia@2x.png'
		];
	endif;
	
	echo '<div class="card-fiche ' . implode(' ', $data->modifiers) . '">';
	echo sprintf(
		'<img src="%s" class="card-fiche-image" alt="image-plante" title="%s"/>',
		$data->image['url'],
		$data->name
	);
	echo '<div class="card-fiche-body">';
	
	echo sprintf('<a href="%s">',
	$data->href)
	;
	
	echo sprintf('
		<span class="card-fiche-title">%s</span>
		<span class="card-fiche-espece">%s</span>',
				 $data->name,
	$data->species
	);
	
	echo '</a></div>';
	
	// TODO Remplacer par module filtre ?
	echo sprintf('
		<div class="card-fiche-icon">%s</div>',
				 get_botascopia_module('icon', $data->icon)
	);
	
//	echo '</div>';
	echo '</div>';
}