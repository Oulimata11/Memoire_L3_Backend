<?php
try {
    require '../config.php';
    require '../TableConfig.php';
    $reponse = array();
    $table_name = "{{{table_name}}}";
    
    $table_config=new TableConfig($table_name);
} catch (\Throwable $th) {
    echo "<h1>" . $th->getMessage() . "</h1>";
}
?>