<?php function botascopia_component_accordion($data) {

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

    if ( $data->items ):

        foreach ($data->items as $item) :

            echo '<div class="js-accordion__panel component-accordion__panel">';

            $item = (object) $item;

            printf(
                '<h%s class="js-accordion__header component-accordion__header">%s</h%s>',
                $data->title_level,
                $item->title,
                $data->title_level
            );

//            echo $item->content;
           acf_form($item->content);
            echo '</div>';

        endforeach;

    endif;

    echo '</div>';
}
