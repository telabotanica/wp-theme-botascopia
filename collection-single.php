<?php
/*
    Template Name: Collection-single
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
		
		the_botascopia_module('cover', [
			'subtitle' => '',
			'title' => ''
		]);
		?>
        <div class="collection-main">
            <div class="left-div">
                <div class="single-collection-title">
                    <?php the_botascopia_module('title',[
						'title' => __('Nom de la collection', 'botascopia'),
						'level' => 1,
					]);
                    ?>
                </div>
                
                <div class="single-collection-buttons">
                    <?php the_botascopia_module('button',[
						'tag' => 'a',
						'href' => '#',
						'title' => 'Téléchargez',
						'text' => 'Téléchargez',
						'modifiers' => 'green-button',
                    ]);?>

					<?php if (is_user_logged_in()) :
                        //TODO changer le bouton favoris si dans favoris ou pas
                     endif;
                    ?>
					<?php the_botascopia_module('button',[
						'tag' => 'a',
						'href' => '#',
						'title' => 'Favoris',
						'text' => 'Favoris',
						'modifiers' => 'green-button outline',
						'icon_after' => ['icon' => 'star-outline', 'color'=>'vert-clair'],
					]);?>
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
                    <div class="single-collection-detail">Composée de x fiches</div>
                    <div class="single-collection-detail">Publié le x septembre 2022</div>
                    <div class="single-collection-detail">Par Martin Dupond</div>
                </div>
                
        </div>
        <div class="right-div">
			<?php
			the_botascopia_module('breadcrumbs');
			?>
            
            <div>
                <?php the_botascopia_module('search-box');?>
            </div>
            
            <div>
                <?php the_botascopia_module('title',[
					'title' => __('Nom de la collection', 'botascopia'),
                    'level' => 3
                ]);?>
            </div>
            
            <div>
                Sed do eiusmod tempor incididunt ut labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco laboris nisi ut aliquip ex ea commodo consequat. Duis aute irure dolor in reprehenderit in voluptate velit esse cillum dolore eu fugiat nulla pariatur.
            </div>
            
            <div class="display-fiches-cards-items">
<!--                TODO Boucle avec chaque card d'espèce-->
                <?php
                    $cat_args = array(
                        'hide_empty' => 0,
                        'order' => 'ASC',
//                        'category_parent' => get_cat_ID( 'collection' ),
						'category_name' => 'Bota2',
                    );
				query_posts( $cat_args );

				// Boucle pour afficher les articles
				if ( have_posts() ) :
					while ( have_posts() ) : the_post();
						$name = get_post_meta( get_the_ID(), 'nom_scientifique', true );
                        $species = get_post_meta( get_the_ID(), 'famille', true );
                        $image = get_the_post_thumbnail_url();
      
						the_botascopia_module('card-fiche', [
                            'href' => get_permalink(),
							'image' => $image,
							'name' => $name,
							'species' => $species,
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

