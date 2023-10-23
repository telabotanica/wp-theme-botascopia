<?php

function getPublishedFiches(){
    $ficheFavorites = [];
	$current_user = "";
	$current_user_id = "";
	
    if (is_user_logged_in() && get_user_meta(wp_get_current_user()->ID, 'favorite_fiche')):
        $ficheFavorites = get_user_meta(wp_get_current_user()->ID, 'favorite_fiche');
    endif;

    if (is_user_logged_in()) :
        $current_user = wp_get_current_user();
        $current_user_id = $current_user->ID;
    endif;

    $posts = new WP_Query([
            'post_type' => 'post',
            'posts_per_page' => -1,
            'post_status' => ['publish'],
            'order'          => 'ASC',
            'orderby'        => 'meta_value',
            'meta_key'       => 'nom_scientifique',
        ]);

    $fiches = [];

    if ($posts->have_posts()) :
        while ($posts->have_posts()) : $posts->the_post();
            // Afficher ici les informations sur chaque article de type "post" connecté
            $name = get_post_meta(get_the_ID(), 'nom_scientifique', true);
            $species = get_post_meta(get_the_ID(), 'famille', true);
            $image = get_the_post_thumbnail_url();
            $id = get_the_ID();
            $ficheTitle = get_the_title();

            if (is_user_logged_in() && get_user_meta(wp_get_current_user()->ID, 'favorite_fiche') && ($key = array_search($id, $ficheFavorites[0]))
                !==
                false) :
                $icone = ['icon' => 'star', 'color' => 'blanc'];
            else:
                $icone = ['icon' => 'star-outline', 'color' => 'blanc'];
            endif;

                $href = get_permalink();

                $fiches[] = [
                    'href' => $href,
                    'image' => $image,
                    'name' => $name,
                    'species' => $species,
                    'icon' => $icone,
                    'id' => 'fiche-'.$id,
                    'extra_attributes' => ['data-user-id' => $current_user_id, 'data-fiche-id' => $id, 'data-fiche-name' => $name, 'data-fiche-url' => $href, 'data-fiche-title' => $ficheTitle]
                ];

        endwhile;
    endif;
    wp_reset_postdata();

    return $fiches;
}

function getMesFiches($status, $role, $userId, $userFavorite, $editorId){
	
	$existingFavorites = [];
	$data = [];
	
	if (get_user_meta($userFavorite, 'favorite_fiche')):
		$existingFavorites = get_user_meta($userFavorite, 'favorite_fiche');
	endif;

	$args = array(
		'post_type' => 'post',
		'post_status' => $status,
		'showposts' => -1,
		'order'          => 'ASC',
		'orderby'        => 'meta_value',
		'meta_key'       => 'nom_scientifique',
	);
	
	if ($userId){
		if ($role == 'contributor' || $role == 'administrator'){
			$args['author'] =  $userId;
		}
	} else {
		$userId = $userFavorite;
		if ($existingFavorites && $existingFavorites[0]){
			$args['post__in'] = $existingFavorites[0];
		} else {
			return null;
		}
	}
	
	if ($editorId){
		$args['meta_query'] = array(
			array(
				'key'   => 'Editor',
				'value' => $editorId,
				'compare' => 'IS',
			)
		);
	}

	$cpt_query = new WP_Query($args);
	if ($cpt_query->have_posts()) {
		while ($cpt_query->have_posts()) {
			$cpt_query->the_post();
			
			$name = get_post_meta(get_the_ID(), 'nom_scientifique', true);
			$species = get_post_meta(get_the_ID(), 'famille', true);
			$image = get_the_post_thumbnail_url();
			$id = get_the_ID();
			$ficheTitle = get_the_title();
			$favorite = false;
			$href = get_the_permalink();
			$status = get_post_status();
			$fiche_author_id = get_post_field('post_author', $id);
			$fiche_author_info = get_userdata($fiche_author_id);
			$fiche_author_roles = $fiche_author_info->roles[0];
			$popupClass = '';
			$isEditor = false;
			$editor = '';
			
			// Affichae de l'icone favoris
			if ($existingFavorites && get_user_meta($userFavorite, 'favorite_fiche') && ($key = array_search($id, $existingFavorites[0]))
				!==
				false) :
				$icone = ['icon' => 'star', 'color' => 'blanc'];
				$favorite = true;
			else:
				$icone = ['icon' => 'star-outline', 'color' => 'blanc'];
			endif;

			// Changement des liens sur la fiche selon le role et status
			switch ($role){
			case 'contributor':
				if ($status == 'draft'){
					if ($userId != $fiche_author_id && $fiche_author_roles != 'contributor'){
						$popupClass = 'fiche-non-reserve';
						$href = '#';
					}
					
					if ($userId == $fiche_author_id){
						$href = '/formulaire/?p='.get_the_title();
					}
				} elseif ($status == 'pending' && $userId != $fiche_author_id){
					$href = '#';
				}
				break;
				
			case 'editor':
				if ($status == 'pending'){
					$editor = get_post_meta($id, 'Editor', true);
					
					if ($editor == $userId || $editor == 0 || !$editor){
						$isEditor = true;
						$href = '/formulaire/?p='.get_the_title();
					}
				}
				break;
			
			case 'administrator':
				if ($status == 'draft' && $userId == $fiche_author_id){
					$href = '/formulaire/?p='.get_the_title();
				}
				
				break;
			}
			
			$data[] = [
				'href' => $href,
				'image' => $image,
				'name' => $name,
				'species' => $species,
				'icon' => $icone,
				'popup' => $popupClass,
				'id' => 'fiche-'.$id,
				'extra_attributes' => ['data-user-id' => $userId, 'data-fiche-id' => $id, 'data-fiche-name' => $name, 'data-fiche-url' => get_permalink(), 'data-fiche-title' =>
					$ficheTitle],
				'favorite' => $favorite,
				'isEditor' => $isEditor,
				'editor' => $editor
			];
			
		}
	}
	
	return $data;
}