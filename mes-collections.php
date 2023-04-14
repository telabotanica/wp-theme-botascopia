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
		if (is_user_logged_in()) :
			$current_user = wp_get_current_user();
			$userId = $current_user->ID;
			$role = $current_user->roles[0];
			$displayName = $current_user->display_name;
			if (get_user_meta(wp_get_current_user()->ID, 'favorite_collection')):
				$existingFavorites = get_user_meta(wp_get_current_user()->ID, 'favorite_collection');
			endif;
			
		else:
			$userId = 0;
			$role = '';
			$displayName = '';
		endif;
		$posts = getCollectionPosts('any');
		
		the_botascopia_module('cover', [
			'subtitle' => $role,
			'title' => $displayName
		]);
		?>
		<div class="collection-main" id="mes-collections">
			<div class="left-div">
				<div class="first-toc">
					<?php
					//                    Actions collections
					if (is_user_logged_in()) :
						$collectionHref = site_url().'/'.get_page_uri();
					else:
						$collectionHref = get_post_type_archive_link('collection');
					endif;
					the_botascopia_module('toc', [
						'title' => 'PROFIL',
						'items' => [
							[
								'text' => 'MES COLLECTIONS',
								'href' => $collectionHref,
								'active' => true,
								'items' => [
									[
										'text' => 'Mes collections favoris',
										'href' => '#collections-favoris',
										'active' => true,
									],
									[
										'text' => 'Compléter une collection',
										'href' => '#collection-a-completer',
										'active' => false,
									],
									[
										'text' => 'Mes collections complètes',
										'href' => '#mes-collections-completes',
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
										'text' => 'Mes fiches à complèter',
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
				if (is_user_logged_in()) :
					echo '<div class="toc-button">';
					the_botascopia_module('button', [
						'tag' => 'a',
						'href' => admin_url('user-edit.php?user_id='.$userId, 'http'),
						'title' => 'Modifier mon profil',
						'text' => 'Modifier mon profil',
						'modifiers' => 'green-button outline',
						'icon_after' => ['icon' => 'cog-circle', 'color' => 'vert-clair'],
					]);
					echo '</div>';
				endif;
				?>
			</div>
		</div>
		<div class="right-div">
			<?php
			the_botascopia_module('breadcrumbs');
			?>
			
			<div class="display-collection-cards">
				<!--                Mes collections favoris-->
				<div id="collections-favoris">
					<?php
					if (is_user_logged_in()) :
					
					the_botascopia_module('title', [
						'title' => __('Mes collections favorites', 'botascopia'),
						'level' => 2,
					]);
					?>
				</div>
				
				<div class="display-collection-cards-items">
					<?php
					foreach ($posts as $post) {
						if (get_user_meta(wp_get_current_user()->ID, 'favorite_collection') && ($key = array_search
							($post['id'], $existingFavorites[0])) !== false) {
							
							the_botascopia_module('card-collection', [
								'href' => $post['href'],
								'name' => $post['name'],
								'nbFiches' => $post['nbFiches'],
								'description' => $post['description'],
								'category' => $post['id'],
								'icon' => $post['icon'],
								'image' => $post['image']
							]);
						}
					}
					?>
				</div>
				<!--            Compléter une collection-->
				<div id="collection-a-completer">
					<?php
					the_botascopia_module('title', [
						'title' => __('Compléter une collection', 'botascopia'),
						'level' => 2,
					]);
					?>
				</div>
				
				<div class="display-collection-cards-items">
					<?php
					
					foreach ($posts as $post) {
						if (( !$post['completed'] || $post['status'] != 'publish')) {
							
							the_botascopia_module('card-collection', [
								'href' => $post['href'],
								'name' => $post['name'],
								'nbFiches' => $post['nbFiches'],
								'description' => $post['description'],
								'category' => $post['id'],
								'icon' => $post['icon'],
								'image' => $post['image']
							]);
						}
					}
					
					?>
				</div>
				<!--                Collections complètes-->
				<div id="mes-collections-completes">
					<?php
					the_botascopia_module('title', [
						'title' => __('Mes collections complétées', 'botascopia'),
						'level' => 2,
					]);
					?>
				</div>
				
				<div class="display-collection-cards-items">
					<?php
					$completedCollection = false;
					foreach ($posts as $post) {
						if ($post['completed'] && $post['status'] == 'publish' && $post['nbFiches'] != 0 &&
							$post['author'] == $userId) {
							$completedCollection = true;
							the_botascopia_module('card-collection', [
								'href' => $post['href'],
								'name' => $post['name'],
								'nbFiches' => $post['nbFiches'],
								'description' => $post['description'],
								'category' => $post['id'],
								'icon' => $post['icon'],
								'image' => $post['image']
							]);
						}
					}
					if ( !$completedCollection) {
						echo('
                            <div>
                            Vous n\'avez pas encore de collections, voulez vous en créer une ?
                            </div>
                            ');
						the_botascopia_module('button', [
							'tag' => 'a',
							'href' => '#',
							'title' => 'Créer une collection',
							'text' => 'Créer une collection',
							'modifiers' => 'green-button',
						]);
					}
					endif;
					?>
				</div>
			</div>
		</div>
	</main><!-- .site-main -->
</div><!-- .content-area -->

<?php
get_footer();
?>

