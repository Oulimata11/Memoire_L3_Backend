<?php
try {
    require './config.php';
    $params=$get_params;

    $condition=dynamicCondition($params,"like");
    // $reponse["condition"]=$condition;
    $query="SELECT m.id,m.matricule,m.prenom,m.nom,c.* FROM membre m join cotisation c on m.id=c.id_membre ";
    $reponse["data"] = $connexion->query($query)->fetchAll(PDO::FETCH_ASSOC);
    $reponse["status"] = true;

    echo json_encode($reponse);
} catch (\Throwable $th) {
    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}

?>