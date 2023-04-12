<?php
/*
    Template Name: Formulaire en front création de fiche
*/
acf_form_head();
get_header();
?>
<div id="primary" class="content-area">
    <main id="main" class="site-main " role="main">
<?php
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

$current_user = wp_get_current_user();

if (isset($_GET['p'])) {
    $titre_du_post = $_GET['p'];

    if ( $current_user->wp_user_level === '7') { //$current_user->roles[0] === 'editor'

        query_posts(array(
            'post_type' => 'post',
            'post_status' => 'pending',
            'title' => $titre_du_post,
            'showposts' => 1
        ));

    } else {
        query_posts(array(
            'post_type' => 'post',
            'post_status' => 'draft',
            'title' => $titre_du_post,
            'showposts' => 1
        ));
    }

    while (have_posts()) : the_post(); ?>
    
    <?php
    the_botascopia_module('cover',[
        'subtitle' => get_post_meta(get_the_ID(), 'nom_vernaculaire', true).' - '.get_post_meta(get_the_ID(), 'famille',true),
        'title' => get_post_meta(get_the_ID(), 'nom_scientifique', true),
        'image' => ['url' => get_template_directory_uri() .'/images/recto-haut.svg'],
        'modifiers' =>['class' => 'fiche-cover']
    ]);
    ?>
        <div class="text">
            <h2><?php the_title(); ?></h2>
            <p><?php echo get_the_excerpt(); ?></p>
        </div>
    <?php endwhile;
    $auteur_autorise = false;
    // $current_user = wp_get_current_user();
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
        ?>
        <div >
            <div><a href="<?php the_field('lien_eflore') ?>" target="_blank">Nom scientifique : <?php the_field( 'nom_scientifique' ); ?></a></div>
            <div>Nom vernaculaire : <?php the_field( 'nom_vernaculaire' ); ?></div>
            <div>Famille : <?php the_field( 'famille' ); ?></div>
        </div>
        <?php

        $args = array(
            'post_id' => get_the_ID() ,
            /*'new_post' => array(
                'post_type' => 'post', // Enregistrer dans les articles
                'post_status' => 'pending', // Enregistrer en brouillon
            ),*/
            'field_groups' => array( $form ), // L'ID du post du groupe de champs
            'submit_value' => 'Enregistrer les modifications', // Intitulé du bouton
            'updated_message' => "Votre demande a bien été prise en compte.",
            'uploader' => 'wp',
            'id' => 'form_draft',
            'html_after_fields' => '<input type="hidden" id="hiddenId" name="acf[current_step]" value="1"/>',
            'return' => home_url(),
            // 'return' => '',
        );


        //acf_form( $args_brouillon ); // Afficher le formulaire
        acf_form( $args ); // Afficher le formulaire
        ?>
        <input type="submit" id="pending_btn" class="acf-button2 button button-primary button-large" name="pending_btn" value="Mettre en relecture" onclick="click_ignore();">
        <script type="text/javascript">
            jQuery(document).ready(function(){
                jQuery("#pending_btn").detach().appendTo('.acf-form-submit');
            });
        </script>

        <script type="text/javascript">
            function click_ignore(e) {
                document.getElementById('hiddenId').value = 2;
                return false;
            }
        </script>

        <?php
        echo "<br />";
        foreach ($formulaires as $id => $titre) {
            echo "<button onclick=\"window.location.href = '".$securise.$_SERVER['HTTP_HOST']."/formulaire/?p=".$titre_du_post."&f=".$id."';\">".$titre."</button>";
        }

    } else if ( $current_user->wp_user_level === '7') { //$current_user->roles[0] === 'editor' TODO remplacer par action/filter

        $editor = get_post_meta(get_the_ID(), 'Editor', true);

        if ((intval($editor) === 0)) {
            if (isset($_GET['a']) and $_GET['a'] == "4" ) {
                update_post_meta( get_the_ID(), 'Editor', $current_user->ID );
                ?>
                <meta http-equiv="refresh" content="0;url=">
                <?php
            } else {
                echo "<button onclick=\"window.location.href = '" . $securise . $_SERVER['HTTP_HOST'] . "/formulaire/?p=" . $titre_du_post . "&a=4';\">Devenir vérificateur</button>";
            }
        } else if (intval($editor) != $utilisateur) {
                echo "Vous n'êtes pas le vérificateur de cette fiche";

        } else {
            ?>
            <div >
                <div><a href="<?php the_field('lien_eflore') ?>" target="_blank">Nom scientifique : <?php the_field( 'nom_scientifique' ); ?></a></div>
                <div>Nom vernaculaire : <?php the_field( 'nom_vernaculaire' ); ?></div>
                <div>Famille : <?php the_field( 'famille' ); ?></div>
            </div>
            <?php

            $args = array(
                    'post_id' => get_the_ID() ,
                    /*'new_post' => array(
                        'post_type' => 'post', // Enregistrer dans les articles
                        'post_status' => 'pending', // Enregistrer en brouillon
                    ),*/
                    'field_groups' => array( $form ), // L'ID du post du groupe de champs
                    'submit_value' => 'Enregistrer les modifications', // Intitulé du bouton
                    'updated_message' => "Votre demande a bien été prise en compte.",
                    'uploader' => 'wp',
                    'id' => 'form_draft',
                    'html_after_fields' => '<input type="hidden" id="hiddenId" name="acf[current_step]" value="2"/>',
                    'return' => home_url(),
                    // 'return' => '',
            );


            //acf_form( $args_brouillon ); // Afficher le formulaire
            acf_form( $args ); // Afficher le formulaire
            ?>
            <input type="submit" id="publish_btn" class="acf-button2 button button-primary button-large" name="publish_btn" value="Valider la fiche" onclick="click_ignore();">
            <script type="text/javascript">
                jQuery(document).ready(function(){
                    jQuery("#publish_btn").detach().appendTo('.acf-form-submit');
                });
            </script>

            <script type="text/javascript">
                function click_ignore(e) {
                    document.getElementById('hiddenId').value = 3;
                    return false;
                }
            </script>

            <?php
            echo "<br />";
            foreach ($formulaires as $id => $titre) {
                echo "<button onclick=\"window.location.href = '".$securise.$_SERVER['HTTP_HOST']."/formulaire/?p=".$titre_du_post."&f=".$id."';\">".$titre."</button>";
            }
        }
    } else {
        echo "Vous n'êtes pas l'auteur de cette fiche";
    }
} else {
    echo "URL inexistante, vérifier celui de la fiche recherchée";
}
acf_enqueue_uploader();
?>
    
    </main>
</div>

<?php
get_footer();
?>
