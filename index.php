<?php 
require(‘./wp-blog-header.php’);
get_header(); ?>
      <nav id="navigation">
<?php
wp_nav_menu(
  array(
    'theme_location' => 'main-menu',
    'menu_id' => 'primary-menu',
 )
);
?>
</nav>
  <div id="primary" class="content-area">
    <main id="main" class="site-main" role="main">

    </main><!-- .site-main -->
  </div><!-- .content-area -->

<?php get_footer(); ?>
