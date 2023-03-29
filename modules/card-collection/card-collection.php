<?php function botascopia_module_card_collection($data) {
	
	$defaults = [
		'href' => '#',
		'image' => get_field('card_image'),
		'name' => 'nom de la collection',
		'nbFiches' => 'x',
		'description' => 'Lorem ipsum dolor sit amet. Ut voluptatem dolor non omnis quia est eveniet illum. Ea alias ullam eos ut voluptatem dolor non lorem ipsum dolor sit amet bla bla bla bli',
		'modifiers' => []
	];
	
	$data = botascopia_styleguide_data($defaults, $data);
	$data->modifiers = botascopia_styleguide_modifiers_array('card', $data->modifiers);
	
	// Définir une image par défaut si aucune n'est présente
	if (empty($data->image['url'])) :
		$data->image = [
		'url' => get_template_directory_uri() . '/images/logo-botascopia@2x.png'
		];
	endif;
	
	echo '<div class="card-collection ' . implode(' ', $data->modifiers) . '">';
	echo sprintf(
		'<div><img src="%s" class="card-collection-image" alt="image-plante" title="%s"/></div>',
		$data->image['url'],
		$data->name
	);
	echo '<div class="card-collection-body">';
	
	echo sprintf('<a href="%s">',
	$data->href)
	;
	
	// TODO calculer le nombre de fiches dans la collection
	echo sprintf('
		<p class="card-collection-title">%s</p>
		<p class="card-collection-subtitle">%s fiches dans la collection</p>
		<p class="card-collection-description">%s</p>',
				 $data->name,
				 $data->nbFiches,
				 $data->description
	);
	
	echo '</a></div>';
	
	echo sprintf('
		<div class="card-collection-icon">%s</div>',
				 get_botascopia_module('icon', ['icon' => 'star-outline', 'color' => 'blanc'])
	);

	echo '</div>';
}