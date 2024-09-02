<div class="first-toc">
    <?php
    $role = wp_get_current_user()->roles[0];
    if (is_user_logged_in()) :
        $current_user = wp_get_current_user();
        $userId = $current_user->ID;
        $role = $current_user->roles[0];
        $displayName = $current_user->display_name;
        
    else:
        $userId = 0;
        $role = '';
        $displayName = '';
    endif;
    // Actions collections
    $collectionHref = home_url().'/'.get_page_uri()."/";

    if (str_contains($collectionHref,"mes-collections")){
        $collectionHref="";
    }else{
        $collectionHref = home_url()."/profil/mes-collections/";
    }

    the_botascopia_module('toc', [
        'title' => 'PROFIL',
        'items' => [
            [
                'text' => Constantes::COLLECTIONS,
                'href' => $collectionHref,
                'active' => true,
                'items' => [
                    [
                        'text' => Constantes::COLLECTIONS_FAV,
                        'href' => $collectionHref . '#collections-favoris',
                        'active' => false,
                    ],
                    [
                        'text' => Constantes::COLLECTIONS_TO_COMP,
                        'href' => $collectionHref . '#collection-a-completer',
                        'active' => false,
                    ],
                    [
                        'text' => Constantes::COLLECTIONS_COMP,
                        'href' => $collectionHref . '#mes-collections-completes',
                        'active' => false,
                    ],
                ]
            ],
        ]
    ]);

    if ($role != 'contributor'):
        echo '<div class="toc-button">';
        the_botascopia_module('button', [
            'tag' => 'a',
            'href' => home_url() . '/profil/mes-collections/creer-une-collection/',
            'title' => Constantes::COLLECTION_TO_CREATE,
            'text' => Constantes::COLLECTION_TO_CREATE,
            'modifiers' => 'green-button',
        ]);
        echo '</div>';
    endif;
    //                    Actions fiches
    echo '<div class="second-toc">';

    $fichesHref = home_url().'/'.get_page_uri()."/";

    if (str_contains($fichesHref,"mes-fiches")){
        $fichesHref="";
    }else{
        $fichesHref = home_url()."/profil/mes-fiches/";
    }
    $textACompleter = Constantes::FICHES_TO_COMP;
    $lienACompleter = "$fichesHref#fiches-a-completer";

    if ($role == 'editor'){
        $textACompleter = Constantes::FICHES_TO_CHK;
        $lienACompleter = "$fichesHref#fiches-en-verification";
    }
    $fichesFavorites = getMesFiches(['draft', 'pending', 'publish', 'private'], $role, null, $userId, null); 
    $nb_fiches = 0;
    if (isset($fichesFavorites)&&!empty($fichesFavorites)){
        $nb_fiches = count($fichesFavorites);
    }
    $items=[];
    if ($nb_fiches>0){
        array_push($items,[
            'text' => Constantes::FICHES_FAV,
            'href' => "$fichesHref#fiches-favoris",
            'active' => true,
        ]);
    }

    $fichesACompleter = getMesFiches('draft', $role, $userId, $userId, null);
    $nb_fiches_comp = 0;
    if (isset($fichesACompleter)&&!empty($fichesACompleter)){
        $nb_fiches_comp = count($fichesACompleter);
    }
    if ($nb_fiches_comp>0){
        array_push($items,[
            'text' => $textACompleter,
            'href' => $lienACompleter,
            'active' => false,
        ]);
    }
    $fichesInValidation = getMesFiches('pending', $role, $userId, $userId, $userId);
    if (isset($fichesInValidation)){
        $nb_fiches_inv = count($fichesInValidation);
    }else{
        $nb_fiches_inv = 0;
    }
    if ($nb_fiches_inv>0){
        array_push($items,[
            'text' => Constantes::FICHES_TO_VAL,
            'href' => "$fichesHref#fiches-a-valider",
            'active' => false,
        ]);
    }
    $fichesAValider = getMesFiches('pending', $role, $userId, $userId, null);
					
    if (isset($fichesAValider)){
        
        $fichesAval = [];
        foreach ($fichesAValider as $fiche){
            if (!$fiche['editor'] || $fiche['editor'] == 0){
                array_push($fichesAval,$fiche);
            }
        }
        $nb_fiches_val = count($fichesAval);
    }else{
        $nb_fiches_val = 0;
    }
    if ($nb_fiches_val>0){
        array_push($items,[
            'text' => Constantes::FICHES_TO_VAL,
            'href' => "$fichesHref#fiches-a-valider",
            'active' => false,
        ]);
    }
    if ($role == 'editor'){
        $fichesValidees = getMesFiches('publish', $role, $userId, $userId, $userId);
    } else {
        $fichesValidees = getMesFiches('publish', $role, $userId, $userId, null);
    }
    if (isset($fichesValidees)){
        $nb_fiches_validees = count($fichesValidees);
    }else{
        $nb_fiches_validees = 0;
    }
    if ($nb_fiches_validees>0){
        array_push($items,[
            'text' => Constantes::FICHES_VAL,
            'href' => "$fichesHref#mes-fiches-validees",
            'active' => false,
        ]);
    }

    the_botascopia_module('toc', [
        'title' => '',
        'items' => [
            [
                'text' => Constantes::FICHES,
                'href' => "$fichesHref#fiches-favoris",
                'active' => false,
                'items' => $items
                
            ],
        ]
    ]);

    ?>
</div>