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
              
         
     

      if ( is_user_logged_in() ) {
        $current_user = wp_get_current_user();
        echo $current_user->display_name;
        query_posts(array(
	   'post_type' => 'post',
	   'post_status' => 'draft',
	   'post_author' => $current_user->ID,
	   'showposts' => 10
    ));
	 while (have_posts()) : the_post(); ?>
		<div><a href='http://oser-beta.tela-botanica.org/formulaire/?p=<?php the_title() ?>' target="_blank"><?php the_field( 'nom_scientifique' ); ?></a></div>
	<?php endwhile;
        echo "<button onclick=\"window.location.href = '".wp_logout_url( get_permalink() )."';\">Se déconnecter</button>"; 
    } else {
        echo "<button onclick=\"window.location.href = '".wp_login_url( get_permalink() )."';\">Se connecter</button>"; 
      }
        ?>
              
         

        </main><!-- .site-main -->
    </div><!-- .content-area -->

<?php get_footer(); ?>
