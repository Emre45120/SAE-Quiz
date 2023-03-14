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

// Récupérer le nom de l'utilisateur connecté
$sql = "SELECT nom FROM UTILISATEUR WHERE email = :email";
$stmt = $connexion->prepare($sql);
$stmt->bindParam(':email', $email);
$stmt->execute();
$user = $stmt->fetch();
$nom = $user['nom'];

$sql = "SELECT * FROM QUESTIONNAIRE";
$stmt = $connexion->prepare($sql);
$stmt->execute();
$questionnaires = $stmt->fetchAll();

?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Page d'accueil</title>
    <link rel="stylesheet" type="text/css" href="css/styleAccueil.css">
    <link rel="stylesheet" type="text/css" href="css/bootstrap.min.css">
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
                <li><a href="deconnecter.php">Se déconnecter</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Bienvenue sur notre site de quizz <?php echo $nom; ?></h1>
    </main>
</body>
</html>
