<!doctype html>
<html>
<head>
<title>
Connexion à MySQL avec PDO
</title>
<meta charset="utf-8">
<link rel="stylesheet" href="css/style.css">
</head>
<body style= "background-color: grey;">
<div class="container" style= " background-color: white; width: 50%; margin: auto; padding: 20px; margin-top: 100px; border-radius: 10px;">
    <h1>Inscription</h1>
    <form method="post">
        Email: <input type="text" required name="email"><br>
        Mot de passe: <input type="password" required name="password"><br>
        Nom: <input type="text" required name="nom"><br>
        <input type="submit" value="S'inscrire">
    </form>
</div>
</body>
</html>
<?php
require("connect.php");

if (isset($_POST["email"]) && isset($_POST["password"]) && isset($_POST["nom"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];
    $nom = $_POST["nom"];

    $sql = "SELECT * FROM UTILISATEUR WHERE email=:email";
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->execute();
    $result = $stmt->fetch();
    if ($result) {
        echo "Email déjà utilisé";
        exit();
    } else {
        echo "Email ou mot de passe incorrect";
    }

    $sql = "INSERT INTO UTILISATEUR (email, mot_de_passe, nom, est_admin) VALUES (:email, :password, :nom, 0)";
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);
    $stmt->bindParam(':nom', $nom);

    $stmt->execute();

    header("Location: conBD.php");

}



?>


