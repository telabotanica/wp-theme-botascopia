<?php
/* Template Name: Fiche Single */
/* Template Post Type: post */
?>
<?php
get_header();
?>
<div id="primary" class="content-area">
	<div class="bg-fill">
	
	</div>
	<main id="main" class="site-main " role="main">
		
		<?php
		$post = get_queried_object();
		
		if (is_user_logged_in()) :
			$current_user = wp_get_current_user();
			$current_user_id = $current_user->ID;
			$current_user_role = $current_user->roles[0];
		else:
			$current_user_id = '';
		endif;
		
		if (is_user_logged_in() && get_user_meta($current_user_id, 'favorite_fiche')):
			$ficheFavorites = get_user_meta(wp_get_current_user()->ID, 'favorite_fiche');
		endif;
		
		$post_id = $post->ID;
		$post_author = get_the_author_meta('display_name', $post->post_author);
		$verificateur = '';
		$verificateur_id = get_post_meta(get_the_ID(), 'Editor', true);
		$verificateur_data = get_userdata($verificateur_id);
		if ($verificateur_data){
			$verificateur = $verificateur_data->display_name;
		}
		
		$date = $post->post_date;
		setlocale(LC_TIME, 'fr_FR.utf8');
		$post_date = strftime('%e %B %Y', strtotime($date));

        if (!empty(get_field("field_643027826f24d"))) {
            $image = ["url" => wp_get_attachment_image_src(get_field("field_643027826f24d")["photo_de_la_plante_entiere"], 'image-tige' )[0]];
        } else {
            $image = getPostImage($post_id);
        }
		
		$index_photos = 0;
		$fruit_photo=null;
		$refs_photo = array();
		
		switch ($post->post_status){
			case 'draft':
				$status = 'À compléter';
				break;
			case 'pending':
				$status = 'En cours de validation';
				break;
			case 'publish':
				$status = 'Validée';
				break;
			default:
				$status = '';
		}

		the_botascopia_module('cover', [
			'subtitle' => get_post_meta($post_id, 'nom_vernaculaire', true).' - '.get_post_meta($post_id, 'famille',
																								true),
			'title' => get_post_meta($post_id, 'nom_scientifique', true),
			'image' => ['url' => get_template_directory_uri() .'/images/recto-haut.svg'],
			'modifiers' =>['class' => 'fiche-cover']
		]);
		echo ('
			<img src= '.$image["url"] .' class="fiche-image">
		');
		?>
		<div class="collection-main">
			<div class="left-div">
				
				<div class="single-collection-buttons" id="fiche-<?php echo $post_id ?>"
					 data-user-id="<?php echo $current_user_id?>"
					 data-fiche-id="<?php echo $post_id ?>">
					
					<?php the_botascopia_module('button', [
						'tag' => 'a',
						'href' => '#',
						'title' => 'Téléchargez',
						'text' => 'Téléchargez',
						'modifiers' => 'green-button',
					]); ?>
					
					<?php if (is_user_logged_in() && isset($ficheFavorites) &&get_user_meta($current_user_id, 'favorite_collection')
						&& ($key
							= array_search($post_id, $ficheFavorites[0])) !== false) :
						//changer le bouton favoris si collection dans favoris ou pas
						$icone = ['icon' => 'star', 'color' => 'blanc'];
						$modifiers = 'green-button';
					else:
						$icone = ['icon' => 'star-outline', 'color' => 'vert-clair'];
						$modifiers = 'green-button outline';
					endif;
					
					the_botascopia_module('button', [
						'tag' => 'a',
						'href' => '#',
						'title' => 'Favoris',
						'text' => 'Favoris',
						'modifiers' => $modifiers,
						'icon_after' => $icone,
						'extra_attributes' => ['id' => 'fav-'.$post_id]
					]);
					
					?>
				</div>
				<div class="single-collection-export-format">
					Formats : PDF (60Mo)
				</div>
				
				<a class="return-button" href="#">
					<?php the_botascopia_module('icon', [
						'icon' => 'arrow-left'
					]); ?>
					<span>RETOUR</span>
				</a>
				
				<div class="first-toc">
					<?php
					the_botascopia_module('toc', [
						'title' => '',
						'items' => [
							[
								'items' => [
									[
										'text' => 'Taxonomie',
										'href' => '#taxonomie',
										'active' => true,
									],
									[
										'text' => 'Description morphologique',
										'href' => '#description-morphologique',
										'active' => false,
									],
									[
										'text' => 'Description vulgarisée',
										'href' => '#description-vulgarisee',
										'active' => false,
									],
									[
										'text' => 'Tige',
										'href' => '#tige',
										'active' => false,
									],
									[
										'text' => 'Feuilles',
										'href' => '#feuilles',
										'active' => false,
									],
									[
										'text' => 'Inflorescence',
										'href' => '#inflorescence',
										'active' => false,
									],
									[
										'text' => 'Fruits',
										'href' => '#fruits',
										'active' => false,
									],
									[
										'text' => 'Fleur mâle',
										'href' => '#fleur-male',
										'active' => false,
									],
									[
										'text' => 'Fleur femelle',
										'href' => '#fleur-femelle',
										'active' => false,
									],
									[
										'text' => 'Fleur bisexuée',
										'href' => '#fleur-bisexuee',
										'active' => false,
									],
									[
										'text' => 'Le saviez vous?',
										'href' => '#le-saviez-vous',
										'active' => false,
									],
									[
										'text' => 'Periode de floraison et de fructification',
										'href' => '#periode-floraison',
										'active' => false,
									],
									[
										'text' => 'Écologie',
										'href' => '#ecologie',
										'active' => false,
									],
									[
										'text' => 'Propriétés',
										'href' => '#proprietes',
										'active' => false,
									],
									[
										'text' => 'Aire de répartition',
										'href' => '#aire-repartition',
										'active' => false,
									],
									[
										'text' => 'Ne pas confondre',
										'href' => '#ne-pas-confondre',
										'active' => false,
									],
									[
										'text' => 'Complément d\'anecdote',
										'href' => '#complement-anecdote',
										'active' => false,
									],
									[
										'text' => 'Références',
										'href' => '#references',
										'active' => false,
									],
								]
							],
						]
					]);
					?>
				</div>
			
			</div>
			<div id="fiche" class="right-div">
				
				<?php
				the_botascopia_module('breadcrumbs');
				?>
				
				<div id="fiche-infos">
					<?php
					the_botascopia_module('title', [
						'title' => __('Infos', 'botascopia'),
						'level' => 4,
						'modifiers' => ['class' => 'fiche-title-infos']
					]);
					?>
					
					<div class="fiche-infos">
						<div class="single-fiche-details">
							<div class="single-fiche-detail">Statut: <?php echo $status ?></div>
							<div class="single-fiche-detail">Publié le <?php echo $post_date ?></div>
							<div class="single-fiche-detail">Par <?php echo $post_author ?></div>
							<div class="single-fiche-detail">Vérifié par <?php echo $verificateur ?></div>
						</div>
						<div id="fiche-infos-right">
							<?php
							the_botascopia_module('title', [
								'title' => __('Apparait dans les collections suivantes :', 'botascopia'),
								'level' => 4,
//								'modifiers' => ['class' => 'fiche-title-infos']
							]);
							?>
							<ul>
								<?php
								$connected_collections = get_posts(
									array(
										'connected_type' => 'collection_to_post',
										'connected_items' => get_queried_object(),
										'nopaging' => true,
										'post_type' => 'collection'
									));
								
								foreach ($connected_collections as $collection){
									echo '<li>' . $collection->post_title . '</li>';
								}
								?>
							</ul>
						</div>
					</div>
				</div>
				
				<div id="taxonomie">
					<?php
					the_botascopia_module('title', [
						'title' => __('Taxonomie', 'botascopia'),
						'level' => 2,
					]);
					?>
					
					<p>Nom scientifique: <?php the_field('nom_scientifique'); ?></p>
					<p>Nom vernaculaire: <?php the_field('nom_vernaculaire'); ?></p>
					<p>Famille: <?php the_field('famille'); ?></p>
					<p>Numéro nomenclatural: <?php the_field('num_nom'); ?></p>
					<p>Référentiel: <?php the_field('referentiel'); ?></p>
					<?php if (!empty(get_field('lien_eflore'))): ?>
					<p>Lien vers e-flore : <a href="<?php the_field('lien_eflore'); ?>" title="Lien vers eflore"
											  target="_blank"><?php the_field('lien_eflore'); ?></a></p>
					<?php endif; ?>
				</div>
				
				<div id="description-morphologique">
					
					<div class="fiche-title-icon">
						<img src=" <?php echo get_template_directory_uri() ?>/images/description.svg" />
						<?php
						the_botascopia_module('title', [
							'title' => __('Description morphologique', 'botascopia'),
							'level' => 2,
						]);
						?>
					</div>

                    <p><?php if (!empty(get_field('port_de_la_plante'))) { echo ucfirst(get_field('port_de_la_plante')).", ";
                            if (!empty(get_field('systeme_sexuel')) && get_field('systeme_sexuel') !== "hermaphrodite" ) { echo get_field('systeme_sexuel').", " ;}
                            if ((get_field('port_de_la_plante') == "herbacée" || get_field('port_de_la_plante') == "liane") && !empty(get_field('mode_de_vie')) && get_field('mode_de_vie') !== array("terrestre") ) { echo implode(', ', get_field('mode_de_vie')).", " ; }
                            if (get_field('port_de_la_plante') == "herbacée" && !empty(get_field('type_de_developpement'))) { echo implode(', ', get_field('type_de_developpement')).", " ;}
                            if ((get_field('port_de_la_plante') == "herbacée" || get_field('port_de_la_plante') == "liane") && !empty(get_field('forme_biologique'))) { echo implode(', ', get_field('forme_biologique')).", " ;} ?>
                            qui peut atteindre jusqu'à <?php the_field('hauteur_maximale'); ?> de haut.
                            <?php if (!empty(get_field(' pilosite_de_la_plante_entiere'))) { echo "Cette plante est ".get_field(' pilosite_de_la_plante_entiere').".";} ?>
                        <?php } ?>
                    </p>
				</div>
				
				<?php $description_vulgarisee = get_field('description_vulgarisee')?: null; ?>
				<?php if ($description_vulgarisee): ?>
				<div id="description-vulgarisee">
					<?php
					the_botascopia_module('title', [
						'title' => __('Description vulgarisée', 'botascopia'),
						'level' => 2,
					]);
					?>
					<p><?php the_field('description_vulgarisee'); ?>.</p>
				</div>
				<?php endif; ?>
				
				<div id="tige">
					<div class="fiche-title-icon">
						<img src=" <?php echo get_template_directory_uri() ?>/images/tige.svg" />
						<?php
						the_botascopia_module('title', [
							'title' => __('Tige', 'botascopia'),
							'level' => 2,
						]);
						?>
					</div>
					<?php if (!empty(get_field('tige'))):?>
					<p class="tige-description">
						<?php
						$tige = get_field('tige');
						if (!empty($tige)) {
							$type_tige = implode(', ', $tige['type_de_tige']);
							$section_tige = implode('-', $tige['section_de_la_tige']);
							$surface_tige = implode(', ', $tige['surface_de_la_tige_jeune']);
							$port_de_la_plante = get_field('port_de_la_plante');
							if (!empty($port_de_la_plante)) {
								if (($port_de_la_plante === 'arbrisseau') || ($port_de_la_plante === 'arbre')) {
									if (!empty($tige['surface_de_lecorce'])) {
										$surface_ecorce = implode(', ', $tige['surface_de_lecorce']);
									}
								}
							}
						}
						?>
						La tige aérienne est <?php echo $tige['tige_aerienne'];?>
						<?php if ($tige['tige_aerienne'] != 'non visible'):;?>, <?php echo $type_tige;?>, <?php echo $tige['ramification'];?>, à section <?php echo $section_tige;?>.
							<br>Sa surface est <?php echo $surface_tige;?> au moins quand elle est jeune.
							<?php if ((($port_de_la_plante === 'arbrisseau') || ($port_de_la_plante === 'arbre')) && (!empty($surface_ecorce))): ?>
								<br>L'écorce est <?php echo $surface_ecorce;?><?php if (!empty($tige['couleur_du_tronc'])) {?> et <?php echo $tige['couleur_du_tronc'];} ?>.
							<?php endif; ?>
						<?php endif; ?>
					</p>
					
					<?php
					// Si une image est enregistrée on l'affiche
                        if (isset($tige["illustration_de_la_tige"]['photo_tige'])){
						affichageImageFiche($tige["illustration_de_la_tige"]['photo_tige']);
					}
					?>
				</div>
				<?php endif; ?>
				
				<div id="feuilles">
					<div class="fiche-title-icon">
						<img src=" <?php echo get_template_directory_uri() ?>/images/feuilles.svg" />
						<?php
						the_botascopia_module('title', [
							'title' => __('Feuilles', 'botascopia'),
							'level' => 2,
						]);
						?>
					</div>
					
					<?php  if (!empty(get_field('feuille'))) { ?>
						<?php $presence_feuilles = get_field('feuille')['presence_de_feuilles']; ?>
						<?php if ('jamais visibles' === $presence_feuilles): ?>
							<p><?php echo $presence_feuilles; ?></p>
						<?php else : ?>
							
							<?php $heteromorphisme_foliaire = get_field('heteromorphisme_foliaire'); ?>
							<?php if (('feuilles toutes semblables' === $heteromorphisme_foliaire) || ('gradient de forme entre la base et le haut de la tige' === $heteromorphisme_foliaire)): ?>
								
								<?php $feuilles_aeriennes = get_field('feuilles_aeriennes'); ?>
								<p>
									Les feuilles sont disposées de façon <?php echo implode(' et ', $feuilles_aeriennes['phyllotaxie']);?> et elles sont <?php echo implode(' et ', $feuilles_aeriennes['type_de_feuille']);?>.<br>
									<?php
									$type_feuille_multiple = 1 < count($feuilles_aeriennes['type_de_feuille']);
									$limbe = 'Le limbe %s est %s';
									$type_limbe = [];
									
									foreach ($feuilles_aeriennes['type_de_feuille'] as $type_feuille) {
										if ('simples' === $type_feuille) {
											$type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des feuilles simples' : ''), implode('-', $feuilles_aeriennes['limbe_des_feuilles_simples']));
										} else {
											$type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des folioles' : ''), implode('-', $feuilles_aeriennes['limbe_des_folioles']));
										}
									}
									?>
									
									<?php echo implode(', ', $type_limbe);?>, à marge foliaire <?php echo implode(' et ', $feuilles_aeriennes['marge_foliaire']);?> et à nervation <?php echo implode(' et ', $feuilles_aeriennes['nervation']);?>.<br>
									
									<?php
									$presence_petiole = $feuilles_aeriennes['petiole'];
									$petiole = 'présent' === $presence_petiole ? $feuilles_aeriennes['longueur_du_petiole'] . ('engainant' === $feuilles_aeriennes['engainant'] ? ', ' . $feuilles_aeriennes['engainant'] :'') : $presence_petiole;
									?>
									
									Le pétiole est <?php echo $petiole; ?>.<br>
									
									<?php echo  'présents' === $feuilles_aeriennes['stipules'] ? $feuilles_aeriennes['forme_et_couleur_des_stipules'] : '';?>
									
									<?php $port_de_la_plante = get_field('port_de_la_plante'); ?>
									<?php if (!empty($port_de_la_plante)): ?>
										<?php if (($port_de_la_plante === 'arbrisseau') || ($port_de_la_plante === 'arbre')): ?>
											<?php echo  $feuilles_aeriennes['feuillage'] ? 'Le feuillage est ' . $feuilles_aeriennes['feuillage'].'.' : '';?>
										<?php endif; ?>
									<?php endif; ?>
								</p>
							
							<?php elseif ('deux formes distinctes de feuilles'=== $heteromorphisme_foliaire): ?>
								
								<?php $deux_formes_distinctes = get_field('deux_formes_distinctes'); ?>
								<?php if ($deux_formes_distinctes === 'plante à feuilles immergées et aériennes'): ?>
									
									<?php $feuilles_aeriennes = get_field('feuilles_aeriennes'); ?>
									<?php if(!empty($feuilles_aeriennes)): ?>
                                        <h4 class="icon-title">
                                            <div class="feuilles-icon icon" style="background-size: cover"></div>Feuilles aériennes
                                        </h4>
										<p>
											Les feuilles sont disposées de façon <?php echo implode(' et ', $feuilles_aeriennes['phyllotaxie']);?> et elles sont <?php echo implode(' et ', $feuilles_aeriennes['type_de_feuille']);?>.<br>
											<?php
											$type_feuille_multiple = 1 < count($feuilles_aeriennes['type_de_feuille']);
											$limbe = 'Le limbe %s est %s';
											$type_limbe = [];
											
											foreach ($feuilles_aeriennes['type_de_feuille'] as $type_feuille) {
												if ('simples' === $type_feuille) {
													$type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des feuilles simples' : ''), implode('-', $feuilles_aeriennes['limbe_des_feuilles_simples']));
												} else {
													$type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des folioles' : ''), implode('-', $feuilles_aeriennes['limbe_des_folioles']));
												}
											}
											?>
											
											<?php echo implode(', ', $type_limbe);?>, à marge foliaire <?php echo implode(' et ', $feuilles_aeriennes['marge_foliaire']);?> et à nervation <?php echo implode(' et ', $feuilles_aeriennes['nervation']);?>.<br>
											
											<?php
											$presence_petiole = $feuilles_aeriennes['petiole'];
											$petiole = 'présent' === $presence_petiole ? $feuilles_aeriennes['longueur_du_petiole'] . ('engainant' === $feuilles_aeriennes['engainant'] ? ', ' . $feuilles_aeriennes['engainant'] :'') : $presence_petiole;
											?>
											
											Le pétiole est <?php echo $petiole; ?>.<br>
											
											<?php echo  'présents' === $feuilles_aeriennes['stipules'] ? $feuilles_aeriennes['forme_et_couleur_des_stipules'] : '';?>
											
											<?php $port_de_la_plante = get_field('port_de_la_plante'); ?>
											<?php if (!empty($port_de_la_plante)): ?>
												<?php if (($port_de_la_plante === 'arbrisseau') || ($port_de_la_plante === 'arbre')): ?>
													<?php echo  $feuilles_aeriennes['feuillage'] ? 'Le feuillage est ' . $feuilles_aeriennes['feuillage'].'.' : '';?>
												<?php endif; ?>
											<?php endif; ?>
										</p>
									<?php endif; ?>

                                    <?php $feuilles_immergees = get_field('feuilles_immergees'); ?>
                                    <?php if(!empty($feuilles_immergees)): ?>
                                        <h4 class="icon-title">
                                            <div class="feuilles-icon icon" style="background-size: cover"></div>Feuilles immergées
                                        </h4>
                                        <p>
                                            Les feuilles sont disposées de façon <?php echo implode(' et ', $feuilles_immergees['phyllotaxie']);?> et elles sont <?php echo implode(' et ', $feuilles_immergees['type_de_feuille']);?>.<br>
                                            <?php
                                            $type_feuille_multiple = 1 < count($feuilles_immergees['type_de_feuille']);
                                            $limbe = 'Le limbe %s est %s';
                                            $type_limbe = [];

                                            foreach ($feuilles_immergees['type_de_feuille'] as $type_feuille) {
                                                if ('simples' === $type_feuille) {
                                                    $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des feuilles simples' : ''), implode('-', $feuilles_immergees['limbe_des_feuilles_simples']));
                                                } else {
                                                    $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des folioles' : ''), implode('-', $feuilles_immergees['limbe_des_folioles']));
                                                }
                                            }
                                            ?>

                                            <?php echo implode(', ', $type_limbe);?>, à marge foliaire <?php echo implode(' et ', $feuilles_immergees['marge_foliaire']);?> et à nervation <?php echo implode(' et ', $feuilles_immergees['nervation']);?>.<br>

                                            <?php
                                            $presence_petiole = $feuilles_immergees['petiole'];
                                            $petiole = 'présent' === $presence_petiole ? $feuilles_immergees['longueur_du_petiole'] . ('engainant' === $feuilles_immergees['engainant'] ? ', ' . $feuilles_immergees['engainant'] :'') : $presence_petiole;
                                            ?>

                                            Le pétiole est <?php echo $petiole; ?>.<br>

                                            <?php echo  'présents' === $feuilles_immergees['stipules'] ? $feuilles_immergees['forme_et_couleur_des_stipules'] : '';?>

                                            <?php $port_de_la_plante = get_field('port_de_la_plante'); ?>
                                            <?php if (!empty($port_de_la_plante)): ?>
                                                <?php if (($port_de_la_plante === 'arbrisseau') || ($port_de_la_plante === 'arbre')): ?>
                                                    <?php echo  $feuilles_immergees['feuillage'] ? 'Le feuillage est ' . $feuilles_immergees['feuillage'].'.' : '';?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </p>
                                    <?php endif; ?>
								
								<?php elseif ($deux_formes_distinctes === 'plante à rameaux stériles et à rameaux fleuris distincts'): ?>
									
									<?php $feuilles_des_rameaux_steriles = get_field('feuilles_des_rameaux_steriles'); ?>
									<?php if(!empty($feuilles_des_rameaux_steriles)): ?>
										<h4 class="icon-title">
											<div class="feuilles-icon icon" style="background-size: cover"></div>Feuilles des rameaux stériles
										</h4>
										<p>
											Les feuilles sont disposées de façon <?php echo implode(' et ', $feuilles_des_rameaux_steriles['phyllotaxie']);?> et elles sont <?php echo implode(' et ', $feuilles_des_rameaux_steriles['type_de_feuille']);?>.<br>
											<?php
											$type_feuille_multiple = 1 < count($feuilles_des_rameaux_steriles['type_de_feuille']);
											$limbe = 'Le limbe %s est %s';
											$type_limbe = [];
											
											foreach ($feuilles_des_rameaux_steriles['type_de_feuille'] as $type_feuille) {
												if ('simples' === $type_feuille) {
													$type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des feuilles simples' : ''), implode('-', $feuilles_des_rameaux_steriles['limbe_des_feuilles_simples']));
												} else {
													$type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des folioles' : ''), implode('-', $feuilles_des_rameaux_steriles['limbe_des_folioles']));
												}
											}
											?>
											
											<?php echo implode(', ', $type_limbe);?>, à marge foliaire <?php echo implode(' et ', $feuilles_des_rameaux_steriles['marge_foliaire']);?> et à nervation <?php echo implode(' et ', $feuilles_des_rameaux_steriles['nervation']);?>.<br>
											
											<?php
											$presence_petiole = $feuilles_des_rameaux_steriles['petiole'];
											$petiole = 'présent' === $presence_petiole ? $feuilles_des_rameaux_steriles['longueur_du_petiole'] . ('engainant' === $feuilles_des_rameaux_steriles['engainant'] ? ', ' . $feuilles_des_rameaux_steriles['engainant'] :'') : $presence_petiole;
											?>
											
											Le pétiole est <?php echo $petiole; ?>.<br>
											
											<?php echo  'présents' === $feuilles_des_rameaux_steriles['stipules'] ? $feuilles_des_rameaux_steriles['forme_et_couleur_des_stipules'] : '';?>
											
											<?php $port_de_la_plante = get_field('port_de_la_plante'); ?>
											<?php if (!empty($port_de_la_plante)): ?>
												<?php if (($port_de_la_plante === 'arbrisseau') || ($port_de_la_plante === 'arbre')): ?>
													<?php echo  $feuilles_des_rameaux_steriles['feuillage'] ? 'Le feuillage est ' . $feuilles_des_rameaux_steriles['feuillage'].'.' : '';?>
												<?php endif; ?>
											<?php endif; ?>
										</p>
									<?php endif; ?>

                                    <?php $feuilles_des_rameaux_fleuris = get_field('feuilles_des_rameaux_fleuris'); ?>
                                    <?php if(!empty($feuilles_des_rameaux_fleuris)): ?>
                                        <h4 class="icon-title">
                                            <div class="feuilles-icon icon" style="background-size: cover"></div>Feuilles des rameaux fleuris
                                        </h4>
                                        <p>
                                            Les feuilles sont disposées de façon <?php echo implode(' et ', $feuilles_des_rameaux_fleuris['phyllotaxie']);?> et elles sont <?php echo implode(' et ', $feuilles_des_rameaux_fleuris['type_de_feuille']);?>.<br>
                                            <?php
                                            $type_feuille_multiple = 1 < count($feuilles_des_rameaux_fleuris['type_de_feuille']);
                                            $limbe = 'Le limbe %s est %s';
                                            $type_limbe = [];

                                            foreach ($feuilles_des_rameaux_fleuris['type_de_feuille'] as $type_feuille) {
                                                if ('simples' === $type_feuille) {
                                                    $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des feuilles simples' : ''), implode('-', $feuilles_des_rameaux_fleuris['limbe_des_feuilles_simples']));
                                                } else {
                                                    $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des folioles' : ''), implode('-', $feuilles_des_rameaux_fleuris['limbe_des_folioles']));
                                                }
                                            }
                                            ?>

                                            <?php echo implode(', ', $type_limbe);?>, à marge foliaire <?php echo implode(' et ', $feuilles_des_rameaux_fleuris['marge_foliaire']);?> et à nervation <?php echo implode(' et ', $feuilles_des_rameaux_fleuris['nervation']);?>.<br>

                                            <?php
                                            $presence_petiole = $feuilles_des_rameaux_fleuris['petiole'];
                                            $petiole = 'présent' === $presence_petiole ? $feuilles_des_rameaux_fleuris['longueur_du_petiole'] . ('engainant' === $feuilles_des_rameaux_fleuris['engainant'] ? ', ' . $feuilles_des_rameaux_fleuris['engainant'] :'') : $presence_petiole;
                                            ?>

                                            Le pétiole est <?php echo $petiole; ?>.<br>

                                            <?php echo  'présents' === $feuilles_des_rameaux_fleuris['stipules'] ? $feuilles_des_rameaux_fleuris['forme_et_couleur_des_stipules'] : '';?>

                                            <?php $port_de_la_plante = get_field('port_de_la_plante'); ?>
                                            <?php if (!empty($port_de_la_plante)): ?>
                                                <?php if (($port_de_la_plante === 'arbrisseau') || ($port_de_la_plante === 'arbre')): ?>
                                                    <?php echo  $feuilles_des_rameaux_fleuris['feuillage'] ? 'Le feuillage est ' . $feuilles_des_rameaux_fleuris['feuillage'].'.' : '';?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </p>
                                    <?php endif; ?>

								<?php endif; ?>
							
							<?php endif; ?>
						
						
						<?php endif; ?>
					<?php } ?>
					
					
					<?php
					// Si une image est enregistrée on l'affiche
					if (isset($feuilles_aeriennes["illustration_de_la_feuille_aerienne"]['photo_de_feuilles_aeriennes'])){
						affichageImageFiche($feuilles_aeriennes["illustration_de_la_feuille_aerienne"]['photo_de_feuilles_aeriennes']);
					}
                    if (isset($feuilles_immergees["illustration_de_la_feuille_immergee"]['photo_de_feuilles_immergees'])){
                        affichageImageFiche($feuilles_immergees["illustration_de_la_feuille_immergee"]['photo_de_feuilles_immergees']);
                    }
                    if (isset($feuilles_des_rameaux_steriles["illustration_de_la_feuille_des_rameaux_steriles"]['photo_de_feuilles_des_rameaux_steriles'])){
                        affichageImageFiche($feuilles_des_rameaux_steriles["illustration_de_la_feuille_des_rameaux_steriles"]['photo_de_feuilles_des_rameaux_steriles']);
                    }
                    if (isset($feuilles_des_rameaux_fleuris["illustration_de_la_feuille_des_rameaux_fleuris"]['photo_de_feuilles_des_rameaux_fleuris'])){
                        affichageImageFiche($feuilles_des_rameaux_fleuris["illustration_de_la_feuille_des_rameaux_fleuris"]['photo_de_feuilles_des_rameaux_fleuris']);
                    }
					?>
				</div>
				
				<div id="inflorescence">
					<div class="fiche-title-icon">
						<img src=" <?php echo get_template_directory_uri() ?>/images/inflorescence.svg" />
						<?php
						the_botascopia_module('title', [
							'title' => __('Inflorescence', 'botascopia'),
							'level' => 2,
						]);
						?>
					</div>
					
					<?php  if (!empty(get_field('inflorescence'))) { ?>
						<?php $inflorescence = get_field('inflorescence');?>
						<p>Les fleurs sont <?php echo $inflorescence['organisation_des_fleurs'];
						if($inflorescence['categorie'] != 'autre') {
							?>. L’inflorescence est <?php echo $inflorescence['categorie']; ?>.</p>
						<?php } else {
							?>. L’inflorescence est <?php echo $inflorescence['description']; ?>.</p>
						<?php } ?>
					<?php } ?>
					
				</div>
				
				<div id="fruits">
					<div class="fiche-title-icon">
						<img src=" <?php echo get_template_directory_uri() ?>/images/fruits.svg" />
						<?php
						the_botascopia_module('title', [
							'title' => __('Fruits', 'botascopia'),
							'level' => 2,
						]);
						?>
					</div>
					
					<?php  if (!empty(get_field('fruit'))) { ?>
						<?php $fruit = get_field('fruit');?>
						<?php if(!empty($fruit['photo'])): ?>
							<?php
							if (!empty(get_field('fleur_male')) && (!empty(get_field('fleur_male')['photo_de_fleur_male']))) {
								if (!empty(get_field('systeme_sexuel')) && (get_field('systeme_sexuel') == "monoïque") || (get_field('systeme_sexuel') == "dioïque") || (get_field('systeme_sexuel') == "andromonoïque") || (get_field('systeme_sexuel') == "androdioïque") || (get_field('systeme_sexuel') == "androgynomonoïque") || (get_field('systeme_sexuel') == "androgynodioïque")) {
								}
							}
							if (!empty(get_field('fleur_femelle')) && (!empty(get_field('fleur_femelle')['photo_de_fleur_femelle']))) {
								if (!empty(get_field('systeme_sexuel')) && (get_field('systeme_sexuel') == "monoïque") || (get_field('systeme_sexuel') == "dioïque") || (get_field('systeme_sexuel') == "gynomonoïque") || (get_field('systeme_sexuel') == "gynodioïque") || (get_field('systeme_sexuel') == "androgynomonoïque") || (get_field('systeme_sexuel') == "androgynodioïque")) {
								}
							}
							if (!empty(get_field('fleur_bisexuee')) && (!empty(get_field('fleur_bisexuee')['photo_de_fleur_bisexuee']))) {
								if (!empty(get_field('systeme_sexuel')) && (get_field('systeme_sexuel') == "hermaphrodite") || (get_field('systeme_sexuel') == "andromonoïque") || (get_field('systeme_sexuel') == "gynomonoïque") || (get_field('systeme_sexuel') == "androdioïque") || (get_field('systeme_sexuel') == "gynodioïque") || (get_field('systeme_sexuel') == "androgynomonoïque") || (get_field('systeme_sexuel') == "androgynodioïque")) {
								}
							}
							?>
						<?php endif; ?>
						<p>Le fruit est <?php echo $fruit['type'];?>.</p>
					<?php } ?>
					
					<?php
					// Si une image est enregistrée on l'affiche
					if (isset($fruit["illustration_du_fruit"]['photo'])){
						affichageImageFiche($fruit["illustration_du_fruit"]['photo']);
					}
					?>
				</div>
				
				<?php $fleur_male =  get_field('fleur_male') ?: null;?>
				<?php if ($fleur_male && !empty(get_field('systeme_sexuel')) && (get_field('systeme_sexuel') == "monoïque" ) || (get_field('systeme_sexuel') == "dioïque" ) || (get_field('systeme_sexuel') == "andromonoïque" ) || (get_field('systeme_sexuel') == "androdioïque" ) || (get_field('systeme_sexuel') == "androgynomonoïque" ) || (get_field('systeme_sexuel') == "androgynodioïque" )) { ?>
				<div id="fleur-male">
					<div class="fiche-title-icon">
						<img src=" <?php echo get_template_directory_uri() ?>/images/fleur-male.svg" />
						<?php
						the_botascopia_module('title', [
							'title' => __('Fleur mâle', 'botascopia'),
							'level' => 2,
						]);
						?>
					</div>
					
					<?php $fleur_male =  get_field('fleur_male') ?: null;?>
					<?php if (!empty(get_field('systeme_sexuel')) && (get_field('systeme_sexuel') == "monoïque" ) || (get_field('systeme_sexuel') == "dioïque" ) || (get_field('systeme_sexuel') == "andromonoïque" ) || (get_field('systeme_sexuel') == "androdioïque" ) || (get_field('systeme_sexuel') == "androgynomonoïque" ) || (get_field('systeme_sexuel') == "androgynodioïque" )): ?>
							<p>
								Fleur <?php echo implode(' et ', $fleur_male['symetrie']); ?>.
								<?php if('présent' !== $fleur_male['perianthe']): { ?>
									Le périanthe est absent.
								<?php } else: { ?>
									<?php
									if ('tépales' === $fleur_male['differenciation_du_perianthe']) {
										$perianthe = implode(' ou ', $fleur_male['perigone']) . ' tépales ' . $fleur_male['soudure_du_perigone'] . ' ; ';
									} else {
                                        if (getType($fleur_male['soudure_de_la_corolle']) == 'string'){
                                            $soudure_corolle = $fleur_male['soudure_de_la_corolle'];
                                        } else {
                                            $soudure_corolle = implode(' ou ', $fleur_male['soudure_de_la_corolle']);
                                        }

                                        if (getType($fleur_male['corolle']) == 'string'){
                                            $corolle = $fleur_male['corolle'];
                                        } else {
                                            $corolle = implode(' ou ', $fleur_male['corolle']);
                                        }
										$perianthe = implode(' ou ', $fleur_male['calice']) . ' sépale(s) ' . $fleur_male['soudure_du_calice'] . ' et ' . $corolle . ' pétale(s) ' . $soudure_corolle . ' ; ' .
											('corolle soudée au calice' === $fleur_male['soudure_du_calice_et_de_la_corolle'] ? $fleur_male['soudure_du_calice_et_de_la_corolle'] . ' ; ' : '');
									}
									?>
									Le périanthe est composé de <?php echo $perianthe;
								} ?>
								<?php endif; ?>
								<?php if(!empty($fleur_male['androcee'])): { ?>
									androcée composé de <?php echo implode(' ou ' , $fleur_male['androcee']); ?> étamine(s) <?php echo $fleur_male['soudure_de_landrocee']; ?> ;
									<?php echo ('androcée soudé à la corolle' === $fleur_male['soudure_androcee-corolle'] ? $fleur_male['soudure_androcee-corolle'] . ', ' : '').
										('soudées au perigone' === $fleur_male['soudure_androcee-perigone'] ? $fleur_male['soudure_androcee-perigone'] . ', ' : ''); ?>
									<?php echo ('présents' === $fleur_male['staminodes'] ? $fleur_male['nombre_de_staminodes'] . ' staminodes ; ' : ''); ?>
									La couleur principale de la fleur est <?php echo $fleur_male['couleur_principale']; ?>.
									<?php if ('pubescente' === $fleur_male['pubescence']) {
										echo "La fleur est ".$fleur_male['pubescence'];?>
										<?php if (!empty($fleur_male['localisation_des_poils'])) {
											echo ' sur '.implode(', ' , $fleur_male['localisation_des_poils']).'.'; }
										else { echo '.'; }}?>
									<?php echo $fleur_male['autre_caractere'];
								} ?>
								<?php endif; ?>
							</p>
					<?php endif ?>
					
					<?php
					// Si une image est enregistrée on l'affiche
					if (isset($fleur_male["illustration_de_la_fleur_male_ou_de_linflorescence"]["photo_de_fleur_male"])){
						affichageImageFiche($fleur_male["illustration_de_la_fleur_male_ou_de_linflorescence"]["photo_de_fleur_male"]);
					}
					
					?>
				</div>
				
				<?php }
				$fleur_femelle =  get_field('fleur_femelle') ?: null;
				if ($fleur_femelle && !empty(get_field('systeme_sexuel')) && (get_field('systeme_sexuel') == "monoïque" ) ||
					(get_field('systeme_sexuel') == "dioïque" ) || (get_field('systeme_sexuel') == "gynomonoïque"
					) || (get_field('systeme_sexuel') == "gynodioïque" ) || (get_field('systeme_sexuel') == "androgynomonoïque" ) || (get_field('systeme_sexuel') == "androgynodioïque" ) || (get_field('systeme_sexuel') == "andromonoïque" )) {
				?>
				<div id="fleur-femelle">
					<div class="fiche-title-icon">
						<img src=" <?php echo get_template_directory_uri() ?>/images/fleur-femelle.svg" />
						<?php
						the_botascopia_module('title', [
							'title' => __('Fleur femelle', 'botascopia'),
							'level' => 2,
						]);
						?>
					</div>
					
					<?php if (!empty(get_field('systeme_sexuel')) && (get_field('systeme_sexuel') == "monoïque" ) ||
						(get_field('systeme_sexuel') == "dioïque" ) || (get_field('systeme_sexuel') == "gynomonoïque"
						) || (get_field('systeme_sexuel') == "gynodioïque" ) || (get_field('systeme_sexuel') == "androgynomonoïque" ) || (get_field('systeme_sexuel') == "androgynodioïque" ) || (get_field('systeme_sexuel') == "andromonoïque" )): ?>
						<p>
							Fleur <?php echo implode(' et ', $fleur_femelle['symetrie']); ?>.
							<?php if('présent' !== $fleur_femelle['perianthe']): { ?>
								Le périanthe est absent.
							<?php } else: { ?>
								<?php
								if ('tépales' === $fleur_femelle['differenciation_du_perianthe']) {
									$perianthe = implode(' ou ', $fleur_femelle['perigone']) . ' tépales ' .
										$fleur_femelle['soudure_du_perigone'] . ' ; ';
								} else {
									if (getType($fleur_femelle['soudure_de_la_corolle']) == 'string'){
										$soudure_corolle = $fleur_femelle['soudure_de_la_corolle'];
									} else {
										$soudure_corolle = implode(' ou ', $fleur_femelle['soudure_de_la_corolle']);
									}
									
									if (getType($fleur_femelle['corolle']) == 'string'){
										$corolle = $fleur_femelle['corolle'];
									} else {
										$corolle = implode(' ou ', $fleur_femelle['corolle']);
									}
									
									$perianthe = implode(' ou ', $fleur_femelle['calice']) . ' sépale(s) ' .
										$fleur_femelle['soudure_du_calice'] . ' et ' .
										$corolle . ' pétale(s) ' . $soudure_corolle
										. ' ; ' .
										('corolle soudée au calice' === $fleur_femelle['soudure_du_calice_et_de_la_corolle'] ? $fleur_femelle['soudure_du_calice_et_de_la_corolle'] . ' ; ' : '');
								}
								?>
								Le périanthe est composé de <?php echo $perianthe;
							} ?>
							<?php endif; ?>
							<?php if(!empty($fleur_femelle['gynecee'])): { ?>
								gynécée composé de <?php echo implode(' ou ' , $fleur_femelle['gynecee']); ?>  carpelle(s) <?php echo $fleur_femelle['soudure_des_carpelles']; ?> ;
								ovaire <?php echo $fleur_femelle['ovaire']; ?>.
								La couleur principale de la fleur est <?php echo $fleur_femelle['couleur_principale']; ?>.
								<?php if ('pubescente' === $fleur_femelle['pubescence']) {
									echo "La fleur est ".$fleur_femelle['pubescence'];?>
									<?php if (!empty($fleur_femelle['localisation_des_poils'])) {
										echo ' sur '.implode(', ' , $fleur_femelle['localisation_des_poils']).'.'; }
									else { echo '.'; }}?>
								<?php echo $fleur_femelle['autre_caractere'];
							}?>
							<?php endif; ?>
						</p>
					<?php endif ?>
					
					<?php
					// Si une image est enregistrée on l'affiche
					if (isset($fleur_femelle["illustration_de_la_fleur_femelle_ou_de_linflorescence"]['photo_de_fleur_femelle']) && $fleur_femelle["illustration_de_la_fleur_femelle_ou_de_linflorescence"]['photo_de_fleur_femelle']){
						affichageImageFiche($fleur_femelle["illustration_de_la_fleur_femelle_ou_de_linflorescence"]['photo_de_fleur_femelle']);
					}
					?>
				</div>
				
				<?php }
				$fleur_bisexuee =  get_field('fleur_bisexuee') ?: null;
				
				if ($fleur_bisexuee && !empty(get_field('systeme_sexuel')) && (get_field('systeme_sexuel') == "hermaphrodite" ) || (get_field('systeme_sexuel') == "andromonoïque" ) || (get_field('systeme_sexuel') == "gynomonoïque" ) || (get_field('systeme_sexuel') == "androdioïque" ) || (get_field('systeme_sexuel') == "gynodioïque" ) || (get_field('systeme_sexuel') == "androgynomonoïque" ) || (get_field('systeme_sexuel') == "androgynodioïque" )) {
				?>
				<div id="fleur-bisexuee">
					<div class="fiche-title-icon">
						<img src=" <?php echo get_template_directory_uri() ?>/images/inflorescence.svg" />
						<?php
						the_botascopia_module('title', [
							'title' => __('Fleur bisexuée', 'botascopia'),
							'level' => 2,
						]);
						?>
					</div>
					
					<?php $fleur_bisexuee =  get_field('fleur_bisexuee') ?: null;?>
					<?php if (!empty(get_field('systeme_sexuel')) && (get_field('systeme_sexuel') == "hermaphrodite" ) || (get_field('systeme_sexuel') == "andromonoïque" ) || (get_field('systeme_sexuel') == "gynomonoïque" ) || (get_field('systeme_sexuel') == "androdioïque" ) || (get_field('systeme_sexuel') == "gynodioïque" ) || (get_field('systeme_sexuel') == "androgynomonoïque" ) || (get_field('systeme_sexuel') == "androgynodioïque" )): ?>
							<p>
								Fleur <?php echo implode(' et ', $fleur_bisexuee['symetrie']); ?>.
								<?php if('présent' !== $fleur_bisexuee['perianthe']): { ?>
									Le périanthe est absent.
								<?php } else: { ?>
									<?php
									if (isset($fleur_bisexuee['composition_du_perianthe']) &&'tépales' === $fleur_bisexuee['composition_du_perianthe']) {
										$perianthe = implode(' ou ', $fleur_bisexuee['perigone']) . ' tépales ';
										$perianthe .=  !empty($fleur_bisexuee['soudure_du_perigone']) ? $fleur_bisexuee['soudure_du_perigone'] . ' ; ' : " ;";
									} else {
										$perianthe = implode(' ou ', $fleur_bisexuee['calice']) . ' sépale( s) ';
										$perianthe .= !empty($fleur_bisexuee['soudure_du_calice']) ? $fleur_bisexuee['soudure_du_calice'] : "" ;
										$perianthe .=    ' et ' . implode(' ou ', $fleur_bisexuee['corolle']) . ' pétale(s) ' ;
										$perianthe .= !empty($fleur_bisexuee['soudure_de_la_corolle']) ? $fleur_bisexuee['soudure_de_la_corolle'] . ' ; ' : "" ;
										$perianthe .= ('corolle soudée au calice' === $fleur_bisexuee['soudure_du_calice_et_de_la_corolle'] ?
											$fleur_bisexuee['soudure_du_calice_et_de_la_corolle'] . ' ; ' : '');
									}
									?>
									Le périanthe est composé de <?php echo $perianthe;
								} ?>
								<?php endif; ?>
								<?php if(!empty($fleur_bisexuee['androcee'])): { ?>
									androcée composé de <?php echo implode(' ou ' , $fleur_bisexuee['androcee']); ?> étamine(s)
									<?php echo $fleur_bisexuee['soudure_de_landrocee']; ?> ; <?php echo ('androcée soudé à la corolle' === $fleur_bisexuee['soudure_androcee-corolle'] ? $fleur_bisexuee['soudure_androcee-corolle'] . ', ' : ''). ('soudées au perigone' === $fleur_bisexuee['soudure_androcee-perigone'] ? $fleur_bisexuee['soudure_androcee-perigone'] . ', ' : ''); ?>
									<?php echo ('présents' === $fleur_bisexuee['staminodes'] ? $fleur_bisexuee['nombre_de_staminodes'] . ' staminodes ; ' : '');
								} ?>
								<?php endif; ?>
								<?php if(!empty($fleur_bisexuee['gynecee'])): { ?>
									gynécée composé de <?php echo implode(' ou ' , $fleur_bisexuee['gynecee']); ?>  carpelle(s) <?php echo $fleur_bisexuee['soudure_des_carpelles']; ?> ;
									ovaire <?php echo $fleur_bisexuee['ovaire']; ?>.
								<?php } ?>
								<?php endif; ?>
								La couleur principale de la fleur est le <?php echo $fleur_bisexuee['couleur_principale']; ?>.
								<?php if ('pubescente' === $fleur_bisexuee['pubescence']) {
									echo "La fleur est ".$fleur_bisexuee['pubescence'];?>
									<?php if (!empty($fleur_bisexuee['localisation_des_poils'])) {
										echo ' sur '.implode(', ' , $fleur_bisexuee['localisation_des_poils']).'.'; }
									else { echo '.'; }}?>
								<?php echo $fleur_bisexuee['autre_caractere'];?>
							</p>
					<?php endif ?>
					
					<?php
					// Si une image est enregistrée on l'affiche
					if (isset($fleur_bisexuee['illustration_de_la_fleur_bisexuee']['photo_de_fleur_bisexuee']) && (!empty(get_field('systeme_sexuel')) && (get_field('systeme_sexuel') == "hermaphrodite" ) || (get_field('systeme_sexuel') == "andromonoïque" ) || (get_field('systeme_sexuel') == "gynomonoïque" ) || (get_field('systeme_sexuel') == "androdioïque" ) || (get_field('systeme_sexuel') == "gynodioïque" ) || (get_field('systeme_sexuel') == "androgynomonoïque" ) || (get_field('systeme_sexuel') == "androgynodioïque" ))) {
						affichageImageFiche($fleur_bisexuee['illustration_de_la_fleur_bisexuee']['photo_de_fleur_bisexuee']);
					}
					?>
				</div>
				
				<?php }
				if (!empty(get_field('le_saviez-vous_'))){
				?>
				<div id="le-saviez-vous">
						<?php
						the_botascopia_module('title', [
							'title' => __('Le saviez-vous ?', 'botascopia'),
							'level' => 2,
						]);
						?>
					
					<p><?php (!empty(get_field('le_saviez-vous_'))) ? the_field('le_saviez-vous_') : "";?></p>
					
				</div>
				
				<?php }	?>
				<div id="periode-floraison">
					<div class="fiche-title-icon">
						<img src=" <?php echo get_template_directory_uri() ?>/images/periode.svg"/>
						<?php
						the_botascopia_module('title', [
							'title' => __('Période de <span class="text-floraison">floraison</span> et de <span class="text-fructification">fructification</span>',
										  'botascopia'),
							'level' => 2,
						]);
						?>
					</div>
					
					<div class="calendar">
						<?php $months = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre']; ?>
						<?php foreach ($months as $m => $month): ?>
							<?php
							$periodes_flor = get_field('periode_de_floraison') ?: null;
							$flor_ok = false;
							if($periodes_flor) {
								$index_flor = 0;
								while (isset($periodes_flor[$index_flor]) && !$flor_ok) {
									$flor_ok = $month === $periodes_flor[$index_flor];
									$index_flor++;
								}
							}
							
							$periodes_fruct = get_field('periode_de_fructification') ?: null;
							$fruct_ok = false;
							if($periodes_fruct) {
								$index_fruct = 0;
								while (isset($periodes_fruct[$index_fruct]) && !$fruct_ok) {
									$fruct_ok = $month === $periodes_fruct[$index_fruct];
									$index_fruct++;
								}
							}
							?>
							<div class="month"><?php echo substr($month,0,1); ?></div>
							<div class="floraison<?php echo $flor_ok ? '-vert' : ''; ?>-icon icon"></div>
							<div class="fructification<?php echo $fruct_ok ? '-mauve' : ''; ?>-icon icon"></div>
						<?php endforeach; ?>
					</div>
				
				</div>
				
				<?php if (!empty(get_field('amplitude_altitudinale')) || !empty(get_field('affinites_ecologiques')) || !empty(get_field('habitat_preferentiel')) || !empty(get_field('systeme_de_reproduction')) || !empty(get_field('pollinisation')) || !empty(get_field('dispersion'))): ?>
				<div id="ecologie">
					
					<div class="fiche-title-icon">
						<img src=" <?php echo get_template_directory_uri() ?>/images/ecologie.svg"/>
						<?php
						the_botascopia_module('title', [
							'title' => __('Écologie','botascopia'),
							'level' => 2,
						]);
						?>
					</div>
					
					<?php if (!empty(get_field('amplitude_altitudinale'))) :?> <p>Altitude : <?php echo get_field('amplitude_altitudinale'); ?> .</p> <?php endif; ?>
						<?php if (!empty(get_field('affinites_ecologiques'))) :?><p>Affinité écologique: <?php echo
						get_field('affinites_ecologiques') ? implode(', ', get_field('affinites_ecologiques')) : "";
						?> .</p><?php endif; ?>
						
						<?php if (!empty(get_field('habitat_preferentiel'))) :?> <p>Habitat : <?php the_field('habitat_preferentiel'); ?>.</p> <?php endif; ?>
						
						<?php if ((!empty(get_field('systeme_de_reproduction'))) || (!empty(get_field
						('pollinisation')))) :?> <p>Plante :<br><?php endif; ?>
						
						<?php if (!empty(get_field('systeme_de_reproduction'))) :?> Système de reproduction <?php
							the_field('systeme_de_reproduction'); ?>, <?php endif; ?>
						
						<?php if (!empty(get_field('pollinisation'))) :?> à pollinisation <?php the_field('pollinisation'); ?>, <?php endif; ?>
						
						<?php if (!empty(get_field('dispersion'))) :?> dispersion des graines et des fruits <?php
						echo get_field('dispersion') ? implode(', ', get_field('dispersion')) : ""; ?>.</p><?php endif;?>
				</div>
					<?php endif ?>
				
				<?php $proprietes = get_field('proprietes')?: null;
				if ($proprietes):
				?>
				<div id="proprietes">
					<?php
					the_botascopia_module('title', [
						'title' => __('Propriétés','botascopia'),
						'level' => 2,
					]);
					?>
					<p><?php echo $proprietes; ?></p>
				</div>
				<?php endif; ?>
				
				<?php if (!empty(get_field('cultivee_en_france')) || !empty(get_field('carte_de_metropole')) || !empty(get_field('repartition_mondiale')) || !empty(get_field('indigenat')) || !empty(get_field('statut_uicn'))): ?>
					<div id="aire-repartition">
						<div class="fiche-title-icon">
							<img src=" <?php echo get_template_directory_uri() ?>/images/location.svg"/>
							<?php
							the_botascopia_module('title', [
								'title' => __('Aire de répartition et statut', 'botascopia'),
								'level' => 2,
							]);
							?>
						</div>
					
						<?php if (!empty(get_field('cultivee_en_france'))) { ?>
							<?php $cultivee_en_france = get_field('cultivee_en_france'); ?>
							<p>En France la plante est présente <?php echo $cultivee_en_france; ?>,<?php echo ("à l'état sauvage" === $cultivee_en_france ? ' où elle est ' . implode (', ', get_field('indigenat')) . '.' : ''); ?> Statut UICN : <?php the_field('statut_uicn'); ?>.</p>
						<?php } ?>
						<?php if (!empty(get_field('carte_de_metropole'))) :?>
							<div class="section-image"><?php echo wp_get_attachment_image(get_field('carte_de_metropole')['id'], 'large'); ?></div>
						<?php endif; ?>
					</div>
				<?php endif; ?>
				
				<?php $description = get_field('description')?: null; ?>
				<?php if ($description): ?>
					<div id="ne-pas-confondre">
						<div class="fiche-title-icon">
							<img src=" <?php echo get_template_directory_uri() ?>/images/ne-pas-confondre.svg"/>
							<?php
							the_botascopia_module('title', [
								'title' => __('Ne pas confondre avec', 'botascopia'),
								'level' => 2,
							]);
							?>
						</div>
						
						<p><?php the_field('description'); ?></p>
						<?php $photo = get_field('illustration_de_la_plante_avec_risque_de_confusion_photo') ? : null;
						if (isset($photo)) {
							affichageImageFiche($photo);
						}
						?>
					</div>
				<?php endif; ?>
				
				<?php $anecdote = get_field('complement_danecdote')?: null; ?>
				<?php if ($anecdote): ?>
				<div id="complement-anecdote">
					<?php
					the_botascopia_module('title', [
						'title' => __('Complément d\'anecdote', 'botascopia'),
						'level' => 2,
					]);
					?>
					<p><?php the_field('complement_danecdote'); ?></p>
				</div>
				<?php endif; ?>
				
				<?php if (!empty(get_field('reference_1'))) : ?>
				<div id="references">
					<?php
					the_botascopia_module('title', [
						'title' => __('Références', 'botascopia'),
						'level' => 2,
					]);
					?>
					<ul>
						<li><?php the_field('reference_1'); ?></li>
						<?php if (!empty(get_field('reference_2'))) : ?>
							<li><?php the_field('reference_2'); ?></li>
						<?php endif; ?>
						
						<?php if (!empty(get_field('reference_3'))) : ?>
							<li><?php the_field('reference_3'); ?></li>
						<?php endif; ?>
						
						<?php if (!empty(get_field('reference_4'))) : ?>
							<li><?php the_field('reference_4'); ?></li>
						<?php endif; ?>
						
						<?php if (!empty(get_field('reference_5'))) : ?>
							<li><?php the_field('reference_5'); ?></li>
						<?php endif; ?>
					</ul>
				</div>
				<?php endif; ?>

                <div class="formulaire-boutons-bas">
                    <?php
                    $securise = (isset($_SERVER['HTTPS'])) ? "https://" : "http://";
                    the_botascopia_module('button',[
                        'tag' => 'a',
                        'title' => 'Retour au formulaire',
                        'text' => 'retour au formulaire',
                        'modifiers' => 'purple-button',
                        'extra_attributes' => ['onclick' => "window.location.href = '".$securise.$_SERVER['HTTP_HOST']."/formulaire/?p=".get_the_title()."'"]
                    ]);
                    ?>
                </div>
				
			</div>
	</main><!-- .site-main -->
</div><!-- .content-area -->

<?php
get_footer();
?>

