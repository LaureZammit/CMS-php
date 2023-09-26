<?php 
@session_start();
?>

<header class="admin-header">
    <a href="../projet/index.php">
        <img class="admin-logo" src="uploads/logo-ligne.png" alt="Logo du site">
    </a>
    <nav class="admin-links">
        <ul>
            <li><a href="../index.php">Accueil</a></li>
            <li><a href="../contact.php">Contact</a></li>
            <?php
                $compte = isset($_SESSION['compte']) ? $_SESSION['compte'] : null;
                
                // Si non connecté : Inscription et connexion
                if ($compte != "admin") {
                    echo '<li><a class="nav-link" href="../connexion.php">Connexion</a></li>';
                    echo '<li><a class="nav-link" href="connexion.php">Admin</a></li>';
                } 
                
                if ($compte  == 'admin') { // Si connecté : profil et deconnexion
                    echo '<div class="dropdown">';
                        echo '<button class="dropbtn admin-links a">Listes</button>';
                        echo '<div class="dropdown-content">';
                            echo '<li><a href="listepages.php">Liste des pages</a></li>';
                            echo '<li><a href="listearticles.php">Liste des articles</a></li>';
                            echo '<li><a href="listeutilisateurs.php">Liste des utilisateurs</a></li>';
                        echo '</div>';
                    echo '</div>';
                    echo '<li><a class="nav-link" href="dashboard.php">Dashboard</a></li>';
                    echo '<li><a class="nav-link" href="deconnexion.php">Déconnexion</a></li>';
                }
            ?>
        </ul>
    </nav>
</header>