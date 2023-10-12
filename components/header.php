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
            <li><a href="./articles.php">Articles</a></li>
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
                if ($compte === "modérateur" && $compte  !== 'admin') {  ?>
                    <div class="dropdown">
                        <button class="dropbtn admin-links a">Listes</button>
                        <div class="dropdown-content">
                            <li><a href="admin/listepages.php">Liste des pages</a></li>
                            <li><a href="admin/listearticles.php">Liste des articles</a></li>
                        </div>
                    </div>
                    <li><a class="nav-link" href="admin/deconnexion.php">Déconnexion</a></li>
                <?php } 
            ?>
        </ul>
    </nav>
</header>