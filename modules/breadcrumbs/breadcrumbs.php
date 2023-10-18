<?php function botascopia_module_breadcrumbs($data) {
  if (!isset($data->modifiers)) $data->modifiers = '';

  // Supporte les microdonnées Schema.org
  // cf. https://schema.org/BreadcrumbList

  // Génération des items selon le type de page courante
  if ( empty($data->items) ) :
	 
    // Article seul
    if ( is_single() ) :
      $category = get_the_category();
      $data->items = ['home'];
	  
	  if (get_post_type() === 'post'){
		  $data->items[] = [ 'href' => site_url().'/fiches', 'text' => 'Les fiches' ];
	  }
	  
	  if (get_post_type() === 'collection' ){
		  $data->items[] = [ 'href' => get_post_type_archive_link('collection'), 'text' => 'Les collections' ];
	  }

      // Article courant
		if (get_post_type() === 'post' ) {
			$data->items[] = ['text' => get_post_meta(get_the_ID(), 'nom_scientifique', true)];
		} else {
			$data->items[] = ['text' => get_the_title()];
		}

    // Page
    elseif ( is_page() ) :

      $data->items = ['home'];

      // Pages parentes
      $ancestors = get_post_ancestors( get_the_ID() );
      if ( !empty( $ancestors ) ) {
        foreach ( array_reverse($ancestors) as $ancestor_id ) :
          $page_parent = get_post( $ancestor_id );
          $data->items[] = [ 'href' => get_permalink( $page_parent ), 'text' => $page_parent->post_title ];
        endforeach;
      }

      // Page courante
      $data->items[] = [ 'text' => get_the_title() ];


    // Archive
    elseif ( is_archive() ) :
		
      $category = get_category( get_query_var('cat') );
      $data->items = ['home'];

	  if (get_post_type() !== 'collection'){
		  $data->items[] = [ 'href' => site_url().'/fiches', 'text' => 'Les fiches' ];
	  } else {
		  $data->items[] = [ 'href' => get_post_type_archive_link('collection'), 'text' => 'Les collections' ];
	  }

    endif;
  
  else :
	  $defaults = [
		  'items' => ['home'],
		  'modifiers' => ''
	  ];
	  $data = botascopia_styleguide_data($defaults, $data);

  endif;

  echo '<div class="breadcrumbs ' . $data->modifiers . '">';

    if ( isset($data->button) ):

      echo '<div class="breadcrumbs-button">' . $data->button . '</div>';

    endif;

    if ( isset($data->items) ):

      echo '<ol class="breadcrumbs-items" itemscope itemtype="http://schema.org/BreadcrumbList">';

      foreach ($data->items as $i => $item) :

        if ( $item === 'home' ) {
          $item = [
            'href' => site_url(),
            'text' => __( 'Accueil', 'botascopia' )
          ];
        }

        $item = (object) $item;

        if (!isset($item->modifiers)) $item->modifiers = '';
        if (!isset($item->text)) $item->text = 'Page';

        echo '<li class="breadcrumbs-item" itemprop="itemListElement" itemscope itemtype="http://schema.org/ListItem">';

        if ( isset($item->href) ) :

          $is_last = ( $i + 1 === count( $data->items ) );

          echo '<a href="' . $item->href . '" class="' . $item->modifiers . '"' . ( $is_last ? ' tabindex="-1"' : '' ) . ' itemprop="item">';
          echo '<span itemprop="name">' . $item->text . '</span>';
          echo '<meta itemprop="position" content="' . ($i + 1) . '" />';
          echo '</a>';

        else :

          echo $item->text;

        endif;

        echo '</li>';

      endforeach;

      echo '</ol>';

    endif;

  echo '</div>';

}
