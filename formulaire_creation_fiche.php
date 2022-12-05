<?php
/*
    Template Name: Formulaire en front création de fiche
*/
acf_form_head();
get_header();
$securise = (isset($_SERVER['HTTPS'])) ? "https://" : "http://";
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
	 while (have_posts()) : the_post(); ?>
		<div class="text">
			<h2><?php the_title(); ?></h2>
			<p><?php echo get_the_excerpt(); ?></p>
		</div>
	<?php endwhile;
	$auteur_autorise = false;
	$utilisateur = get_current_user_id();
	$auteur_id = get_the_author_meta('ID');
	if ($utilisateur !== 0) {
		// si l'auteur du post n'est pas l'admin des fiches
		if ($auteur_id !== $utilisateur and $auteur_id == "3") {
			if (isset($_GET['a']) and $_GET['a'] == "1" ) {
				wp_update_post(array('ID' => get_the_ID(), 'post_author' => $utilisateur));
			} else {
				echo "<button onclick=\"window.location.href = '".$securise.$_SERVER['HTTP_HOST']."/formulaire/?p=".$titre_du_post."&a=1';\">Devenir auteur</button>"; 
			}
			$auteur_autorise = true;
		// s'il s'agit de l'utilisateur ayant modifié la fiche en premier
		} else if ($auteur_id === $utilisateur) {
			$auteur_autorise = true;
		}
	}
	if ($auteur_autorise == true) {
        if (isset($_GET['a']) and $_GET['a'] == "2" ) {
            wp_update_post(array('ID' => get_the_ID(), 'post_status' => 'pending'));

            ?>
            <meta http-equiv="refresh" content="0;url=">
            <?php
        }
?>
		<div >
        	<div><a href="<?php the_field('lien_eflore') ?>" target="_blank">Nom scientifique : <?php the_field( 'nom_scientifique' ); ?></a></div>
	        <div>Nom vernaculaire : <?php the_field( 'nom_vernaculaire' ); ?></div>
	        <div>Famille : <?php the_field( 'famille' ); ?></div>
		</div>
<?php	

        $args = array(
            'post_id' => get_the_ID() ,
            'new_post' => array(
                'post_type' => 'post', // Enregistrer dans les articles
                'post_status' => 'draft', // Enregistrer en brouillon
            ),
            'field_groups' => array( $form ), // L'ID du post du groupe de champs
            'submit_value' => 'Enregistrer le brouillon', // Intitulé du bouton
            'updated_message' => "Votre demande a bien été prise en compte.",
            'uploader' => 'wp',
            'return' => '',
        );


        //acf_form( $args_brouillon ); // Afficher le formulaire
        acf_form( $args ); // Afficher le formulaire

        echo "<button onclick=\"window.location.href = '".$securise.$_SERVER['HTTP_HOST']."/formulaire/?p=".$titre_du_post."&a=2';\">Mettre en relecture</button>";

        echo "<br />";
        foreach ($formulaires as $id => $titre) {
	       echo "<button onclick=\"window.location.href = '".$securise.$_SERVER['HTTP_HOST']."/formulaire/?p=".$titre_du_post."&f=".$id."';\">".$titre."</button>";
        }

    } else {
		echo "Vous n'êtes pas l'auteur de cette fiche";
	} 
} else {
    echo "URL inexistante, vérifier celui de la fiche recherchée";
}
acf_enqueue_uploader();
get_footer();
?>
