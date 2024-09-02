<?php
require_once 'inc/walker.php';

function botascopia_module_toc($data) {

  echo '<div class="toc">';

  if (isset($data->title)){
	  the_botascopia_module('title', [
		  'title' => __($data->title, 'botascopia'),
		  'level' => 2,
		  'modifiers' => ['toc-title', 'with-border-bottom']
	  ]);
  }
  

  echo '<ul class="toc-items">';

  if ( isset($data->items) ) :

    foreach ($data->items as $item) :
      $item = (object) $item;

      echo '<li class="toc-item">';

        if ( isset($item->text) ) {
          printf(
            '<a href="%s" class="toc-item-link">%s</a>',
            esc_url( $item->href ),
//            
            $item->text
          );
        }

        if ( isset($item->items) ) :

          echo '<ul class="toc-subitems">';
		
          foreach ($item->items as $subitem) :
            $subitem = (object) $subitem;
            // Tableau d'objets Taxonomies
            if (gettype($subitem) === 'object' && get_class($subitem) === 'WP_Term') :
              $subitem->text = $subitem->name;
              $subitem->href = '#' . $subitem->slug;

            // Tableau simple
            elseif (gettype($subitem) === 'array') :
              $subitem = (object) $subitem;

            endif;
            
            echo '<li class="toc-subitem">';
              printf(
                '<a href="%s" class="toc-subitem-link">%s%s</a>',
                esc_url( $subitem->href ),
                null,
                $subitem->text
              );
            echo '</li>';

          endforeach;

          echo '</ul>';

        endif;

      echo '</li>';
    endforeach;

  endif;

  echo '</ul>';
  echo '</div>';
}
