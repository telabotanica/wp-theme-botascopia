<?php
/*
    Template Name: Mes fiches
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
			
		else:
			$userId = 0;
			$role = '';
			$displayName = '';
		endif;
  
		$imageId = get_post_thumbnail_id(get_the_ID());
		if ($imageId) {
			$imageFull = wp_get_attachment_image_src($imageId, 'full');
		} else {
			$imageFull = null;
		}
		the_botascopia_module('cover', [
			'subtitle' => $role,
			'title' => $displayName,
			'image' => $imageFull,
		]);
		?>
		<div class="collection-main" id="mes-fiches">
            <?php
			if (is_user_logged_in()) :
                if (get_user_meta($userId, 'favorite_fiche')):
                    $existingFavorites = get_user_meta($userId, 'favorite_fiche');
                endif;
            ?>
			<div class="left-div">
				<div class="first-toc">
					<?php
					// Actions collections
                    $collectionHref = home_url().'/profil/mes-collections/';

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
										'href' => $collectionHref . '/#collections-favoris',
										'active' => false,
									],
									[
										'text' => 'Compléter une collection',
										'href' => $collectionHref . '#collection-a-completer',
										'active' => false,
									],
									[
										'text' => 'Mes collections complètes',
										'href' => $collectionHref . '#mes-collections-completes',
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
                    
                    $fichesHref = home_url().'/'.get_page_uri();
					$textACompleter = 'Compléter une fiche';
					$lienACompleter = '#fiches-a-completer';
                    
                    if ($role == 'editor'){
						$textACompleter = 'Fiches dont je suis le vérificateur';
						$lienACompleter = '#fiches-en-verification';
                    }
                    
					the_botascopia_module('toc', [
						'title' => '',
						'items' => [
							[
								'text' => 'MES FICHES',
								'href' => $fichesHref,
								'active' => false,
								'items' => [
									[
										'text' => 'Mes fiches favoris',
										'href' => '#fiches-favoris',
										'active' => true,
									],
									[
										'text' => $textACompleter,
										'href' => $lienACompleter,
										'active' => false,
									],
									[
										'text' => 'Mes fiches à valider',
										'href' => '#fiches-a-valider',
										'active' => false,
									],
         
									[
										'text' => 'Mes fiches validées',
										'href' => '#mes-fiches-validees',
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
	
			</div>
		</div>
		<div class="right-div">
			<?php
			the_botascopia_module('breadcrumbs');
			?>
			
			<div class="display-collection-cards">
				<!--                Mes fiches favoris-->
				<div id="fiches-favoris">
					<?php
					the_botascopia_module('title', [
						'title' => __('Mes fiches favoris', 'botascopia'),
						'level' => 2,
					]);
					?>
				</div>
    
				<div class="display-fiches-cards-items">
					<?php
                    $hasFavorite = false;
                    
					$fichesFavorites = getMesFiches(['draft', 'pending', 'publish', 'private'], $role, null, $userId, null);
					
                    if ($fichesFavorites){
						foreach ($fichesFavorites as $fiche){
							if ($fiche['favorite']){
								$hasFavorite = true;
								
								the_botascopia_module('card-fiche', [
									'href' => $fiche['href'],
									'image' => $fiche['image'],
									'name' => $fiche['name'],
									'species' => $fiche['species'],
									'icon' => $fiche['icon'],
									'popup' => $fiche['popup'],
									'id' => $fiche['id'],
									'extra_attributes' => $fiche['extra_attributes']
								]);
							}
						}
                    }
     
					if ( !$hasFavorite) {
						echo('
                            <div>
                            Vous n\'avez pas encore de fiche favorite.
                            </div>
                            ');
					}
					?>
				</div>
				<!--            Compléter une fiche-->
                <?php if ($role == 'contributor' || $role == 'administrator'): ?>
				<div id="fiches-a-completer">
					<?php
					the_botascopia_module('title', [
						'title' => __('Compléter une fiche', 'botascopia'),
						'level' => 2,
					]);
					?>
				</div>
				
				<div class="display-fiches-cards-items">
					<?php
                    $fichesACompleter = getMesFiches('draft', $role, $userId, $userId, null);
                    
                    foreach ($fichesACompleter as $fiche){
                        the_botascopia_module('card-fiche', [
                            'href' => $fiche['href'],
                            'image' => $fiche['image'],
                            'name' => $fiche['name'],
                            'species' => $fiche['species'],
                            'icon' => $fiche['icon'],
                            'popup' => $fiche['popup'],
                            'id' => $fiche['id'],
                            'extra_attributes' => $fiche['extra_attributes']
                        ]);
                    }
					?>
				</div>
                <?php endif; ?>

                <!--            fiches dont je suis le vérificateur (profil vérificateur)  -->
				<?php if ($role == 'editor'): ?>
                    <div id="fiches-en-verification">
						<?php
						the_botascopia_module('title', [
							'title' => __('Fiches dont je suis le vérificateur', 'botascopia'),
							'level' => 2,
						]);
						?>
                    </div>

                    <div class="display-fiches-cards-items">
						<?php
						$fichesInValidation = getMesFiches('pending', $role, $userId, $userId, $userId);
						
						foreach ($fichesInValidation as $fiche){
							the_botascopia_module('card-fiche', [
								'href' => $fiche['href'],
								'image' => $fiche['image'],
								'name' => $fiche['name'],
								'species' => $fiche['species'],
								'icon' => $fiche['icon'],
								'popup' => $fiche['popup'],
								'id' => $fiche['id'],
								'extra_attributes' => $fiche['extra_attributes']
							]);
						}
						?>
                    </div>
				<?php endif; ?>
                
                <!--            Fiches à valider-->
                <div id="fiches-a-valider">
					<?php
					the_botascopia_module('title', [
						'title' => __('Fiches à valider', 'botascopia'),
						'level' => 2,
					]);
					?>
                </div>

                <div class="display-fiches-cards-items">
					<?php
					$fichesAValider = getMesFiches('pending', $role, $userId, $userId, null);
					
					foreach ($fichesAValider as $fiche){
                        if (!$fiche['editor'] || $fiche['editor'] == 0){
							the_botascopia_module('card-fiche', [
								'href' => $fiche['href'],
								'image' => $fiche['image'],
								'name' => $fiche['name'],
								'species' => $fiche['species'],
								'icon' => $fiche['icon'],
								'popup' => $fiche['popup'],
								'id' => $fiche['id'],
								'extra_attributes' => $fiche['extra_attributes']
							]);
                        }
					}
					?>
                </div>
                
    
				<!--                Fiches complètes-->
				<div id="mes-fiches-validees">
					<?php
					the_botascopia_module('title', [
						'title' => __('Mes fiches validées', 'botascopia'),
						'level' => 2,
					]);
					?>
				</div>
				
				<div class="display-fiches-cards-items">
					<?php
					$completedFiche = false;
                    if ($role == 'editor'){
						$fichesValidees = getMesFiches('publish', $role, $userId, $userId, $userId);
                    } else {
						$fichesValidees = getMesFiches('publish', $role, $userId, $userId, null);
                    }
					
					foreach ($fichesValidees as $fiche){
						$completedFiche = true;
						the_botascopia_module('card-fiche', [
							'href' => $fiche['href'],
							'image' => $fiche['image'],
							'name' => $fiche['name'],
							'species' => $fiche['species'],
							'icon' => $fiche['icon'],
							'popup' => $fiche['popup'],
							'id' => $fiche['id'],
							'extra_attributes' => $fiche['extra_attributes']
						]);
					}

					if ( !$completedFiche) {
						echo('
                            <div>
                            Vous n\'avez pas encore complétés de fiches, voulez vous en compléter une?
                            </div>
                            ');
						the_botascopia_module('button', [
							'tag' => 'a',
							'href' => home_url() . '/collection',
							'title' => 'Voir les collections',
							'text' => 'Voir les collections',
							'modifiers' => 'green-button',
						]);
					}
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

