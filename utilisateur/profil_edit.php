<?php
use Taf\TafAuth;
use Taf\TableQuery;
try {
    require './config.php';
    require '../TableQuery.php';
    require '../taf_auth/TafAuth.php';
    $taf_auth = new TafAuth();
    // toutes les actions nécéssitent une authentification
    $auth_reponse=$taf_auth->check_auth($reponse);
    if ($auth_reponse["status"] == false) {
        echo json_encode($auth_reponse);
        die;
    }
    
    $table_query=new TableQuery($table_name);
   /* 
        $params
        contient tous les parametres envoyés par la methode POST
     */
 
    $id_utilisateur=addslashes($_POST["id_utilisateur"]);
    $nom_utilisateur =addslashes( $_POST["nom_utilisateur"]);
    $prenom_utilisateur = addslashes($_POST["prenom_utilisateur"]);
    $date_naissance_utilisateur =addslashes( $_POST["date_naissance_utilisateur"]);
    $lieu_naissance_utilisateur =addslashes( $_POST["lieu_naissance_utilisateur"]);
    $telephone_utilisateur = addslashes($_POST["telephone_utilisateur"]);
    if(!empty($id_utilisateur)){
        if (isset($_FILES['image_utilisateur']) && !empty($_FILES['image_utilisateur']['name'])) {
            $image_utilisateur = $_FILES["image_utilisateur"]["name"];
            $image_utilisateur_tmp = $_FILES["image_utilisateur"]["tmp_name"];
            $image_utilisateur_dir = "../images/";
            move_uploaded_file($image_utilisateur_tmp, $image_utilisateur_dir . $image_utilisateur);
        } else {
            $image_query = "SELECT image_utilisateur FROM utilisateur WHERE id_utilisateur = '$id_utilisateur'";
            $result = $taf_config->get_db()->query($image_query);
            $image = $result->fetch(PDO::FETCH_ASSOC);
            $image_utilisateur = $image['image_utilisateur'];
        }
        $query = "update  utilisateur set nom_utilisateur='$nom_utilisateur',
        prenom_utilisateur = '$prenom_utilisateur',date_naissance_utilisateur = '$date_naissance_utilisateur',
        lieu_naissance_utilisateur='$lieu_naissance_utilisateur',telephone_utilisateur='$telephone_utilisateur',
         image_utilisateur = '$image_utilisateur' where id_utilisateur=$id_utilisateur";

        if ($taf_config->get_db()->exec($query)) {
            $reponse["status"] = true;
            $params["id"] = $taf_config->get_db()->lastInsertId();
            $_POST["image_utilisateur"] = $image_utilisateur;
            $reponse["data"] = $_POST;
        } else {
            $reponse["status"] = false;
            $reponse["erreur"] = "Erreur d'insertion à la base de ";
        }
        echo json_encode($reponse);

    } else {
        echo json_encode(["status"=>false,"message"=>"donnees manquant!!"]);
    }
} catch (\Throwable $th) {
    
    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}

?>