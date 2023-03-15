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
    $sql = "SELECT nom,id FROM UTILISATEUR WHERE email=:email";
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $result = $stmt->fetch();
    $nom_utilisateur = $result["nom"];
    $id_utilisateur = $result["id"];
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
</head>
<body>
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
    <?php

// Vérifier si l'ID du questionnaire est fourni
if (!isset($_POST["id_questionnaire"])) {
    echo "<p>Erreur : l'ID du questionnaire n'a pas été fourni.</p>";
    exit();
}

// Récupérer l'ID du questionnaire
$id_questionnaire = $_POST["id_questionnaire"];
echo "<p>Questionnaire : " . $id_questionnaire . "</p>";

// Récupérer les réponses de l'utilisateur
// Récupérer les réponses de l'utilisateur
$reponses_utilisateur = $_POST["reponse"];
// Vérifier les réponses de l'utilisateur
$score = 0;
foreach ($reponses_utilisateur as $id_question => $reponses) {
    // Récupérer la question liée à la réponse
    $sql = "SELECT question ,type FROM QUESTION WHERE id_question = :id_question";
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(':id_question', $id_question);
    $stmt->execute();
    $result2 = $stmt->fetch();
    $question = $result2["question"];
    $type_question = $result2["type"];


    // Récupérer les réponses correctes pour cette question
    $sql = "SELECT reponse FROM REPONSE WHERE id_question = :id_question AND est_correcte = true";
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(':id_question', $id_question);
    $stmt->execute();
    $result3 = $stmt->fetchAll();
    $reponses_correctes = array_column($result3, "reponse");

    // Vérifier si les réponses de l'utilisateur sont correctes
    $est_correct = true;

    

    
    if ($type_question == "choix_unique") {
        if (!in_array($reponses, $reponses_correctes)) {
            $est_correct = false;
            $score += -50;
        }
        else {
            $score += 100;
        }

    
    } else if ($type_question == "choix_multiple") {
        // si la question est de type choix multiple
        if (count ($reponses) != count ($reponses_correctes)) {
            $est_correct = false;
            $score += -50;
        } else {
            foreach ($reponses as $reponse) {
                if (!in_array($reponse, $reponses_correctes)) {
                    $est_correct = false;
                    $score += -50;    
                    break;
                }
            }
            if ($est_correct) {
                $score += 100;
            }
        }

    }
    else if ($type_question == "libre") {
        // si la question est de type texte
        if (!in_array($reponses, $reponses_correctes)) {
            $est_correct = false;
            $score += -50;
        }
        else {
            $score += 100;
        }
    }
    else if ($type_question == "slider"){
        // si la question est de type slider
        if (!in_array($reponses, $reponses_correctes)) {
            $est_correct = false;
            $score += -50;
        }
        else {
            $score += 100;
        }
    }
    
    // Afficher la question, les réponses de l'utilisateur et si la réponse est correcte ou non
    if ($est_correct) {
        echo "<p class='correct'>Bravo, la réponse à la question \"" . $question . "\" est correcte.<brLa bonne réponse était bien " . implode(", ", $reponses_correctes). "</p>";
      } else {
        echo "<p class='incorrect'>Désolé, la réponse à la question \"" . $question . "\" est incorrecte.<br>La bonne réponse était " . implode(", ", $reponses_correctes). "</p>";
    }
      

}

?>
    <?php
    // afficher le score de l'utilisateur
    echo "<p>Votre score est de : " . $score . " !</p>";
    // Insérer le score dans la table SCORE
    $sql = "INSERT INTO SCORE (id_utilisateur,id_questionnaire,score) VALUES (:id_utilisateur, :id_questionnaire,:score)";
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(':id_utilisateur', $id_utilisateur);
    $stmt->bindParam(':id_questionnaire', $id_questionnaire);
    $stmt->bindParam(':score', $score);
    $stmt->execute();
    ?>
    <!-- afficher les boutons de navigation -->
    <div class="navigation-buttons">
        <button onclick="window.location.href='questionnaire.php'">Recommencer un quiz</button>
        <button onclick="window.location.href='score.php'">Voir les scores</button>
    </div>

</body>
</html>


