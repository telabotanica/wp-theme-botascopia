<?php
class Constantes{
	const VERIFICATEUR = "vérificateur";
	const CONTRIBUTEUR = "contributeur";
	const ADMINISTRATEUR = "administrateur";

	const HERMAPHRODITE = "hermaphrodite";
	const MONOIQUE = "monoïque";
	const DIOIQUE = "dioïque";
	const ANDROMONOIQUE = "andromonoïque";
	const GYNOMONOIQUE = "gynomonoïque";
	const ANDRODIOIQUE = "androdioïque";
	const GYNODIOIQUE = "gynodioïque";
	const ANDROGYNOMONIQUE = "androgynomonoïque";
	const ANDROGYNODIOIQUE = "androgynodioïque";

	const COLLECTIONS_FAV = "Mes collections favorites";
	const COLLECTIONS = "MES COLLECTIONS";
	const COLLECTIONS_TO_COMP = 'Compléter une collection';
	const COLLECTIONS_COMP = 'Mes collections complètées';
	const COLLECTIONS_MODIF = "Modifier la collection";
	const COLLECTIONS_DEL = 'Supprimer la collection';
	const COLLECTION_TO_CREATE = 'Créer une collection';

	const FICHES = "MES FICHES";
	const FICHES_FAV = "Mes fiches favorites";
	const FICHES_TO_VAL =  'Mes fiches terminées et en cours de vérification';
	const FICHES_TO_COMP = 'Mes fiches en cours de complétion';
	const FICHES_VAL = 'Mes fiches publiées';
    const FICHES_TO_CHK = "Fiches dont je suis le vérificateur";
	const FICHES_TO_SEE = 'Voir les collections';

	const MESSAGE_CONNEXION = "Vous devez être connecté pour accéder à cette page.";

	const DRAFT = 'draft';
	const DRAFT_FR = 'En cours...';
	const DRAFT_COMP = 'À compléter';
	const PEND = 'pending';
	const PEND_FR = 'En cours de vérification';
	const ENREGISTRER = 'Enregistrer';
	const CORRIGER = 'Corriger';
	const PUBLISH = 'publish';
	const PUBLISH_FR = "Validée";
	const VALIDER = "Valider";
	const INFOS = 'Infos';
	const TT_DEPLIER = 'Tout déplier';
	const BACK_TO_COLLEC =  'Retour aux collections';
	const DONT_PARTICIPATE = 'Ne plus participer à cette fiche';
	const PREVISUALISER = 'Prévisualiser';
	const TELECHARGER = 'Télécharger en pdf';
	const SEND = 'Envoyer la fiche pour vérification';
	const BEC_EDIT = 'Devenir vérificateur de cette fiche';
	const YOUR_DEMAND = "Votre demande a bien été prise en compte.";
	const RESEND = 'Renvoyer pour correction';
	const BACK_TO_FORM = 'Retour au formulaire';

	const DESCRIPTION = "Description morphologique";
	const PERIOD = "Période de floraison et de fructification";
	const AIRE = "Aire de répartition et statut";
	const ECOLOGIE = "Écologie";
	const PROPERTIES = "Propriétés";
	const ANECDOTE = "Complément d'anecdote";
	const CONFUS = "Ne pas confondre avec";
	const VULG = "Description vulgarisée";
	const AGRO = "Agroécologie";
	const REFERENCES = "Références";
	const LE_SAVIEZ_VOUS = 'Le saviez vous?';
	const VOIR = 'Voir plus de fiches';

	const TIGE = "Tige";
	const FEUILLE = "Feuilles";
	const INFLO = "Inflorescence";
	const FL_MALE = "Fleur mâle";
	const FL_FEM = "Fleur femelle";
	const FL_BI = "Fleur bisexuée";
	const FRUIT = "Fruits";

	const DISPLAY_NAME = 'display_name';
	const PHOTO_PLANTE_ENTIERE = "photo_de_la_plante_entiere";
	const NOM_VERNACULAIRE = 'nom_vernaculaire';
	const FAMILLE = 'famille';
	const NOM_SCIENTIFIQUE = 'nom_scientifique';
	const FAVORITE_COLLECTION = 'favorite_collection';
	const PORT_DE_LA_PLANTE = 'port_de_la_plante';
	const SYS_SEXUEL = 'systeme_sexuel';
	const MODE_DE_VIE = 'mode_de_vie_';
	const TYPE_DE_DVPT = 'type_de_developpement';
	const FORME_BIOLOGIQUE = 'forme_biologique';
	const HAUTEUR_MAXIMALE = 'hauteur_maximale';
	const PILOSITE = 'pilosite_de_la_plante_entiere';
	const DESC_VULG = 'description_vulgarisee';
	const TIGE_CHP = 'tige';
	const TYPE_DE_TIGE = 'type_de_tige';
	const SECTION_TIGE = 'section_de_la_tige';
	const SURFACE_TIGE = 'surface_de_la_tige_jeune';
	const SURFACE_ECORCE = 'surface_de_lecorce';
	const TIGE_AERIENNE = 'tige_aerienne_';
	const RAMIFICATION = 'ramification';
	const COULEUR_TRONC = 'couleur_du_tronc';
	const ILLUSTRATION_TIGE = "illustration_de_la_tige";
	const PHOTO_TIGE = 'photo_tige';
	const FEUILLE_CHP = 'feuille';
	const PRESENCE_FEUILLES = 'presence_de_feuilles';
	const HETEROMORPHISME = 'heteromorphisme_foliaire';
	const FEUILLES_AERIENNES = 'feuilles_aeriennes';
	const PHYLLOTAXIE = 'phyllotaxie';
	const TYPE_DE_FEUILLE = 'type_de_feuille';
	const LIMBE_FEUILLES_SIMPLES = 'limbe_des_feuilles_simples_';
	const LIMBE_FOLIOLES = 'limbe_des_folioles_';
	const LOCALISATION_PUBESCENCE_FEUILLES_SIMPLES = "localisation_pubescence_feuilles_simples";
	const LOCALISATION_PUBESCENCE_FOLIOLES = "localisation_pubescence_folioles";
	const MARGE_FOLIAIRE = 'marge_foliaire';
	const NERVATION = 'nervation';
	const PETIOLE = 'petiole';
	const LONGUEUR_PETIOLE = 'longueur_du_petiole';
	const ENGAINANT = 'engainant';
	const STIPULES = 'stipules';
	const FORME_COULEUR_STIPULES = 'forme_et_couleur_des_stipules';
	const FEUILLAGE = 'feuillage';
	const DEUX_FORMES_CHP = 'deux_formes_distinctes';
	const FEUILLES_IMMERGEES = 'feuilles_immergees';
	const FEUILLES_RAMEAUX_STERILES = 'feuilles_des_rameaux_steriles';
	const FEUILLES_RAMEAUX_FLEURIS = 'feuilles_des_rameaux_fleuris';
	const ILLUSTRATION_FEUILLE_AERIENNE = "illustration_de_la_feuille_aerienne";
	const PHOTO_FEUILLES_AERIENNES = 'photo_de_feuilles_aeriennes';
	const ILLUSTRATION_FEUILLE_IMMERGEE = "illustration_de_la_feuille_immergee";
	const PHOTO_FEUILLES_IMMERGEES = 'photo_de_feuilles_immergees';
	const ILLUSTRATION_FEUILLE_RAMEAUX_STERILES = "illustration_de_la_feuille_des_rameaux_steriles";
	const PHOTO_FEUILLES_RAMEAUX_STERILES = 'photo_de_feuilles_des_rameaux_steriles';
	const ILLUSTRATION_FEUILLE_RAMEAUX_FLEURIS = "illustration_de_la_feuille_des_rameaux_fleuris";
	const PHOTO_FEUILLES_RAMEAUX_FLEURIS = 'photo_de_feuilles_des_rameaux_fleuris';
	const INFLO_CHP = 'inflorescence';
	const ORGANISATION_FLEURS = 'organisation_des_fleurs';
	const CATEGORIE = 'categorie_';
	const DESCRIPTION_CHP = 'description';
	const FRUIT_CHP = 'fruit';
	const PHOTO = 'photo';
	const TYPE = 'type_de_fruit';
	const ILLUSTRATION_FRUIT = "illustration_du_fruit";
	const FL_MALE_CHP = 'fleur_male';
	const FL_FEM_CHP = 'fleur_femelle';
	const FL_BI_CHP = 'fleur_bisexuee';
	const PERIANTHE = 'perianthe';
	const SYMETRIE = 'symetrie';
	const DIFFERENCIATION_PERIANTHE = 'differenciation_du_perianthe';
	const PERIGONE = 'perigone';
	const SOUDURE_CALICE_COROLLE = 'soudure_du_calice_et_de_la_corolle';
	const COROLLE = 'corolle';
	const CALICE = 'calice';
	const SOUDURE_CALICE = 'soudure_du_calice_';
	const ANDROCEE = 'androcee';
	const SOUDURE_ANDROCEE_COROLLE ='soudure_androcee-corolle';
	const SOUDURE_ANDROCEE ='soudure_de_landrocee_';
	const SOUDURE_ANDROCEE_PERIGONE = 'soudure_androcee-perigone';
	const STAMINODES = "staminodes";
	const NOMBRE_STAMINODES = 'nombre_de_staminodes';
	const COULEUR_PRINCIPALE = 'couleur_principale';
	const PUBESCENCE = 'pubescence';
	const LOCALISATION_POILS = 'localisation_des_poils_';
	const AUTRE_CARACTERE = 'autre_caractere';
	const ILLUSTRATION_FLEUR_MALE = "illustration_de_la_fleur_male_ou_de_linflorescence";
	const PHOTO_FLEUR_MALE = "photo_de_fleur_male";
	const SOUDURE_PERIGONE = 'soudure_du_perigone_';
	const GYNECEE = 'gynecee';
	const SOUDURE_CARPELLES = 'soudure_des_carpelles_';
	const OVAIRE = 'ovaire_';
	const ILLUSTRATION_FLEUR_FEMELLE = "illustration_de_la_fleur_femelle_ou_de_linflorescence";
	const PHOTO_FLEUR_FEMELLE = 'photo_de_fleur_femelle';
	const COMPOSITION_PERIANTHE = 'composition_du_perianthe';
	const SOUDURE_COROLLE = 'soudure_de_la_corolle';
	const ILLUSTRATION_FLEUR_BISEXUEE = 'illustration_de_la_fleur_bisexuee';
	const PHOTO_FLEUR_BISEXUEE = 'photo_de_fleur_bisexuee';

	const HERBACEE = "herbacée";
	const LIANE = "liane";
	const TERRESTRE = "terrestre";
	const ARBRISSEAU = 'arbrisseau';
	const ARBRE = 'arbre';
	const NON_VISIBLE = 'non visible';
	const JAMAIS_VISIBLES = 'jamais visibles';
	const FEUILLES_SEMBLABLES = 'feuilles toutes semblables';
	const GRADIENT = 'gradient de forme entre la base et le haut de la tige';
	const SIMPLES = 'simples';
	const PRESENT = 'présent';
	const PRESENTS = 'présents';
	const DEUX_FORMES = 'deux formes distinctes de feuilles';
	const FEUILLES_IMMERGEES_AERIENNES = 'plante à feuilles immergées et aériennes';
	const RAMEAUX_STERILES_FLEURIS = 'plante à rameaux stériles et à rameaux fleuris distincts';
	const AUTRE = 'autre';
	const TEPALES = 'tépales';
	const SEPALES = 'sépales';
	const PETALES = 'pétales';
	const PETALES_SEPALES = "pétales et sépales";
	const ANDROCEE_SOUDEE_COROLLE = 'androcée soudé à la corolle';
	const SOUDEES_PERIGONE = 'soudées au perigone';
	const PUBESCENTE = 'pubescente';
	const VISIBLE = 'visible';
	const PLEINE = 'pleine';
	const ORGANISEES_EN_INFLORESCENCES = 'organisées en inflorescences';
	const TOUS_LES_ORGANES_FLORAUX = "tous les organes floraux";
	const ETAT_SAUVAGE = "à l'état sauvage";
	const COMPOSEES = "composées";
	const PREDATEURS = "prédateurs";
	const OUI = "oui";
	const STATUT_PROTECTION = 'a un statut de protection au niveau national et/ou régional';

	const APPARITION = 'Apparait dans les collections suivantes :';
	const FAVORIS = 'Favoris';
}