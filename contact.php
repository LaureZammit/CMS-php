<?php 
session_start();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Formulaire de contact</title>
</head>
<body>
    <?php 
        include_once 'components/header.php';
    ?>
    <h1 class="h1-contact">Contactez-nous</h1>
    
    <form class="form-contact" method="post" action="#">
        <label for="nom">Nom :</label>
        <input type="text" name="nom" id="nom" required><br><br>
        
        <label for="email">Email :</label>
        <input type="email" name="email" id="email" required><br><br>
        
        <label for="message">Message :</label><br>
        <textarea name="message" id="message" rows="5" required></textarea><br><br>
        
        <input type="submit" value="Envoyer">
    </form>
</body>
</html>