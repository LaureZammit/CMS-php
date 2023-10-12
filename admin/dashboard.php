<?php 
/*
    Cette page ne doit s'afficher que si l'admin est connecté

    Cette page doit permettre : 
        [ ] De créer une nouvelle page
        [x] De créer un nouvel article
        [x] De gérer les comptes utilisateurs

    Sur cette page vous devez également afficher :
        [ ] Les derniers articles (les 5 derniers)
        [ ] Les dernières pages (les 5 dernières)
        [ ] Les derniers utilisateurs (les 5 derniers)

    Vous devez avoir la possibilité de :
        [x] Afficher la liste complète des articles (c'est une page à part entière)
        [ ] Afficher la liste complète des pages (c'est une page à part entière)
        [x] Afficher la liste complète des utilisateurs (c'est une page à part entière)
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

// Si la connexion provient d'un compte_user == admin on reste sur la page, sinon on est redirigé vers la page d'accueil
if($result['compte_user'] !== 'admin') {
    header('Location: ../index.php');
} else {

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../css/style.css">
    <title>Dashboard</title>
</head>
<body class="admin-body-dashboard">
    <?php 
        include_once '../components/headerAdmin.php';
    ?>
    <main class="container-admin-dashboard">
        <section class="admin-dashboard-section">
            <h1>Bienvenue <?=$result['prenom_user']?></h1>
            <h2>Que souhaitez-vous faire ?</h2>

            <div class="dashboard-container">
                <div class="dashboard-item">
                    <h3><a href="listepages.php">Liste des pages</a></h3>
                    <p>Vous pouvez créer, modifier, supprimer des pages.</p>
                </div>

                <div class="dashboard-item">
                    <h3><a href="listearticles.php">Liste des articles</a></h3>
                    <p>Vous pouvez créer, modifier, supprimer des articles.</p>
                </div>

                <div class="dashboard-item">
                    <h3><a href="listeutilisateurs.php">Liste des comptes utilisateurs</a></h3>
                    <p>Vous pouvez gérer les comptes utilisateurs.</p>
                </div>
        </section>

        <section class="admin-dashboard-section">
            <h2>Outils</h2>

            <div class="dashboard-container">
                <div class="dashboard-item">
                    <h3><a href="createpages.php">Créer une nouvelle page</a></h3>
                    <p>Vous pouvez créer des pages.</p>
                </div>
                <div class="dashboard-item">
                    <h3><a href="createarticles.php">Créer un nouvel article</a></h3>
                    <p>Vous pouvez créer des articles.</p>
                </div>
                <div class="dashboard-item">
                    
                </div>
        </section>

            <!-- Afficher les 5 derniers articles dans une colonne -->
            <!-- Afficher les 5 deernières pages dans une colonne -->
            <!-- Afficher les 5 derniers utilisateurs dans une colonne -->
        <section class="admin-dashboard-section">
            <h2>Les Derniers éléments</h2>

            <div class="dashboard-container">
                <div class="dashboard-item">
                    <h3><a href="listearticles.php">Derniers Articles</a></h3>
                    <ul>
                        <?php
                        // Requête pour récupérer les 5 derniers articles
                        $requeteDerniersArticles = "SELECT * FROM articles ORDER BY date_article DESC LIMIT 5";
                        $stmtDerniersArticles = $db->prepare($requeteDerniersArticles);
                        $stmtDerniersArticles->execute();

                        while ($article = $stmtDerniersArticles->fetch()) {
                            echo "<li>" . $article['titre_article'] . "</li>";
                        }
                        ?>
                    </ul>
                </div>

                <div class="dashboard-item">
                    <h3>Dernières Pages</h3>
                    <ul>
                        <?php
                        // Requête pour récupérer les 5 dernières pages
                        $requeteDernieresPages = "SELECT * FROM pages ORDER BY date_page DESC LIMIT 5";
                        $stmtDernieresPages = $db->prepare($requeteDernieresPages);
                        $stmtDernieresPages->execute();

                        while ($page = $stmtDernieresPages->fetch()) {
                            echo "<li>" . $page['titre_page'] . "</li>";
                        }
                        ?>
                    </ul>
                </div>

                <div class="dashboard-item">
                    <h3><a href="listeutilisateurs.php">Derniers Utilisateurs</a></h3>
                    <ul>
                        <?php
                        // Requête pour récupérer les 5 derniers utilisateurs
                        $requeteDerniersUtilisateurs = "SELECT * FROM users ORDER BY id_user DESC LIMIT 5";
                        $stmtDerniersUtilisateurs = $db->prepare($requeteDerniersUtilisateurs);
                        $stmtDerniersUtilisateurs->execute();

                        while ($utilisateur = $stmtDerniersUtilisateurs->fetch()) {
                            echo "<li>" . $utilisateur['prenom_user'] . " " . $utilisateur['nom_user'] . "</li>";
                        }
                        ?>
                    </ul>
                </div>
            </div>
        </section>

    </main>

    <?php 
        include_once '../components/footer.php';
    ?>
</body>
</html>

<?php 
}
?>