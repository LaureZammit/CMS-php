<?php 
$session = isset($_SESSION["token"]) ? $_SESSION["token"] : false;
?>


<header>
    <a href="../projet/index.php">
        <img class="header-logo" src="./uploads/logo-ligne.png" alt="Logo du site">
    </a>
    <nav>
        <ul>
            <li><a href="./index.php">Accueil</a></li>
            <li><a href="./pages.php">Articles</a></li>
            <li><a href="./contact.php">Contact</a></li>
            <?php
                // Si non connecté : Inscription et connexion
                if (!isset($_SESSION['id'])) {
                    echo '<li><a href="./inscription.php">Inscription</a></li>';
                    echo '<li><a href="./connexion.php">Connexion</a></li>';
                    echo '<li><a href="./admin/connexion.php">Admin</a></li>';
                } else { // Si connecté : profil et deconnexion
                    echo '<li><a href="./profil.php">Profil</a></li>';
                    echo '<li><a href="../projet/deconnexion.php">Déconnexion</a></li>';
                }
            ?>
        </ul>
    </nav>
</header>