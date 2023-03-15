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
    <link rel="stylesheet" href="css/reset.min.css" />
    <link rel="stylesheet" href="css/style.css" />
    <link rel="stylesheet" href="css/header-6.css" />

</head>
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

    <main>
        <div class="presentation">
            <h1>Bienvenue sur notre site de quizz</h1>
            <p>Notre site web offre une variété de quizz amusants pour tester vos connaissances dans différents domaines. Inscrivez-vous dès maintenant pour commencer à jouer!</p>
            <button onclick="window.location.href='inscriptionBD.php'" >Inscrivez-vous maintenant</button>
        </div>
    </main>
</body>
</html>
