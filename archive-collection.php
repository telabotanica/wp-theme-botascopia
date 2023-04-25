<?php
/*
    Template Name: Collections
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
		the_botascopia_module('cover', [
			'subtitle' => 'Consultez des collections et téléchargez des fiches',
            'title' => 'Les collections'
		]);
		?>
        <div class="collection-main">
            <div class="left-div">
                <div class="first-toc">
					<?php
					the_botascopia_module('toc', [
						'title' => 'FILTRES',
					]);
					echo '</div>';
					?>
                </div>
                <div class="right-div">
					<?php
					the_botascopia_module('breadcrumbs');
					?>

                    <div class="display-collection-cards">

                        <div id="collections-container" class="display-collection-cards-items">
                            <?php
                            // Posts de type collection
							$args = array(
								'post_type' => 'collection',
								'post_status' => array('publish', 'draft', 'pending'),
                                'order' => 'ASC'
							);
							$collection_query = new WP_Query( $args );

							if ( $collection_query->have_posts() ) {
								while ( $collection_query->have_posts() ) {
									$collection_query->the_post();
         
									$collectionName = get_the_title();
									$collection_id = get_the_ID();
                                    $description = get_the_content();
									$image = getPostImage($collection_id);
         
									if (is_user_logged_in() && get_user_meta(wp_get_current_user()->ID, 'favorite_collection')) :
										$existingFavorites = get_user_meta(wp_get_current_user()->ID, 'favorite_collection');
										$icone = changeFavIcon($collection_id, $existingFavorites[0]);
									else:
										$icone = ['icon' => 'star-outline', 'color' => 'blanc'];
									endif;
                                    
                                    $nbFiches = 0;
                                    
                                    // Récupération des fiches liées à chaque collection
                                    $nbFiches = getFiches($collection_id)[0];
                                    
                                    // Affichage des collections
									the_botascopia_module('card-collection', [
										'href' => get_the_guid($collection_id),
										'name' => $collectionName,
										'nbFiches' => $nbFiches,
										'description' => $description,
										'category' => $collection_id,
										'icon' => $icone,
										'image' => $image
									]);
								}
							} else {
								// Si aucun post trouvé
								echo 'Aucune collection trouvé';
							}
							wp_reset_postdata();
                            ?>
                            
							<?php
                            /*
							$cat_args = array(
								'hide_empty' => 0,
								'orderby' => 'name',
								'order' => 'ASC',
							);
							$categories = get_categories($cat_args);
							foreach ($categories as $category) {
								if ($category->category_parent) {
									$parentName = get_category($category->category_parent)->name;
									if ($parentName == 'collections') {
										
										// On change l'icone si la collection est dans les favoris
										if (is_user_logged_in()) :
											$existingFavorites = get_user_meta(wp_get_current_user()->ID, 'favorite_collection');
											$icone = changeFavIcon($category->term_id, $existingFavorites[0]);
										else:
											$icone = ['icon' => 'star-outline', 'color' => 'blanc'];
										endif;
										
										$nbFiches = getNbFiches($category->term_id)[0];
										$completed = getNbFiches($category->term_id)[1];
										$post = get_page_by_title( $category->name, OBJECT, 'post' );
										
										$image = getPostImage($post->ID);
										the_botascopia_module('card-collection', [
											'href' => $category->slug,
											'name' => $post->post_title,
											'nbFiches' => $nbFiches,
											'description' => $category->description,
											'category' => $category->term_id,
											'icon' => $icone,
											'image' => $image
										]);
									}
									
								}
							}
                            */
							?>
                        </div>
                    </div>
                </div>
            </div>


    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php
get_footer();
?>

