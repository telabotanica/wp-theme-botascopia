<?php
/*
    Template Name: create-collection
*/
?>
<?php
get_header();

if (is_user_logged_in()):
	$current_user = wp_get_current_user();
	$userId = $current_user->ID;
else:
	$userId = '';
endif;
?>

<div id="primary" class="content-area">

    <main id="main" class="site-main " role="main">
		<?php
		the_botascopia_module('cover', [
			'backgroundColor' => 'var(--rose-pale)',
			'modifiers'       => ['cover-create-collection']
		]);
		
		if (is_user_logged_in()) :
		?>

        <form id="new-post-form" method="post" enctype="multipart/form-data">
            <div class="new-collection-cover-area">
                <div class="new-titre-collection-block">
                    <input type="hidden" name="meta-type" value="collection">
                    <input type="hidden" name="action" value="create_new_collection">
                    <label for="post-title" class="new-collection-title">Titre de la collection</label>
                    <input type="text" name="post-title" id="post-title" class="description-area" placeholder="Nom de la collection" height="20px" required>
                </div>
                
                <div class="new-photo-collection-block">
                    <input type="file" class="inputfile" name="post-thumbnail" id="post-thumbnail" accept="image/*">
                    <label for="post-thumbnail">
                        <figure>
                            <svg fill="#000000" height="70px" width="70px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 490
                            490" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <polygon points="222.031,490 267.969,490 267.969,267.969 490,267.969 490,222.031 267.969,222.031 267.969,0 222.031,0 222.031,222.031 0,222.031 0,267.969 222.031,267.969 "></polygon> </g></svg>
                        </figure>
                        <span class="upload-label">Ajouter une photo</span>
                        <span class="upload-explanation">820 x 320 pixels maximum</span>
                        <span class="upload-explanation">10 Mo</span>
                        <span class="upload-explanation">Formats: png, jpg</span>
                    </label>
                    <img src="#" id="image-preview" alt="Aperçu de l'image" style="display: none;">
                </div>
            </div>

            <div class="new-collection-main">
                <div class="new-description-collection-block">
                    <label for="post-description" class="new-collection-title">Description de la collection</label>
                    <textarea name="post-description" id="post-description" rows="6" placeholder="500 caractères maximum" maxlength="500" required></textarea>
                </div>
                <h2 class="new-collection-title">
                    Ajouter des fiches
                </h2>
                
                <!-- Section Ajout de fiches -->
                <div id="section-ajout-fiches" class="display-fiches-cards-items">
                    <div class="existing-fiches"></div>
                    <div id="ouvrir_popup_ajouter_fiche" class="card-fiche card">
                        
                        <figure class="card-fiche-image" alt="ajouter fiche" title="ajouter fiche">
                            <svg fill="#000000" height="50px" width="50px" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 490
                        490" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g>
                                <g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g>
                                <g id="SVGRepo_iconCarrier">
                                    <polygon
                                            points="222.031,490 267.969,490 267.969,267.969 490,267.969 490,222.031 267.969,222.031 267.969,0 222.031,0 222.031,222.031 0,222.031 0,267.969 222.031,267.969 "></polygon>
                                </g></svg>
                        </figure>
                        
                        <div class="card-fiche-body">
                            <a href="#" class="">
                                <span class="card-fiche-title">AJOUTER UNE FICHE</span>
                            </a>
                        </div>
                    </div>
                    
                    <?php

                    /*
                    $data = [];
                    $args = array(
                        'post_type'      => 'post',
                        'posts_per_page' => 10,
                        'post_status'    => array('publish', 'draft', 'pending', 'private'),
                        'order'          => 'ASC',
                        'orderby'        => 'meta_value',
                        'meta_key'       => 'nom_scientifique'
                    );
                    
                    $query = new WP_Query($args);
                    
                    if ($query->have_posts()) :
                        while ($query->have_posts()) : $query->the_post();
                            $post_id   = get_the_ID();
                            $post_name = get_post_meta($post_id, 'nom_scientifique', true);
                            
                            $post_species = get_post_meta(get_the_ID(), 'famille', true);
                            $post_imageId = get_post_thumbnail_id($post_id);
                            $post_imageFull = wp_get_attachment_image_src($post_imageId, 'full');
                            $icone = ['icon' => 'star-outline', 'color' => 'blanc'];
                            $ficheTitle = get_the_title();
                            
                            if (is_user_logged_in() && get_user_meta(wp_get_current_user()->ID, 'favorite_fiche')) :
                                
                                $ficheFavorites = get_user_meta(wp_get_current_user()->ID, 'favorite_fiche');
                                
                                if ($ficheFavorites && ($key = array_search($post_id, $ficheFavorites[0])) !== false) {
                                    $icone = ['icon' => 'star', 'color' => 'blanc'];
                                }
                            endif;
                            
                            the_botascopia_module('card-fiche', [
                                'image'            => $post_imageFull,
                                'name'             => $post_name,
                                'species'          => $post_species,
                                'icon'             => $icone,
                                'id'               => 'fiche-'.$post_id,
                                'extra_attributes' => ['data-user-id'     => $userId,
                                                       'data-fiche-id'    => $post_id,
                                                       'data-fiche-name'  => $post_name,
                                                       'data-fiche-url'   => get_permalink(),
                                                       'data-fiche-title' => $ficheTitle
                                ]
                            ]);
                            
                            echo '<option value="'.esc_attr($post_id).'">'.esc_html($post_name).'</option>';
 
                            if($post_imageFull){
                                $post_imageFull = $post_imageFull[0];
                            } else {
                                $post_imageFull = get_template_directory_uri() . '/images/logo-botascopia@2x.png';
                            }
                            $data[] = [
                                'id'      => $post_id,
                                'name'    => $post_name,
                                'species' => $post_species,
                                'image'   => $post_imageFull, // Utilisez [0] pour obtenir l'URL de l'image
                            ];
                        endwhile;
                        wp_reset_postdata();
                    endif;
                    */
                    ?>
                </div>

                <div class="new-collection-buttons">
                    <div>
                    <?php
					the_botascopia_module('button', [
						'tag' => 'a',
						'href' => '#',
						'title' => 'Annuler',
						'text' => 'Annuler',
						'modifiers' => 'purple-button outline return-button',
					]);
                    ?>
                    </div>
                    
                    <div>
                        <input type="submit" value="VALIDER" title="Créer la collection" class="button green-button new-collection-submit-button">
                        
						<?php wp_nonce_field('new-post-collection'); ?>
                    </div>
                </div>
            </div>
        </form>
        
        <?php
        else :
        
        echo ('
        <div><p>Vous devez être connecté pour accéder à cette page</p></div>
        ');
        
        endif;
        ?>
    </main>
</div>

<?php
get_footer();
?>
