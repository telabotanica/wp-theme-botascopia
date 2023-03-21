<?php 

/*
    Template Name: Export fiche
*/

if ( is_readable( __DIR__ . '/vendor/autoload.php' ) ) {
    require __DIR__ . '/vendor/autoload.php';
}

use Spiritix\Html2Pdf\Converter;
use Spiritix\Html2Pdf\Input\UrlInput;
use Spiritix\Html2Pdf\Output\DownloadOutput;

// category slug
$category_name = 'bdtfxcache';
$securise = (isset($_SERVER['HTTPS'])) ? "https://" : "http://";

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

    $posts = query_posts([
        'title'        => $_GET['p'],
        'post_type'   => 'post',
        'post_status' => array('publish', 'pending', 'draft'),
        'showposts' => 1
    ]);
    if (have_posts()) {
        the_post();

//        header('Content-Type: application/pdf');
//        header('Content-Disposition: attachment; filename="'.$_GET['p'].'.pdf"');

        $input = new UrlInput();
        $input->setUrl($securise.$_SERVER['HTTP_HOST'].'/fiche/?p='.$_GET['p']);

        $converter = new Converter($input, new DownloadOutput());

        $converter->setOptions([
            'printBackground' => true,
            'displayHeaderFooter' => true,
            'format' => 'A4',
            'disable-pdf-compression' => true,
            'scale' => 1.1,
        ]);

        $output = $converter->convert();
        $output->download($_GET['p'].'.pdf');
    }

}
