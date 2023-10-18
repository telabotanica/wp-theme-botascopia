<?php get_header(); ?>

<div id="primary" class="content-area">
        <main id="main" class="site-main main-accueil" role="main">
			<?php
			$imageId          = get_post_thumbnail_id(get_the_ID());
			if ($imageId) {
				$imageFull = wp_get_attachment_image_src($imageId, 'full');
			} else {
				$imageFull = null;
			}
			
			the_botascopia_module('cover', [
				'title'    => 'Bienvenue sur Botascopia',
				'subtitle' => 'Quand Tela Botanica met à profit son savoir-faire collaboratif et l’Université Paris Saclay sa rigueur scientifique, cela donne Botascopia ! Ce site vous propose des fiches sur les plantes contenant de nombreuses informations sur les plantes de France en licence CC-BY-SA 40.. Vous pouvez les consulter, les télécharger en pdf, les organiser en collections et même les rédiger !',
				'image'    => $imageFull
			]);
			?>
        <?php if ( have_posts() ) :
            // Start the loop.
            while ( have_posts() ) : the_post();

                /*
                 * Include the Post-Format-specific template for the content.
                 * If you want to override this in a child theme, then include a file
                 * called content-___.php (where ___ is the Post Format name) and that will be used instead.
                 */
                //get_template_part( 'content', get_post_format() );
              the_content();

            // End the loop.
            endwhile;

            // Previous/next page navigation.
            the_posts_pagination( array(
                'prev_text'          => __( 'Previous page' ),
                'next_text'          => __( 'Next page' ),
                'before_page_number' => '<span class="meta-nav screen-reader-text">' . __( 'Page' ) . ' </span>',
            ) );

        // If no content, include the "No posts found" template.
        else :
            get_template_part( 'content', 'none' );

        endif;
     
	$securise = (isset($_SERVER['HTTPS'])) ? "https://" : "http://";

        ?>

        </main><!-- .site-main -->
    </div><!-- .content-area -->

<?php get_footer(); ?>
