<!doctype html>
<html>
<head>
<title>
Connexion à MySQL avec PDO
</title>
<meta charset="utf-8">
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<h1>Authentification</h1>
<form method="post">
    Email: <input type="text" name="email"><br>
    Mot de passe: <input type="password" name="password"><br>
    <input type="submit" value="Se connecter">
</form>
</body>
</html>
<?php
require("connect.php");

if (isset($_POST["email"]) && isset($_POST["password"])) {
    $email = $_POST["email"];
    $password = $_POST["password"];

    $sql = "SELECT * FROM UTILISATEUR WHERE email=:email AND mot_de_passe=:password";
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(':email', $email);
    $stmt->bindParam(':password', $password);

    $stmt->execute();
    $result = $stmt->fetch();

    if ($result) {
        session_start();
        $_SESSION["email"] = $email;
        header("Location: Accueil.php");
        echo "Authentification réussi !";
    } else {
        echo "Email ou mot de passe incorrect";
    }
}
?>


