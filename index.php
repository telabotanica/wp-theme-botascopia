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
			$search = $_GET['q'] ?? '';
            $legende = get_post(get_post_thumbnail_id())->post_excerpt;
            $licence = '';
			$description_page = null;
			$description_page = get_post_meta(get_the_ID(), 'description_page', true);
			
			if ($legende){
				$licence = $legende .', licence CC-BY-SA';
			}

			the_botascopia_module('cover', [
				'title'    => 'Bienvenue sur Botascopia',
				'subtitle' => $description_page ?? esc_html($description_page),
				'image'    => $imageFull,
				'search' => [
					'placeholder'   => __('Rechercher une collection ...', 'botascopia'),
					'value' => $search,
					'pageurl' => 'collection?q',
					'id' => 'search-home'
				],
				'licence' => $licence
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
        <input id="path-home" class="hidden" value='<?php echo get_site_url(); ?>'/>
        </main><!-- .site-main -->
    </div><!-- .content-area -->

<?php get_footer(); ?>
<script src="<?php echo (get_template_directory_uri() . '/assets/scripts/home.js'); ?>" ></script>
