<?php
echo "<h1><a href='./taf'>Accueil</a></h1>";
try {
    require './config.php';
    if(!isset($_GET["table"]) && !isset($_GET["tout"])){
        echo "<h1>Paramètre(s) requi(s)</h1>";
        exit;
    }
    
    function generate($table_name){
        echo "<h1>Génération des routes de la table \"" . $table_name . "\"</h1>";

        $config_api = "./api/config.php";
        $config_content = file_get_contents($config_api);
        $config_content=str_replace("{{{table_name}}}",$table_name,$config_content);
        if ( !is_dir( "./".$table_name ) ) {
            mkdir("./".$table_name);      
        }
        file_put_contents('./'.$table_name."/config.php", $config_content);
    
        copy('./api/add.php', "./".$table_name."/add.php");
        copy('./api/delete.php', "./".$table_name."/delete.php");
        copy('./api/edit.php', "./".$table_name."/edit.php");
        copy('./api/get.php', "./".$table_name."/get.php");
        copy('./api/index.php', "./".$table_name."/index.php");
        echo "<h3>Succes</h3>";
    }
    if(isset($_GET["table"])){
        $table_name=$_GET["table"];
        generate($table_name);
        header('location:./taf#table_'.$table_name);
    }elseif(isset($_GET["tout"])){
        $query = "SHOW TABLES";
        $tables = $connexion->query($query)->fetchAll(PDO::FETCH_ASSOC);
        foreach ($tables as $key => $value) {
            $table_name = $value["Tables_in_" . $database_name];
            generate($table_name);
        }
        header('location: taf.php');
    }
    

    
} catch (\Throwable $th) {

    echo "<h1>" . $th->getMessage() . "</h1>";
}
?>