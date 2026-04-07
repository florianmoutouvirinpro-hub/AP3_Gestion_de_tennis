<?php
session_start();
if (!isset($_SESSION['admin'])) header('index.php');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Confidentialité & Sécurité</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
<nav>
    <a href="dashboard.php">Dashboard</a>
    <a href="licencies.php">Licenciés</a>
    <a href="formules.php">Formules</a>
    <a href="politique.php">Confidentialité</a>
    <a href="logout.php">Déconnexion</a>
</nav>
<div class="container">
    <h2>Politique de Confidentialité et Sécurité RGPD/ANSSI</h2>
    <p>Ce site respecte scrupuleusement le RGPD et les recommandations ANSSI pour la cybersécurité :</p>
    <ul>
        <li><strong>RGPD</strong> : Droit d'accès, rectification, suppression, portabilité des données personnelles</li>
        <li><strong>Authentification</strong> : Mots de passe bcrypt (hashé), sessions PHP sécurisées</li>
        <li><strong>Protections techniques</strong> : PDO requêtes préparées (anti-SQLi), htmlspecialchars (anti-XSS)</li>
        <li><strong>Confidentialité</strong> : Aucune diffusion à des tiers, hébergement local sécurisé</li>
        <li><strong>ANSSI</strong> : Journalisation connexions, validation client/serveur, export CSV sécurisé</li>
    </ul>
    <p><em>Date de dernière mise à jour : 16/12/2025</em></p>
</div>
</body>
</html>
