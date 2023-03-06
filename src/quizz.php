<!DOCTYPE html>
<html>
<head>
	<title>Quiz</title>
</head>
<body>
	<?php
	require("connect.php");

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
   }
   

	// Récupérer l'ID du questionnaire à partir de l'URL
	if (isset($_GET["id"])) {
      $id_questionnaire = $_GET["id"];
  } else {
      // Si l'ID n'est pas fourni dans l'URL, afficher un message d'erreur
      echo "<p>Erreur : l'ID du questionnaire n'a pas été fourni.</p>";
      exit();
  }
  

	// Récupérer les questions et réponses du questionnaire
	$sql = "SELECT q.id_question, q.question, r.id, r.reponse, r.est_correcte FROM question q INNER JOIN reponse r ON q.id_question=r.id_question WHERE q.id_questionnaire=$id_questionnaire ORDER BY q.id_question, r.id";
	$result = mysqli_query($conn, $sql);

	// Afficher les questions et réponses
	$current_question_id = null;
	while ($row = mysqli_fetch_assoc($result)) {
	    if ($row["id_question"] != $current_question_id) {
	        // Nouvelle question
	        echo "<h2>" . $row["question"] . "</h2>";
	        $current_question_id = $row["id_question"];
	    }

	    // Afficher la réponse
	    echo "<p><input type='radio' name='" . $row["id_question"] . "' value='" . $row["id"] . "'> " . $row["reponse"] . "</p>";
	}

	// Fermer la connexion
	mysqli_close($conn);
	?>

	<!-- Formulaire pour soumettre les réponses -->
	<form method="post" action="resultats.php">
		<input type="hidden" name="id_questionnaire" value="<?php echo $id_questionnaire; ?>">
		<input type="submit" value="Soumettre">
	</form>
</body>
</html>
