<?php function botascopia_module_categories_labels($data) {
  $defaults = [
    'items' => []
  ];

  $data = botascopia_styleguide_data($defaults, $data);

  if ( empty($data->items) ) {
    foreach ( get_the_category() as $category ) {
      $data->items[] = [
        'href' => get_category_link( $category->term_id ),
        'text' => $category->name
      ];
    }
  }

  echo '<span class="categories-labels">';

    foreach ($data->items as $item) {
      printf(
        '<a href="%s" rel="category" title="%s">%s</a>',
        $item['href'],
        $item['text'],
        $item['text']
      );
    }

  echo '</span>';
}
