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
// supprimer un questionnaire
if (isset($_GET["id"])) {
    $id_questionnaire = $_GET["id"];
} else {
    // Si l'ID n'est pas fourni dans l'URL, afficher un message d'erreur
    echo "<p>Erreur : l'ID du questionnaire n'a pas été fourni.</p>";
    exit();
}
echo "".$id_questionnaire."";

$sql = "SELECT * FROM QUESTION WHERE id_questionnaire=:id_questionnaire";
$stmt = $connexion->prepare($sql);
$stmt->bindParam(':id_questionnaire', $id_questionnaire);
$stmt->execute();
$questions = $stmt->fetchAll();
foreach ($questions as $question) {
    $id_question = $question["id_question"];
    $sql = "DELETE FROM REPONSE WHERE id_question=:id_question";
    $stmt = $connexion->prepare($sql);
    $stmt->bindParam(':id_question', $id_question);
    $stmt->execute();
}

$sql = "DELETE FROM QUESTION WHERE id_questionnaire=:id_questionnaire";
$stmt = $connexion->prepare($sql);
$stmt->bindParam(':id_questionnaire', $id_questionnaire);
$stmt->execute();

$sql = "DELETE FROM SCORE WHERE id_questionnaire=:id_questionnaire";
$stmt = $connexion->prepare($sql);
$stmt->bindParam(':id_questionnaire', $id_questionnaire);
$stmt->execute();


$sql = "DELETE FROM QUESTIONNAIRE WHERE id = ".$id_questionnaire."";
$stmt = $connexion->prepare($sql);
$stmt->execute();


header("Location: admin.php");

?>

