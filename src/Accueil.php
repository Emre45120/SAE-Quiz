<?php

require("connect.php");

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
</head>
<body>
    <header>
        <nav>
            <ul>
                <li><a href="#">Accueil</a></li>
                <li><a href="#">Connexion</a></li>
                <li><a href="#">Quizz</a></li>
                <li><a href="#">Score</a></li>
            </ul>
        </nav>
    </header>
    <main>
        <h1>Bienvenue sur notre site de quizz</h1>
        <p>Vous pouvez répondre à des quizz et tester vos connaissances !</p>
        <ul>
        <?php foreach ($questionnaires as $questionnaire) { ?>
            <li><a href="#"><?php echo $questionnaire['nom']; ?></a></li>
        <?php } ?>
        </ul>
    </main>
</body>
</html>
