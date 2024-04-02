<?php
/*
    Template Name: profil
*/
get_header();

$securise = (isset($_SERVER['HTTPS'])) ? "https://" : "http://";
$users=get_users();


?>

<div id="primary" class="content-area">

    <main id="main" class="site-main site-main-profil" role="main">
		<?
		$cpt=0;
		if (is_user_logged_in()) :
			$current_user = wp_get_current_user();
			$userId       = $current_user->ID;
			$current_user_role = $current_user->roles[0];
			//Pour tester
            /* $user = new WP_User( 13 );
			$user->set_role( 'contributor' );

			$user = new WP_User( 18 );
			$user->set_role( 'contributor' );

			$user = new WP_User( 16 );
			$user->set_role( 'contributor' ); */
            if (!$current_user->first_name && !$current_user->last_name){
                $nameToShow = $current_user->display_name;
            } else {
                $nameToShow = $current_user->first_name.' '.$current_user->last_name;
            }
			
			$imageId = get_post_thumbnail_id(get_the_ID());
			if ($imageId) {
				$imageFull = wp_get_attachment_image_src($imageId, 'full');
			} else {
				$imageFull = null;
			}
			$legende = get_post(get_post_thumbnail_id())->post_excerpt;
			$licence = '';
			
			if ($legende){
				$licence = $legende .', licence CC-BY-SA';
			}
		the_botascopia_module('cover', [
			'subtitle' => $current_user->roles[0],
			'title' => $nameToShow,
			'image' => $imageFull,
			'licence' => $licence
		]);
		?>
            <div class="profil-main">
                <div class="profil-buttons">
					<?php
					the_botascopia_module('button', [
						'tag' => 'a',
						'href' => home_url() . '/profil/mes-collections/',
						'title' => 'Mes collections',
						'text' => 'Mes collections',
						'modifiers' => 'green-button',
					]);
					?>
					
					<?php
					the_botascopia_module('button', [
						'tag' => 'a',
						'href' => home_url() . '/profil/mes-fiches/',
						'title' => 'Mes fiches',
						'text' => 'Mes fiches',
						'modifiers' => 'green-button',
					]);
					?>
                </div>
                
                <div>
					<?php
					the_botascopia_module('button',[
						'tag' => 'button',
						'title' => 'Se déconnecter',
						'text' => 'Se déconnecter',
						'modifiers' => 'purple-button',
						'extra_attributes' => ['onclick' => "window.location.href = '".wp_logout_url( $securise.$_SERVER['HTTP_HOST'] )."'"]
					]);
					?>
                </div>
					
				<?php if ($current_user_role==='administrator'){?>
					<table>
						<tr><th>Nom</th><th>Adresse email</th><th>Statut</th><th><th></tr>
					
					<?php
							foreach($users as $user){
								$id=$user->data->ID;
								$nom=$user->data->display_name;
								$email=$user->data->user_email;
								$role=$user->roles[0];
								$cpt++;
								switch ($role) {
									case "administrator":
										$role="administrateur";
										break;
									case "editor":
										$role= "rédacteur";
										break;
									case "author":
										$role="auteur";
										break;
									case "contributor":
										$role="contributeur";
										break;
									case "subscriber";
										$role="abonné";
										break;
									default;
										$role="";
										break;
								}
								if($role ==='contributeur' OR $role === 'author' OR $role === 'subscriber'){
									echo "<tr><td>$nom</td><td>$email</td><td>$role</td><td><button id='change_$cpt' value='$id'>Devenir rédacteur</button></td></tr>";
								}else{
									echo "<tr><td>$nom</td><td>$email</td><td>$role</td><td></td></tr>";
								}
							
							} 
						
						?>
					</table>
				<?php }elseif($current_user_role==='editor'){?>
					<h3>Donnez le statut de rédacteur à un utilisateur</h3>
					<form>
						<label>Tapez une adresse email d'un utilisateur</label>
						<input id="email" type="text"/>
						<input id="getUser" type="button" value="Chercher">
					</form>

				<?php }	?>
            </div>
   <?php
		else :
			echo('
        <div><p>Vous devez être connecté pour accéder à cette page</p></div>
        ');
		
		endif;
		?>
    </main>
</div>

<?php
get_footer();
?>
<script>
document.addEventListener('DOMContentLoaded', function() {
	var cpt_str='<?php echo $cpt; ?>';
	var cpt = parseInt(cpt_str);
	
	for (i=0;i<=cpt;i++){
		var element = document.querySelector("#change_"+i);
		if (element){
			element.addEventListener("click", function(){changeStatusAdmin(this)}); 
				
			   
		}
		
	}
	document.querySelector("#getUser").addEventListener("click", function(){changeStatusRedac(this)}); 
});

function changeStatusAdmin(e){
	var id = e.value;
	var httpc = new XMLHttpRequest();
	var url = '<?php echo get_rest_url(null, 'modify/role/admin') ?>';
	httpc.open("PUT", url, true);
 	httpc.setRequestHeader("Content-type", "application/json; charset=utf-8");
	var data = {'id':id};
 	httpc.send(JSON.stringify(data));
	httpc.onload = function() {
		if (httpc.readyState == XMLHttpRequest.DONE) {
			// Check the status of the response
			if (httpc.status == 200) {
			// Access the data returned by the server
				var msg = httpc.response;
				if (msg){
					msg = "L'utilisateur est bien devenu rédacteur";
					alert(msg);
					location.reload();
				}
				
			} else {
				alert("Erreur");
			}
		}
	};
				
}

function changeStatusRedac(){
	var email = document.querySelector("#email").value;
	console.log(email);
	var httpc = new XMLHttpRequest();
	var url = '<?php echo get_rest_url(null, 'modify/role/redac') ?>';
	httpc.open("PUT", url, true);
 	httpc.setRequestHeader("Content-type", "application/json; charset=utf-8");
	var data = {'email':email};
 	httpc.send(JSON.stringify(data));
	httpc.onload = function() {
		if (httpc.readyState == XMLHttpRequest.DONE) {
			// Check the status of the response
			if (httpc.status == 200) {
			// Access the data returned by the server
				var msg = httpc.response;
				console.log(msg);
				if (msg=="1"){
					msg="L'utilisateur est bien devenu rédacteur.";
				}else if(msg=='2'){
					msg = "L'utilisateur est déjà rédacteur ou ne peut le devenir.";
				}else if(msg=='3'){
					msg = "L'utilisateur n'existe pas";
				}
				alert(msg);
				location.reload();
			} else {
				alert("Erreur");
			}
		}
	};
}	
</script>