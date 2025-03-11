<?php
//$GLOBALS['is_error'] = true;
get_header();

  the_botascopia_module('error-page', [
    'type' => 'maintenance',
    'title' => __("Le site est en cours de maintenance.", 'botascopia'),
    'text' => __("Veuillez nous excuser pour la gêne occasionnée, le site devrait revenir à la normale d'ici très peu de temps.", 'botascopia'),
    'button' => [
      'href' => '',
      'text' => __('Réessayer', 'telabotanica')
    ]
  ]);


get_footer();
