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
    <h1>Authentification</h1>
    <form method="post">
        Email: <input type="text" name="email"><br>
        Mot de passe: <input type="password" name="password"><br>
        <input type="submit" value="Se connecter">
    </form>
</div>
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
        if ($result["est_admin"] == 1) {
            $_SESSION["admin"] = true;
        }
        header("Location: Accueil.php");
        echo "Authentification réussi !";
    } else {
        echo "Email ou mot de passe incorrect";
    }
}
?>


