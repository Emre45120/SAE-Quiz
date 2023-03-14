<?php

require("connect.php");

// Vérifier si l'utilisateur est connecté
session_start(); // démarrer la session
if (!isset($_SESSION["email"])) {
    // Rediriger l'utilisateur vers la page de connexion
    header("Location: conBD.php");
    exit();
}else if ($_SESSION["admin"] == false) {
    // Rediriger l'utilisateur vers la page de connexion
    header("Location: Accueil.php");
    exit();
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
    <div class="table-responsive my-4">
            <table class="table table-dark">
                <thead>
                    <tr class="text">
                        <th scope="col">Questionnaire</th>
                        <th scope="col">Supprimer</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    
                    // Modifier la requête SQL pour inclure le filtre sélectionné
                    $sql = "SELECT * FROM QUESTIONNAIRE";
                    
                    $result = $connexion->prepare($sql);
                    $result->execute();
                    
                    if ($result->rowCount() > 0) {
                        while ($row = $result->fetch(PDO::FETCH_ASSOC)) {
                            echo "<tr>";
                            echo "<td>" . $row["nomQ"] . "</td>";
                            echo "<td> <a href='delete.php?id=" . $row["id"] . "'>Supprimer</a></td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "0 results";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </main>
</body>
</html>
