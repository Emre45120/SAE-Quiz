<!doctype html>
<html>
<head>
<title>
Connexion à MySQL avec PDO
</title>
<meta charset="utf-8">
</head>
<body>
<h1>
Interrogation de la table CARNET avec PDO
</h1>

<?php
require("connect.php");
$query = "SELECT * FROM QUESTION";
$result = $connexion->query($query);
foreach($result as $row) {
    echo $row['id'] . " " . $row['id_question'] . "<br>" . $row["id_questionnaire"]." ";
}
?>
</body>
</html>