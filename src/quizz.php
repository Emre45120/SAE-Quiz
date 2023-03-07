<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quiz</title>
    <link rel="stylesheet" type="text/css" href="css/styleAccueil.css">
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="Accueil.php">Accueil</a></li>
                <li><a href="questionnaire.php">Questionnaire</a></li>
                <li><a href="score.php">Score</a></li>
                <?php if (!$is_authenticated) { ?>
                <li><a href="conBD.php">Se connecter</a></li>
                <?php } ?>
                <?php if ($is_authenticated) { ?>
                <li><a href="deconnecter.php">Se déconnecter</a></li>
                <?php } ?>
            </ul>
        </nav>
    </header>
	<?php
	// Connexion à la base de données
	require("connect.php");

	// Vérifier si l'utilisateur est connecté
	session_start(); // démarrer la session
	if (isset($_SESSION["email"])) {
		// l'utilisateur est connecté
		$is_authenticated = true;
		// récupérer le nom de l'utilisateur connecté
		$email = $_SESSION["email"];
		$sql = "SELECT nom FROM UTILISATEUR WHERE email=:email";
		$stmt = $connexion->prepare($sql);
		$stmt->bindParam(':email', $email);
		$stmt->execute();
		$result = $stmt->fetch();
		$nom_utilisateur = $result["nom"];
	} else {
		// l'utilisateur n'est pas connecté
		$is_authenticated = false;
		echo "Vous devez être connecté pour accéder à ce questionnaire.";
		exit();
	}

	// Récupérer l'ID du questionnaire à partir de l'URL
	if (isset($_GET["id"])) {
		$id_questionnaire = $_GET["id"];
	} else {
		// Si l'ID n'est pas fourni dans l'URL, afficher un message d'erreur
		echo "<p>Erreur : l'ID du questionnaire n'a pas été fourni.</p>";
		exit();
	}

	// Récupérer les questions du questionnaire
	$sql = "SELECT id_question, question, type FROM QUESTION WHERE id_questionnaire = $id_questionnaire";
	$stmt = $connexion->prepare($sql);
	$stmt->execute();
	$questions = $stmt->fetchAll();

	echo '<form method="post" action="resultats.php">';
	echo '<input type="hidden" name="id_questionnaire" value="' . $id_questionnaire . '">';

	foreach ($questions as $question) {
		echo "<p>" . $question["question"] . "</p>";
		$idq = $question['id_question'];
		$sql = "SELECT id, reponse FROM REPONSE WHERE id_question = $idq";
		$stmt = $connexion->prepare($sql);
		$stmt->execute();
		$reponses = $stmt->fetchAll();
		foreach ($reponses as $reponse) {
			if ($question["type"] == "choix_multiple") {
				echo '<input type="checkbox" name="reponse[' . $idq . '][]" value="' . $reponse["reponse"] . '">';
				echo '<label for="' . $reponse["reponse"] . '">' . $reponse["reponse"] . '</label>';
				echo '<br>';
			} elseif ($question["type"] == "choix_unique") {
				echo '<input type="radio" name="reponse[' . $idq . ']" value="' . $reponse["reponse"] . '">';
				echo '<label for="' . $reponse["reponse"] . '">' . $reponse["reponse"] . '</label>';
				echo '<br>';
			} elseif ($question["type"] == "libre") {
				echo '<textarea name="reponse[' . $idq . ']"></textarea>';
			} else {
				// Type de question non pris en charge
				echo "<p>Erreur : type de question non pris en charge</p>";
                exit();
            }
        }
    }
    echo '<br><input type="submit" name="submit" value="Soumettre">';
    echo '</form>';   ?>        
</body>
</html>