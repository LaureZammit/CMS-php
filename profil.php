<?php 
session_start();

// On vérifie si l'utilisateur possède des infos de session
$session = isset($_SESSION["token"]) ? $_SESSION["token"] : false;

$errorMessage = null;
$prenom = null;
$nom = null;
$email = null;
$pseudo = null;
$password = null;
$avatar = null;
$compte = null;

if($session) {
    $token = $session;
    $isTokenCorrect = password_verify("Super connexion", $token);

    // On vérifie si le token est correct
    if($isTokenCorrect) {
        
        // Mon token est correct je fais appel à ma base de données pour récupérer le mail
        $id = isset($_COOKIE['id']) ? $_COOKIE['id'] : null;

        // On fait appel à la BDD
        require_once ('admin/connect.php');

        // On récupère les données de l'utilisateur
        $requete = "SELECT * FROM users WHERE id_user = :id";

        // On prépare la requête
        $requete = $db->prepare($requete);

        // On injecte les valeurs
        $requete->execute(array(
            ':id' => $_SESSION['id']
        ));

        $result = $requete->fetch();

        // On récupère les données de l'utilisateur
        $prenom = $result['prenom_user'];
        $nom = $result['nom_user'];
        $email = $result['mail_user'];
        $pseudo = $result['pseudo_user'];
        $password = $result['password_user'];
        $avatar = $result['avatar_user'];
        $compte = $result['compte_user'];

        if($result) {
            // Si on arrive ici : l'utilisateur est connecté
            $errorMessage = "Bienvenue " . $prenom . " " . $nom . "<br/>";
        }

        if(!$result) {
            // Si on arrive ici : l'utilisateur n'est pas connecté
            header('Location: connexion.php');
        }
    }
} else {
    header('Location: connexion.php');
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
<meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Profil - <?=$prenom?></title>
</head>
<body>
    <?php 
        // Insertion du header.php
        include_once 'components/header.php';
    ?>

    <main>
        <section>
            <h1><?=$errorMessage?></h1>
    
            <p>Votre profil</p>
            <ul>
                <li>Prénom : <?=$prenom?></li>
                <li>Nom : <?=$nom?></li>
                <li>Email : <?=$email?></li>
                <li>Pseudo : <?=$pseudo?></li>
                <li>Compte : <?=$compte?></li>
                <li>Avatar : <?=$avatar?></li>
            </ul>
            <input type="submit" name="updateavatar" value="Modifier ma photo de profil">
            <input type="submit" name="update" value="Modifier mes renseignements">
            <input type="submit" name="delete" value="Supprimer mon compte">
        </section>
    </main>
</body>
</html>