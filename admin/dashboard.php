<?php 
/*
    Cette page ne doit s'afficher que si l'admin est connecté

    Cette page doit permettre : 
        - De créer une nouvelle page
        - De créer un nouvel article
        - De gérer les comptes utilisateurs

    Sur cette page vous devez également afficher :
        - Les derniers articles (les 5 derniers)
        - Les dernières pages (les 5 dernières)
        - Les derniers utilisateurs (les 5 derniers)

    Vous devez avoir la possibilité de :
        - Afficher la liste complète des articles (c'est une page à part entière)
        - Afficher la liste complète des pages (c'est une page à part entière)
        - Afficher la liste complète des utilisateurs (c'est une page à part entière)
*/
session_start();

// La page ne s'affiche que si la connexion provient d'un compte_user == admin

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

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Dashboard</title>
</head>
<body>
    <?php 
        include_once '../components/headerAdmin.php';
    ?>
    <main>
        <section>
            <h1>Bienvenue <?=$result['prenom_user']?></h1>
            <h2>Que souhaitez-vous faire ?</h2>

            <div class="dashboard-container">
                <div class="dashboard-item">
                    <h3><a href="listepages.php">Créer une nouvelle page</a></h3>
                    <p>Vous pouvez créer une nouvelle page.</p>
                </div>

                <div class="dashboard-item">
                    <h3><a href="listearticles.php">Gérer les articles</a></h3>
                    <p>Vous pouvez créer un nouvel article.</p>
                </div>
                
                <div class="dashboard-item">
                    <h3><a href="listeutilisateurs.php">Gérer les comptes utilisateurs</a></h3>
                    <p>Vous pouvez gérer les comptes utilisateurs.</p>
                </div>
        </section>

    </main>
</body>
</html>