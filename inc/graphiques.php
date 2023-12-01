<?php

function genererSVG($nom_fiche, $valeurs) {
    $template = file_get_contents(get_template_directory() . '/assets/graphs/preferences.svg');
    $target_directory = wp_upload_dir()['basedir'] . "/graphs_agro_eco/";
    // Créer le dossier s'il n'existe pas
    wp_mkdir_p($target_directory);
    $save_path = $target_directory . "$nom_fiche.svg";

    $insertionPoint = strpos($template, '</svg>');
    $tolerance_gel = get_field('preferences_physico-chimiques_tolerance_au_gel') ?? false;

    // Ajouter les valeurs dans le SVG
    foreach ($valeurs as $id => $valeur) {
        $count = count($valeur['x']);
        $valeur_y = $valeur['y'];
        $element = '';
        if ($valeur['x'] && ($valeur['x'][0] != 0)){
            if ($count === 1) {
                // S'il n'y a qu'une valeur, utiliser un cercle
                $valeur_x = $valeur['x'][0];
                $valeur_y = $valeur_y + 5;
                $element = "<circle id=\"$id\" class=\"valeur\" cx=\"$valeur_x\" cy=\"$valeur_y\" r=\"10\"/>";
            } else {
                // S'il y a plusieurs valeurs utiliser un rectangle
                $start_x = $valeur['x'][0];
                $end_x = end($valeur['x']);
                // Calculer la largeur du rectangle
                $width = $end_x - $start_x;

                $element = "<rect id=\"$id\" class=\"valeur\" x=\"$start_x\" y=\"$valeur_y\" width=\"$width\" height=\"10\" rx=\"5\" ry=\"5\"/>";
            }
        } elseif ($valeur['x'] && $valeur['x'][0] == 0){
                $element = '<rect id="line_grad_salinite" x="135" y="265" class="cache" width="440" height="50"/>
                            <text transform="matrix(1 0 0 1 140 298)" class="titre">Ne supporte pas le sel</text>';
        }
        $template = substr_replace($template, $element, $insertionPoint, 0);
    }
    if ($tolerance_gel){
        $element = '<text transform="matrix(1 0 0 1 30 330)" class="titre">Cette plante tolère le gel</text>';
    } else {
        $element = '<text transform="matrix(1 0 0 1 30 330)" class="titre">Cette plante ne tolère pas le gel</text>';
    }
    $template = substr_replace($template, $element, $insertionPoint, 0);

    // Sauvegarder le nouveau SVG dans un fichier
    file_put_contents($save_path, $template);
}

function getChampsAgroEcoPourSvg(){
    $lumiere_field = get_field('preferences_physico-chimiques_lumiere') ?? [];
    $humidite_atm = get_field('preferences_physico-chimiques_humidite_atmospherique') ?? [];
    $continentalite = get_field('preferences_physico-chimiques_continentalite') ?? [];
    $reaction_ph = get_field('preferences_physico-chimiques_reaction_ph') ?? [];
    $humidite_sol = get_field('preferences_physico-chimiques_humidite_du_sol') ?? [];
    $texture_sol = get_field('preferences_physico-chimiques_texture_du_sol') ?? [];
    $richesse_azote = get_field('preferences_physico-chimiques_richesse_en_azote_n') ?? [];
    $salinite = get_field('preferences_physico-chimiques_salinite') ?? [];

    $valeursPdf = [
        "ve_lumiere" => ['x' => ajusterValeurs(extractFirstValue($lumiere_field), 1, 9, 30), 'y'=> 34],
        "ve_humidite_atm" => ['x' => ajusterValeurs(extractFirstValue($humidite_atm), 1, 9, 30), 'y'=> 68],
        "ve_continentalite" => ['x' => ajusterValeurs(extractFirstValue($continentalite), 1, 9, 30), 'y'=> 105],
        "ve_reaction_ph" => ['x' => ajusterValeurs(extractFirstValue($reaction_ph), 1, 9, 30), 'y'=> 143],
        "humidite_sol" => ['x' => ajusterValeurs(extractFirstValue($humidite_sol), 1, 12, 24), 'y'=> 183],
        "ve_texture_sol" => ['x' => ajusterValeurs(extractFirstValue($texture_sol), 1, 9, 30), 'y'=> 220],
        "ve_richesse_en_n" => ['x' => ajusterValeurs(extractFirstValue($richesse_azote), 1, 9, 30), 'y'=> 255],
        "ve_salinite" => ['x' => ajusterValeurs(extractFirstValue($salinite), 1, 9, 30), 'y'=> 290],
    ];

    return $valeursPdf;
}

function extractFirstValue($field) {
    $result = [];
    foreach ($field as $value) {
        $parts = explode("-", $value);
        $result[] = trim($parts[0]);
    }
    return $result;
}

function ajusterValeurs($valeurs, $min, $max, $interval) {
    $result = [];

    foreach ($valeurs as $valeur) {
        if ($valeur == 0){
            $result[] = 0;
        } elseif ($valeur >= $min && $valeur <= $max) {
            $adjustedValue = 140 + ($valeur - 1) * $interval;
            $result[] = $adjustedValue;
        }
    }

    return $result;
}