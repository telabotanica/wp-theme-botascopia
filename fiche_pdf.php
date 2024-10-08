<?php
/*
    Template Name: Fiche pdf
*/

?>
<!DOCTYPE html>
<html <?php language_attributes(); ?> class="no-js">
<head>
    <meta charset="<?php bloginfo( 'charset' ); ?>">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="profile" href="http://gmpg.org/xfn/11">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Ubuntu&display=swap');
    </style>
    <?php if (is_singular() && pings_open(get_queried_object())) : ?>
        <link rel="pingback" href="<?php bloginfo('pingback_url'); ?>">
    <?php endif; ?>

    <meta name="robots" content="max-snippet:-1, max-image-preview:large, max-video-preview:-1">
    <?php wp_head(); ?>
</head>

<body <?php body_class(); ?>>

<?php if (isset($_GET['p'])): ?>
    <?php
    $posts = query_posts([
        'title'        => $_GET['p'],
        'post_type'   => 'post',
        'post_status' => array('publish', 'pending', 'draft'),
        'showposts' => 1
    ]);?>
    
    <?php while (have_posts()) : the_post(); 
    
    ?>
    
        <div id="page-1" class="page page-1">

            <header>
                <div class="pdf-container">
                    <h1><?php echo getFilteredTitle(get_post_meta(get_the_ID(), Constantes::NOM_SCIENTIFIQUE, true)); ?></h1>
                    <h2><?php the_field('nom_vernaculaire');?> — <?php the_field('famille');?></h2>
                    <div class="characteristic pdf-description-morpho">
                        <h3 class="icon-title">
                            <div class="description-icon icon"></div>description morphologique
                        </h3>
                        <p><?php if (!empty(get_field('port_de_la_plante'))) { echo ucfirst(get_field('port_de_la_plante')).", ";} 
                             if (!empty(get_field('systeme_sexuel')) && get_field('systeme_sexuel') !== Constantes::HERMAPHRODITE ) { echo get_field('systeme_sexuel').", " ;}
                             if ((get_field('port_de_la_plante') == Constantes::HERBACEE || get_field('port_de_la_plante') == Constantes::LIANE) && !empty(get_field('mode_de_vie_')) && get_field('mode_de_vie_') !== array("terrestre") ) { echo implode(', ', get_field('mode_de_vie_')).", " ; }
                             if (get_field('port_de_la_plante') == Constantes::HERBACEE && !empty(get_field('type_de_developpement'))) { echo implode(', ', get_field('type_de_developpement')).", " ;}
                             if ((get_field('port_de_la_plante') == Constantes::HERBACEE || get_field('port_de_la_plante') == Constantes::LIANE) && !empty(get_field('forme_biologique'))) { echo implode(', ', get_field('forme_biologique')).", " ;} ?>
                            qui peut atteindre jusqu'à <?php the_field('hauteur_maximale'); ?> de haut. 
                            <?php if (!empty(get_field(' pilosite_de_la_plante_entiere'))) { echo "Cette plante est ".get_field(' pilosite_de_la_plante_entiere').".";} ?>
                            <?php $signes = get_field("signe_particulier"); if (!empty($signes)){echo $signes;} ?>
                        </p>
                    </div>
                </div>
                <?php
                $index_photos = 0;
                $fruit_photo=null;
                $refs_photo = array();
                
                    $refs_photo[] = getFicheImage(get_the_ID());
                    $image = $refs_photo[0];
                
                ?>
                <div class="round-picture" style="background-image: url('<?php echo $image;?>'); background-size: cover;"></div>
            </header>

            <main id="caracteristiques" class="pdf-container">
<!--Tige-->
                <div id="stem" class="characteristic">
                    <?php
                    $tige = get_field('tige');
                    
                    if (!empty($tige)) {
                        $type_tige = implode(', ', $tige['type_de_tige']);
                        $section_tige = implode('-', $tige['section_de_la_tige']);
                        $surface_tige = implode(', ', $tige['surface_de_la_tige_jeune']);
                        $port_de_la_plante = get_field('port_de_la_plante');
                        if (!empty($port_de_la_plante)) {
                            if (($port_de_la_plante === Constantes::ARBRISSEAU) || ($port_de_la_plante === Constantes::ARBRE)) {
                                if (!empty($tige['surface_de_lecorce'])) {
                                    $surface_ecorce = implode(', ', $tige['surface_de_lecorce']);
                                }
                            }
                        }
                        ?>
                        <h4 class="icon-title">
                            <div class="tige-icon icon"></div>Tige
                        </h4>
                        <?php if(!empty($tige["illustration_de_la_tige"]['photo_tige'])): ?>
                            <?php
                            $id_photo = $tige["illustration_de_la_tige"]['photo_tige']["id"]?: null;
                            $index_photos++;
                            $credit_photo = get_field('tige_illustration_de_la_tige_auteur_de_la_photo')?: null;
                            $source_photo = get_field('tige_illustration_de_la_tige_source_de_la_photo')?: null;
                            $refs_photo[] = ['index_photo' => $index_photos, 'id'=> $id_photo, 'credit_photo' => $credit_photo, 'source_photo' => $source_photo]
                            ?>
                            <div class="picture-ref"><?php echo $index_photos;?></div>
                        <?php endif; ?>
                        <p>La tige aérienne est <?php if ($tige['tige_aerienne_'] !== Constantes::VISIBLE) { echo $tige['tige_aerienne_'];?>, <?php }?>
                            <?php if ($tige['tige_aerienne_'] != Constantes::NON_VISIBLE):?>
                                <?php echo $type_tige;?>, <?php echo $tige['ramification'];?>
                                <?php if ($section_tige !== Constantes::PLEINE) {?>
                                    , à section <?php echo $section_tige;
                                }?>.
                                    <br>Sa surface est <?php echo $surface_tige;?> au moins quand elle est jeune.
                                <?php if ((($port_de_la_plante === Constantes::ARBRISSEAU) || ($port_de_la_plante === Constantes::ARBRE)) && (!empty($surface_ecorce))): ?>
                                    <br>L'écorce est <?php echo $surface_ecorce;?><?php if (!empty($tige['couleur_du_tronc'])) {?> et <?php echo $tige['couleur_du_tronc'];} ?>.
                                <?php endif; ?>
                            <?php endif; ?>
                            <?php $champ = get_field('appareil_vegetatif');
                                if (!empty($champ)){
                                    echo "Description de l'appareil végétatif : $champ";
                                }
                            ?>
                            
                        </p>
                    <?php } ?>
                </div>

<!--feuilles                -->
                <div id="leaf" class="characteristic">
                    <?php
                    $feuille = get_field('feuille');
                    
                    if (!empty($feuille)) { ?>
                        <?php $presence_feuilles = $feuille['presence_de_feuilles']; ?>
                        <?php if ('jamais visibles' === $presence_feuilles){ ?>
                            <h4 class="icon-title">
                                <div class="feuilles-icon icon"></div>Feuilles
                            </h4>
                            <p><?php echo $presence_feuilles; ?></p>
                        <?php }else{?>
                            
                            <?php $heteromorphisme_foliaire = get_field('heteromorphisme_foliaire'); ?>
                           
                            <?php if ((Constantes::FEUILLES_SEMBLABLES === $heteromorphisme_foliaire) || (Constantes::GRADIENT === $heteromorphisme_foliaire)): ?>

                                <?php $feuilles_aeriennes = get_field('feuilles_aeriennes'); ?>
                                <?php if(!empty($feuilles_aeriennes["illustration_de_la_feuille_aerienne"]['photo_de_feuilles_aeriennes'])): ?>
                                    <h4 class="icon-title">
                                        <div class="feuilles-icon icon"></div>Feuilles
                                    </h4>
                                    <?php
                                    $id_photo = $feuilles_aeriennes["illustration_de_la_feuille_aerienne"]['photo_de_feuilles_aeriennes']["id"]?: null;
                                    $index_photos++;
                                    $credit_photo = get_field('feuilles_aeriennes_illustration_de_la_feuille_aerienne_auteur_de_la_photo')?: null;
                                    $source_photo = get_field('feuilles_aeriennes_illustration_de_la_feuille_aerienne_source_de_la_photo')?: null;
                                    $refs_photo[] = ['index_photo' => $index_photos, 'id'=> $id_photo, 'credit_photo' => $credit_photo, 'source_photo' => $source_photo];
                                    ?>
                                    <div class="picture-ref"><?php echo $index_photos;?></div>
                                <?php endif; ?>
                                <p>
                                    Les feuilles sont disposées de façon <?php $phyllo= implode(' et ', $feuilles_aeriennes['phyllotaxie']); echo $phyllo;?>, et elles sont <?php echo implode(' et ', $feuilles_aeriennes['type_de_feuille']);?>.<br>
                                    <?php
                                    $type_feuille_multiple = 1 < count($feuilles_aeriennes['type_de_feuille']);
                                    $limbe = 'Le limbe %s est %s';
                                    $type_limbe = [];

                                    foreach ($feuilles_aeriennes['type_de_feuille'] as $type_feuille) {
                                        if (Constantes::SIMPLES === $type_feuille) {
                                            $text = implode('-', $feuilles_aeriennes[Constantes::LIMBE_FEUILLES_SIMPLES]);
                                            $text = getPubescence($feuilles_aeriennes,1,$text);
                                            $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des feuilles simples' : ''), trim($text));
                                        } else {
                                            $texte = implode('-', $feuilles_aeriennes[Constantes::LIMBE_FOLIOLES]);
                                            $texte = getPubescence($feuilles_aeriennes,2,$texte);
                                            $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des folioles' : ''), trim($texte));
                                        }
                                    }
                                    ?>

                                    <?php echo implode('. ', $type_limbe);?>, à marge foliaire <?php echo implode(' et ', $feuilles_aeriennes['marge_foliaire']);?> et à nervation <?php echo implode(' et ', $feuilles_aeriennes['nervation']);?>.<br>

                                    <?php
                                    $presence_petiole = $feuilles_aeriennes['petiole'];
                                    $petiole = Constantes::PRESENT === $presence_petiole ? $feuilles_aeriennes['longueur_du_petiole'] . (Constantes::ENGAINANT === $feuilles_aeriennes[Constantes::ENGAINANT] ? ', ' . $feuilles_aeriennes[Constantes::ENGAINANT] :'') : $presence_petiole;
                                    ?>

                                    Le pétiole est <?php echo $petiole; ?>.<br>

                                    <?php echo  Constantes::PRESENTS === $feuilles_aeriennes['stipules'] ? $feuilles_aeriennes['forme_et_couleur_des_stipules'] : '';?>

                                    <?php $port_de_la_plante = get_field('port_de_la_plante'); ?>
                                    <?php if (!empty($port_de_la_plante)): ?>
                                        <?php if (($port_de_la_plante === Constantes::ARBRISSEAU) || ($port_de_la_plante === Constantes::ARBRE)): ?>
                                            <?php echo  $feuilles_aeriennes['feuillage_'] ? 'Le feuillage est ' . $feuilles_aeriennes['feuillage_'].'.' : '';?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                    <?php 
                                        /* $champ = get_field('feuilles_aeriennes_appareil_vegetatif');
                                        
                                        if (!empty($champ)){
                                            echo " $champ";
                                        } */
                                    ?>
                                </p>

                            <?php elseif (Constantes::DEUX_FORMES === $heteromorphisme_foliaire): ?>

                                <?php $deux_formes_distinctes = get_field('deux_formes_distinctes'); ?>
                                <?php if ($deux_formes_distinctes === Constantes::FEUILLES_IMMERGEES_AERIENNES): ?>

                                    <!-- feuilles aeriennes-->
                                    <?php $feuilles_aeriennes = get_field('feuilles_aeriennes'); 
                                    ?>
                                    
                                    <?php if(!empty($feuilles_aeriennes)): ?>
                                        <h4 class="icon-title">
                                            <div class="feuilles-icon icon"></div>Feuilles aériennes
                                        </h4>
                                        <?php if(!empty($feuilles_aeriennes["illustration_de_la_feuille_aerienne"]['photo_de_feuilles_aeriennes'])): ?>
                                            <?php
                                            
                                            $id_photo = $feuilles_aeriennes["illustration_de_la_feuille_aerienne"]['photo_de_feuilles_aeriennes']["id"]?: null;
                                            $index_photos++;
                                            $credit_photo = get_field('feuilles_aeriennes_illustration_de_la_feuille_aerienne_auteur_de_la_photo')?: null;
                                            $source_photo = get_field('feuilles_aeriennes_illustration_de_la_feuille_aerienne_source_de_la_photo')?: null;
                                            $refs_photo[] = ['index_photo' => $index_photos, 'id'=> $id_photo, 'credit_photo' => $credit_photo, 'source_photo' => $source_photo];
                                            ?>
                                            <div class="picture-ref"><?php echo $index_photos;?></div>
                                        <?php endif; ?>
                                        <p>
                                            Les feuilles sont disposées de façon <?php echo implode(' et ', $feuilles_aeriennes['phyllotaxie']);?> et elles sont <?php echo implode(' et ', $feuilles_aeriennes['type_de_feuille']);?>.<br>
                                            <?php
                                            $type_feuille_multiple = 1 < count($feuilles_aeriennes['type_de_feuille']);
                                            $limbe = 'Le limbe %s est %s';
                                            $type_limbe = [];

                                            foreach ($feuilles_aeriennes['type_de_feuille'] as $type_feuille) {
                                                if (Constantes::SIMPLES === $type_feuille) {
                                                    $text = implode('-', $feuilles_aeriennes[Constantes::LIMBE_FEUILLES_SIMPLES]);
                                                    $text = getPubescence($feuilles_aeriennes,1,$text);
                                                    $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des feuilles simples' : ''), trim($text));
                                                } else {
                                                    $texte = implode('-', $feuilles_aeriennes[Constantes::LIMBE_FOLIOLES]);
                                                    $texte = getPubescence($feuilles_aeriennes,2,$texte);
                                                    $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des folioles' : ''), trim($texte));
                                                }
                                            }
                                            ?>

                                            <?php echo implode('. ', $type_limbe);?>, à marge foliaire <?php echo implode(' et ', $feuilles_aeriennes['marge_foliaire']);?> et à nervation <?php echo implode(' et ', $feuilles_aeriennes['nervation']);?>.<br>

                                            <?php
                                            $presence_petiole = $feuilles_aeriennes['petiole'];
                                            $petiole = Constantes::PRESENT === $presence_petiole ? $feuilles_aeriennes['longueur_du_petiole'] . (Constantes::ENGAINANT === $feuilles_aeriennes[Constantes::ENGAINANT] ? ', ' . $feuilles_aeriennes[Constantes::ENGAINANT] :'') : $presence_petiole;
                                            ?>

                                            Le pétiole est <?php echo $petiole; ?>.<br>
                                            
                                            <?php echo  Constantes::PRESENTS === $feuilles_aeriennes['stipules'] ? $feuilles_aeriennes['forme_et_couleur_des_stipules'] : '';?>

                                            <?php $port_de_la_plante = get_field('port_de_la_plante'); ?>
                                            <?php if (!empty($port_de_la_plante)): ?>
                                                <?php if (($port_de_la_plante === Constantes::ARBRISSEAU) || ($port_de_la_plante === Constantes::ARBRE)): ?>
                                                    <?php echo  $feuilles_aeriennes['feuillage_'] ? 'Le feuillage est ' . $feuilles_aeriennes['feuillage_'].'.' : '';?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                            
                                        </p>
                                    <?php endif; ?>

                                    <!--Feuilles des rameaux stériles-->
                                <?php elseif ($deux_formes_distinctes === Constantes::RAMEAUX_STERILES_FLEURIS): ?>
                                    
                                    <?php $feuilles_des_rameaux_steriles = get_field('feuilles_des_rameaux_steriles'); ?>
                                    <?php if(!empty($feuilles_des_rameaux_steriles)): ?>
                                        <h4 class="icon-title">
                                            <div class="feuilles-icon icon"></div>Feuilles des rameaux stériles
                                        </h4>
                                        <?php if(!empty($feuilles_des_rameaux_steriles["illustration_de_la_feuille_des_rameaux_steriles"]['photo_de_feuilles_des_rameaux_steriles'])): ?>
                                            <?php
                                            $id_photo = $feuilles_des_rameaux_steriles["illustration_de_la_feuille_des_rameaux_steriles"]['photo_de_feuilles_des_rameaux_steriles']["id"]?: null;
                                            $index_photos++;
                                            $credit_photo = get_field('feuilles_des_rameaux_steriles_illustration_de_la_feuille_des_rameaux_steriles_auteur_de_la_photo')?: null;
                                            $source_photo = get_field('feuilles_des_rameaux_steriles_illustration_de_la_feuille_des_rameaux_steriles_source_de_la_photo')?: null;
                                            $refs_photo[] = ['index_photo' => $index_photos, 'id'=> $id_photo, 'credit_photo' => $credit_photo, 'source_photo' => $source_photo];
                                            ?>
                                            <div class="picture-ref"><?php echo $index_photos;?></div>
                                        <?php endif; ?>
                                        <p>
                                            Les feuilles sont disposées de façon <?php echo implode(' et ', $feuilles_des_rameaux_steriles['phyllotaxie']);?> et elles sont <?php echo implode(' et ', $feuilles_des_rameaux_steriles['type_de_feuille']);?>.<br>
                                            <?php
                                            $type_feuille_multiple = 1 < count($feuilles_des_rameaux_steriles['type_de_feuille']);
                                            $limbe = 'Le limbe %s est %s';
                                            $type_limbe = [];

                                            foreach ($feuilles_des_rameaux_steriles['type_de_feuille'] as $type_feuille) {
                                                if (Constantes::SIMPLES === $type_feuille) {
                                                    $text = implode('-', $feuilles_des_rameaux_steriles[Constantes::LIMBE_FEUILLES_SIMPLES]);
                                                    $text = getPubescence($feuilles_des_rameaux_steriles,1,$text);
                                                    $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des feuilles simples' : ''), trim($text));
                                                } else {
                                                    $texte = implode('-', $feuilles_des_rameaux_steriles[Constantes::LIMBE_FOLIOLES]);
                                                    $texte = getPubescence($feuilles_des_rameaux_steriles,2,$texte);
                                                    $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des folioles' : ''), trim($texte));
                                                }
                                            }
                                            ?>

                                            <?php echo implode('. ', $type_limbe);?>, à marge foliaire <?php echo implode(' et ', $feuilles_des_rameaux_steriles['marge_foliaire']);?> et à nervation <?php echo implode(' et ', $feuilles_des_rameaux_steriles['nervation']);?>.<br>

                                            <?php
                                            $presence_petiole = $feuilles_des_rameaux_steriles['petiole'];
                                            $petiole = Constantes::PRESENT === $presence_petiole ? $feuilles_des_rameaux_steriles['longueur_du_petiole'] . (Constantes::ENGAINANT === $feuilles_des_rameaux_steriles[Constantes::ENGAINANT] ? ', ' . $feuilles_des_rameaux_steriles[Constantes::ENGAINANT] :'') : $presence_petiole;
                                            ?>

                                            Le pétiole est <?php echo $petiole; ?>.<br>

                                            <?php echo  Constantes::PRESENTS === $feuilles_des_rameaux_steriles['stipules'] ? $feuilles_des_rameaux_steriles['forme_et_couleur_des_stipules'] : '';?>

                                            <?php $port_de_la_plante = get_field('port_de_la_plante'); ?>
                                            <?php if (!empty($port_de_la_plante)): ?>
                                                <?php if (($port_de_la_plante === Constantes::ARBRISSEAU) || ($port_de_la_plante === Constantes::ARBRE)): ?>
                                                    <?php echo  $feuilles_des_rameaux_steriles['feuillage_'] ? 'Le feuillage est ' . $feuilles_des_rameaux_steriles['feuillage_'].'.' : '';?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </p>
                                    <?php endif; ?>

                                <?php endif; ?>

                            <?php endif; ?>

                        <?php } ?>
                        
                    <?php } ?>
                </div>
<!--Inflorescence-->
                <div id="inflo" class="characteristic">
                    <?php  if (!empty(get_field('inflorescence'))) { ?>
                        <h4 class="icon-title">
                            <div class="inflorescence-icon icon"></div>Inflorescence
                        </h4>
                        <?php $inflorescence = get_field('inflorescence');?>
                        <p>Les fleurs sont <?php echo $organisation = $inflorescence['organisation_des_fleurs'];?>.
                        <?php if($organisation === Constantes::ORGANISEES_EN_INFLORESCENCES) {
                            if($inflorescence['categorie_'] != 'autre') {
                                ?> L’inflorescence est <?php echo $inflorescence['categorie_']; ?>.</p>
                            <?php } else {
                                ?> L’inflorescence est <?php echo $inflorescence['description']; ?>.</p>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </div>

<!--Fruits-->
                <div id="frutty" class="characteristic">
                    <?php  if (!empty(get_field('fruit'))) { ?>
                        <h4 class="icon-title">
                            <div class="fruits-icon icon"></div>Fruits
                        </h4>
                        <?php $fruit = get_field('fruit');?>
                        <?php if(!empty($fruit["illustration_du_fruit"]['photo'])): ?>
                            <?php
                            
                            $index_fruit_photo = $index_photos+1;
                            $fruit_photo = $fruit["illustration_du_fruit"]['photo'];
                            if (!empty(get_field('fleur_male')) && (!empty(get_field('fleur_male')["illustration_de_la_fleur_male_ou_de_linflorescence"]['photo_de_fleur_male']))) {
                                if (!empty(get_field('systeme_sexuel')) && (get_field('systeme_sexuel') == Constantes::MONOIQUE) || (get_field('systeme_sexuel') == Constantes::DIOIQUE) || (get_field('systeme_sexuel') == Constantes::ANDROMONOIQUE) || (get_field('systeme_sexuel') == Constantes::ANDRODIOIQUE) || (get_field('systeme_sexuel') == Constantes::ANDROGYNOMONIQUE) || (get_field('systeme_sexuel') == Constantes::ANDROGYNODIOIQUE)) {
                                    $index_fruit_photo++;
                                }
                            }
                            if (!empty(get_field('fleur_femelle')) && (!empty(get_field('fleur_femelle')["illustration_de_la_fleur_femelle_ou_de_linflorescence"]['photo_de_fleur_femelle']))) {
                                if (!empty(get_field('systeme_sexuel')) && (get_field('systeme_sexuel') == Constantes::MONOIQUE) || (get_field('systeme_sexuel') == Constantes::DIOIQUE) || (get_field('systeme_sexuel') == Constantes::GYNOMONOIQUE) || (get_field('systeme_sexuel') == Constantes::GYNODIOIQUE) || (get_field('systeme_sexuel') == Constantes::ANDROGYNOMONIQUE) || (get_field('systeme_sexuel') == Constantes::ANDROGYNODIOIQUE)) {
                                    $index_fruit_photo++;
                                }
                            }
                            if (!empty(get_field('fleur_bisexuee')) && (!empty(get_field('fleur_bisexuee')['illustration_de_la_fleur_bisexuee']['photo_de_fleur_bisexuee']))) {
                                if (!empty(get_field('systeme_sexuel')) && (get_field('systeme_sexuel') == Constantes::HERMAPHRODITE) || (get_field('systeme_sexuel') == Constantes::ANDROMONOIQUE) || (get_field('systeme_sexuel') == Constantes::GYNOMONOIQUE) || (get_field('systeme_sexuel') == Constantes::ANDRODIOIQUE) || (get_field('systeme_sexuel') == Constantes::GYNODIOIQUE) || (get_field('systeme_sexuel') == Constantes::ANDROGYNOMONIQUE) || (get_field('systeme_sexuel') == Constantes::ANDROGYNODIOIQUE)) {
                                    $index_fruit_photo++;
                                }
                            }
                            ?>
                            <div class="picture-ref"><?php echo $index_fruit_photo;?></div>
                        <?php endif; ?>
                        <p>Le fruit est <?php echo $fruit['type_de_fruit'];?>. <?php $complement = $fruit['precisions_sur_la_description_des_fruits']; if (!empty($complement)){ echo $complement;}?></p>
                    <?php } ?>
                </div>

<!--  Fleur bisexuée-->
                <?php $fleur_bisexuee =  get_field('fleur_bisexuee') ?: null;
                $a_fleur_male = false;
                $a_fleur_femelle = false;
                $fleur_male = get_field('fleur_male');
                
                $fleur_femelle = get_field('fleur_femelle');
                $systeme=get_field('systeme_sexuel');
                if ($systeme === Constantes::GYNOMONOIQUE || $systeme=== Constantes::GYNODIOIQUE){
                    $fleur_male=null;
                }
                if($systeme=== Constantes::HERMAPHRODITE){
                    $fleur_male=null;
                    $fleur_femelle=null;
                }
               
                
                ?>
                <?php if (!empty(get_field('systeme_sexuel')) && (get_field('systeme_sexuel') == Constantes::HERMAPHRODITE ) || (get_field('systeme_sexuel') == Constantes::ANDROMONOIQUE ) || (get_field('systeme_sexuel') == Constantes::GYNOMONOIQUE ) || (get_field('systeme_sexuel') == Constantes::ANDRODIOIQUE ) || (get_field('systeme_sexuel') == Constantes::GYNODIOIQUE ) || (get_field('systeme_sexuel') == Constantes::ANDROGYNOMONIQUE ) || (get_field('systeme_sexuel') == Constantes::ANDROGYNODIOIQUE )): ?>
                    <div id ="fl_bi" class="characteristic 
                    <?php if(get_field('fleur_bisexuee')){
                        if($fleur_male && !empty($fleur_male)){
                            $a_fleur_male=true;
                            echo 'fleur-monosexe';
                        }elseif($fleur_femelle && !empty($fleur_femelle)){ 
                            $a_fleur_femelle=true;
                            echo 'fleur-monosexe';
                        }else{
                            echo 'fleur-bisexuee'; }
                        }

                        
                        ?>">
                        <h4 class="icon-title">
                            <div class="fleur-bisexuee-icon icon"></div>Fleur bisexuée
                        </h4>
                        
                        <?php if(!empty($fleur_bisexuee['illustration_de_la_fleur_bisexuee']['photo_de_fleur_bisexuee'])): ?>
                            <?php
                            $id_photo = $fleur_bisexuee['illustration_de_la_fleur_bisexuee']['photo_de_fleur_bisexuee']["id"]?: null;
                            $index_photos++;
                            $credit_photo = get_field('fleur_bisexuee_illustration_de_la_fleur_bisexuee_auteur_de_la_photo')?: null;
                            $source_photo = get_field('fleur_bisexuee_illustration_de_la_fleur_bisexuee_source_de_la_photo')?: null;
                            $refs_photo[] = ['index_photo' => $index_photos, 'id'=> $id_photo, 'credit_photo' => $credit_photo, 'source_photo' => $source_photo];
                            ?>
                            <div class="picture-ref"><?php echo $index_photos;?></div>
                        <?php endif; ?>
                        <input id="fm_txt" class='hidden' value='<?php echo $a_fleur_male; ?>'/></p>
                        <input id="ff_txt" class='hidden' value='<?php echo $a_fleur_femelle; ?>'/></p>
                        <p>
                            <?php if(Constantes::PRESENT !== $fleur_bisexuee['perianthe']){ ?>
                                Périanthe absent ; 
                            <?php }else{ ?>
                                Fleur <?php echo implode(' et ', $fleur_bisexuee['symetrie']); ?> ;
                                <?php
                                if (isset($fleur_bisexuee['composition_du_perianthe'])){
                                    $composition = $fleur_bisexuee['composition_du_perianthe'];
                                    $perianthe = displayPerianthe($composition,$fleur_bisexuee);
                                }
                                ?>
                                <?php if(isset($perianthe)): ?>
                                    périanthe composé de <?php echo $perianthe; ?>
                                <?php endif; ?>
                            <?php } ?>
                            
                            <?php if(!empty($fleur_bisexuee['androcee'])): { ?>
                                androcée composé de <?php $etamines = $fleur_bisexuee['androcee']; echo getValueOrganesFloraux($etamines,"étamines",$fleur_bisexuee['soudure_de_landrocee_']); ?>
                                <?php echo (Constantes::ANDROCEE_SOUDEE_COROLLE === $fleur_bisexuee['soudure_androcee-corolle'] ? $fleur_bisexuee['soudure_androcee-corolle'] . ', ' : ''). (Constantes::SOUDEES_PERIGONE === $fleur_bisexuee['soudure_androcee-perigone'] ? $fleur_bisexuee['soudure_androcee-perigone'] . ', ' : ''); ?>
                                <?php echo (Constantes::PRESENTS === $fleur_bisexuee['staminodes'] ? $fleur_bisexuee['nombre_de_staminodes'] . ' staminode(s) ; ' : '');
                            } ?>
                            <?php endif; ?>
                            <?php if(!empty($fleur_bisexuee['gynecee'])): { ?>
                                gynécée composé de <?php $carpelles = $fleur_bisexuee['gynecee']; echo getValueOrganesFloraux($carpelles,"carpelles",getSoudureCarpelles($fleur_bisexuee)); ?>
                                ovaire <?php echo $fleur_bisexuee['ovaire_']; ?>.
                            <?php } ?>
                            <?php endif; ?>
                            La couleur principale de la fleur est le <?php echo $fleur_bisexuee['couleur_principale']; ?>.
                            <?php if (Constantes::PUBESCENTE === $fleur_bisexuee['pubescence']) {
                                echo "La fleur est ".$fleur_bisexuee['pubescence'];?>
                                <?php if (!empty($fleur_bisexuee['localisation_des_poils_']) && ($fleur_bisexuee['localisation_des_poils_'] != array(Constantes::TOUS_LES_ORGANES_FLORAUX))) {
                                    echo ' sur '.implode(', ' , $fleur_bisexuee['localisation_des_poils_']).'.'; }
                                else { echo '.'; }}?>
                            <?php echo $fleur_bisexuee['autre_caractere'];?>
                        </p>
                    </div>
                <?php endif ?>

<!--Fleur femelle-->
               

                <?php if (!empty(get_field('systeme_sexuel'))): ?>
                    <?php if ((get_field('systeme_sexuel') === "monoïque" ) || (get_field('systeme_sexuel') === "dioïque" ) || (get_field('systeme_sexuel') === "gynomonoïque" ) || (get_field('systeme_sexuel') === "gynodioïque" ) || (get_field('systeme_sexuel') === "androgynomonoïque" ) || (get_field('systeme_sexuel') === "androgynodioïque" )): ?>
                        <div id="fl_fem" class="characteristic fleur-monosexe">
                            <h4 class="icon-title">
                                <div class="fleur-femelle-icon icon"></div>Fleur femelle
                            </h4>
                            <?php if(!empty($fleur_femelle["illustration_de_la_fleur_femelle_ou_de_linflorescence"]['photo_de_fleur_femelle'])): ?>
                                <?php
                                
                                $id_photo = $fleur_femelle["illustration_de_la_fleur_femelle_ou_de_linflorescence"]['photo_de_fleur_femelle']["id"]?: null;
                                $index_photos++;
                                $credit_photo = get_field('fleur_femelle_illustration_de_la_fleur_femelle_ou_de_linflorescence_auteur_de_la_photo')?: null;
                                $source_photo = get_field('fleur_femelle_illustration_de_la_fleur_femelle_ou_de_linflorescence_source_de_la_photo')?: null;
                                $refs_photo[] = ['index_photo' => $index_photos, 'id'=> $id_photo, 'credit_photo' => $credit_photo, 'source_photo' => $source_photo];
                                ?>
                                <div class="picture-ref"><?php echo $index_photos;?></div>
                            <?php endif; ?>
                            <p>
                                <?php if(Constantes::PRESENT !== $fleur_femelle['perianthe']){ ?>
                                    Périanthe absent ; 
                                <?php } else { ?>
                                    Fleur <?php echo implode(' et ', $fleur_femelle['symetrie']); ?> ;
                                    <?php
                                    $perianthe = displayPerianthe($fleur_femelle['differenciation_du_perianthe'],$fleur_femelle)
                                    ?>

                                    périanthe composé de <?php echo $perianthe;
                                } ?>
                                
                                <?php if(!empty($fleur_femelle['gynecee'])): { ?>
                                    gynécée composé de <?php $carpelles = $fleur_femelle['gynecee']; echo getValueOrganesFloraux($carpelles,"carpelles",getSoudureCarpelles($fleur_femelle)); ?>
                                    ovaire <?php echo $fleur_femelle['ovaire_']; ?>.
                                    La couleur principale de la fleur est <?php echo $fleur_femelle['couleur_principale']; ?>.
                                    <?php if (Constantes::PUBESCENTE === $fleur_femelle['pubescence']) {
                                        echo "La fleur est ".$fleur_femelle['pubescence'];?>
                                        <?php if (!empty($fleur_femelle['localisation_des_poils_']) && ($fleur_femelle['localisation_des_poils_'] != array(Constantes::TOUS_LES_ORGANES_FLORAUX))) {
                                            echo ' sur '.implode(', ' , $fleur_femelle['localisation_des_poils_']).'.'; }
                                        else { echo '.'; }}?>
                                    <?php echo $fleur_femelle['autre_caractere'];
                                }?>
                                <?php endif; ?>
                            </p>
                        </div>
                    <?php endif;?>
                <?php endif ?>

<!--fleur male-->
                
                <?php if (!empty(get_field('systeme_sexuel')) && (get_field('systeme_sexuel') == Constantes::MONOIQUE ) || (get_field('systeme_sexuel') == Constantes::DIOIQUE ) || (get_field('systeme_sexuel') == Constantes::ANDROMONOIQUE ) || (get_field('systeme_sexuel') == Constantes::ANDRODIOIQUE ) || (get_field('systeme_sexuel') == Constantes::ANDROGYNOMONIQUE ) || (get_field('systeme_sexuel') == Constantes::ANDROGYNODIOIQUE )): ?>
                <div id="fl_male" class="characteristic fleur-monosexe">
                    <h4 class="icon-title">
                        <div class="fleur-male-icon icon"></div>Fleur mâle
                    </h4>
                    <?php if(!empty($fleur_male["illustration_de_la_fleur_male_ou_de_linflorescence"]['photo_de_fleur_male'])): ?>
                        <?php
                        $id_photo = $fleur_male["illustration_de_la_fleur_male_ou_de_linflorescence"]['photo_de_fleur_male']["id"]?: null;
                        $index_photos++;
                        $credit_photo = get_field('fleur_male_illustration_de_la_fleur_male_ou_de_linflorescence_auteur_de_la_photo')?: null;
                        $source_photo = get_field('fleur_male_illustration_de_la_fleur_male_ou_de_linflorescence_source_de_la_photo')?: null;
                        $refs_photo[] = ['index_photo' => $index_photos, 'id'=> $id_photo, 'credit_photo' => $credit_photo, 'source_photo' => $source_photo];
                        ?>
                        <div class="picture-ref"><?php echo $index_photos;?></div>
                    <?php endif; ?>
                    <p>
                        <?php if(Constantes::PRESENT !== $fleur_male['perianthe']): { ?>
                            Périanthe absent ; 
                        <?php } else: { ?>
                            Fleur <?php echo implode(' et ', $fleur_male['symetrie']); ?>;
                            <?php
                            $composition = $fleur_male['differenciation_du_perianthe'];
                            $perianthe = displayPerianthe($composition,$fleur_male)
                            ?>
                            périanthe composé de <?php echo $perianthe;
                        } ?>
                        <?php endif; ?>
                        <?php if(!empty($fleur_male['androcee'])): { ?>
                            androcée composé de <?php $etamines = $fleur_male['androcee']; echo getValueOrganesFloraux($etamines,'étamines',$fleur_male['soudure_de_landrocee_']); ?>
                        <?php echo (Constantes::ANDROCEE_SOUDEE_COROLLE === $fleur_male['soudure_androcee-corolle'] ? $fleur_male['soudure_androcee-corolle'] . ', ' : '').
                                (Constantes::SOUDEES_PERIGONE === $fleur_male['soudure_androcee-perigone'] ? $fleur_male['soudure_androcee-perigone'] . ', ' : ''); ?>
                            <?php echo (Constantes::PRESENTS === $fleur_male['staminodes'] ? $fleur_male['nombre_de_staminodes'] . ' staminode(s) ; ' : ''); ?>
                            La couleur principale de la fleur est <?php echo $fleur_male['couleur_principale']; ?>.
                            <?php if (Constantes::PUBESCENTE === $fleur_male['pubescence']) {
                                echo "La fleur est ".$fleur_male['pubescence'];?>
                                <?php if (!empty($fleur_male['localisation_des_poils_']) && ($fleur_male['localisation_des_poils_'] != array(Constantes::TOUS_LES_ORGANES_FLORAUX))) {
                                    echo ' sur '.implode(', ' , $fleur_male['localisation_des_poils_']).'.'; }
                                else { echo '.'; }}?>
                            <?php echo $fleur_male['autre_caractere'];
                        } ?>
                        <?php endif; ?>
                    </p>
                </div>
                <?php endif ?>
            </main>

            <footer>
                <div class="footer-section">
                    <div class="center le-saviez-vous">
                        <h4 class="grand-hotel">Le saviez-vous ?</h4>
                        <p><?php (!empty(get_field('feuille'))) ? the_field('le_saviez-vous_') : "";?></p>
                    </div>
                </div>
                <?php if(0 < $index_photos): ?>
                    <?php if(!empty($fruit_photo)): ?>
                        <?php
                        $id_photo = $fruit_photo["id"]?: null;
                        $index_photos++;
                        $credit_photo = get_field('fruit_illustration_du_fruit_auteur_de_la_photo')?: null;
                        $source_photo = get_field('fruit_illustration_du_fruit_source_de_la_photo')?: null;
                        $refs_photo[] = ['index_photo' => $index_photos, 'id'=> $id_photo, 'credit_photo' => $credit_photo, 'source_photo' => $source_photo]
                        ?>
                    <?php endif; ?>
                    <div class="footer-section">
                        <ol class="characteristic-photos">
                            <?php for ($i = 1;$i <= $index_photos; $i++): ?>
                                <?php if($i == 6) : {break;} ?>
                                <?php endif; ?>
                                <li><?php echo wp_get_attachment_image($refs_photo[$i]['id'], [148, 148]); ?><div class="picture-ref"><?php echo $i; ?></div></li>

                            <?php endfor; ?>
                        </ol>
                    </div>
                <?php endif; ?>
            </footer>
        </div>
        <div id="page-2" class="page page-2">
            <header>
                <h3 class="icon-title">
                    <div class="periode-icon icon"></div>période de <span class="green">floraison</span> et de <span class="purple">fructification</span>
                </h3>
                <div class="calendar">
                    <?php $months = ['Janvier','Février','Mars','Avril','Mai','Juin','Juillet','Août','Septembre','Octobre','Novembre','Décembre']; ?>
                    <?php foreach ($months as $m => $month): ?>
                        <?php
                        $periodes_flor = get_field('periode_de_floraison') ?: null;
                        $flor_ok = false;
                        if($periodes_flor) {
                            $index_flor = 0;
                            while (isset($periodes_flor[$index_flor]) && !$flor_ok) {
                                $flor_ok = $month === $periodes_flor[$index_flor];
                                $index_flor++;
                            }
                        }

                        $periodes_fruct = get_field('periode_de_fructification') ?: null;
                        $fruct_ok = false;
                        if($periodes_fruct) {
                            $index_fruct = 0;
                            while (isset($periodes_fruct[$index_fruct]) && !$fruct_ok) {
                                $fruct_ok = $month === $periodes_fruct[$index_fruct];
                                $index_fruct++;
                            }
                        }
                        ?>
                        <div class="month"><?php echo substr($month,0,1); ?></div>
                        <div class="floraison<?php echo $flor_ok ? '-vert' : ''; ?>-icon icon"></div>
                        <div class="fructification<?php echo $fruct_ok ? '-mauve' : ''; ?>-icon icon"></div>
                    <?php endforeach; ?>
                </div>
            </header>
            <main class="pdf-container">
<!--Aire répartition-->
                <?php if (!empty(get_field('cultivee_en_france_')) || !empty(get_field('carte_de_metropole')) || !empty(get_field('repartition_mondiale')) || !empty(get_field('indigenat_')) || !empty(get_field('statut_uicn'))): ?>
                <div class="characteristic">
                    <h3 class="icon-title">
                        <div class="location-icon icon"></div>aire de répartition et statut
                    </h3>
                    <div class="pdf-inline-image">
                    <?php if (!empty(get_field('cultivee_en_france_'))) { ?>
                        <?php $cultivee_en_france = get_field('cultivee_en_france_'); ?>
                        <p>En France métropolitaine, la plante est présente <?php echo $cultivee_en_france; ?><?php echo (Constantes::ETAT_SAUVAGE === $cultivee_en_france ? ' où elle est ' . implode (', ', get_field('indigenat_')) . '.' : '.'); ?> Statut de protection : <br><?php the_field('statut_uicn'); ?>.</p>
                    <?php } ?>
                    <?php if (!empty(get_field('carte_de_metropole'))) :?>
                            <div class="characteristic-photos section-image">
                            <?php echo wp_get_attachment_image(get_field('carte_de_metropole')['id'], [100, 100]); ?>
                        </div>
                    <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>

                <?php if (!empty(get_field('amplitude_altitudinale')) || !empty(get_field('affinites_ecologiques')) || !empty(get_field('habitat_preferentiel')) || !empty(get_field('systeme_de_reproduction')) || !empty(get_field('pollinisation')) || !empty(get_field('dispersion'))): ?>
<!--Ecologie-->
                    <div class="characteristic pdf-ecologie">
                        <h3 class="icon-title">
                            <div class="ecologie-icon icon"></div>écologie
                        </h3>
                        <?php if (!empty(get_field('amplitude_altitudinale'))) :?><p>Altitude : <?php echo get_field('amplitude_altitudinale'); ?>.<?php endif; ?>
						<?php if (!empty(get_field('affinites_ecologiques'))) :?>Affinités écologiques : <?php echo get_field('affinites_ecologiques') ? implode(', ', get_field('affinites_ecologiques')) : "";
						?>.<?php endif; ?>
						
						<?php if (!empty(get_field('habitat_preferentiel'))) :?>Habitat(s) : <?php the_field('habitat_preferentiel'); ?>.<?php endif; ?>
						
						<?php if ((!empty(get_field('systeme_de_reproduction'))) || (!empty(get_field('pollinisation')))) :?>Plante : <?php endif; ?>
						
						<?php if (!empty(get_field('systeme_de_reproduction'))) :?> Système de reproduction <?php
							the_field('systeme_de_reproduction'); ?>, <?php endif; ?>
						
						<?php if (!empty(get_field('pollinisation'))) :?> à pollinisation <?php the_field('pollinisation'); ?>, <?php endif; ?>
						
						<?php if (!empty(get_field('dispersion'))) :?> dispersion des graines ou des fruits <?php
						echo get_field('dispersion') ? implode(', ', get_field('dispersion')) : ""; ?>.</p><?php endif;?>
                    </div>
                <?php endif; ?>
<!--Préférence physico chimiques-->     
                <div class="characteristic">
                    
                    <?php
                        $champs = [];
                        array_push($champs,"lumiere");
                        array_push($champs,"humidite_atmospherique");
                        array_push($champs,"continentalite");
                        array_push($champs,"reaction_ph");
                        array_push($champs,"humidite_du_sol");
                        array_push($champs,"texture_du_sol");
                        array_push($champs,"richesse_en_azote_n");
                        array_push($champs,"salinite");
                        $has_field=false;
                        foreach($champs as $value){
                            
                            if (!empty(get_field("preferences_physico-chimiques_$value"))){
                                
                                $has_field = true;
                                break;
                            }
                        }
                        
                        if ($has_field){
                            $champs_agros_eco = getChampsAgroEcoPourSvg();
                            genererSVG(get_the_title(), $champs_agros_eco); ?>
                            <h4 class="icon-title">
                                Préférences physico-chimiques
                            </h4>
                            <img class="graph-agro-eco" width="300" height="250" src="<?php echo (wp_upload_dir()['baseurl']. "/graphs_agro_eco/".get_the_title().".svg")?>">
                            <?php
                        }
                      
                    ?>
                    
                </div>
            
<!--Ne pas confondre-->
                
                    <div class="characteristic">
                        <h3 class="icon-title">
                            <div class="ne-pas-confondre-icon icon"></div>ne pas confondre avec
                            <div class="picture-ref"><?php echo $index_photos+1;?></div>
                        </h3>
                        <div class="pdf-inline-image">
                        <p><span class="pdf-espece-pas-confondre"><?php
                            $espece = get_field('nom_despece');
                            echo $espece;
                            echo ('</span></br>');
                            the_field('description');
                            ?></p>
                        <?php $photo = get_field('illustration_de_la_plante_avec_risque_de_confusion_photo')?: null; ?>
                        <?php if (!empty($photo)): ?>
                            <?php
                            $id_photo = $photo['id']?: null;
                            $index_photos++;
                            $credit_photo = get_field('illustration_de_la_plante_avec_risque_de_confusion_auteur_de_la_photo')?: null;
                            $source_photo = get_field('illustration_de_la_plante_avec_risque_de_confusion_source_de_la_photo')?: null;
                            $refs_photo[] = ['index_photo' => $index_photos, 'id'=> $id_photo, 'credit_photo' => $credit_photo, 'source_photo' => $source_photo];
                            ?>
                           
                            <div class="characteristic-photos">
                                <?php echo wp_get_attachment_image($photo['id'], [148, 148]); ?>
                            </div>
                        <?php endif; ?>
                        </div>
                    </div>
                

<!--Valeurs ecologiques histo et locale-->
                <?php $description = get_field('description')?: null; ?>
                <?php $valeurs_ecolo = get_field('valeurs_ecologiques_historiques_et_locales') ?: null; ?>
                <?php if ($valeurs_ecolo): ?>
                    <div class="characteristic">
                        <h4 class="icon-title">
                            Valeurs écologiques, historiques et locales
                        </h4>
                        <p>
                            <?php
                            if ($valeurs_ecolo['cette_plante_est-elle_connue_pour_avoir_ete_ou_etre_actuellement_cultivee_']){
                                echo 'Cette plante a été ou est cultivée entre autres pour ';
                                $usages = $valeurs_ecolo['cette_plante_a_t_elle_ete_ou_est_elle_cultivee_pour_les_usages_suivants'];
                                if (!empty($usages)){
                                    $nb_usage = 0;
                                    $usage_total = count($usages);
                                    foreach ($usages as $usage){
                                        if ($nb_usage <= 3){
                                            echo ($usage);
                                            $nb_usage++;
                                            if ($nb_usage == $usage_total || $nb_usage == 3){
                                                echo '.';
                                            } else {
                                                echo ', ';
                                            }
                                        }
                                    }
                                }
                            } else {
                                echo 'Cette plante n\'est pas connue pour être ou avoir été cultivée.';
                            }

                            $autre_usages = $valeurs_ecolo['plante_connue_pour_des_proprietes_autres_que_la_toxicite_cf_categorie_interaction_avec_le_vivant'];
                            if (!empty($autre_usages)){
                                echo ' Elle est connue entre autres pour ';
                                $nb_autre_usages = 0;
                                $autres_usage_total = count($autre_usages);
                                foreach ($autre_usages as $autre_usage){
                                    if ($nb_autre_usages <= 3){
                                        echo $autre_usage;
                                        $nb_autre_usages++;
                                        if ($nb_autre_usages == $autres_usage_total || $nb_autre_usages == 3){
                                            echo '.';
                                        } else {
                                            echo ', ';
                                        }
                                    }
                                }
                            }
                            
                            $protection_statut_france = $valeurs_ecolo['quel_est_le_statut_de_protection_france_metropolitaine'];
                            $protection_statut = $valeurs_ecolo['statut_de_protection'];
                            if (!empty($protection_statut)){
                                echo (' Elle ');
                                echo $protection_statut;
                                echo '.';
                                if (!empty($protection_statut_france)){
                                    echo (' Elle a le statut de protection ');
                                    echo $protection_statut_france;
                                    echo (' sur tout le territoire.');
                                }
                            }
                            ?>
                        </p>
                    </div>
                <?php endif; ?>
            </main>
            <footer>
                <div class="pdf-container">
                    <div class="footer-section info">
                        <div class="columns columns-left">
                            <div>
                                <h5 class="inline">auteur(e-s) :</h5>
                                <p class="inline"><?php echo get_the_author(); ?></p>
                            </div>
                            <div>
                                <h5 class="inline">date de mise à jour :</h5>
                                <p class="inline">
                                    <?php $post = get_post();
                                        $date = new DateTime($post->post_modified);
                                        $date_crea = new DateTime($post->post_date);
                                        $post_date = getDateInFrench($date->format('d F Y'));
                                        $post_date_crea = getDateInFrench($date_crea->format('d F Y')); 
                                        echo $post_date;
                                    ?>
                                </p>
                            </div>

                            <h5>crédits photos :</h5>

                            <?php if(0 < $index_photos): ?>
                                <ol class="credits-photo">
                                    <?php foreach ($refs_photo as $i => $photo) :
                                        if ($i > 0) :
                                        ?>
                                        <li><?php echo $i.' - '. ($photo['credit_photo'] ?: 'nc') .', '. ($photo['source_photo'] ?: null); ?></li>
                                    <?php endif; endforeach; ?>
                                </ol>
                            <?php endif; ?>
                        </div>
                        <div class="columns columns-right">
                            <h5>référence(s) :</h5>

                            <?php for($i = 1; $i <= 3; $i++): ?>
                                <?php $ref = get_field('reference_'.strval($i)) ?: null;?>
                                <?php if($ref):?>
                                    <div class="ref-liste">
                                        <p class="ref-liste-item"><?php echo $ref; ?></p>
                                    </div>
                                <?php endif; ?>
                            <?php endfor; ?>

                        </div>
                    </div>

                    <div class="footer-section-logos">
                        <div class="footer-logos-left">
                            <div class="footer-logo"><img src="<?php echo get_template_directory_uri(); ?>/images/logo-botascopia.png" alt="Logo Botascopia"></div>
                        </div>
                        <div class="footer-logos-right">
                            <div>Fondateurs :  </div>
                            <div class="footer-logo"><img class="logo-saclay" src="<?php echo get_template_directory_uri(); ?>/images/logo-saclay.png" alt="Logo Université Paris-Saclay"></div>
                            <div class="footer-logo"><img src="<?php echo get_template_directory_uri(); ?>/images/logo-tela.png" alt="Logo Tela Botanica"></div>
                        </div>
                    </div>
                </div>
            </footer>

        </div>
    <?php endwhile;?>
<?php else: ?>
    <p>Nom de fiche invalide</p>
<?php endif; ?>
<input id="systeme" value='<?php echo $systeme; ?>' class="hidden"/>
</body>
<script src='<?php echo get_template_directory_uri() . '/assets/scripts/fiche_pdf.js' ?>'></script>
</html>
