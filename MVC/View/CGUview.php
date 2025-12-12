<?php

class CGUView {
    public function renderCGU($cguContent) {
        ?>
        <!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CGU – LogeMangue</title>
    <link rel="stylesheet" href="../css/style.css">

    <style>
        .cgu-header {
            background: linear-gradient(90deg, #ffb300, #ff7a00);
            color: white;
            padding: 40px 20px;
            text-align: center;
            border-radius: 0 0 20px 20px;
            font-family: "Poppins", sans-serif;
        }

        .cgu-container {
            max-width: 850px;
            margin: 40px auto;
            padding: 0 20px;
            font-family: "Poppins", sans-serif;
            line-height: 1.7;
        }

        .cgu-container h2 {
            margin-top: 30px;
            color: #ff7a00;
            font-weight: 700;
        }

        footer {
            margin-top: 40px;
            padding: 20px;
            background: #f3f3f3;
            text-align: center;
            border-top: 1px solid #ddd;
        }
    </style>
</head>

<body>

<header class="cgu-header">
    <h1>Conditions Générales d'Utilisation</h1>
</header>

<main class="cgu-container">

    <p>
        Bienvenue sur LogeMangue…
    </p>

    <h2>1. Objet des CGU</h2>
    <p>…</p>

    <h2>2. Accès au site</h2>
    <p>…</p>

    <h2>3. Création et gestion du compte</h2>
    <p>…</p>

    <h2>4. Fonctionnalités proposées</h2>
    <p>…</p>

    <h2>5. Règles d’utilisation</h2>
    <p>…</p>

    <h2>6. Responsabilités</h2>
    <p>…</p>

    <h2>7. Données personnelles</h2>
    <p>…</p>

    <h2>8. Modifications des CGU</h2>
    <p>…</p>

    <h2>9. Contact</h2>
    <p>…</p>

</main>

<footer>
    <a href="index.php">Retour à l’accueil</a>
</footer>

</body>
</html>

        <?php
    }
}