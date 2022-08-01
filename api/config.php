<?php

use Taf\TafAuth;

try {
    require '../TafConfig.php';

    $reponse = array();
    $table_name = "{{{table_name}}}";
    $taf_config = new \Taf\TafConfig();
} catch (\Throwable $th) {
    echo "<h1>" . $th->getMessage() . "</h1>";
}
