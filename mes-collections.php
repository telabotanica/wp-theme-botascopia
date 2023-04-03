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
                        'href' => '#',
                        'title' => 'Créer une collection',
                        'text' => 'Créer une collection',
                        'modifiers' => 'green-button',
                    ]);
                    echo '</div>';
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
								
								if ($parentName == 'collections' && (($key = array_search($category->term_id,
                                                                                          $existingFavorites[0])) !== false)) {
									
									// On change l'icone si la collection est dans les favoris
									if (($key = array_search($category->term_id, $existingFavorites[0])) !== false) {
										$icone = ['icon' => 'star', 'color' => 'blanc'];
									} else {
										$icone = ['icon' => 'star-outline', 'color' => 'blanc'];
									}
									
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
				
				<?php endif ?>
            </div>
        </div>
</main><!-- .site-main -->
</div><!-- .content-area -->

<?php
get_footer();
?>
