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

    <main id="main" class="site-main new-collection-main" role="main">
		<?php
		if (is_user_logged_in()) :
			$edit = isset($_GET['edit']) ? $_GET['edit'] : false;

            if ($edit == 'true'){
                $edit = true;
                $collection_id = isset($_GET['c']) ? $_GET['c'] : '';
                if ($collection_id){
                    $collection = get_post($collection_id);
                }
				
				if (!isset($collection) || $collection->post_type != 'collection' || !$collection_id){
					echo ('<div class="error">Erreur: La collection n\'est pas valide</div>');
					$edit = false;
				}
				
				if (isset($collection) && $userId != $collection->post_author){
					echo ('<div class="error">Erreur lors de la récupération de la collection</div>');
					$edit = false;
				}

            } else {
				$edit = false;
            }
            
        the_botascopia_module('cover', [
			'backgroundColor' => 'var(--rose-pale)',
			'modifiers'       => ['cover-create-collection']
		]);
  
		?>

        <form id="new-post-form" method="post" enctype="multipart/form-data">
            <div class="new-collection-cover-area">
                <div class="new-titre-collection-block">
                    <input type="hidden" name="meta-type" value="collection">
                    <input type="hidden" name="action" value="create_new_collection">
                    <label for="post-title" class="new-collection-title">Titre de la collection</label>
                    <input type="text" name="post-title" id="post-title" class="description-area" placeholder="Nom de la collection" height="20px" required <?php
					if ($edit){
						echo 'value="' . esc_attr($collection->post_title) . '"';
					}
					?>>
                </div>
                
                <div class="new-photo-collection-block">
                    <input type="file" class="inputfile hidden" name="post-thumbnail" id="post-thumbnail" accept="image/*">
                    <label for="post-thumbnail">
                        <figure class="add-photo-cross">
                            <svg fill="#000000" version="1.1" id="Capa_1" xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" viewBox="0 0 490
                            490" xml:space="preserve"><g id="SVGRepo_bgCarrier" stroke-width="0"></g><g id="SVGRepo_tracerCarrier" stroke-linecap="round" stroke-linejoin="round"></g><g id="SVGRepo_iconCarrier"> <polygon points="222.031,490 267.969,490 267.969,267.969 490,267.969 490,222.031 267.969,222.031 267.969,0 222.031,0 222.031,222.031 0,222.031 0,267.969 222.031,267.969 "></polygon> </g></svg>
                        </figure>
                        <span class="upload-label">Ajouter une photo</span>
                        <span class="upload-explanation">820 x 320 pixels maximum</span>
                        <span class="upload-explanation">10 Mo maximum</span>
                        <span class="upload-explanation">Formats: png, jpg</span>
                    </label>
                    <?php
                    if ($edit && get_the_post_thumbnail_url($collection_id, 'full')){
                        $image = get_the_post_thumbnail_url($collection_id, 'full');
						echo('<img src="'.$image.'" id="image-preview" alt="Aperçu de l\'image" style="display: block;">');
                    } else {
                        echo ('<img src="#" id="image-preview" alt="Aperçu de l\'image" style="display: none;">');
                    }
                    ?>
                    
                </div>
            </div>

            <div class="new-collection-container">
                <div class="new-description-collection-block">
                    <label for="post-description" class="new-collection-title">Description de la collection</label>
                    <textarea name="post-description" id="post-description" rows="6" placeholder="500 caractères maximum" maxlength="500" required><?php if ($edit){ echo esc_textarea($collection->post_content);}?></textarea>
                </div>
                <!-- Section ajouter des participants-->
                <h2 class="new-collection-title">
                    Ajouter des participants
                </h2>
                
                <div id="section-ajout-participants">
					<?php
                    if ($edit){
                        $participantsEmails = [];
                        //TODO chercher et afficher les invitations déjà envoyées
                    }
                    
					the_botascopia_module('button', [
						'tag' => 'button',
						'href' => '#',
						'title' => 'Ajouter par mail',
						'text' => 'Ajouter par mail',
						'modifiers' => 'green-button',
                        'extra_attributes' => ['id' => 'button-ajout-participant']
					]);
					?>
                    <input id="emails-selected" type="hidden" name="participantsEmails" <?php
					if ($edit){
						echo 'value="' . esc_attr(json_encode($participantsEmails)) . '"';
					}
					?>>
                </div>
                
                <h2 class="new-collection-title">
                    Ajouter des fiches
                </h2>
                
                <!-- Section Ajout de fiches -->
                <div id="section-ajout-fiches" class="display-fiches-cards-items">
                    <div class="existing-fiches">
                        <?php
                        if ($edit){
                            $fiches = [];
                            $selectedIds = [];
							$connected_posts = new WP_Query(
								array(
									'connected_type' => 'collection_to_post',
									'connected_items' => $collection_id,
									'post_status' => 'any',
									'order'          => 'ASC',
									'orderby'        => 'meta_value',
									'meta_key'       => 'nom_scientifique',
								));
							
							if ($connected_posts->have_posts()) :
								while ($connected_posts->have_posts()) : $connected_posts->the_post();
									$post_id = get_the_ID();
									$post_species = get_post_meta(get_the_ID(), 'famille', true);
									$post_name = get_post_meta($post_id, 'nom_scientifique', true);
									$post_imageId = get_post_thumbnail_id($post_id);
									$post_imageFull = wp_get_attachment_image_src($post_imageId, 'full');
									if($post_imageFull){
										$post_imageFull = $post_imageFull[0];
									} else {
										$post_imageFull = get_template_directory_uri() . '/images/logo-botascopia@2x.png';
									}
									
									$fiches[] = [
										'id'      => $post_id,
										'name'    => $post_name,
										'species' => $post_species,
										'image'   => $post_imageFull,
									];
                                endwhile;
                            endif;
                            
                            foreach ($fiches as $fiche){?>
                            
                            <div class="card card-fiche card-selected" data-fiche-id="<?php echo $fiche['id']; ?>">
                                <a data-fiche-id="<?php echo $fiche['id']; ?>">
                                <img src="<?php echo $fiche['image']; ?>" alt="photo de <?php echo $fiche['name']; ?>" class="card-fiche-image" title="<?php echo $fiche['name']; ?>">
                                </a>
                                <div class="card-fiche-body">
                                    <a><span class="card-fiche-title"><?php echo $fiche['name']; ?></span>
                                        <span class="card-fiche-espece"><?php echo $fiche['species']; ?></span>
                                    </a>
                                </div>
                            </div>
                            
							<?php
                                $selectedIds[] = $fiche['id'];
							}
                        }
                        ?>
                        
                    </div>
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
                    <input id="fiches-selected" type="hidden" name="selectedCardIds" <?php
					if ($edit){
						echo 'value="' . esc_attr(json_encode($selectedIds)) . '"';
					}
					?>>
 
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
                        
                        <?php if($edit) : ?>
                            <input type="hidden" name="edit" value="<?php echo 'true' ?>">
                            <input type="hidden" name="collection_id" value="<?php echo esc_attr($collection_id); ?>">
                        <?php endif; ?>
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
