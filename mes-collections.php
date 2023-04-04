<?php
/*
    Template Name: Mes Collections
*/
?>
<?php
get_header();
?>

<div id="primary" class="content-area">
    <div class="bg-fill">

    </div>
    <main id="main" class="site-main " role="main">
		<?php
		$current_user = wp_get_current_user();
		the_botascopia_module('cover', [
			'subtitle' => $current_user->roles[0],
			'title' => $current_user->display_name
		]);
		?>
        <div class="collection-main">
            <div class="left-div">
                <div class="first-toc">
					<?php
//                    Actions collections
					the_botascopia_module('toc', [
						'title' => 'PROFIL',
						'items' => [
							[
								'text' => 'MES COLLECTIONS',
								'href' => '/collections',
								'active' => true,
								'items' => [
									[
										'text' => 'Mes collections favoris',
										'href' => '#',
										'active' => true,
									],
									[
										'text' => 'Compléter une collection',
										'href' => '#',
										'active' => false,
									],
									[
										'text' => 'Mes collections complètes',
										'href' => '#',
										'active' => false,
									],
								]
							],
						]
					]);
                    
                    echo '<div class="toc-button">';
                        the_botascopia_module('button', [
                        'tag' => 'a',
                        'href' => '/creer-une-collection',
                        'title' => 'Créer une collection',
                        'text' => 'Créer une collection',
                        'modifiers' => 'green-button',
                    ]);
                    echo '</div>';
                    
//                    Actions fiches
					echo '<div class="second-toc">';
					the_botascopia_module('toc', [
						'title' => '',
						'items' => [
							[
								'text' => 'MES FICHES',
								'href' => '/fiches',
								'active' => false,
								'items' => [
									[
										'text' => 'Mes fiches favoris',
										'href' => '#',
										'active' => false,
									],
									[
										'text' => 'Compléter une fiche',
										'href' => '#',
										'active' => false,
									],
									[
										'text' => 'Mes fiches à valider',
										'href' => '#',
										'active' => false,
									],
									[
										'text' => 'Mes fiches à complètes',
										'href' => '#',
										'active' => false,
									],
								]
							],
						]
					]);
                        echo '<div class="toc-button">';
                        the_botascopia_module('button', [
                            'tag' => 'a',
                            'href' => '#',
                            'title' => 'Créer une fiche',
                            'text' => 'Créer une fiche',
                            'modifiers' => 'green-button',
                        ]);
                        echo '</div>';
					?>
                </div>
                
                <?php
				echo '<div class="toc-button">';
				the_botascopia_module('button', [
					'tag' => 'a',
					'href' => admin_url( 'user-edit.php?user_id=' . $current_user->ID, 'http' ),
					'title' => 'Modifier mon profil',
					'text' => 'Modifier mon profil',
					'modifiers' => 'green-button outline',
					'icon_after' => ['icon' => 'cog-circle', 'color'=>'vert-clair'],
				]);
				echo '</div>';
                ?>
            </div>
        </div>
        <div class="right-div">
			<?php
			the_botascopia_module('breadcrumbs');
			?>

            <div class="display-collection-cards">
<!--                Mes collections favoris-->
                <div>
					<?php
					the_botascopia_module('title', [
						'title' => __('Mes collections favoris', 'botascopia'),
						'level' => 2,
					]);
					?>
                </div>
				
				<?php if (is_user_logged_in()) : ?>

                    <div class="display-collection-cards-items">
						<?php
						$cat_args = array(
							'hide_empty' => 0,
							'order' => 'ASC',
						);
						$categories = get_categories($cat_args);
						foreach ($categories as $category) {
							if ($category->category_parent) {
								$parentName = get_category($category->category_parent)->name;
								$existingFavorites = get_user_meta(wp_get_current_user()->ID, 'favorite_collection');
								
								if ($parentName == 'collections' && (($key = array_search($category->term_id,$existingFavorites[0])) !== false)) {
									
									// On change l'icone si la collection est dans les favoris
                                    $icone = changeFavIcon($category->term_id, $existingFavorites[0]);
									
									// Récupérer le lien de la catégorie parente et de la sous-catégorie
									$parentLink = get_term_link($category->category_parent);
									$subcatLink = get_term_link($category);
									
									$nbFiches = getNbFiches($category->term_id)[0];
									$completed = getNbFiches($category->term_id)[1];
									
									$post = get_page_by_title( $category->name, OBJECT, 'post' );
									
									$image = getPostImage($post->ID);
         
									the_botascopia_module('card-collection', [
										'href' => $subcatLink,
										'name' => $category->name,
										'nbFiches' => $nbFiches,
										'description' => $category->description,
										'category' => $category->term_id,
										'icon' => $icone,
                                        'image' => $image
									]);
								}
								
							}
						}
						?>
                    </div>
				
				<?php endif ?>
<!--            Compléter une collection-->
                <div>
					<?php
					the_botascopia_module('title', [
						'title' => __('Compléter une collection', 'botascopia'),
						'level' => 2,
					]);
					?>
                </div>
	
				<?php if (is_user_logged_in()) : ?>

                    <div class="display-collection-cards-items">
						<?php
						$cat_args = array(
							'hide_empty' => 0,
							'order' => 'ASC',
						);
						$categories = get_categories($cat_args);
                        foreach ($categories as $category) {
                            if ($category->category_parent) {
								$parentName = get_category($category->category_parent)->name;
								$existingFavorites = get_user_meta(wp_get_current_user()->ID, 'favorite_collection');
                                
                                if ($parentName == 'collections') {
									$parentLink = get_term_link($category->category_parent);
									$subcatLink = get_term_link($category);
         
									// On change l'icone si la collection est dans les favoris
									$icone = changeFavIcon($category->term_id, $existingFavorites[0]);
                                    
                                    // On vérifie le statut des fiches et on les compte
									$fiche_args = array(
										'hide_empty' => 0,
										'order' => 'ASC',
										'cat' => $category->term_id,
										'post_status' => array('publish', 'draft', 'pending')
									);
									query_posts($fiche_args);
                                    
                                    $nbFiches = 0;
                                    $completed = true;
									if ( have_posts() ) :
										while ( have_posts() ) : the_post();
                                    $postId = get_the_ID();
                                    $postName = $post_title = get_the_title($postId);;
                                            if ($postName != $category->name ){
												$status = get_post_status($postId);
												$nbFiches++;
												if ($status != 'publish'){
													$completed = false;
												}
                                            }
                                    endwhile;
                                    endif;
									// Réinitialiser la requête
									wp_reset_query();
	
									$post = get_page_by_title( $category->name, OBJECT, 'post' );
									
                                    if ($post && (!$completed || $nbFiches == 0
                                        || $post->post_status != 'publish') && $post->post_author == $current_user->ID){
                                        
										$image = getPostImage($post->ID);
          
										the_botascopia_module('card-collection', [
											'href' => $subcatLink,
											'name' => $category->name,
											'nbFiches' => $nbFiches,
											'description' => $category->description,
											'category' => $category->term_id,
											'icon' => $icone,
                                            'image'=> $image
										]);
                                    }
								}
							}
						}
      
						?>
                    </div>
	
				<?php endif ?>
<!--                Collections complètes-->
                <div>
					<?php
					the_botascopia_module('title', [
						'title' => __('Mes collections complétées', 'botascopia'),
						'level' => 2,
					]);
					?>
                </div>

                <div class="display-collection-cards-items">
					<?php
					$cat_args = array(
						'hide_empty' => 0,
						'order' => 'ASC',
					);
					$categories = get_categories($cat_args);
					foreach ($categories as $category) {
						if ($category->category_parent) {
							$parentName = get_category($category->category_parent)->name;
							$existingFavorites = get_user_meta(wp_get_current_user()->ID, 'favorite_collection');
				
							if ($parentName == 'collections') {
								$parentLink = get_term_link($category->category_parent);
								$subcatLink = get_term_link($category);
					
								// On change l'icone si la collection est dans les favoris
								$icone = changeFavIcon($category->term_id, $existingFavorites[0]);
					
								// On vérifie le statut des fiches et on les compte
								$fiche_args = array(
									'hide_empty' => 0,
									'order' => 'ASC',
									'cat' => $category->term_id,
									'post_status' => array('publish', 'draft', 'pending')
								);
								query_posts($fiche_args);
					
								$nbFiches = 0;
								$completed = true;
								if ( have_posts() ) :
									while ( have_posts() ) : the_post();
										$postId = get_the_ID();
										$postName = $post_title = get_the_title($postId);;
										if ($postName != $category->name ){
											$status = get_post_status($postId);
											$nbFiches++;
											if ($status != 'publish'){
												$completed = false;
											}
										}
									endwhile;
								endif;
								// Réinitialiser la requête
								wp_reset_query();
					
								$post = get_page_by_title( $category->name, OBJECT, 'post' );
        
								if ($post && $completed && $nbFiches != 0 && $post->post_status =='publish' &&
                                    $post->post_author == $current_user->ID){
                                    $image = getPostImage($post->ID);
         
									the_botascopia_module('card-collection', [
										'href' => $subcatLink,
										'name' => $category->name,
										'nbFiches' => $nbFiches,
										'description' => $category->description,
										'category' => $category->term_id,
										'icon' => $icone,
                                        'image' => $image
									]);
								}
							}
						}
					}
		
					?>
                </div>
            </div>
        </div>
</main><!-- .site-main -->
</div><!-- .content-area -->

<?php
get_footer();
?>

