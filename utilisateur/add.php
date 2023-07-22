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

 
    $id_role= addslashes($_POST['id_role']);
    $matricule_utilisateur= addslashes($_POST['matricule_utilisateur']);
    $nom_utilisateur= addslashes($_POST['nom_utilisateur']);
    $prenom_utilisateur= addslashes($_POST['prenom_utilisateur']);
    $date_naissance_utilisateur= addslashes($_POST['date_naissance_utilisateur']);
    $lieu_naissance_utilisateur= addslashes($_POST['lieu_naissance_utilisateur']);
    $date_insertion_utilisateur= addslashes($_POST['date_insertion_utilisateur']);
    $telephone_utilisateur= addslashes($_POST['telephone_utilisateur']);
    $email_utilisateur = addslashes($_POST['email_utilisateur']);
    $password_utilisateur= addslashes($_POST['password_utilisateur']);
    $password_hash = md5($password_utilisateur);
    if (!empty($nom_utilisateur)) {
        if (isset($_FILES['image_utilisateur']) && !empty($_FILES['image_utilisateur']['name'])) {
            $image_utilisateur= $_FILES['image_utilisateur']['name'];
            $image_utilisateur_tmp = $_FILES['image_utilisateur']['tmp_name'];
            $image_utilisateur_dir = "images/";
            move_uploaded_file($image_utilisateur_tmp, $image_utilisateur_dir . $image_utilisateur);
        } else {
            $image_utilisateur= 'profil.png';
        }
        $query = "INSERT INTO utilisateur(id_role,matricule_utilisateur,nom_utilisateur,prenom_utilisateur,date_naissance_utilisateur,
        lieu_naissance_utilisateur,date_insertion_utilisateur,telephone_utilisateur,
        email_utilisateur,password_utilisateur,image_utilisateur)
        VALUES ('$id_role','$matricule_utilisateur','$nom_utilisateur','$prenom_utilisateur','$date_naissance_utilisateur','$lieu_naissance_utilisateur',
        '$date_insertion_utilisateur','$telephone_utilisateur',
        '$email_utilisateur','$password_hash','$image_utilisateur')";
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
