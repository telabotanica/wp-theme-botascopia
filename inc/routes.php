<?php
function modifyRoleAdmin($data) {
	
	$params=$data->get_params();
	$id=$params['id'];
	$mode = $params['mode'];
	if ($mode===1){
		$user = new WP_User( $id );
		$user->set_role( 'editor' );
		getResponse(1,$user->data);

	}elseif($mode===2){
		$user = new WP_User( $id );
		$user->set_role( 'contributor' );
		getResponse($mode,$user->data);

	}else{
		getResponse(3,null);
	}
}

function getResponse($mode,$user){
	
	$resp=['id'=>$user->ID,'nom'=>$user->display_name,'email'=>$user->user_email,'mode'=>$mode];
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($resp);
	
}

//Exécute la fonction précédente lors de l'appel à la route /modify/role/admin
//Permet de modifier le statut d'un utilisateur en rédacteur lorsque l'utlisateur connecté est admin
add_action( 'rest_api_init', function () {
	register_rest_route( 'modify', '/role/admin', array(
	  'methods' => 'put',
	  'callback' => 'modifyRoleAdmin',
	  
	) );
} );

function modifyRoleRedacteur($data) {
	
	$params=$data->get_params();
	$email=$params['email'];
	
	
	if ( email_exists( $email ) ){  
        $user = get_user_by("email", $email);
        $userId = $user->ID;
		$role = get_userdata($userId)->roles[0];
		if ($role === 'contributor' OR $role === 'author' OR $role === 'subscriber'){
			$user->set_role('editor');
			getResponse(1,$user);
			
		}else{
			getResponse(2,$user);
		}
    }else{
		getResponse(3,null);
    }
}

//Exécute la fonction précédente lors de l'appel à la route /modify/role/redac
//Permet de modifier le statut d'un utilisateur en rédacteur lorsque l'utlisateur connecté est lui-même rédacteur
add_action( 'rest_api_init', function () {
	register_rest_route( 'modify', '/role/redac', array(
	  'methods' => 'put',
	  'callback' => 'modifyRoleRedacteur',
	  
	) );
} );

function checkUser($data) {
	
	$email="";
	$params=$data->get_params();
	$id=$params['id'];
	$mode=$params['mode'];
	
	if ($id!==0){
		$user = new WP_User( $id );
		$email = $user->data->user_email;
	}else{
		$email=$params['email'];
	}
	if ( email_exists( $email ) ){  
        $user = get_user_by("email", $email);
        $userId = $user->ID;
		$role = get_userdata($userId)->roles[0];
		if ($role === 'contributor' OR $role === 'author' OR $role === 'subscriber'){
			if ($mode===2){
				getResponse(4,$user);
				return;
			}
			getResponse(1,$user);

		}else if($role==='editor'){
			if ($mode===1 || $mode===0){
				getResponse(4,$user);
				return;
			}
			getResponse(2,$user);
		}else{
			getResponse(4,$user);
		}
    }else{
		getResponse(3,null);
    }
}

//Exécute la fonction précédente lors de l'appel à la route /modify/check/user
//Permet de modifier le statut d'un utilisateur avant modification
add_action( 'rest_api_init', function () {
	register_rest_route( 'modify', '/check/user', array(
	  'methods' => 'put',
	  'callback' => 'checkUser',
	  
	) );
} );

function deleteFicheFromCollection($data) {
	global $wpdb;

	$email="";
	$params=$data->get_params();
	$post_id=$params['post_id'];
    $collection_id=$params['collection_id'];
	$mode = 0;
	if (!empty($post_id) AND !empty($collection_id)){
        $mode = 1;
        $nom = get_field("nom_scientifique",$post_id);
        $wpdb->delete( "wp_p2p", array( 'p2p_from' => $collection_id,'p2p_to'=>$post_id ) );

        return getResponseCollections($mode,$nom);
    }else{
        return getResponseCollections($mode);
    }
}

//Exécute la fonction précédente lors de l'appel à la route /delete/collection/fiche
//Permet de supprimer une fiche d'une collection
add_action( 'rest_api_init', function () {
	register_rest_route( 'delete', '/collection/fiche', array(
	  'methods' => 'delete',
	  'callback' => 'deleteFicheFromCollection',
	  
	) );
} );

function getResponseCollections($mode,$nom=null){
	$resp=['nom'=>$nom,'mode'=>$mode];
	header('Content-Type: application/json; charset=utf-8');
	echo json_encode($resp);
}