<?php 

// Gérer la déconnexion avec une fin de session
session_destroy();

// Renvoyer sur la page d'accueil
header('Location: index.php');
?>