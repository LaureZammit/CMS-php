<?php
session_start();

$token = password_verify("Super connexion", $_SESSION['token']);
if (!isset($_SESSION['id']) || !$token) {
    header('Location: ../connexion.php');
}

// Inclure le fichier de connexion à la base de données
require_once('admin/connect.php');

// Récupérer l'ID de l'article depuis la requête GET
if (isset($_GET['id_article'])) {
    $id_article = $_GET['id_article'];

    // Préparer et exécuter la requête pour récupérer l'article
    $requeteArticle = "SELECT * FROM articles WHERE id_article = :id_article";
    $requeteArticle = $db->prepare($requeteArticle);
    $requeteArticle->execute([':id_article' => $id_article]);
    $article = $requeteArticle->fetch();

    if ($article) {
        // Affichage de l'article
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Article : <?= $article['titre_article'] ?></title>
</head>
<body class="body-detail-article">
    <?php
        include_once 'components/header.php';
    ?>
    <main class="main-detail-article">
        <article class="article-detail">
            <h1 class="article-title"><?= $article['titre_article'] ?></h1>
            <img src="uploads/articles/<?=$article['image_article'] ?>" alt="<?= $article['titre_article'] ?>" class="article-image" />
            <p class="article-content"><?= $article['contenu_article'] ?></p>
            <p class="article-info"><span class="label">Date de publication :</span> <span class="value"><?= $article['date_article'] ?></span></p>
            <p class="article-info"><span class="label">Catégorie :</span> <span class="value"><?= $article['categorie'] ?></span></p>
            <p class="article-info"><span class="label">Statut :</span> <span class="value"><?= $article['statut'] ?></span></p>
            <p class="article-info"><span class="label">Auteur :</span> <span class="value"><?= $article['id_user'] ?></span></p>
        </article>
    </main>
    
    <?php
        include_once 'components/footer.php';
    ?>
</body>
</html>
<?php
    } else {
        echo "L'article demandé n'existe pas.";
    }
} else {
    echo "L'ID de l'article n'a pas été spécifié.";
}
?>