<?php
/* Template Name: Guide */
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
			'subtitle' => 'Comprenez le fonctionnement de Botascopia',
			'title' => 'Guide',
//			'image' => [get_template_directory_uri() .'/images/logo-botascopia.png']
		]);

		?>
		<div class="collection-main">
			<div class="left-div">
				<div class="first-toc">
					<?php
					the_botascopia_module('toc', [
						'title' => 'Sommaire',
						'items' => [
							[
								'items' => []
							],
						]
					]);
					?>
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

