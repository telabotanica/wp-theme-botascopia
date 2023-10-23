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
		$search = $_GET['q'] ?? '';
		
		$imageId = get_post_thumbnail_id(get_the_ID());
		if ($imageId) {
			$imageFull = wp_get_attachment_image_src($imageId, 'full');
		} else {
			$imageFull = null;
		}
  
		the_botascopia_module('cover', [
			'subtitle' => 'Consultez des collections et téléchargez des fiches',
            'title' => 'Les collections',
			'image' => $imageFull,
			'search' => [
				'placeholder'   => __('Rechercher une collection', 'botascopia'),
				'value' => $search,
				'pageurl' => 'collection?q',
				'id' => 'search-archive-collection'
			]
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
							$search = $_GET['q'] ?? '';
                            $collections = getCollectionPosts(['publish', 'draft', 'pending'], $search);
                            
                            foreach ($collections as $collection){
								$nbFiches = 0;
								
								// Récupération des fiches liées à chaque collection
								$nbFiches = getFiches($collection['id'])[0];
								
								// Affichage des collections
								the_botascopia_module('card-collection', [
									'href' => get_the_guid($collection['id']),
									'name' => $collection['name'],
									'nbFiches' => $nbFiches,
									'description' => $collection['description'],
									'category' => $collection['id'],
									'icon' => $collection['icon'],
									'image' => $collection['image']
								]);
                            }

							?>
                        </div>
                    </div>
                    <div class="voir-plus-container">
						<?php
						the_botascopia_module('button', [
							'tag'              => 'button',
							'title'            => 'Voir plus',
							'text'             => 'Voir plus',
							'modifiers'        => 'green-button',
							'extra_attributes' => ['id' => 'loadMoreCollections']
						]);
						?>
                    </div>
                </div>
            </div>


    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php
get_footer();
?>

