<?php
/*
    Template Name: Formulaire en front création de fiche
*/
acf_form_head();
get_header();
$form = 12;
$formulaires = array(
	"12" => "Description morphologique",
	"127" => "Période de floraison et de fructification",
	"130" => "Aire de répartition et statut", 
	"136" => "Écologie", 
	"143" => "Complément d’anecdote",
	"145" => "Propriétés",
	"147" => "Ne pas confondre avec",
	"150" => "Description vulgarisée",
	"154" => "Références");

if (isset($_GET['f']) && array_key_exists($_GET['f'], $formulaires)) {
	$form = $_GET['f'];
}	

if (isset($_GET['p'])) {
    $titre_du_post = $_GET['p'];
    query_posts(array(
	   'post_type' => 'post',
	   'post_status' => 'draft',
	   'title' => $titre_du_post,
	   'showposts' => 1
    ));
?>
	<?php while (have_posts()) : the_post(); ?>
		<div class="text">
			<h2><?php the_title(); ?></h2>
			<p><?php echo get_the_excerpt(); ?></p>
		</div>
	<?php endwhile;
	if (is_null(the_field( 'nom_scientifique' ))  === false) {
		echo "rrrrrrr";
	}
	$auteur_autorise = false;
	$current_user = strval (wp_get_current_user()->ID);
	$post_author = get_post(the_id())->post_author;
	if ($current_user !== 0) {
		// si l'auteur du post n'est pas l'admin des fiches
		if ($post_author !== $current_user and $post_author == "3") {
			$post_author = $current_user;
			$auteur_autorise = true;
		// s'il s'agit de l'utilisateur ayant modifié la fiche en premier
		} else if ($post_author == $current_user) {
			$auteur_autorise = true;
		}
	}
	if ($auteur_autorise == true) {
?>

	
		<div >
        	<div><a href="<?php the_field('lien_eflore') ?>">Nom scientifique : <?php the_field( 'nom_scientifique' ); ?></a></div>
	        <div>Nom vernaculaire : <?php the_field( 'nom_vernaculaire' ); ?></div>
	        <div>Famille : <?php the_field( 'famille' ); ?></div>
		</div>
<?php	

        $args = array(
            'post_id' => the_id() ,
            'new_post' => array(
                'post_type' => 'post', // Enregistrer dans les articles
                'post_status' => 'draft', // Enregistrer en brouillon
            ),
            'field_groups' => array( $form ), // L'ID du post du groupe de champs
            'submit_value' => 'Enregistrer le brouillon', // Intitulé du bouton
            'updated_message' => "Votre demande a bien été prise en compte.",
            'uploader' => 'wp',
        );

        acf_form( $args ); // Afficher le formulaire
        echo "<br />";
        foreach ($formulaires as $id => $titre) {
	       echo "<button onclick=\"window.location.href = 'http://oser-beta.tela-botanica.org/formulaire/?p=".$titre_du_post."&f=".$id."';\">".$titre."</button>";   
        }	    
/*}else { 
 echo "Fiche non existante";
  //Handle the case where there is no parameter
}*/
	} else { 
		echo "Utilisateur non autorisé";
	} 
} else {
    echo "Pas de nom donné";
}
acf_enqueue_uploader();
get_footer();
?>
