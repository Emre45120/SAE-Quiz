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
foreach ($reponses_utilisateur as $id_question => $reponses) {
    // Récupérer la question liée à la réponse
    $sql = "SELECT question FROM QUESTION WHERE id_question = :id_question";
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(':id_question', $id_question);
    $stmt->execute();
    $result = $stmt->fetch();
    $question = $result["question"];

    // Récupérer les réponses correctes pour cette question
    $sql = "SELECT reponse FROM REPONSE WHERE id_question = :id_question AND est_correcte = true";
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(':id_question', $id_question);
    $stmt->execute();
    $result = $stmt->fetchAll();
    $reponses_correctes = array_column($result, "reponse");
    echo "<p>Les réponses correctes sont : " . implode(",", $reponses_correctes) . "</p>";

    // Vérifier si les réponses de l'utilisateur sont correctes
    $est_correct = true;
    foreach ($reponses_correctes as $reponse_correcte) {
        if (is_array($reponses)) {
            $reponses = implode(",", $reponses);
        }
        echo "<p>Reponse correcte : " . $reponse_correcte . "</p>";
        echo "<p>Reponse utilisateur : " . $reponses . "</p>";
        if (!in_array($reponse_correcte, $reponses_utilisateur)) {
            $est_correct = false;
            break;
        }
    }

    

    // Afficher la question, les réponses de l'utilisateur et si la réponse est correcte ou non
    echo "<p>Question : " . $question . "</p>";
    echo "<p>Réponse de l'utilisateur : " . $reponses . "</p>";
    if ($est_correct) {
        echo "<p>La réponse est correcte.</p>";
    } else {
        echo "<p>La réponse est incorrecte.</p>";
    }
}

?>
</body>
</html>


