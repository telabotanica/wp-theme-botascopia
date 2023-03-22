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
    ]);
    ?>
    <?php while (have_posts()) : the_post(); ?>
        <div id="page-1" class="page page-1">

            <header>
                <div class="container">
                    <h1><?php the_field('nom_scientifique');?></h1>
                    <h2><?php the_field('nom_vernaculaire');?> — <?php the_field('famille');?></h2>
                    <div class="characteristic">
                        <h3 class="icon-title">
                            <div class="description-icon icon"></div>description morphologique
                        </h3>
                        <p><?php if (!empty(get_field('port_de_la_plante'))) { echo ucfirst(get_field('port_de_la_plante')).", "; 
                             if (!empty(get_field('systeme_sexuel')) && get_field('systeme_sexuel') !== "hermaphrodite" ) { echo get_field('systeme_sexuel').", " ;} 
                             if (!empty(get_field('mode_de_vie')) && get_field('mode_de_vie') !== "terrestre" ) { echo implode(', ', get_field('mode_de_vie')).", " ; }
                             if (!empty(get_field('type_de_developpement'))) { echo implode(', ', get_field('type_de_developpement')).", " ;} 
                             if (!empty(get_field('forme_biologique'))) { echo implode(', ', get_field('forme_biologique')).", " ;} ?>
                            qui peut atteindre jusqu'à <?php the_field('hauteur_maximale'); ?> de haut. 
                            <?php if (!empty(get_field(' pilosite_de_la_plante_entiere'))) { echo "Cette plante est ".get_field(' pilosite_de_la_plante_entiere').".";} ?>
                            <?php } ?>
                        </p>
                    </div>
                </div>
                <?php
                $index_photos = 0;
                $fruit_photo=null;
                $refs_photo = array();
                if (!empty(get_field('photo_de_la_plante_entiere'))) {
                    $refs_photo[] = get_field('photo_de_la_plante_entiere');
                }
                ?>
                <div class="round-picture" style="background-image: url('<?php echo wp_get_attachment_image_url($refs_photo[0], 'large'); ?>'); background-size: cover;">
            </header>

            <main class="container">
                <div class="characteristic">
                    <?php
                    $tige = get_field('tige');
                    if (!empty($tige)) {
                        $type_tige = implode(', ', $tige['type_de_tige']);
                        $section_tige = implode('-', $tige['section_de_la_tige']);
                        $surface_tige = implode(', ', $tige['surface_de_la_tige_jeune']);
                        $port_de_la_plante = get_field('port_de_la_plante');
                        if (!empty($port_de_la_plante)) {
                            if (($port_de_la_plante === 'arbrisseau') || ($port_de_la_plante === 'arbre')) {
                                if (!empty($tige['surface_de_lecorce'])) {
                                    $surface_ecorce = implode(', ', $tige['surface_de_lecorce']);
                                }
                            }
                        }
                        ?>
                        <h4 class="icon-title">
                            <div class="tige-icon icon"></div>Tige
                        </h4>
                        <?php if(!empty($tige['photo_tige'])): ?>
                            <?php
                            $refs_photo[] = $tige['photo_tige']["id"];
                            $index_photos++;
                            ?>
                            <div class="picture-ref"><?php echo $index_photos;?></div>
                        <?php endif; ?>
                        <p>La tige aérienne est <?php echo $tige['tige_aerienne'];?>
                            <?php if ($tige['tige_aerienne'] != 'non visible'):;?>, <?php echo $type_tige;?>, <?php echo $tige['ramification'];?>, à section <?php echo $section_tige;?>.
                                <br>Sa surface est <?php echo $surface_tige;?> au moins quand elle est jeune.
                                <?php if ((($port_de_la_plante === 'arbrisseau') || ($port_de_la_plante === 'arbre')) && (!empty($surface_ecorce))): ?>
                                    <br>L'écorce est <?php echo $surface_ecorce;?><?php if (!empty($tige['couleur_du_tronc'])) {?> et <?php echo $tige['couleur_du_tronc'];} ?>.
                                <?php endif; ?>
                            <?php endif; ?>
                        </p>
                    <?php } ?>
                </div>

                <div class="characteristic">
                    <?php
                    $feuille = get_field('feuille');
                    if (!empty($feuille)) { ?>
                        <?php $presence_feuilles = get_field('feuille')['presence_de_feuilles']; ?>
                        <?php if ('jamais visibles' === $presence_feuilles): ?>
                            <h4 class="icon-title">
                                <div class="feuilles-icon icon"></div>Feuilles
                            </h4>
                            <p><?php echo $presence_feuilles; ?></p>
                        <?php else : ?>

                            <?php $heteromorphisme_foliaire = get_field('heteromorphisme_foliaire'); ?>
                            <?php if (('feuilles toutes semblables' === $heteromorphisme_foliaire) || ('gradient de forme entre la base et le haut de la tige' === $heteromorphisme_foliaire)): ?>

                                <?php $feuilles_aeriennes = get_field('feuilles_aeriennes'); ?>
                                <?php if(!empty($feuilles_aeriennes['photo_de_feuilles_aeriennes'])): ?>
                                    <h4 class="icon-title">
                                        <div class="feuilles-icon icon"></div>Feuilles
                                    </h4>
                                    <?php
                                    $refs_photo[] = $feuilles_aeriennes['photo_de_feuilles_aeriennes']["id"];
                                    $index_photos++;
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
                                        if ('simples' === $type_feuille) {
                                            $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des feuilles simples' : ''), implode('-', $feuilles_aeriennes['limbe_des_feuilles_simples']));
                                        } else {
                                            $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des folioles' : ''), implode('-', $feuilles_aeriennes['limbe_des_folioles']));
                                        }
                                    }
                                    ?>

                                    <?php echo implode(', ', $type_limbe);?>, à marge foliaire <?php echo implode(' et ', $feuilles_aeriennes['marge_foliaire']);?> et à nervation <?php echo implode(' et ', $feuilles_aeriennes['nervation']);?>.<br>

                                    <?php
                                    $presence_petiole = $feuilles_aeriennes['petiole'];
                                    $petiole = 'présent' === $presence_petiole ? $feuilles_aeriennes['longueur_du_petiole'] . ('engainant' === $feuilles_aeriennes['engainant'] ? ', ' . $feuilles_aeriennes['engainant'] :'') : $presence_petiole;
                                    ?>

                                    Le pétiole est <?php echo $petiole; ?>.<br>

                                    <?php echo  'présents' === $feuilles_aeriennes['stipules'] ? $feuilles_aeriennes['forme_et_couleur_des_stipules'] : '';?>

                                    <?php $port_de_la_plante = get_field('port_de_la_plante'); ?>
                                    <?php if (!empty($port_de_la_plante)): ?>
                                        <?php if (($port_de_la_plante === 'arbrisseau') || ($port_de_la_plante === 'arbre')): ?>
                                            <?php echo  $feuilles_aeriennes['feuillage'] ? 'Le feuillage est ' . $feuilles_aeriennes['feuillage'].'.' : '';?>
                                        <?php endif; ?>
                                    <?php endif; ?>
                                </p>

                            <?php elseif ('deux formes distinctes de feuilles'=== $heteromorphisme_foliaire): ?>

                                <?php $deux_formes_distinctes = get_field('deux_formes_distinctes'); ?>
                                <?php if ($deux_formes_distinctes === 'plante à feuilles immergées et aériennes'): ?>

                                    <?php $feuilles_aeriennes = get_field('feuilles_aeriennes'); ?>
                                    <?php if(!empty($feuilles_aeriennes)): ?>
                                        <h4 class="icon-title">
                                            <div class="feuilles-icon icon"></div>Feuilles aériennes
                                        </h4>
                                        <?php if(!empty($feuilles_aeriennes['photo_de_feuilles_aeriennes'])): ?>
                                            <?php
                                            $refs_photo[] = $feuilles_aeriennes['photo_de_feuilles_aeriennes']["id"];
                                            $index_photos++;
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
                                                if ('simples' === $type_feuille) {
                                                    $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des feuilles simples' : ''), implode('-', $feuilles_aeriennes['limbe_des_feuilles_simples']));
                                                } else {
                                                    $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des folioles' : ''), implode('-', $feuilles_aeriennes['limbe_des_folioles']));
                                                }
                                            }
                                            ?>

                                            <?php echo implode(', ', $type_limbe);?>, à marge foliaire <?php echo implode(' et ', $feuilles_aeriennes['marge_foliaire']);?> et à nervation <?php echo implode(' et ', $feuilles_aeriennes['nervation']);?>.<br>

                                            <?php
                                            $presence_petiole = $feuilles_aeriennes['petiole'];
                                            $petiole = 'présent' === $presence_petiole ? $feuilles_aeriennes['longueur_du_petiole'] . ('engainant' === $feuilles_aeriennes['engainant'] ? ', ' . $feuilles_aeriennes['engainant'] :'') : $presence_petiole;
                                            ?>

                                            Le pétiole est <?php echo $petiole; ?>.<br>

                                            <?php echo  'présents' === $feuilles_aeriennes['stipules'] ? $feuilles_aeriennes['forme_et_couleur_des_stipules'] : '';?>

                                            <?php $port_de_la_plante = get_field('port_de_la_plante'); ?>
                                            <?php if (!empty($port_de_la_plante)): ?>
                                                <?php if (($port_de_la_plante === 'arbrisseau') || ($port_de_la_plante === 'arbre')): ?>
                                                    <?php echo  $feuilles_aeriennes['feuillage'] ? 'Le feuillage est ' . $feuilles_aeriennes['feuillage'].'.' : '';?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </p>
                                    <?php endif; ?>

                                    <?php /*$feuilles_immergees = get_field('feuilles_immergees'); */?><!--
                                    <?php /*if(!empty($feuilles_immergees)): */?>
                                        <h4 class="icon-title">
                                            <div class="feuilles-icon icon"></div>Feuilles immergées
                                        </h4>
                                        <?php /*if(!empty($feuilles_immergees['photo_de_feuilles_immergees'])): */?>
                                            <?php
/*                                            $refs_photo[] = $feuilles_immergees['photo_de_feuilles_immergees']["id"];
                                            $index_photos++;
                                            */?>
                                            <div class="picture-ref"><?php /*echo $index_photos;*/?></div>
                                        <?php /*endif; */?>
                                        <p>
                                            Les feuilles sont disposées de façon <?php /*echo implode(' et ', $feuilles_immergees['phyllotaxie']);*/?> et elles sont <?php /*echo implode(' et ', $feuilles_immergees['type_de_feuille']);*/?>.<br>
                                            <?php
/*                                            $type_feuille_multiple = 1 < count($feuilles_immergees['type_de_feuille']);
                                            $limbe = 'Le limbe %s est %s';
                                            $type_limbe = [];

                                            foreach ($feuilles_immergees['type_de_feuille'] as $type_feuille) {
                                                if ('simples' === $type_feuille) {
                                                    $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des feuilles simples' : ''), implode('-', $feuilles_immergees['limbe_des_feuilles_simples']));
                                                } else {
                                                    $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des folioles' : ''), implode('-', $feuilles_immergees['limbe_des_folioles']));
                                                }
                                            }
                                            */?>

                                            <?php /*echo implode(', ', $type_limbe);*/?>, à marge foliaire <?php /*echo implode(' et ', $feuilles_immergees['marge_foliaire']);*/?> et à nervation <?php /*echo implode(' et ', $feuilles_immergees['nervation']);*/?>.<br>

                                            <?php
/*                                            $presence_petiole = $feuilles_immergees['petiole'];
                                            $petiole = 'présent' === $presence_petiole ? $feuilles_immergees['longueur_du_petiole'] . ('engainant' === $feuilles_immergees['engainant'] ? ', ' . $feuilles_immergees['engainant'] :'') : $presence_petiole;
                                            */?>

                                            Le pétiole est <?php /*echo $petiole; */?>.<br>

                                            <?php /*echo  'présents' === $feuilles_immergees['stipules'] ? $feuilles_immergees['forme_et_couleur_des_stipules'] : '';*/?>

                                            <?php /*$port_de_la_plante = get_field('port_de_la_plante'); */?>
                                            <?php /*if (!empty($port_de_la_plante)): */?>
                                                <?php /*if (($port_de_la_plante === 'arbrisseau') || ($port_de_la_plante === 'arbre')): */?>
                                                    <?php /*echo  $feuilles_immergees['feuillage'] ? 'Le feuillage est ' . $feuilles_immergees['feuillage'].'.' : '';*/?>
                                                <?php /*endif; */?>
                                            <?php /*endif; */?>
                                        </p>
                                    --><?php /*endif; */?>

                                <?php elseif ($deux_formes_distinctes === 'plante à rameaux stériles et à rameaux fleuris distincts'): ?>

                                    <?php $feuilles_des_rameaux_steriles = get_field('feuilles_des_rameaux_steriles'); ?>
                                    <?php if(!empty($feuilles_des_rameaux_steriles)): ?>
                                        <h4 class="icon-title">
                                            <div class="feuilles-icon icon"></div>Feuilles des rameaux stériles
                                        </h4>
                                        <?php if(!empty($feuilles_des_rameaux_steriles['photo_de_feuilles_des_rameaux_steriles'])): ?>
                                            <?php
                                            $refs_photo[] = $feuilles_des_rameaux_steriles['photo_de_feuilles_des_rameaux_steriles']["id"];
                                            $index_photos++;
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
                                                if ('simples' === $type_feuille) {
                                                    $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des feuilles simples' : ''), implode('-', $feuilles_des_rameaux_steriles['limbe_des_feuilles_simples']));
                                                } else {
                                                    $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des folioles' : ''), implode('-', $feuilles_des_rameaux_steriles['limbe_des_folioles']));
                                                }
                                            }
                                            ?>

                                            <?php echo implode(', ', $type_limbe);?>, à marge foliaire <?php echo implode(' et ', $feuilles_des_rameaux_steriles['marge_foliaire']);?> et à nervation <?php echo implode(' et ', $feuilles_des_rameaux_steriles['nervation']);?>.<br>

                                            <?php
                                            $presence_petiole = $feuilles_des_rameaux_steriles['petiole'];
                                            $petiole = 'présent' === $presence_petiole ? $feuilles_des_rameaux_steriles['longueur_du_petiole'] . ('engainant' === $feuilles_des_rameaux_steriles['engainant'] ? ', ' . $feuilles_des_rameaux_steriles['engainant'] :'') : $presence_petiole;
                                            ?>

                                            Le pétiole est <?php echo $petiole; ?>.<br>

                                            <?php echo  'présents' === $feuilles_des_rameaux_steriles['stipules'] ? $feuilles_des_rameaux_steriles['forme_et_couleur_des_stipules'] : '';?>

                                            <?php $port_de_la_plante = get_field('port_de_la_plante'); ?>
                                            <?php if (!empty($port_de_la_plante)): ?>
                                                <?php if (($port_de_la_plante === 'arbrisseau') || ($port_de_la_plante === 'arbre')): ?>
                                                    <?php echo  $feuilles_des_rameaux_steriles['feuillage'] ? 'Le feuillage est ' . $feuilles_des_rameaux_steriles['feuillage'].'.' : '';?>
                                                <?php endif; ?>
                                            <?php endif; ?>
                                        </p>
                                    <?php endif; ?>

                                    <?php /*$feuilles_des_rameaux_fleuris = get_field('feuilles_des_rameaux_fleuris'); */?><!--
                                    <?php /*if (!empty($feuilles_des_rameaux_fleuris)) : */?>
                                        <h4 class="icon-title">
                                            <div class="feuilles-icon icon"></div>Feuilles des reameaux fleuris
                                        </h4>
                                        <?php /*if(!empty($feuilles_des_rameaux_fleuris['photo_de_feuilles_des_rameaux_fleuris'])): */?>
                                            <?php
/*                                            $refs_photo[] = $feuilles_des_rameaux_fleuris['photo_de_feuilles_des_rameaux_fleuris']["id"];
                                            $index_photos++;
                                            */?>
                                            <div class="picture-ref"><?php /*echo $index_photos;*/?></div>
                                        <?php /*endif; */?>
                                        <p>
                                            Les feuilles sont disposées de façon <?php /*echo implode(' et ', $feuilles_des_rameaux_fleuris['phyllotaxie']);*/?> et elles sont <?php /*echo implode(' et ', $feuilles_des_rameaux_fleuris['type_de_feuille']);*/?>.<br>
                                            <?php
/*                                            $type_feuille_multiple = 1 < count($feuilles_des_rameaux_fleuris['type_de_feuille']);
                                            $limbe = 'Le limbe %s est %s';
                                            $type_limbe = [];

                                            foreach ($feuilles_des_rameaux_fleuris['type_de_feuille'] as $type_feuille) {
                                                if ('simples' === $type_feuille) {
                                                    $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des feuilles simples' : ''), implode('-', $feuilles_des_rameaux_fleuris['limbe_des_feuilles_simples']));
                                                } else {
                                                    $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des folioles' : ''), implode('-', $feuilles_des_rameaux_fleuris['limbe_des_folioles']));
                                                }
                                            }
                                            */?>

                                            <?php /*echo implode(', ', $type_limbe);*/?>, à marge foliaire <?php /*echo implode(' et ', $feuilles_des_rameaux_fleuris['marge_foliaire']);*/?> et à nervation <?php /*echo implode(' et ', $feuilles_des_rameaux_fleuris['nervation']);*/?>.<br>

                                            <?php
/*                                            $presence_petiole = $feuilles_des_rameaux_fleuris['petiole'];
                                            $petiole = 'présent' === $presence_petiole ? $feuilles_des_rameaux_fleuris['longueur_du_petiole'] . ('engainant' === $feuilles_des_rameaux_fleuris['engainant'] ? ', ' . $feuilles_des_rameaux_fleuris['engainant'] :'') : $presence_petiole;
                                            */?>

                                            Le pétiole est <?php /*echo $petiole; */?>.<br>

                                            <?php /*echo  'présents' === $feuilles_des_rameaux_fleuris['stipules'] ? $feuilles_des_rameaux_fleuris['forme_et_couleur_des_stipules'] : '';*/?>

                                            <?php /*$port_de_la_plante = get_field('port_de_la_plante'); */?>
                                            <?php /*if (!empty($port_de_la_plante)): */?>
                                                <?php /*if (($port_de_la_plante === 'arbrisseau') || ($port_de_la_plante === 'arbre')): */?>
                                                    <?php /*echo  $feuilles_des_rameaux_fleuris['feuillage'] ? 'Le feuillage est ' . $feuilles_des_rameaux_fleuris['feuillage'].'.' : '';*/?>
                                                <?php /*endif; */?>
                                            <?php /*endif; */?>
                                        </p>
                                    --><?php /*endif; */?>

                                <?php endif; ?>

                        <?php endif; ?>


                        <?php endif; ?>
                    <?php } ?>
                </div>

                <div class="characteristic">
                    <?php  if (!empty(get_field('inflorescence'))) { ?>
                        <h4 class="icon-title">
                            <div class="inflorescence-icon icon"></div>Inflorescence
                        </h4>
                        <?php $inflorescence = get_field('inflorescence');?>
                        <p>Les fleurs sont <?php echo $inflorescence['organisation_des_fleurs'];?>.
                        <?php if($inflorescence['organisation_des_fleurs'] === 'organisées en inflorescences') {
                            if($inflorescence['categorie'] != 'autre') {
                                ?> L’inflorescence est <?php echo $inflorescence['categorie']; ?>.</p>
                            <?php } else {
                                ?> L’inflorescence est <?php echo $inflorescence['description']; ?>.</p>
                            <?php } ?>
                        <?php } ?>
                    <?php } ?>
                </div>

                <div class="characteristic">
                    <?php  if (!empty(get_field('fruit'))) { ?>
                        <h4 class="icon-title">
                            <div class="fruits-icon icon"></div>Fruits
                        </h4>
                        <?php $fruit = get_field('fruit');?>
                        <?php if(!empty($fruit['photo'])): ?>
                            <?php
                            // $refs_photo[] = $fruit['photo']["id"];
                            // $index_photos++;
                            $index_fruit_photo = $index_photos+1;
                            $fruit_photo = $fruit['photo'];
                            if (!empty(get_field('fleur_male')) && (!empty(get_field('fleur_male')['photo_de_fleur_male']))) {
                                if (!empty(get_field('systeme_sexuel')) && (get_field('systeme_sexuel') == "monoïque") || (get_field('systeme_sexuel') == "dioïque") || (get_field('systeme_sexuel') == "andromonoïque") || (get_field('systeme_sexuel') == "androdioïque") || (get_field('systeme_sexuel') == "androgynomonoïque") || (get_field('systeme_sexuel') == "androgynodioïque")) {
                                    $index_fruit_photo++;
                                }
                            }
                            if (!empty(get_field('fleur_femelle')) && (!empty(get_field('fleur_femelle')['photo_de_fleur_femelle']))) {
                                if (!empty(get_field('systeme_sexuel')) && (get_field('systeme_sexuel') == "monoïque") || (get_field('systeme_sexuel') == "dioïque") || (get_field('systeme_sexuel') == "gynomonoïque") || (get_field('systeme_sexuel') == "gynodioïque") || (get_field('systeme_sexuel') == "androgynomonoïque") || (get_field('systeme_sexuel') == "androgynodioïque")) {
                                    $index_fruit_photo++;
                                }
                            }
                            if (!empty(get_field('fleur_bisexuee')) && (!empty(get_field('fleur_bisexuee')['photo_de_fleur_bisexuee']))) {
                                if (!empty(get_field('systeme_sexuel')) && (get_field('systeme_sexuel') == "hermaphrodite") || (get_field('systeme_sexuel') == "andromonoïque") || (get_field('systeme_sexuel') == "gynomonoïque") || (get_field('systeme_sexuel') == "androdioïque") || (get_field('systeme_sexuel') == "gynodioïque") || (get_field('systeme_sexuel') == "androgynomonoïque") || (get_field('systeme_sexuel') == "androgynodioïque")) {
                                    $index_fruit_photo++;
                                }
                            }
                            ?>
                            <div class="picture-ref"><?php echo $index_fruit_photo;?></div>
                        <?php endif; ?>
                        <p>Le fruit est <?php echo $fruit['type'];?>.</p>
                    <?php } ?>
                </div>


                <?php $fleur_male =  get_field('fleur_male') ?: null;?>
                <?php if (!empty(get_field('systeme_sexuel')) && (get_field('systeme_sexuel') == "monoïque" ) || (get_field('systeme_sexuel') == "dioïque" ) || (get_field('systeme_sexuel') == "andromonoïque" ) || (get_field('systeme_sexuel') == "androdioïque" ) || (get_field('systeme_sexuel') == "androgynomonoïque" ) || (get_field('systeme_sexuel') == "androgynodioïque" )): ?>
                <div class="characteristic fleur-monosexe">
                    <h4 class="icon-title">
                        <div class="fleur-male-icon icon"></div>Fleur mâle
                    </h4>
                    <?php if(!empty($fleur_male['photo_de_fleur_male'])): ?>
                        <?php
                        $refs_photo[] = $fleur_male['photo_de_fleur_male']["id"];
                        $index_photos++;
                        ?>
                        <div class="picture-ref"><?php echo $index_photos;?></div>
                    <?php endif; ?>
                    <p>
                        <?php if('présent' !== $fleur_male['perianthe']): { ?>
                            Périanthe absent ;
                        <?php } else: { ?>
                            Fleur <?php echo implode(' et ', $fleur_male['symetrie']); ?>;
                            <?php
                            if ('tépales' === $fleur_male['differenciation_du_perianthe']) {
                                $perianthe = implode(' ou ', $fleur_male['perigone']) . ' tépales ' . $fleur_male['soudure_du_perigone'] . ' ; ';
                            } else {
                                $perianthe = implode(' ou ', $fleur_male['calice']) . ' sépale(s) ' . $fleur_male['soudure_du_calice'] . ' et ' . implode(' ou ', $fleur_male['corolle']) . ' pétale(s) ' . $fleur_male['soudure_de_la_corolle'] . ' ; ' .
                                    ('corolle soudée au calice' === $fleur_male['soudure_du_calice_et_de_la_corolle'] ? $fleur_male['soudure_du_calice_et_de_la_corolle'] . ' ; ' : '');
                            }
                            ?>
                            périanthe composé de <?php echo $perianthe;
                        } ?>
                        <?php endif; ?>
                        <?php if(!empty($fleur_male['androcee'])): { ?>
                            androcée composé de <?php echo implode(' ou ' , $fleur_male['androcee']); ?> étamine(s) <?php echo $fleur_male['soudure_de_landrocee']; ?> ;
                        <?php echo ('androcée soudée à la corolle' === $fleur_male['soudure_androcee-corolle'] ? $fleur_male['soudure_androcee-corolle'] . ', ' : '').
                                ('soudées au perigone' === $fleur_male['soudure_androcee-perigone'] ? $fleur_male['soudure_androcee-perigone'] . ', ' : ''); ?>
                            <?php echo ('présents' === $fleur_male['staminodes'] ? $fleur_male['nombre_de_staminodes'] . ' staminodes ; ' : ''); ?>
                            La couleur principale de la fleur est <?php echo $fleur_male['couleur_principale']; ?>.
                            <?php if ('pubescente' === $fleur_male['pubescence']) {
                                echo "La fleur est ".$fleur_male['pubescence'];?>
                                <?php if (!empty($fleur_male['localisation_des_poils'])) {
                                    echo ' sur: '.implode(', ' , $fleur_male['localisation_des_poils']).'.'; }
                                else { echo '.'; }}?>
                            <?php echo $fleur_male['autre_caractere'];
                        } ?>
                        <?php endif; ?>
                    </p>
                </div>
                <?php endif ?>


                <?php $fleur_femelle =  get_field('fleur_femelle') ?: null;?>
                <?php if (!empty(get_field('systeme_sexuel')) && (get_field('systeme_sexuel') == "monoïque" ) || (get_field('systeme_sexuel') == "dioïque" ) || (get_field('systeme_sexuel') == "gynomonoïque" ) || (get_field('systeme_sexuel') == "gynodioïque" ) || (get_field('systeme_sexuel') == "androgynomonoïque" ) || (get_field('systeme_sexuel') == "androgynodioïque" )): ?>
                <div class="characteristic fleur-monosexe">
                    <h4 class="icon-title">
                        <div class="fleur-femelle-icon icon"></div>Fleur femelle
                    </h4>
                    <?php if(!empty($fleur_femelle['photo_de_fleur_femelle'])): ?>
                        <?php
                        $refs_photo[] = $fleur_femelle['photo_de_fleur_femelle']["id"];
                        $index_photos++;
                        ?>
                        <div class="picture-ref"><?php echo $index_photos;?></div>
                    <?php endif; ?>
                    <p>
                        <?php if('présent' !== $fleur_femelle['perianthe']): { ?>
                            Périanthe absent ;
                        <?php } else: { ?>
                            Fleur <?php echo implode(' et ', $fleur_femelle['symetrie']); ?> ;
                            <?php
                            if ('tépales' === $fleur_femelle['differenciation_du_perianthe']) {
                                $perianthe = implode(' ou ', $fleur_femelle['perigone']) . ' tépales ' . $fleur_femelle['soudure_du_perigone'] . ' ; ';
                            } else {
                                $perianthe = implode(' ou ', $fleur_femelle['calice']) . ' sépale(s) ' . $fleur_femelle['soudure_du_calice'] . ' et ' . implode(' ou ', $fleur_femelle['corolle']) . ' pétale(s) ' . $fleur_femelle['soudure_de_la_corolle'] . ' ; ' .
                                    ('corolle soudée au calice' === $fleur_femelle['soudure_du_calice_et_de_la_corolle'] ? $fleur_femelle['soudure_du_calice_et_de_la_corolle'] . ' ; ' : '');
                            }
                            ?>
                            périanthe composé de <?php echo $perianthe;
                        } ?>
                        <?php endif; ?>
                        <?php if(!empty($fleur_femelle['gynecee'])): { ?>
                            gynécée composé de <?php echo implode(' ou ' , $fleur_femelle['gynecee']); ?>  carpelle(s) <?php echo $fleur_femelle['soudure_des_carpelles']; ?> ;
                            ovaire <?php echo $fleur_femelle['ovaire']; ?>.
                            La couleur principale de la fleur est <?php echo $fleur_femelle['couleur_principale']; ?>.
                            <?php if ('pubescente' === $fleur_femelle['pubescence']) {
                                echo "La fleur est ".$fleur_femelle['pubescence'];?>
                                <?php if (!empty($fleur_femelle['localisation_des_poils'])) {
                                    echo ' sur: '.implode(', ' , $fleur_femelle['localisation_des_poils']).'.'; }
                                else { echo '.'; }}?>
                            <?php echo $fleur_femelle['autre_caractere'];
                        }?>
                        <?php endif; ?>
                    </p>
                </div>
                <?php endif ?>

                <?php $fleur_bisexuee =  get_field('fleur_bisexuee') ?: null;?>
                <?php if (!empty(get_field('systeme_sexuel')) && (get_field('systeme_sexuel') == "hermaphrodite" ) || (get_field('systeme_sexuel') == "andromonoïque" ) || (get_field('systeme_sexuel') == "gynomonoïque" ) || (get_field('systeme_sexuel') == "androdioïque" ) || (get_field('systeme_sexuel') == "gynodioïque" ) || (get_field('systeme_sexuel') == "androgynomonoïque" ) || (get_field('systeme_sexuel') == "androgynodioïque" )): ?>
                    <div class="characteristic fleur-bisexuee">
                        <h4 class="icon-title">
                            <div class="fleur-bisexuee-icon icon"></div>Fleur bisexuée
                        </h4>
                        <?php if(!empty($fleur_bisexuee['photo_de_fleur_bisexuee'])): ?>
                            <?php
                            $refs_photo[] = $fleur_bisexuee['photo_de_fleur_bisexuee']["id"];
                            $index_photos++;
                            ?>
                            <div class="picture-ref"><?php echo $index_photos;?></div>
                        <?php endif; ?>
                        <p>
                            <?php if('présent' !== $fleur_bisexuee['perianthe']): { ?>
                                Périanthe absent ;
                            <?php } else: { ?>
                                Fleur <?php echo implode(' et ', $fleur_bisexuee['symetrie']); ?> ;
                                <?php
                                if ('tépales' === $fleur_bisexuee['differenciation_du_perianthe']) {
                                    $perianthe = implode(' ou ', $fleur_bisexuee['perigone']) . ' tépales ';
                                    $perianthe .=  !empty($fleur_bisexuee['soudure_du_perigone']) ? $fleur_bisexuee['soudure_du_perigone'] . ' ; ' : " ;";
                                } else {
                                    $perianthe = implode(' ou ', $fleur_bisexuee['calice']) . ' sépale( s) ';
                                    $perianthe .= !empty($fleur_bisexuee['soudure_du_calice']) ? $fleur_bisexuee['soudure_du_calice'] : "" ;
                                    $perianthe .=    ' et ' . implode(' ou ', $fleur_bisexuee['corolle']) . ' pétale(s) ' ;
                                    $perianthe .= !empty($fleur_bisexuee['soudure_de_la_corolle']) ? $fleur_bisexuee['soudure_de_la_corolle'] . ' ; ' : "" ;
                                    $perianthe .= ('corolle soudée au calice' === $fleur_bisexuee['soudure_du_calice_et_de_la_corolle'] ?
                                        $fleur_bisexuee['soudure_du_calice_et_de_la_corolle'] . ' ; ' : '');
                                }
                                ?>
                                périanthe composé de <?php echo $perianthe;
                            } ?>
                            <?php endif; ?>
                            <?php if(!empty($fleur_bisexuee['androcee'])): { ?>
                                androcée composé de <?php echo implode(' ou ' , $fleur_bisexuee['androcee']); ?> étamine(s)
                                <?php echo $fleur_bisexuee['soudure_de_landrocee']; ?> ; <?php echo ('androcée soudée à la corolle' === $fleur_bisexuee['soudure_androcee-corolle'] ? $fleur_bisexuee['soudure_androcee-corolle'] . ', ' : ''). ('soudées au perigone' === $fleur_bisexuee['soudure_androcee-perigone'] ? $fleur_bisexuee['soudure_androcee-perigone'] . ', ' : ''); ?>
                                <?php echo ('présents' === $fleur_bisexuee['staminodes'] ? $fleur_bisexuee['nombre_de_staminodes'] . ' staminodes ; ' : '');
                            } ?>
                            <?php endif; ?>
                            <?php if(!empty($fleur_bisexuee['gynecee'])): { ?>
                                gynécée composé de <?php echo implode(' ou ' , $fleur_bisexuee['gynecee']); ?>  carpelle(s) <?php echo $fleur_bisexuee['soudure_des_carpelles']; ?> ;
                                ovaire <?php echo $fleur_bisexuee['ovaire']; ?>.
                            <?php } ?>
                            <?php endif; ?>
                                La couleur principale de la fleur est le <?php echo $fleur_bisexuee['couleur_principale']; ?>.
                                <?php if ('pubescente' === $fleur_bisexuee['pubescence']) {
                                    echo "La fleur est ".$fleur_bisexuee['pubescence'];?>
                                    <?php if (!empty($fleur_bisexuee['localisation_des_poils'])) {
                                        echo ' sur: '.implode(', ' , $fleur_bisexuee['localisation_des_poils']).'.'; }
                                    else { echo '.'; }}?>
                                <?php echo $fleur_bisexuee['autre_caractere'];?>
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
                        $refs_photo[] = $fruit_photo["id"];
                        $index_photos++;
                        ?>
                    <?php endif; ?>
                    <div class="footer-section">
                        <!--<ol class="characteristic-photos">
                            <?php /*for ($i = 1;$i <= $index_photos; $i++): */?>
                                <?php /*if($i == 3) : {break;} */?>
                                <?php /*endif; */?>
                                <li><?php /*echo wp_get_attachment_image($refs_photo[$i], [148, 148]); */?><div class="picture-ref"><?php /*echo $i; */?></div></li>

                            <?php /*endfor; */?>
                        </ol>-->
                        <ol class="characteristic-photos">
                            <?php for ($i = 1;$i <= $index_photos; $i++): ?>
                                <?php if($i == 3) : {break;} ?>
                                <?php endif; ?>
                                <?php $testing_img_src = wp_get_attachment_image_src($refs_photo[$i], 'full');

                                $img = wp_get_image_editor( $testing_img_src[0] );

                                if ( ! is_wp_error( $img ) ) {
                                $img->resize( 148, 148 );
                                $img->set_quality( 100 );
                                $img->stream();
                                } ?>
                                <li><img src= <?php echo $img; ?><div class="picture-ref"><?php echo $i; ?></div></li>

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
            <main class="container">
                <?php if (!empty(get_field('amplitude_altitudinale')) || !empty(get_field('affinites_ecologiques')) || !empty(get_field('habitat_preferentiel')) || !empty(get_field('systeme_de_reproduction')) || !empty(get_field('pollinisation')) || !empty(get_field('dispersion'))): ?>
                <div class="characteristic">
                    <h3 class="icon-title">
                        <div class="ecologie-icon icon"></div>écologie
                    </h3>
                    <p><?php if (!empty(get_field('amplitude_altitudinale'))) :?> Altitude : <?php echo get_field('amplitude_altitudinale'); ?> ; <?php endif; ?> <?php if (!empty(get_field('affinites_ecologiques'))) :?> Plante <?php echo get_field('affinites_ecologiques') ? implode(', ', get_field('affinites_ecologiques')) : ""; ?> ; <?php endif; ?>
                        <?php if (!empty(get_field('habitat_preferentiel'))) :?> habitat : <?php the_field('habitat_preferentiel'); ?>. <?php endif; ?> <?php if ((!empty(get_field('systeme_de_reproduction'))) || (!empty(get_field('pollinisation')))) :?> Plante <?php endif; ?> <?php if (!empty(get_field('systeme_de_reproduction'))) :?> <?php the_field('systeme_de_reproduction'); ?>, <?php endif; ?> <?php if (!empty(get_field('pollinisation'))) :?> à pollinisation <?php the_field('pollinisation'); ?>, <?php endif; ?>
                        <?php if (!empty(get_field('dispersion'))) :?> Dispersion des graines et des fruits <?php echo get_field('dispersion') ? implode(', ', get_field('dispersion')) : ""; ?>.<?php endif; ?></p>
                </div>
                <?php endif; ?>
                <?php $proprietes = get_field('proprietes')?: null; ?>
                <?php if ($proprietes): ?>
                    <div class="characteristic">
                        <h3 class="icon-title">
                            <div class="groupe-163-icon icon"></div>propriétés
                        </h3>
                        <p><?php echo $proprietes; ?></p>
                    </div>
                <?php endif; ?>
                <?php if (!empty(get_field('cultivee_en_france')) || !empty(get_field('carte_de_metropole')) || !empty(get_field('repartition_mondiale')) || !empty(get_field('indigenat')) || !empty(get_field('statut_uicn'))): ?>
                <div class="characteristic">
                    <h3 class="icon-title">
                        <div class="location-icon icon"></div>aire de répartition
                    </h3>
                    <?php if (!empty(get_field('cultivee_en_france'))) { ?>
                        <?php $cultivee_en_france = get_field('cultivee_en_france'); ?>
                        <p>En France la plante est présente <?php echo $cultivee_en_france; ?>,<?php echo ("à l'état sauvage" === $cultivee_en_france ? ' où elle est ' . implode (', ', get_field('indigenat')) . '.' : ''); ?> Statut UICN : <?php the_field('statut_uicn'); ?>.</p>
                    <?php } ?>
                    <?php if (!empty(get_field('carte_de_metropole'))) :?>
                        <div class="section-image"><?php echo wp_get_attachment_image(get_field('carte_de_metropole')['id'], 'large'); ?></div>
                        <div class="credits-photo">OpenObs - 02/2023</div>
                    <?php endif; ?>
                </div>
                <?php endif; ?>

                <?php $description = get_field('description')?: null; ?>
                <?php if ($description): ?>
                    <div class="characteristic">
                        <h3 class="icon-title">
                            <div class="ne-pas-confondre-icon icon"></div>ne pas confondre
                        </h3>
                        <p><?php the_field('description'); ?>.</p>
                        <?php $photo = get_field('photo')?: null; ?>
                        <?php if (!empty($photo)): ?>
                            <div class="section-image"><?php echo wp_get_attachment_image($photo['id'], 'large'); ?></div>
                        <?php endif; ?>
                    </div>
                <?php endif; ?>
            </main>
            <footer>
                <div class="container">
                    <div class="footer-section">
                        <ul class="logos">
                            <li><img src="<?php echo get_template_directory_uri(); ?>/images/logo-saclay.png" alt="Logo Université Paris-Saclay"></li>
                            <li><img src="<?php echo get_template_directory_uri(); ?>/images/logo-botascopia.png" alt="Logo Botascopia"></li>
                            <li><img src="<?php echo get_template_directory_uri(); ?>/images/logo-tela.png" alt="Logo Tela Botanica"></li>
                        </ul>
                    </div>
                    <div class="footer-section info">
                        <div class="columns">
                            <div>
                                <h5 class="inline">auteur(e-s) :</h5>
                                <p class="inline"><?php echo get_the_author(); ?></p>
                            </div>
                            <div>
                                <h5 class="inline">date de création :</h5>
                                <p class="inline"><?php echo get_the_date('m/Y', get_the_ID()); ?></p>
                            </div>

                            <h5>crédits photos :</h5>

                            <?php if(0 < $index_photos): ?>
                                <ol class="credits-photo">
                                    <?php foreach ($refs_photo as $i => $photo_id) : ?>
                                        <?php $credit =  get_the_excerpt($photo_id) ?: null; ?>
                                        <li><?php echo $i.' - '. (get_the_excerpt($photo_id) ?: 'nc'); ?></li>
                                    <?php endforeach; ?>
                                </ol>
                            <?php endif; ?>

                        </div>

                        <div class="columns">
                            <h5>référence(s) :</h5>
                            <?php for($i = 1; $i <= 3; $i++): ?>
                                <?php $ref = get_field('reference_'.strval($i)) ?: null;?>
                                <?php if($ref):?>
                                    <p><?php echo $ref; ?></p>
                                <?php endif; ?>
                            <?php endfor; ?>
                        </div>
                    </div>
                </div>
            </footer>

        </div>
    <?php endwhile;?>
<?php else: //Handle the case where there is no parameter?>
    <p>Nom de fiche invalide</p>
<?php endif; ?>
</body>
</html>
