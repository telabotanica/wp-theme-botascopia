<?php
/*
    Template Name: Formulaire en front création de fiche
*/
	acf_form_head();
get_header();
if (isset($_GET['p'])) {
  $titre_du_post = $_GET['p'];
/*$page = get_page_by_title($titre_du_post); 
$content = apply_filters('the_content', $page->post_content); 
if ($content !== false) {
	echo $content; ?>*/

query_posts(array(
	'post_type' => 'post',
	'post_status' => 'draft',
	'showposts' => 10
) );
?>
<?php while (have_posts()) : the_post(); ?>
		<div class="text">
			<h2><b><a href="<?php the_permalink() ?>"><?php the_title(); ?></a></b></h2>
			<p><i>Recette proposée par : <?php the_author(); ?></i></p>
			<p><?php echo get_the_excerpt(); ?></p>
		</div>
<?php endwhile;
if (1 == 1) {?>	

	
<div >

        <div>Nom scientifique : <?php the_field( 'nom_scientifique' ); ?></div>

        <div>Nom vernaculaire : <?php the_field( 'nom_vernaculaire' ); ?></div>

        <div>Famille : <?php the_field( 'famille' ); ?></div>
</div>
<?php	

    $args = array(
        'post_id' => $post ,//'new_post', // On va créer une nouvelle publication
        'new_post' => array(
            'post_type' => 'article', // Enregistrer dans l'annuaire
            'post_status' => 'draft', // Enregistrer en brouillon
        ),
        'field_groups' => array( 12 ), // L'ID du post du groupe de champs
        'submit_value' => 'Enregistrer la fiche', // Intitulé du bouton
        'updated_message' => "Votre demande a bien été prise en compte.",
    );

    acf_form( $args ); // Afficher le formulaire
}else { 
 echo "Fiche non existante";
  //Handle the case where there is no parameter
}}
	else { 
 echo "Pas de nom donné";
  //Handle the case where there is no parameter
}

?>