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
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Quiz</title>
	<link rel="stylesheet" type="text/css" href="css/styleAccueil.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
    <link rel="stylesheet" href="css/reset.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/header-6.css" /></head>
<body>
<header class="site-header">
      <div class="site-header__top">
        <div class="wrapper site-header__wrapper top">
          <nav class="nav">
            <button class="nav__toggle" aria-expanded="false" type="button">
              menu
            </button>
            <ul class="nav__wrapper">
              <li class="nav__item"><a href="Accueil.php">Accueil</a></li>
              <li class="nav__item"><a href="questionnaire.php">Questionnaire</a></li>
              <li class="nav__item"><a href="score.php">Score</a></li>
              <?php if (isset($_SESSION["admin"]) && $_SESSION["admin"] == 1) { ?>
                    <li class="nav__item"><a href="admin.php">Admin</a></li>
                    <li class="nav__item"><a href="Createquestionaire.php">Créer un questionnaire</a></li>
                    <li class="nav__item"><a href="JSON.php">JSON</a></li>
                <?php } ?>
                
            </ul>
          </nav>
            <?php if (!$is_authenticated) { ?>
                <button class="button" onclick="window.location.href='conBD.php'">Se connecter</button>
            <?php } ?>
            <?php if ($is_authenticated) { ?>
                <button class="button" onclick="window.location.href='deconnecter.php'">Se déconnecter</button>
            <?php } ?>
        </div>
      </div>
      <div class="site-header__bottom">
        <div class="wrapper site-header__wrapper bottom">
          <a href="#" class="brand">SAE QUIZ</a>
        </div>
      </div>
    </header>
	<div class="container">
			
	<?php

	// Récupérer l'ID du questionnaire à partir de l'URL
	if (isset($_GET["id"])) {
		$id_questionnaire = $_GET["id"];
	} else {
		// Si l'ID n'est pas fourni dans l'URL, afficher un message d'erreur
		echo "<p>Erreur : l'ID du questionnaire n'a pas été fourni.</p>";
		exit();
	}

	$sql = "SELECT * FROM QUESTIONNAIRE where id= $id_questionnaire";
	$stmt = $connexion->prepare($sql);
	$stmt->execute();
	$questionnaires = $stmt->fetchAll();

	// Afficher le questionnaire ( son nom )
	foreach ($questionnaires as $questionnaire) {
		echo "<h1 style='font-size: 32px'>" . $questionnaire["nomQ"] . "</h1>";
	}

	// Récupérer les questions du questionnaire
	$sql = "SELECT id_question, question, type FROM QUESTION WHERE id_questionnaire = $id_questionnaire";
	$stmt = $connexion->prepare($sql);
	$stmt->execute();
	$questions = $stmt->fetchAll();

	echo '<form method="post" action="resultats.php">';
	echo '<input type="hidden" name="id_questionnaire" value="' . $id_questionnaire . '"><br><br>';
	
	foreach ($questions as $question) {
		echo "<p>" . $question["question"] . "</p>";
		$idq = $question['id_question'];
		$sql = "SELECT id, reponse FROM REPONSE WHERE id_question = $idq";
		$stmt = $connexion->prepare($sql);
		$stmt->execute();
		$reponses = $stmt->fetchAll();
		if ($question["type"] == "choix_multiple") {
			echo '<div class="checkbox-container">';
		}
		
		foreach ($reponses as $reponse) {
			if ($question["type"] == "choix_multiple") {
				echo '<input type="checkbox"  name="reponse[' . $idq . '][]" value="' . $reponse["reponse"] . '>';
				echo '<label for="' . $reponse["reponse"] . '">' . $reponse["reponse"] . '</label>';
				echo '<br>';
	
			} elseif ($question["type"] == "choix_unique") {
				echo '<input type="radio" id = "ra" required name="reponse[' . $idq . ']" value="' . $reponse["reponse"] . '">';
				echo '<label for="' . $reponse["reponse"] . '">' . $reponse["reponse"] . '</label><br><br>';
	
			} elseif ($question["type"] == "libre") {
				echo '<textarea name="reponse[' . $idq . ']" id = "text" required placeholder="Entrez votre réponse ici"></textarea><br><br>';
	
			} else if ($question["type"] == "slider"){
				echo '<input type="range" name="reponse[' . $idq . ']" min="0" max="100" value="'. $reponse["reponse"] . '" id="slider" min="0" max="100" step="1">';
				echo '<p>Valeur actuelle : <span id="valeur">' . $reponse["reponse"] . '</span></p><br>';
	
			}else {
				// Type de question non pris en charge
				echo "<p>Erreur : type de question non pris en charge</p>";
				exit();
			}
		}
		if ($question["type"] == "choix_multiple") {
			echo '</div>';
		}
		echo '<br>';
	}
	echo '<input type="submit" name="submit" value="Soumettre">';
	echo '</form>';
	  
	?>    
	</div>
</body>
<script>
		var slider = document.getElementById("slider");
		var valeur = document.getElementById("valeur");

		// Affiche la valeur actuelle du slider lorsqu'il est modifié
		slider.addEventListener("input", function() {
			valeur.innerHTML = slider.value;
		});
	</script>
</html>