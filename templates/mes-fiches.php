<?php
/*
    Template Name: Mes fiches
*/
?>
<?php
get_header();
function createOptions($nb){
	for ($i=1;$i<=$nb;$i++){
		$is_div=false;
		if($i % 5 === 0){
			
			echo "<option>$i</option>";
			$is_div = true;
		}
		if(($i===$nb AND !$is_div) OR $i===1){
			echo "<option selected>$i</option>";
		}
	}
}
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
		$legende = get_post(get_post_thumbnail_id())->post_excerpt;
		$licence = '';
		
		if ($legende){
			$licence = $legende .', licence CC-BY-SA';
		}
		the_botascopia_module('cover', [
			'subtitle' => $role,
			'title' => $displayName,
			'image' => $imageFull,
			'licence' => $licence
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
										'text' => 'Mes collections favorites',
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

					if ($role != 'contributor'):
					echo '<div class="toc-button">';
					the_botascopia_module('button', [
						'tag' => 'a',
						'href' => home_url() . '/profil/mes-collections/creer-une-collection/',
						'title' => 'Créer une collection',
						'text' => 'Créer une collection',
						'modifiers' => 'green-button',
					]);
					echo '</div>';
					endif;
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
										'text' => 'Mes fiches favorites',
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

					echo '</div>';
					?>
				</div>
	
			</div>
		</div>
		<div class="right-div">
			<?php
			the_botascopia_module('breadcrumbs');
			?>
			<?php $fichesFavorites = getMesFiches(['draft', 'pending', 'publish', 'private'], $role, null, $userId, null); 
				$nb_fiches = count($fichesFavorites);
			?>
			<div class="display-collection-cards">
				<!--                Mes fiches favoris-->
				<div id="fiches-favoris">
					<?php
					the_botascopia_module('title', [
						'title' => __("Mes fiches favorites ($nb_fiches)", 'botascopia'),
						'level' => 2,
					]);
					?>
				</div>
				
				
				<div id="favoris" class="display-fiches-cards-items">
					<?php if($nb_fiches>0): ?>
						<div class="selector">Nombre de fiches par page
							<select id="nb_fiches_fav" class="button purple-button">
								<?php 
									createOptions($nb_fiches);
								?>
							</select>
						</div>
					<?php
					endif;
                    $hasFavorite = false;
					
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
                            Vous n\'avez pas encore de fiches favorites.
                            </div>
                            ');
					}
					?>
				</div>
				<input id='fiches_fav' class='hidden' value='<?php echo json_encode($fichesFavorites); ?>' />
				<!--            Compléter une fiche-->
				<div id="fiches-a-completer">
					<?php
					$fichesACompleter = getMesFiches('draft', $role, $userId, $userId, null);
					$nb_fiches_comp = count($fichesACompleter);
					the_botascopia_module('title', [
						'title' => __("Fiches en cours de complétion ($nb_fiches_comp)", 'botascopia'),
						'level' => 2,
					]);
					?>
				</div>
				
				<div id='div_comp' class="display-fiches-cards-items">
					<?php if($nb_fiches_comp>0): ?>
						<div class="selector">Nombre de fiches par page
							<select id="nb_fiches_comp" class="button purple-button">
								<?php 
									createOptions($nb_fiches_comp);
								?>
							</select>
						</div>
					<?php
					endif;
        
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
					<input id="fiches_compl" class='hidden' value='<?php echo json_encode($fichesACompleter); ?>' /> 
				</div>

                <!--            fiches dont je suis le vérificateur (profil vérificateur)  -->
				<?php if ($role == 'editor'): ?>
                    <div id="fiches-en-verification">
						<?php
						$fichesInValidation = getMesFiches('pending', $role, $userId, $userId, $userId);
						if (isset($fichesInValidation)){
							$nb_fiches_inv = count($fichesInValidation);
						}else{
							$nb_fiches_inv = 0;
						}
						
						the_botascopia_module('title', [
							'title' => __("Fiches dont je suis le vérificateur ($nb_fiches_inv)", 'botascopia'),
							'level' => 2,
						]);
						?>
                    </div>

                    <div id="div_fiches_inval" class="display-fiches-cards-items">
						<?php if($nb_fiches_inv>0): ?>
							<div class="selector">Nombre de fiches par page
								<select id="nb_fiches_inval" class="button purple-button">
									<?php 
										createOptions($nb_fiches_comp);
									?>
								</select>
							</div>
						<?php
						endif;
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
					<input id="fiches_inval" class='hidden' value='<?php echo json_encode($fichesInValidation); ?>' />
				<?php endif; ?>
                
                <!--            Fiches à valider-->
                <div id="fiches-a-valider">
					<?php
					$fichesAValider = getMesFiches('pending', $role, $userId, $userId, null);
					
					if (isset($fichesAValider)){
						
						$fichesAval = [];
						foreach ($fichesAValider as $fiche){
							if (!$fiche['editor'] || $fiche['editor'] == 0){
								array_push($fichesAval,$fiche);
							}
						}
						$nb_fiches_val = count($fichesAval);
					}else{
						$nb_fiches_val = 0;
					}
					the_botascopia_module('title', [
						'title' => __("Fiches terminées et en attente de vérification ($nb_fiches_val)", 'botascopia'),
						'level' => 2,
					]);
					?>
                </div>

                <div id="div_fiches_term" class="display-fiches-cards-items">
					<?php 
						if($nb_fiches_val>0): 
					?>
							<div class="selector">Nombre de fiches par page
								<select id="nb_fiches_term" class="button purple-button">
									<?php 
										createOptions($nb_fiches_val);
									?>
								</select>
							</div>
					<?php
						endif;
					foreach ($fichesAval as $fiche){
				
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
				<input id="fiches_term" class='hidden' value='<?php echo json_encode($fichesAval); ?>' />
    
				<!--                Fiches complètes-->
				<div id="mes-fiches-validees">
					<?php
					if ($role == 'editor'){
						$fichesValidees = getMesFiches('publish', $role, $userId, $userId, $userId);
                    } else {
						$fichesValidees = getMesFiches('publish', $role, $userId, $userId, null);
                    }
					if (isset($fichesValidees)){
						$nb_fiches_validees = count($fichesValidees);
					}else{
						$nb_fiches_validees = 0;
					}
				
					the_botascopia_module('title', [
						'title' => __("Mes fiches publiées ($nb_fiches_validees)", 'botascopia'),
						'level' => 2,
					]);
					?>
				</div>
				
				<div id="div_fiches_val" class="display-fiches-cards-items">
					<?php 
						if($nb_fiches_validees>0): 
					?>
							<div class="selector">Nombre de fiches par page
								<select id="nb_fiches_val" class="button purple-button">
									<?php 
										createOptions($nb_fiches_validees);
									?>
								</select>
							</div>
					<?php
						endif;
					$completedFiche = false;
                    
					
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
                            Vous n\'avez pas encore complété de fiches, voulez-vous en compléter une?
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
			<input id="fiches_val" class='hidden' value='<?php echo json_encode($fichesValidees); ?>' />
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
<script src="<?php echo (get_template_directory_uri() . '/assets/scripts/mes-fiches.js'); ?>" ></script>

