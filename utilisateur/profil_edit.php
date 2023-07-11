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

    if(empty($params)){
        $reponse["status"] = false;
        $reponse["erreur"] = "Parameters required";
        echo json_encode($reponse);
        exit;
    }
    // // condition sur la modification
    // $condition=$table_query->dynamicCondition(json_decode($params["condition"]),'=');
    // // execution de la requete de modification
    // $query=$table_query->dynamicUpdate(json_decode($params["data"]),$condition);
    //$reponse["query"]=$query;
    $id_utilisateur=$params["id_utilisateur"];
    $nom_utilisateur = $params["nom_utilisateur"];
    $prenom_utilisateur = $params["prenom_utilisateur"];
    $date_naissance_utilisateur= $params["date_naissance_utilisateur"];
    $lieu_naissance_utilisateur =$params["lieu_naissance_utilisateur"];
    $date_insertion_utilisateur =$params["date_insertion_utilisateur"];
    $telephone_utilisateur =$params["telephone_utilisateur"];
    $query="UPDATE utilisateur set nom_utilisateur=$nom_utilisateur, prenom_utilisateur=$prenom_utilisateur,
    date_naissance_utilisateur = $date_naissance_utilisateur, lieu_naissance_utilisateur=$lieu_naissance_utilisateur,
    date_insertion_utilisateur=$date_insertion_utilisateur,telephone_utilisateur=$telephone_utilisateur
    where id_utilisateur=$id_utilisateur ";
    $resultat=$taf_config->get_db()->exec($query);
    if ($resultat) {
        $reponse["status"] = true;
    } else {
        $reponse["status"] = false;
        $reponse["erreur"] = "Erreur! ou pas de moification";
    }
    echo json_encode($reponse);
} catch (\Throwable $th) {
    
    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}

?>