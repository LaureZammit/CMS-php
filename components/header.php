<?php 
@session_start();

?>


<header>
    <a href="../CMS-PHP/index.php">
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
                if (!isset($_SESSION['id'])) { ?>
                    <li><a href="./inscription.php">Inscription</a></li>
                    <li><a href="./connexion.php">Connexion</a></li>
                    <li><a href="./admin/connexion.php">Admin</a></li>
                <?php } ?>

                <?php 
                if ($compte  === 'admin' && $compte !== "modérateur") { // Si connecté : profil et deconnexion  ?>
                    <div class="dropdown">
                        <button class="dropbtn admin-links a">Listes</button>
                        <div class="dropdown-content">
                            <li><a href="admin/listepages.php">Liste des pages</a></li>
                            <li><a href="admin/listearticles.php">Liste des articles</a></li>
                            <li><a href="admin/listeutilisateurs.php">Liste des utilisateurs</a></li>
                        </div>
                    </div>
                    <li><a class="nav-link" href="admin/dashboard.php">Dashboard</a></li>
                    <li><a class="nav-link" href="admin/deconnexion.php">Déconnexion</a></li>
                <?php } ?>
                
                <?php 
                if ($compte === "modérateur") {  ?>
                    <div class="dropdown">
                        <button class="dropbtn admin-links a">Listes</button>
                        <div class="dropdown-content">
                            <li><a href="admin/listepages.php">Liste des pages</a></li>
                            <li><a href="admin/listearticles.php">Liste des articles</a></li>
                        </div>
                    </div>
                    <li><a class="nav-link" href="admin/deconnexion.php">Déconnexion</a></li>
                <?php } 

                //} 
                else if ($compte  == 'admin') {
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
                    echo '<li><a href="../CMS-PHP/deconnexion.php">Déconnexion</a></li>';
                }
            ?>
        </ul>
    </nav>
</header>