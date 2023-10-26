<?php function botascopia_module_search_box($data) {
  $defaults = [
	  'autocomplete'  => false,
	  'instantsearch' => false,
	  'placeholder'   => __('Rechercher une collection', 'botascopia'),
	  'value'         => get_search_query() ? : get_query_var('q', false),
	  'index'         => false,
	  'suggestions'   => false,
	  'facetFilters'  => '',
	  'modifiers'     => ['large'],
	  'pageurl'       => '',
	  'id'            => '',
	  'post'          => ''
  ];

  $data = botascopia_styleguide_data($defaults, $data);
  $data->modifiers = botascopia_styleguide_modifiers_array('search-box', $data->modifiers);

  printf(
    '<div class="%s" data-autocomplete="%s" data-instantsearch="%s" data-index="%s" data-facet-filters="%s">',
    implode(' ', $data->modifiers),
    var_export($data->autocomplete, true),
    var_export($data->instantsearch, true),
    $data->index,
    $data->facetFilters
  );
    printf(
      '<form role="search" method="get" action="%s" data-post="%s", id="%s">',
      esc_url( home_url( '/' . $data->pageurl ) ),
		$data->post,
		$data->id
    );
      if ($data->index) :
        printf(
          '<input name="in" type="hidden" value="%s" />',
          esc_attr( sanitize_key( $data->index ) )
        );
      endif;
      echo '<div class="search-box-wrapper">';
        printf(
          '<input name="q" type="text" class="search-box-input" placeholder="%s" value="%s" autocomplete="off" spellcheck="false" />',
          esc_attr( $data->placeholder ),
          esc_attr( $data->value )
        );
        printf(
          '<span class="search-box-button">%s</span>',
          get_botascopia_module('icon', ['icon' => 'search'])
        );
      echo '</div>';
	
	the_botascopia_module('button', [
		'tag' => 'button',
		'title' => 'Rechercher',
		'text' => 'Rechercher',
		'modifiers' => 'green-button',
		'extra_attributes' => ['type' => 'submit', 'id' => 'search-button']
	]);
    echo '</form>';
//var_dump($data->suggestions);
//    if ( $data->suggestions ) :
//      $suggestions = array_map(function($suggestion) {
//        return sprintf(
//          '<a href="%s">%s</a>',
//          '#' . $suggestion, // TODO compose URL to search results
//          $suggestion
//        );
//      }, $data->suggestions);
//
//      printf(
//        '<div class="search-box-suggestions">%s</div>',
//        sprintf(
//          __('Par exemple : %s...', 'botascopia'),
//          implode($suggestions, ', ')
//        )
//      );
//    endif;

  echo '</div>';
}
