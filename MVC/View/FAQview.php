<?php

class FAQView {

    public function renderFAQ($faqContent = null) {
        ?>
       <!DOCTYPE html>
<html lang="fr">
<head>
        <link rel="stylesheet" type="text/css" href="../css/style.css" />

<meta charset="UTF-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
<title>FAQ – LogeMangue</title>
<?php include "../php/db2withoutlogin.php"  ; ?>
<style>
    body {
        font-family: 'Segoe UI', sans-serif;
        background: linear-gradient(135deg, #fff4ec, #ffffff);
        margin: 0;
        color: #333;
    }

    .faq-header {
        background: linear-gradient(135deg, #ff7a00, #ffce3cff);
        color: white;
        padding: 60px 20px;
        text-align: center;
        box-shadow: 0 10px 30px rgba(0,0,0,0.15);
    }

    .faq-header h1 {
        margin: 0;
        font-size: 2.6rem;
    }

    .faq-header p {
        opacity: 0.9;
        font-size: 1.1rem;
    }

    main {
        max-width: 850px;
        margin: -40px auto 80px;
        padding: 0 20px;
    }

    .faq-item {
        background: white;
        border-radius: 16px;
        margin-bottom: 18px;
        box-shadow: 0 15px 40px rgba(0,0,0,0.08);
        overflow: hidden;
        transition: transform 0.3s ease;
    }

    .faq-item:hover {
        transform: translateY(-4px);
    }

    .faq-question {
        padding: 22px 26px;
        cursor: pointer;
        font-weight: 600;
        font-size: 1.05rem;
        display: flex;
        justify-content: space-between;
        align-items: center;
    }

    .faq-question span {
        font-size: 1.6rem;
        color: #ff7a00;
        transition: transform 0.4s ease;
    }

    .faq-item.active .faq-question span {
        transform: rotate(45deg);
    }

    .faq-answer {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.5s ease, opacity 0.3s ease;
        opacity: 0;
        padding: 0 26px;
    }

    .faq-item.active .faq-answer {
        max-height: 300px;
        opacity: 1;
        padding-bottom: 26px;
    }

    .faq-answer p {
        margin: 0;
        line-height: 1.6;
        color: #555;
    }

    footer {
        text-align: center;
        padding: 30px;
        background: #f6f6f6;
    }

    footer a {
        color: #ff7a00;
        text-decoration: none;
        font-weight: 600;
    }
</style>
</head> 
<header class="topbar">
    <a href="index" class="topbar-logo">
      <img src="../png/topbar.png" onresize="3000" alt="Logo" />
    </a>
  <div class="burger-menu">
    <span></span>
    <span></span>
    <span></span>
  </div>
    <nav class="topbar-nav">
      <a class="nav-link" href="index">Accueil</a>
      <a class="nav-link" href="logements">Recherche</a>
        <?php if (!$isEtudiant): ?>
      <a class="nav-link" href="publish">Publier une annonce</a>
        <?php endif; ?>
      <?php if (!$isEtudiant): ?>
      <a class="nav-link" href="logements?view=mesannonces">Mes annonces</a>        
      <?php endif; ?>
      <?php if ($isEtudiant): ?>
      <a class="nav-link" href="candidatures">Mes candidatures</a>        
      <?php endif; ?>      

      <a class="nav-link" href="listemessagerie">Ma messagerie</a>
      <?php if ($isAdmin): ?> 
          <a class="nav-link" href="admin">Admin ⚙️</a>
      <?php endif; ?>

      <a class="nav-link " href="profil">Mon profil</a>
    </nav>
  </header>

<body>

<header class="faq-header">
    <h1>Besoin d’aide ?</h1>
    <p>On répond à toutes vos questions 👇</p>
</header>

<main>

    <div class="faq-item">
        <div class="faq-question">
            Comment créer un compte ?
            <span>＋</span>
        </div>
        <div class="faq-answer">
            <p>Inscrivez-vous en quelques secondes via le bouton “Inscription” et confirmez votre adresse e-mail.</p>
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question">
            Comment trouver un logement ?
            <span>＋</span>
        </div>
        <div class="faq-answer">
            <p>Utilisez les filtres intelligents pour trouver rapidement le logement qui vous correspond.</p>
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question">
            Comment réserver un logement ?
            <span>＋</span>
        </div>
        <div class="faq-answer">
            <p>Cliquez sur “Réserver” depuis l’annonce et suivez les étapes de confirmation.</p>
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question">
            Publier une annonce ?
            <span>＋</span>
        </div>
        <div class="faq-answer">
            <p>Connectez-vous à votre compte propriétaire et publiez votre annonce en quelques clics.</p>
        </div>
    </div>

    <div class="faq-item">
        <div class="faq-question">
            Mot de passe oublié ?
            <span>＋</span>
        </div>
        <div class="faq-answer">
            <p>Utilisez le lien “Mot de passe oublié” pour le réinitialiser instantanément.</p>
        </div>
    </div>

</main>


<script>
    document.querySelectorAll('.faq-question').forEach(question => {
        question.addEventListener('click', () => {
            const item = question.parentElement;

            // ferme les autres
            document.querySelectorAll('.faq-item').forEach(i => {
                if (i !== item) i.classList.remove('active');
            });

            item.classList.toggle('active');
        });
    });
</script>

</body>
</html>
<<<<<<< HEAD
        <?php
=======

<?php
>>>>>>> 280c68f215bad762b5e66ae8dd93a28d627d6363
    }
}