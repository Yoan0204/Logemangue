<div>
        <a href="index.php">
          <img class="sidebar-logo" src="Aberent.png" alt="Logo">
        </a>
        <nav class="nav flex-column">
          <a class="nav-link active-link" href="index.php">Accueil</a>
          <a class="nav-link" href="recherche.php">Recherche</a>

          <hr>
          <?php if (isset($user['type_utilisateur']) && $user['type_utilisateur'] == 'Proprietaire' or $user['type_utilisateur'] == 'Organisme') : ?>
            <li><a class="nav-link" href="publish.php">Publier une annonce</a></li>
          <?php endif; ?>  
          <?php if (isset($user['type_utilisateur']) && $user['type_utilisateur'] == 'Proprietaire' or $user['type_utilisateur'] == 'Organisme') : ?>
            <li><a class="nav-link" href="mesannonces.php">Mes annonces</a></li>
          <?php endif; ?>  
          <a class="nav-link" href="messagerie.php?dest=8">Ma messagerie</a>

          <hr>
          <a class="nav-link" href="#">FAQ</a>
          <a class="nav-link" href="#">Contact</a>

          <hr>
          <?php if (isset($user['is_admin']) && $user['is_admin'] == 1) : ?>
            <li><a class="nav-link " href="admin.php">Admin ⚙️</a></li>
          <?php endif; ?> 
          <a class="nav-link" href="profil.php">Mon profil</a>
          <a class="nav-link" href="login.html">Connexion</a>
        </nav>
</div>