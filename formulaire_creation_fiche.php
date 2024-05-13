<?php
/*
    Template Name: Formulaire en front création de fiche
*/

use JsPhpize\Nodes\Constant;

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
    Constantes::DESCRIPTION,
    Constantes::PERIOD,
    Constantes::AIRE,
    Constantes::ECOLOGIE,
    Constantes::PROPERTIES,
    Constantes::ANECDOTE,
    Constantes::CONFUS,
    Constantes::VULG,
    Constantes::AGRO,
    Constantes::REFERENCES
];

$button_titles = [
    Constantes::TIGE,
    Constantes::FEUILLE,
    Constantes::INFLO,
    Constantes::FL_MALE,
    Constantes::FL_FEM,
    Constantes::FL_BI,
    Constantes::FRUIT,
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
    $not_available = false;
    if ( $current_user->roles[0] === 'editor') { //$current_user->roles[0] === 'editor'

        $page= get_page_by_post_title($titre_du_post,OBJECT,'post');
        $post_id=$page->ID;
       
        if ($page->post_status=='publish'){
            $not_available=true;
        }

    } else if($current_user->roles[0] === 'administrator'){
        
        $page= get_page_by_post_title($titre_du_post,OBJECT,'post');
        $post_id=$page->ID;
      
        
    }else{
        $page= get_page_by_post_title($titre_du_post,OBJECT,'post');
        $post_id=$page->ID;
      
        if ($page->post_status != 'draft'){
            $not_available=true;
        }
    }
        

        
            the_botascopia_module('cover',[
                'subtitle' => get_post_meta($post_id, 'nom_vernaculaire', true).' - '.get_post_meta($id, 'famille',true),
                'title' => "<i>".get_post_meta($post_id, 'nom_scientifique', true)."</i>",
                'image' => [get_template_directory_uri() .'/images/recto-haut.svg'],
                'modifiers' =>['class' => 'fiche-cover']
            ]);

            $id_image=get_post_meta($post_id, '_photo_de_la_plante_entiere', true);
            
            $image = get_post($id_image);
            if (isset($image)){
                echo ('
                    <img src= '.$image->guid .' class="fiche-image">
                ');
            }
            

            
        
            $auteur_autorise = false;
            // $current_user = wp_get_current_user();
            $utilisateur = get_current_user_id();
            $auteur_id = get_post_field('post_author', $post_id);
            $auteur = get_userdata($auteur_id);
            $auteur_role = $auteur->roles;
            $auteur_name = get_the_author_meta('display_name', $auteur_id);
            $date = explode(" ",$page->post_date)[0];
            $modified_date = explode(" ",$page->post_modified)[0];
            
            switch ($page->post_status){
                case Constantes::DRAFT:
                    $status = Constantes::DRAFT_FR;
                    $acf_value = 1;
                    $acf_submit_text = Constantes::ENREGISTRER;
                    break;
                case Constantes::PEND:
                    $status = Constantes::PEND_FR;
                    $acf_value = 2;
                    $acf_submit_text = Constantes::CORRIGER;
                    break;
                case Constantes::PUBLISH:
                    $status = Constantes::PUBLISH_FR;
                    $acf_value = 3;
                    $acf_submit_text = Constantes::CORRIGER;
                    break;
                default:
                    $status = '';
                    $acf_value = 1;
                    $acf_submit_text = Constantes::VALIDER;
            }
            
            if ($utilisateur !== 0) {
                // si l'auteur du post n'est pas l'admin des fiches
                if ($auteur_id != $utilisateur && isset($_GET['a']) && $_GET['a'] == "1" && $page->post_status == 'draft') {
                        wp_update_post(array('ID' => $post_id, 'post_author' => $utilisateur));
                        $auteur_autorise = true;
                } else if ($auteur_id == $utilisateur) {
                    if (isset($_GET['a']) and $_GET['a'] == "2" ) {
                        // Désinscription de la fiche
                        wp_update_post(array('ID' => $post_id, 'post_author' => 3));
                        $auteur_autorise = false;
                    } elseif($page->post_status == 'draft') {
                        $auteur_autorise = true;
                    }
                } else if ($current_user->wp_user_level == '10'){
                    $auteur_autorise = true;
                }
            }
        if (!$not_available){
            if ($auteur_autorise) {
                ?>
                 <div class="formulaire-top-page">
                    <div class="floating-button-div">
                        <button class="fb"></button>
                    </div>    
                    <div class="formulaire-details">
                        <?php
                        the_botascopia_module('title',[
                            'title' => __(Constantes::INFOS, 'botascopia'),
                            'level' => 4,
                        ]);
                        ?>
                        <div class="formulaire-detail">Statut: <?php echo $status ?></div>
                        <div class="formulaire-detail">Modifiée le <?php echo $modified_date ?></div>
                        <div class="formulaire-detail">Par <?php echo $auteur_name ?></div>
                    </div>
                    <?php
                    echo "<p>Cases à cocher : choix multiples ; boutons ronds : un seul choix</p>";
                    the_botascopia_module('button',[
                        'tag' => 'button',
                        'title' => Constantes::TT_DEPLIER,
                        'text' => Constantes::TT_DEPLIER,
                        'modifiers' => 'green-button outline',
                        'extra_attributes' => ['id' => 'bouton-toutdeplier', 'accordion-status' => '0']
                    ]);
                    ?>
                </div>
                
                <?php
                $fiche_complete = true;
                // récupérer tous les champs du post
                /* $fields=[];
                foreach($page as $value){
                    $meta_key = $value->meta_key;
                    
                    $obj = get_field_object($meta_key,$post_id);
                   
                    if ($obj){
                        array_push($fields,$obj);
                    }
                    
                } */
                
                foreach ($formulaires as $formulaire){
                    $id = $formulaire['ID'];
                    $titre = $formulaire['title'];
                    $key = $formulaire['key'];
                    $args = array(
                        'post_id' => $post_id ,
                        'field_groups' => array( $id ), // L'ID du post du groupe de champs
                        'field_title' => $titre,
                        'field_key' => $key,
                        'submit_value' => $acf_submit_text, // Intitulé du bouton
                        'html_submit_button' => '<button type="submit" class="acf-button button green-button">%s</button>',
                        'updated_message' => Constantes::YOUR_DEMAND,
                        'uploader' => 'wp',
                        'id' => 'form_draft'.$id,
                        'html_after_fields' => '<input type="hidden" id="hidden'.$id .'" name="acf[current_step]" value="'.$acf_value
                            .'"/>',
                        'return' => $securise.$_SERVER['HTTP_HOST'].'/formulaire/?p='.$titre_du_post,
                    );
                    $formsId[] = $id;
                    
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
                            if (!get_post_meta($post_id,$field_group)){
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
                        'href' => '/collections',
                        'title' => Constantes::BACK_TO_COLLEC,
                        'text' => Constantes::BACK_TO_COLLEC,
                        'modifiers' => 'purple-button'
                    ]);
                    
                    if ($auteur_id == $utilisateur || (isset($_GET['a']) && $_GET['a'] == "1")) {
                        the_botascopia_module('button', [
                            'tag' => 'button',
                            'title' => Constantes::DONT_PARTICIPATE,
                            'text' => Constantes::DONT_PARTICIPATE,
                            'modifiers' => 'purple-button outline desinscription-fiche',
                            'extra_attributes' => ['onclick' => "window.location.href = '".$securise.$_SERVER['HTTP_HOST']."/formulaire/?p=".$titre_du_post."&a=2'"]
                        ]);
                    }

                    the_botascopia_module('button',[
                        'tag' => 'a',
                        'href' => get_site_url()."/?p=$post_id",
                        'title' => Constantes::PREVISUALISER,
                        'text' => Constantes::PREVISUALISER,
                        'modifiers' => 'green-button outline',
                    ]);
                    
                    the_botascopia_module('button',[
                        'tag' => 'button',
                        'title' => Constantes::TELECHARGER,
                        'text' => Constantes::TELECHARGER,
                        'modifiers' => 'green-button',
                        'icon_after' => ['icon' => 'pdf', 'color'=>'blanc'],
                        'extra_attributes' => ['onclick' => "window.location.href = '".$securise.$_SERVER['HTTP_HOST']."/export/?p=".get_the_title()."'"]
                    ]);

                    if ($fiche_complete){
                        the_botascopia_module('button',[
                            'tag' => 'button',
                            'title' => Constantes::SEND,
                            'text' => Constantes::SEND,
                            'modifiers' => 'green-button acf-button2',
                            'extra_attributes' => [
                                    'type' => "submit",
                                    'id' => "pending_btn",
                                    'name'=> "pending_btn",
                                    'value' => Constantes::SEND,
                                    'data-post-id' => $post_id
                            ]
                        ]);
                    }
                    ?>
                </div>

                <?php
            } else if ( $current_user->wp_user_level == '7' && $status != 'Validée') { //$current_user->roles[0] === 'editor'
                // (pour les validateurs)
                // TODO remplacer par action/filter
                
                // Ne plus être vérificateur
                if (isset($_GET['a']) and $_GET['a'] == "5" ){
                    update_post_meta( $post_id, 'Editor',0 );
                }
                
                // Renvoyer à l'auteur pour correction
                if (isset($_GET['a']) and $_GET['a'] == "3" ){
                    if (isset($_GET['h'])){
                        wp_update_post(array('ID' => $post_id, 'post_status' =>
                            'draft', 'post_author' => $_GET['h']));
                    } else {
                        wp_update_post(array('ID' => $post_id, 'post_status' =>
                            'draft'));
                    }
                    
                    update_post_meta( $post_id, 'Editor',0 );
                }

                $editor = get_post_meta($post_id, 'Editor', true);

                if ((intval($editor) == 0)) {
                    if (isset($_GET['a']) and $_GET['a'] == "4" ) {
                        // Devenir vérificateur
                        update_post_meta( $post_id, 'Editor', $current_user->ID );
                        ?>
                        <meta http-equiv="refresh" content="0;url=">
                        <?php
                    } else {
                        the_botascopia_module('button',[
                            'tag' => 'button',
                            'title' => Constantes::BEC_EDIT,
                            'text' => Constantes::BEC_EDIT,
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
                        <div class="formulaire-detail">Modifiée le <?php echo $modified_date ?></div>
                        <div class="formulaire-detail">Par <?php echo $auteur_name ?></div>
                    </div>
                    <?php
                    the_botascopia_module('button',[
                        'tag' => 'button',
                        'title' => Constantes::TT_DEPLIER,
                        'text' => Constantes::TT_DEPLIER,
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
                        'post_id' => $post_id ,
                        'field_groups' => array( $id ), // L'ID du post du groupe de champs
                        'field_title' => $titre,
                        'field_key' => $key,
                        'submit_value' => 'Corriger', // Intitulé du bouton
                        'html_submit_button' => '<button type="submit" class="acf-button button green-button">%s</button>',
                        'updated_message' => Constantes::YOUR_DEMAND,
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
                            'href' => '/collections',
                            'title' => Constantes::BACK_TO_COLLEC,
                            'text' => Constantes::BACK_TO_COLLEC,
                            'modifiers' => 'purple-button'
                        ]);
                        
                        if ((intval($editor) == $utilisateur) || (isset($_GET['a']) && $_GET['a'] == "4")) {
                            the_botascopia_module('button', [
                                'tag' => 'button',
                                'title' => Constantes::DONT_PARTICIPATE,
                                'text' => Constantes::DONT_PARTICIPATE,
                                'modifiers' => 'purple-button outline',
                                'extra_attributes' => ['onclick' => "window.location.href = '".$securise
                                    .$_SERVER['HTTP_HOST']."/formulaire/?p=".$titre_du_post."&a=5'"]
                            ]);

                            the_botascopia_module('button', [
                                'tag' => 'button',
                                'title' => Constantes::RESEND,
                                'text' => Constantes::RESEND,
                                'modifiers' => 'purple-button outline',
                                'extra_attributes' => ['onclick' => "window.location.href = '".$securise
                                    .$_SERVER['HTTP_HOST']."/formulaire/?p=".$titre_du_post."&a=3&h=".$auteur_id."'"]
                            ]);
                        }
                        
                        the_botascopia_module('button',[
                            'tag' => 'a',
                            'href' => get_site_url()."/?p=$post_id",
                            'title' => Constantes::PREVISUALISER,
                            'text' => Constantes::PREVISUALISER,
                            'modifiers' => 'green-button outline',
                        ]);
                        
                        the_botascopia_module('button',[
                            'tag' => 'button',
                            'title' => Constantes::TELECHARGER,
                            'text' => Constantes::TELECHARGER,
                            'modifiers' => 'green-button',
                            'icon_after' => ['icon' => 'pdf', 'color'=>'blanc'],
                            'extra_attributes' => ['onclick' => "window.location.href = '".$securise.$_SERVER['HTTP_HOST']."/export/?p=".$titre_du_post."'"]
                        ]);
                        
                            the_botascopia_module('button',[
                                'tag' => 'button',
                                'title' => Constantes::PUBLISH,
                                'text' => Constantes::PUBLISH,
                                'modifiers' => 'green-button acf-button2',
                                'extra_attributes' => ['type' => "submit", 'id' => "publish_btn", 'name'=> "publish_btn", 'value' => "Envoyer la fiche à validation", 'data-post-id' => $post_id, 'data-post-title' => $titre_du_post]

                            ]);
                        ?>
                        
                    </div>
                    <?php
                }
            }
        }else{
            echo "<div>Vous n'avez pas l'autorisation de modifier cette fiche.</div>";
        }     
    }else {
        echo "URL inexistante, vérifier celui de la fiche recherchée";
    }
    acf_enqueue_uploader();
    ?>
        
        </main>
    </div>

<?php
get_footer();
?>
<script src="<?php echo (get_template_directory_uri() . '/assets/scripts/formulaire.js'); ?>" ></script>
