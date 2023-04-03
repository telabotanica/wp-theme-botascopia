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
//			'search' => [
//				'index' => 'collections',
//				'placeholder' => __('Rechercher une collection...', 'botascopia'),
//				'instantsearch' => false,
//				'pageurl' => 'collections'
//			]
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
                        <div>
							<?php
							the_botascopia_module('title', [
								'title' => __('Les collections', 'botascopia'),
								'level' => 2,
							]);
							?>
                        </div>

                        <div id="collections-container" class="display-collection-cards-items">
							<?php
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
											if (($key = array_search($category->term_id, $existingFavorites[0])) !== false) {
												$icone = ['icon' => 'star', 'color' => 'blanc'];
											} else {
												$icone = ['icon' => 'star-outline', 'color' => 'blanc'];
											}
										else:
											$icone = ['icon' => 'star-outline', 'color' => 'blanc'];
										endif;
										
										the_botascopia_module('card-collection', [
											'href' => $category->slug,
											'name' => $category->name,
											'nbFiches' => $category->count,
											'description' => $category->description,
											'category' => $category->term_id,
											'icon' => $icone
										]);
									}
									
								}
							}
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
