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
    "Propriétés",
    "Complément d'anecdote",
    "Ne pas confondre avec",
    "Description vulgarisée",
    "Références",
//    "Logos",
//    "Taxonomie"
];

$button_titles = [
    "Tige",
    "Feuille",
    "Inflorescence",
    "Fleur mâle",
    "Fleur femelle",
    "Fleur bisexuée",
    "Fruit",
];

$field_titles = [
    "tige",
    "feuille",
    "inflorescence",
    "fleur_male",
    "fleur_femelle",
    "fleur_bisexuee",
    "fruit",
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
    $auteur = get_userdata($auteur_id);
    $auteur_role = $auteur->roles;
    $auteur_name = get_the_author_meta('display_name', $auteur_id);
    $date = get_the_date();
    $modified_date = get_the_modified_date();
    
    switch (get_post_status()){
        case 'draft':
            $status = 'En cours';
            $acf_value = 1;
            $acf_submit_text = 'Enregistrer';
            break;
        case 'pending':
            $status = 'En cours de validation';
            $acf_value = 2;
            $acf_submit_text = 'Corriger';
            break;
        case 'publish':
            $status = 'Validée';
            $acf_value = 3;
            $acf_submit_text = 'Corriger';
            break;
        default:
            $status = '';
            $acf_value = 1;
            $acf_submit_text = 'Valider';
    }
    
    if ($utilisateur !== 0) {
        // si l'auteur du post n'est pas l'admin des fiches
        if ($auteur_id !== $utilisateur && $auteur_role[0] != 'contributor' && isset($_GET['a']) && $_GET['a'] == "1") {
                wp_update_post(array('ID' => get_the_ID(), 'post_author' => $utilisateur));
                $auteur_autorise = true;
        } else if ($auteur_id === $utilisateur) {
            if (isset($_GET['a']) and $_GET['a'] == "2" ) {
                // Désinscription de la fiche
                wp_update_post(array('ID' => get_the_ID(), 'post_author' => 3));
                $auteur_autorise = false;
            } else {
                $auteur_autorise = true;
            }
        } else if ($current_user->wp_user_level == '10'){
            $auteur_autorise = true;
        }
    }
    if ($auteur_autorise) {
        ?>
        
        <div class="formulaire-top-page">
            <div class="formulaire-details">
                <?php
                the_botascopia_module('title',[
                    'title' => __('Infos', 'botascopia'),
                    'level' => 4,
                ]);
                ?>
                <div class="formulaire-detail">Statut: <?php echo $status ?></div>
                <div class="formulaire-detail">Créé le <?php echo $modified_date ?></div>
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
        $fiche_complete = true;
        // récupérer tous les champs du post
        $fields = get_field_objects(get_the_ID());
        
        foreach ($formulaires as $formulaire){
            $id = $formulaire['ID'];
            $titre = $formulaire['title'];
            $key = $formulaire['key'];
            $args = array(
                'post_id' => get_the_ID() ,
                'field_groups' => array( $id ), // L'ID du post du groupe de champs
                'field_title' => $titre,
                'field_key' => $key,
                'submit_value' => $acf_submit_text, // Intitulé du bouton
                'html_submit_button' => '<button type="submit" class="acf-button button green-button">%s</button>',
                'updated_message' => "Votre demande a bien été prise en compte.",
                'uploader' => 'wp',
                'id' => 'form_draft'.$id,
                'html_after_fields' => '<input type="hidden" id="hidden'.$id .'" name="acf[current_step]" value="'.$acf_value
                    .'"/>',
                'return' => $securise.$_SERVER['HTTP_HOST'].'/formulaire/?p='.get_the_title(),
            );
            $formsId[] = $id;

            /*if ($titre == "Description morphologique") {

                the_botascopia_component('inner_accordion',
                    [
                        'title_level' => 2,
                        'items' => [
                            [
                                'content' => $args,
                                'title' => $titre,
                            ],
                        ],
                        'modifiers' => ['id' => 'inner_accordion' . $id]
                    ]
                );
            }*/
            
            the_botascopia_component('accordion',
                 [
                     'title_level' => 2,
                     'items' => [
                         [
                             'content' => $args,
                             'title' => $titre,
                         ],
                     ],
                     'modifiers' => ['id' => 'accordion'.$id]
                 ]
            );
            
            // récupérer tous les champs du groupe de champs ACF
            $group_fields = acf_get_fields($key);
            
            // Vérification si les champs obligatoires sont remplis
            foreach ($group_fields as $field) {
                if ($field['required'] == 1 && $field['name'] != '_validate_email'){
                   $field_group = acf_get_field_group($field['parent'])['title'];
                    if (!get_post_meta(get_the_ID(),$field_group)){
                        $fiche_complete = false;
                        break;
                    }
                }
            }
        }
       ?>
        
        <div class="formulaire-boutons-bas">
            <?php
            the_botascopia_module('button',[
                'tag' => 'a',
                'href' => '/collection',
                'title' => 'Retour aux collections',
                'text' => 'retour aux collection',
                'modifiers' => 'purple-button'
            ]);
            
            if ($auteur_id === $utilisateur || (isset($_GET['a']) && $_GET['a'] == "1")) {
                the_botascopia_module('button', [
                    'tag' => 'button',
                    'title' => 'Ne plus participer à cette fiche',
                    'text' => 'Ne plus participer à la fiche',
                    'modifiers' => 'purple-button outline desinscription-fiche',
                    'extra_attributes' => ['onclick' => "window.location.href = '".$securise.$_SERVER['HTTP_HOST']."/formulaire/?p=".$titre_du_post."&a=2'"]
                ]);
            }

            the_botascopia_module('button',[
                'tag' => 'a',
                'href' => get_permalink(),
                'title' => 'Prévisualiser',
                'text' => 'Prévisualiser',
                'modifiers' => 'green-button outline',
            ]);
            
            the_botascopia_module('button',[
                'tag' => 'button',
                'title' => 'Télécharger en pdf',
                'text' => 'Télécharger en pdf',
                'modifiers' => 'green-button',
                'icon_after' => ['icon' => 'pdf', 'color'=>'blanc'],
                'extra_attributes' => ['onclick' => "window.location.href = '".$securise.$_SERVER['HTTP_HOST']."/export/?p=".get_the_title()."'"]
            ]);

            if ($fiche_complete){
                the_botascopia_module('button',[
                    'tag' => 'button',
                    'title' => 'Envoyer la fiche à vérification',
                    'text' => 'Envoyer la fiche à vérification',
                    'modifiers' => 'green-button acf-button2',
                    'extra_attributes' => ['type' => "submit", 'id' => "pending_btn", 'name'=> "pending_btn", 'value'
                    => "Envoyer la fiche à validation",
                        'data-post-id' => get_the_ID(),
                    ]
                ]);
            }
            ?>
        </div>

        <?php
    } else if ( $current_user->wp_user_level === '7' && $status != 'Validée') { //$current_user->roles[0] === 'editor'
        // (pour les validateurs)
        // TODO remplacer par action/filter
        
        // Ne plus être vérificateur
        if (isset($_GET['a']) and $_GET['a'] == "5" ){
            update_post_meta( get_the_ID(), 'Editor',0 );
        }
        
        // Renvoyer à l'auteur pour correction
        if (isset($_GET['a']) and $_GET['a'] == "3" ){
            wp_update_post(array('ID' => get_the_ID(), 'post_status' =>
                'draft'));
            update_post_meta( get_the_ID(), 'Editor',0 );
        }

        $editor = get_post_meta(get_the_ID(), 'Editor', true);

        if ((intval($editor) === 0)) {
            if (isset($_GET['a']) and $_GET['a'] == "4" ) {
                // Devenir vérificateur
                update_post_meta( get_the_ID(), 'Editor', $current_user->ID );
                ?>
                <meta http-equiv="refresh" content="0;url=">
                <?php
            } else {
                the_botascopia_module('button',[
                    'tag' => 'button',
                    'title' => 'Devenir vérificateur de cette fiche',
                    'text' => 'Devenir vérificateur de cette fiche',
                    'modifiers' => 'green-button',
                    'extra_attributes' => ['onclick' => "window.location.href = '".$securise.$_SERVER['HTTP_HOST']."/formulaire/?p=".$titre_du_post."&a=4'"]
                ]);
            }
            
        } else if (intval($editor) != $utilisateur) {
                echo "Vous n'êtes pas le vérificateur de cette fiche";

        } else {
            // Si vérificateur de la fiche
        ?>
            <div class="formulaire-top-page">
            <div class="formulaire-details">
                <?php
                the_botascopia_module('title',[
                    'title' => __('Infos', 'botascopia'),
                    'level' => 4,
                ]);
                ?>
                <div class="formulaire-detail">Statut: <?php echo $status ?></div>
                <div class="formulaire-detail">Créé le <?php echo $modified_date ?></div>
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
                   'submit_value' => 'Corriger', // Intitulé du bouton
                   'html_submit_button' => '<button type="submit" class="acf-button button green-button">%s</button>',
                   'updated_message' => "Votre demande a bien été prise en compte.",
                   'uploader' => 'wp',
                   'id' => 'form_draft'.$id,
                   'html_after_fields' => '<input type="hidden" id="hidden'.$id .'" name="acf[current_step]" value="2"/>',
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
                        'modifiers' => ['id' => 'accordion'.$id]
                    ]
               );
           }
            ?>
            
            <div class="formulaire-boutons-bas">
                <?php
                the_botascopia_module('button',[
                    'tag' => 'a',
                    'href' => '/collection',
                    'title' => 'Retour aux collections',
                    'text' => 'retour aux collection',
                    'modifiers' => 'purple-button'
                ]);
                
                if ((intval($editor) == $utilisateur) || (isset($_GET['a']) && $_GET['a'] == "4")) {
                    the_botascopia_module('button', [
                        'tag' => 'button',
                        'title' => 'Ne plus être vérificateur de cette fiche',
                        'text' => 'Ne plus être vérificateur de cette fiche',
                        'modifiers' => 'purple-button outline',
                        'extra_attributes' => ['onclick' => "window.location.href = '".$securise
                            .$_SERVER['HTTP_HOST']."/formulaire/?p=".$titre_du_post."&a=5'"]
                    ]);
                    
                    the_botascopia_module('button', [
                        'tag' => 'button',
                        'title' => 'Renvoyer pour correction',
                        'text' => 'Renvoyer pour correction',
                        'modifiers' => 'purple-button outline',
                        'extra_attributes' => ['onclick' => "window.location.href = '".$securise
                            .$_SERVER['HTTP_HOST']."/formulaire/?p=".$titre_du_post."&a=3'"]
                    ]);
                }
                
                the_botascopia_module('button',[
                    'tag' => 'a',
                    'href' => get_permalink(),
                    'title' => 'Prévisualiser',
                    'text' => 'Prévisualiser',
                    'modifiers' => 'green-button outline',
                ]);
                
                the_botascopia_module('button',[
                    'tag' => 'button',
                    'title' => 'Télécharger en pdf',
                    'text' => 'Télécharger en pdf',
                    'modifiers' => 'green-button',
                    'icon_after' => ['icon' => 'pdf', 'color'=>'blanc'],
                    'extra_attributes' => ['onclick' => "window.location.href = '".$securise.$_SERVER['HTTP_HOST']."/export/?p=".get_the_title()."'"]
                ]);
                
                    the_botascopia_module('button',[
                        'tag' => 'button',
                        'title' => 'Publier',
                        'text' => 'Publier',
                        'modifiers' => 'green-button acf-button2',
                        'extra_attributes' => ['type' => "submit", 'id' => "publish_btn", 'name'=> "publish_btn", 'value' => "Envoyer la fiche à validation", 'data-post-id' => get_the_ID()]
                    ]);
                ?>
            </div>
            <?php
        }
    } else {
        /*
        the_botascopia_module('button',[
            'tag' => 'button',
            'title' => 'Devenir auteur',
            'text' => 'Devenir auteur',
            'modifiers' => 'green-button',
            'extra_attributes' => ['onclick' => "window.location.href = '".$securise.$_SERVER['HTTP_HOST']."/formulaire/?p=".$titre_du_post."&a=1'"]
        ]);
        */
//        echo "Vous n'êtes pas l'auteur de cette fiche";
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
