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
                $compte = isset($_SESSION['compte']) ? $_SESSION['compte'] : null;
                // Si non connecté : Inscription et connexion
                if (!isset($_SESSION['id'])) {
                    echo '<li><a href="./inscription.php">Inscription</a></li>';
                    echo '<li><a href="./connexion.php">Connexion</a></li>';
                    echo '<li><a href="./admin/connexion.php">Admin</a></li>';
                    
                } else if ($compte  == 'admin') {
                    echo '<div class="dropdown">';
                        echo '<button class="dropbtn admin-links a">Listes</button>';
                        echo '<div class="dropdown-content">';
                            echo '<li><a href="admin/listepages.php">Liste des pages</a></li>';
                            echo '<li><a href="admin/listearticles.php">Liste des articles</a></li>';
                            echo '<li><a href="admin/listeutilisateurs.php">Liste des utilisateurs</a></li>';
                        echo '</div>';
                    echo '</div>';
                    echo '<li><a class="nav-link" href="admin/dashboard.php">Dashboard</a></li>';
                    echo '<li><a class="nav-link" href="admin/deconnexion.php">Déconnexion</a></li>';

                } else { // Si connecté : profil et deconnexion
                    echo '<li><a href="./profil.php">Profil</a></li>';
                    echo '<li><a href="../projet/deconnexion.php">Déconnexion</a></li>';
                }
            ?>
        </ul>
    </nav>
</header>