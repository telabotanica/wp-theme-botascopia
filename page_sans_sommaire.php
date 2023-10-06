<?php
/* Template Name: page sans sommaire */
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
		the_botascopia_module('cover', [
			'subtitle' => esc_html($description_page),
			'title' => get_the_title(),
//			'image' => [get_template_directory_uri() .'/images/logo-botascopia.png']
		]);

		?>
		<div class="collection-main">
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

