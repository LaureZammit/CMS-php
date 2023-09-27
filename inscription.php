<?php 
session_start();

$errorMessage = "";
$isEverythingOk = true;

// Si le formulaire a été envoyé
if(isset($_POST['submit'])) {
    // On récupère les données
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email'];
    $pseudo = $_POST['pseudo'];
    $password = $_POST['password'];
    $avatarFileName = $_FILES['avatar'];

    // Si le mail n'est pas un mail valide
    if(!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $errorMessage = "L'email n'est pas valide<br />";
        $isEverythingOk = false;
    }

    // On ne fait pas l'enregistrement si le mot de passe est < à 6 caractères
    if(strlen($password) < 6) {
        $errorMessage .= "Le mot de passe doit faire au moins 6 caractères<br />";
        $isEverythingOk = false;
    }

    // Si tout est ok 
    if($isEverythingOk) {
        // On fait appel à la BDD
        require_once ('admin/connect.php');

        /// Gestion de l'avatar
        $avatarFileName = ''; // Initialisez la variable pour stocker le nom du fichier avatar

        if(isset($_FILES['avatar']) && $_FILES['avatar']['error'] === UPLOAD_ERR_OK) {
            $avatarFileName = $_FILES['avatar']['name'];
            $avatarTmpName = $_FILES['avatar']['tmp_name'];
            $avatarPath = 'uploads/' . $avatarFileName; // Spécifiez le chemin où vous souhaitez stocker l'avatar

            // Déplacez le fichier téléchargé vers le dossier d'uploads
            if(move_uploaded_file($avatarTmpName, $avatarPath)) {
                // Le fichier a été téléchargé avec succès
            } else {
                // Une erreur s'est produite lors du téléchargement du fichier
                $errorMessage .= "Une erreur s'est produite lors du téléchargement de l'avatar.<br />";
                $isEverythingOk = false;
            }
        }

        // Si le pseudo est déjà enregistré en base de données
        // Récupérer les pseudo de la base de données, pour les comparer au mot de passe entré sans prendre en compte la casse
        $requete = "SELECT pseudo_user as pseudo, mail_user as mail FROM users WHERE mail_user = :mail OR pseudo_user = :pseudo";

        // On prépare la requête
        $requete = $db->prepare($requete);

        // On injecte les valeurs
        $requete->execute(array(
            ':mail' => $email,
            // ':pseudo' => $pseudo peu importe la casse
            ':pseudo' => strtolower($pseudo)
        ));

        $data = $requete->fetch();

        // Si le pseudo existe déjà
        if($data['pseudo'] == $pseudo) {
            $errorMessage .= "Quelque chose s'est mal passé, merci d'entrer un autre pseudo pour t'inscrire.<br />";
            $isEverythingOk = false;
        }

        // Si le mail existe déjà
        if($data['mail'] == $email) {
            $errorMessage .= "Quelque chose s'est mal passé, merci d'entrer un autre mail pour t'inscrire.<br />";
            $isEverythingOk = false;
        }

        // Si on ne récupère pas de données, on peut faire l'inscription
        if(!$data) {
            // On hash le mot de passe entré
            $hash = password_hash($password, PASSWORD_DEFAULT);

            // On prépare la requête
            $requeteInscription = "INSERT INTO users (nom_user, prenom_user, mail_user, pseudo_user, password_user, avatar_user, compte_user) VALUES (:nom, :prenom, :mail, :pseudo, :pass, :avatar, :compte)";

            $compte = "membre";

            $requeteInscription = $db->prepare($requeteInscription);

            $requeteInscription->execute(array(
                'nom' => $nom,
                'prenom' => $prenom,
                'mail' => $email,
                'pseudo' => $pseudo,
                'pass' => $hash,
                'avatar' => $avatarFileName,
                'compte' => $compte
            ));

            // On redirige vers la page de connexion
            header('Location: connexion.php');
        }
    }
}

?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="css/style.css">
    <title>Page d'inscription</title>
</head>
<body>
    <?php 
        // Insertion du header.php
        include_once 'components/header.php';
    ?>
    <main>
        <section class="inscription-section">
            <h1>Page d'inscription</h1>
            <form class="inscription-form" enctype="multipart/form-data" action="#" method="post">
                <!-- Nom, Prénom, mail, pseudo, mot de passe, avatar -->
                <label for="nom">Nom</label>
                <input type="text" name="nom" id="nom" placeholder="Votre nom" required>
                <label for="prenom">Prénom</label>
                <input type="text" name="prenom" id="prenom" placeholder="Votre prénom" required>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Votre email" required>
                <label for="pseudo">Pseudo</label>
                <input type="text" name="pseudo" id="pseudo" placeholder="Votre pseudo" required>
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" placeholder="Votre mot de passe" required>
                <label for="avatar">Avatar</label>
                <input type="file" name="avatar" id="avatar">
    
                <input type="submit" name="submit" value="S'inscrire">
            </form>
            <p class="errorMessage"><?= $errorMessage?></p>
        </section>
    </main>
</body>
</html>