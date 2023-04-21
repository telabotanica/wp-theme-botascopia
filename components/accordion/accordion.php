<?php function botascopia_component_accordion($data)
{
	
	$defaults = [
		'title_level' => get_sub_field('title_level'),
		'items' => get_sub_field('items'),
		'icon' => [],
		'modifiers' => []
	];
	
	$data = botascopia_styleguide_data($defaults, $data);
	$data->modifiers = botascopia_styleguide_modifiers_array(['component', 'component-accordion', 'js-accordion'], $data->modifiers);
	
	printf(
		'<div class="%s" data-accordion-prefix-classes="component-accordion">',
		implode(' ', $data->modifiers)
	);
	
	$titre = $data->items[0]['title'];
	$post_id = $data->items[0]['content']['post_id'];
	$field_title = $data->items[0]['content']['field_title'];
	$field_group_key = $data->items[0]['content']['field_key'];
	$field_group_id = $data->items[0]['content']['field_groups'][0];
	
	switch ($titre){
		case 'Description vulgarisée':
		case 'Description morphologique':
			$image = 'description';
			break;
		case 'Période de floraison et de fructification':
			$image = 'periode';
			break;
		case 'Écologie':
			$image = 'ecologie';
			break;
		case 'tige':
			$image = 'tige';
			break;
		case 'Aire de répartition et statut':
			$image = 'location';
			break;
		case 'Ne pas confondre avec':
			$image = 'ne-pas-confondre';
			break;
		default:
			$image = '';
	}

	if ($image){
		echo '<img class="accordion-icon" src="'.get_template_directory_uri().'/images/'.$image.'.svg" />' ;
	}
	
	// récupérer tous les champs du post
	$fields = get_field_objects($post_id);

// récupérer tous les champs du groupe de champs ACF
	$group_fields = acf_get_fields($field_group_key);

// vérifier si tous les champs du groupe sont remplis
	$champs_complet = true;
	$required = false;
	$non_required = false;
	
	// Permet de savoir s'il y a un mélange de champs obligatoires et non obligatoires
	foreach ($group_fields as $field) {
		if ($field['required'] == 1) {
			$required = true;
			if (!get_post_meta($post_id, $field_title)){
				$champs_complet = false;
				break;
			}
		}
		if ($field['required'] == 0) {
			$non_required = true;
		}
	}
	
	foreach ($group_fields as $field) {
			if ($non_required && !$required) {
				if (( !array_key_exists($field['name'], $fields) && $field['required'] == 0) || (empty($fields[$field['name']]['value']) && $field['required'] == 0)) {
					$champs_complet = false;
					break;
				}
		}
	}
	
	$button = 'purple-button';
	$text = 'Incomplet';
	
	if ($champs_complet) {
		$button = 'green-button';
		$text = 'complet';
	}
	
	the_botascopia_module('button', [
		'tag' => 'button',
		'title' => $text,
		'text' => $text,
		'modifiers' => $button.' formulaire-field-status',
		'icon_after' => ['icon' => 'angle-down', 'color' => 'blanc'],
		'extra_attributes' => ['id' => 'bouton-status-'.$data->modifiers['id']]
	]);
	
	if ($data->items):
		
		foreach ($data->items as $item) :
			echo '<div class="js-accordion__panel component-accordion__panel">';
			
			$item = (object)$item;
			
			printf(
				'<h%s class="js-accordion__header component-accordion__header">%s</h%s>',
				$data->title_level,
				$item->title,
				$data->title_level
			);
			
			acf_form($item->content);
			echo '</div>';
		
		endforeach;
	
	endif;
	
	echo '</div>';
}
