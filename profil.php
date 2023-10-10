<?php
/*
    Template Name: profil
*/
get_header();

$securise = (isset($_SERVER['HTTPS'])) ? "https://" : "http://";

?>

<div id="primary" class="content-area">

    <main id="main" class="site-main " role="main">
		<?php
		if (is_user_logged_in()) :
			$current_user = wp_get_current_user();
			$userId       = $current_user->ID;
			$current_user_role = $current_user->roles[0];
            
            if (!$current_user->first_name && !$current_user->last_name){
                $nameToShow = $current_user->display_name;
            } else {
                $nameToShow = $current_user->first_name.' '.$current_user->last_name;
            }
   
		the_botascopia_module('cover', [
			'subtitle' => $current_user->roles[0],
			'title' => $nameToShow
		]);
		?>
            <div class="profil-main">
                <div class="profil-buttons">
					<?php
					the_botascopia_module('button', [
						'tag' => 'a',
						'href' => site_url() . '/profil/mes-collections/',
						'title' => 'Mes collections',
						'text' => 'Mes collections',
						'modifiers' => 'green-button',
					]);
					?>
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
                <!--                Affichage Contributeur -->
                <div class="home-author-fiches-container">
                    <?php
					the_botascopia_module('title', [
						'title' => __('Mes fiches', 'botascopia'),
						'level' => 2,
					]);
					
					$args = array(
						'post_type' => 'post',
						'post_status' => 'draft',
						'author' => $current_user->ID,
						'showposts' => -1
					);
					
					$cpt_query = new WP_Query($args);
					// Create cpt loop, with a have_posts() check!
					if ($cpt_query->have_posts()) {
						while ($cpt_query->have_posts()) {
							$cpt_query->the_post();
							if ($current_user->wp_user_level === '1') {
								echo('<div class="home-author-fiches">
                                <div class="profil-collection-name">');
								the_field('nom_scientifique');
                                echo (',<br>');
                                the_field('famille');
								echo '</div><div>';
								the_botascopia_module('button', [
									'tag' => 'button',
									'title' => 'compléter',
									'text' => 'compléter',
									'modifiers' => 'green-button',
									'extra_attributes' => ['onclick' => "window.location.href = '".$securise.$_SERVER['HTTP_HOST'].'/formulaire/?p='.get_the_title()."'"]
								]);
								echo('</div></div>');
							}
						}
					}
                    ?>
                </div>

<!--                Affichage Vérificateur -->
                <?php
                if ($current_user->roles[0] == 'editor') :
                ?>
                <!--                Fiches à vérifier-->
                <div class="home-author-collections-container">
                    <?php
					the_botascopia_module('title', [
						'title' => __('Les fiches à vérifier', 'botascopia'),
						'level' => 2,
					]);
                    
                    $args = array(
						'post_type' => 'post',
						'post_status' => 'pending',
						'showposts' => -1,
						'order'          => 'ASC',
						'orderby'        => 'meta_value',
						'meta_key'       => 'nom_scientifique',
					);
					
					$cpt_query = new WP_Query($args);

					if ($cpt_query->have_posts()) {
						while ($cpt_query->have_posts()) {
							$cpt_query->the_post();
							$name = get_post_meta(get_the_ID(), 'nom_scientifique', true);
							$species = get_post_meta(get_the_ID(), 'famille', true);
							$image = get_the_post_thumbnail_url();
							$id = get_the_ID();
							$ficheTitle = get_the_title();
       
							$editor = get_post_meta($id, 'Editor', true);
       
                            if ((intval($editor) === 0)){
                                echo ('
                                <div class="home-author-collections">
                                    <div class="profil-collection-name">
                                '. $name.',<br>'. $species.'
                                    </div><div>');
                                
                                the_botascopia_module('button', [
                                'tag' => 'a',
                                'href' => home_url() .'/formulaire/?p='.$ficheTitle.'&a=4',
                                'title' => 'Devenir vérificateur de cette fiche',
                                'text' => 'Devenir vérificateur de cette fiche',
                                'modifiers' => 'green-button'
								]);
                                
                                echo ('</div></div>');
							}
						}
					}
                    ?>
                </div>

                <!--                Fiches en cours de vérification-->
                <div class="home-author-collections-container">
						<?php
						the_botascopia_module('title', [
							'title' => __('Fiches dont je suis le vérificateur', 'botascopia'),
							'level' => 2,
						]);
						
						$args = array(
							'post_type' => 'post',
							'post_status' => 'pending',
							'showposts' => -1,
							'order'          => 'ASC',
							'orderby'        => 'meta_value',
							'meta_key'       => 'nom_scientifique',
						);
						
						$cpt_query = new WP_Query($args);

						if ($cpt_query->have_posts()) {
							while ($cpt_query->have_posts()) {
								$cpt_query->the_post();
								$name = get_post_meta(get_the_ID(), 'nom_scientifique', true);
								$species = get_post_meta(get_the_ID(), 'famille', true);
								$image = get_the_post_thumbnail_url();
								$id = get_the_ID();
								$ficheTitle = get_the_title();
								
								$editor = get_post_meta($id, 'Editor', true);
								
								if ((intval($editor) === $userId)){
									echo ('
                                <div class="home-author-collections">
                                    <div class="profil-collection-name">
                                '. $name.',<br>'. $species.'
                                    </div>
                                    <div class="profil-verificateur-buttons"><div>');
									
									the_botascopia_module('button', [
										'tag' => 'a',
                                        'href' => home_url() .'/formulaire/?p='.$ficheTitle,
										'title' => 'Vérifier cette fiche',
										'text' => 'Vérifier cette fiche',
										'modifiers' => 'green-button',
									]);
									
									echo ('</div><div>');
									the_botascopia_module('button', [
										'tag' => 'a',
										'href' => home_url() .'/formulaire/?p='.$ficheTitle.'&a=5',
										'title' => 'Ne plus être vérificateur de cette fiche',
										'text' => 'Ne plus être vérificateur de cette fiche',
										'modifiers' => 'purple-button'
									]);
									echo ('</div></div></div>');
								}
							}
						}
						?>


                    </div>
                <?php
                endif;
                ?>
<!--                Affichage des collections de l'utilisateur-->
                <div class="home-author-collections-container">
                    <?php
					the_botascopia_module('title', [
						'title' => __('Mes collections', 'botascopia'),
						'level' => 2,
					]);
					$posts = getCollectionPosts(['draft', 'pending', 'publish', 'private']);
                    
                    foreach ($posts as $post) {
                        if ($current_user->ID == $post['author']){
                            $href = home_url() . '/collection/creer-une-collection/?collection='.$post['id'].'&edit=true';
                            echo ('
                            <div class="home-author-collections" id="profil-collection-'.$post["id"].'">
                                <div class="profil-collection-name">
                                    '. $post["name"] .'
                                </div><div class="profil-verificateur-buttons">
                                <div>');
                                
                            the_botascopia_module('button', [
									'tag' => 'a',
									'href' => $href,
									'title' => 'modifier la collection',
									'text' => 'Modifier la collection',
									'modifiers' => 'green-button'
								]);
                            echo('</div><div>');
                            
							the_botascopia_module('button', [
								'tag' => 'button',
								'title' => 'supprimer la collection',
								'text' => 'supprimer la collection',
								'modifiers' => 'purple-button delete-collection-button',
								'extra_attributes' => ['id' => 'delete-collection-'.$post['id'], 'data-collection-id' => $post['id']]
							]);
                            
                            echo('</div></div></div>');
                            
                        }
					}
     
                    ?>
                </div>
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
