<?php
session_start(); // démarrer la session
session_destroy(); // détruire la session
header("Location: Accueil.php"); // rediriger l'utilisateur vers la page d'accueil
exit; // arrêter le script
?>
