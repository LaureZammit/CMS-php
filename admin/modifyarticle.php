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
    // On récupère les données des articles
    $requeteModifyArticle = "SELECT * FROM articles WHERE id_article = :id";

    // Exécuter la requête
    $requeteModifyArticle = $db->prepare($requeteModifyArticle);

    // Exécuter la requête
    $requeteModifyArticle->execute(array(
        ':id' => $_GET['id']
    ));

    $article = $requeteModifyArticle->fetch();

    // Fonction de modification de la base de données au clic sur modifier l'article puis redirection vers la page listearticles.php
    if (isset($_POST['submitupdate'])) {
        // Le formulaire de modification a été soumis
        // Récupérez les données soumises
        $newTitle = $_POST['new_title'];
        $newImage = $_FILES['new_image'];
        $newContent = $_POST['new_content'];
        $newDate = $_POST['new_date'];
        $newCategory = $_POST['new_category'];
        $newStatus = $_POST['new_status'];
        $newAuthor = $_POST['new_author'];
        $id = $_POST['id'];

        // Récupérer les données pour l'image uniquement si une nouvelle image a été soumise
        if(isset($_FILES['new_image']) && $_FILES['new_image']['error'] === UPLOAD_ERR_OK) {
            $newImage = $_FILES['new_image']['name'];
            $imageTmpName = $_FILES['new_image']['tmp_name'];
            $imagePath = 'uploads/' . $newImage;
    
            // Déplacez le fichier téléchargé vers le dossier d'uploads
            if(move_uploaded_file($imageTmpName, $imagePath)) {
                // Le fichier a été téléchargé avec succès
                $requeteImageUpdate = "UPDATE articles SET image_article = :new_image WHERE id_article = :id";
            } else {
                // Une erreur s'est produite lors du téléchargement du fichier
                $errorMessage .= "Une erreur s'est produite lors du téléchargement de l'image.<br />";
                $isEverythingOk = false;
            }
        } else {
            // Une erreur s'est produite lors du téléchargement du fichier
            $errorMessage .= "Une erreur s'est produite lors du téléchargement de l'image.<br />";
        }
        // Effectuez la mise à jour des données dans la base de données
        $requeteUpdate = "UPDATE articles SET titre_article = :new_title, contenu_article = :new_content, date_article = :new_date, categorie = :new_category, statut = :new_status, id_user = :new_author WHERE id_article = :id";
        
        // Préparez la requête
        $stmtUpdate = $db->prepare($requeteUpdate);

        // Injectez les valeurs
        $stmtUpdate->execute(array(
            'new_title' => $newTitle,
            'new_content' => $newContent,
            'new_date' => $newDate,
            'new_category' => $newCategory,
            'new_status' => $newStatus,
            'new_author' => $newAuthor,
            'id' => $id
        ));
        
        header('Location: listearticles.php');
    }

    // Si on clique sur l'input Supprimer on arrive sur la page href="deletearticles.php?id=<?=$article['id_article']"
    if (isset($_POST['supprimer'])) {
        header('Location: deletearticle.php?id='.$article['id_article']);
    }

    // Si on clique sur l'input name=returnHome on retourne sur la liste des article
    if (isset($_POST['returnHome'])) {
        header('Location: listearticles.php');
    }
} else {
    header('Location: ../index.php');
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/style.css">
    <title>Modifier un article</title>
</head>
    <body>
        <?php 
            include_once '../components/headerAdmin.php';
        ?>
        <main>
            <section class="modify-article-container">
                <h1>Modification de l'article : </h1>
                <h2><?=$article['titre_article']?></h2>
                <form class="create-articles-form" action="#" enctype="multipart/form-data" method="POST">

                    <!-- Formlaire de modification pré-remplie avec les données de la base de données -->
                    <input type="hidden" name="id" value="<?=$_GET['id']?>">

                    <label for="title">Titre de l'article</label>

                    <input type="text" name="new_title" id="title" value="<?=$article['titre_article']?>" required>

                    <label for="image">Image</label>
                    <img class="modify-img-article" src="../uploads/articles/<?=$article['image_article']?>" alt="<?=$article['titre_article']?>">
                    <input type="file" name="new_image" id="image" />

                    <label for="content">Contenu de l'article</label>
                    <!-- Textarea préremplie avec les données $article['content_article'] -->
                    <textarea name="new_content" id="content" cols="30" rows="10" required><?=$article['contenu_article']?></textarea>

                    <label for="date">Date de publication</label>
                    <input type="date" name="new_date" id="date" value="<?=$article['date_article']?>" required>

                    <label for="category">Catégorie de l'article</label>
                    <!-- Select qui sélectionne par défaut la catégorie enregistrée de l'article -->
                    <select name="new_category" id="category" required>
                        <option value="<?=$article['categorie']?>"><?=$article['categorie']?></option>
                        <option value="Informatique">Informatique</option>
                        <option value="Santé">Santé</option>
                        <option value="Animaux">Animaux</option>
                        <option value="FaitsDivers">Faits divers</option>
                    </select>

                    <label for="status">Statut de publication</label>
                    <!-- Select qui sélectionne par défaut le statut enregistré de l'article -->
                    <select name="new_status" id="status" required>
                        <option value="<?=$article['statut']?>"><?=$article['statut']?></option>
                        <option value="Brouillon">En brouillon</option>
                        <option value="Relecture">En attente de relecture</option>
                        <option value="Publié">Publié</option>
                    </select>

                    <label for="author">Auteur de l'article</label>
                    <input type="text" name="new_author" id="author" value ="<?=$article['id_user']?>" required>

                    <p class="errorMessage"><?=$errorMessage?></p>
                    <br>

                    <input type="submit" value="Modifier" name="submitupdate">
                    <input type="submit" value="Supprimer" name="supprimer">
                    <input type="submit" value="Annuler - Retourner sur la liste des articles" name="returnHome">
                </form>

            </section>
        </main>
    </body>
</html>