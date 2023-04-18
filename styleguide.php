<?php get_header(); ?>

<?php
the_botascopia_module('breadcrumbs');

$categories_items = [[
    'text' => 'categorie 1',
    'href' => 'https://www.tela-botanica.org'
],
    [
        'text' => 'categorie 2',
        'href' => 'https://www.tela-botanica.org'
    ]];

the_botascopia_module('categories', [
    'modifiers' => 'layout-column-item',
    'items' => $categories_items
]);

the_botascopia_module('categories-labels', [
    'items' => $categories_items
]);
?>
  <br>
<?php

the_botascopia_module('button', [
    'tag' => 'a',
    'href' => 'https://www.google.fr',
    'target' => '_blank',
    'text' => 'Bouton',
    'title' => '',
    'modifiers' => 'green-button',
    'icon_after' => 'star'
]);
the_botascopia_module('button', [
    'tag' => 'a',
    'href' => 'https://www.google.fr',
    'target' => '_blank',
//				'text' => 'Bouton',
    'title' => '',
    'modifiers' => 'green-button outline',
    'icon_after' => ['icon' => 'edit', 'color'=>'orange']
]);
the_botascopia_module('button', [
    'tag' => 'a',
    'href' => 'https://www.tela-botanica.org',
    'target' => '_blank',
    'title' => '',
    'modifiers' => 'green-button outline',
    'icon_after' => ['icon' => 'star-outline', 'color'=>'vert-clair'],
    'extra_attributes' => ['type' => "submit", 'id' => "pending_btn", 'name'=> "pending_btn", 'value' => "Envoyer la fiche à validation", 'onclick' => "click_ignore();"]
]);
the_botascopia_component('buttons', [
    'items' => [
        [
            'tag' => 'a',
            'href' => 'https://www.tela-botanica.org',
            'target' => '_blank',
            'text' => 'Bouton 1',
            'title' => '',
            'modifiers' => 'green-button back',
            'icon_after' => 'star',
            'icon_before' => ['icon' => 'cog', 'color'=>'vert-clair']
//						'icon_after' => ['icon' => 'edit', 'color'=>'green'],
        ],
        [
            'tag' => 'a',
            'href' => 'https://www.tela-botanica.org',
            'target' => '_blank',
            'text' => 'Bouton 2',
            'title' => '',
            'modifiers' => 'purple-button',
            'icon_after' => ['icon' => 'angle-down', 'color'=>'blanc']
        ],
        [
            'tag' => 'a',
            'href' => 'https://www.tela-botanica.org',
            'target' => '_blank',
            'text' => 'Bouton 3',
            'title' => '',
            'modifiers' => 'green-button outline',
            'icon_after' => ['icon' => 'cog-circle', 'color'=>'vert-clair'],
        ],
        [
            'tag' => 'a',
            'href' => 'https://www.tela-botanica.org',
            'target' => '_blank',
            'text' => 'Bouton 4',
            'title' => '',
            'modifiers' => 'purple-button outline',
        ],
        [
            'tag' => 'a',
            'href' => 'https://www.tela-botanica.org',
            'target' => '_blank',
//						'text' => '',
            'title' => '',
            'modifiers' => 'green-button outline',
            'icon_after' => ['icon' => 'star-outline', 'color'=>'vert-clair']
        ],
    ]
]);
//			the_botascopia_module('cover');
//			$cover_image = get_field('cover_image', get_queried_object());
//			$cover_image = get_the_post_thumbnail(16,'cover-background');
//			$cover_image = get_field('cover_image', get_queried_object());
//            var_dump(get_queried_object());
//            $image = [
//				'ID' => get_post_thumbnail_id(16),
//				'url' => wp_get_attachment_image_url( 16, 'cover-background' ),
////				'title' => get_the_title(),
//                'sizes' => 'cover-background'
//            ];

the_botascopia_module('cover', [
    'title' => 'Cover 1',
//                'modifiers' => 'orange',
    'subtitle' => __("Exemple de cover", 'botascopia'),
//                'image' => $cover_image
//				'image' => ['ID' => 239,
//					'url' => wp_get_attachment_image_url( 239, 'cover-background' ),
//					'title' => get_the_title(),
//                    'sizes' => wp_get_attachment_image_sizes(239, 'cover-background' )
//                ],
] );

echo '<div> icone :' . get_botascopia_module('icon', ['icon' => 'star-outline', 'color' => 'vert-clair']) .
    '</div>';

// TODO Pagination a tester
get_botascopia_module('pagination', [
//		'id' => 'pag-bottom',
//		'count_id' => 'member-dir-count-bottom',
//		'links_id' => 'member-dir-pag-bottom',
//		'context' => 'buddypress',
//		'type' => 'members'
]);

the_botascopia_module('notice', [
    'type' => 'alert',
    'closable' => true,
    'title' => __('Bientôt disponible.', 'telabotanica'),
    'text' => __('Vous retrouverez prochainement ici la liste complète de vos contributions.<br />Pour le moment, seules les plus récentes sont affichées.', 'telabotanica')
]);

the_botascopia_module('title', [
    'title' => __('Module Titre', 'botascopia'),
    'level' => 1,
    'modifiers' => ''
]);
the_botascopia_component('title', [
    'title' => __( "Composant titre niveau 2", 'telabotanica' ),
    'level' => 2,
]);
the_botascopia_component('title', [
    'title' => __( "Composant titre niveau 3", 'telabotanica' ),
    'level' => 3,
]);
the_botascopia_component('title', [
    'title' => __( "Composant titre niveau 4", 'telabotanica' ),
    'level' => 4,
]);
echo '<div style="margin-top: 30px">';
the_botascopia_module('toc', [
    'title' => 'titre du sommaire',
    'items' => [
        [
            'text' => 'Par département',
            'href' => '?module=liste-zones-geo',
            'active' => true,
            'items' => [
                [
                    'text' => 'Liste des taxons',
                    'href' => '?module=liste-taxons',
                    'active' => true,
                ],
                [
                    'text' => 'Liste des taxons2',
                    'href' => '?module=liste-taxons',
                    'active' => false,
                ],
            ]
        ],
        [
            'text' => 'Carte',
            'href' => '?module=carte',
            'active' => false,
            'items' => [
                [
                    'text' => 'Liste des taxons',
                    'href' => '?module=liste-taxons',
                    'active' => false,
                ],
                [
                    'text' => 'Liste des taxons2',
                    'href' => '?module=liste-taxons',
                    'active' => false,
                ],
            ]
        ]
    ]
]);
echo '</div>';
the_botascopia_module('search-box', [
//				'suggestions' => ['coquelicot', 'quercus ilex', 'végétation', 'mooc'],
    'modifiers' => ['large', 'is-primary']
]);
/*
			the_botascopia_module('card-collection',[
                    'href' => '#',
		'image' => get_field('card_image'),
		'name' => 'nom de la collection',
		'nbFiches' => 'x',
		'description' => 'Lorem ipsum dolor sit amet. Ut voluptatem dolor non omnis quia est eveniet illum. Ea alias ullam eos ut voluptatem dolor non lorem ipsum dolor sit amet bla bla bla bli',
		'icon' => ['icon' => 'star-outline', 'color' => 'blanc'],
		'modifiers' => []
            ]);
*/
the_botascopia_module('card-fiche', [
//                    'href' => '',
    'image' => '',
    'name' => 'Plante 1 bla bla bla bla bla bli bli bli bli bli bla bla bla bla bla',
    'species' => 'Espèce 1aaaaa et gdf et defrew',
    'icon' => ['icon' => 'star', 'color' => 'blanc']
]);

?>

<?php get_footer(); ?>
