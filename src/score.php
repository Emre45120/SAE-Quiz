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

// Récupérer les scores depuis la base de données
$sql = "SELECT u.nom, q.nomQ, s.score
FROM SCORE s
INNER JOIN UTILISATEUR u ON s.id_utilisateur = u.id
INNER JOIN QUESTIONNAIRE q ON s.id_questionnaire = q.id;
";
$result = $connexion->prepare($sql);
$result->execute();



?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Score</title>
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
                <?php if (!$is_authenticated) { ?>
                <li><a href="conBD.php">Se connecter</a></li>
                <?php } ?>
                <?php if ($is_authenticated) { ?>
                <li><a href="deconnecter.php">Se déconnecter</a></li>
                <?php } ?>
            </ul>
        </nav>
    </header>
    <div class="container-fluid pt-4 px-4 ">
    <div class="bg-light text-center rounded p-4">
        <form method="post">
            <label for="sort">Trier par:</label>
            <select name="sort" id="sort">
                <option value="nom ASC">Nom (A-Z)</option>
                <option value="nom DESC">Nom (Z-A)</option>
                <option value="nomQ ASC">Questionnaire (A-Z)</option>
                <option value="nomQ DESC">Questionnaire (Z-A)</option>
                <option value="score ASC">Score (Croissant)</option>
                <option value="score DESC">Score (Décroissant)</option>
            </select>
            <button type="submit" class="btn btn-primary">Filtrer</button>
        </form>
        <div class="table-responsive my-4">
            <table class="table table-dark">
                <thead>
                    <tr class="text">
                        <th scope="col">Nom Joueur</th>
                        <th scope="col">Questionnaire</th>
                        <th scope="col">Score</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    // Récupérer le filtre sélectionné par l'utilisateur
                    $sort = isset($_POST['sort']) ? $_POST['sort'] : 'score DESC';
                    
                    // Modifier la requête SQL pour inclure le filtre sélectionné
                    $sql = "SELECT u.nom, q.nomQ, s.score
                            FROM SCORE s
                            INNER JOIN UTILISATEUR u ON s.id_utilisateur = u.id
                            INNER JOIN QUESTIONNAIRE q ON s.id_questionnaire = q.id
                            ORDER BY $sort";
                    
                    $result = $connexion->prepare($sql);
                    $result->execute();
                    
                    if ($result->rowCount() > 0) {
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . $row["nom"] . "</td>";
                            echo "<td>" . $row["nomQ"] . "</td>";
                            echo "<td>" . $row["score"] . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "0 results";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

