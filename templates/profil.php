<?php
/*
    Template Name: profil
*/
get_header();

$securise = (isset($_SERVER['HTTPS'])) ? "https://" : "http://";

?>

<div id="primary" class="content-area">

    <main id="main" class="site-main site-main-profil" role="main">
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
