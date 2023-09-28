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
    // Si on submit le formulaire :
    if(isset($_POST['title']) && isset($_FILES['image']) && isset($_POST['content']) && isset($_POST['date']) && isset($_POST['status'])) {
        // On vérifie que les champs ne sont pas vides
        if(!empty($_POST['title']) && !empty($_FILES['image']) && !empty($_POST['content']) && !empty($_POST['date']) && !empty($_POST['category']) && !empty($_POST['status'])) {
            die('ok');
            // On vérifie que l'image est bien une image
            $image = $_FILES['image'];
            $imageType = $image['type'];
            $imageSize = $image['size'];
            $imageTmpName = $image['tmp_name'];
            $imageError = $image['error'];

            // On vérifie que l'image est bien une image
            if($imageError == 0) {
                // On vérifie que l'image est bien au format jpeg ou png
                if($imageType == 'image/jpeg' || $imageType == 'image/png') {
                    // On vérifie que l'image ne dépasse pas 3Mo
                    if($imageSize <= 3000000) {
                        // On déplace l'image dans le dossier images
                        move_uploaded_file($imageTmpName, '../uploads/pages/' . $image['name']);
                        // On enregistre l'ensemble des données envoyées par le formulaire sur la base de données articles
                        $requeteArticle = "INSERT INTO pages (titre_page, image_page, contenu_page, date_page, statut_page) VALUES (:title, :image, :content, :date, :status)";
                        // On prépare la requête
                        $requeteArticle = $db->prepare($requeteArticle);
                        // On injecte les valeurs
                        $requeteArticle->execute(array(
                            'title' => $_POST['title'],
                            'image' => $image['name'],
                            'content' => $_POST['content'],
                            'date' => $_POST['date'],
                            'status' => $_POST['status']
                        ));
                    } else {
                        $errorMessage = 'L\'image est trop lourde !';
                    }
                } else {
                    $errorMessage = 'L\'image n\'est pas au bon format !';
                }
            } else {
                $errorMessage = 'Il y a eu une erreur lors de l\'upload de l\'image !';
            }
        } else {
            $errorMessage = 'Veuillez remplir tous les champs !';
        }
    }
    
} else {
    header('Location: ../index.php');
}

?>

<!-- Formulaire de création d'articles -->
<!DOCTYPE html>
<html lang="fr">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <link rel="stylesheet" href="../css/style.css">
        <title>Création de pages</title>
    </head>
    <body>
        <?php 
            include_once '../components/headerAdmin.php';
        ?>
        <main>
            <section>
                <h1>Création de pages</h1>
                <form class="create-articles-form" action="#" enctype="multipart/form-data" method="POST">

                    <label for="title">Titre de la page</label>
                    <input type="text" name="title" id="title" required>

                    <label for="image">Image</label>
                    <input type="file" name="image" id="image" required>

                    <label for="content">Contenu de la page</label>
                    <textarea name="content" id="content" cols="30" rows="10" required></textarea>

                    <label for="date">Date de publication</label>
                    <input type="date" name="date" id="date" required>

                    <label for="status">Statut de publication</label>
                    <select name="status" id="status" required>
                        <option value="Brouillon">En brouillon</option>
                        <option value="Relecture">En attente de relecture</option>
                        <option value="Publié">Publié</option>
                    </select>

                    <input type="submit" value="Créer la page">
                </form>

                <p><?=$errorMessage?></p>
            </section>
    </body>
</html>