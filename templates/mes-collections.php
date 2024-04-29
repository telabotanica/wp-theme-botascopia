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
		if (is_user_logged_in()){
			$current_user = wp_get_current_user();
			$userId = $current_user->ID;
			$role = $current_user->roles[0];
			$displayName = $current_user->display_name;
			if (get_user_meta(wp_get_current_user()->ID, 'favorite_collection')){
				$existingFavorites = get_user_meta(wp_get_current_user()->ID, 'favorite_collection');
			}
			
		}else{
			$userId = 0;
			$role = '';
			$displayName = '';
		}
		$posts = getCollectionPosts(['draft', 'pending', 'publish', 'private'], '');
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
			'subtitle' => getRole($role),
			'title' => $displayName,
			'image' => $imageFull,
			'licence' => $licence
		]);
		?>
	<?php
	if(is_user_logged_in()){
	?>	
		<div class="collection-main" id="mes-collections">
           
			<div class="left-div">
				<?php
					get_template_part("/templates/menu-gauche");
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
					
					
					the_botascopia_module('title', [
						'title' => __(Constantes::COLLECTIONS_FAV, 'botascopia'),
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
                            	Vous n\'avez pas encore de collections favorites.
                            </div>
                            ');
					}
					?>
				</div>
				<!--            Compléter une collection-->
				<div id="collection-a-completer">
					<?php
					the_botascopia_module('title', [
						'title' => __(Constantes::COLLECTIONS_TO_COMP, 'botascopia'),
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
									'title' => Constantes::COLLECTIONS_MODIF,
									'text' => Constantes::COLLECTIONS_MODIF,
									'modifiers' => 'green-button'
								]);
                                echo ('</div><div>');
								the_botascopia_module('button', [
									'tag' => 'button',
									'title' => Constantes::COLLECTIONS_DEL,
									'text' => Constantes::COLLECTIONS_DEL,
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
						'title' => __(Constantes::COLLECTIONS_COMP, 'botascopia'),
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
								'title' => Constantes::COLLECTIONS_MODIF,
								'text' => Constantes::COLLECTIONS_MODIF,
								'modifiers' => 'green-button'
							]);
							echo ('</div><div>');
							the_botascopia_module('button', [
								'tag' => 'button',
								'title' => Constantes::COLLECTIONS_DEL,
								'text' => Constantes::COLLECTIONS_DEL,
								'modifiers' => 'purple-button delete-collection-button',
								'extra_attributes' => ['id' => 'delete-collection-'.$post['id'], 'data-collection-id' => $post['id']]
							]);
							echo ('</div></div></div>');
						}
					}
					
					if ( !$completedCollection) {
						echo('
                            <div>
                            Vous n\'avez pas encore de collections, voulez-vous en créer une ?
                            </div>
                            ');
						if ($role != 'contributor'){
							the_botascopia_module('button', [
								'tag' => 'a',
								'href' => home_url() . '/profil/mes-collections/creer-une-collection/',
								'title' => Constantes::COLLECTION_TO_CREATE,
								'text' => Constantes::COLLECTION_TO_CREATE,
								'modifiers' => 'green-button',
							]);
						}
					}
					
					?>
				</div>
			</div>
		</div>
	<?php
	}else{
		echo ("<div><p>".Constantes::MESSAGE_CONNEXION."</p></div>");
	}
	?>
	</main><!-- .site-main -->
</div><!-- .content-area -->

<?php
get_footer();
?>

