<div class="first-toc">
    <?php
    $role = wp_get_current_user()->roles[0];
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

    the_botascopia_module('toc', [
        'title' => '',
        'items' => [
            [
                'text' => Constantes::FICHES,
                'href' => "$fichesHref#fiches-favoris",
                'active' => false,
                'items' => [
                    [
                        'text' => Constantes::FICHES_FAV,
                        'href' => "$fichesHref#fiches-favoris",
                        'active' => true,
                    ],
                    [
                        'text' => $textACompleter,
                        'href' => $lienACompleter,
                        'active' => false,
                    ],
                    [
                        'text' => Constantes::FICHES_TO_VAL,
                        'href' => "$fichesHref#fiches-a-valider",
                        'active' => false,
                    ],

                    [
                        'text' => Constantes::FICHES_VAL,
                        'href' => "$fichesHref#mes-fiches-validees",
                        'active' => false,
                    ],
                ]
            ],
        ]
    ]);

    ?>
</div>