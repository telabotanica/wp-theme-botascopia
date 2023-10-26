<?php
/*
    Template Name: collection single
*/
?>
<?php
get_header();
?>
<div id="primary" class="content-area">
	<div class="bg-fill">
	
	</div>
	<main id="main" class="site-main " role="main">
		
		<?php
		$post = get_queried_object();
		if (is_user_logged_in() && get_user_meta(wp_get_current_user()->ID, 'favorite_collection')) :
			$collectionFavorites = get_user_meta(wp_get_current_user()->ID, 'favorite_collection');
		endif;
		if (is_user_logged_in() && get_user_meta(wp_get_current_user()->ID, 'favorite_fiche')):
			$ficheFavorites = get_user_meta(wp_get_current_user()->ID, 'favorite_fiche');
		endif;
		$post_id = $post->ID;
		$post_author = get_the_author_meta('display_name', $post->post_author);
		
		$date = $post->post_date;
		setlocale(LC_TIME, 'fr_FR.utf8');
		$post_date = strftime('%e %B %Y', strtotime($date));
		
		$nbFiches = getFiches($post_id)[0];
		$completed = getFiches($post_id)[1];
		$image = getPostImage($post_id);
		
		$imageId = get_post_thumbnail_id($post_id);
		$imageFull = wp_get_attachment_image_src($imageId, 'full');
		
		$legende = get_post(get_post_thumbnail_id())->post_excerpt;
		$licence = '';
		
		if ($legende){
			$licence = $legende .', licence CC-BY-SA';
		}
		
		if (is_user_logged_in()) :
			$current_user = wp_get_current_user();
			$current_user_id = $current_user->ID;
			$current_user_role = $current_user->roles[0];
		endif;
		
		the_botascopia_module('cover', [
			'subtitle' => '',
			'title' => '',
			'image' => $imageFull,
			'licence' => $licence
		]);
		
		if ($post->post_status == 'private') {
			$classes = 'main-status-bandeau main-status-incomplete';
			$mainStatusText = 'Collection privée';
		} elseif (( !$completed || $post->post_status != 'publish')) {
			$classes = 'main-status-bandeau main-status-incomplete';
			$mainStatusText = 'Collection non complète';
		} else {
			$classes = 'main-status-bandeau main-status-complete';
			$mainStatusText = 'Collection complète';
		}
		
		echo('
            <div class="'.$classes.'">
                '.$mainStatusText.'
            </div>
        ');
		
		if (is_user_logged_in()):
			$current_user = wp_get_current_user();
			$userId = $current_user->ID;
		else:
			$userId = '';
		endif;
		
		?>
		<div class="collection-main">
			<div class="left-div">
				<div class="single-collection-title">
					<?php the_botascopia_module('title', [
						'title' => __($post->post_title, 'botascopia'),
						'level' => 1,
						'modifiers' => 'collection-title'
					]);
					?>
				</div>
				<?php
				?>
				<div class="single-collection-buttons" id="collection-<?php echo $post_id ?>"
					 data-user-id="<?php echo $userId ?>"
					 data-category-id="<?php echo $post_id ?>">
					
					<?php the_botascopia_module('button', [
						'tag' => 'a',
						'href' => '#',
						'title' => 'Téléchargez',
						'text' => 'Téléchargez',
						'modifiers' => 'green-button',
					]); ?>
					
					<?php if (is_user_logged_in() && get_user_meta(wp_get_current_user()->ID, 'favorite_collection') && ($key = array_search($post_id, $collectionFavorites[0])) !== false) :
						//changer le bouton favoris si collection dans favoris ou pas
						$icone = ['icon' => 'star', 'color' => 'blanc'];
						$modifiers = 'green-button';
					else:
						$icone = ['icon' => 'star-outline', 'color' => 'vert-clair'];
						$modifiers = 'green-button outline';
					endif;
					
					the_botascopia_module('button', [
						'tag' => 'a',
						'href' => '#',
						'title' => 'Favoris',
						'text' => 'Favoris',
						'modifiers' => $modifiers,
						'icon_after' => $icone,
						'extra_attributes' => ['id' => 'fav-'.$post_id]
					]);
					
					?>
				</div>
				<div class="single-collection-export-format">
					Formats : PDF (60Mo)
				</div>
				
				<a class="return-button return-button-collection" href="#">
					<?php the_botascopia_module('icon', [
						'icon' => 'arrow-left'
					]); ?>
					<span>RETOUR</span>
				</a>
				
				<div class="single-collection-details">
					<div class="single-collection-detail">Composée de <?php echo $nbFiches ?> fiches</div>
					<div class="single-collection-detail">Publié le <?php echo $post_date ?></div>
					<div class="single-collection-detail">Par <?php echo $post_author ?></div>
				</div>
			
			</div>
			<div class="right-div">
				<?php
				the_botascopia_module('breadcrumbs');
				?>
				
				<div>
					<?php the_botascopia_module('search-box', [
						'placeholder' => 'Rechercher une fiche',
						'id' => 'single-collection-search',
                        'post' => $post_id
					]); ?>
				</div>
				
				<div class="single-collection-title-right">
					<?php the_botascopia_module('title', [
						'title' => __('Description', 'botascopia'),
						'level' => 3
					]); ?>
				</div>
				
				<div>
					<?php echo $post->post_content ?>
				</div>
				
				<div id="single-collection-fiches-container" class="display-fiches-cards-items">
					<?php
					$page = $_GET['t'] ?? '1';
                    $prevPage = $page - 1;
                    $nextPage = $page + 1;
                    
                    $totalPage = ceil($nbFiches / 10);
                    $paged = $page;
                    
                    if ($totalPage == 0){
                        $totalPage = 1;
                    }
                    
                   loadFiches($post_id, $paged);

					?>
				</div>
				
				<?php
				the_botascopia_module('pagination', [
					'page'      => $paged,
					'totalPage' => $totalPage,
					'id'        => 'single-collection-pagination',
					'href'      => get_the_permalink()
				]);
				?>

	</main><!-- .site-main -->
</div><!-- .content-area -->

<?php
get_footer();
?>

