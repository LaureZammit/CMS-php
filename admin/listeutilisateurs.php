<?php 
session_start();

// La page ne s'affiche que si la connexion provient d'un compte_user == admin, sinon afficher la page d'accueil
if(!isset($_SESSION['id']) || $_SESSION['token'] != password_verify("Super connexion", $_SESSION['token'])) {
    header('Location: ../index.php');
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
    <title>Liste des utilisateurs</title>
</head>
<body class="admin-user-body">
    <?php 
        include_once '../components/headerAdmin.php';
    ?>

    <main class="admin-user-main">
        <section>
            <h1>Liste des utilisateurs</h1>

            <!-- Pour chaque utilisateur, afficher un tableau avec nom, prenom, email, pseudo, avatar et compte -->
            <table class="admin-user-table">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Pseudo</th>
                        <th>Email</th>
                        <th>Avatar</th>
                        <th>Compte</th>
                    </tr>
                </thead>
                <tbody>
                <?php 
                    // On récupère les données des utilisateurs
                    $requete = "SELECT * FROM users";

                    // On prépare la requête
                    $requete = $db->prepare($requete);

                    // On injecte les valeurs
                    $requete->execute();

                    // On récupère les données
                    $result = $requete->fetchAll();

                    foreach($result as $user) {
                        // Déterminez la couleur pastel en fonction du niveau de compte de l'utilisateur
                        $backgroundColor = '';
                        if ($user['compte_user'] == 'admin') {
                            $backgroundColor = '#ffe6e6'; // Couleur pastel pour admin
                        } elseif ($user['compte_user'] == 'modérateur') {
                            $backgroundColor = '#e6ffe6'; // Couleur pastel pour modérateur
                        } elseif ($user['compte_user'] == 'membre') {
                            $backgroundColor = '#e6e6ff'; // Couleur pastel pour membre
                        }
                    
                        // Affichez la ligne avec le style généré
                        echo '<tr style="background-color: ' . $backgroundColor . ';">';
                            echo '<td>'.$user['nom_user'].'</td>';
                            echo '<td>'.$user['prenom_user'].'</td>';
                            echo '<td>'.$user['pseudo_user'].'</td>';
                            echo '<td>'.$user['mail_user'].'</td>';
                            echo '<td>'.$user['avatar_user'].'</td>';
                            echo '<td>'.$user['compte_user'].'</td>';
                            echo '<td><a href="modifierutilisateur.php?id='.$user['id_user'].'">Modifier</a></td>';
                            echo '<td><a href="supprimerutilisateur.php?id='.$user['id_user'].'">Supprimer</a></td>';
                        echo '</tr>';
                    }
                ?>
                </tbody>
            </table>
        </section>
    </main>
</body>
</html>