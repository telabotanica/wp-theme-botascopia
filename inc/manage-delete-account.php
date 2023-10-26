<?php

// Creates deleted_tb_user role
add_role( 'deleted_tb_user', __('Ex-telabotaniste', 'telabotanica' ), array('read' => false));

/**
 * retrieve user id with 'deleted_tb_user' role
 *
 * @return integer
 */

function retrieve_deleted_tb_user_id() {
	$deleted_tb_user = get_user_by('login', 'Ex-telabotaniste');
	if (!$deleted_tb_user){
		$user_data = array(
			'user_login' => 'Ex-telabotaniste',
			'user_pass' => wp_generate_password(),
			'role' => 'deleted_tb_user',
		);
		$user_id = wp_insert_user($user_data);
	} else {
		$user_id = $deleted_tb_user->ID;
	}
	
	return $user_id;
}
// Lancement de la fonction lors de l'activation du thème
add_action('after_setup_theme', 'retrieve_deleted_tb_user_id');
/*
// Fonction pour réaffecter les articles lors de la suppression d'un utilisateur
function reassign_posts_on_user_deletion($user_id) {
	// Récupérez l'ID de l'utilisateur "Ex-telabotaniste"
	$deleted_tb_user_id = retrieve_deleted_tb_user_id();
	
	if ($deleted_tb_user_id) {
		// Récupérez toutes les publications de l'utilisateur supprimé
		$posts_to_reassign = get_posts(array(
										   'post_type' => ['post', 'collection'],
										   'author' => $user_id,
										   'posts_per_page' => -1
									   ));
		
		// Réaffectez les publications à l'utilisateur "Ex-telabotaniste"
		foreach ($posts_to_reassign as $post) {
			wp_update_post(array(
							   'ID' => $post->ID,
							   'post_author' => $deleted_tb_user_id
						   ));
		}
	}
}

// Lancer la réaffectation des articles lors de la suppression d'un utilisateur
add_action('delete_user', 'reassign_posts_on_user_deletion');
*/