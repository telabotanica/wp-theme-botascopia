<?php 

/*
    Template Name: Export fiche
*/

if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
    require __DIR__ . '/vendor/autoload.php';
}

use Knp\Snappy\Pdf;

// category slug
$category_name = 'bdtfxcache';

if (empty($_GET['p'])) {

    $the_query = new WP_Query( [ 
        'category_name' => $category_name,
        'post_status' => 'publish',
        /*'posts_per_page' => 5 */
    ] ); 

    $string = '<ul class="postslist">';
    if ( $the_query->have_posts() ) {
        while ( $the_query->have_posts() ) {
            $the_query->the_post();
            $name = get_post_field( 'post_name', get_post() );
            $string .= '<li><a href="export/?p=' . $name .'" rel="bookmark">' . get_the_title() .'</a></li>';
        }
    } else {
        $string .= '<li>Pas de fiche trouvée dans la categorie '.$category_name.'</li>';
    }
    $string .= '</ul>';

    /* Restore original Post Data */
    wp_reset_postdata();

    echo $string;

} else {

    $the_query = new WP_Query( [ 'name' => $_GET['p'] ] );
    if ($the_query->have_posts()) {
        $the_query->the_post();

        $snappy = new Pdf(__DIR__ . '/vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64');
        header('Content-Type: application/pdf');
        header('Content-Disposition: attachment; filename="'.get_post_field( 'post_name' ).'.pdf"');
        echo $snappy->getOutput( get_site_url().'/fiche/?p='.get_post_field( 'post_name', get_post() ) );
    }

}
