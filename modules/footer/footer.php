<?php function botascopia_module_footer($data) { ?>
  <footer class="footer" role="contentinfo">
      <div class="footer-layout">
          <div class="footer-left">
              <div class="footer-about-tela">

                  <div class="footer-about-botascopia-logo">
                      <img src="<?php echo get_template_directory_uri().'/images/logo-botascopia.png'; ?>" alt="Botascopia"/>
                  </div>

                  <div class="footer-about-tela-details">
                      <div class="footer-about-tela-details-adresse">1b rue de Verdun, 34000 Montpellier, France</div>
                      <div class="footer-about-tela-details-tel"><?php _e('Téléphone', 'telabotanica') ?> : +33 (0)4 67 52 41 22</div>
                  </div>

              </div>
			 
              <div class="footer-nav-plan" role="navigation" aria-label="<?php esc_attr_e('Plan du site', 'botascopia'); ?>">
                  <div class="footer-column footer-liens">
                      <h4 class="footer-nav-title">Liens rapide</h4>
					  <?php
					  if (has_nav_menu('footer-liens')) :
					  wp_nav_menu([
									  'theme_location' => 'footer-liens',
									  'menu_class'     => 'footer-nav-items layout-wrapper',
									  'depth'          => 2,
								  ]);
					 endif; ?>
                  </div>
                  <div class="footer-column footer-legal">
                      <div>
						  <?php
						  if (has_nav_menu('footer-legal')) :?>
                          <h4 class="footer-nav-title">Légal</h4>
                          <?php
                          wp_nav_menu([
                                          'theme_location' => 'footer-legal',
                                          'menu_class'     => 'footer-nav-items layout-wrapper',
                                          'depth'          => 2,
                                      ]);
							endif;?>
                      </div>
                      <div>
						  <?php
						  if (has_nav_menu('footer-contacts')) :?>
                          <h4 class="footer-nav-title">Contacts</h4>
						  <?php
						  wp_nav_menu([
										  'theme_location' => 'footer-contacts',
										  'menu_class'     => 'footer-nav-items layout-wrapper',
										  'depth'          => 2,
									  ]);
							endif;?>
                      </div>
                  </div>
				  
              </div>
          </div>
    
          <div class="footer-logos">
              <img src="<?php echo get_template_directory_uri() . '/images/logo-tela@2x.png'; ?>" class="footer-logo-tela">
              <img src="<?php echo get_template_directory_uri() . '/images/logo-saclay.png'; ?>" class="footer-logo-saclay">
          </div>
          <div class="footer-plan-button">
              <?php
              the_botascopia_module('button',[
                  'tag' => 'button',
                  'title' => 'Plan du site',
                  'text' => 'Plan du site',
                  'modifiers' => 'green-button outline',
                  'extra_attributes' => ["id" => "togglePlanBtn"]
              ]);
              ?>
          </div>
<!--          <button id="togglePlanBtn">Plan du site</button>-->
          
      </div>
    <?php if ( has_nav_menu( 'footer-bar' ) ) : ?>
      <nav class="footer-nav-bar" role="navigation" aria-label="<?php esc_attr_e( 'Menu de pied de page', 'botascopia'
      ); ?>">
        <?php
          wp_nav_menu( [
            'theme_location' => 'footer-bar',
            'menu_class'     => 'footer-nav-bar-items',
            'depth'          => 1
          ] );
        ?>
      </nav>
    <?php endif; ?>

    
    
  </footer><!-- .site-footer -->
<?php
}
