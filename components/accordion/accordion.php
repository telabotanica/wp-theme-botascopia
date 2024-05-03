<?php function botascopia_component_accordion($data)
{
	
	$defaults = [
		'title_level' => get_sub_field('title_level'),
		'items' => get_sub_field('items'),
		'icon' => [],
		'modifiers' => []
	];
	
	$data = botascopia_styleguide_data($defaults, $data);
	$data->modifiers = botascopia_styleguide_modifiers_array(['component', 'component-accordion', 'js-accordion'], $data->modifiers);
	
	printf(
		'<div class="%s" data-accordion-prefix-classes="component-accordion">',
		implode(' ', $data->modifiers)
	);
	
	$titre = $data->items[0]['title'];
	$post_id = $data->items[0]['content']['post_id'];
	echo "<pre>";
	//print_r(get_post_meta($post_id));
	echo "</pre>";
	$field_title = $data->items[0]['content']['field_title'];
	$field_group_key = $data->items[0]['content']['field_key'];
	$field_group_id = $data->items[0]['content']['field_groups'][0];
	
	switch ($titre){
		case Constantes::VULG:
		case Constantes::DESCRIPTION:
			$image = 'description';
			break;
		case Constantes::PERIOD:
			$image = 'periode';
			break;
		case Constantes::ECOLOGIE:
			$image = 'ecologie';
			break;
		case Constantes::PROPERTIES:
			$image = 'feuilles';
			break;
		case Constantes::AIRE:
			$image = 'location';
			break;
		case Constantes::CONFUS:
			$image = 'ne-pas-confondre';
			break;
		default:
			$image = '';
	}

	if ($image){
		echo '<img class="accordion-icon" src="'.get_template_directory_uri().'/images/'.$image.'.svg" />' ;
	}
	
	
// récupérer tous les champs du groupe de champs ACF
	$group_fields = acf_get_fields($field_group_key);
	
// vérifier si tous les champs du groupe sont remplis
	
	$champs_requis = false;
	$champs_incomplets = false;
	$bool = getBoolean($group_fields,$champs_requis,1,$post_id);
	$champs_requis = $bool ? $bool : false;
	

	if (!$champs_requis){
		$bool = getBoolean($group_fields,$champs_incomplets,0,$post_id);
		$champs_incomplets = $bool ? $bool : false;
	}
	
	if ($champs_requis) {
		$button = 'red-button';
		$text = 'Requis';
	}else{
		if ($champs_incomplets){
			$button = 'purple-button';
			$text = 'Incomplet';
		}else{
			$button = 'green-button';
			$text = 'Complet';
		}
	}

	the_botascopia_module('button', [
		'tag' => 'button',
		'title' => $text,
		'text' => $text,
		'modifiers' => $button.' formulaire-field-status',
		'extra_attributes' => ['id' => 'bouton-status-'.$data->modifiers['id']]
	]);
	
	if ($data->items):
		
		foreach ($data->items as $item) :
			echo '<div class="js-accordion__panel component-accordion__panel">';
			
			$item = (object)$item;
			
			printf(
				'<h%s class="js-accordion__header component-accordion__header">%s</h%s>',
				$data->title_level,
				$item->title,
				$data->title_level
			);
			
			acf_form($item->content);
			
			echo '</div>';
		
		endforeach;
	
	endif;
	
	echo '</div>';
}

function getBoolean($group_fields,$bool,$nb,$post_id){
	foreach ($group_fields as $field) {
		
		if ($field['required'] == $nb) {
			$name=$field['name'];
			$fd =get_field($field['name'],$post_id);
			
			$tab_names=["tige","feuilles_aeriennes","feuilles_immergees","feuilles_des_rameaux_fleuris","feuilles_des_rameaux_steriles","inflorescence","fleur_bisexuee","fleur_male","fleur_femmelle","preferences_physico-chimiques","interaction_avec_le_vivant","adaptations_aux_pratiques_de_culture","valeurs_ecologiques_historiques_et_locales"];
			$feuilles_tab=["feuilles_aeriennes","feuilles_immergees","feuilles_des_rameaux_fleuris","feuilles_des_rameaux_steriles"];
			
			if (in_array($name,$tab_names)){
				/* $regions_tab=['auvergne_rhone_alpes','bourgogne_franche_comte','bretagne',"centre_val_de_loire","corse","grand_est","guadeloupe","hauts_de_france","ile_de_france","la_reunion","martinique","mayotte","normandie","nouvelle_aquitaine","occitanie","provence_alpes_cote_d_azur"];	 */
				if (in_array($name,$feuilles_tab) AND get_field("feuille_presence_de_feuilles",$post_id) !== "visibles"){
					continue;
				}
				
				if ($name==="feuilles_aeriennes" AND get_field("heteromorphisme_foliaire",$post_id)==="deux formes distinctes de feuilles" AND get_field("deux_formes_distinctes",$post_id)==="plante à rameaux stériles et à rameaux fleuris distincts"){
				
					continue;
				}
				if ($name==="feuilles_immergees" AND get_field("heteromorphisme_foliaire",$post_id)==="deux formes distinctes de feuilles" AND get_field("deux_formes_distinctes",$post_id)==="plante à rameaux stériles et à rameaux fleuris distincts"){
					continue;
				}
				if ($name==="feuilles_immergees" AND get_field("heteromorphisme_foliaire",$post_id)!=="deux formes distinctes de feuilles"){
					continue;
				}
				if ($name==="feuilles_des_rameaux_fleuris" AND get_field("heteromorphisme_foliaire",$post_id)!=="deux formes distinctes de feuilles"){
					continue;
				}
				if ($name==="feuilles_des_rameaux_fleuris" AND get_field("heteromorphisme_foliaire",$post_id)==="deux formes distinctes de feuilles" AND get_field("deux_formes_distinctes",$post_id)==="plante à feuilles immergées et aériennes"){
					continue;
				}
				if ($name==="feuilles_des_rameaux_steriles" AND get_field("heteromorphisme_foliaire",$post_id)!=="deux formes distinctes de feuilles"){
					continue;
				}
				if ($name==="feuilles_des_rameaux_seriles" AND get_field("heteromorphisme_foliaire",$post_id)==="deux formes distinctes de feuilles" AND get_field("deux_formes_distinctes",$post_id)==="plante à feuilles immergées et aériennes"){
					continue;
				}
				$sys_bi=["hermaphrodite","andromonoïque","androdioïque","gynomonoïque","gynodioïque","androgynomonoïque","androgynodioïque"];
				$systeme_sexuel=get_field("systeme_sexuel",$post_id);
				if ($name==="fleur_bisexuee" AND !in_array($systeme_sexuel,$sys_bi)){
					continue;
				}
				$sys_fem = ["monoïque","dioïque","gynomonoïque","gynodioïque","androgynomonoïque","androgynodioïque"];
				if ($name==="fleur_femelle" AND !in_array($systeme_sexuel,$sys_fem)){
					continue;
				}
				$sys_male = ["monoïque","dioïque","andromonoïque","androdioïque","androgynomonoïque","androgynodioïque"];
				if ($name==="fleur_male" AND !in_array($systeme_sexuel,$sys_male)){
					continue;
				}
				$tige_tab = ['illustration_de_la_tige','type_de_tige',"ramification","section_de_la_tige","surface_de_la_tige_jeune","surface_de_lecorce"];
				$perianthe_tab = ["composition_du_perianthe","corolle","soudure_de_la_corolle","perigone","soudure_du_perigone","calice","soudure du calice"];
				$cor_tab=["corolle","soudure_de_la_corolle"];
				$cal_tab=["calice","soudure du calice"];
				$per_tab=["perigone","soudure_du_perigone"];
			
				foreach ($fd as $key => $sub_field){
					
					$sub_field=get_field($name."_".$key,$post_id);
					
					if(in_array($key,$tige_tab) AND empty($sub_field)){
						$tige = get_field($name."_tige_aerienne");
						if( $tige !== 'non visible' ){
							return $bool = true;
						}
					}else if ($key =="limbe_des_feuilles_simples"){
						$type = get_field($name."_type_de_feuille");
						if (empty($sub_field) AND in_array("simples",$type)){
							
								return $bool=true;
								break;
						}
					}else if ($key =="limbe_des_folioles" OR $key =="nombre_de_folioles"){
					
						$type = get_field($name."_type_de_feuille");
						if (empty($sub_field) AND in_array("composées",$type)){
							
								return $bool=true;
								break;
						}
					}else if ($key =="forme_et_couleur_des_stipules"){
						$val = get_field($name."_stipules");
						if (empty($sub_field) AND $val ==="présents"){
							
								return $bool=true;
								break;
						}
					}else if ($key =="localisation_des_poils"){
						$val = get_field($name."_pubescence");
						if (empty($sub_field) AND $val ==="pubescente"){
							
								return $bool=true;
								break;
						}
					}else if (in_array($key,$perianthe_tab)){
						$val = get_field($name."_perianthe");
						if (empty($sub_field) AND $val ==="présent"){
							
								return $bool=true;
								break;
						}else{
							$val = get_field($name."_composition_du_perianthe");
							if (in_array($key,$cor_tab)){
								
								if (empty($sub_field) AND ($val ==="pétales" OR $val === "sépales et pétales")){
									
										return $bool=true;
										break;
								}
							}
							if (in_array($key,$cal_tab)){
								
								if (empty($sub_field) AND ($val ==="sépales" OR $val === "sépales et pétales")){
									
										return $bool=true;
										break;
								}
							}
							if (in_array($key,$per_tab)){
								
								if (empty($sub_field) AND ($val ==="tépales")){
									
										return $bool=true;
										break;
								}
							}
						}
					}else if ($key =="autre_caractere" /* OR $key =="nombre_de_folioles" */){
						continue;
					}else if ($name === "inflorescence" AND $key =="description"){
						if (empty($sub_field)){
							$val =get_field($name."_categorie",$post_id);
							
							if ($val === "autre"){
								return $bool=true;
								break;
							}
							
						}
					}else if ($name === "valeurs_ecologiques_historiques_et_locales" AND $key =="description"){
						if (empty($sub_field)){
							$val =get_field($name."_cette_plante_a_t_elle_ete_ou_est_elle_cultivee_pour_les_usages_suivants",$post_id);
							
							if (in_array("autre",$val)){
								return $bool=true;
								break;
							}
							
						}
					}else if($key==="surface_de_lecorce"){
						$val = get_field("port_de_la_plante",$post_id);
						
						if(($val==='arbre' || $val === 'arbrisseau') AND empty($sub_field)){
							return $bool=true;
							break;
						}
					}else if($key==="type_dauxiliaires"){
						$val = get_field($name."_plantes_connues_pour_attirer_des_auxiliaires_de_culture",$post_id);
						
						if($val AND empty($sub_field)){
							return $bool=true;
							break;
						}
					}else if($key==="les_ravageurs"){
						$val = get_field($name."_plantes_connues_pour_attirer_les_ravageurs",$post_id);
						if($val AND empty($sub_field)){
							return $bool=true;
							break;
						}
					}else if($key==="les_predateurs"){
						$val = get_field($name."_type_dauxiliaires",$post_id);
						if(in_array('prédateurs',$val) AND empty($sub_field)){
							return $bool=true;
							break;
						}
					}else if($key==="a_quelles_molecules_"){
						$val = get_field($name."_est-ce_quune_resistance_aux_herbicides_a_ete_identifiee_chez_cette_espece_",$post_id);
						if($val==='oui' AND empty($sub_field)){
							return $bool=true;
							break;
						}
					}else if($key==="statut_de_protection_a_l_echelle_locale_regions_concernees"){
						$val = get_field($name."_statut_de_protection",$post_id);
						if($val==='a un statut de protection au niveau national et/ou régional' AND empty($sub_field)){
							return $bool=true;
							break;
						}
					}else if($key==="quel_est_le_statut_de_protection__auvergne_rhone_alpes"){
						$val = get_field($name."_statut_de_protection_a_l_echelle_locale_regions_concernees",$post_id);
						if(in_array("Auvergne-Rhône-Alpes",$val) AND empty($sub_field)){
							return $bool=true;
							break;
						}
					}else if($key==="quel_est_le_statut_de_protection__bourgogne_franche_comte"){
						$val = get_field($name."_statut_de_protection_a_l_echelle_locale_regions_concernees",$post_id);
						if(in_array("Bourgogne-Franche-Comté",$val) AND empty($sub_field)){
							return $bool=true;
							break;
						}
					}else if($key==="quel_est_le_statut_de_protection__bretagne"){
						$val = get_field($name."_statut_de_protection_a_l_echelle_locale_regions_concernees",$post_id);
						if(in_array("Bretagne",$val) AND empty($sub_field)){
							return $bool=true;
							break;
						}
					}else if($key==="quel_est_le_statut_de_protection__centre_val_de_loire"){
						$val = get_field($name."_statut_de_protection_a_l_echelle_locale_regions_concernees",$post_id);
						if(in_array("Centre-Val de Loire",$val) AND empty($sub_field)){
							return $bool=true;
							break;
						}
					}else if($key==="quel_est_le_statut_de_protection__corse"){
						$val = get_field($name."_statut_de_protection_a_l_echelle_locale_regions_concernees",$post_id);
						if(in_array("Corse",$val) AND empty($sub_field)){
							return $bool=true;
							break;
						}
					}else if($key==="quel_est_le_statut_de_protection__grand_est"){
						$val = get_field($name."_statut_de_protection_a_l_echelle_locale_regions_concernees",$post_id);
						if(in_array("Grand-Est",$val) AND empty($sub_field)){
							return $bool=true;
							break;
						}
					}else if($key==="quel_est_le_statut_de_protection__guadeloupe"){
						$val = get_field($name."_statut_de_protection_a_l_echelle_locale_regions_concernees",$post_id);
						if(in_array("Guadeloupe",$val) AND empty($sub_field)){
							return $bool=true;
							break;
						}
					}else if($key==="quel_est_le_statut_de_protection__hauts_de_france"){
						$val = get_field($name."_statut_de_protection_a_l_echelle_locale_regions_concernees",$post_id);
						if(in_array("Hauts-de-France",$val) AND empty($sub_field)){
							return $bool=true;
							break;
						}
					}else if($key==="quel_est_le_statut_de_protection__ile_de_france"){
						$val = get_field($name."_statut_de_protection_a_l_echelle_locale_regions_concernees",$post_id);
						if(in_array("Île-de-France",$val) AND empty($sub_field)){
							return $bool=true;
							break;
						}
					}else if($key==="quel_est_le_statut_de_protection__la_reunion"){
						$val = get_field($name."_statut_de_protection_a_l_echelle_locale_regions_concernees",$post_id);
						if(in_array("La Réunion",$val) AND empty($sub_field)){
							return $bool=true;
							break;
						}
					}else if($key==="quel_est_le_statut_de_protection__martinique"){
						$val = get_field($name."_statut_de_protection_a_l_echelle_locale_regions_concernees",$post_id);
						if(in_array("Martinique",$val) AND empty($sub_field)){
							return $bool=true;
							break;
						}
					}else if($key==="quel_est_le_statut_de_protection__mayotte"){
						$val = get_field($name."_statut_de_protection_a_l_echelle_locale_regions_concernees",$post_id);
						if(in_array("Mayotte",$val) AND empty($sub_field)){
							return $bool=true;
							break;
						}
					}else if($key==="quel_est_le_statut_de_protection__normandie"){
						$val = get_field($name."_statut_de_protection_a_l_echelle_locale_regions_concernees",$post_id);
						if(in_array("Normandie",$val) AND empty($sub_field)){
							return $bool=true;
							break;
						}
					}else if($key==="quel_est_le_statut_de_protection__nouvelle_aquitaine"){
						$val = get_field($name."_statut_de_protection_a_l_echelle_locale_regions_concernees",$post_id);
						if(in_array("Nouvelle-Aquitaine",$val) AND empty($sub_field)){
							return $bool=true;
							break;
						}
					}else if($key==="quel_est_le_statut_de_protection__occitanie"){
						$val = get_field($name."_statut_de_protection_a_l_echelle_locale_regions_concernees",$post_id);
						if(in_array("Occitanie",$val) AND empty($sub_field)){
							return $bool=true;
							break;
						}
					}else if($key==="quel_est_le_statut_de_protection__pays_de_la_loire"){
						$val = get_field($name."_statut_de_protection_a_l_echelle_locale_regions_concernees",$post_id);
						if(in_array("Pays de la Loire",$val) AND empty($sub_field)){
							return $bool=true;
							break;
						}
					}else if($key==="quel_est_le_statut_de_protection__provence_alpes_cote_d_azur"){
						$val = get_field($name."_statut_de_protection_a_l_echelle_locale_regions_concernees",$post_id);
						if(in_array("Provence-Alpes-Côte d'Azur",$val) AND empty($sub_field)){
							return $bool=true;
							break;
						}
					}else if($key==="precisions_sur_la_zone_de_la_region_concernee_departement_environnement_auvergne_rhone_alpes"){
						continue;
					}else if($key==="precisions_sur_la_zone_de_la_region_concernee_departement_environnement_bourgogne_franhce_comte"){
						continue;
					}else if($key==="precisions_sur_la_zone_de_la_region_concernee_departement_environnement_bretagne"){
						continue;
					}else if($key==="precisions_sur_la_zone_de_la_region_concernee_departement_environnement_centre_val_de_loire"){
						continue;
					}else if($key==="precisions_sur_la_zone_de_la_region_concernee_departement_environnement_corse"){
						continue;
					}else if($key==="precisions_sur_la_zone_de_la_region_concernee_departement_environnement_grand_est"){
						continue;
					}else if($key==="precisions_sur_la_zone_de_la_region_concernee_departement_environnement_guadeloupe"){
						continue;
					}else if($key==="precisions_sur_la_zone_de_la_region_concernee_departement_environnement_hauts_de_france"){
						continue;
					}else if($key==="precisions_sur_la_zone_de_la_region_concernee_departement_environnement_ile_de_france"){
						continue;
					}else if($key==="precisions_sur_la_zone_de_la_region_concernee_departement_environnement_la_reunion"){
						continue;
					}else if($key==="precisions_sur_la_zone_de_la_region_concernee_departement_environnement_martinique"){
						continue;
					}else if($key==="precisions_sur_la_zone_de_la_region_concernee_departement_environnement_mayotte"){
						continue;
					}else if($key==="precisions_sur_la_zone_de_la_region_concernee_departement_environnement_normandie"){
						continue;
					}else if($key==="precisions_sur_la_zone_de_la_region_concernee_departement_environnement_nouvelle_aquitaine"){
						continue;
					}else if($key==="precisions_sur_la_zone_de_la_region_concernee_departement_environnement_occitanie"){
						continue;
					}else if($key==="precisions_sur_la_zone_de_la_region_concernee_departement_environnement_pays_de_la_loire"){
						continue;
					}else if($key==="precisions_sur_la_zone_de_la_region_concernee_departement_environnement_provence_alpes_cote_d_azur"){
						continue;
					}else if (empty($sub_field) AND $sub_field !== false){
						
						return $bool=true;
						break;
					}
				}
			}
			if ( empty($fd)){
				if($name==='heteromorphisme_foliaire'){
					if (empty(get_field("feuille_presence_de_feuilles"))){
						return $bool = true;
					}
				}else if ($name==="pilosite_de_la_plante_entiere"){
					$port = get_field("port_de_la_plante");
					if ($port==='arbre' || $port === 'arbrisseau'){
						return $bool=true;
					}
					
				}else if (is_array($field['conditional_logic'])){
					$conditions = $field['conditional_logic'][0][0];
					$champ= $conditions['field'];
					$operator = $conditions['operator'];
					$value= $conditions['value'];

					$valeur=null;

				
					$valeur = get_field($champ);
					
					if ($operator == "=="){
						if ($value==$valeur){
							
							return $bool=true;
							break;
						}
					}
					
				}else{
					
					
					return $bool=true;
					break;
				}
				
			}
		}
		
	}
}

