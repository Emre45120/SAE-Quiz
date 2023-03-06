<!DOCTYPE html>
<html>
<head>
	<title>Quiz</title>
</head>
<body>
	<h1>Quiz</h1>
	<?php
	require("connect.php");
	echo "<h2>Questionnaire</h2>";


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
   }
   

	// Récupérer l'ID du questionnaire à partir de l'URL
	if (isset($_GET["id"])) {
      $id_questionnaire = $_GET["id"];
  } else {
      // Si l'ID n'est pas fourni dans l'URL, afficher un message d'erreur
      echo "<p>Erreur : l'ID du questionnaire n'a pas été fourni.</p>";
      exit();
  }

  

	// Récupérer les questions  du questionnaire
	$sql = "SELECT question ,type FROM QUESTION WHERE id_questionnaire = $id_questionnaire";
	$stmt = $connexion->prepare($sql);
	$stmt->execute();
	$questions = $stmt->fetchAll();



	foreach ($questions as $question) {
		echo "<p>" . $question["question"] . "</p>";
		$idq = $question['id_question'];
		$sql = "SELECT reponse FROM REPONSE WHERE id_question = $idq";
		$stmt = $connexion->prepare($sql);
		$stmt->execute();
		$reponses = $stmt->fetchAll();




		foreach ($reponses as $reponse) {
			echo "<p>" . $reponse["reponse"] . "</p>";
			
		}



	}

	
	// Afficher les questions et réponses du questionnaire
	// foreach ($questionReponse as $question) {
    // 	echo "<p>" . $question["question"] . "</p>";

    // 	// Vérifier le type de question et générer le formulaire approprié
		
    // 	if ($question["type"] == "choix_multiple") {
    // 	    echo "<input type='checkbox' name='reponse[]' value='1'>" . $question["reponse"] . "<br>";
    // 	    echo "<input type='checkbox' name='reponse[]' value='2'>" . $question["reponse"] . "<br>";
    // 	    echo "<input type='checkbox' name='reponse[]' value='3'>" . $question["reponse"] . "<br>";
    // 	} elseif ($question["type"] == "choix_unique") {
    // 	    echo "<input type='radio' name='reponse' value='1'>" . $question["reponse"] . "<br>";
    // 	    echo "<input type='radio' name='reponse' value='2'>" . $question["reponse"] . "<br>";
    // 	    echo "<input type='radio' name='reponse' value='3'>" . $question["reponse"] . "<br>";
    // 	} elseif ($question["type"] == "libre") {
    // 	    echo "<input type='text' name='reponse'><br>";
    // 	} else {
    // 	    // Type de question non pris en charge
    // 	    echo "<p>Erreur : type de question non pris en charge.</p>";
    // 	    exit();
    // 	}
	// }	



	// Fermer la connexion à la base de données
	mysqli_close($conn);
	?>



	<!-- Formulaire pour soumettre les réponses -->
	<form method="post" action="resultats.php">
		<input type="hidden" name="id_questionnaire" value="<?php echo $id_questionnaire; ?>">
		<input type="submit" value="Soumettre">
	</form>
</body>
</html>
