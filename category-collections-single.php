<?php
/*
    Template Name: category-collection-single
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
		$collection = get_queried_object();
		$collectionFavorites = get_user_meta(wp_get_current_user()->ID, 'favorite_collection');
		$ficheFavorites = get_user_meta(wp_get_current_user()->ID, 'favorite_fiche');
		if (is_user_logged_in()):
			$current_user = wp_get_current_user();
            $userId = $current_user->ID;
        else:
            $userId = '';
        endif;
		
		the_botascopia_module('cover', [
			'subtitle' => '',
			'title' => ''
		]);
		?>
        <div class="collection-main">
            <div class="left-div">
                <div class="single-collection-title">
                    <?php the_botascopia_module('title',[
						'title' => __($collection->name, 'botascopia'),
						'level' => 1,
					]);
                    ?>
                </div>
                <?php
                ?>
                <div class="single-collection-buttons" id="collection-<?php echo $collection->term_id ?>"
                     data-user-id="<?php echo $userId ?>"
                     data-category-id="<?php echo $collection->term_id ?>">
                    
                    <?php the_botascopia_module('button',[
						'tag' => 'a',
						'href' => '#',
						'title' => 'Téléchargez',
						'text' => 'Téléchargez',
						'modifiers' => 'green-button',
                    ]);?>

					<?php if (is_user_logged_in() && ($key = array_search($collection->term_id,
                                                                          $collectionFavorites[0]))
                        !==
                        false) :
                        //changer le bouton favoris si collection dans favoris ou pas
                        $icone = ['icon' => 'star', 'color'=>'blanc'];
                        $modifiers = 'green-button';
                    else:
						$icone = ['icon' => 'star-outline', 'color'=>'vert-clair'];
						$modifiers = 'green-button outline';
					endif;
     
					the_botascopia_module('button',[
						'tag' => 'a',
						'href' => '#',
						'title' => 'Favoris',
						'text' => 'Favoris',
						'modifiers' => $modifiers,
						'icon_after' => $icone,
						'extra_attributes' => ['id' => 'fav-'. $collection->term_id]
					]);
                    
                    ?>
                </div>
                <div class="single-collection-export-format">
                    Formats : PDF (60Mo)
                </div>
                
                <div class="single-collection-return">
                    <?php the_botascopia_module('icon',[
                            'icon'=> 'arrow-left'
                    ]); ?>
                    <span>RETOUR</span>
                </div>
                
                <div class="single-collection-details">
                    <div class="single-collection-detail">Composée de <?php echo $collection->count ?> fiches</div>
                    <div class="single-collection-detail">Publié le x septembre 2022</div>
                    <div class="single-collection-detail">Par xxx</div>
                </div>
                
        </div>
        <div class="right-div">
			<?php
			the_botascopia_module('breadcrumbs');
			?>
            
            <div>
                <?php the_botascopia_module('search-box');?>
            </div>
            
            <div class="single-collection-title-right">
                <?php the_botascopia_module('title',[
					'title' => __($collection->name, 'botascopia'),
                    'level' => 3
                ]);?>
            </div>
            
            <div>
                <?php echo $collection->description ?>
            </div>
            
            <div class="display-fiches-cards-items">
                <?php
                    $cat_args = array(
                        'hide_empty' => 0,
                        'order' => 'ASC',
						'cat' => $collection->term_id,
                    );
				query_posts( $cat_args );

				// Boucle pour afficher les articles
				if ( have_posts() ) :
					while ( have_posts() ) : the_post();
						$name = get_post_meta( get_the_ID(), 'nom_scientifique', true );
                        $species = get_post_meta( get_the_ID(), 'famille', true );
                        $image = get_the_post_thumbnail_url();
                        $id = get_the_ID();
                        
				if (is_user_logged_in() && ($key = array_search($id,
																$ficheFavorites[0]))
					!==
					false) :
					$icone = ['icon' => 'star', 'color' => 'blanc'];
                else:
					$icone = ['icon' => 'star-outline', 'color' => 'blanc'];
                endif;
                
						the_botascopia_module('card-fiche', [
                            'href' => get_permalink(),
							'image' => $image,
							'name' => $name,
							'species' => $species,
							'icon' => $icone,
							'extra_attributes' => ['id' => 'fiche-'.$id, 'data-user-id'=> $userId, 'data-fiche-id'=>$id]
						]);
                    
					endwhile;
				else :
					echo 'Aucun article trouvé.';
				endif;
				// Réinitialiser la requête
				wp_reset_query();
                
                ?>
            </div>

        </div>
    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php
get_footer();
?>
