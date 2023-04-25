<?php
/*
    Template Name: create-collection
*/
?>
<?php
get_header();
?>

<div id="primary" class="content-area">
<!--	<div class="bg-fill">-->
<!--	-->
<!--	</div>-->
	<main id="main" class="site-main " role="main">
		<div class="collection-main">
<!--			<div class="left-div">-->
<!--				-->
<!--				<a class="return-button" href="#">-->
<!--					--><?php //the_botascopia_module('icon',[
//						'icon'=> 'arrow-left'
//					]); ?>
<!--					<span>RETOUR</span>-->
<!--				</a>-->
<!--                <div id="error-message">-->
<!--					--><?php
//					if ( isset( $_GET['error'] ) ) {
//						$error = $_GET['error'];
//						if ( $error == 'existing_title' ) {
//							echo 'Une collection avec le même titre existe déjà.';
//						}
//					}
//					?>
<!--                </div>-->
<!--                -->
<!--			</div>-->
			<div class="right-div">
				<?php
				the_botascopia_module('breadcrumbs');
				?>
				
				<div class="entry-content">
					<h1>Créer une nouvelle collection</h1>
					<form id="new-post-form" method="post">
                        <input type="hidden" name="meta-type" value="collection">
                        <input type="hidden" name="action" value="create_new_collection">
						<label for="post-title">Nom de la collection</label>
						<input type="text" name="post-title" id="post-title" required>
						<br>
						<label for="post-description">Description de la collection</label>
						<textarea name="post-description" id="post-description" rows="10" required></textarea>
						<br>
						<input type="submit" value="Créer la collection">
						<?php wp_nonce_field( 'new-post-collection' ); ?>
					</form>
				</div>
			</div>
	</main>
</div>


<?php
get_footer();
?>
