<?php
/* Template Name: Glossaire */
?>
<?php
get_header();
?>
<div id="primary" class="content-area">
	<div class="bg-fill">
	
	</div>
	<main id="main" class="site-main " role="main">
		<?php
		$description_page = get_post_meta(get_the_ID(), 'description_page', true);
		$imageId = get_post_thumbnail_id(get_the_ID());
		if ($imageId) {
			$imageFull = wp_get_attachment_image_src($imageId, 'full');
		} else {
			$imageFull = null;
		}
		$legende = get_post(get_post_thumbnail_id())->post_excerpt;
		$licence = '';
		
		if ($legende){
			$licence = $legende .', licence CC-BY-SA';
		}
		the_botascopia_module('cover', [
			'subtitle' => esc_html($description_page),
			'title' => get_the_title(),
			'image' => $imageFull,
			'licence' => $licence
		]);

		?>
		<div class="collection-main">
			<div class="left-div">
                <h2 class="title toc-title with-border-bottom">Filtres</h2>
                <div class="filtre-glossaire toc">
                    <button class="bouton-glossaire" data-target="glossaire-A">A</button>
                    <button class="bouton-glossaire" data-target="glossaire-B">B</button>
                    <button class="bouton-glossaire" data-target="glossaire-C">C</button>
                    <button class="bouton-glossaire" data-target="glossaire-D">D</button>
                    <button class="bouton-glossaire" data-target="glossaire-E">E</button>
                    <button class="bouton-glossaire" data-target="glossaire-F">F</button>
                    <button class="bouton-glossaire" data-target="glossaire-G">G</button>
                    <button class="bouton-glossaire" data-target="glossaire-H">H</button>
                    <button class="bouton-glossaire" data-target="glossaire-I">I</button>
                    <button class="bouton-glossaire" data-target="glossaire-J">J</button>
                    <button class="bouton-glossaire" data-target="glossaire-K">K</button>
                    <button class="bouton-glossaire" data-target="glossaire-L">L</button>
                    <button class="bouton-glossaire" data-target="glossaire-M">M</button>
                    <button class="bouton-glossaire" data-target="glossaire-N">N</button>
                    <button class="bouton-glossaire" data-target="glossaire-O">O</button>
                    <button class="bouton-glossaire" data-target="glossaire-P">P</button>
                    <button class="bouton-glossaire" data-target="glossaire-Q">Q</button>
                    <button class="bouton-glossaire" data-target="glossaire-R">R</button>
                    <button class="bouton-glossaire" data-target="glossaire-S">S</button>
                    <button class="bouton-glossaire" data-target="glossaire-T">T</button>
                    <button class="bouton-glossaire" data-target="glossaire-U">U</button>
                    <button class="bouton-glossaire" data-target="glossaire-V">V</button>
                    <button class="bouton-glossaire" data-target="glossaire-W">W</button>
                    <button class="bouton-glossaire" data-target="glossaire-X">X</button>
                    <button class="bouton-glossaire" data-target="glossaire-Y">Y</button>
                    <button class="bouton-glossaire" data-target="glossaire-Z">Z</button>
                </div>
			</div>
			<div id="guide-container" class="right-div">
				<?php
				the_botascopia_module('breadcrumbs');
				?>
                
                <?php
                the_content();
                ?>
				
			</div>
	</main><!-- .site-main -->
</div><!-- .content-area -->

<?php
get_footer();
?>

