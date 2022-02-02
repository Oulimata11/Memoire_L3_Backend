<?php
try {
    require './config.php';
    require './function.php';
    $reponse = array();

    $query = "SHOW TABLES";
    $tables = $connexion->query($query)->fetchAll(PDO::FETCH_ASSOC);
    json_encode($tables);
    $non_connecte = false;
} catch (\Throwable $th) {
    // echo "<h1>veillez éditer le fichier de configuration (config.php), renseigner la bonne base de donnée et un utilisateur</h1>";
    // echo "<h1>" . $th->getMessage() . "</h1>";
    // exit;
    $non_connecte = true;
}
?>
<!DOCTYPE html>
<html lang="fr">

    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>TAF</title>
        <link href="./bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
        <style>
            #editor{
                height: 300px;
                font-size: 14px;
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
                <?php if (!$non_connecte) : ?>
                    <h1 class="col-12 ">La(es) table(s) de la base de données <span class="text-danger"><?= $database_name ?></span>
                        <a href="./generate.php?tout=oui" class="px-2 right"><button class="btn btn-warning">Tout générer</button></a>
                    </h1>
                    <p class="col-12 fs-4 mt-2">
                    <ol class="list-group" id="mes_tables">
                        <?php
                        $dir    = './';
                        $files = scandir($dir);
                        foreach ($tables as $key => $value) {
                            $table_name = $value["Tables_in_" . strtolower($database_name)];
                            if (array_search($table_name, $files)) {
                                echo "<li id='table_$table_name' class='list-group-item fs-3  d-flex justify-content-between align-items-center bg-light'><span>" . $table_name . "</span><a class='px-2 right' href='./$table_name'><button class='btn btn-primary'> --------> voir exemple </button></a></li>";
                            } else {
                                echo "<li id='table_$table_name' class='list-group-item fs-3  d-flex justify-content-between align-items-center'><span>" . $table_name . "</span><a class='px-2 right' href='generate?table=$table_name'><button class='btn btn-secondary'>Générer les routes </button></a>";
                            }
                        }
                        ?>
                    </ol>
                    <?php elseif ($non_connecte && ($host != "" || $user != "" || $password != "" || $database_name != "")) : ?>
                    <div class="alert alert-danger fs-3" role="alert">
                        Echec de connexion à votre base de données <span class="text-danger"><?= $database_name; ?></span> avec l'utilisateur <span class="text-danger"><?= $user; ?></span>
                    </div>
                <?php else: ?>
                    <div class="alert alert-warning fs-3" role="alert">
                        Après la configuration, vous actualisez cette page
                    </div>
                <?php endif; ?>
                </p>
                <h1 class="col-12 mt-5">
                    Configuration
                </h1>
                <p class="col-12 fs-4 mt-2">
                    La configuration repose sur le fichier <span class="text-danger">config.php</span>.
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
    <script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.13/ace.js" type="text/javascript" charset="utf-8"></script>
    <script>
        var config = {
            theme: "ace/theme/monokai",
            selectionStyle: "text",
            readOnly: true,
            showLineNumbers: false,
            showGutter: false
        };

        var editor = ace.edit('editor', config);
        editor.session.setMode({path:"ace/mode/php", inline:true});
    </script>
</html>