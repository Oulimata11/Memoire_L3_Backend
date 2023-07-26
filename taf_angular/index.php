<?php
use Taf\TableDocumentation;
session_start();
require '../TafConfig.php';
require '../TableDocumentation.php';
$taf_config = new \Taf\TafConfig();
$taf_config->check_documentation_auth();
?>

<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>JantTaf</title>
    <link href="../taf_assets/bootstrap.min.css" rel="stylesheet">
    <style>
        #editor {
            height: 250px;
            font-size: 14px;
        }

        #get_exemple,
        #add_exemple,
        #edit_exemple,
        #delete_exemple,
        #add_form {
            height: 500px;
            max-height: 750px;
            font-size: 14px;
        }

        #add_form_ts {
            height: 500px;
            max-height: 750px;
            font-size: 14px;
        }

        #json_add,
        #json_edit,
        #json_delete {
            height: 300px;
            max-height: 500px;
            font-size: 14px;
        }

        #json_delete {
            height: 200px;
        }
    </style>
</head>

<body class="bg-light">
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-dark">
            <div class="container-fluid">
                <a href="#" class="navbar-brand text-danger">JantTaf</a>
                <span>
                    <a href="https://h24code.com/donate.html" target="_blank" class="px-2 right"><button class="btn btn-secondary">Faire un don</button></a>
                    <a href="login.php" class="px-2 right"><button class="btn btn-danger">Déconnexion</button></a>
                </span>
            </div>
        </nav>
    </header>
    <main class="container mt-5">
        <div class="row">
            <?php if ($taf_config->is_connected()) : ?>
                <h1 class="col-12 ">Fichier de configuration pour le projet angular <span class="text-danger"><?= $taf_config->database_name ?></span>
                    <a href="./generate.php?tout=oui" class="px-2 right"><button class="btn btn-primary">Télécharger</button></a>
                </h1>
                <div class="col-12">
                    <div class="row position-relative my-3">
                        <div id="editor" class="col-12">
                            <?php
                            function get_base_url()
                            {
                                if (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on') {
                                    $url = "https://";
                                } else {
                                    $url = "http://";
                                }
                                // Append the host(domain name, ip) to the URL.   
                                $url .= $_SERVER['HTTP_HOST'];
                        
                                // Append the requested resource location to the URL   
                                $url .= $_SERVER['REQUEST_URI'];
                        
                                return $url;
                            }
                            echo json_encode([
                                "projectName" => "projet1.angular",
                                "decription" => "Fichier de configuration de Taf",
                                "taf_base_url" => get_base_url(),
                                "les_modules" => [
                                    [
                                        "module" => "home",
                                        "les_tables" => array_map(function($une_table){
                                            $docs=new TableDocumentation($une_table);
                                            return ["table" => $une_table, "description"=>$docs->description,"les_types"=>["add","edit","list","details"]];
                                        },$taf_config->tables)
                                    ],
                                    [
                                        "module" => "public",
                                        "les_tables" => [
                                            ["table" => "login", "description"=>["login","pwd"],"les_types"=>["login"]]
                                        ]
                                    ],
                                ]
                            ]);

                            ?>
                        </div>
                    </div>
                </div>
            <?php elseif (!$taf_config->is_connected() && ($taf_config->host != "" || $taf_config->user != "" || $taf_config->password != "" || $taf_config->database_name != "")) : ?>
                <div class="alert alert-danger fs-3" role="alert">
                    Echec de connexion à votre base de données <span class="text-danger"><?= $taf_config->database_name; ?></span> avec l'utilisateur <span class="text-danger"><?= $taf_config->user; ?></span>
                </div>
            <?php else : ?>
                <div class="alert alert-warning fs-3" role="alert">
                    Après la configuration, vous actualisez cette page
                </div>
            <?php endif; ?>

        </div>
    </main>
</body>
<!-- JavaScript Bundle with Popper -->
<script src="../taf_assets/bootstrap.bundle.min.js"></script>
<script src="../taf_assets/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
    var config = {
        theme: "ace/theme/monokai",
        selectionStyle: "text",
        readOnly: true,
        showLineNumbers: false,
        showGutter: false
    };

    var editor = ace.edit('editor', config);
    editor.session.setMode({
        path: "ace/mode/php",
        inline: true
    });
</script>

</html>