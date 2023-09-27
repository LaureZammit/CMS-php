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
$compteprofile = null;

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
            'id' => $_SESSION['id']
        ));

        $result = $requete->fetch();

        // On récupère les données de l'utilisateur
        $prenom = $result['prenom_user'];
        $nom = $result['nom_user'];
        $email = $result['mail_user'];
        $pseudo = $result['pseudo_user'];
        $password = $result['password_user'];
        $compteprofile = $result['compte_user'];
        $avatar = $result['avatar_user'];

        if($result) {
            // Si on arrive ici : l'utilisateur est connecté
            $messageBienvenue = "Bienvenue " . $prenom . " " . $nom . "<br/>";
        }

        if(!$result) {
            // Si on arrive ici : l'utilisateur n'est pas connecté
            header('Location: connexion.php');
        }

        if (isset($_FILES['new_avatar']) && $_FILES['new_avatar']['error'] === UPLOAD_ERR_OK) {
            // Spécifiez le chemin où vous souhaitez stocker l'avatar téléchargé
            $uploadDirectory = 'uploads/';

            // Obtenez le nom du fichier téléchargé
            $avatarFileName  = $_FILES['new_avatar']['name'];

            // Déplacez le fichier téléchargé vers le dossier d'uploads
            $avatarPath = $uploadDirectory . $avatarFileName ;

            if (move_uploaded_file($_FILES['new_avatar']['tmp_name'], $avatarPath)) {
                try {
                    $requete = "UPDATE users SET avatar_user = :avatar WHERE id_user = :id";
    
                    // On prépare la requête
                    $requete = $db->prepare($requete);
    
                    // On injecte les valeurs
                    $requete->execute(array(
                        ':avatar' => $avatarFileName,
                        ':id' => $_SESSION['id']
                    ));
    
                    header('Location: profil.php');
                    exit;
                } catch (PDOException $e) {
                    echo "Erreur : " . $e->getMessage();
                }
            } else {
                echo "Une erreur s'est produite lors du téléchargement de l'avatar.";
            }
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
        <section class="profile-section">
            <h1 class="message-bienvenue"><?=$messageBienvenue?></h1>
    
            <h2>Votre profil</h2>

            <button id="showAvatarForm">Modifier ma photo de profil</button>
            <div id="avatarFormContainer" style="display: none;">
                <form class="profile-form" enctype="multipart/form-data" action="profil.php" method="post">
                    <label for="new_avatar">Nouvel avatar :</label>
                    <input type="file" name="new_avatar" id="new_avatar">
                    <input class="profile-buttons" type="submit" name="update_avatar" value="Modifier ma photo de profil">
                </form>
            </div>

            <div class="profile-info">
                <ul>
                    <li>Prénom : <?=$prenom?></li>
                    <li>Nom : <?=$nom?></li>
                    <li>Email : <?=$email?></li>
                    <li>Pseudo : <?=$pseudo?></li>
                    <li>Compte : <?=$compteprofile?></li>
                    <li>Avatar : </li>
                    <li><img src="uploads/<?=$avatar?>" alt="Photo de profile"></li>
                </ul>
            </div>
            <input class="profile-buttons" type="submit" name="update" value="Modifier mes renseignements">
            <input class="profile-buttons" type="submit" name="deleteprofile" value="Supprimer mon compte">
        </section>
    </main>
</body>
</html>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const showAvatarFormButton = document.getElementById('showAvatarForm');
    const avatarFormContainer = document.getElementById('avatarFormContainer');

    showAvatarFormButton.addEventListener('click', function() {
        if (avatarFormContainer.style.display === 'none' || avatarFormContainer.style.display === '') {
            avatarFormContainer.style.display = 'block';
        } else {
            avatarFormContainer.style.display = 'none';
        }
    });
});
</script>