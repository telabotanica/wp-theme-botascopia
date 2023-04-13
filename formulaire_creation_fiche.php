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

$groups = acf_get_field_groups();
$formulaires = [];

$group_titles = [
    "Description morphologique",
    "Période de floraison et de fructification",
    "Aire de répartition et statut",
    "Écologie",
    "Complément d'anecdote",
    "Propriétés",
    "Ne pas confondre avec",
    "Description vulgarisée",
    "Références",
    "Logos",
    "Taxonomie"
];

foreach ( $group_titles as $title ) {
    foreach ($groups as $group) {
        if ($group['title'] == $title){
            $groupInfo = [];
            $groupInfo = [
                'ID' => $group['ID'],
                'title' => $title,
                'key' => $group['key']
                ]
            ;
            $formulaires[] = $groupInfo;
        }
        
    }
}

foreach ($formulaires as $formulaire){
    if (isset($_GET['f']) && array_key_exists($_GET['f'], $formulaire)) {
        $form = $_GET['f'];
    }
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
            }
            // Le bouton devenir auteur est maintenant dans un popup depuis la page collection
//            else {
//                echo "<button onclick=\"window.location.href = '".$securise.$_SERVER['HTTP_HOST']."/formulaire/?p=".$titre_du_post."&a=1';\">Devenir auteur</button>";
//            }
            $auteur_autorise = true;
            // s'il s'agit de l'utilisateur ayant modifié la fiche en premier
        } else if ($auteur_id === $utilisateur) {
            $auteur_autorise = true;
        }
    }
    if ($auteur_autorise) {
        $auteur_name = get_the_author_meta('display_name', $auteur_id);
        $date = get_the_date();
        
        switch (get_post_status()){
            case 'draft':
                $status = 'En cours';
                break;
            case 'pending':
                $status = 'En cours de validation';
                break;
            case 'publish':
                $status = 'Validée';
                break;
            default:
                $status = '';
        }
        ?>
        
        <div class="formulaire-top-page">
            <div class="formulaire-details">
                <?php
                the_botascopia_module('title',[
                    'title' => __('Infos', 'botascopia'),
                    'level' => 4,
                ]);
                ?>
                <div class="formulaire-detail">Statue: <?php echo $status ?></div>
                <div class="formulaire-detail">Créé le <?php echo get_the_date() ?></div>
                <div class="formulaire-detail">Par <?php echo $auteur_name ?></div>
            </div>
            <?php
            the_botascopia_module('button',[
                'tag' => 'button',
                'title' => 'Tout déplier',
                'text' => 'Tout déplier',
                'modifiers' => 'green-button outline',
                'extra_attributes' => ['id' => 'bouton-toutdeplier', 'accordion-status' => '0']
            ]);
            ?>
        </div>
        <?php
        foreach ($formulaires as $formulaire){
            $id = $formulaire['ID'];
            $titre = $formulaire['title'];
            $key = $formulaire['key'];
            
            $args = array(
                'post_id' => get_the_ID() ,
                'field_groups' => array( $id ), // L'ID du post du groupe de champs
                'field_title' => $titre,
                'field_key' => $key,
                'submit_value' => 'Valider', // Intitulé du bouton
                'updated_message' => "Votre demande a bien été prise en compte.",
                'uploader' => 'wp',
                'id' => 'form_draft'.$id,
                'html_after_fields' => '<input type="hidden" id="hidden'.$id .'" name="acf[current_step]" value="1"/>',
                'return' => $securise.$_SERVER['HTTP_HOST'].'/formulaire/?p='.get_the_title(),
            );
            
            the_botascopia_component('accordion',
                 [
                     'title_level' => 2,
                     'items' => [
                         [
                             'content' => $args,
                             'title' => $titre,
                         ],
                     ],
                     'modifiers' => ['id' => 'accordion' . $id]
                 ]
            );
        }
       ?>
        <div class="formulaire-boutons-bas">
            <?php
            the_botascopia_module('button',[
                'tag' => 'button',
                'title' => 'Retour',
                'text' => 'retour',
                'modifiers' => 'purple-button return-button'
            ]);
            
            the_botascopia_module('button',[
                'tag' => 'a',
                'href' => '#',
                'title' => 'Ne plus participer à cette fiche',
                'text' => 'Ne plus participer à la fiche',
                'modifiers' => 'purple-button outline'
            ]);
            
            the_botascopia_module('button',[
                'tag' => 'a',
                'href' => '#',
                'title' => 'Prévisualiser',
                'text' => 'Prévisualiser',
                'modifiers' => 'green-button outline',
            ]);
            
            the_botascopia_module('button',[
                'tag' => 'a',
                'href' => '#',
                'title' => 'Télécharger en pdf',
                'text' => 'Télécharger en pdf',
                'modifiers' => 'green-button',
                'icon_after' => ['icon' => 'pdf', 'color'=>'blanc']
            ]);
            
            // TODO: Ajouter une condition pour n'activer le bouton que si tous les champs sont complets
            the_botascopia_module('button',[
                'tag' => 'button',
                'title' => 'Envoyer la fiche à validation',
                'text' => 'Envoyer la fiche à validation',
                'modifiers' => 'green-button acf-button2',
                'extra_attributes' => ['type' => "submit", 'id' => "pending_btn", 'name'=> "pending_btn", 'value' => "Envoyer la fiche à validation", 'onclick' => "click_ignore();"]
            ]);
            ?>
        </div>
        
<!--        <input type="submit" id="pending_btn" class="acf-button2 green-button outline"-->
<!--               name="pending_btn" value="Envoyer la fiche à validation" onclick="click_ignore();">-->
<!--        <script type="text/javascript">-->
<!--            jQuery(document).ready(function(){-->
<!--                jQuery("#pending_btn").detach().appendTo('.acf-form-submit');-->
<!--            });-->
<!--        </script>-->

<!--        <script type="text/javascript">-->
<!--            function click_ignore(e) {-->
<!--                document.getElementById('hiddenId').value = 2;-->
<!--                return false;-->
<!--            }-->
<!--        </script>-->

        <?php
//        echo "<br />";
//        foreach ($formulaires as $id => $titre) {
//            echo "<button onclick=\"window.location.href = '".$securise.$_SERVER['HTTP_HOST']."/formulaire/?p=".$titre_du_post."&f=".$id."';\">".$titre."</button>";
//        }

    } else if ( $current_user->wp_user_level === '7') { //$current_user->roles[0] === 'editor' (pour les validateurs) 
        // TODO remplacer par action/filter

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
//            foreach ($formulaires as $id => $titre) {
//                echo "<button onclick=\"window.location.href = '".$securise.$_SERVER['HTTP_HOST']."/formulaire/?p=".$titre_du_post."&f=".$id."';\">".$titre."</button>";
//            }
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
