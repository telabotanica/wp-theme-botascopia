<?php get_header(); ?>
      <nav id="navigation">
<?php
/*wp_nav_menu(
  array(
    'theme_location' => 'main-menu',
    'menu_id' => 'primary-menu',
 )
);*/
?>
</nav>
<div id="primary" class="content-area">
        <main id="main" class="site-main" role="main">

        <?php if ( have_posts() ) : ?>

            <?php if ( is_home() && ! is_front_page() ) : ?>
                <header>
                    <h1 class="page-title screen-reader-text"><?php single_post_title(); ?></h1>
                </header>
            <?php endif; ?>

            <?php
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
	
    if ( is_user_logged_in() ) {
        $current_user = wp_get_current_user();
        echo('<div class="home-author-fiches">');
        the_botascopia_module('title', [
            'title' => __('Mes fiches', 'botascopia'),
            'level' => 2,
        ]);
        echo('</div>');
        
        $args = array(
            'post_type' => 'post',
            'post_status' => 'draft',
            'author' => $current_user->ID,
            'showposts' => 10
        );
        
        $cpt_query = new WP_Query($args);
        // Create cpt loop, with a have_posts() check!
        if ($cpt_query->have_posts()) {
            while ($cpt_query->have_posts()) {
                $cpt_query->the_post();
                if ($current_user->wp_user_level === '1') {
                    echo('<div class="home-author-fiches">');
                    the_field('nom_scientifique');
                    
                    the_botascopia_module('button', [
                        'tag' => 'button',
                        'title' => 'compléter',
                        'text' => 'compléter',
                        'modifiers' => 'green-button',
                        'extra_attributes' => ['onclick' => "window.location.href = '".$securise.$_SERVER['HTTP_HOST'].'/formulaire/?p='.get_the_title()."'"]
                    ]);
                    echo('</div>');
                }
            }
            
        }
        
		the_botascopia_module('button',[
			'tag' => 'button',
			'title' => 'Se déconnecter',
			'text' => 'Se déconnecter',
			'modifiers' => 'purple-button',
			'extra_attributes' => ['onclick' => "window.location.href = '".wp_logout_url( $securise.$_SERVER['HTTP_HOST'] )."'"]
		]);
    }
        ?>

        </main><!-- .site-main -->
    </div><!-- .content-area -->

<?php get_footer(); ?>
