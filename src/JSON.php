<?php

require("connect.php");

session_start(); // démarrer la session
if (isset($_SESSION["email"])) {
    // l'utilisateur est connecté
    $is_authenticated = true;
    $email = $_SESSION["email"];

} else {
    // l'utilisateur n'est pas connecté
    $is_authenticated = false;
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>JSON</title>
    <link rel="stylesheet" type="text/css" href="css/styleAccueil.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
</head>
<header>
        <nav>
        <ul>
        <li><a href="Accueil.php">Accueil</a></li>
                <li><a href="questionnaire.php">Questionnaire</a></li>
                <li><a href="score.php">Score</a></li>
                <?php if (isset($_SESSION["admin"]) && $_SESSION["admin"] == 1) { ?>
                    <li><a href="admin.php">Admin</a></li>
                    <li><a href="Createquestionaire.php">Créer un questionnaire</a></li>
                <?php } ?>
                <?php if (!$is_authenticated) { ?>
                <li><a href="conBD.php">Se connecter</a></li>
                <?php } ?>
                <?php if ($is_authenticated) { ?>
                <li><a href="deconnecter.php">Se déconnecter</a></li>
                <?php } ?>
            </ul>
        </nav>
    </header>
<body>
	<form method="post" enctype="multipart/form-data" action = "ExtraireImporter.php" target="_self">
		<label for="json_file">Choisissez un fichier JSON à charger :</label>
		<input type="file" name="json_file" id="json_file">
		<br><br>
		<input type="submit" name="importer" value="Charger un fichier JSON" target="_self">
        <input type = "submit" name = "exporter" value = "Exporter un fichier JSON">
        
	</form>

</body>
</html>
