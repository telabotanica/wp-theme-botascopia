<?php

function getPublishedFiches(){
    $ficheFavorites = [];
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