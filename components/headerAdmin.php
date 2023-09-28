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
                if ($compte !== "admin" && $compte !== "modérateur") { ?>
                    <li><a class="nav-link" href="../connexion.php">Connexion</a></li>
                    <li><a class="nav-link" href="connexion.php">Admin</a></li>
                <?php } ?> 
                <?php 
                if ($compte  === 'admin' && $compte !== "modérateur") { // Si connecté : profil et deconnexion  ?>
                    <div class="dropdown">
                        <button class="dropbtn admin-links a">Listes</button>
                        <div class="dropdown-content">
                            <li><a href="listepages.php">Liste des pages</a></li>
                            <li><a href="listearticles.php">Liste des articles</a></li>
                            <li><a href="listeutilisateurs.php">Liste des utilisateurs</a></li>
                        </div>
                    </div>
                    <li><a class="nav-link" href="dashboard.php">Dashboard</a></li>
                    <li><a class="nav-link" href="deconnexion.php">Déconnexion</a></li>
                <?php } ?>
                <?php 
                if ($compte === "modérateur") {  ?>
                    <div class="dropdown">
                        <button class="dropbtn admin-links a">Listes</button>
                        <div class="dropdown-content">
                            <li><a href="listepages.php">Liste des pages</a></li>
                            <li><a href="listearticles.php">Liste des articles</a></li>
                        </div>
                    </div>
                    <li><a class="nav-link" href="deconnexion.php">Déconnexion</a></li>
                <?php } 
            ?>
        </ul>
    </nav>
</header>