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
                <?php if ($current_user_role==='administrator'){?>
					<h3>Changez le statut d'un utilisateur</h3>
					<div id="content">
						<table >
							<tr><th>Nom</th><th>Adresse email</th><th>Statut</th><th><th></tr>
								<?php 

									$number=10;
								if (count_users()<=$number){
									$number = count_users();
								}
									$paged = (get_query_var('paged')) ? get_query_var('paged') : 1;
								if($paged==1){
									$offset=0; 
								}else {
									$offset= ($paged-1)*$number;
								}
								$_VERIFICATEUR = "vérificateur";
								$_CONTRIBUTEUR = "contributeur";
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
											$role= $_VERIFICATEUR;
											break;
										case "author":
											$role="auteur";
											break;
										case "contributor":
											$role=$_CONTRIBUTEUR;
											break;
										case "subscriber";
											$role="abonné";
											break;
										default;
											$role="";
											break;
										}
										if($role ===$_CONTRIBUTEUR OR $role === 'auteur' OR $role === 'abonné'){
											echo "<tr><td>$nom</td><td>$email</td><td>$role</td><td><button id='changeToEditor_$cpt' value='$id' class='button green-button'>Devenir $_VERIFICATEUR</button></td></tr>";
										}else if($role===$_VERIFICATEUR){
											echo "<tr><td>$nom</td><td>$email</td><td>$role</td><td><button id='changeToContrib_$cpt' value='$id' class='button green-button'>Devenir $_CONTRIBUTEUR</button></td></tr>";
										}else{
											echo "<tr><td>$nom</td><td>$email</td><td>$role</td><td></td></tr>";
										}
									}
								} else {
									echo "Pas d'utilisateurs";
								} 
								?> 
								</table>
							</div>
							<input id="routeAdmin" value='<?php echo get_rest_url(null, 'modify/role/admin') ?>' class="hidden"/>
							<input id="cpt" value='<?php echo $cpt; ?>' class="hidden"/>
							<?php
							$total_user = $user_query->total_users; 
							$total_pages=ceil($total_user/$number);?>
							<div>

								<?php
									the_botascopia_module('pagination', [
										'page'      => $paged,
										'totalPage' => $total_pages,
										'id'        => 'pagination',
										'href'      => get_the_permalink(),
										'schema'	=> 'paged'
									]);
								?>
							</div>
						
				<?php }elseif($current_user_role==='editor'){?>
					<div id="content">
						<h3>Donnez le statut de rédacteur à un utilisateur</h3>
						<form>
							<label id="label-change">Tapez une adresse email d'un utilisateur</label>
							
							<?php 
								the_botascopia_module('search-box',[
									'placeholder' => 'Rechercher un utilisateur',
									
								]);
							?>
							<input id="routeRedac" value='<?php echo get_rest_url(null, 'modify/role/redac') ?>' class="hidden"/>
							
						</form>
					</div>
				<?php }	?>
                <div>
					<input id="routeCheck" value='<?php echo get_rest_url(null, 'modify/check/user') ?>' class="hidden"/>
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
					
				
            </div>
   		<?php
			else :
				echo('<div><p>Vous devez être connecté pour accéder à cette page.</p></div>');
			
			endif;
		?>
    </main>
	


</div>

<?php
get_footer();
?>