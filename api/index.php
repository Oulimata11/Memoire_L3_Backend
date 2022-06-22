<?php
try {
    require './config.php';
    mode($mode_deploiement);

    $query = "desc $table_name";
    $description = $connexion->query($query)->fetchAll(PDO::FETCH_ASSOC);
} catch (\Throwable $th) {

    echo "<h1>" . $th->getMessage() . "</h1>";
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>TAF</title>
    <link href="../bootstrap.min.css" rel="stylesheet" >
    <style>
        #get_exemple,
        #add_exemple,
        #edit_exemple,
        #delete_exemple,
        #add_form{
            height: 500px;
            max-height: 750px;
            font-size: 14px;
        }
        #add_form_ts{
            height: 500px;
            max-height: 750px;
            font-size: 14px;
        }

        #json_add,
        #json_edit,
        #json_delete{
            height: 300px;
            max-height: 500px;
            font-size: 14px;
        }

        #json_delete{
            height: 200px;
        }
    </style>
</head>

<body class="bg-light">
    <header>
        <nav class="navbar navbar-expand-lg navbar-light bg-dark">
            <div class="container-fluid">
                <a href='../taf.php' class="navbar-brand text-danger">TAF</a>
                <a href="https://h24code.com/donate.html" target="_blank" class="px-2 right"><button class="btn btn-secondary">Faire un don</button></a>
            </div>
        </nav>
    </header>
    <main class="container mt-5">
        <div class="row">
            <p class="col-12 fs-3 text-justify">
            <h1>Description de la table <span class="text-danger"><?= $table_name ?></span></h1>
            <ol>
                <?php
                try {
                    foreach ($description as $key => $value) {
                        echo "<li class=\"fs-4\">" . $value["Field"] . "</li>";
                    }
                } catch (\Throwable $th) {
                    $reponse["status"] = false;
                    $reponse["erreur"] = $th->getMessage();

                    echo "<li>" . $th->getMessage() . "</li>";
                }

                ?>
            </ol>
            </p>
            <h1>Action(s) possible(s) dans la table <span class="text-danger"><?= $table_name ?></span></h1>
            <?php
                table_documentation($table_name, $description);
            ?>
    </main>
</body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/ace/1.4.13/ace.js" type="text/javascript" charset="utf-8"></script>
<script>
    var globConfig = {
        selectionStyle: "text",
        readOnly: true,
        showLineNumbers: false,
        showGutter: false
    };

    var configJs = {
        mode: "ace/mode/javascript",
        theme: "ace/theme/monokai",
        ...globConfig
    };

    var configJson = {
        mode: "ace/mode/javascript",
        theme: "ace/theme/github",
        ...globConfig
    };

    var configHtml = {
        mode: "ace/mode/html",
        theme: "ace/theme/monokai",
        ...globConfig
    };

    var editor_get = ace.edit('get_exemple', configJs),
        editor_add = ace.edit('add_exemple', configJs),
        editor_edit = ace.edit('edit_exemple', configJs),
        editor_delete = ace.edit('delete_exemple', configJs),
        editor_form = ace.edit('add_form', configHtml),
        editor_form_ts = ace.edit('add_form_ts', configJs);

    var json_add = ace.edit('json_add', configJson),
        json_edit = ace.edit('json_edit', configJson),
        json_delete = ace.edit('json_delete', configJson);
</script>
</html>