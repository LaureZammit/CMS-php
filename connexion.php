<?php 
session_start();

// Pas d'erreur
$errorMessage = '';

$email = isset($_POST['email']) ? $_POST['email'] : null;
$password = isset($_POST['password']) ? $_POST['password'] : null;

// Test si le formulaire est envoyé
if(isset($_POST['email']) && isset($_POST['password'])) {
    // Le formulaire à bien été envoyé

    // On vérifie si les champs ne sont pas vides
    if(!empty($_POST['email']) && !empty($_POST['password'])) {

        // Si le mdp renseigné est inférieur à 6 caractères :
        if(strlen($_POST['password']) < 6) {
            $errorMessage = "Le mot de passe doit faire au moins 6 caractères<br/>";
        }

        if(strlen($_POST['password']) >= 6) {
            // On fait appel à la BDD
            require_once ('admin/connect.php');

            // On récupère les données de l'utilisateur
            $requete = "SELECT password_user as pass, id_user as id FROM users WHERE mail_user = :email";

            // On prépare la requête
            $requete = $db->prepare($requete);

            // On injecte les valeurs
            $requete->execute(array(
                ':email' => $_POST['email']
            ));

            $result = $requete->fetch();

            // Si on obtient pas de résultat : message d'erreur
            if(!$result) {
                $errorMessage = "Email ou mot de passe incorrect.<br/>";
            }

            // On teste si le mdp correspond
            if($result) {
                // Si mdp faux
                if(!password_verify($_POST['password'], $result['pass'])) {
                    $errorMessage = "Email ou mot de passe incorrect.<br/>";
                }

                if(password_verify($_POST['password'], $result['pass'])) {
                    // Si on arrive ici : le mdp correspond

                    $token = password_hash("Super connexion", PASSWORD_DEFAULT);

                    // On connecte l'utilisateur avec les sessions
                    $_SESSION['id'] = $result['id'];
                    $_SESSION['token'] = $token;

                    header('Location: profil.php');
                }
            }
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
    <title>Page de connexion</title>
</head>
<body>
    <?php 
        // Insertion du header.php
        include_once 'components/header.php';
    ?>
    <main>
        <section class="connexion-membre">
            <h1>Page de connexion</h1>
            <form action="#" method="post">
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Votre email" required>
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" placeholder="Votre mot de passe" required>
                <input class="submit-connexion" name="submit" type="submit" value="Se connecter">
            </form>
            <p class="errorMessage"><?= $errorMessage?></p>
        </section>
    </main>
</body>
</html>