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
        'name'        => $_GET['p'],
        'post_type'   => 'post',
        'numberposts' => 1
        ]);
    ?>
    <?php while (have_posts()) : the_post(); ?>
        <div id="page-1" class="page page-1">
            <div class="container">

                <header>
                    <h1><em><?php the_field('nom_scientifique');?></em></h1>
                    <h2><?php the_field('nom_vernaculaire');?> — <?php the_field('famille');?></h2>
                    <h3 class="icon-title">déscription morphologique</h3>
                    <p><?php the_field('description_vulgarisee');?></p>
                    <div class="round-picture"><img src="" alt=""></div>
                </header>

                <main>
                    <div class="characteristic">
                        <h4 class="icon-title tige-icon">Tige</h4><div class="picture-ref">1</div>
                        <?php
                            $tige = get_field('tige');
                            $type_tige = implode(', ', $tige['type_de_tige']);
                            $section_tige = implode(' et ', $tige['section_de_la_tige']);
                            $surface_tige = implode(', ', $tige['surface_de_la_tige_jeune']);
                        ?>
                        <p>La tige aérienne est <?php echo $tige['tige_aerienne'];?>, <?php echo $type_tige;?>, <?php echo $tige['ramification'];?>, à section <?php echo $section_tige;?>.<br>Sa surface est <?php echo $surface_tige;?> au moins quand elle est jeune.</p>
                    </div>

                    <div class="characteristic">
                        <h4 class="icon-title feuilles-icon">Feuilles</h4><div class="picture-ref">2</div>
                        <?php $presence_feuilles = get_field('feuille')['presence_de_feuilles']; ?>
                        <?php if ('visibles' !== $presence_feuilles): ?>
                            <p><?php echo $presence_feuilles; ?></p>
                        <?php else: ?>
                            <?php $feuille = get_field('feuilles_visibles'); ?>
                            <p>
                                Les feuilles sont disposées de façon <?php echo implode(' et ', $feuille['phyllotaxie']);?> et elles sont <?php echo implode(' et ', $feuille['type_de_feuille']);?>.<br>
                                <?php
                                    $type_feuille_multiple = 1 < count($feuille['type_de_feuille']);
                                    $limbe = 'Le limbe %s est %s';
                                    $type_limbe = [];

                                    foreach ($feuille['type_de_feuille'] as $type_feuille) {
                                        if ('simples' === $type_feuille) {
                                            $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des feuilles simples' : ''), implode(' et ', $feuille['limbe_des_feuilles_simples']));
                                        } else {
                                            $type_limbe[] = sprintf($limbe, ($type_feuille_multiple ? 'des folioles' : ''), implode(' et ', $feuille['limbe_des_folioles']));
                                        }
                                    }
                                ?>
                                <?php echo implode(', ', $type_limbe);?>, à marge foliaire <?php echo implode(' et ', $feuille['marge_foliaire']);?> et à nervation <?php echo implode(' et ', $feuille['nervation']);?>.<br>
                                <?php
                                    $presence_petiole = $feuille['petiole'];
                                    $petiole = 'présent' === $presence_petiole ? $feuille['longueur_du_petiole'] . ('engainant' === $feuille['engainant'] ? ', ' . $feuille['engainant'] :'') : $presence_petiole;
                                ?>
                                Le pétiole est <?php echo $petiole; ?>.<br>
                                <?php echo  'présent' === $feuille['stipules'] ? $feuille['forme_et_couleur_des_stipules'] : '';?>
                                <?php echo  $feuille['feuillage'] ? 'Le feuillage est ' . $feuille['feuillage'] : '';?>.
                            </p>
                        <?php endif; ?>
                    </div>

                    <div class="characteristic">
                        <h4 class="icon-title inflorescence-icon">Inflorescence</h4>
                        <?php $inflorescence = get_field('inflorescence');?>
                        <p>Les fleurs sont <?php echo $inflorescence['organisation_des_fleurs']; ?>. L’inflorescence est <?php echo $inflorescence['categorie']; ?>.</p>
                    </div>

                    <div class="characteristic">
                        <h4 class="icon-title fruits-icon">Fruits</h4><div class="picture-ref">3</div>
                        <?php $fruit = get_field('fruit');?>
                        <p>Le fruit est <?php echo $fruit['type'];?>.</p>
                    </div>

                    <?php $fleur_bisexuee =  get_field('fleur_bisexuee') ?: null;?>
                    <?php if ($fleur_bisexuee): ?>
                        <div class="characteristic">
                            <h4 class="icon-title fleur-male-icon">Fleur bisexuée</h4><div class="picture-ref">4</div>
                            <p>
                                Fleur <?php echo implode(' et ', $fleur_bisexuee['symetrie']); ?> ;
                                <?php if('présent' !== $fleur_bisexuee['perianthe']): ?>
                                    Le périanthe est absent.
                                <?php else: ?>
                                    <?php
                                        if ('tépales' === $fleur_bisexuee['differenciation_du_perianthe']) {
                                            $perianthe = implode(' ou ', $fleur_bisexuee['perigone']) . ' tépales ' . $fleur_bisexuee['soudure_du_perigone'] . ' ; ';
                                        } else {
                                            $perianthe = implode(' ou ', $fleur_bisexuee['calice']) . ' sépale(s) ' . $fleur_bisexuee['soudure_du_calice'] . ' et ' . implode(' ou ', $fleur_bisexuee['corolle']) . ' pétale(s) ' . $fleur_bisexuee['soudure_de_la_corolle'] . ' ; ' . ('corolle soudée au calice' === $fleur_bisexuee['soudure_du_calice_et_de_la_corolle'] ? $fleur_bisexuee['soudure_du_calice_et_de_la_corolle'] . ' ; ' : '');
                                        }
                                    ?>
                                    périanthe composé de <?php echo $perianthe; ?>
                                    androcée composé de <?php echo implode(' ou ' , $fleur_bisexuee['androcee']); ?> étamine(s) <?php echo $fleur_bisexuee['soudure_de_landrocee']; ?> ; <?php echo ('androcée soudée à la corolle' === $fleur_bisexuee['soudure_androcee-corolle'] ? $fleur_bisexuee['soudure_androcee-corolle'] . ', ' : ''). ('soudées au perigone' === $fleur_bisexuee['soudure_androcee-perigone'] ? $fleur_bisexuee['soudure_androcee-perigone'] . ', ' : ''); ?>
                                    <?php echo ('présents' === $fleur_bisexuee['staminodes'] ? $fleur_bisexuee['nombre_de_staminodes'] . ' staminodes ; ' : ''); ?>
                                    gynécée composé de <?php echo implode(' ou ' , $fleur_bisexuee['gynecee']); ?>  carpelle(s) <?php echo $fleur_bisexuee['soudure_des_carpelles']; ?> ;
                                     ovaire <?php echo $fleur_bisexuee['ovaire']; ?>.
                                     La couleur principale de la fleur est <?php echo $fleur_bisexuee['couleur_principale']; ?>.
                                     La fleur est <?php echo $fleur_bisexuee['pubescence']. ('pubescente' === $fleur_bisexuee['pubescence'] ? ' sur: '.implode(', ' , $fleur_bisexuee['localisation_des_poils']) : ''); ?>.
                                     <?php echo $fleur_bisexuee['autre_caractere']; ?>.
                                <?php endif; ?>
                            </p>
                        </div>
                    <?php else: ?>
                        <?php $fleur_male =  get_field('fleur_male') ?: null;?>
                        <div class="characteristic">
                            <h4 class="icon-title fleur-male-icon">Fleur mâle</h4><div class="picture-ref">4</div>
                            <p>
                                Fleur <?php echo implode(' et ', $fleur_male['symetrie']); ?> ;
                                <?php if('présent' !== $fleur_male['perianthe']): ?>
                                    Le périanthe est absent.
                                <?php else: ?>
                                    <?php
                                    if ('tépales' === $fleur_male['differenciation_du_perianthe']) {
                                        $perianthe = implode(' ou ', $fleur_male['perigone']) . ' tépales ' . $fleur_male['soudure_du_perigone'] . ' ; ';
                                    } else {
                                        $perianthe = implode(' ou ', $fleur_male['calice']) . ' sépale(s) ' . $fleur_male['soudure_du_calice'] . ' et ' . implode(' ou ', $fleur_male['corolle']) . ' pétale(s) ' . $fleur_male['soudure_de_la_corolle'] . ' ; ' . ('corolle soudée au calice' === $fleur_male['soudure_du_calice_et_de_la_corolle'] ? $fleur_male['soudure_du_calice_et_de_la_corolle'] . ' ; ' : '');
                                    }
                                    ?>
                                    périanthe composé de <?php echo $perianthe; ?>
                                    androcée composé de <?php echo implode(' ou ' , $fleur_male['androcee']); ?> étamine(s) <?php echo $fleur_male['soudure_de_landrocee']; ?> ; <?php echo ('androcée soudée à la corolle' === $fleur_male['soudure_androcee-corolle'] ? $fleur_male['soudure_androcee-corolle'] . ', ' : ''). ('soudées au perigone' === $fleur_male['soudure_androcee-perigone'] ? $fleur_male['soudure_androcee-perigone'] . ', ' : ''); ?>
                                    <?php echo ('présents' === $fleur_male['staminodes'] ? $fleur_male['nombre_de_staminodes'] . ' staminodes ; ' : ''); ?>
                                    La couleur principale de la fleur est <?php echo $fleur_male['couleur_principale']; ?>.
                                    La fleur est <?php echo $fleur_male['pubescence']. ('pubescente' === $fleur_male['pubescence'] ? ' sur: '.implode(', ' , $fleur_male['localisation_des_poils']) : ''); ?>.
                                    <?php echo $fleur_male['autre_caractere']; ?>.
                                <?php endif; ?>
                            </p>
                        </div>
                        <?php $fleur_femelle =  get_field('fleur_femelle') ?: null;?>
                        <div class="characteristic">
                            <h4 class="icon-title fleur-femelle-icon">Fleur femelle</h4><div class="picture-ref">5</div>
                            <p>
                                Fleur <?php echo implode(' et ', $fleur_femelle['symetrie']); ?> ;
                                <?php if('présent' !== $fleur_femelle['perianthe']): ?>
                                    Le périanthe est absent.
                                <?php else: ?>
                                    <?php
                                    if ('tépales' === $fleur_femelle['differenciation_du_perianthe']) {
                                        $perianthe = implode(' ou ', $fleur_femelle['perigone']) . ' tépales ' . $fleur_femelle['soudure_du_perigone'] . ' ; ';
                                    } else {
                                        $perianthe = implode(' ou ', $fleur_femelle['calice']) . ' sépale(s) ' . $fleur_femelle['soudure_du_calice'] . ' et ' . implode(' ou ', $fleur_femelle['corolle']) . ' pétale(s) ' . $fleur_femelle['soudure_de_la_corolle'] . ' ; ' . ('corolle soudée au calice' === $fleur_femelle['soudure_du_calice_et_de_la_corolle'] ? $fleur_femelle['soudure_du_calice_et_de_la_corolle'] . ' ; ' : '');
                                    }
                                    ?>
                                    périanthe composé de <?php echo $perianthe; ?>
                                    gynécée composé de <?php echo implode(' ou ' , $fleur_femelle['gynecee']); ?>  carpelle(s) <?php echo $fleur_femelle['soudure_des_carpelles']; ?> ;
                                    ovaire <?php echo $fleur_femelle['ovaire']; ?>.
                                    La couleur principale de la fleur est <?php echo $fleur_femelle['couleur_principale']; ?>.
                                    La fleur est <?php echo $fleur_femelle['pubescence']. ('pubescente' === $fleur_femelle['pubescence'] ? ' sur: '.implode(', ' , $fleur_femelle['localisation_des_poils']) : ''); ?>.
                                    <?php echo $fleur_femelle['autre_caractere']; ?>.
                                <?php endif; ?>
                            </p>
                        </div>
                    <?php endif; ?>
                </main>

            </div>
            <div class="footer-section">
                <div class="center">
                    <h4 class="grand-hotel-font">Le saviez-vous ?</h4>
                    <p><?php the_field('le_saviez-vous_');?></p>
                </div>
            </div>
            <div class="footer-section">
                <ol>
                    <li><img src="" alt=""><div class="picture-ref">1</div></li>
                    <li><img src="" alt=""><div class="picture-ref">2</div></li>
                    <li><img src="" alt=""><div class="picture-ref">3</div></li>
                    <li><img src="" alt=""><div class="picture-ref">4</div></li>
                    <li><img src="" alt=""><div class="picture-ref">5</div></li>
                </ol>
            </div>
        </div>
        <div id="page-2" class="page page-2">
            <div class="container">
                <header>
                    <h3 class="icon-title periode-icon">période de <span class="green">floraison</span> et de <span class="purple">fructification</span></h3>
                </header>
                <main>
                    <div class="characteristic">
                        <h3 class="icon-title ecologie-icon">écologie</h3>
                        <p>Altitude : <?php the_field('amplitude_altitudinale'); ?> ; <?php echo implode(', ', get_field('affinites_ecologiques')); ?> ; habitat : <?php the_field('habitat_preferentiel'); ?>. Plante <?php the_field('systeme_de_reproduction'); ?>, à pollinisation <?php the_field('pollinisation'); ?>, dispersion des graines et des fruits par <?php echo implode(', ', get_field('dispersion')); ?>.</p>
                    </div>
                    <?php $proprietes = get_field('proprietes')?: null; ?>
                    <?php if ($proprietes): ?>
                        <div class="characteristic">
                            <h3 class="icon-title groupe-163-icon">propriétés</h3>
                            <p><?php echo $proprietes; ?></p>
                        </div>
                    <?php endif; ?>

                    <div class="characteristic">
                        <h3 class="icon-title location-icon">aire de répartition</h3>
                        <?php $cultivee_en_france = get_field('cultivee_en_france'); ?>
                        <p>En France la plante est présente <?php echo $cultivee_en_france; ?>,<?php echo ("à l'état sauvage" === $cultivee_en_france ? ' où elle est ' . implode (', ', get_field('indigenat')) . '.' : ''); ?> Statut UICN : <?php the_field('statut_uicn'); ?>.</p>
                        <div><img src="" alt=""></div>
                    </div>

                    <?php $description = get_field('description')?: null; ?>
                    <?php if ($description): ?>
                        <div class="characteristic">
                            <h3 class="icon-title ne-pas-confondre-icon">ne pas confondre</h3>
                            <p><?php the_field('description'); ?>.</p>
                            <?php $photo = get_field('photo')?: null; ?>
                            <?php if (!empty($photo)): ?>
                                <div><img src="" alt=""></div>
                            <?php endif; ?>
                        </div>
                    <?php endif; ?>
                </main>
                <div class="footer-section">
                    <ul class="logos">
                      <li><img src="" alt=""></li>
                      <li><img src="" alt=""></li>
                      <li><img src="" alt=""></li>
                    </ul>
                </div>
                <div class="footer-section">
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
                        <div class="columns">
                            <ol>
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                            </ol>
                        </div>
                        <div class="columns">
                            <ol start="4">
                                <li></li>
                                <li></li>
                                <li></li>
                                <li></li>
                            </ol>
                        </div>

                    </div>

                    <div class="columns">
                        <h5>référence(s) :</h5>
                        <?php for($i = 1; $i <= 5; $i++): ?>
                            <?php $ref = get_field('reference_'.strval($i)) ?: null;?>
                            <?php if($ref):?>
                                <p><?php echo $ref; ?></p>
                            <?php endif; ?>
                        <?php endfor; ?>
                    </div>
                </div>
            </div>
        </div>
    <?php endwhile;?>
<?php else: //Handle the case where there is no parameter?>
    <p>Nom de fiche invalide</p>
<?php endif; ?>
</body>
</html>
