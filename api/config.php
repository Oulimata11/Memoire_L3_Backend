<?php
try {
    require '../config.php';
    require '../function.php';
    $reponse = array();
    $table_name = "{{{table_name}}}";
} catch (\Throwable $th) {

    echo "<h1>" . $th->getMessage() . "</h1>";
}
?>