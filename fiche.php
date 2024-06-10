<?php
/* Template Name: Fiche Single */
/* Template Post Type: post */
?>
<?php
get_header();

?>
<div id="primary" class="content-area">
    <!-- Ne pas enlever car sinon, ça pète le css ! -->
	<div class="bg-fill">
	
	</div>
	<main id="main" class="site-main fiche-main" role="main">
		
		<?php
		$post = get_queried_object();
        $securise = (isset($_SERVER['HTTPS'])) ? "https://" : "http://";
		
		if (is_user_logged_in()){
			$current_user = wp_get_current_user();
			$current_user_id = $current_user->ID;
			$current_user_role = $current_user->roles[0];
        }else{
			$current_user_id = '';
        }
		
		if (is_user_logged_in() && get_user_meta($current_user_id, 'favorite_fiche')){
			$ficheFavorites = get_user_meta(wp_get_current_user()->ID, 'favorite_fiche');
        }
		
		$post_id = $post->ID;
		$post_author = get_the_author_meta(Constantes::DISPLAY_NAME, $post->post_author);
		$verificateur = '';
		$verificateur_id = get_post_meta(get_the_ID(), 'Editor', true);
		$verificateur_data = get_userdata($verificateur_id);
		if ($verificateur_data){
			$verificateur = $verificateur_data->display_name;
		}
		$title=get_the_title();
		$date = new DateTime($post->post_modified);
        $date_crea = new DateTime($post->post_date);
        $post_date = getDateInFrench($date->format('d F Y'));
        $post_date_crea = getDateInFrench($date_crea->format('d F Y'));
        

		$index_photos = 0;
		$fruit_photo=null;
		$refs_photo = array();
		$texte = "";
		switch ($post->post_status){
			case Constantes::DRAFT:
				$status = Constantes::DRAFT_COMP;
                if ($date > $date_crea){
                    $texte = Constantes::MODIFIEE_LE;
                }else{
                    $texte = Constantes::CREEE_LE;
                }
				break;
			case Constantes::PEND:
				$status = Constantes::PEND_FR;
                $texte = Constantes::MODIFIEE_LE;
				break;
			case Constantes::PUBLISH:
				$status = Constantes::PUBLISH_FR;
                $texte = Constantes::PUBLIEE_LE;
				break;
			default:
				$status = '';
		}

		the_botascopia_module('cover', [
			'subtitle' => get_post_meta($post_id, Constantes::NOM_VERNACULAIRE, true).' - '.get_post_meta($post_id, Constantes::FAMILLE,true),
			'title' => getFilteredTitle(get_post_meta($post_id, Constantes::NOM_SCIENTIFIQUE, true)),
			'image' => [get_template_directory_uri() .'/images/recto-haut.svg'],
			'modifiers' =>['class' => 'fiche-cover']
		]);

        $image = getFicheImage($post_id);
  
		echo ('
			<img src= '.$image .' class="fiche-image">
		');
		?>
		<div class="collection-main">
			<div class="left-div">
				
				<div class="single-collection-buttons" id="fiche-<?php echo $post_id ?>"
					 data-user-id="<?php echo $current_user_id?>"
					 data-fiche-id="<?php echo $post_id ?>">

                    <?php if (is_user_logged_in() && isset($ficheFavorites) &&get_user_meta($current_user_id, Constantes::FAVORITE_COLLECTION)
                        && ($key = array_search($post_id, $ficheFavorites[0])) !== false){
                        //changer le bouton favoris si collection dans favoris ou pas
                        $icone = ['icon' => 'star', 'color' => 'blanc'];
                        $modifiers = 'green-button';
                    }else{
                        $icone = ['icon' => 'star-outline', 'color' => 'vert-clair'];
                        $modifiers = 'green-button outline';
                    }

                    the_botascopia_module('button', [
                        'tag' => 'a',
                        'href' => '#',
                        'title' => Constantes::FAVORIS,
                        'text' => Constantes::FAVORIS,
                        'modifiers' => $modifiers,
                        'icon_after' => $icone,
                        'extra_attributes' => ['id' => 'fav-'.$post_id]
                    ]);
                    ?>
					
					<?php the_botascopia_module('button', [
						'tag' => 'a',
						'href' => '#',
						'title' => Constantes::TELECHARGER,
						'text' => Constantes::TELECHARGER,
						'modifiers' => 'green-button',
                        'icon_after' => ['icon' => 'pdf', 'color'=>'blanc'],
                        'extra_attributes' => ['onclick' => "window.location.href = '".$securise.$_SERVER['HTTP_HOST']."/export/?p=".get_the_title()."'"]
					]); ?>

                    <div class="single-collection-export-format">
                        Formats : PDF (60Mo)
                    </div>

				</div>

				
				<a class="return-button return-button-collection" href="#">
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
										'text' => Constantes::DESCRIPTION,
										'href' => '#description-morphologique',
										'active' => false,
									],
									[
										'text' => Constantes::VULG,
										'href' => '#description-vulgarisee',
										'active' => false,
									],
									[
										'text' => Constantes::TIGE,
										'href' => '#tige',
										'active' => false,
									],
									[
										'text' => Constantes::FEUILLE,
										'href' => '#feuilles',
										'active' => false,
									],
									[
										'text' => Constantes::INFLO,
										'href' => '#inflorescence',
										'active' => false,
									],
									[
										'text' => Constantes::FRUIT,
										'href' => '#fruits',
										'active' => false,
									],
									[
										'text' => Constantes::FL_MALE,
										'href' => '#fleur-male',
										'active' => false,
									],
									[
										'text' => Constantes::FL_FEM,
										'href' => '#fleur-femelle',
										'active' => false,
									],
									[
										'text' => Constantes::FL_BI,
										'href' => '#fleur-bisexuee',
										'active' => false,
									],
									[
										'text' => Constantes::LE_SAVIEZ_VOUS,
										'href' => '#le-saviez-vous',
										'active' => false,
									],
									[
										'text' => Constantes::PERIOD,
										'href' => '#periode-floraison',
										'active' => false,
									],
									[
										'text' => Constantes::ECOLOGIE,
										'href' => '#ecologie',
										'active' => false,
									],
									[
										'text' => Constantes::PROPERTIES,
										'href' => '#proprietes',
										'active' => false,
									],
									[
										'text' => Constantes::AIRE,
										'href' => '#aire-repartition',
										'active' => false,
									],
									[
										'text' => Constantes::CONFUS,
										'href' => '#ne-pas-confondre',
										'active' => false,
									],
									[
										'text' => Constantes::ANECDOTE,
										'href' => '#complement-anecdote',
										'active' => false,
									],
                                    [
                                        'text' => Constantes::AGRO,
                                        'href' => '#agroecologie',
                                        'active' => false,
                                    ],
									[
										'text' => Constantes::REFERENCES,
										'href' => '#references',
										'active' => false,
									],
                                    [
										'text' => Constantes::VOIR,
										'href' => '#voir_plus',
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
							<div class="single-fiche-detail">Statut : <?php echo $status; ?></div>
							<div class="single-fiche-detail"> <?php echo ($texte." ".$post_date); ?></div>
							<div class="single-fiche-detail">Par <?php echo $post_author; ?></div>
							<div class="single-fiche-detail">Vérifiée par <?php echo $verificateur; ?></div>
						</div>
						<div id="fiche-infos-right">
							<?php
							the_botascopia_module('title', [
								'title' => __(Constantes::APPARITION, 'botascopia'),
								'level' => 4,
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
									echo '<li><a href="'.$collection->guid.'">' . $collection->post_title . '</a></li>';
								}
								?>
							</ul>
						</div>
					</div>
                </div>
				<div>
                    <div id="description-morphologique">
                        
                        <div class="fiche-title-icon">
                            <img src=" <?php echo get_template_directory_uri() ?>/images/description.svg" />
                            <?php
                            the_botascopia_module('title', [
                                'title' => __(Constantes::DESCRIPTION, 'botascopia'),
                                'level' => 2,
                            ]);
                            ?>
                        </div>

                        <p><?php if (!empty(get_field(Constantes::PORT_DE_LA_PLANTE))) { 
                                echo ucfirst(get_field(Constantes::PORT_DE_LA_PLANTE))." ";
                                if (!empty(get_field(Constantes::SYS_SEXUEL)) && get_field(Constantes::SYS_SEXUEL) !== Constantes::HERMAPHRODITE ) { echo get_field(Constantes::SYS_SEXUEL).", " ;}
                                if ((get_field(Constantes::PORT_DE_LA_PLANTE) ==  Constantes::HERBACEE|| get_field(Constantes::PORT_DE_LA_PLANTE) == Constantes::LIANE) && !empty(get_field(Constantes::MODE_DE_VIE)) && get_field(Constantes::MODE_DE_VIE) !== array(Constantes::TERRESTRE) ) { echo implode(', ', get_field(Constantes::MODE_DE_VIE)).", " ; }
                                if (get_field(Constantes::PORT_DE_LA_PLANTE) == Constantes::HERBACEE && !empty(get_field(Constantes::TYPE_DE_DVPT))) { echo implode(', ', get_field(Constantes::TYPE_DE_DVPT)).", " ;}
                                if ((get_field(Constantes::PORT_DE_LA_PLANTE) == Constantes::HERBACEE || get_field(Constantes::PORT_DE_LA_PLANTE) == Constantes::LIANE) && !empty(get_field(Constantes::FORME_BIOLOGIQUE))) { echo implode(', ', get_field(Constantes::FORME_BIOLOGIQUE)).", " ;} ?>
                                qui peut atteindre jusqu'à <?php the_field(Constantes::HAUTEUR_MAXIMALE); ?> de haut.
                                <?php if (!empty(get_field(Constantes::PILOSITE))) { echo "Cette plante est ".get_field(Constantes::PILOSITE).".";} ?>
                            <?php } ?>
                        </p>
                    </div>
				</div>
				<?php $description_vulgarisee = get_field(Constantes::VULG)?: null; ?>
                <div>
                    <?php if ($description_vulgarisee){ ?>
                        <div id="description-vulgarisee">
                            <?php
                            the_botascopia_module('title', [
                                'title' => __(Constantes::VULG, 'botascopia'),
                                'level' => 2,
                            ]);
                            ?>
                            <p><?php the_field(Constantes::DESC_VULG); ?></p>
                        </div>
                    <?php } ?>
                </div>

                <div>
                    <div id="tige" class="display-fiche-container">
                        <div class="fiche-title-container">
                            <div class="fiche-title-icon">
                                <img src=" <?php echo get_template_directory_uri() ?>/images/tige.svg" />
                                <?php
                                the_botascopia_module('title', [
                                    'title' => __(Constantes::TIGE, 'botascopia'),
                                    'level' => 2,
                                ]);
                                ?>
                            </div>
                            <div>
                                <?php if (!empty(get_field(Constantes::TIGE_CHP))){?>
                                    <p class="tige-description">
                                        <?php
                                        $tige = get_field(Constantes::TIGE_CHP);
                                        if (!empty($tige)) {
                                            $type_tige = implode(', ', $tige[Constantes::TYPE_DE_TIGE]);
                                            $section_tige = implode('-', $tige[Constantes::SECTION_TIGE]);
                                            $surface_tige = implode(', ', $tige[Constantes::SURFACE_TIGE]);
                                            $port_de_la_plante = get_field(Constantes::PORT_DE_LA_PLANTE);
                                            if (!empty($port_de_la_plante)) {
                                                if (($port_de_la_plante === Constantes::ARBRISSEAU) || ($port_de_la_plante === Constantes::ARBRE)) {
                                                    if (!empty($tige[Constantes::SURFACE_ECORCE])) {
                                                        $surface_ecorce = implode(', ', $tige[Constantes::SURFACE_ECORCE]);
                                                    }
                                                }
                                            }
                                        }
                                        ?>
                                        La tige aérienne est <?php $tige_type = $tige[Constantes::TIGE_AERIENNE]; if ($tige_type !== Constantes::VISIBLE){echo trim($tige_type).",";}?>
                                        <?php if ($tige_type != Constantes::NON_VISIBLE){?> 
                                            <?php echo" $type_tige";?>, <?php echo $tige[Constantes::RAMIFICATION];?>, à section <?php echo $section_tige;?>.
                                            <br>Sa surface est <?php echo $surface_tige;?> au moins quand elle est jeune.
                                            <?php if ((($port_de_la_plante === Constantes::ARBRISSEAU) || ($port_de_la_plante === Constantes::ARBRE)) && (!empty($surface_ecorce))){ ?>
                                                <br>L'écorce est <?php echo $surface_ecorce;?><?php if (!empty($tige[Constantes::COULEUR_TRONC])) {?> et <?php echo $tige[Constantes::COULEUR_TRONC];} ?>.
                                            <?php } ?>
                                        <?php }?>
                                    
                                    </p>
                                <?php } ?>
                            </div>
                        </div>
                        
                        <?php
                        // Si une image est enregistrée on l'affiche
                        if (isset($tige[Constantes::ILLUSTRATION_TIGE][Constantes::PHOTO_TIGE])){
                            affichageImageFiche($tige[Constantes::ILLUSTRATION_TIGE][Constantes::PHOTO_TIGE]);
                        }
                        ?>
                    </div>
                </div>    
				
                <div>
                    <div id="feuilles">
                        <div id="feuille-texte" class="fiche-title-container"> 
                            <div id="titre-feuilles" class="fiche-title-icon">
                                <img src=" <?php echo get_template_directory_uri() ?>/images/feuilles.svg" />
                            
                                
                                    <?php
                            
                                    the_botascopia_module('title', [
                                        'title' => __(Constantes::FEUILLE, 'botascopia'),
                                        'level' => 2,
                                    ]);
                                    ?>
                            
                            </div>
                            <?php  if (!empty(get_field(Constantes::FEUILLE_CHP))) { ?>
                                
                                <?php $presence_feuilles = get_field(Constantes::FEUILLE_CHP)[Constantes::PRESENCE_FEUILLES]; ?>
                                <?php if (Constantes::JAMAIS_VISIBLES === $presence_feuilles){ ?>
                                    <p><?php echo $presence_feuilles; ?></p>
                                <?php }else{ ?>
                                    <?php $champ = get_field('appareil_vegetatif');
                                        if (!empty($champ)){
                                            echo "Description de l'appareil végétatif : $champ";
                                        }
                                    ?>
                                    <?php $heteromorphisme_foliaire = get_field(Constantes::HETEROMORPHISME); 
                                            
                                    ?>
                                    <?php if ((Constantes::FEUILLES_SEMBLABLES === $heteromorphisme_foliaire) || (Constantes::GRADIENT === $heteromorphisme_foliaire)){ ?>
                                        
                                        <?php $feuilles_aeriennes = get_field(Constantes::FEUILLES_AERIENNES); ?>
                                        <div class="display-fiche-container">
                                            <p>
                                                Les feuilles sont disposées de façon <?php $phyllo= implode(' et ', $feuilles_aeriennes[Constantes::PHYLLOTAXIE]); echo $phyllo; ?> et elles sont <?php echo implode(' et ', $feuilles_aeriennes[Constantes::TYPE_DE_FEUILLE]);?>.<br>
                                                <?php
                                                $type_feuille_multiple = 1 < count($feuilles_aeriennes[Constantes::TYPE_DE_FEUILLE]);
                                                $limbe = 'Le limbe %s est %s';
                                                $type_limbe = [];
                                                
                                                foreach ($feuilles_aeriennes[Constantes::TYPE_DE_FEUILLE] as $type_feuille) {
                                                    if (Constantes::SIMPLES === $type_feuille) {
                                                        $text = implode('-', $feuilles_aeriennes[Constantes::LIMBE_FEUILLES_SIMPLES]);
                                                        $text = getPubescence($feuilles_aeriennes,1,$text);
                                                        $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des feuilles simples' : ''), trim($text));
                                                    } else {
                                                        $texte = implode('-', $feuilles_aeriennes[Constantes::LIMBE_FOLIOLES]);
                                                        $texte = getPubescence($feuilles_aeriennes,2,$texte);
                                                        $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des folioles' : ''), trim($texte));
                                                    }
                                                }
                                                
                                                ?>
                                                
                                                <?php echo implode('. ', $type_limbe);?>, à marge foliaire <?php echo implode(' et ', $feuilles_aeriennes[Constantes::MARGE_FOLIAIRE]);?> et à nervation <?php echo implode(' et ', $feuilles_aeriennes[Constantes::NERVATION]);?>.<br>
                                                
                                                <?php
                                                $presence_petiole = $feuilles_aeriennes[Constantes::PETIOLE];
                                                $petiole = Constantes::PRESENT === $presence_petiole ? $feuilles_aeriennes[Constantes::LONGUEUR_PETIOLE] . (Constantes::ENGAINANT === $feuilles_aeriennes[Constantes::ENGAINANT] ? ', ' . $feuilles_aeriennes[Constantes::ENGAINANT] :'') : $presence_petiole;
                                                ?>
                                                
                                                Le pétiole est <?php echo $petiole; ?>.<br>
                                                
                                                <?php echo Constantes::PRESENTS === $feuilles_aeriennes[Constantes::STIPULES] ? $feuilles_aeriennes[Constantes::FORME_COULEUR_STIPULES] : '';?>
                                                
                                                <?php $port_de_la_plante = get_field(Constantes::PORT_DE_LA_PLANTE); ?>
                                                <?php if (!empty($port_de_la_plante)){ ?>
                                                    <?php if (($port_de_la_plante === Constantes::ARBRISSEAU) || ($port_de_la_plante === Constantes::ARBRE)){ ?>
                                                        <?php echo  $feuilles_aeriennes[Constantes::FEUILLAGE] ? 'Le feuillage est ' . $feuilles_aeriennes[Constantes::FEUILLAGE].'.' : '';?>
                                                    <?php } ?>
                                                <?php } ?>
                                                
                                            </p>
                                        </div>    
                                    
                                    
                                    <?php }elseif (Constantes::DEUX_FORMES=== $heteromorphisme_foliaire){?>
                                        
                                        <?php $deux_formes_distinctes = get_field(Constantes::DEUX_FORMES_CHP); ?>
                                        <?php if ($deux_formes_distinctes === Constantes::FEUILLES_IMMERGEES_AERIENNES): ?>
                                            
                                            <?php $feuilles_aeriennes = get_field(Constantes::FEUILLES_AERIENNES); ?>
                                            <?php if(!empty($feuilles_aeriennes)): ?>
                                                <div class="display-fiche-container">
                                                    
                                                        <h4 class="icon-title">
                                                            <div class="feuilles-icon icon" style="background-size: cover"></div>Feuilles aériennes
                                                        </h4>
                                                        <p>
                                                            Les feuilles sont disposées de façon <?php $phyllo = implode(' et ', $feuilles_aeriennes[Constantes::PHYLLOTAXIE]); echo $phyllo;?>, et elles sont <?php echo implode(' et ', $feuilles_aeriennes[Constantes::TYPE_DE_FEUILLE]);?>.<br>
                                                            <?php
                                                            $type_feuille_multiple = 1 < count($feuilles_aeriennes[Constantes::TYPE_DE_FEUILLE]);
                                                            $limbe = 'Le limbe %s est %s';
                                                            $type_limbe = [];

                                                            foreach ($feuilles_aeriennes[Constantes::TYPE_DE_FEUILLE] as $type_feuille) {
                                                                if (Constantes::SIMPLES === $type_feuille) {
                                                                    $text = implode('-', $feuilles_aeriennes[Constantes::LIMBE_FEUILLES_SIMPLES]);
                                                                    $text = getPubescence($feuilles_aeriennes,1,$text);
                                                                    $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des feuilles simples' : ''), trim($text));
                                                                } else {
                                                                    $texte = implode('-', $feuilles_aeriennes[Constantes::LIMBE_FOLIOLES]);
                                                                    $texte = getPubescence($feuilles_aeriennes,2,$texte);
                                                                    $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des folioles' : ''), trim($texte));
                                                                }
                                                            }
                                                            ?>

                                                            <?php echo implode('. ', $type_limbe);?>, à marge foliaire <?php echo implode(' et ', $feuilles_aeriennes[Constantes::MARGE_FOLIAIRE]);?> et à nervation <?php echo implode(' et ', $feuilles_aeriennes[Constantes::NERVATION]);?>.<br>

                                                            <?php
                                                            $presence_petiole = $feuilles_aeriennes[Constantes::PETIOLE];
                                                            $petiole = Constantes::PRESENT === $presence_petiole ? $feuilles_aeriennes[Constantes::LONGUEUR_PETIOLE] . (Constantes::ENGAINANT === $feuilles_aeriennes[Constantes::ENGAINANT] ? ', ' . $feuilles_aeriennes[Constantes::ENGAINANT] :'') : $presence_petiole;
                                                            ?>

                                                            Le pétiole est <?php echo $petiole; ?>.<br>

                                                            <?php echo  Constantes::PRESENTS === $feuilles_aeriennes[Constantes::STIPULES] ? $feuilles_aeriennes[Constantes::FORME_COULEUR_STIPULES] : '';?>

                                                            <?php $port_de_la_plante = get_field(Constantes::PORT_DE_LA_PLANTE); ?>
                                                            <?php if (!empty($port_de_la_plante)): ?>
                                                                <?php if (($port_de_la_plante === Constantes::ARBRISSEAU) || ($port_de_la_plante === Constantes::ARBRE)): ?>
                                                                    <?php echo  $feuilles_aeriennes[Constantes::FEUILLAGE] ? 'Le feuillage est ' . $feuilles_aeriennes[Constantes::FEUILLAGE].'.' : '';?>
                                                                <?php endif; ?>
                                                            <?php endif; ?>
                                                        </p>
                                                    
                                                    
                                                </div>
                                            <?php endif; ?>

                                            <?php $feuilles_immergees = get_field(Constantes::FEUILLES_IMMERGEES); ?>
                                            <?php if(!empty($feuilles_immergees)): ?>
                                            <div class="display-fiche-container">
                                                
                                                    <h4 class="icon-title">
                                                        <div class="feuilles-icon icon" style="background-size: cover"></div>Feuilles immergées
                                                    </h4>
                                                    <p>
                                                        Les feuilles sont disposées de façon <?php $phyllo= implode(' et ', $feuilles_immergees[Constantes::PHYLLOTAXIE]); echo $phyllo; ?>, et elles sont <?php echo implode(' et ', $feuilles_immergees[Constantes::TYPE_DE_FEUILLE]);?>.<br>
                                                        <?php
                                                        $type_feuille_multiple = 1 < count($feuilles_immergees[Constantes::TYPE_DE_FEUILLE]);
                                                        $limbe = 'Le limbe %s est %s';
                                                        $type_limbe = [];

                                                        foreach ($feuilles_immergees[Constantes::TYPE_DE_FEUILLE] as $type_feuille) {
                                                            if (Constantes::SIMPLES === $type_feuille) {
                                                                $text = implode('-', $feuilles_immergees[Constantes::LIMBE_FEUILLES_SIMPLES]);
                                                                $text = getPubescence($feuilles_immergees,1,$text);
                                                                $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des feuilles simples' : ''), trim($text));
                                                            } else {
                                                                $texte = implode('-', $feuilles_immergees[Constantes::LIMBE_FOLIOLES]);
                                                                $texte = getPubescence($feuilles_immergees,2,$texte);
                                                                $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des folioles' : ''), trim($texte));
                                                            }
                                                        }
                                                        ?>

                                                        <?php echo implode('. ', $type_limbe);?>, à marge foliaire <?php echo implode(' et ', $feuilles_immergees[Constantes::MARGE_FOLIAIRE]);?> et à nervation <?php echo implode(' et ', $feuilles_immergees[Constantes::NERVATION]);?>.<br>

                                                        <?php
                                                        $presence_petiole = $feuilles_immergees[Constantes::PETIOLE];
                                                        $petiole = Constantes::PRESENT === $presence_petiole ? $feuilles_immergees[Constantes::LONGUEUR_PETIOLE] . (Constantes::ENGAINANT === $feuilles_immergees[Constantes::ENGAINANT] ? ', ' . $feuilles_immergees[Constantes::ENGAINANT] :'') : $presence_petiole;
                                                        ?>

                                                        Le pétiole est <?php echo $petiole; ?>.<br>

                                                        <?php echo  Constantes::PRESENTS === $feuilles_immergees[Constantes::STIPULES] ? $feuilles_immergees[Constantes::FORME_COULEUR_STIPULES] : '';?>

                                                        <?php $port_de_la_plante = get_field(Constantes::PORT_DE_LA_PLANTE); ?>
                                                        <?php if (!empty($port_de_la_plante)): ?>
                                                            <?php if (($port_de_la_plante === Constantes::ARBRISSEAU) || ($port_de_la_plante === Constantes::ARBRE)): ?>
                                                                <?php echo  $feuilles_immergees[Constantes::FEUILLAGE] ? 'Le feuillage est ' . $feuilles_immergees[Constantes::FEUILLAGE].'.' : '';?>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </p>
                                                
                                                
                                            </div>
                                            <?php endif; ?>
                                        
                                        <?php elseif ($deux_formes_distinctes === Constantes::RAMEAUX_STERILES_FLEURIS): ?>
                                            
                                            <?php $feuilles_des_rameaux_steriles = get_field(Constantes::FEUILLES_RAMEAUX_STERILES); ?>
                                            <?php if(!empty($feuilles_des_rameaux_steriles)): ?>
                                            <div class="display-fiche-container">
                                                
                                                    <h4 class="icon-title">
                                                        <div class="feuilles-icon icon" style="background-size: cover"></div>Feuilles des rameaux stériles
                                                    </h4>
                                                    <p>
                                                        Les feuilles sont disposées de façon <?php $phyllo=  implode(' et ', $feuilles_des_rameaux_steriles[Constantes::PHYLLOTAXIE]); echo $phyllo;?> et elles sont <?php echo implode(' et ', $feuilles_des_rameaux_steriles[Constantes::TYPE_DE_FEUILLE]);?>.<br>
                                                        <?php
                                                        $type_feuille_multiple = 1 < count($feuilles_des_rameaux_steriles[Constantes::TYPE_DE_FEUILLE]);
                                                        $limbe = 'Le limbe %s est %s';
                                                        $type_limbe = [];

                                                        foreach ($feuilles_des_rameaux_steriles[Constantes::TYPE_DE_FEUILLE] as $type_feuille) {
                                                            if (Constantes::SIMPLES === $type_feuille) {
                                                                $text = implode('-', $feuilles_des_rameaux_steriles[Constantes::LIMBE_FEUILLES_SIMPLES]);
                                                                $text = getPubescence($feuilles_des_rameaux_steriles,1,$text);
                                                                $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des feuilles simples' : ''), trim($text));
                                                            } else {
                                                                $texte = implode('-', $feuilles_des_rameaux_steriles[Constantes::LIMBE_FOLIOLES]);
                                                                $texte = getPubescence($feuilles_des_rameaux_steriles,2,$texte);
                                                                $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des folioles' : ''), trim($texte));
                                                            }
                                                        }
                                                        ?>

                                                        <?php echo implode('. ', $type_limbe);?>, à marge foliaire <?php echo implode(' et ', $feuilles_des_rameaux_steriles[Constantes::MARGE_FOLIAIRE]);?> et à nervation <?php echo implode(' et ', $feuilles_des_rameaux_steriles[Constantes::NERVATION]);?>.<br>

                                                        <?php
                                                        $presence_petiole = $feuilles_des_rameaux_steriles[Constantes::PETIOLE];
                                                        $petiole = Constantes::PRESENT === $presence_petiole ? $feuilles_des_rameaux_steriles[Constantes::LONGUEUR_PETIOLE] . (Constantes::ENGAINANT === $feuilles_des_rameaux_steriles[Constantes::ENGAINANT] ? ', ' . $feuilles_des_rameaux_steriles[Constantes::ENGAINANT] :'') : $presence_petiole;
                                                        ?>

                                                        Le pétiole est <?php echo $petiole; ?>.<br>

                                                        <?php echo  Constantes::PRESENTS === $feuilles_des_rameaux_steriles[Constantes::STIPULES] ? $feuilles_des_rameaux_steriles[Constantes::FORME_COULEUR_STIPULES] : '';?>

                                                        <?php $port_de_la_plante = get_field(Constantes::PORT_DE_LA_PLANTE); ?>
                                                        <?php if (!empty($port_de_la_plante)): ?>
                                                            <?php if (($port_de_la_plante === Constantes::ARBRISSEAU) || ($port_de_la_plante === Constantes::ARBRE)): ?>
                                                                <?php echo  $feuilles_des_rameaux_steriles[Constantes::FEUILLAGE] ? 'Le feuillage est ' . $feuilles_des_rameaux_steriles[Constantes::FEUILLAGE].'.' : '';?>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </p>
                                                
                                            </div>
                                            <?php endif; ?>

                                            <?php $feuilles_des_rameaux_fleuris = get_field(Constantes::FEUILLES_RAMEAUX_FLEURIS); ?>
                                            <?php if(!empty($feuilles_des_rameaux_fleuris)): ?>
                                            <div class="display-fiche-container">
                                                
                                                    <h4 class="icon-title">
                                                        <div class="feuilles-icon icon" style="background-size: cover"></div>Feuilles des rameaux fleuris
                                                    </h4>
                                                    <p>
                                                        Les feuilles sont disposées de façon <?php $phyllo = implode(' et ', $feuilles_des_rameaux_fleuris[Constantes::PHYLLOTAXIE]); echo $phyllo;?>, et elles sont <?php echo implode(' et ', $feuilles_des_rameaux_fleuris[Constantes::TYPE_DE_FEUILLE]);?>.<br>
                                                        <?php
                                                        $type_feuille_multiple = 1 < count($feuilles_des_rameaux_fleuris[Constantes::TYPE_DE_FEUILLE]);
                                                        $limbe = 'Le limbe %s est %s';
                                                        $type_limbe = [];

                                                        foreach ($feuilles_des_rameaux_fleuris[Constantes::TYPE_DE_FEUILLE] as $type_feuille) {
                                                            if (Constantes::SIMPLES === $type_feuille) {
                                                                $text = implode('-', $feuilles_des_rameaux_fleuris[Constantes::LIMBE_FEUILLES_SIMPLES]);
                                                                $text = getPubescence($feuilles_des_rameaux_fleuris,1,$text);
                                                                $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des feuilles simples' : ''), trim($text));
                                                            } else {
                                                                $texte = implode('-', $feuilles_des_rameaux_fleuris[Constantes::LIMBE_FOLIOLES]);
                                                                $texte = getPubescence($feuilles_des_rameaux_fleuris,2,$texte);
                                                                $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des folioles' : ''), trim($texte));
                                                            }
                                                        }
                                                        ?>

                                                        <?php echo implode('. ', $type_limbe);?>, à marge foliaire <?php echo implode(' et ', $feuilles_des_rameaux_fleuris[Constantes::MARGE_FOLIAIRE]);?> et à nervation <?php echo implode(' et ', $feuilles_des_rameaux_fleuris[Constantes::NERVATION]);?>.<br>

                                                        <?php
                                                        $presence_petiole = $feuilles_des_rameaux_fleuris[Constantes::PETIOLE];
                                                        $petiole = Constantes::PRESENT === $presence_petiole ? $feuilles_des_rameaux_fleuris[Constantes::LONGUEUR_PETIOLE] . (Constantes::ENGAINANT === $feuilles_des_rameaux_fleuris[Constantes::ENGAINANT] ? ', ' . $feuilles_des_rameaux_fleuris[Constantes::ENGAINANT] :'') : $presence_petiole;
                                                        ?>

                                                        Le pétiole est <?php echo $petiole; ?>.<br>

                                                        <?php echo  Constantes::PRESENTS === $feuilles_des_rameaux_fleuris[Constantes::STIPULES] ? $feuilles_des_rameaux_fleuris[Constantes::FORME_COULEUR_STIPULES] : '';?>

                                                        <?php $port_de_la_plante = get_field(Constantes::PORT_DE_LA_PLANTE); ?>
                                                        <?php if (!empty($port_de_la_plante)): ?>
                                                            <?php if (($port_de_la_plante === Constantes::ARBRISSEAU) || ($port_de_la_plante === Constantes::ARBRE)): ?>
                                                                <?php echo  $feuilles_des_rameaux_fleuris[Constantes::FEUILLAGE] ? 'Le feuillage est ' . $feuilles_des_rameaux_fleuris[Constantes::FEUILLAGE].'.' : '';?>
                                                            <?php endif; ?>
                                                        <?php endif; ?>
                                                    </p>
                                            </div>
                                            <?php endif; ?>

                                        <?php endif; ?>

                                    
                                    <?php } ?>
                                
                                
                                <?php } ?>
                        
                            <?php } ?>
                        </div>
                        <?php 
                            if (isset($feuilles_aeriennes[Constantes::ILLUSTRATION_FEUILLE_AERIENNE][Constantes::PHOTO_FEUILLES_AERIENNES])){
                                affichageImageFiche($feuilles_aeriennes[Constantes::ILLUSTRATION_FEUILLE_AERIENNE][Constantes::PHOTO_FEUILLES_AERIENNES],"id = feuille-image");
                            }else if (isset($feuilles_immergees[Constantes::ILLUSTRATION_FEUILLE_IMMERGEE][Constantes::PHOTO_FEUILLES_IMMERGEES])){
                                affichageImageFiche($feuilles_immergees[Constantes::ILLUSTRATION_FEUILLE_IMMERGEE][Constantes::PHOTO_FEUILLES_IMMERGEES],"id = feuille-image");
                            }else if (isset($feuilles_des_rameaux_steriles[Constantes::ILLUSTRATION_FEUILLE_RAMEAUX_STERILES][Constantes::PHOTO_FEUILLES_RAMEAUX_STERILES])){
                                affichageImageFiche($feuilles_des_rameaux_steriles[Constantes::ILLUSTRATION_FEUILLE_RAMEAUX_STERILES][Constantes::PHOTO_FEUILLES_RAMEAUX_STERILES],"id = feuille-image");
                            }else if (isset($feuilles_des_rameaux_fleuris[Constantes::ILLUSTRATION_FEUILLE_RAMEAUX_FLEURIS][Constantes::PHOTO_FEUILLES_RAMEAUX_FLEURIS])){
                                affichageImageFiche($feuilles_des_rameaux_fleuris[Constantes::ILLUSTRATION_FEUILLE_RAMEAUX_FLEURIS][Constantes::PHOTO_FEUILLES_RAMEAUX_FLEURIS],"id = feuille-image");
                            }
                        ?>    
                    </div>
                </div>

                <div>
                    <div id="inflorescence">
                        <div class="fiche-title-icon">
                            <img src=" <?php echo get_template_directory_uri() ?>/images/inflorescence.svg" />
                            <?php
                            the_botascopia_module('title', [
                                'title' => __(Constantes::INFLO, 'botascopia'),
                                'level' => 2,
                            ]);
                            ?>
                        </div>
                        
                        <?php  if (!empty(get_field(Constantes::INFLO_CHP))) { ?>
                            <?php $inflorescence = get_field(Constantes::INFLO_CHP);?>
                            <p>Les fleurs sont <?php $organisation = $inflorescence[Constantes::ORGANISATION_FLEURS]; echo $organisation.". ";
                            $categorie = $inflorescence[Constantes::CATEGORIE];
                            if ($organisation === Constantes::ORGANISEES_EN_INFLORESCENCES){
                                if( $categorie != Constantes::AUTRE) {
                                    ?>L’inflorescence est <?php echo $inflorescence[Constantes::CATEGORIE]; ?>.</p>
                                <?php } else {
                                    ?>L’inflorescence est <?php echo $inflorescence[Constantes::DESCRIPTION_CHP]; ?>.</p>
                                <?php } ?>
                            <?php } ?>
                        <?php } ?>
                    </div>
				</div>
                <div>
                    <div id="fruits" class="display-fiche-container">
                        <div class="fiche-title-container">
                            <div class="fiche-title-icon">
                                <img src=" <?php echo get_template_directory_uri() ?>/images/fruits.svg" />
                                <?php
                                the_botascopia_module('title', [
                                    'title' => __(Constantes::FRUIT, 'botascopia'),
                                    'level' => 2,
                                ]);
                                ?>
                            </div>
                            <div>
                                <?php  if (!empty(get_field(Constantes::FRUIT_CHP))) { ?>
                                    <?php $fruit = get_field(Constantes::FRUIT_CHP);?>
                                    
                                    <p>Le fruit est <?php echo $fruit[Constantes::TYPE];?>.</p>
                                <?php } ?>
                            </div>
                        </div>

                        <?php
                        // Si une image est enregistrée on l'affiche
                        if (isset($fruit[Constantes::ILLUSTRATION_FRUIT][Constantes::PHOTO])){
                            affichageImageFiche($fruit[Constantes::ILLUSTRATION_FRUIT][Constantes::PHOTO]);
                        }
                        ?>
                    </div>
                </div>
				<?php $fleur_male =  get_field(Constantes::FL_MALE_CHP) ?: null;?>
                <?php $fleur_femelle =  get_field(Constantes::FL_FEM_CHP) ?: null;?>
                <?php $fleur_bisexuee =  get_field(Constantes::FL_BI_CHP) ?: null;?>
                <?php $systeme = get_field(Constantes::SYS_SEXUEL);
                    
                    switch ($systeme) {
                        case Constantes::HERMAPHRODITE:
                        $fleur_male = null;
                        $fleur_femelle = null;
                        break;
                    case Constantes::MONOIQUE:
                        $fleur_bisexuee =null;
                        break;
                    case Constantes::DIOIQUE:
                        $fleur_bisexuee = null;
                        break;
                    case Constantes::ANDROMONOIQUE:
                        $fleur_femelle = null;
                        break;
                    case Constantes::GYNOMONOIQUE;
                        $fleur_male = null;
                        break;
                    case Constantes::ANDRODIOIQUE;
                        $fleur_femelle = null;
                        break;
                    case Constantes::GYNODIOIQUE;
                        $fleur_male = null;
                        break;
                    default;
                        break;
                    }
                ?>
                <div>
                    <?php if (isset($fleur_male) AND !empty($fleur_male)){
                            if (isset($systeme) AND !empty($systeme)){
                                if (($systeme === Constantes::MONOIQUE) OR ($systeme === Constantes::DIOIQUE) OR ($systeme === Constantes::ANDROMONOIQUE) OR ($systeme === Constantes::ANDRODIOIQUE) OR ($systeme === Constantes::ANDROGYNOMONIQUE) OR ($systeme === Constantes::ANDROGYNODIOIQUE)){ ?>
                                    <div id="fleur-male" class="display-fiche-container">
                                        <div class="fiche-title-container">
                                            <div class="fiche-title-icon">
                                                <img src=" <?php echo get_template_directory_uri() ?>/images/fleur-male.svg"/>
                                                <?php
                                                the_botascopia_module('title', [
                                                    'title' => __(Constantes::FL_MALE, 'botascopia'),
                                                    'level' => 2,
                                                ]);
                                                ?>
                                            </div>
                                            <div>
                                                <p>
                                                    
                                                    <?php if(Constantes::PRESENT !== $fleur_male[Constantes::PERIANTHE]) { ?>
                                                        Le périanthe est absent ; 
                                                    <?php }else{ ?>
                                                        <?php
                                                        $perianthe="";
                                                        echo "Fleur ".implode(' et ', $fleur_male[Constantes::SYMETRIE]).". ";
                                                        $composition = $fleur_male[Constantes::DIFFERENCIATION_PERIANTHE];
                                                        $perianthe = displayPerianthe($composition,$fleur_male);
                                                        ?>
                                                        Le périanthe est composé de <?php echo $perianthe.". ";
                                                    } ?>
                                                    
                                                    <?php if(!empty($fleur_male[Constantes::ANDROCEE])){ ?>
                                                        Androcée composé de <?php $etamines = $fleur_male[Constantes::ANDROCEE]; echo getValueOrganesFloraux($etamines,"étamine(s)",$fleur_male[Constantes::SOUDURE_ANDROCEE]) ?> ;
                                                        <?php echo (Constantes::ANDROCEE_SOUDEE_COROLLE === $fleur_male[Constantes::SOUDURE_ANDROCEE_COROLLE] ? $fleur_male[Constantes::SOUDURE_ANDROCEE_COROLLE] . ', ' : '').
                                                            (Constantes::SOUDEES_PERIGONE === $fleur_male[Constantes::SOUDURE_ANDROCEE_PERIGONE] ? $fleur_male[Constantes::SOUDURE_ANDROCEE_PERIGONE] . ', ' : ''); ?>
                                                        <?php echo (Constantes::PRESENTS === $fleur_male[Constantes::STAMINODES] ? $fleur_male[Constantes::NOMBRE_STAMINODES] . ' staminodes ; ' : ''); ?>
                                                        la couleur principale de la fleur est <?php echo $fleur_male[Constantes::COULEUR_PRINCIPALE]; ?>.
                                                        <?php if (Constantes::PUBESCENTE === $fleur_male[Constantes::PUBESCENCE]) {
                                                            echo "La fleur est ".$fleur_male[Constantes::PUBESCENCE];?>
                                                            <?php if (!empty($fleur_male[Constantes::LOCALISATION_POILS])) {
                                                                echo ' sur '.implode(', ' , $fleur_male[Constantes::LOCALISATION_POILS]).'.'; }
                                                            else { echo '.'; }}?>
                                                        <?php echo $fleur_male[Constantes::AUTRE_CARACTERE];
                                                    } ?>
                                                
                                                </p>
                                            </div>
                                        </div>
                                        <?php
                                        // Si une image est enregistrée on l'affiche
                                        if (isset($fleur_male[Constantes::ILLUSTRATION_FLEUR_MALE][Constantes::PHOTO_FLEUR_MALE])){
                                            affichageImageFiche($fleur_male[Constantes::ILLUSTRATION_FLEUR_MALE][Constantes::PHOTO_FLEUR_MALE]);
                                        }
                                        
                                        ?>
                                    </div>
                                <?php 
                                }
                            }
                        } ?>
                </div> 
                <div>         
                    <?php  
                    if (isset($fleur_femelle) AND !empty($fleur_femelle)){
                        if (isset($systeme) AND !empty($systeme)){
                            if (($systeme === Constantes::MONOIQUE) OR ($systeme === Constantes::DIOIQUE) OR ($systeme === Constantes::GYNOMONOIQUE) OR ($systeme === Constantes::GYNODIOIQUE) OR ($systeme === Constantes::ANDROGYNOMONIQUE) OR ($systeme === Constantes::ANDROGYNODIOIQUE)){ ?>
                    
                                <div id="fleur-femelle" class="display-fiche-container">
                                    <div class="fiche-title-container">
                                        <div class="fiche-title-icon">
                                            <img src=" <?php echo get_template_directory_uri() ?>/images/fleur-femelle.svg"/>
                                            <?php
                                            the_botascopia_module('title', [
                                                'title' => __(Constantes::FL_FEM, 'botascopia'),
                                                'level' => 2,
                                            ]);
                                            ?>
                                        </div>
                                        <div>
                                            <p>

                                                <?php $soudure_corolle = '';
                                                    if (isset($fleur_femelle[Constantes::SOUDURE_COROLLE])){
                                                        
                                                        $soudure_corolle = $fleur_femelle[Constantes::SOUDURE_COROLLE];
                                                        
                                                    }
                                            
                                                if(Constantes::PRESENT !== $fleur_femelle[Constantes::PERIANTHE]) { ?>
                                                    Le périanthe est absent ; 
                                                <?php } else { ?>
                                                    Fleur <?php echo implode(' et ', $fleur_femelle[Constantes::SYMETRIE]); $perianthe=""?>.
                                                    <?php
                                                    $composition = $fleur_femelle[Constantes::DIFFERENCIATION_PERIANTHE];
                                                    $perianthe = displayPerianthe($composition,$fleur_femelle);
                                                    ?>
                                                    Le périanthe est composé de <?php echo $perianthe.". ";
                                                } ?>
                                                
                                                <?php if(!empty($fleur_femelle[Constantes::GYNECEE])): { ?>
                                                    Gynécée composé de <?php $carpelles = $fleur_femelle[Constantes::GYNECEE]; echo getValueOrganesFloraux($carpelles,"carpelle(s)",$fleur_femelle[Constantes::SOUDURE_CARPELLES]);?>; 
                                                    ovaire <?php echo $fleur_femelle[Constantes::OVAIRE]; ?>.
                                                    La couleur principale de la fleur est <?php echo $fleur_femelle[Constantes::COULEUR_PRINCIPALE]; ?>.
                                                    <?php if (Constantes::PUBESCENTE === $fleur_femelle[Constantes::PUBESCENCE]) {
                                                        echo "La fleur est ".$fleur_femelle[Constantes::PUBESCENCE];?>
                                                        <?php if (!empty($fleur_femelle[Constantes::LOCALISATION_POILS])) {
                                                            echo ' sur '.implode(', ' , $fleur_femelle[Constantes::LOCALISATION_POILS]).'.'; }
                                                        else { echo '.'; }}?>
                                                    <?php echo $fleur_femelle[Constantes::AUTRE_CARACTERE];
                                                }?>
                                                <?php endif; ?>
                                            </p>
                                        </div>
                                    </div>
                                    <?php
                                    // Si une image est enregistrée on l'affiche
                                    if (isset($fleur_femelle[Constantes::ILLUSTRATION_FLEUR_FEMELLE][Constantes::PHOTO_FLEUR_FEMELLE]) && $fleur_femelle[Constantes::ILLUSTRATION_FLEUR_FEMELLE][Constantes::PHOTO_FLEUR_FEMELLE]){
                                        affichageImageFiche($fleur_femelle[Constantes::ILLUSTRATION_FLEUR_FEMELLE][Constantes::PHOTO_FLEUR_FEMELLE]);
                                    }
                                    ?>
                                </div>
                            <?php
                            }
                        }
                    } ?>    
                </div>
                <div>
                    <?php
                    if (isset($fleur_bisexuee) AND !empty($fleur_bisexuee)){
                        if (isset($systeme) AND !empty($systeme)){
                            if (($systeme === Constantes::HERMAPHRODITE) OR ($systeme === Constantes::ANDROMONOIQUE) OR ($systeme === Constantes::ANDRODIOIQUE) OR ($systeme === Constantes::GYNODIOIQUE) OR ($systeme === Constantes::GYNOMONOIQUE) OR ($systeme === Constantes::ANDROGYNOMONIQUE) OR ($systeme === Constantes::ANDROGYNODIOIQUE)){ ?> 
                    
                                <div id="fleur-bisexuee" class="display-fiche-container">
                                    <div class="fiche-title-container">
                                        <div class="fiche-title-icon">
                                            <img src=" <?php echo get_template_directory_uri() ?>/images/inflorescence.svg" />
                                            <?php
                                            the_botascopia_module('title', [
                                                'title' => __(Constantes::FL_BI, 'botascopia'),
                                                'level' => 2,
                                            ]);
                                            ?>
                                        </div>
                                        <div>
                                            
                                            <p>
                                                <?php  $perianthe="";?>
                                                <?php if(Constantes::PRESENT !== $fleur_bisexuee[Constantes::PERIANTHE]){ ?>
                                                    Le périanthe est absent ; 
                                                <?php } else {
                                                    $perianthe="";
                                                    echo "Fleur ".implode(' et ', $fleur_bisexuee[Constantes::SYMETRIE]).". ";
                                                    $composition = $fleur_bisexuee[Constantes::COMPOSITION_PERIANTHE];
                                                    $perianthe = displayPerianthe($composition,$fleur_bisexuee);
                                                   
                                                    echo "Le périanthe est composé de $perianthe. ";
                                                }   
                                                ?>
                                               
                                                <?php if(!empty($fleur_bisexuee[Constantes::ANDROCEE])): { ?>
                                                    Androcée composé de <?php $etamines = $fleur_bisexuee[Constantes::ANDROCEE]; echo getValueOrganesFloraux($etamines,"étamine(s)",$fleur_bisexuee[Constantes::SOUDURE_ANDROCEE]);?>
                                                     ; <?php echo (Constantes::ANDROCEE_SOUDEE_COROLLE === $fleur_bisexuee[Constantes::SOUDURE_ANDROCEE_COROLLE] ? $fleur_bisexuee[Constantes::SOUDURE_ANDROCEE_COROLLE] . ', ' : ''). (Constantes::SOUDEES_PERIGONE === $fleur_bisexuee[Constantes::SOUDURE_ANDROCEE_PERIGONE] ? $fleur_bisexuee[Constantes::SOUDURE_ANDROCEE_PERIGONE] . ', ' : ''); ?>
                                                    <?php echo (Constantes::PRESENTS === $fleur_bisexuee[Constantes::STAMINODES] ? $fleur_bisexuee[Constantes::NOMBRE_STAMINODES] . ' staminodes ; ' : '');
                                                } ?>
                                                <?php endif; ?>
                                                <?php if(!empty($fleur_bisexuee[Constantes::GYNECEE])): { ?>
                                                    gynécée composé de <?php $carpelles =  $fleur_bisexuee[Constantes::GYNECEE]; echo getValueOrganesFloraux($carpelles,"carpelle(s)",$fleur_bisexuee[Constantes::SOUDURE_CARPELLES]); ?> ;
                                                    ovaire <?php echo $fleur_bisexuee[Constantes::OVAIRE]; ?>.
                                                <?php } ?>
                                                <?php endif; ?>
                                                La couleur principale de la fleur est le <?php echo $fleur_bisexuee[Constantes::COULEUR_PRINCIPALE]; ?>.
                                                <?php if (Constantes::PUBESCENTE === $fleur_bisexuee[Constantes::PUBESCENCE]) {
                                                    echo "La fleur est ".$fleur_bisexuee[Constantes::PUBESCENCE];?>
                                                    <?php if (!empty($fleur_bisexuee[Constantes::LOCALISATION_POILS])) {
                                                        echo ' sur '.implode(', ' , $fleur_bisexuee[Constantes::LOCALISATION_POILS]).'.'; }
                                                    else { echo '.'; }}?>
                                                <?php echo $fleur_bisexuee[Constantes::AUTRE_CARACTERE];?>
                                            </p>
                                            

                                        </div>
                                    </div>
                                    <?php
                                    // Si une image est enregistrée on l'affiche
                                    if (isset($fleur_bisexuee[Constantes::ILLUSTRATION_FLEUR_BISEXUEE][Constantes::PHOTO_FLEUR_BISEXUEE]) && (!empty(get_field(Constantes::SYS_SEXUEL)) && (get_field(Constantes::SYS_SEXUEL) == Constantes::HERMAPHRODITE ) || (get_field(Constantes::SYS_SEXUEL) == Constantes::ANDROMONOIQUE ) || (get_field(Constantes::SYS_SEXUEL) == Constantes::GYNOMONOIQUE ) || (get_field(Constantes::SYS_SEXUEL) == Constantes::ANDRODIOIQUE ) || (get_field(Constantes::SYS_SEXUEL) == Constantes::GYNODIOIQUE ) || (get_field(Constantes::SYS_SEXUEL) == Constantes::ANDROGYNODIOIQUE ) || (get_field(Constantes::SYS_SEXUEL) == Constantes::ANDROGYNOMONIQUE ))) {
                                        affichageImageFiche($fleur_bisexuee[Constantes::ILLUSTRATION_FLEUR_BISEXUEE][Constantes::PHOTO_FLEUR_BISEXUEE]);
                                    }
                                    ?>
                                </div>
                            <?php 
                            }
                        }
                    } ?>    
                </div>            
                <div>
                    <?php
                        if (!empty(get_field('le_saviez-vous_'))){
                    ?>
                    <div id="le-saviez-vous">
                            <?php
                            the_botascopia_module('title', [
                                'title' => __(Constantes::LE_SAVIEZ_VOUS, 'botascopia'),
                                'level' => 2,
                            ]);
                            ?>
                        
                        <p><?php (!empty(get_field('le_saviez-vous_'))) ? the_field('le_saviez-vous_') : "";?></p>
                        
                    </div>
                    
                    <?php }	?>
                </div>
                <div>
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
                            <?php foreach ($months as $m => $month){ ?>
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
                                <div class="monthly-calendar">
                                    <div class="month"><?php echo substr($month,0,1); ?></div>
                                    <div class="floraison<?php echo $flor_ok ? '-vert' : ''; ?>-icon icon"></div>
                                    <div class="fructification<?php echo $fruct_ok ? '-mauve' : ''; ?>-icon icon"></div>
                                </div>
                            <?php }?>
                        </div>
                    
                    </div>
                    
                    <?php if (!empty(get_field('amplitude_altitudinale')) || !empty(get_field('affinites_ecologiques')) || !empty(get_field('habitat_preferentiel')) || !empty(get_field('systeme_de_reproduction')) || !empty(get_field('pollinisation')) || !empty(get_field('dispersion'))){ ?>
                    <div id="ecologie">
                        
                        <div class="fiche-title-icon">
                            <img src=" <?php echo get_template_directory_uri() ?>/images/ecologie.svg"/>
                            <?php
                            the_botascopia_module('title', [
                                'title' => __(Constantes::ECOLOGIE,'botascopia'),
                                'level' => 2,
                            ]);
                            ?>
                        </div>
                        
                        <?php if (!empty(get_field('amplitude_altitudinale'))) {?> 
                            <p>Altitude : <?php echo get_field('amplitude_altitudinale'); ?> .</p> 
                        <?php } ?>
                        <?php if (!empty(get_field('affinites_ecologiques'))){?>
                            <p>Affinités écologiques : <?php echo get_field('affinites_ecologiques') ? implode(', ', get_field('affinites_ecologiques')) : "";?>.</p>
                        <?php } ?>
                            
                        <?php if (!empty(get_field('habitat_preferentiel'))){?> 
                            <p>Habitat(s) : <?php the_field('habitat_preferentiel'); ?>.</p> 
                        <?php }?>
                            
                        <?php if ((!empty(get_field('systeme_de_reproduction'))) || (!empty(get_field('pollinisation')))) {?> 
                            <p>Plante :<br>
                        <?php }?>
                        
                        <?php if (!empty(get_field('systeme_de_reproduction'))) {?>
                            Système de reproduction <?php the_field('systeme_de_reproduction'); ?>, 
                        <?php } ?>
                        
                        <?php if (!empty(get_field('pollinisation'))) {?>
                            à pollinisation <?php the_field('pollinisation'); ?>, 
                        <?php } ?>
                        
                        <?php if (!empty(get_field('dispersion'))){?>
                            dispersion des graines ou des fruits <?php echo get_field('dispersion') ? implode(', ', get_field('dispersion')) : ""; ?>.</p>
                        <?php }?>
                    
                    <?php } ?>
                    
                </div>
                <div>    
                    <?php $proprietes = get_field('proprietes')?: null;
                    if ($proprietes){
                        ?>
                        <div id="proprietes">
                            <?php
                            the_botascopia_module('title', [
                                'title' => __(Constantes::PROPERTIES,'botascopia'),
                                'level' => 2,
                            ]);
                            ?>
                            <p><?php echo $proprietes; ?></p>
                        </div>
                    <?php } ?>
                </div>
                <div>
                    <?php if (!empty(get_field('cultivee_en_france')) || !empty(get_field('carte_de_metropole')) || !empty(get_field('repartition_mondiale')) || !empty(get_field('indigenat_')) || !empty(get_field('statut_uicn'))){ ?>
                        <div id="aire-repartition" class="display-fiche-container">
                            <div class="fiche-title-container">
                                <div class="fiche-title-icon">
                                    <img src=" <?php echo get_template_directory_uri() ?>/images/location.svg"/>
                                    <?php
                                    the_botascopia_module('title', [
                                        'title' => __(Constantes::AIRE, 'botascopia'),
                                        'level' => 2,
                                    ]);
                                    ?>
                                </div>
                                <?php if (!empty(get_field('cultivee_en_france_'))) { ?>
                                    <?php $cultivee_en_france = get_field('cultivee_en_france_'); ?>
                                    <p>En France métropolitaine, la plante est présente <?php echo $cultivee_en_france; ?><?php $texte = ("à l'état sauvage" === $cultivee_en_france ? ', où elle est ' . implode (', ', get_field('indigenat_')) . '.' : ''); if(substr($texte, -1)!=="."){$texte.=".";} echo $texte;?> Statut UICN : <?php the_field('statut_uicn'); ?>.</p>

                                    <?php if ($cultivee_en_france === "seulement à l'état cultivé") { ?>
                                        <?php if (!empty(get_field('repartition_mondiale'))) { ?>
                                            <?php $repartition_mondiale = get_field('repartition_mondiale'); ?>
                                            <p><?php echo "<a href='$repartition_mondiale'>$repartition_mondiale</a>"; ?></p>
                                        <?php } ?>
                                    <?php } ?>
                                <?php } ?>
                            </div>

                            <?php if (!empty(get_field('carte_de_metropole'))) :?>
                                <div class="image-fiche">
                                    <?php affichageImageFiche(get_field('carte_de_metropole'))?>
                                </div>
                            <?php endif; ?>
                        </div>
                    <?php } ?>
                </div>
                <div>    
                    <?php $espece = get_post_meta(get_the_ID(), 'nom_despece', TRUE); ?>
                    <?php if (isset($espece) AND !(empty($espece))){ ?>
                        <div id="ne-pas-confondre" class="display-fiche-container">
                            <div class="fiche-title-container">
                                <div class="fiche-title-icon">
                                    <img src=" <?php echo get_template_directory_uri() ?>/images/ne-pas-confondre.svg"/>
                                    <?php
                                    the_botascopia_module('title', [
                                        'title' => __(Constantes::CONFUS, 'botascopia'),
                                        'level' => 2,
                                    ]);
                                    ?>
                                </div>
                                <div>
                                    <?php
                                        
                                        
                                        the_botascopia_module('title', [
                                            'title' => "<i>$espece</i>",
                                            'level' => 3,
                                        ]);
                                    ?>
                                    
                                    <p><?php the_field(Constantes::DESCRIPTION_CHP); ?></p>
                                </div>
                            </div>

                            <?php $photo = get_field('illustration_de_la_plante_avec_risque_de_confusion_photo') ? : null;
                            if (isset($photo)) {
                                affichageImageFiche($photo);
                            }
                            ?>
                        </div>
                    <?php } ?>
                </div>
                <div>
                    <?php $anecdote = get_field('complement_danecdote')?: null; ?>
                    <?php if ($anecdote){ ?>
                    <div id="complement-anecdote">
                        <?php
                        the_botascopia_module('title', [
                            'title' => __(Constantes::ANECDOTE, 'botascopia'),
                            'level' => 2,
                        ]);
                        ?>
                        <p><?php the_field('complement_danecdote'); ?></p>
                    </div>
                    <?php } ?>
                </div>
                <div>
                    <div id="agroecologie" class="display-fiche-container">
                        <div class="fiche-title-container">
                            <div class="fiche-title-icon">
                                <img src=" <?php echo get_template_directory_uri() ?>/images/ecologie.svg" />
                                <?php
                                the_botascopia_module('title', [
                                    'title' => __(Constantes::AGRO, 'botascopia'),
                                    'level' => 2,
                                ]);
                                ?>
                            </div>
                        </div>
                            
                                <div class="agro_ecologie">

                                    <?php
                                        $preferences="La plante préfère ces expositions : <br>"; 
                                        $champ=get_field('preferences_physico-chimiques_lumiere');
                                        if (!empty($champ)){
                                    ?>
                                        <p><?php $preferences_expo = implode(', ',$champ); echo $preferences.$preferences_expo;?></p>
                                    <?php } ?>  
                                    
                                    <?php
                                        $preferences="Elle supporte ces taux d'humidité : <br>"; 
                                        $champ=get_field('preferences_physico-chimiques_humidite_atmospherique');
                                        if (!empty($champ)){
                                    ?>
                                        <p><?php $preferences_humidite = implode(', ',$champ); echo $preferences.$preferences_humidite;?></p>
                                    <?php } ?>

                                    <?php
                                        $preferences="Sa continentalité est : <br>"; 
                                        $champ=get_field('preferences_physico-chimiques_continentalite');
                                        if (!empty($champ)){
                                    ?>
                                        <p><?php $preferences_continentalite = implode(', ',$champ); echo $preferences.$preferences_continentalite;?></p>
                                    <?php } ?>

                                    <?php
                                        $preferences="Elle est adaptée aux sols : <br>"; 
                                        $champ_ph=get_field('preferences_physico-chimiques_reaction_ph');
                                        if (!empty($champ_ph)){
                                    ?>
                                        <?php $preferences_ph = implode(', ',$champ_ph); $preferences.= $preferences_ph; ?>
                                    <?php } ?>
                                    
                                    <?php
                                        $champ_hum=get_field('preferences_physico-chimiques_humidite_du_sol');
                                        if (!empty($champ_hum)){
                                    ?>
                                        <?php $preferences_humidite_sol = "<br>dont l'humidité est : <br>".implode(', ',$champ_hum); $preferences.= $preferences_humidite_sol; ?>
                                    <?php } ?>
                                    
                                    <?php
                                        $champ_texture=get_field('preferences_physico-chimiques_texture_du_sol');
                                        if (!empty($champ_texture)){
                                    ?>
                                        <?php $preferences_texture_sol = "<br>dont la texture est composée de : <br>".implode(', ',$champ_texture); $preferences.= $preferences_texture_sol; ?>
                                    <?php } ?>
                                    
                                    <?php
                                        $champ_azote=get_field('preferences_physico-chimiques_richesse_en_azote_n');
                                        if (!empty($champ_azote)){
                                    ?>
                                        <?php $preferences_azote_sol = "<br>dont la richesse en azote est : <br>".implode(', ',$champ_azote); $preferences.= $preferences_azote_sol; ?>
                                    <?php } ?>
                                
                                    <?php
                                        $champ_sal=get_field('preferences_physico-chimiques_salinite');
                                        if (!empty($champ_sal)){
                                    ?>
                                        <?php $preferences_salinite_sol = "<br>dont la salinité est : <br>".implode(', ',$champ_sal); $preferences.= $preferences_salinite_sol; ?>
                                    <?php } ?>

                                    <?php if ($champ_ph OR $champ_hum OR $champ_texture OR $champ_azote OR $champ_sal){ ?>
                                        <p><?php echo $preferences; ?></p>
                                    <?php } ?>

                                    <?php
                                        $preferences="Elle tolère la température minimale de "; 
                                        $champ=get_field('preferences_physico-chimiques_temperature_minimale_supportee');
                                        
                                        if (!empty($champ)){
                                    ?>
                                        <p><?php $preferences_temperature = $champ; echo $preferences.$preferences_temperature ." °C.";?></p>
                                    <?php } ?>

                                    <?php
                                        $champ=get_field('preferences_physico-chimiques_tolerance_au_gel');
                                        if ($champ){
                                    ?>
                                        <p><?php echo "Elle supporte le gel.";?></p>
                                    <?php }else{ ?>
                                        <p><?php echo "Elle ne supporte pas le gel.";?></p>
                                    <?php }?>

                                    <?php if (get_field('interaction_avec_le_vivant_des_symbioses_avec_des_organismes_fixateurs_dazote')){ ?>
                                        <p>Cette plante peut développer des symbioses avec des bactéries fixatrices d’azote.</p>
                                    <?php } ?>
                                    <?php if (get_field('interaction_avec_le_vivant_plantes_connues_pour_attirer_des_auxiliaires_de_culture')){ ?>
                                        <?php if (get_field('interaction_avec_le_vivant_type_dauxiliaires') == 'pollinisateurs' || get_field('interaction_avec_le_vivant_type_dauxiliaires') == 'parasitoïdes' && !empty(get_field('interaction_avec_le_vivant_quelles_sont_les_structures_connues_pour_attirer_les_auxiliaires_de_culture_'))){ ?>
                                            <p>Cette plante attire des <?php echo implode(get_field('interaction_avec_le_vivant_type_dauxiliaires'));?> grâce à <?php echo get_field('interaction_avec_le_vivant_quelles_sont_les_structures_connues_pour_attirer_les_auxiliaires_de_culture_');?>.</p>
                                        <?php } ?>
                                        <?php if (get_field('interaction_avec_le_vivant_type_dauxiliaires') == 'prédateurs' && !empty(get_field('interaction_avec_le_vivant_quelles_sont_les_structures_connues_pour_attirer_les_auxiliaires_de_culture_')) && !empty(get_field('interaction_avec_le_vivant_les_predateurs'))){ ?>
                                            <p>Cette plante attire des <?php echo implode(get_field('interaction_avec_le_vivant_les_predateurs'));?>, prédateurs ayant un rôle d'auxiliaires de culture grâce à <?php echo implode(', ', get_field('interaction_avec_le_vivant_quelles_sont_les_structures_connues_pour_attirer_les_auxiliaires_de_culture_'));?>.</p>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if (get_field('interaction_avec_le_vivant_plantes_connues_pour_repousser_les_ravageurs')){ ?>
                                        <?php if (!empty(get_field('interaction_avec_le_vivant_plantes_connues_pour_repousser_les_ravageurs'))){
                                            $les_ravageurs = implode(', ', get_field('interaction_avec_le_vivant_les_ravageurs'));?>
                                            <p>Cette plante repousse des <?php echo $les_ravageurs;?>, ravageurs de culture.</p>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if (get_field('interaction_avec_le_vivant_plantes_connues_pour_attirer_les_ravageurs')){ ?>
                                        <?php if (!empty(get_field('interaction_avec_le_vivant_plantes_connues_pour_attirer_les_ravageurs'))):
                                            $les_ravageurs = implode('-', get_field('interaction_avec_le_vivant_les_ravageurs'));?>
                                            <p>Cette plante attire des <?php echo $les_ravageurs;?>, ravageurs de culture.<p>
                                        <?php endif; ?>
                                    <?php } ?>
                                    <?php if (!empty(get_field('interaction_avec_le_vivant_communautes_vegetales_dans_lesquelles_la_plante_est_observee'))){ ?>
                                        <p>Elle pousse <?php echo implode(', ',get_field('interaction_avec_le_vivant_communautes_vegetales_dans_lesquelles_la_plante_est_observee'));?>.</p>
                                    <?php } ?>
                                    <?php if (!empty(get_field('interaction_avec_le_vivant_plante_presentant_une_multiplication_vegetative')) && get_field('interaction_avec_le_vivant_plante_presentant_une_multiplication_vegetative') == 'oui'){ ?>
                                        <?php if (!empty(get_field('interaction_avec_le_vivant_structures_liees_a_la_multiplication_vegetative'))){ ?>
                                            <p>Cette plante présente une multiplication végétative grâce à <?php echo implode(', ', get_field('interaction_avec_le_vivant_structures_liees_a_la_multiplication_vegetative'));?>.</p>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php if (!empty(get_field('interaction_avec_le_vivant_la_plante_est-elle_connue_pour_emettre_des_substances_allelopathiques_')) && get_field('interaction_avec_le_vivant_la_plante_est-elle_connue_pour_emettre_des_substances_allelopathiques_') == 'oui'){ ?>
                                        <p>Elle est connue pour émetttre des substances allélopathiques.</p>
                                    <?php } ?>
                                    <?php if (!empty(get_field('interaction_avec_le_vivant_cette_plante_est-elle_utilisee_comme_plante_compagne_'))){ ?>
                                        <p>Cette plante est utilisée comme plante compagne des <?php echo implode(", ",get_field('interaction_avec_le_vivant_cette_plante_est-elle_utilisee_comme_plante_compagne_'));?>.</p>
                                    <?php } ?>
                                    <?php if (!empty(get_field('interaction_avec_le_vivant_toxicite_pour_les_animaux_non_humains'))){
                                        $animaux_affectes = implode(', ', get_field('interaction_avec_le_vivant_toxicite_pour_les_animaux_non_humains'));?>
                                        <p>Elle est toxique pour <?php echo $animaux_affectes;?>
                                        <?php if (get_field('interaction_avec_le_vivant_toxicite_pour_lhumain') == 'oui'){ ?>
                                            et l'humain
                                        <?php } ?>
                                        <?php if (!empty(get_field('interaction_avec_le_vivant_la_plante_est_toxique_au_niveau_'))){ ?>
                                            au niveau <?php echo implode(", ", get_field('interaction_avec_le_vivant_la_plante_est_toxique_au_niveau_'));?>.</p>
                                        <?php } ?>
                                    <?php } ?>
                                    <?php
                                        $champ=get_field('interaction_avec_le_vivant_plante_servant_explicitement_dabri_a_un_organisme');
                                        if ($champ):
                                    ?>
                                        <p><?php echo $champ;?></p>
                                    <?php endif; ?>
                        
                                    <?php if (!empty(get_field('adaptations_aux_pratiques_de_culture_cette_espece_est_observee')) && get_field('adaptations_aux_pratiques_de_culture_cette_espece_est_observee') != 'rarement ou jamais dans les cultures et leurs abords'){ ?>
                                        <?php if (!empty(get_field('adaptations_aux_pratiques_de_culture_cette_espece_est_observee_preferentiellement'))){ ?>
                                            <?php if (!empty(get_field('adaptations_aux_pratiques_de_culture_type_de_culture_preferentiel'))){ ?>
                                                <p>Cette espèce est observée <?php echo(get_field('adaptations_aux_pratiques_de_culture_cette_espece_est_observee').", ");?> 
                                                <?php if (!empty(get_field('adaptations_aux_pratiques_de_culture_precision_-_cette_espece_est_observee_preferentiellement'))){ 
                                                    echo implode(", ",get_field('adaptations_aux_pratiques_de_culture_precision_-_cette_espece_est_observee_preferentiellement')).", "; 
                                                }?>
                                                <?php echo "dans ".implode(', ', get_field('adaptations_aux_pratiques_de_culture_cette_espece_est_observee_preferentiellement'));?> 
                                                <?php echo get_field('adaptations_aux_pratiques_de_culture_type_de_culture_preferentiel');?>.
                                                </p>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>

                                    <?php
                                        $levee="Sa levée a lieu ces saisons-là : <br>"; 
                                        $champ=get_field('adaptations_aux_pratiques_de_culture_periode_de_levee_');
                                        if (!empty($champ)){
                                    ?>
                                        <p><?php $mois = implode(', ',$champ); echo $levee.$mois;?>.</p>
                                    <?php } ?>  

                                    <?php if (!empty(get_field('adaptations_aux_pratiques_de_culture_cette_plante_est_favorisee_dans_les_systemes_de_culture')) && get_field('adaptations_aux_pratiques_de_culture_cette_plante_est_favorisee_dans_les_systemes_de_culture') != 'sans travail du sol'){?>
                                        <?php if (!empty(get_field('adaptations_aux_pratiques_de_culture_profondeur_du_travail_du_sol'))){ ?>
                                            <p>Cette plante est favorisée dans les systèmes de culture <?php echo get_field('adaptations_aux_pratiques_de_culture_cette_plante_est_favorisee_dans_les_systemes_de_culture');?> <?php echo get_field('adaptations_aux_pratiques_de_culture_profondeur_du_travail_du_sol');?>.</p>
                                        <?php } ?>
                                    <?php } ?>

                                    <?php if (!empty(get_field('adaptations_aux_pratiques_de_culture_au_bout_de_combien_de_temps_la_moitie_du_stock_semencier_a_perdu_son_pouvoir_germinatif__'))){ ?>
                                        <p>La persistance de son stock semencier est <?php echo get_field('adaptations_aux_pratiques_de_culture_au_bout_de_combien_de_temps_la_moitie_du_stock_semencier_a_perdu_son_pouvoir_germinatif__');?>.</p>
                                    <?php } ?>

                                    <?php if (!empty(get_field('adaptations_aux_pratiques_de_culture_est-ce_quune_resistance_aux_herbicides_a_ete_identifiee_chez_cette_espece_')) && get_field('adaptations_aux_pratiques_de_culture_est-ce_quune_resistance_aux_herbicides_a_ete_identifiee_chez_cette_espece_') == 'oui'){ ?>
                                        <?php if (!empty(get_field('adaptations_aux_pratiques_de_culture_a_quelles_molecules_'))){ ?>
                                            <p>La plante est résistante à ces molécules : <?php echo get_field('adaptations_aux_pratiques_de_culture_a_quelles_molecules_');?>.</p>
                                        <?php } ?>
                                    <?php } ?>
                                    
                                    <?php
                                        $intro="Cette plante est connue pour être ou avoir été cultivée pour les usages suivants : <br>"; 
                                        $champ=get_field('valeurs_ecologiques_historiques_et_locales_cette_plante_est-elle_connue_pour_avoir_ete_ou_etre_actuellement_cultivee_');
                                        
                                        if (!empty($champ)){
                                    ?>
                                        <p><?php $usages = implode(", ",get_field('valeurs_ecologiques_historiques_et_locales_cette_plante_a_t_elle_ete_ou_est_elle_cultivee_pour_les_usages_suivants')); echo $intro.$usages;?>.</p>
                                        <?php
                                            $sources = get_field('valeurs_ecologiques_historiques_et_locales_reference_pour_les_informations_sur_les_usages');
                                            if(!empty($sources)){
                                        ?>
                                            <p><?php echo "Sources : $sources"?></p>
                                        <?php } ?>
                                    <?php } ?>

                                    <?php
                                        $intro="Cette plante est connue pour ces propriétés, autres que la toxicité : <br>"; 
                                        $champ=get_field('valeurs_ecologiques_historiques_et_locales_plante_connue_pour_des_proprietes_autres_que_la_toxicite_cf_categorie_interaction_avec_le_vivant');
                                        if (!empty($champ)){
                                    ?>
                                        <p><?php $proprietes = implode(', ',$champ); echo $intro.$proprietes;?>.</p>
                                    <?php } ?>

                                    <?php
                                        $champ=get_field('valeurs_ecologiques_historiques_et_locales_particularites_de_cette_espece_en_lien_avec_le_terroir_ou_le_territoire');
                                        if ($champ){
                                    ?>
                                        <p><?php echo $champ;?></p>
                                    <?php } ?>

                                    <?php
                                        $champ=get_field('valeurs_ecologiques_historiques_et_locales_statut_de_protection');
                                        if ($champ){
                                    ?>
                                        <p>La plante <?php echo $champ;?>.</p>
                                        <?php if($champ==='a un statut de protection au niveau national et/ou régional'){ ?>
                                            <?php $statut_nat = get_field('valeurs_ecologiques_historiques_et_locales_quel_est_le_statut_de_protection_france_metropolitaine'); 
                                            if (isset($statut_nat)){ ?>
                                                <p><?php echo "La plante est protégée au niveau national avec ce statut : $statut_nat"; ?></p>
                                            <?php } ?>
                                            <?php $statut_reg = get_field("valeurs_ecologiques_historiques_et_locales_statut_de_protection_a_l_echelle_locale_regions_concernees");
                                                if ($statut_reg){                       
                                            ?>
                                                    <p>La plante est protégée dans ces régions : <?php $regions = implode(", ",$statut_reg); echo $regions; ?>.</p>
                                                    <?php $auv_st = get_field('valeurs_ecologiques_historiques_et_locales_quel_est_le_statut_de_protection__auvergne_rhone_alpes');
                                                        if ($auv_st){
                                                    ?>
                                                        <p>Son statut de protection en Auvergne - Rhône Alpes est <?php echo $auv_st ?>.</p>
                                                        <?php $precisions = get_field('valeurs_ecologiques_historiques_et_locales_precisions_sur_la_zone_de_la_region_concernee_departement_environnement_auvergne_rhone_alpes');
                                                            if (!empty($precisions)){?>
                                                                <p>Précisions : <?php echo $precisions; ?>.</p>
                                                            <?php } ?>
                                                    <?php } ?>

                                                    <?php $bour_st = get_field('valeurs_ecologiques_historiques_et_locales_quel_est_le_statut_de_protection__bourgogne_franche_comte');
                                                        if ($bour_st){
                                                    ?>
                                                        <p>Son statut de protection en Bourgogne - Franche-Comté est <?php echo $bour_st ?>.</p>
                                                        <?php $precisions = get_field('valeurs_ecologiques_historiques_et_locales_precisions_sur_la_zone_de_la_region_concernee_departement_environnement_bourgogne_franhce_comte');
                                                            if (!empty($precisions)){?>
                                                                <p>Précisions : <?php echo $precisions; ?>.</p>
                                                            <?php } ?>
                                                    <?php } ?>

                                                    <?php $bret_st = get_field('valeurs_ecologiques_historiques_et_locales_quel_est_le_statut_de_protection__bretagne');
                                                        if ($bret_st){
                                                    ?>
                                                        <p>Son statut de protection en Bretagne est <?php echo $bret_st ?>.</p>
                                                        <?php $precisions = get_field('valeurs_ecologiques_historiques_et_locales_precisions_sur_la_zone_de_la_region_concernee_departement_environnement_bretagne');
                                                            if (!empty($precisions)){?>
                                                                <p>Précisions : <?php echo $precisions; ?>.</p>
                                                            <?php } ?>
                                                    <?php } ?>

                                                    <?php $centre_st = get_field('valeurs_ecologiques_historiques_et_locales_quel_est_le_statut_de_protection__centre_val_de_loire');
                                                        if ($centre_st){
                                                    ?>
                                                        <p>Son statut de protection en Centre - Val de Loire est <?php echo $centre_st ?>.</p>
                                                        <?php $precisions = get_field('valeurs_ecologiques_historiques_et_locales_precisions_sur_la_zone_de_la_region_concernee_departement_environnement_centre_val_de_loire');
                                                            if (!empty($precisions)){?>
                                                                <p>Précisions : <?php echo $precisions; ?>.</p>
                                                            <?php } ?>
                                                    <?php } ?>

                                                    <?php $corse_st = get_field('valeurs_ecologiques_historiques_et_locales_quel_est_le_statut_de_protection__corse');
                                                        if ($corse_st){
                                                    ?>
                                                        <p>Son statut de protection en Corse est <?php echo $corse_st ?>.</p>
                                                        <?php $precisions = get_field('valeurs_ecologiques_historiques_et_locales_precisions_sur_la_zone_de_la_region_concernee_departement_environnement_corse');
                                                            if (!empty($precisions)){?>
                                                                <p>Précisions : <?php echo $precisions; ?>.</p>
                                                            <?php } ?>
                                                    <?php } ?>

                                                    <?php $est_st = get_field('valeurs_ecologiques_historiques_et_locales_quel_est_le_statut_de_protection__grand_est');
                                                        if ($est_st){
                                                    ?>
                                                        <p>Son statut de protection dans le Grand Est est <?php echo $est_st ?>.</p>
                                                        <?php $precisions = get_field('valeurs_ecologiques_historiques_et_locales_precisions_sur_la_zone_de_la_region_concernee_departement_environnement_grand_est');
                                                            if (!empty($precisions)){?>
                                                                <p>Précisions : <?php echo $precisions; ?>.</p>
                                                            <?php } ?>
                                                    <?php } ?>

                                                    <?php $guade_st = get_field('valeurs_ecologiques_historiques_et_locales_quel_est_le_statut_de_protection__guadeloupe');
                                                        if ($guade_st){
                                                    ?>
                                                        <p>Son statut de protection en Guadeloupe est <?php echo $guade_st ?>.</p>
                                                        <?php $precisions = get_field('valeurs_ecologiques_historiques_et_locales_precisions_sur_la_zone_de_la_region_concernee_departement_environnement_guadeloupe');
                                                            if (!empty($precisions)){?>
                                                                <p>Précisions : <?php echo $precisions; ?>.</p>
                                                            <?php } ?>
                                                    <?php } ?>

                                                    <?php $hdf_st = get_field('valeurs_ecologiques_historiques_et_locales_quel_est_le_statut_de_protection__hauts_de_france');
                                                        if ($hdf_st){
                                                    ?>
                                                        <p>Son statut de protection dans les Hauts de France est <?php echo $hdf_st ?>.</p>
                                                        <?php $precisions = get_field('valeurs_ecologiques_historiques_et_locales_precisions_sur_la_zone_de_la_region_concernee_departement_environnement_hauts_de_france');
                                                            if (!empty($precisions)){?>
                                                                <p>Précisions : <?php echo $precisions; ?>.</p>
                                                            <?php } ?>
                                                    <?php } ?>

                                                    <?php $idf_st = get_field('valeurs_ecologiques_historiques_et_locales_quel_est_le_statut_de_protection__ile_de_france');
                                                        if ($idf_st){
                                                    ?>
                                                        <p>Son statut de protection en Île de France est <?php echo $idf_st ?>.</p>
                                                        <?php $precisions = get_field('valeurs_ecologiques_historiques_et_locales_precisions_sur_la_zone_de_la_region_concernee_departement_environnement_ile_de_france');
                                                            if (!empty($precisions)){?>
                                                                <p>Précisions : <?php echo $precisions; ?>.</p>
                                                            <?php } ?>
                                                    <?php } ?>

                                                    <?php $reu_st = get_field('valeurs_ecologiques_historiques_et_locales_quel_est_le_statut_de_protection__la_reunion');
                                                        if ($reu_st){
                                                    ?>
                                                        <p>Son statut de protection à La Réunion est <?php echo $reu_st ?>.</p>
                                                        <?php $precisions = get_field('valeurs_ecologiques_historiques_et_locales_precisions_sur_la_zone_de_la_region_concernee_departement_environnement_la_reunion');
                                                            if (!empty($precisions)){?>
                                                                <p>Précisions : <?php echo $precisions; ?>.</p>
                                                            <?php } ?>
                                                    <?php } ?>

                                                    <?php $mar_st = get_field('valeurs_ecologiques_historiques_et_locales_quel_est_le_statut_de_protection__martinique');
                                                        if ($mar_st){
                                                    ?>
                                                        <p>Son statut de protection en Martinique est <?php echo $mar_st ?>.</p>
                                                        <?php $precisions = get_field('valeurs_ecologiques_historiques_et_locales_precisions_sur_la_zone_de_la_region_concernee_departement_environnement_martinique');
                                                            if (!empty($precisions)){?>
                                                                <p>Précisions : <?php echo $precisions; ?>.</p>
                                                            <?php } ?>
                                                    <?php } ?>

                                                    <?php $may_st = get_field('valeurs_ecologiques_historiques_et_locales_quel_est_le_statut_de_protection__mayotte');
                                                        if ($may_st){
                                                    ?>
                                                        <p>Son statut de protection à Mayotte est <?php echo $may_st ?>.</p>
                                                        <?php $precisions = get_field('valeurs_ecologiques_historiques_et_locales_precisions_sur_la_zone_de_la_region_concernee_departement_environnement_mayotte');
                                                            if (!empty($precisions)){?>
                                                                <p>Précisions : <?php echo $precisions; ?>.</p>
                                                            <?php } ?>
                                                    <?php } ?>

                                                    <?php $norm_st = get_field('valeurs_ecologiques_historiques_et_locales_quel_est_le_statut_de_protection__normandie');
                                                        if ($norm_st){
                                                    ?>
                                                        <p>Son statut de protection en Normandie est <?php echo $norm_st ?>.</p>
                                                        <?php $precisions = get_field('valeurs_ecologiques_historiques_et_locales_precisions_sur_la_zone_de_la_region_concernee_departement_environnement_normandie');
                                                            if (!empty($precisions)){?>
                                                                <p>Précisions : <?php echo $precisions; ?>.</p>
                                                            <?php } ?>
                                                    <?php } ?>

                                                    <?php $aqui_st = get_field('valeurs_ecologiques_historiques_et_locales_quel_est_le_statut_de_protection__nouvelle_aquitaine');
                                                        if ($aqui_st){
                                                    ?>
                                                        <p>Son statut de protection en Nouvelle Aquitaine est <?php echo $aqui_st ?>.</p>
                                                        <?php $precisions = get_field('valeurs_ecologiques_historiques_et_locales_precisions_sur_la_zone_de_la_region_concernee_departement_environnement_nouvelle_aquitaine');
                                                            if (!empty($precisions)){?>
                                                                <p>Précisions : <?php echo $precisions; ?>.</p>
                                                            <?php } ?>
                                                    <?php } ?>

                                                    <?php $occ_st = get_field('valeurs_ecologiques_historiques_et_locales_quel_est_le_statut_de_protection__occitanie');
                                                        if ($occ_st){
                                                    ?>
                                                        <p>Son statut de protection en Occitanie est <?php echo $occ_st ?>.</p>
                                                        <?php $precisions = get_field('valeurs_ecologiques_historiques_et_locales_precisions_sur_la_zone_de_la_region_concernee_departement_environnement_occitanie');
                                                            if (!empty($precisions)){?>
                                                                <p>Précisions : <?php echo $precisions; ?>.</p>
                                                            <?php } ?>
                                                    <?php } ?>

                                                    <?php $loire_st = get_field('valeurs_ecologiques_historiques_et_locales_quel_est_le_statut_de_protection__pays_de_la_loire');
                                                        if ($loire_st){
                                                    ?>
                                                        <p>Son statut de protection dans les Pays de la Loire est <?php echo $loire_st ?>.</p>
                                                        <?php $precisions = get_field('valeurs_ecologiques_historiques_et_locales_precisions_sur_la_zone_de_la_region_concernee_departement_environnement_pays_de_la_loire');
                                                            if (!empty($precisions)){?>
                                                                <p>Précisions : <?php echo $precisions; ?>.</p>
                                                            <?php } ?>
                                                    <?php } ?>

                                                    <?php $paca_st = get_field('valeurs_ecologiques_historiques_et_locales_quel_est_le_statut_de_protection__provence_alpes_cote_d_azur');
                                                        if ($paca_st){
                                                    ?>
                                                        <p>Son statut de protection en Provence Alpes Côte d'Azur est <?php echo $paca_st ?>.</p>
                                                        <?php $precisions = get_field('valeurs_ecologiques_historiques_et_locales_precisions_sur_la_zone_de_la_region_concernee_departement_environnement_provence_alpes_cote_d_azur');
                                                            if (!empty($precisions)){?>
                                                                <p>Précisions : <?php echo $precisions; ?>.</p>
                                                            <?php } ?>
                                                    <?php } ?>
                                            <?php } ?>

                                        <?php } ?> 
                                    <?php } ?>
                                    <?php
                                        $champ=get_field('sources_generales');
                                        if ($champ){
                                    ?>
                                        <p>Sources générales : <?php echo $champ;?></p>
                                    <?php } ?>
                                </div>
                           
                       
                    </div>
                </div>
                <div>
                    <?php if (!empty(get_field('reference_1'))){ ?>
                        <div id="references">
                            <?php
                            the_botascopia_module('title', [
                                'title' => __(Constantes::REFERENCES, 'botascopia'),
                                'level' => 2,
                            ]);
                            ?>
                            <ul>
                                <li><?php the_field('reference_1'); ?></li>
                                <?php if (!empty(get_field('reference_2'))){?>
                                    <li><?php the_field('reference_2'); ?></li>
                                <?php } ?>
                                
                                <?php if (!empty(get_field('reference_3'))){ ?>
                                    <li><?php the_field('reference_3'); ?></li>
                                <?php } ?>
                                
                                <?php if (!empty(get_field('reference_4'))){ ?>
                                    <li><?php the_field('reference_4'); ?></li>
                                <?php } ?>
                                
                                <?php if (!empty(get_field('reference_5'))){ ?>
                                    <li><?php the_field('reference_5'); ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>
                </div>
                <div>
                    <div id="voir_plus">
                    
                        <div>
                            <div id="titre">    
                                <?php
                                    the_botascopia_module('title', [
                                        'title' => __(Constantes::VOIR, 'botascopia'),
                                        'level' => 2,
                                    ]);
                                ?>
                            </div>
                            <div id="grille">
                                <?php    
                                    $search_term="bdtfx-nn-";
                                    
                                    $query = new WP_Query( array ( 'orderby' => 'rand', 'posts_per_page' => '6','wpse18703_title' => $search_term, 'post_status' =>'publish' ) );
                                    
                                    if (have_posts()) : while ( $query->have_posts() ) : $query->the_post();
                                        $name = getFilteredTitle(get_post_meta(get_the_ID(), 'nom_scientifique', true));
                                        $species = get_post_meta(get_the_ID(), 'famille', true);
                                        $id = get_the_ID();
                                        $ficheTitle = get_the_title();
                                        $status = get_post_status();
                                        $image = getFicheImage($id);
                                        
                                        $fiche_author_id = get_post_field('post_author', $id);
                                        $fiche_author_info = get_userdata($fiche_author_id);
                                        $fiche_author_roles = $fiche_author_info->roles[0];
                                        
                                        if (is_user_logged_in() && get_user_meta(wp_get_current_user()->ID, 'favorite_fiche') && ($key = array_search($id, $ficheFavorites[0]))!==false):
                                            $icone = ['icon' => 'star', 'color' => 'blanc'];
                                        else:
                                            $icone = ['icon' => 'star-outline', 'color' => 'blanc'];
                                        endif;
                                        
                                        
                                        $href = get_the_permalink();
                                        
                                        the_botascopia_module('card-fiche', [
                                            'href' => $href,
                                            'image' => $image,
                                            'name' => $name,
                                            'species' => $species,
                                            'icon' => $icone,
                                            'id' => 'fiche-'.$id,
                                            'extra_attributes' => ['data-user-id' => $current_user_id, 'data-fiche-id' => $id, 'data-fiche-name' => $name, 'data-fiche-url' => get_permalink(), 'data-fiche-title' => $ficheTitle]
                                        ]);
                                        
                                ?>

                                <?php endwhile; ?>

                                <?php else : ?>

                                    <p>Aucune autre fiche trouvée.</p>

                                <?php endif; ?> 
                            </div>
                        </div>	
                        
                    </div>
                </div>
            
                <div class="formulaire-boutons-bas">
                    <?php
                    $securise = (isset($_SERVER['HTTPS'])) ? "https://" : "http://";
                    if (is_user_logged_in()){
                        
                        if ($current_user_role == 'administrator' ||
                            ($current_user_role == 'contributor' && $status == 'draft' &&
                                $current_user_id == $post_id) ||
                            ($current_user_role == 'editor' && $status == 'pending')){
                            the_botascopia_module('button',[
                                'tag' => 'a',
                                'title' => Constantes::BACK_TO_FORM,
                                'text' => Constantes::BACK_TO_FORM,
                                'modifiers' => 'purple-button',
                                'extra_attributes' => ['onclick' => "window.location.href = '".$securise.$_SERVER['HTTP_HOST']."/formulaire/?p=".$title."'"]
                            ]);
                        }
                    }
                    ?>
                </div>
            </div>
        </div>    
	</main><!-- .site-main -->
</div><!-- .content-area -->

<?php
get_footer();
?>

