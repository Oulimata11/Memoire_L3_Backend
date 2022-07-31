<?php
    require './TafConfig.php';
    $taf_config = new \Taf\TafConfig();
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAF</title>
    <link href="./taf_assets/bootstrap.min.css" rel="stylesheet">
    <style>
        #editor {
            height: 100px;
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
                <a href="#" class="navbar-brand text-danger">TAF</a>
                <a href="https://h24code.com/donate.html" target="_blank" class="px-2 right"><button class="btn btn-secondary">Faire un don</button></a>
            </div>
        </nav>
    </header>
    <main class="container mt-5">
        <div class="row">
            <p class="col-12 text-justify fs-4">
                TAF est un générateur automatique d'api à partir d'une base de données <strong>MYSQL</strong>. Une fois
                que vous avez bien configuré le fichier de configuration (config.php), grâce à l'api vous pouvez voir toutes
                les tables de votre base de données et ainsi générer les fichiers nécessaires à la manipulation de ces tables
                comme la recupération, la suppression, l'ajout, la modification d'une donnée.
            </p>
            <?php if ($taf_config->is_connected()) : ?> 
                <h1 class="col-12 ">La(es) table(s) de la base de données <span class="text-danger"><?= $taf_config->database_name ?></span>
                    <a href="./generate.php?tout=oui" class="px-2 right"><button class="btn btn-warning">Tout générer</button></a>
                </h1>
                <p class="col-12 fs-4 mt-2">
                <ol class="list-group" id="mes_tables">
                    <?php
                    $dir    = './';
                    $files = scandir($dir);
                    foreach ($taf_config->tables as $key => $value) {
                        $mysql_suffixe="Tables_in_";
                        $table_name = $value[$mysql_suffixe . strtolower($taf_config->database_name)];
                        if (array_search($table_name, $files)) { // table dèja générée
                            echo "<li id='table_$table_name' class='list-group-item fs-3  d-flex justify-content-between align-items-center bg-light'><span>" . $table_name . "</span><a class='px-2 right' href='./$table_name'><button class='btn btn-primary'> --------> voir exemple </button></a></li>";
                        } else {// table non encore générée
                            echo "<li id='table_$table_name' class='list-group-item fs-3  d-flex justify-content-between align-items-center'><span>" . $table_name . "</span><a class='px-2 right' href='generate?table=$table_name'><button class='btn btn-secondary'>Générer les routes </button></a>";
                        }
                    }
                    ?>
                </ol>
            <?php elseif (!$taf_config->is_connected() && ($taf_config->host != "" || $taf_config->user != "" || $taf_config->password != "" || $taf_config->database_name != "")) : ?>
                <div class="alert alert-danger fs-3" role="alert">
                    Echec de connexion à votre base de données <span class="text-danger"><?= $taf_config->database_name; ?></span> avec l'utilisateur <span class="text-danger"><?= $taf_config->user; ?></span>
                </div>
            <?php else : ?>
                <div class="alert alert-warning fs-3" role="alert">
                    Après la configuration, vous actualisez cette page
                </div>
            <?php endif; ?>
            <h1 class="col-12 mt-5">
                Configuration
            </h1>
            <p class="col-12 fs-4 mt-2">
                La configuration repose sur le fichier <span class="text-danger">TafConfig.php</span>.
                Dans ce fichier vous devez spécifier: <br>
                • l'adresse de votre serveur MYSQL <br>
                • le nom de votre base de donnée <br>
                • votre nom d'utilisateur <br>
                • votre mot de passe <br>
                Vous aurez donc 4 variables à paramétrer comme dans le code ci dessous:
            </p>
        </div>
        <div class="col-12">
            <div class="row position-relative my-3">
                <div id="editor" class="col-12">
                    $host = "localhost:3306";
                    $database_name = "test";
                    $user = "root";
                    $password = "root";
                </div>
            </div>
        </div>
    </main>
</body>
<!-- JavaScript Bundle with Popper -->
<script src="./taf_assets/bootstrap.bundle.min.js" ></script>
<script src="./taf_assets/ace.js" type="text/javascript" charset="utf-8"></script>
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