<?php 
session_start();

// Pas d'erreur
$errorMessage = '';

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Page de connexion</title>
</head>
<body>
    <?php 
        // Insertion du header.php
        include_once 'components/header.php';
    ?>
    <main>
        <h1>Page de connexion</h1>
        <form action="profil.php" method="post">
            <label for="email">Email</label>
            <input type="email" name="email" id="email" placeholder="Votre email" required>
            <label for="password">Mot de passe</label>
            <input type="password" name="password" id="password" placeholder="Votre mot de passe" required>
            <input type="submit" value="Se connecter">
        </form>
    </main>
</body>
</html>