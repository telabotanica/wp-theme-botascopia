<?php
// adding "bs_" (botascopia) prefix to avoid overriding native wp functions

// Chargement des dépendances installées avec Composer

use JsPhpize\Nodes\Constant;

require get_template_directory() . '/vendor/autoload.php';

// ajout de la recherche sur les champs acf
require get_template_directory() . '/inc/custom-search-acf-wordpress.php';

// Chargement du styleguide
require get_template_directory() . '/inc/styleguide.php';

// Chargement du fichier utile
require get_template_directory() . '/inc/utile.php';

// Personnalisation de la page de collections
require get_template_directory() . '/inc/collections.php';

// Personnalisation de la page des fiches
require get_template_directory() . '/inc/fiches.php';

// Personnalisation de la page de login
require get_template_directory() . '/inc/login.php';

// Redirection des non-admins vers la page d'accueil lors du login
require get_template_directory() . '/inc/redirect-after-login.php';

// Gestion des contenus, liens, commentaires etc. à la suppression d'un compte
require get_template_directory() . '/inc/manage-delete-account.php';

// Génération du svg pour champs agro eco du pdf
require get_template_directory() . '/inc/graphiques.php';

// Fichier des constantes
require get_template_directory() . '/inc/Constantes.php';

// Fichier des routes
require get_template_directory() . '/inc/routes.php';
//require get_template_directory() . '/maintenance.php';

// add theme supports
function bs_theme_supports() {
  add_theme_support('title-tag');
  add_theme_support('post-thumbnails');
  add_theme_support('menus');
//  register_nav_menu('main-menu', 'Menu principal');
  set_post_thumbnail_size( 220, 160, array( 'center', 'center') );
  add_image_size( 'medium_square', 250, 250, array( 'center', 'center') );
  add_image_size( 'home-latest-post', 600, 365, array( 'center', 'center') );
  add_image_size( 'home-post-thumbnail', 65, 50, array( 'center', 'center') );
  add_image_size( 'cover-background', 1920, 500, array( 'center', 'center') );
	
	// This theme uses wp_nav_menu() in two locations.
	register_nav_menus(
		[
			'principal' => __('Menu principal', 'botascopia'),
			'secondary' => __('Menu secondaire', 'botascopia'),
			'footer-bar' => __('Pied de page - bandeau', 'botascopia'),
			'footer-liens' => __('footer-liens', 'botascopia'),
			'footer-legal' => __('footer-legal', 'botascopia'),
			'footer-contacts' => __('footer-contacts', 'botascopia'),
		]);
}
add_action('after_setup_theme', 'bs_theme_supports');

// load css (and js, later if needed)
function bs_load_scripts() {
	wp_enqueue_style( 'bs-style', get_template_directory_uri() . '/dist/bundle.css' );
	
	// Theme script.
	wp_enqueue_script( 'bs-script', get_template_directory_uri() . '/dist/bundle.js', [ 'jquery', 'wp-util' ], null, true);
	wp_localize_script( 'bs-script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	
}
add_action('wp_enqueue_scripts', 'bs_load_scripts' );

// auto export acf fields after each saved change
function bs_acf_export_json( $path ) {
  $path = get_stylesheet_directory() . '/acf-json';
  return $path;
}
add_filter('acf/settings/save_json', 'bs_acf_export_json');

function set_user_admin_bar_false_by_default($user_id) {
    update_user_meta( $user_id, 'show_admin_bar_front', 'false' );
    update_user_meta( $user_id, 'show_admin_bar_admin', 'false' );
}
add_action("user_register", "set_user_admin_bar_false_by_default", 10, 1);

add_filter( 'login_redirect', function( $url, $query, $user ) {
    return home_url();
}, 10, 3 );

add_action( 'set_pending', 'add_editor_meta' );

function add_editor_meta( $post_id ) {
    $date = date('Y-m-d H:i:s');
    $date_gmt = gmdate('Y-m-d H:i:s');

    wp_update_post(array('ID' => $post_id, 'post_date' => $date, 'post_date_gmt' => $date_gmt, 'post_status' => 'pending'));

    update_post_meta( $post_id, 'Editor', 0 );
}

add_action( 'set_publish', 'publish_post' );

function publish_post( $post_id ) {
    wp_update_post(array('ID' => $post_id, 'post_status' => 'publish'));
}

function my_acf_save_post($post_id) {

    $submitedStatus = $_POST['acf']['current_step'];
    if ($submitedStatus == 1){
        $value = 'draft';
    } else if ($submitedStatus == 2){
        $value = 'pending';
    } else if ($submitedStatus == 3){
        $value = 'publish';
    }

// Update current post
    $my_post = array(
        'ID' => $post_id,
        'post_status' => $value,
    );
    remove_action('acf/save_post', 'my_acf_save_post', 20);

// Update the post into the database
    wp_update_post($my_post);

// Add the action back
    add_action('acf/save_post', 'my_acf_save_post', 20);
}

// run after ACF saves the $_POST['acf'] data
add_action('acf/save_post', 'my_acf_save_post', 20);


// Save fiche to favorite
function add_fav_fiche_meta() {
	$user_id = $_POST['user_id'];
	$fiche = $_POST['fiche'];
	$favorites = [];
	
	// On récupère les favoris existants
	$existingFavorites = get_user_meta($user_id, 'favorite_fiche');
	
	foreach ($existingFavorites[0] as $key => $value) {
		$favorites[] = $value;
	}
	
	// si category déjà dans favoris on l'enlève
	if (($key = array_search($fiche, $favorites)) !== false) {
		unset($favorites[$key]);
	} else {
		$favorites[] = $fiche;
	}
	
	// update user meta with array
	update_user_meta($user_id, 'favorite_fiche', $favorites);
}
add_action( 'wp_ajax_set_fav_fiche', 'add_fav_fiche_meta' );

// Ajoutez la variable de requête personnalisée
function custom_query_vars($query_vars) {
	$query_vars[] = 'custom_collection';
	return $query_vars;
}
add_filter('query_vars', 'custom_query_vars');

function uploadImage($file){
	// get the file suffix
	$suffix = pathinfo($file['name'], PATHINFO_EXTENSION);
	// define the save location and filename
	$filename = date('YmdHis').'.'.$suffix;
	$wp_upload_dir = wp_upload_dir();
	$path = $wp_upload_dir['path'].'/'.$filename;
	
	// upload file
	if(move_uploaded_file($file['tmp_name'], $path)) {
		$attach_id = wp_insert_attachment(array(
											  'guid'              =>  $wp_upload_dir['url'].'/'.$filename,
											  'post_mime_type'    =>  $file['type'],
											  'post_title'        =>  preg_replace( '/\.[^.]+$/', '', $filename),
											  'post_content'      =>  '',
											  'post_status'       =>  'inherit',
										  ), $path, get_the_ID());
		
		// Make sure that this file is included, as wp_generate_attachment_metadata() depends on it.
		require_once( ABSPATH . 'wp-admin/includes/image.php' );

		// Generate the metadata for the attachment, and update the database record.
		$attach_data = wp_generate_attachment_metadata($attach_id, $path);
		wp_update_attachment_metadata( $attach_id, $attach_data );
		
		return $attach_id;
	} else {
		return null;
	}
}

function getPostImage($id){
	$imageId = get_post_thumbnail_id($id);
	$getImage = wp_get_attachment_image_src($imageId, 'full');

	if ($getImage){
		$image[] = $getImage[0];
	} else {
		$image[] = get_template_directory_uri() . '/images/logo-botascopia@2x.png';
	}
	
	return $image;
}

function getFicheImage($id){
	$champ = get_field("illustration_plante_entiere_photo_de_la_plante_entiere",$id);
	$image = null;
	if (!empty($champ)){
		$id_image = $champ;
		if (intval($id_image) !== 0){
			$img = get_post($id_image);
			$image = $img->guid;
		}else{
			$image = get_template_directory_uri() . '/images/logo-botascopia@2x.png';
		}
	}else{
		$image = get_template_directory_uri() . '/images/logo-botascopia@2x.png';
	}
    
    return $image;
}

function changeFavIcon($categoryId, $favoritesArray){
	if (($key = array_search($categoryId, $favoritesArray)) !== false) {
		$icone = ['icon' => 'star', 'color' => 'blanc'];
	} else {
		$icone = ['icon' => 'star-outline', 'color' => 'blanc'];
	}
	
	return $icone;
}

// Template pour les fiches
function custom_post_template($single) {
	global $post;
	
	if ($post->post_type == 'post') {
		$single_template = dirname(__FILE__) . '/fiche.php';
		if (file_exists($single_template)) {
			return $single_template;
		}
	}
	return $single;
}
add_filter('single_template', 'custom_post_template');

// Fonction pour le plugin Posts 2 Posts
function my_connection_types() {
	p2p_register_connection_type(
		array(
			'name' => 'collection_to_post',
			'from' => 'collection',
			'to' => 'post',
		));
}
add_action( 'p2p_init', 'my_connection_types' );

add_filter( 'p2p_connected_query_args', 'add_private_collections', 10, 3 );
function add_private_collections( $args, $ctype, $post ) {
	if ( $ctype->name == 'collection_to_post' ) {
		$args['post_status'] = array('publish', 'draft', 'pending', 'private');
	}
	return $args;
}

function getFiches($id){
	$nbFiches = 0;
	$completed = true;
	
	$connected_posts = new WP_Query(
		array(
			'connected_type' => 'collection_to_post',
			'connected_items' => $id,
			'nopaging' => true,
			'post_status' => array('publish', 'draft', 'pending', 'private'),
		));
	if ($connected_posts->have_posts()) :
		while ($connected_posts->have_posts()) : $connected_posts->the_post();
			$ficheId = get_the_ID();
			$status = get_post_status($ficheId);
			$nbFiches++;
			if ($status != 'publish') {
				$completed = false;
			}
			// Afficher ici les informations sur chaque article de type "post" connecté
		endwhile;
	endif;
	wp_reset_postdata();
	
	if ($nbFiches == 0 ){
		$completed = false;
	}
	
	return [$nbFiches, $completed];
}

function reserver_fiche() {
	$userId = $_POST['user_id'];
	$ficheId = $_POST['fiche'];
	wp_update_post(array('ID' => $ficheId, 'post_author' => $userId));
	wp_die();
}
add_action( 'wp_ajax_reserver_fiche', 'reserver_fiche' );

function affichageImageFiche($photo,$id=null){
	if (!empty($photo)){
		$photoId = $photo['ID'];
		$image = wp_get_attachment_image_src( $photoId, 'image-tige' )[0];
		echo ("<div $id class='image-fiche'><img src='".esc_url( $image )."' class='image-tige'></div>");
	}
}

// Envoyer une fiche à validation ou en publication
add_action( 'wp_ajax_set_fiche_status', 'set_fiche_status' );
add_action( 'wp_ajax_nopriv_set_fiche_status', 'set_fiche_status' );

function set_fiche_status() {
	$post_id = intval( $_POST['post_id'] );
	$date = date('Y-m-d H:i:s');
	$date_gmt = gmdate('Y-m-d H:i:s');
	$status = $_POST['status'];
	
	wp_update_post(array('ID' => $post_id, 'post_status' =>
		$status));
	
	if ($status == 'pending'){
		update_post_meta( $post_id, 'Editor', 0 );
	}
	
	die();
}

function enregistrer_meta_groupe_champs_acf($post_id) {
//	dump($_POST['acf']);
	$field_group='';
	foreach ($_POST['acf'] as $acf_field_key => $acf_value){
		$field = acf_get_field($acf_field_key);
		if (isset($field['name']) && $field['name'] != '_validate_email' && !empty($acf_value)){
			$field_group = acf_get_field_group($field['parent'])['title'];
			break;
		}
	}
	
	// Valeur de la meta à enregistrer
	$meta_value = 'complet';
	
	// Ajoute la paire clé/valeur de métadonnées au post spécifié
	add_post_meta($post_id, $field_group, 'complet', true);
}
add_action('acf/save_post', 'enregistrer_meta_groupe_champs_acf', 20);

function ajout_boite_meta_description() {
	add_meta_box(
		'page_description', // ID unique de la boîte de méta
		'Description de la Page', // Titre de la boîte de méta
		'afficher_boite_meta_description', // Fonction pour afficher le contenu de la boîte de méta
		'page', // Type de contenu où la boîte de méta doit apparaître (page dans cet exemple)
		'normal', // Emplacement de la boîte de méta (normal, side, advanced)
		'high' // Priorité de la boîte de méta (high, core, default, low)
	);
}

// Fonction pour afficher le contenu de la boîte de méta description
function afficher_boite_meta_description($post) {
	// Récupérer la valeur actuelle du champ personnalisé "description_page"
	$description_page = get_post_meta($post->ID, 'description_page', true);
	
	// Afficher le champ de saisie pour la description
	?>
	<label for="description_page">Description de la page :</label>
	<textarea id="description_page" name="description_page" style="width:100%;" rows="1"><?php echo esc_textarea($description_page); ?> </textarea>
	<?php
}

// Fonction pour sauvegarder la valeur du champ personnalisé
function sauvegarder_meta_description($post_id) {
	if (defined('DOING_AUTOSAVE') && DOING_AUTOSAVE) {
		return;
	}
	
	// Vérifier les autorisations
	if (!current_user_can('edit_page', $post_id)) {
		return;
	}
	
	// Enregistrez la valeur du champ personnalisé
	if (isset($_POST['description_page'])) {
		update_post_meta($post_id, 'description_page', sanitize_text_field($_POST['description_page']));
	}
}

// Ajouter des actions pour lier les fonctions aux événements appropriés
add_action('add_meta_boxes', 'ajout_boite_meta_description');
add_action('save_post', 'sauvegarder_meta_description');


add_filter( 'manage_posts_columns', 'revealid_add_id_column', 5 );
add_action( 'manage_posts_custom_column', 'revealid_id_column_content', 5, 2 );


function revealid_add_id_column( $columns ) {
    $columns['revealid_id'] = 'ID';
    return $columns;
}

function revealid_id_column_content( $column, $id ) {
    if( 'revealid_id' == $column ) {
        echo $id;
    }
}

//Traduit le rôle en français
function getRole($role){
	switch ($role) {
		case "administrator":
		return $role=Constantes::ADMINISTRATEUR;
		break;
	case "editor":
		return $role= Constantes::VERIFICATEUR;
		break;
	case "author":
		return $role="auteur";
		break;
	case "contributor":
		return $role=Constantes::CONTRIBUTEUR;
		break;
	case "subscriber";
		return $role="abonné";
		break;
	default;
		return $role="";
		break;
	}
}

//Permet d'afficher les valeurs min - max
function getValueOrganesFloraux($organes,$mot,$soudure){

	$organes_tab = [];
	
	if (is_array($organes)){
		foreach($organes as $value){
			if($value!=='n&gt;20'){
				array_push($organes_tab,intval($value));
			}else{
				array_push($organes_tab,$value);
			}
			
		}
		if (!empty($organes_tab)){
			
			if (!in_array('n&gt;20',$organes_tab)){
				
				$min = min($organes_tab);
				$max = max($organes_tab);
				if ($min !== $max){
					
					$texte = "$min-$max $mot $soudure";
					$texte = trim($texte);
					$texte .=" ; ";
					return $texte;	
				}else{
					
					if ($min ===1){
						$mot = substr($mot, 0, -1);
						return "$min $mot ; ";
					}
					$texte = "$min $mot $soudure";
					$texte = trim($texte);
					$texte .=" ; ";
					return $texte;
				}
			}else{
				$texte = "plus de 20 $mot $soudure";
				$texte=trim($texte);
				$texte .=" ; ";
				return $texte;
			}
		}else{
			return " ; ";
		}
		
		
	}else{
		
		$texte = "$organes $mot $soudure";
		
		$texte = trim($texte);
		$texte .=" ; ";
		return $texte;	
	}
}

//Requête personnalisée
function wpse18703_posts_where( $where, $wp_query )
{
    global $wpdb;
    if ( $wpse18703_title = $wp_query->get( 'wpse18703_title' ) ) {
        $where .= ' AND ' . $wpdb->posts . '.post_title LIKE \'' . esc_sql( $wpdb->esc_like( $wpse18703_title ) ) . '%\'';
		
    }
    return $where;
}
add_filter( 'posts_where', 'wpse18703_posts_where', 10, 2 );

//Récupère l'id du post en fonction du titre
function get_page_by_post_title($post_title, $output = OBJECT, $post_type = 'post' ){
    global $wpdb;
    $page = $wpdb->get_var( $wpdb->prepare( "SELECT ID FROM $wpdb->posts WHERE post_title = %s AND post_type= %s" , $post_title, $post_type ));

    if ( $page ) return get_post($page,$output);

    return null;
}
 add_action('init','get_page_by_post_title');

 //Permet d'exclure des champs dans l'affichage du formulaire
function exclude_fields( $field ) {
	$array_labels = [];
    if( in_array($field['label'],$array_labels) ) {
        return false; 
    }
    return $field;
}
add_filter('acf/prepare_field', 'exclude_fields'); 

//Affiche le complément sur la pubescence
function getPubescence($feuille,$mode,$texte){
    if (str_contains($texte,'pubescent') AND $mode ===1){
        $comp= $feuille[Constantes::LOCALISATION_PUBESCENCE_FEUILLES_SIMPLES];
        return $texte = str_replace("pubescent","pubescent $comp",$texte);
    }else if (str_contains($texte, 'pubescent') AND $mode ===2){
        $comp= $feuille[Constantes::LOCALISATION_PUBESCENCE_FOLIOLES];
        return $texte = str_replace("pubescent","pubescent $comp",$texte);
    }else{
		return $texte;
	}
}

//Réduit la donnée "soudés sur toute la longueur (ovaire, styles, stigmates)" dans le pdf car trop longue
function getSoudureCarpelles($fleur){
	$soudure = $fleur[Constantes::SOUDURE_CARPELLES];
	if ($soudure === "soudés sur toute la longueur (ovaire, styles, stigmates)"){
		return "soudés sur toute la longueur";
	}else{
		return $soudure;
	}
}

//Convertit les mois en français
function getDateInFrench($date){
	switch ($date){
		case str_contains($date, Constantes::JANUARY):
			$date = str_replace(Constantes::JANUARY,"janvier",$date);
			return $date;
		case str_contains($date, Constantes::FEBRUARY):
			$date = str_replace(Constantes::FEBRUARY,"février",$date);
			return $date;
		case str_contains($date, Constantes::MARCH):
			$date = str_replace(Constantes::MARCH,"mars",$date);
			return $date;
		case str_contains($date, Constantes::APRIL):
			$date = str_replace(Constantes::APRIL,"avril",$date);
			return $date;
		case str_contains($date, Constantes::MAY):
			$date = str_replace(Constantes::MAY,"mai",$date);
			return $date;
		case str_contains($date, Constantes::JUNE):
			$date = str_replace(Constantes::JUNE,"juin",$date);
			return $date;
		case str_contains($date, Constantes::JULY):
			$date = str_replace(Constantes::JULY,"juillet",$date);
			return $date;
		case str_contains($date, Constantes::AUGUST):
			$date = str_replace(Constantes::AUGUST,"août",$date);
			return $date;
		case str_contains($date, Constantes::SEPTEMBER):
			$date = str_replace(Constantes::SEPTEMBER,"septembre",$date);
			return $date;
		case str_contains($date, Constantes::OCTOBER):
			$date = str_replace(Constantes::OCTOBER,"octobre",$date);
			return $date;
		case str_contains($date, Constantes::NOVEMBER):
			$date = str_replace(Constantes::NOVEMBER,"novembre",$date);
			return $date;
		case str_contains($date, Constantes::DECEMBER):
			$date = str_replace(Constantes::DECEMBER,"décembre",$date);
			return $date;
	}
}

//Enlève les + dans le nom scientifique
function getFilteredTitle($nom){
	$nom = str_replace("+","",$nom);
	return $nom;
}

//Affiche le title dans la preview
function custom_title($title) {
	$url = get_the_permalink(get_the_ID());
    if (str_contains($url,'?p=') OR str_contains($url,'bdtfx')) {
		$nom = str_replace("<i>",'',get_field('nom_scientifique',get_the_ID()));
		$nom = str_replace("</i>","",$nom);
        $title['title'] = getFilteredTitle($nom) ."(".$title['title'].")";
    }
    return $title;
}
add_filter('document_title_parts', 'custom_title');

//Affiche le texte du périanthe dans la preview et le pdf
function displayPerianthe($composition,$fleur){
	
    $perianthe="";
    if ($composition === Constantes::TEPALES) {
        $tepales = $fleur['perigone'];
		$soudure = !empty($fleur['soudure_du_perigone_']) ? $fleur['soudure_du_perigone_'] : "";
        $perianthe = getValueOrganesFloraux($tepales,'tépales',$soudure);
        
    } else if($composition === Constantes::PETALES_SEPALES){
        $sepales = $fleur['calice'];
		$soudure = $fleur['soudure_du_calice_'];
		
        $perianthe = getValueOrganesFloraux($sepales, 'sépales',$soudure);
		$perianthe = substr($perianthe, 0, -2);
        $petales = $fleur['corolle'];
		$soudure = $fleur['soudure_de_la_corolle_'];
        $perianthe .= ' et ' . getValueOrganesFloraux($petales,"pétales",$soudure);
       
    }else if($composition === Constantes::SEPALES){
        $sepales = $fleur['calice'];
		$soudure = $fleur['soudure_du_calice_'];
        $perianthe = getValueOrganesFloraux($sepales, 'sépales',$soudure);
        
    }else if($composition === Constantes::PETALES){
        $petales = $fleur['corolle'];
		$soudure = $fleur['soudure_de_la_corolle_'];
        $perianthe .= getValueOrganesFloraux($petales,"pétales",$soudure);
		
    }
    return trim($perianthe);
}

function getPersistance($persistance){
	switch($persistance){
		case "faible" :
			$persistance .= " (moins de 5 ans)";
			return $persistance;
		case "moyenne" :
			$persistance .= " (entre 5 et 10 ans)";
			return $persistance;
		case "forte" :
			$persistance .= " (plus de 10 ans)";
			return $persistance;
	}
}

function wp_maintenance_mode() {
    if ( !current_user_can( 'edit_themes' ) || !is_user_logged_in() ) {
        wp_die('<h1>Site en maintenance</h1><p>Notre site est actuellement en maintenance. Veuillez revenir plus tard.</p>');
    }
}
add_action('get_header', 'wp_maintenance_mode');
