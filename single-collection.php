<?php
/*
    Template Name: collection single
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
		$post = get_queried_object();
		$collectionFavorites = get_user_meta(wp_get_current_user()->ID, 'favorite_collection');
		$ficheFavorites = get_user_meta(wp_get_current_user()->ID, 'favorite_fiche');
		$post_id = $post->ID;
		$post_author = get_the_author_meta('display_name', $post->post_author);
  
		$date = $post->post_date;
		setlocale(LC_TIME, 'fr_FR.utf8');
		$post_date = strftime('%e %B %Y', strtotime($date));
		
		$nbFiches = getFiches($post_id)[0];
		$completed = getFiches($post_id)[1];
		$image = getPostImage($post_id);
		the_botascopia_module('cover', [
			'subtitle' => '',
			'title' => '',
			'image' => $image
		]);
		
		if (is_user_logged_in()):
			$current_user = wp_get_current_user();
			$userId = $current_user->ID;
		else:
			$userId = '';
		endif;
		
		?>
        <div class="collection-main">
            <div class="left-div">
                <div class="single-collection-title">
					<?php the_botascopia_module('title', [
						'title' => __($post->name, 'botascopia'),
						'level' => 1,
					]);
					?>
                </div>
				<?php
				?>
                <div class="single-collection-buttons" id="collection-<?php echo $post_id ?>"
                     data-user-id="<?php echo $userId ?>"
                     data-category-id="<?php echo $post_id ?>">
					
					<?php the_botascopia_module('button', [
						'tag' => 'a',
						'href' => '#',
						'title' => 'Téléchargez',
						'text' => 'Téléchargez',
						'modifiers' => 'green-button',
					]); ?>
					
					<?php if (is_user_logged_in() && ($key = array_search($post_id,
																		  $collectionFavorites[0]))
						!==
						false) :
						//changer le bouton favoris si collection dans favoris ou pas
						$icone = ['icon' => 'star', 'color' => 'blanc'];
						$modifiers = 'green-button';
					else:
						$icone = ['icon' => 'star-outline', 'color' => 'vert-clair'];
						$modifiers = 'green-button outline';
					endif;
					
					the_botascopia_module('button', [
						'tag' => 'a',
						'href' => '#',
						'title' => 'Favoris',
						'text' => 'Favoris',
						'modifiers' => $modifiers,
						'icon_after' => $icone,
						'extra_attributes' => ['id' => 'fav-'.$post_id]
					]);
					
					?>
                </div>
                <div class="single-collection-export-format">
                    Formats : PDF (60Mo)
                </div>

                <a class="return-button" href="#">
					<?php the_botascopia_module('icon', [
						'icon' => 'arrow-left'
					]); ?>
                    <span>RETOUR</span>
                </a>

                <div class="single-collection-details">
                    <div class="single-collection-detail">Composée de <?php echo $nbFiches ?> fiches</div>
                    <div class="single-collection-detail">Publié le <?php echo $post_date ?></div>
                    <div class="single-collection-detail">Par <?php echo $post_author ?></div>
                </div>

            </div>
            <div class="right-div">
				<?php
				the_botascopia_module('breadcrumbs');
				?>

                <div>
					<?php the_botascopia_module('search-box',[
                            'placeholder' => 'Rechercher une fiche',
//                            'pageurl' => get_page_uri()
                    ]); ?>
                </div>

                <div class="single-collection-title-right">
					<?php the_botascopia_module('title', [
						'title' => __($post->name, 'botascopia'),
						'level' => 3
					]); ?>
                </div>

                <div>
					<?php echo $post->post_content ?>
                </div>

                <div class="display-fiches-cards-items">
					<?php
					
					$search_query = get_search_query();
					
					$connected_posts = new WP_Query(
						array(
							'connected_type' => 'collection_to_post',
							'connected_items' => $post_id,
							'nopaging' => true,
							'post_status' => array('publish', 'draft', 'pending'),
							'meta_query' => array(
								'relation' => 'OR',
								array(
									'key' => 'nom_scientifique',
									'value' => $search_query,
									'compare' => 'LIKE'
								),
								array(
									'key' => 'famille',
									'value' => $search_query,
									'compare' => 'LIKE'
								)
							)
						));
					if ($connected_posts->have_posts()) :
						while ($connected_posts->have_posts()) : $connected_posts->the_post();
							// Afficher ici les informations sur chaque article de type "post" connecté
							$name = get_post_meta(get_the_ID(), 'nom_scientifique', true);
							$species = get_post_meta(get_the_ID(), 'famille', true);
							$image = get_the_post_thumbnail_url();
							$id = get_the_ID();
							$ficheName = get_the_title();
							
							if (is_user_logged_in() && ($key = array_search($id, $ficheFavorites[0]))
								!==
								false) :
								$icone = ['icon' => 'star', 'color' => 'blanc'];
							else:
								$icone = ['icon' => 'star-outline', 'color' => 'blanc'];
							endif;
							
							the_botascopia_module('card-fiche', [
								'href' => get_permalink(),
								'image' => $image,
								'name' => $name,
								'species' => $species,
								'icon' => $icone,
								'extra_attributes' => ['id' => 'fiche-'.$id, 'data-user-id' => $userId, 'data-fiche-id' => $id]
							]);
						
						endwhile;
					endif;
					wp_reset_postdata();
					?>
                </div>

            </div>
    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php
get_footer();
?>

