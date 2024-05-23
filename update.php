<?php
define ('ABSPATH','./'); 
require_once "../../../wp-config.php";

function returnParams(){
  
  //Changer les paramètres selon serveur
  $dbname = DB_NAME;
  $username = DB_USER;
  $password = DB_PASSWORD;
  $host = DB_HOST;

  // Construire le DSN pour la connexion PDO
  if ($host==='localhost'){
    //Si test en local, remplacer le socket par celui dans l'onglet Database de localWP car la connection par serveur ne fonctionne pas
    $socket = '';
    $dsn = "mysql:unix_socket=$socket;dbname=$dbname;charset=utf8mb4";
    return new Params($username,$password,$dsn);
  }else{
    $dsn = "mysql:host=$host;dbname=$dbname";
    return new Params($username,$password,$dsn);
  }
  
}

class Params{
  private $username="";
  private $password="";
  private $dsn="";

  public function __construct($username,$password,$dsn) {
    $this->username = $username;
    $this->password = $password;
    $this->dsn = $dsn;
  }

  function set_username($username) {
    $this->username = $username;
  }
  function get_username() {
    return $this->username;
  }

  function set_password($password) {
    $this->password = $password;
  }
  function get_password() {
    return $this->password;
  }

  function set_dsn($dsn) {
    $this->dsn = $dsn;
  }
  function get_dsn() {
    return $this->dsn;
  }

}
function modifyData($ancien_champ,$nouveau_champ,$field,$mots_a_corriger = null,$mots_corriges = null){
    //Changer les paramètres selon serveur
    $params = returnParams();
    
    try {
      $conn = new PDO($params->get_dsn(), $params->get_username(), $params->get_password());
      
      $req = "SELECT post_id,meta_value FROM wp_postmeta WHERE meta_key='$ancien_champ'";
    
      $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

      $stmt = $conn->query($req);
      $res = $stmt->fetchAll();
     
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
    
          if(isset($mots_a_corriger) AND isset($mots_corriges)){
            
            for ($i=0;$i<count($mots_a_corriger);$i++){
              
              $mot_a_corr = $mots_a_corriger[$i];
              $mot_corr = $mots_corriges[$i];
              $data=[$nouveau_champ,"%$mot_a_corr%"];
              $stmt = $conn->prepare("SELECT meta_id,meta_value FROM wp_postmeta WHERE meta_key=? AND meta_value LIKE ?");
              $stmt->execute($data);
              $res = $stmt->fetchAll();
              
              if (!empty($res)){
                foreach($res as $item){
                  $value = $item['meta_value'];
                  $id = $item['meta_id'];
                  $value_parts = explode(";",$value);
                  
                  for ($j=0;$j<count($value_parts);$j++){
                    $part = $value_parts[$j];
                    
                      if(str_contains($part,$mot_a_corr) AND preg_match("(l'année|hémiparasite|holoparasite|libres|soudés sur toute la longueur (ovaire, styles, stigmates)|exotique envahissante)", $part) !== 1){
                       
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

function modifyDataSeasons($ancien_champ,$nouveau_champ,$field,$mots_a_corriger = null){
  

  $params = returnParams();
    
  try {
    $conn = new PDO($params->get_dsn(), $params->get_username(), $params->get_password());
    
    $req = "SELECT post_id,meta_value FROM wp_postmeta WHERE meta_key='$ancien_champ'";
    
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->query($req);
    $res = $stmt->fetchAll();
    
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
  
        if(isset($mots_a_corriger)){
          
          for ($i=0;$i<count($mots_a_corriger);$i++){
            
            $mot_a_corr = $mots_a_corriger[$i];
            
            $data=[$nouveau_champ,"%$mot_a_corr%"];
            $stmt = $conn->prepare("SELECT meta_id,meta_value FROM wp_postmeta WHERE meta_key=? AND meta_value LIKE ?");
            $stmt->execute($data);
            $res = $stmt->fetchAll();
          
            if (!empty($res)){
              foreach($res as $item){
                $value = $item['meta_value'];
                $id = $item['meta_id'];
                $value_parts = explode(";",$value);
                
                for ($j=0;$j<count($value_parts);$j++){
                  $part = $value_parts[$j];
                  
                    $hiver = ["Janvier","Février","Mars"];
                    $printemps = ["Avril","Mai","Juin"];
                    $ete = ["Juillet","Août","Septembre"];
                    $automne = ["Octobre","Novembre","Décembre"];
                    for ($k=0;$k<count($hiver);$k++){
                      if(str_contains($part,$hiver[$k])){
                      
                        $part = preg_replace("([0-9]+)",strlen('hiver'),$part);
                        $part = str_replace($mot_a_corr,'hiver',$part);
                        $value_parts[$j]=$part;
                      }else if(str_contains($part,$printemps[$k])){
                      
                        $part = preg_replace("([0-9]+)",strlen('printemps'),$part);
                        $part = str_replace($mot_a_corr,'printemps',$part);
                        $value_parts[$j]=$part;
                      }else if(str_contains($part,$ete[$k])){
                      
                        $part = preg_replace("([0-9]+)",strlen('été'),$part);
                        $part = str_replace($mot_a_corr,'été',$part);
                        $value_parts[$j]=$part;
                      }else if(str_contains($part,$automne[$k])){
                      
                        $part = preg_replace("([0-9]+)",strlen('automne'),$part);
                        $part = str_replace($mot_a_corr,'automne',$part);
                        $value_parts[$j]=$part;
                      }
                    }
                   
                    
                  
                }
                $tableau = array_unique($value_parts);
                $array_values=[];
                array_push($array_values,$tableau[0]);
                $cpt = 0;
                foreach ($tableau as $val){
                  if (preg_match("(i:[0-9]+)",$val)){
                    continue;
                  }else if ($val==="}"){
                    array_push($array_values,$val);
                  }else{
                   
                    if($cpt > 0){
                      array_push($array_values,"i:$cpt");
                    }
                    array_push($array_values,$val);
                    $cpt++;
                  }
                }
               
                $array_values[0]=preg_replace("(a:[0-9]+)","a:$cpt",$array_values[0]);
                $value = implode(";",$array_values);
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

function modifyDataPhoto($ancien_champ,$nouveau_champ,$field){
  $params = returnParams();
    
  try {
    $conn = new PDO($params->get_dsn(), $params->get_username(), $params->get_password());
    
    $req = "SELECT post_id,meta_value FROM wp_postmeta WHERE meta_key='$ancien_champ'";
  
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    $stmt = $conn->query($req);
    $res = $stmt->fetchAll();
    
    $data=[];
    $data2=[];
    foreach($res as $item){
      $id = $item['post_id'];
      $value = $item['meta_value'];
      $req = "SELECT meta_id FROM wp_postmeta WHERE meta_key='$nouveau_champ' AND post_id=$id";
      $stmt = $conn->query($req);
      $res = $stmt->fetch();
     
      if (empty($res)){
        array_push($data,[$id, $nouveau_champ, $value]);
        array_push($data2,[$id,"_$nouveau_champ",$field]);
      }
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
  
        $conn->commit();

  
    }catch (Exception $e){
        $conn->rollback();
        throw $e;
    }
    
    
  } catch(PDOException $e) {
    echo "Connection failed: " . $e->getMessage();
  }
}


//Fruit : type
modifyData("fruit_type","fruit_type_de_fruit","field_6307665aecd841",["une crypsèle"],["une cypsèle"]);

/* modifyData("inflorescence_categorie","inflorescence_categorie_","field_6304ec28c13d61",["un panicule"],["une panicule"]); */

//modifyData("fleur_bisexuee_localisation_des_poils","fleur_bisexuee_localisation_des_poils_","field_6310aa953ed5c1",["l'androcée","le gynécée"],["les étamines","les carpelles"]);

/* modifyData("fleur_male_localisation_des_poils","fleur_male_localisation_des_poils_","field_6304fcbc829d01",["l'androcée","le gynécée"],["les étamines","les carpelles"]); */

/* modifyData("fleur_femelle_localisation_des_poils","fleur_femelle_localisation_des_poils_","field_6307632eecd801",["l'androcée","le gynécée"],["les étamines","les carpelles"]); */

//modifyData("feuilles_aeriennes_limbe_des_feuilles_simples","feuilles_aeriennes_limbe_des_feuilles_simples_","field_6304dac552e051",["oblongue"],["oblong"]);

//modifyData("feuilles_des_rameaux_steriles_limbe_des_feuilles_simples","feuilles_des_rameaux_steriles_limbe_des_feuilles_simples_","field_634e49d1480131",["oblongue"],["oblong"]); 

//modifyData("feuilles_des_rameaux_fleuris_limbe_des_feuilles_simples","feuilles_des_rameaux_fleuris_limbe_des_feuilles_simples_","field_634e49eb480211",["oblongue"],["oblong"]); 

//modifyData("feuilles_immergees_limbe_des_feuilles_simples","feuilles_immergees_limbe_des_feuilles_simples_","field_634e48ca9fff01",["oblongue"],["oblong"]); 

//modifyData("feuilles_aeriennes_limbe_des_folioles","feuilles_aeriennes_limbe_des_folioles_","field_6304db62937371",["oblongue"],["oblong"]);

//modifyData("feuilles_des_rameaux_steriles_limbe_des_folioles","feuilles_des_rameaux_steriles_limbe_des_folioles_","field_634e49d1480141",["oblongue"],["oblong"]); 

//modifyData("feuilles_des_rameaux_fleuris_limbe_des_folioles","feuilles_des_rameaux_fleuris_limbe_des_folioles_","field_634e49eb480221",["oblongue"],["oblong"]); 

//modifyData("feuilles_immergees_limbe_des_folioles","feuilles_immergees_limbe_des_folioles_","field_634e48ca9fff11",["oblongue"],["oblong"]); 

/* modifyData("tige_tige_aerienne","tige_tige_aerienne_","field_6304c66b239191",["visible"],["visible toute l'année"]); */

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

/* modifyData("fleur_femelle_soudure_des_carpelles","fleur_femelle_soudure_des_carpelles_","field_63076023ecd7c1",["soudés sur toute la longueur"],["soudés sur toute la longueur (ovaire, styles, stigmates)"]); */

/* modifyData("fleur_bisexuee_ovaire","fleur_bisexuee_ovaire_","field_6310a9e23ed591",["semi-infère"],["intermédiaire"]); */

/* modifyData("fleur_femelle_ovaire","fleur_femelle_ovaire_","field_630761dbecd7d1",["semi-infère"],["intermédiaire"]); */

/* modifyData("cultivee_en_france","cultivee_en_france_","field_63073315d174c1",["seulement à l'état cultivée"],["seulement à l'état cultivé"]); */

modifyDataSeasons("adaptations_aux_pratiques_de_culture_periode_de_levee","adaptations_aux_pratiques_de_culture_periode_de_levee_","field_65143be5c09dd1",["Janvier","Février","Mars","Avril","Mai","Juin","Juillet","Août","Septembre","Octobre","Novembre","Décembre"]); 

//modifyData("indigenat","indigenat_","field_63073560d174f1",["envahissante"],["exotique envahissante"]);

/* modifyDataPhoto("feuilles_aeriennes_photo_de_feuilles_aeriennes","feuilles_aeriennes_illustration_de_la_feuille_aerienne_photo_de_feuilles_aeriennes","field_6304d939b94a3"); */

/* modifyDataPhoto("feuilles_des_rameaux_fleuris_photo_de_feuilles_des_rameaux_fleuris","feuilles_des_rameaux_fleuris_illustration_de_la_feuille_des_rameaux_fleuris_photo_de_feuilles_des_rameaux_fleuris","field_634e49eb4801e"); */

/* modifyDataPhoto("feuilles_des_rameaux_steriles_photo_de_feuilles_des_rameaux_steriles","feuilles_des_rameaux_steriles_illustration_de_la_feuille_des_rameaux_steriles_photo_de_feuilles_des_rameaux_steriles","field_634e49d148010"); */

/* modifyDataPhoto("feuilles_immergees_photo_de_feuilles_immergees","feuilles_immergees_illustration_de_la_feuille_immergee_photo_de_feuilles_immergees","field_634e48ca9ffed"); */

/* modifyDataPhoto("fleur_bisexuee_photo_de_fleur_bisexuee","fleur_bisexuee_illustration_de_la_fleur_bisexuee_photo_de_fleur_bisexuee","field_63108532cce34"); */

/* modifyDataPhoto("fleur_femelle_photo_de_fleur_femelle","fleur_femelle_illustration_de_la_fleur_femelle_ou_de_linflorescence_photo_de_fleur_femelle","field_630640cbdb1e4"); */

/* modifyDataPhoto("fleur_male_photo_de_fleur_male","fleur_male_illustration_de_la_fleur_male_ou_de_linflorescence_photo_de_fleur_male","field_6304ef9dbe188"); */

/* modifyDataPhoto("fruit_photo","fruit_illustration_du_fruit_photo","field_63076618ecd83"); */

/* modifyDataPhoto("tige_photo_tige","tige_illustration_de_la_tige_photo_tige","field_6304c61a23918"); */

//modifyDataPhoto("photo_de_la_plante_entiere","illustration_de_la_plante_entiere_photo_de_la_plante_entiere","field_6304bda381ab9");