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
// Si la connexion provient d'un compte_user == admin on reste sur la page, sinon on est redirigé vers la page d'accueil
if($result['compte_user'] !== 'admin') {
    header('Location: ../index.php');
} else {
    // Fonction de MODIFICATION de compte
    if (isset($_POST['submitupdate'])) {
        // Le formulaire de modification a été soumis
        // Récupérez les données soumises
        $newNom = $_POST['new_nom'];
        $newPrenom = $_POST['new_prenom'];
        $newPseudo = $_POST['new_pseudo'];
        $newEmail = $_POST['new_email'];
        $newAvatar = $_FILES['new_avatar'];
        $newCompte = $_POST['new_compte'];
        $id = $_POST['id'];

        // Si le pseudo existe déjà
        if($newPseudo == $result['pseudo_user']) {
            $errorMessageModif .= "Quelque chose s'est mal passé, merci d'entrer un autre pseudo.<br />";
        }

        // Si le mail existe déjà
        if($newEmail == $result['mail_user']) {
            $errorMessageModif .= "Quelque chose s'est mal passé, merci d'entrer un autre mail pour t'inscrire.<br />";
            $isEverythingOk = false;
        }

        if(isset($_FILES['new_avatar']) && $_FILES['new_avatar']['error'] === UPLOAD_ERR_OK) {
            $newAvatar = $_FILES['new_avatar']['name'];
            $avatarTmpName = $_FILES['new_avatar']['tmp_name'];
            $avatarPath = '../uploads/' . $newAvatar; // Spécifiez le chemin où vous souhaitez stocker l'avatar

            // Déplacez le fichier téléchargé vers le dossier d'uploads
            if(move_uploaded_file($avatarTmpName, $avatarPath)) {
                // Le fichier a été téléchargé avec succès
            } else {
                // Une erreur s'est produite lors du téléchargement du fichier
                $errorMessage .= "Une erreur s'est produite lors du téléchargement de l'avatar.<br />";
                $isEverythingOk = false;
            }
        }

        // Effectuez la mise à jour des données dans la base de données
        $requeteUpdate = "UPDATE users SET nom_user = :new_nom, prenom_user = :new_prenom, pseudo_user = :new_pseudo, mail_user = :new_email, avatar_user = :new_avatar, compte_user = :new_compte WHERE id_user = :id";
        
        // Préparez la requête
        $stmtUpdate = $db->prepare($requeteUpdate);

        // Injectez les valeurs
        $stmtUpdate->execute(array(
            'new_nom' => $newNom,
            'new_prenom' => $newPrenom,
            'new_pseudo' => $newPseudo,
            'new_email' => $newEmail,
            'new_avatar' => $newAvatar,
            'new_compte' => $newCompte,
            'id' => $id
        ));
    }

    // Fonction de SUPPRESSION de compte
    if (isset($_POST['delete_id'])) {
        $deleteId = $_POST['delete_id'];
        
        // Effectuez la suppression de l'utilisateur de la base de données
        $requeteDelete = "DELETE FROM users WHERE id_user = :id";
        
        // Préparez la requête de suppression
        $stmtDelete = $db->prepare($requeteDelete);

        // Injectez la valeur de l'ID à supprimer
        $stmtDelete->execute(array(':id' => $deleteId));

        // Redirigez l'utilisateur vers la page actuelle
        header('Location: listeutilisateurs.php');
        exit;
    }

    $errorMessage = "";
    $errorMessageModif = "";
    $isEverythingOk = true;

    // Formulaire ENREGISTREMENT d'un nouveau membre
    // Si le formulaire a été envoyé
    if(isset($_POST['submitnew'])) {
        // On récupère les données
        $nom = $_POST['nom'];
        $prenom = $_POST['prenom'];
        $email = $_POST['email'];
        $pseudo = $_POST['pseudo'];
        $password = $_POST['password'];
        $avatar = $_POST['avatar'];

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
                    'avatar' => $avatar,
                    'compte' => $compte
                ));
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
                        // Déterminez la couleur en fonction du niveau de compte de l'utilisateur
                        $backgroundColor = '';
                        if ($user['compte_user'] == 'admin') {
                            $backgroundColor = '#ffe6e6';
                        } elseif ($user['compte_user'] == 'modérateur') {
                            $backgroundColor = '#e6ffe6';
                        } elseif ($user['compte_user'] == 'membre') {
                            $backgroundColor = '#e6e6ff';
                        }
                    ?>
                        <!-- Affichez la ligne avec le style généré -->
                        <tr style="background-color: <?=$backgroundColor?>;">
                            <td><?=$user['nom_user']?></td>
                            <td><?=$user['prenom_user']?></td>
                            <td><?=$user['pseudo_user']?></td>
                            <td><?=$user['mail_user']?></td>
                            <td>
                                <img class="admin-img-profil" src="../uploads/<?=$user['avatar_user']?>" alt="Photo de profile de <?=$user['pseudo_user']?>">
                            </td>
                            <td><?=$user['compte_user']?></td>
                            <td>
                                <button class="modify-button button" href="modifierutilisateur.php?id=<?=$user['id_user']?>">Modifier</button>
                            </td>
                            <td>
                                <form method="post">
                                    <input type="hidden" name="delete_id" value="<?=$user['id_user']?>">
                                    <button type="submit" class="delete-button button">Supprimer</button>
                                </form>
                            </td>
                        </tr>


                        <tr class="edit-row" style="display: none;">
                            <td colspan="6">
                                <form method="post" enctype="multipart/form-data" class="edit-form" action="#">
                                    <input type="hidden" name="id" value="<?=$user['id_user']?>">
                                    <input type="text" name="new_nom" value="<?=$user['nom_user']?>"><br>
                                    <input type="text" name="new_prenom" value="<?=$user['prenom_user']?>"><br>
                                    <input type="text" name="new_pseudo" value="<?=$user['pseudo_user']?>"><br>
                                    <input type="text" name="new_email" value="<?=$user['mail_user']?>"><br>
                                    <input type="file" name="new_avatar" value="<?=$user['avatar_user']?>"><br>
                                    <select class="compteselect" name="new_compte" id="compte" value="<?=$user['compte_user']?>">
                                        <option value="membre">Membre</option>
                                        <option value="modérateur">Modérateur</option>
                                        <option value="admin">Admin</option>
                                    </select>
                                    <input type="submit" name="submitupdate" value="Enregistrer">
                                    <button class="cancel-button">Annuler</button>
                                </form>
                            </td>
                        </tr>
                    <?php }
                    ?>
                </tbody>
            </table>
            <p class="errormessage"><?=$errorMessageModif?></p>
        </section>

        <section class="inscription-section">
            <h1>Inscrire un nouvel utilisateur</h1>
            <form class="inscription-form" enctype="multipart/form-data" action="#" method="post">
                <!-- Nom, Prénom, mail, pseudo, mot de passe, avatar, compte -->
                <label for="nom">Nom</label>
                <input type="text" name="nom" id="nom" placeholder="Nom" required>
                <label for="prenom">Prénom</label>
                <input type="text" name="prenom" id="prenom" placeholder="Prénom" required>
                <label for="email">Email</label>
                <input type="email" name="email" id="email" placeholder="Email" required>
                <label for="pseudo">Pseudo</label>
                <input type="text" name="pseudo" id="pseudo" placeholder="Pseudo" required>
                <label for="password">Mot de passe</label>
                <input type="password" name="password" id="password" placeholder="Mot de passe" required>
                <label for="avatar">Avatar</label>
                <input type="file" name="avatar" id="avatar">
                <label for="compte">Niveau de compte</label>
                <select class="compteselect" name="compte" id="compte">
                    <option value="membre">Membre</option>
                    <option value="modérateur">Modérateur</option>
                    <option value="admin">Admin</option>
                </select>
    
                <input type="submit" name="submitnew" value="S'inscrire">
            </form>
            <p class="errorMessage"><?= $errorMessage?></p>
        </section>
    </main>
</body>
</html>

<script>
// JavaScript pour gérer l'affichage/masquage du formulaire de modification
document.addEventListener("DOMContentLoaded", function () {
    const editButtons = document.querySelectorAll(".modify-button");
    const editRows = document.querySelectorAll(".edit-row");

    editButtons.forEach((button, index) => {
        button.addEventListener("click", function () {
            editRows[index].style.display = "table-row";
        });
    });

    const cancelButtons = document.querySelectorAll(".cancel-button");

    cancelButtons.forEach((button, index) => {
        button.addEventListener("click", function (e) {
            e.preventDefault();
            editRows[index].style.display = "none";
        });
    });
});

// Fonction pour confirmer la suppression
function confirmDelete(userId) {
    if (confirm("Êtes-vous sûr de vouloir supprimer cet utilisateur ?")) {
        // L'utilisateur a confirmé la suppression, soumettez le formulaire de suppression
        document.querySelector("form input[name='delete_id']").value = userId;
        document.querySelector("form").submit();
    }
}
</script>