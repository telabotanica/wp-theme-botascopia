<?php
/*
    Template Name: Mes fiches
*/
?>
<?php
get_header();
function createOptions($nb){
	for ($i=1;$i<=$nb;$i++){
		
		$multiple=0;
		if ($i===1){
			echo "<option selected>$i</option>";
		}
		if($i % 5 === 0){
			
			echo "<option selected>$i</option>";
			$multiple=$i;
		}
		if($i===$nb AND $i !== $multiple AND $i!==1){
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
			'subtitle' => getRole($role),
			'title' => $displayName,
			'image' => $imageFull,
			'licence' => $licence
		]);
		?>
	<?php
	if (is_user_logged_in()){
		if (get_user_meta($userId, 'favorite_fiche')):
			$existingFavorites = get_user_meta($userId, 'favorite_fiche');
		endif;
	?>
		<div class="collection-main" id="mes-fiches">
           
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
			<?php $fichesFavorites = getMesFiches(['draft', 'pending', 'publish', 'private'], $role, null, $userId, null); 
				$nb_fiches = 0;
				if (isset($fichesFavorites)&&!empty($fichesFavorites)){
					$nb_fiches = count($fichesFavorites);
				}
				
			?>
			
			<div class="display-collection-cards">
				<?php if($nb_fiches>0): ?>
					<!--Mes fiches favoris-->
					<div id="fiches-favoris">
						<?php
						the_botascopia_module('title', [
							'title' => __(Constantes::FICHES_FAV." ($nb_fiches)", 'botascopia'),
							'level' => 2,
						]);
						?>
					</div>
					
					
					<div id="favoris" class="display-fiches-cards-items rubrique1">
						
							<div class="selector">Nombre de fiches par page
								<select id="nb_fiches_fav" class="button purple-button">
									<?php 
										createOptions($nb_fiches);
									?>
								</select>
							</div>
						<?php
						
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
				<?php endif; ?>
				<input id='fiches_fav' class='hidden' value='<?php echo json_encode($fichesFavorites); ?>' />
				<?php
					$fichesACompleter = getMesFiches('draft', $role, $userId, $userId, null);
					$nb_fiches_comp = 0;
					if (isset($fichesACompleter)&&!empty($fichesACompleter)){
						$nb_fiches_comp = count($fichesACompleter);
					}
				?>
				<?php if($nb_fiches_comp>0): ?>
					<!--Compléter une fiche-->
					<div id="fiches-a-completer">
						
						<?php
						the_botascopia_module('title', [
							'title' => __(Constantes::FICHES_TO_COMP." ($nb_fiches_comp)", 'botascopia'),
							'level' => 2,
						]);
						?>
					</div>
					
					<div id='div_comp' class="display-fiches-cards-items rubrique2">
						
							<div class="selector">Nombre de fiches par page
								<select id="nb_fiches_comp" class="button purple-button">
									<?php 
										createOptions($nb_fiches_comp);
									?>
								</select>
							</div>
						<?php
						
			
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
				<input id="fiches_compl" class='hidden' value='<?php echo json_encode($fichesACompleter); ?>' /> 
				
				<?php
					$fichesInValidation = getMesFiches('pending', $role, $userId, $userId, $userId);
					if (isset($fichesInValidation)){
						$nb_fiches_inv = count($fichesInValidation);
					}else{
						$nb_fiches_inv = 0;
					}
				?>
				<?php if($nb_fiches_inv>0): ?>
					<!--fiches dont je suis le vérificateur (profil vérificateur)  -->
					<?php if ($role == 'editor'): ?>
						<div id="fiches-en-verification">
							
							<?php
							the_botascopia_module('title', [
								'title' => __(Constantes::FICHES_TO_CHK." ($nb_fiches_inv)", 'botascopia'),
								'level' => 2,
							]);
							?>
						</div>

						<div id="div_fiches_inval" class="display-fiches-cards-items rubrique3">
							
								<div class="selector">Nombre de fiches par page
									<select id="nb_fiches_inval" class="button purple-button">
										<?php 
											createOptions($nb_fiches_comp);
										?>
									</select>
								</div>
							<?php
							
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
				<?php endif; ?>	
				<input id="fiches_inval" class='hidden' value='<?php echo json_encode($fichesInValidation); ?>' />
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
				?>
				<?php if($nb_fiches_val>0):?>
					<!--Fiches à valider-->
					<div id="fiches-a-valider">
						
						<?php
						the_botascopia_module('title', [
							'title' => __(Constantes::FICHES_TO_VAL." ($nb_fiches_val)", 'botascopia'),
							'level' => 2,
						]);
						?>
					</div>

					<div id="div_fiches_term" class="display-fiches-cards-items rubrique4">
						
								<div class="selector">Nombre de fiches par page
									<select id="nb_fiches_term" class="button purple-button">
										<?php 
											createOptions($nb_fiches_val);
										?>
									</select>
								</div>
						<?php
							
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
				<?php endif; ?>	
				<input id="fiches_term" class='hidden' value='<?php echo json_encode($fichesAval); ?>' />
    
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
				?>
				<?php if($nb_fiches_validees>0):?>
					<!--Fiches complètes-->
					<div id="mes-fiches-validees">
						
						<?php
						the_botascopia_module('title', [
							'title' => __(Constantes::FICHES_VAL." ($nb_fiches_validees)", 'botascopia'),
							'level' => 2,
						]);
						?>
					</div>
					
					<div id="div_fiches_val" class="display-fiches-cards-items rubrique5">
						
								<div class="selector">Nombre de fiches par page
									<select id="nb_fiches_val" class="button purple-button">
										<?php 
											createOptions($nb_fiches_validees);
										?>
									</select>
								</div>
						<?php
							
						
						
						
						foreach ($fichesValidees as $fiche){
							$completedFiche = true;
							the_botascopia_module('card-fiche', [
								'href' => $fiche['href'],
								'image' => $fiche['image'],
								'name' => getFilteredTitle($fiche['name']),
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
				<?php if ( $nb_fiches_validees===0) {
							echo('
								<div>
								Vous n\'avez pas encore complété de fiches, voulez-vous en compléter une?
								</div>
								');
							the_botascopia_module('button', [
								'tag' => 'a',
								'href' => home_url() . '/collection',
								'title' => Constantes::FICHES_TO_SEE,
								'text' => Constantes::FICHES_TO_SEE,
								'modifiers' => 'green-button',
							]);
						}?>	
			</div>
			<input id="fiches_val" class='hidden' value='<?php echo json_encode($fichesValidees); ?>' />
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
<script src="<?php echo (get_template_directory_uri() . '/assets/scripts/mes-fiches.js'); ?>" ></script>

