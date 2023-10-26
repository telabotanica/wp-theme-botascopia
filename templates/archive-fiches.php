<?php
/*
    Template Name: Fiches
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
		$legende = get_post(get_post_thumbnail_id())->post_excerpt;
		$licence = '';
		
		if ($legende){
			$licence = $legende .', licence CC-BY-SA';
		}
  
		the_botascopia_module('cover', [
			'subtitle' => 'Consultez et téléchargez des fiches',
            'title' => 'Les fiches',
			'image' => $imageFull,
            'search' => [
				'placeholder'   => __('Rechercher une fiche', 'botascopia'),
                'value' => $search,
                'pageurl' => 'fiches?q',
                'id' => 'search-archive-fiches'
            ],
			'licence' => $licence
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
                        <div id="fiches-container" class="display-fiches-cards-items">
                            <?php

							$search = $_GET['q'] ?? '';

                            $fiches = getPublishedFiches($search);

                            foreach ($fiches as $fiche){
                                the_botascopia_module('card-fiche', [
                                    'href' => $fiche['href'],
                                    'image' => $fiche['image'],
                                    'name' => $fiche['name'],
                                    'species' => $fiche['species'],
                                    'icon' => $fiche['icon'],
                                    'id' => 'fiche-' . $fiche['id'],
                                    'extra_attributes' => ['data-user-id' => $fiche['extra_attributes']['data-user-id'], 'data-fiche-id' => $fiche['extra_attributes']['data-fiche-id'], 'data-fiche-name' => $fiche['extra_attributes']['data-fiche-name'], 'data-fiche-url' => $fiche['extra_attributes']['data-fiche-url'], 'data-fiche-title' => $fiche['extra_attributes']['data-fiche-title']]
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
							'extra_attributes' => ['id' => 'loadMoreFiches']
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

