<?php 

session_start();

$token = password_verify("Super connexion", $_SESSION['token']);
if(!isset($_SESSION['id']) || !$token) {
    header('Location: ../connexion.php');
}

// On fait appel à la BDD
require_once ('connect.php');

// On récupère les données de l'utilisateur
$requete = "SELECT * FROM users WHERE id_user = :id";

// On prépare la requête
$requete = $db->prepare($requete);

// On injecte les valeurs
$requete->execute(array(
    ':id' => $_SESSION['id']
));

$result = $requete->fetch();

$errorMessage = '';

// Si la connexion provient d'un compte_user == admin || compte_user == modérateur : on reste sur la page, sinon on est redirigé vers la page d'accueil
if($result['compte_user'] == 'admin' || $result['compte_user'] == 'modérateur') {
    // Suppression des données dans la base de données articles pour l'id inscrit dans l'URL
    $requeteDeleteArticle = "DELETE FROM articles WHERE id_article = :id";

    // Exécuter la requête
    $requeteDeleteArticle = $db->prepare($requeteDeleteArticle);

    // Exécuter la requête
    $requeteDeleteArticle->execute(array(
        ':id' => $_GET['id']
    ));

    header('Location: listearticles.php');
} else {
    header('Location: ../index.php');
}


?>