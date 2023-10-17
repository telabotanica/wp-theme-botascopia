<?php
/*
    Template Name: Fiches
*/
?>
<?php
get_header();
?>

<div id="primary" class="content-area">
    <div class="bg-fill">

    </div>
    <main id="main" class="site-main " role="main">
		<?php
		the_botascopia_module('cover', [
			'subtitle' => 'Consultez et téléchargez des fiches',
            'title' => 'Les fiches'
		]);
		?>
        <div class="collection-main">
            <div class="left-div">
                <div class="first-toc">
					<?php
					the_botascopia_module('toc', [
						'title' => 'FILTRES',
					]);
					echo '</div>';
					?>
                </div>
                <div class="right-div">
					<?php
					the_botascopia_module('breadcrumbs');
					?>

                    <div class="display-collection-cards">
                        <div id="fiches-container" class="display-fiches-cards-items">
                            <?php
                            $fiches = getPublishedFiches();

                            foreach ($fiches as $fiche){
                                the_botascopia_module('card-fiche', [
                                    'href' => $fiche['href'],
                                    'image' => $fiche['image'],
                                    'name' => $fiche['name'],
                                    'species' => $fiche['species'],
                                    'icon' => $fiche['icon'],
                                    'id' => 'fiche-' . $fiche['id'],
                                    'extra_attributes' => ['data-user-id' => $fiche['extra_attributes']['data-user-id'], 'data-fiche-id' => $fiche['extra_attributes']['data-fiche-id'], 'data-fiche-name' => $fiche['extra_attributes']['data-fiche-name'], 'data-fiche-url' => $fiche['extra_attributes']['data-fiche-url'], 'data-fiche-title' => $fiche['extra_attributes']['data-fiche-title']]
                                ]);
                            }

							?>
                        </div>
                    </div>
                    <div class="voir-plus-container">
						<?php
						the_botascopia_module('button', [
							'tag'              => 'button',
							'title'            => 'Voir plus',
							'text'             => 'Voir plus',
							'modifiers'        => 'green-button',
							'extra_attributes' => ['id' => 'loadMoreFiches']
						]);
						?>
                    </div>
                </div>
            </div>


    </main><!-- .site-main -->
</div><!-- .content-area -->

<?php
get_footer();
?>

