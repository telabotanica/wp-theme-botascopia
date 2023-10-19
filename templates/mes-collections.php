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
		$posts = getCollectionPosts(['draft', 'pending', 'publish', 'private']);
		
		the_botascopia_module('cover', [
			'subtitle' => $role,
			'title' => $displayName
		]);
		?>
		<div class="collection-main" id="mes-collections">
            <?php
			if (is_user_logged_in()) :
            ?>
			<div class="left-div">
				<div class="first-toc">
					<?php
					// Actions collections
					if (is_user_logged_in()) :
						$collectionHref = home_url().'/'.get_page_uri();
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
						'href' => home_url() . '/profil/mes-collections/creer-une-collection/',
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
//					the_botascopia_module('button', [
//						'tag' => 'a',
//						'href' => '#',
//						'title' => 'Créer une fiche',
//						'text' => 'Créer une fiche',
//						'modifiers' => 'green-button',
//					]);
					echo '</div>';
					?>
				</div>
				
				<?php
				if (is_user_logged_in()) :
					echo '<div class="toc-button" id="collection-modif-profil">';
//					the_botascopia_module('button', [
//						'tag' => 'a',
//						'href' => admin_url('user-edit.php?user_id='.$userId, 'http'),
//						'title' => 'Modifier mon profil',
//						'text' => 'Modifier mon profil',
//						'modifiers' => 'green-button outline',
//						'icon_after' => ['icon' => 'cog-circle', 'color' => 'vert-clair'],
//					]);
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
                    $hasFavorite = false;
					foreach ($posts as $post) {
						if (get_user_meta(wp_get_current_user()->ID, 'favorite_collection') && ($key = array_search
							($post['id'], $existingFavorites[0])) !== false) {
							
							if ($post['status'] == 'private' && $current_user->ID != $post['author']){
								$nbFiches = 'x';
							} else {
								$nbFiches = $post['nbFiches'];
							}
       
							the_botascopia_module('card-collection', [
								'href' => $post['href'],
								'name' => $post['name'],
								'nbFiches' => $nbFiches,
								'description' => $post['description'],
								'category' => $post['id'],
								'icon' => $post['icon'],
								'image' => $post['image']
							]);
							$hasFavorite = true;
						}
					}
					
					if ( !$hasFavorite) {
						echo('
                            <div>
                            Vous n\'avez pas encore de collection favorite.
                            </div>
                            ');
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
                        if ($userId && $userId == $post['author']){
							if (( !$post['completed'] || $post['status'] != 'publish')){
								$nbFiches = $post['nbFiches'];
                                echo ('<div id="profil-collection-'.$post["id"].'">');
								the_botascopia_module('card-collection', [
									'href' => $post['href'],
									'name' => $post['name'],
									'nbFiches' => $nbFiches,
									'description' => $post['description'],
									'category' => $post['id'],
									'icon' => $post['icon'],
									'image' => $post['image']
								]);
								$href = home_url() . '/profil/mes-collections/creer-une-collection/?edit=true&c='.$post['id'];
                                echo ('
                                <div class="update-collection-buttons"><div>');
								the_botascopia_module('button', [
									'tag' => 'a',
									'href' => $href,
									'title' => 'modifier la collection',
									'text' => 'Modifier la collection',
									'modifiers' => 'green-button'
								]);
                                echo ('</div><div>');
								the_botascopia_module('button', [
									'tag' => 'button',
									'title' => 'supprimer la collection',
									'text' => 'supprimer la collection',
									'modifiers' => 'purple-button delete-collection-button',
									'extra_attributes' => ['id' => 'delete-collection-'.$post['id'], 'data-collection-id' => $post['id']]
								]);
								echo ('</div></div></div>');
                            }
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
							echo ('<div id="profil-collection-'.$post["id"].'">');
							the_botascopia_module('card-collection', [
								'href' => $post['href'],
								'name' => $post['name'],
								'nbFiches' => $post['nbFiches'],
								'description' => $post['description'],
								'category' => $post['id'],
								'icon' => $post['icon'],
								'image' => $post['image']
							]);
							$href = home_url() . '/profil/mes-collections/creer-une-collection/?edit=true&c='.$post['id'];
							echo ('
                                <div class="update-collection-buttons"><div>');
							the_botascopia_module('button', [
								'tag' => 'a',
								'href' => $href,
								'title' => 'modifier la collection',
								'text' => 'Modifier la collection',
								'modifiers' => 'green-button'
							]);
							echo ('</div><div>');
							the_botascopia_module('button', [
								'tag' => 'button',
								'title' => 'supprimer la collection',
								'text' => 'supprimer la collection',
								'modifiers' => 'purple-button delete-collection-button',
								'extra_attributes' => ['id' => 'delete-collection-'.$post['id'], 'data-collection-id' => $post['id']]
							]);
							echo ('</div></div></div>');
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
							'href' => home_url() . '/profil/mes-collections/creer-une-collection/',
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
	<?php
	else :
		echo ('
        <div><p>Vous devez être connecté pour accéder à cette page</p></div>
        ');
	endif;
	?>
	</main><!-- .site-main -->
</div><!-- .content-area -->

<?php
get_footer();
?>

