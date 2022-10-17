<?php get_header();?>

<h1>Voir toutes les fiches</h1>

<?php if (have_posts()) : ?>
    <?php while (have_posts()) : the_post(); ?>
        <li>
            <a href="<?php the_permalink(); ?>">
                <div class=""><?php the_title(); ?></div>
            </a>
        </li>

    <?php endwhile; ?>
<?php else : ?>
    <p>Pas de fiches correspondant à votre recherche.</p>
<?php endif;?>

<?php get_footer();?>