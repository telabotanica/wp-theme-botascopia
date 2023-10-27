<?php
/* Template Name: page sans sommaire */
?>
<?php
get_header();
?>
<div id="primary" class="content-area page-sans-sommaire-primary">
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
		<div class="collection-main page-sans-sommaire-main">
			<div class="left-div">
<!--				<div class="first-toc">-->
<!--					--><?php
//					the_botascopia_module('toc', [
//						'title' => 'Sommaire',
//						'items' => [
//							[
//								'items' => []
//							],
//						]
//					]);
//					?>
<!--				</div>-->
			</div>
			<div id="glossaire-container" class="right-div">
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

