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
    echo "Vous n'êtes pas connecté";
}

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
<body style="text-align: center;">
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
        <h1>Bienvenue sur notre site de quizz</h1>
        <p>Vous pouvez répondre à des quizz et tester vos connaissances !</p>
        <div class="questionnaires">
            <ul>
                <?php foreach ($questionnaires as $questionnaire) { ?>
                <li><a href="quizz.php?id=<?php echo $questionnaire['id']; ?>"><?php echo $questionnaire['nomQ']; ?></a></li>
                <?php } ?>
            </ul>
        </div>

    </main>
</body>
</html>
