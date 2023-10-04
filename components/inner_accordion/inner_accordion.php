<?php function botascopia_component_inner_accordion($data)
{
	
	$defaults = [
		'title_level' => get_sub_field('title_level'),
		'items' => get_sub_field('items'),
		'icon' => [],
		'modifiers' => []
	];
	
	$data = botascopia_styleguide_data($defaults, $data);
	$data->modifiers = botascopia_styleguide_modifiers_array(['component', 'component-inner-accordion', 'js-inner-accordion'], $data->modifiers);

    /*printf('<h1>Example of nested accordions</h1>

        <div class="js-accordion" id="accordion1" data-accordion-prefix-classes="minimalist-accordion">
            <div class="js-accordion__panel">
                <h2 class="js-accordion__header">First tab</h2>
                <p>Content of 1st tab</p>
            </div>
            <div class="js-accordion__panel">
                <h2 class="js-accordion__header">Second tab</h2>
                <p>Content of 2nd tab</p>

                <div class="js-accordion" id="accordion_inner" data-accordion-prefix-classes="minimalist-accordion">
                    <div class="js-accordion__panel">
                        <h3 class="js-accordion__header">First tab</h3>
                        <p>Content of 1st tab</p>
                    </div>
                    <div class="js-accordion__panel">
                        <h2 class="js-accordion__header">Second tab</h2>
                        <p>Content of 2nd tab</p>
                    </div>
                    <div class="js-accordion__panel">
                        <h2 class="js-accordion__header">Third tab</h2>
                        <p>Content of 3rd tab</p>
                    </div>
                </div>
            </div>
            <div class="js-accordion__panel">
                <h2 class="js-accordion__header">Third tab</h2>
                <p>Content of 3rd tab</p>
            </div>
        </div>');*/

	printf(
		'<div class="%s" data-inner-accordion-prefix-classes="component-inner-accordion">',
		implode(' ', $data->modifiers)
	);

    $titre = $data->items[0]['title'];

	switch ($titre){
		case 'tige':
			$image = 'tige';
			break;
		case 'feuille':
			$image = 'feuilles';
			break;
		case 'inflorescence':
			$image = 'inflorescence';
			break;
		case 'fleur_male':
			$image = 'fleur-male';
			break;
        case 'fleur_femelle':
            $image = 'fleur-femelle';
            break;
        case 'fleur_bisexuee':
            $image = 'inflorescence';
            break;
        case 'fruit':
            $image = 'fruits';
            break;
		default:
			$image = '';
	}

	if ($image){
		echo '<img class="inner-accordion-icon" src="'.get_template_directory_uri().'/images/'.$image.'.svg" />' ;
	}


	the_botascopia_module('button', [
		'tag' => 'button',
		'title' => 'complet',
		'text' => 'complet',
		'modifiers' => 'green-button'.' formulaire-field-status',
//		'icon_after' => ['icon' => 'angle-down', 'color' => 'blanc'],
		'extra_attributes' => ['id' => 'bouton-status-'.$data->modifiers['id']]
	]);

	if ($data->items):

		foreach ($data->items as $item) :
			echo '<div class="js-inner-accordion__panel component-inner-accordion__panel">';

			$item = (object)$item;

			printf(
				'<h%s class="js-inner-accordion__header component-inner-accordion__header">%s</h%s>',
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
