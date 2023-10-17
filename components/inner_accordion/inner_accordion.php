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

            echo(var_dump($item));

            acf_form(array('fields' => array("field_643027826f24d",
                "field_6304bda381ab9",
                "field_643026639db39",
                "field_643028a453acb",
                "field_643028d253acc",
                "field_6304bdd481aba",
                "field_6304bdf681abb",
                "field_6304be7c75bd9",
                "field_6304c10075bda",
                "field_6304c15175bdc",
                "field_6304c1bd75bdd",
                "field_6304c23d75be0",
                "field_6304c46075be1",
                "field_6304c4aa75be2",)));

            echo '</div>';

            echo '<div class="js-inner-accordion__panel component-inner-accordion__panel">';

            $item = (object)$item;

            printf(
                '<h%s class="js-inner-accordion__header component-inner-accordion__header">%s</h%s>',
                $data->title_level,
                $item->title,
                $data->title_level
            );

            acf_form(array('fields' => array("field_6304e1ece18b0",
                "field_64302a1423559",
                "field_6304c61a23918",
                "field_64302ab82355a",
                "field_64302ad92355b",
                "field_64302b092355c",
                "field_6304c66b23919",
                "field_6304c6d52391a",
                "field_6304c8e02391b",
                "field_6304c9402391c",
                "field_6304c9762391d",
                "field_6304ca5e58ca8",
                "field_6304cd8ee66c2",)));

            echo '</div>';

            echo '<div class="js-inner-accordion__panel component-inner-accordion__panel">';

            $item = (object)$item;

            printf(
                '<h%s class="js-inner-accordion__header component-inner-accordion__header">%s</h%s>',
                $data->title_level,
                $item->title,
                $data->title_level
            );

            acf_form(array('fields' => array("field_6304d897b94a1",
                "field_6304d8aeb94a2",
                "field_642ec4792882c",
                "field_634e45f4d4d68",
                "field_634e46c42f080",
                "field_6304d9e2b94a5",
                "field_64302c09300f6",
                "field_6304d939b94a3",
                "field_64302c70300f7",
                "field_64302c81300f8",
                "field_64302cb2300f9",
                "field_6304d98fb94a4",
                "field_6304da7252e04",
                "field_6304dac552e05",
                "field_64413c967e502",
                "field_6304db6293737",
                "field_6304dbec93738",
                "field_6304dc3393739",
                "field_6304dc629373a",
                "field_6304dc9a9373b",
                "field_6304dcd49373c",
                "field_6304dee810a78",
                "field_6304df1810a79",
                "field_6304df4410a7a",
                "field_634e48ca9ffec",
                "field_64302de9cb306",
                "field_634e48ca9ffed",
                "field_64302e31cb307",
                "field_64302e55cb308",
                "field_64302e8dcb309",
                "field_634e48ca9ffee",
                "field_634e48ca9ffef",
                "field_634e48ca9fff0",
                "field_64413e9995328",
                "field_634e48ca9fff1",
                "field_634e48ca9fff2",
                "field_634e48ca9fff3",
                "field_634e48ca9fff4",
                "field_634e48ca9fff5",
                "field_634e48ca9fff6",
                "field_634e48ca9fff7",
                "field_634e48ca9fff8",
                "field_634e48ca9fff9",
                "field_634e49d04800f",
                "field_64302ecd0360a",
                            "field_634e49d148010",
                            "field_64302f410360b",
                            "field_64302f5c0360c",
                            "field_64302f6f0360d",
                    "field_634e49d148011",
                    "field_634e49d148012",
                    "field_634e49d148013",
                    "field_64413f147b5ba",
                    "field_634e49d148014",
                    "field_634e49d148015",
                    "field_634e49d148016",
                    "field_634e49d148017",
                    "field_634e49d148018",
                    "field_634e49d148019",
                    "field_634e49d14801a",
                    "field_634e49d14801b",
                    "field_634e49d14801c",
            "field_634e49ea4801d",
                    "field_64302faf2cfc7",
                            "field_634e49eb4801e",
                            "field_643030ad2cfc8",
                            "field_643030b82cfc9",
                            "field_643030e72cfca",
                    "field_634e49eb4801f",
                    "field_634e49eb48020",
                    "field_634e49eb48021",
                    "field_64413f347b5bb",
                    "field_634e49eb48022",
                    "field_634e49eb48023",
                    "field_634e49eb48024",
                    "field_634e49eb48025",
                    "field_634e49eb48026",
                    "field_634e49eb48027",
                    "field_634e49eb48028",
                    "field_634e49eb48029",
                    "field_634e49eb4802a",)));

            echo '</div>';

            echo '<div class="js-inner-accordion__panel component-inner-accordion__panel">';

            $item = (object)$item;

            printf(
                '<h%s class="js-inner-accordion__header component-inner-accordion__header">%s</h%s>',
                $data->title_level,
                $item->title,
                $data->title_level
            );

            acf_form(array('fields' => array("field_6304e4f591a4b",
                "field_6304ea4b91a4c",
                "field_6304ec28c13d6",
                "field_6304eccbc13d7",
                "field_631084afcce33",
                "field_6430313691d85",
                "field_63108532cce34",
                "field_6430318c91d86",
                "field_6430319791d87",
                "field_643031b891d88",
                "field_63108562cce35",
                "field_631085accce36",
                "field_631085d0cce37",
                "field_63108612cce38",
                "field_6310864dcce39",
                "field_631086bfcce3a",
                "field_6310872ecce3c",
                "field_63108755cce3d",
                "field_631087cbcce3f",
                "field_63108805cce40",
                "field_63108833cce41",
                "field_6310888731cea",
                "field_631088ab31ceb",
                "field_631088e831cec",
                "field_6310893431ced",
                "field_6310895931cee",
                "field_6310898731cef",
                "field_6310a8a03ed57",
                "field_6310a8be3ed58",
                "field_6310a9e23ed59",
                "field_6310aa163ed5a",
                "field_6310aa6f3ed5b",
                "field_6310aa953ed5c",
                "field_6310aac33ed5d",
                "field_6304eeb9be187",
                "field_6430320019c3d",
                "field_6304ef9dbe188",
                "field_6430323419c3e",
                "field_6430324419c3f",
                "field_6430327b19c40",
                "field_6304f001be189",
                "field_6304f06dbe18a",
                "field_6304f0d3be18b",
                "field_6304f10bbe18c",
                "field_6304f178be18d",
                "field_6304f4812d9ef",
                "field_6304f5bb2d9f2",
                "field_6304f698c677e",
                "field_6304f734c677f",
                "field_6304f7e7c6780",
                "field_6304f8b6829c8",
                "field_6304f8f3829c9",
                "field_6304f99e829ca",
                "field_6304f9ce829cb",
                "field_6304fa25829cc",
                "field_6304fb26829cd",
                "field_6304fc15829ce",
                "field_6304fc93829cf",
                "field_6304fcbc829d0",
                "field_6304fdcd68d53",
                "field_63063f13db1e3",
                "field_643032c845f1c",
                "field_630640cbdb1e4",
                "field_6430330045f1d",
                "field_6430330f45f1e",
                "field_6430332d45f1f",
                "field_630642a5a99d9",
                "field_63075a6becd6f",
                "field_63075a90ecd70",
                "field_63075ab5ecd71",
                "field_63075ae3ecd72",
                "field_63075b63ecd73",
                "field_63075bd9ecd75",
                "field_63075d5aecd76",
                "field_63075dcbecd78",
                "field_63075e2cecd79",
                "field_63075eb5ecd7a",
                "field_63075fcfecd7b",
                "field_63076023ecd7c",
                "field_630761dbecd7d",
                "field_6307625aecd7e",
                "field_630762f1ecd7f",
                "field_6307632eecd80",
                "field_630763f2ecd81",)));

            echo '</div>';

            echo '<div class="js-inner-accordion__panel component-inner-accordion__panel">';

            $item = (object)$item;

            printf(
                '<h%s class="js-inner-accordion__header component-inner-accordion__header">%s</h%s>',
                $data->title_level,
                $item->title,
                $data->title_level
            );

            acf_form(array('fields' => array("field_63076535ecd82",
                "field_6430339ac06aa",
                "field_63076618ecd83",
                "field_643033c4c06ab",
                "field_643033e6c06ac",
                "field_643033fcc06ad",
                "field_6307665aecd84",)));

			echo '</div>';
		
		endforeach;
	
	endif;
	
	echo '</div>';
}
