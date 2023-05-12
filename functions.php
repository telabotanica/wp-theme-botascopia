<?php
// adding "bs_" (botascopia) prefix to avoid overriding native wp functions

// Chargement des dépendances installées avec Composer
require get_template_directory() . '/vendor/autoload.php';

// ajout de la recherche sur les champs acf
require get_template_directory() . '/inc/custom-search-acf-wordpress.php';

// Chargement du styleguide
require get_template_directory() . '/inc/styleguide.php';

// Chargement du fichier utile
require get_template_directory() . '/inc/utile.php';

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
			'footer-columns' => __('Pied de page - en colonnes', 'botascopia'),
		]);
}
add_action('after_setup_theme', 'bs_theme_supports');

// load css (and js, later if needed)
function bs_load_scripts() {
	wp_enqueue_style( 'bs-style', get_template_directory_uri() . '/dist/bundle.css' );
	
	// Theme script.
	wp_enqueue_script( 'bs-script', get_template_directory_uri() . '/dist/bundle.js', [ 'jquery', 'wp-util' ], null, true );
	wp_localize_script( 'bs-script', 'ajax_object', array( 'ajax_url' => admin_url( 'admin-ajax.php' ) ) );
	
//  wp_enqueue_style( 'style', get_stylesheet_uri());
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

// Save collection to favorite
function add_fav_collection_meta() {
	$user_id = $_POST['user_id'];
	$category = $_POST['category'];
	$favorites = [];
	
	// On récupère les favoris existants
	$existingFavorites = get_user_meta($user_id, 'favorite_collection');
	
	foreach ($existingFavorites[0] as $key => $value) {
		$favorites[] = $value;
	}
	
	// si category déjà dans favoris on l'enlève
	if (($key = array_search($category, $favorites)) !== false) {
		unset($favorites[$key]);
	} else {
		$favorites[] = $category;
	}
	
	// update user meta with array
	update_user_meta($user_id, 'favorite_collection', $favorites);
}
add_action( 'wp_ajax_set_fav_coll', 'add_fav_collection_meta' );

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


// Template for single collection (subcategory of 'collections' category
add_filter( 'category_template', 'cxc_custom_category_templates' );
function cxc_custom_category_templates( $template ) {
	$category = get_category( get_queried_object_id() );
	if ( $category->category_parent > 0 ) {
		$sub_category_template = locate_template( 'category-collections-single.php' ); // specify template name which you create for child category
		$template = !empty($sub_category_template) ? $sub_category_template : $template;
	}
	return $template;
}

// Permet de lier le nom d'une collection (post) avec une catégorie du même nom
function create_category_from_post_name($post_id) {
	if (isset($_POST['meta-type']) && $_POST['meta-type'] === 'collection') {
		$post = get_post($post_id);
		$category_name = $post->post_title;
		$parent_term = term_exists('collections', 'category');
		$parent_term_id = $parent_term['term_id'];
		$category = wp_insert_term($category_name, 'category', array('parent' => $parent_term_id));
		if ( !is_wp_error($category)) {
			wp_set_object_terms($post_id, $category_name, 'category', true);
			$category_link = get_term_link($category['term_id']);
			wp_redirect($category_link);
			exit;
		}
	}
}
add_action('wp_insert_post', 'create_category_from_post_name');

// Permet de créer une nouvelle collection (Post)
function create_new_post_collection() {
	
	// Vérification si le nom existe déjà
	if (isset($_POST['post-title']) && get_page_by_title( $_POST['post-title'], 'OBJECT', 'post', false )){
		wp_redirect( home_url( '/creer-une-collection/?error=existing_title' ) );
		exit();
	} else {
		if (isset($_POST['post-title']) && isset($_POST['post-description'])) {
			$title = sanitize_text_field($_POST['post-title']);
			$description = wp_kses_post($_POST['post-description']);
			$my_post = array(
				'post_title' => $title,
				'post_content' => $description,
//			'post_status' => 'publish',
				'post_category' => array( get_cat_ID( $title ) ),
			);
			
			$post_id = wp_insert_post( $my_post );
			if (isset($_POST['meta-type']) && 'collection' === $_POST['meta-type']){
				create_category_from_post_name($post_id);
			}
			
			if ($post_id) {
				wp_redirect( get_permalink( $post_id ) );
				exit;
			} else {
				echo 'Erreur lors de la création de la collection';
			}
		}
	}
}
add_action('init', 'create_new_post_collection');

function getPostImage($id){
	$getImage = wp_get_attachment_image_src( get_post_thumbnail_id($id), 'thumbnail');
	if ($getImage){
		$image['url'] = $getImage[0];
	} else {
		$image['url'] = get_template_directory_uri() . '/images/logo-botascopia@2x.png';
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

// Ajout d'un type de post 'collection'
function custom_post_type() {
	
	$labels = array(
		'name' => 'collection',
		'singular_name' => 'collection',
		'add_new' => 'Ajouter une nouvelle collection',
		'add_new_item' => 'une nouvelle collection',
		'edit_item' => 'Modifier la collection',
		'new_item' => 'Nouvelle collection',
		'view_item' => 'Voir la collection',
		'search_items' => 'Rechercher des collections',
		'not_found' => 'Aucune collection trouvé',
		'not_found_in_trash' => 'Aucune collection trouvé dans la corbeille',
		'parent_item_colon' => 'collection',
		'menu_name' => 'Les collections'
	);
	
	$args = array(
		'labels' => $labels,
		'public' => true,
		'has_archive' => true,
		'publicly_queryable' => true,
		'query_var' => true,
		'rewrite' => array('slug' => 'collection'),
		'capability_type' => 'post',
		'hierarchical' => false,
		'supports' => array(
			'title',
			'editor',
			'author',
			'thumbnail',
			'excerpt',
			'comments',
			'custom-fields'
		),
	);
	
	register_post_type( 'collection', $args );
}

add_action( 'init', 'custom_post_type' );

// Template pour les post de type 'collection'

add_filter( 'template_include', 'collection_template_include' );
function collection_template_include( $template ) {

	if ( get_post_type() == 'collection' ) {
		$new_template = locate_template( array( 'archive-collection.php' ) );
		if ( '' != $new_template ) {
			return $new_template ;
		}
	}

	return $template;
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

// AJout de catégories pour le post type collection
function custom_taxonomy() {
	$args = array(
		'hierarchical'      => true, // Si les catégories doivent être hiérarchiques ou non
		'labels'            => array(
			'name'              => 'Les collections',
			'singular_name'     => 'Collection',
			'search_items'      => 'Rechercher des collections',
			'all_items'         => 'Toutes les collections',
			'parent_item'       => 'Collection parente',
			'parent_item_colon' => 'Collection parente :',
			'edit_item'         => 'Modifier la collection',
			'update_item'       => 'Mettre à jour la collection',
			'add_new_item'      => 'Ajouter une nouvelle collection',
			'new_item_name'     => 'Nom de la nouvelle collection',
			'menu_name'         => 'catégories',
		),
		'show_ui'           => true, // Si l'interface utilisateur de la taxonomie doit être affichée ou non
		'show_admin_column' => true, // Si une colonne doit être affichée dans l'interface d'administration pour cette taxonomie
		'query_var'         => true, // Si la taxonomie doit être utilisée dans les requêtes URL
		'rewrite'           => array( 'slug' => 'collection' ), // Le slug à utiliser pour les URLs
	);
	register_taxonomy( 'category-collection', 'collection', $args );
}
add_action( 'init', 'custom_taxonomy' );

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

// Pour définir le fichier single-collection.php en tant que template des collections
add_filter(
	'template_include',
	function($template) {
		global $wp_query;
		if (1 == $wp_query->found_posts) {
			global $wp_query;
			$type = $wp_query->get('post_type') ?: false;
			$template_type = $type ? 'single-' . $type. '.php' : 'single.php';
			if ( locate_template($template_type) ) {
				return locate_template($template_type);
			} elseif ( $type && locate_template('single.php') ) {
				return locate_template('single.php');
			}
		}
		return $template;
	}
);

function getFiches($id)
{
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
	
	return [$nbFiches, $completed];
}

// Récupère les informations des collections
function getCollectionPosts($status){
	// Posts de type collection
	$args = array(
		'post_type' => 'collection',
		'post_status' => $status,
		'posts_per_page' => -1,
		'order' => 'ASC'
	);
	$collection_query = new WP_Query( $args );
	$posts = [];
	
	if ( $collection_query->have_posts() ) {
		while ( $collection_query->have_posts() ) {
			$collection_query->the_post();
			
			$collectionName = get_the_title();
			$collection_id = get_the_ID();
			$description = get_the_content();
			$image = getPostImage($collection_id);
			$collectionStatus = get_post_status($collection_id);
			$author = get_the_author_meta('ID');
			
			if (is_user_logged_in() && get_user_meta(wp_get_current_user()->ID, 'favorite_collection')) :
				$existingFavorites = get_user_meta(wp_get_current_user()->ID, 'favorite_collection');
				$icone = changeFavIcon($collection_id, $existingFavorites[0]);
			else:
				$icone = ['icon' => 'star-outline', 'color' => 'blanc'];
			endif;

			$fiches = getFiches($collection_id);
			$post = [
				'href' => get_the_guid($collection_id),
				'name' => $collectionName,
				'nbFiches' => $fiches[0],
				'description' => $description,
				'id' => $collection_id,
				'icon' => $icone,
				'image' => $image,
				'status' => $collectionStatus,
				'completed' => $fiches[1],
				'author' => $author
			];
			
			$posts[] = $post;
		}
	}
	
	wp_reset_postdata();
	return $posts;
}

//add_action( 'set_publish', 'publish_post' );

function reserver_fiche() {
	$userId = $_POST['user_id'];
	$ficheId = $_POST['fiche'];
	wp_update_post(array('ID' => $ficheId, 'post_author' => $userId));
	wp_die();
}

add_action( 'wp_ajax_reserver_fiche', 'reserver_fiche' );

function affichageImageFiche($photo){
	if (!empty($photo)){
		$photoId = $photo['ID'];
		$image = wp_get_attachment_image_src( $photoId, 'image-tige' )[0];
        $image = image_downsize($photoId, array(275, 275));
		echo ('<img src="'.esc_url( $image ).'" class="image-tige" height="275px" width="275px">');
	}
}

/*function affichageImageFiche($photo){
    if (!empty($photo)){
        $photoId = $photo['ID'];
        $image = wp_get_attachment_image_src( $photoId, 'image-tige' )[0];
        echo ('<img src="'.esc_url( $image ).'" class="image-tige" height="275px" width="275px">');
    }
}*/

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

// Les pages utilisant le template mes-collections ne sont plus visible dans le menu de navigation si l'utilisateur
// n'est pas connecté
function custom_nav_menu_items($items, $args) {
	// Vérifiez si l'utilisateur est connecté
	if (is_user_logged_in()) {
		// Si l'utilisateur est connecté, affichez tous les éléments de menu normalement
		return $items;
	} else {
		// Sinon, supprimez les pages ayant le template "Mes Collections" du menu de navigation
		foreach ($items as $key => $item) {
			if (get_page_template_slug($item->object_id) == 'mes-collections.php') {
				unset($items[$key]);
			}
		}
		return $items;
	}
}
add_filter('wp_nav_menu_objects', 'custom_nav_menu_items', 10, 2);

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
