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

	printf(
		'<div class="%s" data-inner-accordion-prefix-classes="component-inner-accordion">',
		implode(' ', $data->modifiers)
	);

    $titre = $data->items[0]['title'];

    $form_key = $data->items[0]['content']['field_key'];

    $group_fields = acf_get_fields($form_key);

    $field_groups = acf_get_field_groups($data->items[0]['content']['field_groups']);



    // echo var_dump($group_fields);
    $size = count($group_fields);

    foreach ($group_fields as $field) {

        //    for ($i = 0; $i < $size; ++$i) {

        if ($field['type'] === 'group') {


            switch ($field['name']) {
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

            if ($image) {
                echo '<img class="inner-accordion-icon" src="' . get_template_directory_uri() . '/images/' . $image . '.svg" />';
            }

            the_botascopia_module('button', [
                'tag' => 'button',
                'title' => 'complet',
                'text' => 'complet',
                'modifiers' => 'green-button' . ' formulaire-field-status',
//	        	'icon_after' => ['icon' => 'angle-down', 'color' => 'blanc'],
                'extra_attributes' => ['id' => 'bouton-status-' . $data->modifiers['id']]
            ]);

            echo '<div class="js-inner-accordion__panel component-inner-accordion__panel">';

            printf(
                '<h%s class="js-inner-accordion__header component-inner-accordion__header">%s</h%s>',
                $data->title_level,
                $field['label'],
                $data->title_level
            );

            // echo var_dump($field);

            foreach ($field['sub_fields'] as $sub_field){
                echo var_dump($sub_field);

                $args = array(
                    'post_id' => $data->items[0]['content']['post_id'],
                    'field_groups' => $field['sub_fields'], // L'ID du post du groupe de champs
                    'field_title' => $sub_field['name'],
                    'field_key' => $sub_field['parent'],
                    'submit_value' => 'Corriger', // Intitulé du bouton
                    'html_submit_button' => '<button type="submit" class="acf-button button green-button">%s</button>',
                    'updated_message' => "Votre demande a bien été prise en compte.",
                    'uploader' => 'wp',
                    'id' => $data->items[0]['content']['id'],
                    // 'html_before_fields' => '<div class="js-inner-accordion__panel component-inner-accordion__panel">',
                    // 'html_after_fields' => '</div>',
                    'return' => $data->items[0]['content']['return'],
                );

                ($args);
            }
            echo '</div>';

            /*if ($data->items):

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

            echo '</div>';*/
        }
    }
}
