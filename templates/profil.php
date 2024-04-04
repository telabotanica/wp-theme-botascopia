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
					<h3>Changez le statut d'un utilisateur</h3>
					<table id="content">
						<tr><th>Nom</th><th>Adresse email</th><th>Statut</th><th><th></tr>
							<?php 

								$number=10;// total no of author to display
							if (count_users()<=$number){
								$number = count_users();
							}
								$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
							if($paged==1){
								$offset=0; 
							}else {
								$offset= ($paged-1)*$number;
							}

							$user_query = new WP_User_Query( array('number' => $number, 'offset' => $offset, 'orderby' => 'display_name' ) );
							if ( ! empty( $user_query->results ) ) {
								foreach ( $user_query->results as $user ) {
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
										echo "<tr><td>$nom</td><td>$email</td><td>$role</td><td><button id='changeToEditor_$cpt' value='$id' class='button green-button'>Devenir rédacteur</button></td></tr>";
									}else if($role==='rédacteur'){
										echo "<tr><td>$nom</td><td>$email</td><td>$role</td><td><button id='changeToContrib_$cpt' value='$id' class='button green-button'>Devenir contributeur</button></td></tr>";
									}else{
										echo "<tr><td>$nom</td><td>$email</td><td>$role</td><td></td></tr>";
									}
								}
							} else {
								echo "Pas d'utilisateurs";
							} 
							?> 
							</table>
							<input id="routeAdmin" value='<?php echo get_rest_url(null, 'modify/role/admin') ?>' class="hidden"/>
							<input id="cpt" value='<?php echo $cpt; ?>' class="hidden"/>
							<?php
							$total_user = $user_query->total_users; 
							$total_pages=ceil($total_user/$number);?>
							<div>

								<?php
									echo paginate_links(array( 
									'base' => get_pagenum_link(1) . '%_%', 
									'format' => '?paged=%#%', 
									'current' => $paged, 
									'total' => $total_pages, 
									'prev_text' => 'Précédents', 
									'next_text' => 'Suivants',
									'type' => 'table',
									)); ?>
							</div>
						
				<?php }elseif($current_user_role==='editor'){?>
					<div id="content">
						<h3>Donnez le statut de rédacteur à un utilisateur</h3>
						<form>
							<label id="label-change">Tapez une adresse email d'un utilisateur</label>
							<input id="email" type="text"/>
							<input id="routeRedac" value='<?php echo get_rest_url(null, 'modify/role/redac') ?>' class="hidden"/>
							<?php 
								the_botascopia_module('button',[
									
									'title' => 'Chercher',
									'text' => 'Chercher',
									'modifiers' => 'green-button',
									'extra_attributes' => ['id' => 'getUser']
								]);
							?>
						</form>
					</div>
				<?php }	?>
            </div>
   		<?php
			else :
				echo('<div><p>Vous devez être connecté pour accéder à cette page</p></div>');
			
			endif;
		?>
    </main>
	


</div>

<?php
get_footer();
?>