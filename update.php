<?php
function modifyData($ancien_champ,$nouveau_champ,$field,$mots_a_corriger = null,$mots_corriges = null){
  //Changer les paramètres selon serveur
  $servername = "localhost";
  $username = "root";
  $password = "root";
  $local = "mysql:unix_socket=/home/thomas/.config/Local/run/-jIQgK0o7/mysql/mysqld.sock;dbname=local";

  try {
      $conn = new PDO($local, $username, $password);
      
      
      $req = "SELECT post_id,meta_value FROM wp_postmeta WHERE meta_key='$ancien_champ'";
    
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $stmt = $conn->query($req);
      $res = $stmt->fetchAll();
      /* var_dump($res);
      die(); */
     
      $data=[];
      $data2=[];
      foreach($res as $item){
        $id = $item['post_id'];
        $value = $item['meta_value'];
      
        array_push($data,[$id, $nouveau_champ, $value]);
        array_push($data2,[$id,"_$nouveau_champ",$field]);
        
      }
      
      $stmt = $conn->prepare("INSERT INTO wp_postmeta (post_id,meta_key,meta_value) VALUES (?, ?, ?)");
      try {
          $conn->beginTransaction();
          foreach ($data as $row)
          {
              $stmt->execute($row);
          }
          
          foreach ($data2 as $row)
          {
              $stmt->execute($row);
          }
            
          $stmt = $conn->prepare("DELETE FROM wp_postmeta WHERE meta_key='$ancien_champ'");
          $stmt->execute();

          $stmt = $conn->prepare("DELETE FROM wp_postmeta WHERE meta_key='_$ancien_champ'");
          $stmt->execute();
        /*   $conn->commit();
          die(); */
          if(isset($mots_a_corriger) AND isset($mots_corriges)){
            
            for ($i=0;$i<count($mots_a_corriger);$i++){
              
              $mot_a_corr = $mots_a_corriger[$i];
              $mot_corr = $mots_corriges[$i];
              $data=[$nouveau_champ,"%$mot_a_corr%"];
              $stmt = $conn->prepare("SELECT meta_id,meta_value FROM wp_postmeta WHERE meta_key=? AND meta_value LIKE ?");
              $stmt->execute($data);
              $res = $stmt->fetchAll();
              /* if ($i==1){
                var_dump($res);
                die();
              } */
              
              if (!empty($res)){
                foreach($res as $item){
                  $value = $item['meta_value'];
                  $id = $item['meta_id'];
                  $value_parts = explode(";",$value);
                  
                  for ($j=0;$j<count($value_parts);$j++){
                    $part = $value_parts[$j];
                    
                      if(str_contains($part,$mot_a_corr) AND preg_match("(l'année|hémiparasite|holoparasite|libres|soudés sur toute la longueur (ovaire, styles, stigmates))", $part) !== 1){
                       
                        $part = preg_replace("([0-9]+)",strlen($mot_corr),$part);
                        $part = str_replace($mot_a_corr,$mot_corr,$part);
                        $value_parts[$j]=$part;
                      }
                    
                  }
                  $value = implode(";",$value_parts);
                  $data=[$value,$id];
                  $stmt = $conn->prepare("UPDATE wp_postmeta set meta_value=? WHERE meta_id=?");
                  $stmt->execute($data);
                }
              }
            }
          }
          $conn->commit();

    
      }catch (Exception $e){
          $conn->rollback();
          throw $e;
      }
      
      
  } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }
}

/* modifyData("fruit_type","fruit_type_de_fruit","field_6307665aecd841",["une crypsèle"],["une cypsèle"]); */

/* modifyData("inflorescence_categorie","inflorescence_categorie_","field_6304ec28c13d61",["un panicule"],["une panicule"]); */

//modifyData("fleur_bisexuee_localisation_des_poils","fleur_bisexuee_localisation_des_poils_","field_6310aa953ed5c1",["l\'androcée","le gynécée"],["les étamines","les carpelles"]);

/* modifyData("fleur_male_localisation_des_poils","fleur_male_localisation_des_poils_","field_6304fcbc829d01",["l\'androcée","le gynécée"],["les étamines","les carpelles"]); */

/* modifyData("fleur_femelle_localisation_des_poils","fleur_femelle_localisation_des_poils_","field_6307632eecd801",["l\'androcée","le gynécée"],["les étamines","les carpelles"]); */

//modifyData("feuilles_aeriennes_limbe_des_feuilles_simples","feuilles_aeriennes_limbe_des_feuilles_simples_","field_6304dac552e051",["oblongue"],["oblong"]);

//modifyData("feuilles_des_rameaux_steriles_limbe_des_feuilles_simples","feuilles_des_rameaux_steriles_limbe_des_feuilles_simples_","field_634e49d1480131",["oblongue"],["oblong"]); 

//modifyData("feuilles_des_rameaux_fleuris_limbe_des_feuilles_simples","feuilles_des_rameaux_fleuris_limbe_des_feuilles_simples_","field_634e49eb480211",["oblongue"],["oblong"]); 

//modifyData("feuilles_immergees_limbe_des_feuilles_simples","feuilles_immergees_limbe_des_feuilles_simples_","field_634e48ca9fff01",["oblongue"],["oblong"]); 

/* modifyData("tige_tige_aerienne","tige_tige_aerienne_","field_6304c66b239191",["visible toute l'année une partie de l'année"],["visible une partie de l'année"]); */

/* modifyData("mode_de_vie","mode_de_vie_","field_6304c10075bda1",["parasite"],["holoparasite"]); */

/* modifyData("fleur_bisexuee_soudure_du_perigone","fleur_bisexuee_soudure_du_perigone_","field_63108833cce411",["soudé sur plus de la moitié de la longueur","soudé sur moins de la moitié de la longueur","partiellement soudés entre eux","libre"],["soudés sur plus de la moitié de la longueur","soudés sur moins de la moitié de la longueur","soudés pour une partie d'entre eux","libres"]); */
/* 
modifyData("fleur_male_soudure_du_perigone","fleur_male_soudure_du_perigone_","field_6304f7e7c67801",["soudé sur plus de la moitié de la longueur","soudé sur moins de la moitié de la longueur","partiellement soudés entre eux","libre"],["soudés sur plus de la moitié de la longueur","soudés sur moins de la moitié de la longueur","soudés pour une partie d'entre eux","libres"]); */

/* modifyData("fleur_femelle_soudure_du_perigone","fleur_femelle_soudure_du_perigone_","field_63075eb5ecd7a1",["soudé sur plus de la moitié de la longueur","soudé sur moins de la moitié de la longueur","partiellement soudés entre eux","libre"],["soudés sur plus de la moitié de la longueur","soudés sur moins de la moitié de la longueur","soudés pour une partie d'entre eux","libres"]); */

/* modifyData("fleur_bisexuee_soudure_du_calice","fleur_bisexuee_soudure_du_calice_","field_631086bfcce3a1",["soudé sur plus de la moitié de la longueur","soudé sur moins de la moitié de la longueur","partiellement soudés entre eux","libre"],["soudés sur plus de la moitié de la longueur","soudés sur moins de la moitié de la longueur","soudés pour une partie d'entre eux","libres"]); */

/* modifyData("fleur_male_soudure_du_calice","fleur_male_soudure_du_calice_","field_6304f4812d9ef1",["soudé sur plus de la moitié de la longueur","soudé sur moins de la moitié de la longueur","partiellement soudés entre eux","libre"],["soudés sur plus de la moitié de la longueur","soudés sur moins de la moitié de la longueur","soudés pour une partie d'entre eux","libres"]); */

/* modifyData("fleur_femelle_soudure_du_calice","fleur_femelle_soudure_du_calice_","field_63075b63ecd731",["soudé sur plus de la moitié de la longueur","soudé sur moins de la moitié de la longueur","partiellement soudés entre eux","libre"],["soudés sur plus de la moitié de la longueur","soudés sur moins de la moitié de la longueur","soudés pour une partie d'entre eux","libres"]); */

/* modifyData("fleur_bisexuee_soudure_de_la_corolle","fleur_bisexuee_soudure_de_la_corolle_","field_63108755cce3d1",["soudé sur plus de la moitié de la longueur","soudé sur moins de la moitié de la longueur","partiellement soudés entre eux","libre"],["soudés sur plus de la moitié de la longueur","soudés sur moins de la moitié de la longueur","soudés pour une partie d'entre eux","libres"]); */

/* modifyData("fleur_femelle_soudure_de_la_corolle","fleur_femelle_soudure_de_la_corolle_","field_63075d5aecd761",["soudé sur plus de la moitié de la longueur","soudé sur moins de la moitié de la longueur","partiellement soudés entre eux","libre"],["soudés sur plus de la moitié de la longueur","soudés sur moins de la moitié de la longueur","soudés pour une partie d'entre eux","libres"]); */

/* modifyData("fleur_bisexuee_soudure_de_landrocee","fleur_bisexuee_soudure_de_landrocee_","field_631088ab31ceb1",["soudé(s) sur plus de la moitié de la longueur","soudé(s) sur moins de la moitié de la longueur","partiellement soudé(s) entre eux"],["soudées sur plus de la moitié de la longueur","soudées sur moins de la moitié de la longueur","soudées pour une partie d'entre elles"]); */

/* modifyData("fleur_male_soudure_de_landrocee","fleur_male_soudure_de_landrocee_","field_6304f8f3829c91",["soudé sur plus de la moitié de la longueur","soudé sur moins de la moitié de la longueur","partiellement soudés entre eux","libre"],["soudées sur plus de la moitié de la longueur","soudées sur moins de la moitié de la longueur","soudées pour une partie d'entre elles","libres"]); */

/* modifyData("fleur_bisexuee_soudure_des_carpelles","fleur_bisexuee_soudure_des_carpelles_","field_6310a8be3ed581",["soudés sur toute la longueur"],["soudés sur toute la longueur (ovaire, styles, stigmates)"]); */

/* modifyData("fleur_femelle_soudure_des_carpelles","fleur_femelle_soudure_des_carpelles_","field_63076023ecd7c1",["soudés sur toute la longueur","soudés sur toute la longueur (ovaire, styles, stigmates) (ovaire, styles, stigmates)"],["soudés sur toute la longueur (ovaire, styles, stigmates)","soudés sur toute la longueur (ovaire, styles, stigmates)"]); */

/* modifyData("fleur_bisexuee_ovaire","fleur_bisexuee_ovaire_","field_6310a9e23ed591",["semi-infère"],["intermédiaire"]); */

/* modifyData("fleur_femelle_ovaire","fleur_femelle_ovaire_","field_630761dbecd7d1",["semi-infère"],["intermédiaire"]); */

/* modifyData("cultivee_en_france","cultivee_en_france_","field_63073315d174c1",["seulement à l'état cultivée"],["seulement à l'état cultivé"]); */

