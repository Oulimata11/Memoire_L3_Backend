<?php

use Taf\TafAuth;
use Taf\TableQuery;

try {
    require './config.php';
    require '../TableQuery.php';
    require '../taf_auth/TafAuth.php';
    $taf_auth = new TafAuth();
    // toutes les actions nécéssitent une authentification
    $auth_reponse = $taf_auth->check_auth();
    if ($auth_reponse["status"] == false) {
        echo json_encode($auth_reponse);
        die;
    }

    $table_query = new TableQuery($table_name);
    /* 
        $params
        contient tous les parametres envoyés par la methode POST
     */

    // if (empty($params)) {
    //     $reponse["status"] = false;
    //     $reponse["erreur"] = "Parameters required";
    //     echo json_encode($reponse);
    //     exit;
    // }
    // pour charger l'heure courante
    // $params["date_enregistrement"]=date("Y-m-d H:i:s");
//   // Chiffrement du mot de passe

 
    $id_utilisateur= addslashes($_POST['id_utilisateur']);
    $nom_gardien= addslashes($_POST['nom_gardien']);
    $prenom_gardien= addslashes($_POST['prenom_gardien']);
    $date_naissance_gardien= addslashes($_POST['date_naissance_gardien']);
    $lieu_naissance_gardien= addslashes($_POST['lieu_naissance_gardien']);
    $date_insertion_gardien= addslashes($_POST['date_insertion_gardien']);
    $telephone_gardien= addslashes($_POST['telephone_gardien']);
    $email_gardien = addslashes($_POST['email_gardien']);
    if (!empty($nom_gardien)) {
        if (isset($_FILES['image_gardien']) && !empty($_FILES['image_gardien']['name'])) {
            $image_gardien= $_FILES['image_gardien']['name'];
            $image_gardien_tmp = $_FILES['image_gardien']['tmp_name'];
            $image_gardien_dir = "../images/";
            move_uploaded_file($image_gardien_tmp, $image_gardien_dir . $image_gardien);
        } else {
            $image_gardien= 'profil.png';
        }
        $query = "INSERT INTO gardien(id_utilisateur,nom_gardien,prenom_gardien,date_naissance_gardien,
        lieu_naissance_gardien,date_insertion_gardien,telephone_gardien,
        email_gardien,image_gardien)
        VALUES ('$id_utilisateur','$nom_gardien','$prenom_gardien','$date_naissance_gardien','$lieu_naissance_gardien',
        '$date_insertion_gardien','$telephone_gardien',
        '$email_gardien','$image_gardien')";
        if ($taf_config->get_db()->exec($query)) {
            $reponse["status"] = true;
            $params["id"] = $taf_config->get_db()->lastInsertId();
            $reponse["data"] = $params;
        } else {
            $reponse["status"] = false;
            $reponse["erreur"] = "Erreur d'insertion à la base de ";
        }
        echo json_encode($reponse);
    } else {
        echo json_encode(["status" => false, "message" => "donnees manquant!!"]);
    }
} catch (\Throwable $th) {

    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}
