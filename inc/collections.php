<?php
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

// Ajoutez une règle de réécriture pour le slug personnalisé
function custom_rewrite_rule() {
	add_rewrite_rule('^collection/creer-une-collection/?','index.php?custom_collection=create-collection','top');
}
add_action('init', 'custom_rewrite_rule', 10, 0);

// Affichez le formulaire sur la page personnalisée
function display_collection_form() {
	if (get_query_var('custom_collection') === 'create-collection') {
		include(__DIR__ . '/../create-collection.php');
		exit;
	}
}
add_action('template_redirect', 'display_collection_form');

// Permet de créer une nouvelle collection (Post)
function create_new_post_collection() {
	$imageId = null;
	// Vérifier si nous sommes en mode édition
	$edit = isset($_POST['edit']) && $_POST['edit'] === 'true';
	print_r($edit);
	if ($edit == 'true'){
		$edit = true;
	} else {
		$edit = false;
	}
	// Si c'est une mise à jour, récupérer l'ID du post
	$collection_id = $edit && isset($_POST['collection_id']) ? (int)$_POST['collection_id'] : null;
	
//	// Vérification si le nom existe déjà
//	if (!$edit && isset($_POST['post-title']) && get_page_by_title( $_POST['post-title'], 'OBJECT', 'collection', false )){
//		wp_redirect( home_url( '/collection/creer-une-collection/?error=existing_title' ) );
//		exit();
//	} else {
		if (isset($_POST['post-title']) && isset($_POST['post-description'])) {
			$title = sanitize_text_field($_POST['post-title']);
			$description = wp_kses_post($_POST['post-description']);
			
			$linked_posts = isset($_POST['selectedCardIds']) ? json_decode(stripslashes($_POST['selectedCardIds']), true) : array();
			
			// Vérifiez si un fichier a été téléchargé
			if (isset($_FILES['post-thumbnail'])) {
				$file = $_FILES['post-thumbnail'];
				
				// Vérifiez s'il n'y a pas d'erreurs de téléchargement
				if ($file['error'] === 0) {
					$imageId=uploadImage($file);
				} else {
					switch ($file['error']) {
					case UPLOAD_ERR_INI_SIZE:
					case UPLOAD_ERR_FORM_SIZE:
						echo 'Le fichier est trop volumineux.';
						break;
					case UPLOAD_ERR_PARTIAL:
						echo 'Le téléchargement du fichier a été partiellement effectué.';
						break;
					case UPLOAD_ERR_NO_FILE:
						echo 'Aucun fichier n\'a été téléchargé.';
						break;
					default:
						echo 'Erreur inconnue lors du téléchargement du fichier.';
					}
				}
			}
			
			// Créez un post de type 'collection'
			if ($edit){
				$post_id = wp_update_post(
					array(
						'ID'           => $collection_id,
						'post_title'   => $title,
						'post_name'    => $title,
						'post_content' => $description,
						'post_status'  => 'publish',
						'post_type'    => 'collection',
					));
			} else {
				$post_id = wp_insert_post(
					array(
						'post_title'   => $title,
						'post_content' => $description,
						'post_status'  => 'publish',
						'post_type'    => 'collection',
					));
			}
			
			
			if ($post_id) {
				// Associez l'image téléchargée comme image mise en avant de la collection
				if ($imageId){
					set_post_thumbnail($post_id, $imageId);
				}
				
				// Lier les fiiches à la collection
				$old_linked_posts = array();
				if ($edit) {
					$old_connections = p2p_get_connections('collection_to_post', array(
						'from' => $collection_id,
					));
					// Tableau associatif pour stocker l'ID de la connexion en fonction de l'ID du post lié
					$old_connections_mapping = [];
					
					foreach ($old_connections as $old_connection) {
						$old_linked_posts[] = $old_connection->p2p_to;
						// Stocker l'ID de la connexion en fonction de l'ID du post lié
						$old_connections_mapping[$old_connection->p2p_to] = $old_connection->p2p_id;
					}
					
					// Comparez les anciennes et les nouvelles connexions
					$added_connections   = array_diff($linked_posts, $old_linked_posts);
					$removed_connections = array_diff($old_linked_posts, $linked_posts);
					
					$removed_ids = [];
					foreach ($removed_connections as $removed_connection_post_id) {
						// Récupérer l'ID de la connexion à partir du tableau associatif
						$removed_ids[] = $old_connections_mapping[$removed_connection_post_id];
					}
					
					// Ajoutez les nouvelles connexions
					if ( !empty($added_connections)) {
						foreach ($added_connections as $linked_post_id) {
							p2p_create_connection('collection_to_post', array(
								'from' => $collection_id,
								'to'   => $linked_post_id,
								'meta' => array(
									'date' => current_time('mysql')
								)
							));
						}
					}

					// Supprimez les connexions existantes qui ne sont plus nécessaires
					if ( !empty($removed_ids)) {
						foreach ($removed_ids as $p2p_id) {
							p2p_delete_connection($p2p_id);
						}
					}
				} else {
					// Vérifiez si des articles liés ont été sélectionnés
					if (!empty($linked_posts)) {
						// Assurez-vous que Posts 2 Posts est activé
						if (function_exists('p2p_type')) {
							
							// Parcourez les articles liés et établissez la connexion
							foreach ($linked_posts as $linked_post_id) {
								p2p_create_connection('collection_to_post', array(
									'from' => $post_id,
									'to' => $linked_post_id,
									'meta' => array(
										'date' => current_time('mysql')
									)
								));
							}
						}
					}
				}
				
				wp_redirect(get_permalink($post_id));
				exit;
			} else {
				echo 'Erreur lors de la création de la collection';
			}
		}
//	}
}
add_action('init', 'create_new_post_collection');

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

// Les pages utilisant le template mes-collections ne sont plus visible dans le menu de navigation si l'utilisateur n'est pas connecté
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

// Charge les fiches dans le popup de création de collection
function load_popup_content() {
	$data = [];
	$search_term = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';
	
	$args = array(
		'post_type'      => 'post',
		'posts_per_page' => 30,
		'post_status'    => array('publish', 'draft', 'pending', 'private'),
		'order'          => 'ASC',
		'orderby'        => 'meta_value',
		'meta_key'       => 'nom_scientifique',
	);
	
	// If a search term is provided, add it to the query
	if (!empty($search_term)) {
		$args['meta_query'] = array(
			'relation' => 'OR',
			array(
				'key'   => 'nom_scientifique',
				'value' => $search_term,
				'compare' => 'LIKE',
			),
			array(
				'key' => 'famille',
				'value' => $search_term,
				'compare' => 'LIKE'
			)
		);
	}
	
	$query = new WP_Query($args);
	
	if ($query->have_posts()) :
		while ($query->have_posts()) : $query->the_post();
			$post_id   = get_the_ID();
			$post_name = get_post_meta($post_id, 'nom_scientifique', true);
			
			$post_species = get_post_meta(get_the_ID(), 'famille', true);
			$post_imageId = get_post_thumbnail_id($post_id);
			$post_imageFull = wp_get_attachment_image_src($post_imageId, 'full');
			
			if($post_imageFull){
				$post_imageFull = $post_imageFull[0];
			} else {
				$post_imageFull = get_template_directory_uri() . '/images/logo-botascopia@2x.png';
			}
			
			$data[] = [
				'id'      => $post_id,
				'name'    => $post_name,
				'species' => $post_species,
				'image'   => $post_imageFull, // Utilisez [0] pour obtenir l'URL de l'image
			];
		endwhile;
		wp_reset_postdata();
	endif;
	
	$json_data = json_encode($data);
	
	// Envoyez les données JSON en tant que réponse à la requête Ajax
	echo $json_data;
	
	die();
}
add_action('wp_ajax_load_popup_content', 'load_popup_content');

// Charge les fiches dans le popup de création de collection
function load_collection_content() {
	$data = [];
	$search_term = isset($_GET['search']) ? sanitize_text_field($_GET['search']) : '';
	$post_id = isset($_GET['post']) ? sanitize_text_field($_GET['post']) : '';
	if (empty($search_term) && $post_id){
		$url = get_the_permalink($post_id);
		echo $url;
		die();
	}
	
	if (is_user_logged_in() && get_user_meta(wp_get_current_user()->ID, 'favorite_fiche')):
		$ficheFavorites = get_user_meta(wp_get_current_user()->ID, 'favorite_fiche');
	endif;
	
	if (is_user_logged_in()) :
		$current_user = wp_get_current_user();
		$current_user_id = $current_user->ID;
		$current_user_role = $current_user->roles[0];
	endif;
	
	$args = array(
		'connected_type' => 'collection_to_post',
		'connected_items' => $post_id,
		'nopaging' => true,
		'posts_per_page' => 30,
		'post_status'    => array('publish', 'draft', 'pending', 'private'),
		'order'          => 'ASC',
		'orderby'        => 'meta_value',
		'meta_key'       => 'nom_scientifique',
	);
	
	// If a search term is provided, add it to the query
	if (!empty($search_term)) {
		$args['meta_query'] = array(
			'relation' => 'OR',
			array(
				'key'   => 'nom_scientifique',
				'value' => $search_term,
				'compare' => 'LIKE',
			),
			array(
				'key' => 'famille',
				'value' => $search_term,
				'compare' => 'LIKE'
			)
		);
	}

	$connected_posts = new WP_Query($args);
	
	if ($connected_posts->have_posts()) :
		while ($connected_posts->have_posts()) : $connected_posts->the_post();
			// Afficher ici les informations sur chaque article de type "post" connecté
			$name = get_post_meta(get_the_ID(), 'nom_scientifique', true);
			$species = get_post_meta(get_the_ID(), 'famille', true);
			$image = get_the_post_thumbnail_url();
			$id = trim(get_the_ID());
			$ficheTitle = get_the_title();
			$status = get_post_status();
			
			if (!$image) :
				$image =
					get_template_directory_uri() . '/images/logo-botascopia@2x.png';
			endif;
			
			$fiche_author_id = get_post_field('post_author', $id);
			$fiche_author_info = get_userdata($fiche_author_id);
			$fiche_author_roles = $fiche_author_info->roles[0];
			
			if (is_user_logged_in() && get_user_meta(wp_get_current_user()->ID, 'favorite_fiche') && ($key = array_search($id, $ficheFavorites[0])) !== false) :
				$icone = ['icon' => 'star', 'color' => 'icon-color-blanc'];
			else:
				$icone = ['icon' => 'star-outline', 'color' => 'icon-color-blanc'];
			endif;
			
			switch ($status):
			case 'draft':
				$fichesClasses = 'card-status-bandeau main-status-incomplete';
				$ficheStatusText = 'à completer';
				break;
			case 'pending':
				$fichesClasses = 'card-status-bandeau main-status-complete';
				$ficheStatusText = 'en cours...';
				break;
			case 'publish':
				$fichesClasses = 'card-status-bandeau main-status-complete';
				$ficheStatusText = 'complet';
				break;
			default:
				$fichesClasses = '';
				$ficheStatusText = '';
			endswitch;
			
			// Cas des fiches réservées (toujours en draft)
			if ($fiche_author_roles == 'contributor') {
				if ($status == 'draft'){
					$fichesClasses = 'card-status-bandeau main-status-incomplete';
					$ficheStatusText = 'en cours...';
				} elseif ($status == 'pending'){
					$editor = get_post_meta($id, 'Editor', true);
					
					if ($editor == $current_user_id || $editor == 0){
						$fichesClasses = 'card-status-bandeau main-status-complete';
						$ficheStatusText = 'A vérifier';
					} else {
						$fichesClasses = 'card-status-bandeau main-status-complete';
						$ficheStatusText = 'En cours de vérification';
					}
				}
			}
			
			// Si la fiche n'appartient pas à un contributeur, un contributeur peut en prendre l'ownership si celle-ci est en draft
			if (is_user_logged_in() && $current_user_role == 'contributor' && $status == 'draft' &&
				$current_user_id != $fiche_author_id && $fiche_author_roles != 'contributor') {
				$popupClass = 'fiche-non-reserve';
			} else {
				$popupClass = '';
			}
			
			// Différent lien selon le statut de la fiche et l'utilisateur
			if (is_user_logged_in()) {
				if (($current_user_role == 'contributor' && $status == 'draft' &&
						$current_user_id == $fiche_author_id) ||
					($current_user_role == 'editor' && $status == 'pending')) {
					$href = '/formulaire/?p='.get_the_title();
				} elseif ($status == 'publish' || $current_user_role == 'administrator' ) {
					$href = get_permalink();
				} else {
					$href = '#';
				}
			} elseif ($status == 'publish') {
				$href = get_permalink();
			} else {
				$href = '#';
			}
			
			$data[] = [
				'href'             => $href,
				'image'            => $image,
				'name'             => $name,
				'species'          => $species,
				'icon'             => $icone,
				'popup'            => $popupClass,
				'id'               => 'fiche-'.$id,
				'data-user-id'     => $current_user_id,
				'data-fiche-id'    => $id,
				'data-fiche-name'  => $name,
				'data-fiche-url'   => get_permalink(),
				'data-fiche-title' => $ficheTitle,
				'fichesClasses'    => $fichesClasses,
				'ficheStatusText'  => $ficheStatusText
				];
		endwhile;
		wp_reset_postdata();
	endif;
//	print_r($data);
	$json_data = json_encode($data);

	// Envoyez les données JSON en tant que réponse à la requête Ajax
	echo $json_data;
	
	die();
}
add_action('wp_ajax_load_collection_content', 'load_collection_content');

function loadFiches($post_id, $paged){
	if (is_user_logged_in() && get_user_meta(wp_get_current_user()->ID, 'favorite_fiche')):
		$ficheFavorites = get_user_meta(wp_get_current_user()->ID, 'favorite_fiche');
	endif;
	
	if (is_user_logged_in()) :
		$current_user = wp_get_current_user();
		$current_user_id = $current_user->ID;
		$current_user_role = $current_user->roles[0];
	endif;
	
	$offset = ($paged - 1 ) * 10;
	$connected_posts = new WP_Query(
		array(
			'connected_type' => 'collection_to_post',
			'connected_items' => $post_id,
			'posts_per_page' => 10,
			'paged' => $paged,
			'offset' => $offset,
			'post_status' => 'any',
			'order'          => 'ASC',
			'orderby'        => 'meta_value',
			'meta_key'       => 'nom_scientifique',
		));

	if ($connected_posts->have_posts()) :
		while ($connected_posts->have_posts()) : $connected_posts->the_post();
			// Afficher ici les informations sur chaque article de type "post" connecté
			$name = get_post_meta(get_the_ID(), 'nom_scientifique', true);
			$species = get_post_meta(get_the_ID(), 'famille', true);
			$image = get_the_post_thumbnail_url();
			$id = get_the_ID();
			$ficheTitle = get_the_title();
			$status = get_post_status();
			
			$fiche_author_id = get_post_field('post_author', $id);
			$fiche_author_info = get_userdata($fiche_author_id);
			$fiche_author_roles = $fiche_author_info->roles[0];
			
			if (is_user_logged_in() && get_user_meta(wp_get_current_user()->ID, 'favorite_fiche') && ($key = array_search($id, $ficheFavorites[0]))
				!==
				false) :
				$icone = ['icon' => 'star', 'color' => 'blanc'];
			else:
				$icone = ['icon' => 'star-outline', 'color' => 'blanc'];
			endif;
			
			switch ($status):
			case 'draft':
				$fichesClasses = 'card-status-bandeau main-status-incomplete';
				$ficheStatusText = 'à completer';
				break;
			case 'pending':
				$fichesClasses = 'card-status-bandeau main-status-complete';
				$ficheStatusText = 'en cours...';
				break;
			case 'publish':
				$fichesClasses = 'card-status-bandeau main-status-complete';
				$ficheStatusText = 'complet';
				break;
			default:
				$fichesClasses = '';
				$ficheStatusText = '';
			endswitch;
			
			// Cas des fiches réservées (toujours en draft)
			if ($fiche_author_roles == 'contributor') {
				if ($status == 'draft'){
					$fichesClasses = 'card-status-bandeau main-status-incomplete';
					$ficheStatusText = 'en cours...';
				} elseif ($status == 'pending'){
					$editor = get_post_meta($id, 'Editor', true);
					
					if ($editor == $current_user_id || $editor == 0){
						$fichesClasses = 'card-status-bandeau main-status-complete';
						$ficheStatusText = 'A vérifier';
					} else {
						$fichesClasses = 'card-status-bandeau main-status-complete';
						$ficheStatusText = 'En cours de vérification';
					}
				}
			}
			
			// Si la fiche n'appartient pas à un contributeur, un contributeur peut en prendre
			// l'ownership si celle-ci est en draft
			if (is_user_logged_in() && $current_user_role == 'contributor' && $status == 'draft' &&
				$current_user_id != $fiche_author_id && $fiche_author_roles != 'contributor') {
				$popupClass = 'fiche-non-reserve';
			} else {
				$popupClass = '';
			}
			
			// Différent lien selon le statut de la fiche et l'utilisateur
			if (is_user_logged_in()) {
				if (($current_user_role == 'contributor' && $status == 'draft' &&
						$current_user_id == $fiche_author_id) ||
					($current_user_role == 'editor' && $status == 'pending')) {
					$href = '/formulaire/?p='.get_the_title();
				} elseif ($status == 'publish' || $current_user_role == 'administrator' ) {
					$href = get_permalink();
				} else {
					$href = '#';
				}
			} elseif ($status == 'publish') {
				$href = get_permalink();
			} else {
				$href = '#';
			}
			
			echo('
				<div class="fiche-status">
					<div class="'.$fichesClasses.'">
						'.$ficheStatusText.'
					</div>
				');
			
			the_botascopia_module('card-fiche', [
				'href' => $href,
				'image' => $image,
				'name' => $name,
				'species' => $species,
				'icon' => $icone,
				'popup' => $popupClass,
				'id' => 'fiche-'.$id,
				'extra_attributes' => ['data-user-id' => $current_user_id, 'data-fiche-id' => $id, 'data-fiche-name' => $name, 'data-fiche-url' => get_permalink(), 'data-fiche-title' => $ficheTitle]
			]);
			echo '</div>';
		endwhile;
	endif;
	wp_reset_postdata();
}