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

        if ( $current_user->wp_user_level === '7') { //$current_user->roles[0] === 'editor'
            $args = array(
                'post_type' => 'post',
                'post_status' => 'pending',
                'showposts' => 10
            );
        } else {
            $args = array(
                'post_type' => 'post',
                'post_status' => 'draft',
                'author' => $current_user->ID,
                'showposts' => 10
            );
        }

	    $cpt_query = new WP_Query($args);
	    // Create cpt loop, with a have_posts() check!
	    if ($cpt_query->have_posts()) {
            echo "<div>".$current_user->display_name.", votre.s formulaire.s :</div><br />";
            echo "<div>".$current_user->roles[0].", votre.s formulaire.s :</div><br />";
            while ($cpt_query->have_posts()) {
                $cpt_query->the_post();
                if ( $current_user->wp_user_level === '7') { //$current_user->roles[0] === 'editor'
                    $validator_query = new WP_Query([
                                'post_type' => 'revision',
                                'post_parent' => get_the_ID(),
                                'post_status' => 'inherit',
                                'author' => $current_user->ID
                            ]);
                    if ($validator_query->have_posts()) {
                    ?>
                    <div style="float:left;width:75%;"><?php the_field( 'nom_scientifique' ); ?>
	    			<span style="float:right;" >
	                <button onclick="window.location.href = '<?php echo $securise.$_SERVER['HTTP_HOST']; ?>/formulaire/?p=<?php the_title(); ?>'">Corriger</button>
                        <button onclick="window.location.href = '<?php echo $securise.$_SERVER['HTTP_HOST']; ?>/fiche/?p=<?php the_title(); ?>'">Prévisualiser</button>
                        <button onclick="window.location.href = '<?php echo $securise.$_SERVER['HTTP_HOST']; ?>/export/?p=<?php the_title(); ?>'">Exporter</button>
                    </span>
                    </div>
                    <?php } ?>
                <?php } else { ?>
                    <div style="float:left;width:75%;"><?php the_field( 'nom_scientifique' ); ?>
	    		    <span style="float:right;" >
                        <button onclick="window.location.href = '<?php echo $securise.$_SERVER['HTTP_HOST']; ?>/formulaire/?p=<?php the_title(); ?>'">Editer</button>
	    		        <button onclick="window.location.href = '<?php echo $securise.$_SERVER['HTTP_HOST']; ?>/fiche/?p=<?php the_title(); ?>'">Prévisualiser</button>
	    			    <button onclick="window.location.href = '<?php echo $securise.$_SERVER['HTTP_HOST']; ?>/export/?p=<?php the_title(); ?>'">Exporter</button>
	    			</span>
                    </div>
                <?php } ?>
            <?php }


            if ( $current_user->wp_user_level === '7') { //$current_user->roles[0] === 'editor'
                //echo "<div></div><br />";
                echo "<div style=float:left;width:100%;margin-bottom:1em;margin-top:3em;>Fiches non attribuées :</div><br />";
                while ($cpt_query->have_posts()) {
                    $cpt_query->the_post();
                    $pending_query = new WP_Query([
                                    'post_type' => 'revision',
                                    'post_parent' => get_the_ID(),
                                    'post_status' => 'inherit',
                                    'author' => - get_the_author_meta('ID')
                                ]);
                    //echo var_dump($pending_query);
                    if ($pending_query->have_posts()) {
                        echo "<div style=float:left;width:75%;margin-bottom:1em;>Pas de fiches en relecture</div>";
                    } else {
                    ?>
                        <div style="float:left;width:75%;"><?php the_field( 'nom_scientifique' ); ?>
                        <span style="float:right;" >
	    				    <button onclick="window.location.href = '<?php echo $securise.$_SERVER['HTTP_HOST']; ?>/formulaire/?p=<?php the_title(); ?>'">Corriger</button>
                            <button onclick="window.location.href = '<?php echo $securise.$_SERVER['HTTP_HOST']; ?>/fiche/?p=<?php the_title(); ?>'">Prévisualiser</button>
                            <button onclick="window.location.href = '<?php echo $securise.$_SERVER['HTTP_HOST']; ?>/export/?p=<?php the_title(); ?>'">Exporter</button>
                        </span>
                        </div>
                    <?php }
                }
            }

        }
        echo "<div style='clear:both;'><button onclick=\"window.location.href = '".wp_logout_url( $securise.$_SERVER['HTTP_HOST'] )."';\">Se déconnecter</button></div>";
    } else {
       	echo "<div style='clear:both;'><button onclick=\"window.location.href = '".wp_login_url( $securise.$_SERVER['HTTP_HOST'] )."';\">Se connecter</button></div>";
    }
        ?>
              
         

        </main><!-- .site-main -->
    </div><!-- .content-area -->

<?php get_footer(); ?>
