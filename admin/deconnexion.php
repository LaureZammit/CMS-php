<?php 

session_start();

unset($_SESSION['id']);
unset($_SESSION['token']);
unset($_SESSION);

// Gérer la déconnexion avec une fin de session
session_destroy();

// Renvoyer sur la page de connexion Admin
header('Location: connexion.php');
exit;
?>