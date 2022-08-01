<?php

use Taf\TableQuery;

try {
    require './config.php';
    require '../TableQuery.php';
    $table_query=new TableQuery($table_name);

    $condition=$table_query->dynamicCondition($_GET,"like");
    // $reponse["condition"]=$condition;
    $query="select *from $table_name ".$condition;
    $reponse["data"] = $taf_config->get_db()->query($query)->fetchAll(PDO::FETCH_ASSOC);
    $reponse["status"] = true;

    echo json_encode($reponse);
} catch (\Throwable $th) {
    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}

?>