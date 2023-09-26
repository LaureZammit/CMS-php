<?php 
@session_start();

?>

<header>
    <a href="../projet/index.php">
        <img class="header-logo" src="uploads/logo-ligne.png" alt="Logo du site">
    </a>
    <nav>
        <ul>
            <li><a href="../index.php">Accueil</a></li>
            <li><a href="../contact.php">Contact</a></li>
            <?php
                $compte = isset($_SESSION['compte']) ? $_SESSION['compte'] : null;
                
                // Si non connecté : Inscription et connexion
                if (isset($_SESSION['compte']) || $compte != "admin") {
                    echo '<li><a href="../connexion.php">Connexion</a></li>';
                    echo '<li><a href="connexion.php">Admin</a></li>';
                } 
                
                if (isset($_SESSION['compte']) && $compte  == 'admin') { // Si connecté : profil et deconnexion
                    echo '<li><a href="listepages.php">Liste des pages</a></li>';
                    echo '<li><a href="listearticles.php">Liste des articles</a></li>';
                    echo '<li><a href="listeutilisateurs.php">Liste des utilisateurs</a></li>';
                    echo '<li><a href="dashboard.php">Dashboard</a></li>';
                    echo '<li><a href="deconnexion.php">Déconnexion</a></li>';
                }
            ?>
        </ul>
    </nav>
</header>