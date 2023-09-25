<?php 
session_start();

// 
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Page d'inscription</title>
</head>
<body>
    <?php 
        // Insertion du header.php
        include_once 'components/header.php';
    ?>
    <main>
        <section>
            <h1>Page d'inscription</h1>
            <form  action="profil.php" method="post">
    
                <!-- Nom, Prénom, mail, pseudo, mot de passe, avatar -->
                <label for="lastname">Nom</label>
                <input type="text" name="lastname" id="lastname" placeholder="Votre nom" required>
                <label for="firstname">Prénom</label>
                <input type="text" name="firstname" id="firstname" placeholder="Votre prénom" required>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Votre email" required>
                <label for="pseudo">Pseudo</label>
                <input type="text" name="pseudo" id="pseudo" placeholder="Votre pseudo" required>
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" placeholder="Votre mot de passe" required>
                <label for="avatar">Avatar</label>
                <input type="file" name="avatar" id="avatar" required>
    
                <input type="submit" value="S'inscrire">
            </form>
        </section>
    </main>
</body>
</html>