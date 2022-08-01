<?php

use Taf\TafAuth;

try {
    require './TafAuth.php';
    $taf_auth= new TafAuth();

    $taf_auth->check_auth($reponse);

    echo json_encode($reponse);
} catch (\Throwable $th) {
    $reponse["status"] = false;
    $reponse["erreur"] = $th->getMessage();

    echo json_encode($reponse);
}
