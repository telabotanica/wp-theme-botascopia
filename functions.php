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
	$post = get_post($post_id);
	$category_name = $post->post_title;
	$parent_term = term_exists('collections', 'category');
	$parent_term_id = $parent_term['term_id'];
	$category = wp_insert_term($category_name, 'category', array('parent' => $parent_term_id));
	if (!is_wp_error($category)) {
		wp_set_object_terms($post_id, $category_name, 'category', true);
		$category_link = get_term_link($category['term_id']);
		wp_redirect($category_link);
		exit;
	}
}
add_action('wp_insert_post', 'create_category_from_post_name');

// Permet de créer une nouvelle collection (Post)
function create_new_post() {
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
				'post_description' => $description,
//			'post_status' => 'publish',
				'post_category' => array( get_cat_ID( $title ) ),
			);
			$post_id = wp_insert_post( $my_post );
			create_category_from_post_name($post_id);
			
			if ($post_id) {
				wp_redirect( get_permalink( $post_id ) );
				exit;
			} else {
				echo 'Erreur lors de la création de la collection';
			}
		}
	}
}
add_action('init', 'create_new_post');