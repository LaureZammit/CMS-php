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
    // Récupérer les valeurs des filtres depuis le formulaire
    $categorieFilter = isset($_GET['categorie']) ? $_GET['categorie'] : null;
    $statusFilter = isset($_GET['status']) ? $_GET['status'] : null;
    
    // On récupère les données des articles
    $requeteArticle = "SELECT * FROM articles WHERE 1 = 1";
    $params = array();

    if ($categorieFilter !== null && $categorieFilter !== '') {
        $requeteArticle .= " AND categorie = :categorie";
        $params[':categorie'] = $categorieFilter;
    }

    if ($statusFilter !== null && $statusFilter !== '') {
        $requeteArticle .= " AND statut = :status";
        $params[':status'] = $statusFilter;
    }

    if ($statusFilter == '' && $categorieFilter == '') {
        $requeteArticle .= " ORDER BY date_article DESC";
    }
    // var_dump($params);

    // Exécuter la requête
    $requeteArticle = $db->prepare($requeteArticle);

    // Exécuter la requête
    $requeteArticle->execute($params); // Utilisez le tableau des paramètres que vous avez créé précédemment

    $resultArticle = $requeteArticle->fetchAll();

    // $params = array(':id' => $_SESSION['id']);
    // if ($categorieFilter !== null && $categorieFilter !== '') {
    //     $params[':categorie'] = $categorieFilter;
    // }
    // if ($statusFilter !== null && $statusFilter !== '') {
    //     $params[':status'] = $statusFilter;
    // }

    // Si on clique sur l'input Modifier on arrive sur la page href="modifyarticle.php?id=<?=$article['id_article']"
    if (isset($_POST['modifier'])) {
        header('Location: modifyarticle.php?id='.$article['id_article']);
    }

    // Si on clique sur submitnewarticle on est redirigé vers la page createarticlesphp
    if (isset($_GET['submitnewarticle'])) {
        header('Location: createarticles.php');
    }

    // Si on clique sur l'input Supprimer on arrive sur la page href="deletearticles.php?id=<?=$article['id_article']"
    if(isset($_POST['supprimer'])) {
        header('Location: deletearticle.php?id='.$article['id_article']);
    }
?>
<!DOCTYPE html>
<html lang="fr">
<head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/style.css">
        <title>Listes des articles</title>
    </head>
<body class="body-liste-article">
    <?php 
        include_once '../components/headerAdmin.php';
    ?>
    <main class="main-liste-article">
        <section>
            <h1>Liste des articles</h1>

            <form method="GET" class="filter-form">
                <label for="categorie">Trier par catégorie :</label>
                <select name="categorie" id="categorie">
                    <option value="">Toutes les catégories</option>
                    <option value="Informatique">Informatique</option>
                    <option value="Santé">Santé</option>
                    <option value="Animaux">Animaux</option>
                    <option value="FaitsDivers">Faits divers</option>
                </select>

                <label for="status">Trier par statut :</label>
                <select name="status" id="status">
                    <option value="">Tous les statuts</option>
                    <option value="Publié">Publié</option>
                    <option value="Brouillon">En brouillon</option>
                    <option value="Relecture">En attente de relecture</option>
                </select>

                <input type="submit" value="Filtrer">

                <div>
                    <input type="submit" name="submitnewarticle" value="Créer un nouvel article">
                </div>
            </form>

            <div class="article-list">
                <?php 
                    // Vérifier si des articles ont été trouvés
                    if (count($resultArticle) > 0) {
                        // Afficher les articles
                        foreach ($resultArticle as $article) {
                            ?>
                            <div class="article">
                                <!-- Balise a avec le lien vers l'article sélectionné en fonction de son id -->
                                <a href="../detailarticle.php?id_article=<?=$article['id_article']?>">
                                    <h2><?=$article['titre_article']?></h2>
                                    <img src="../uploads/articles/<?=$article['image_article']?>" alt="<?=$article['titre_article']?>" />
                                    <p><?=substr($article['contenu_article'], 0, 500) . "..."?></p>
                                    <p><span class="bolder">Date de publication :</span> <?=$article['date_article']?></p>
                                    <p><span class="bolder">Catégorie : </span><?=$article['categorie']?></p>
                                    <p><span class="bolder">Statut :</span> <?=$article['statut']?></p>
                                    <p><span class="bolder">Auteur : </span><?=$article['id_user']?></p>
                                </a>
                                <div class="div-form-modify-delete">
                                    <form action="modifyarticle.php?id=<?=$article['id_article']?>" method="post">
                                        <input type="submit" value="Modifier"/>
                                    </form>
                                    <form action="deletearticle.php?id=<?=$article['id_article']?>" method="post">
                                        <input type="submit" value="Supprimer"/>
                                    </form>
                                </div>
                            </div>
                        <?php
                        }
                    } else {
                        ?>
                        <div class="article">
                            <p>Aucun article trouvé</p>
                        </div>
                        <?php 
                    }
                        ?>
            </div>
        </section>
    </main>
</body>
</html>

<?php
    } else {
        header('Location: ../index.php');
    }
?>