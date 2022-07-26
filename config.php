<?php
header("Access-Control-Allow-Origin: *");
header('Access-Control-Allow-Credentials: true');
header("Access-Control-Allow-Methods: GET, POST, OPTIONS");
header("Access-Control-Allow-Headers: Content-Type");

$host="";
$database_name="";
$user="";
$password="";
$connexion = new PDO("mysql:host=$host;dbname=$database_name",$user,$password);
//à commenter en mode production
$connexion->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
// methode de récupération des données selon le type d'opperation
$add_params=$_POST;
$delete_params=$_POST;
$edit_params =$_POST;
$get_params=$_GET;
//mode déploiement ou pas
$mode_deploiement=false;
//reglage de l'heure
date_default_timezone_set("UTC");
?>  