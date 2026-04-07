<?php
require 'db.php';
session_start();

// Vérifier la session
if(!isset($_SESSION['admin'])) {
    header('Location: auth.php');
    exit;
}

$current_role = $_SESSION['role'] ?? 'Administrateur';

// Compteurs
$nlic = $pdo->query('SELECT COUNT(*) FROM licencie')->fetchColumn();
$nfor = $pdo->query('SELECT COUNT(*) FROM formule')->fetchColumn();
$nsem = $pdo->query('SELECT COUNT(*) FROM inscription')->fetchColumn();

// AP2 : Personnel & Bureau
$nperso = $pdo->query('SELECT COUNT(*) FROM personnel')->fetchColumn();
$nperso_actif = $pdo->query('SELECT COUNT(*) FROM personnel WHERE actif = 1')->fetchColumn();
$nb_mandats_en_cours = $pdo->query('SELECT COUNT(*) FROM mandat_bureau WHERE date_fin IS NULL')->fetchColumn();

// AP3 : Historique
$n_supprimes = $pdo->query('SELECT COUNT(*) FROM historique')->fetchColumn();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tableau de Bord - Club de Tennis</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <!-- Navigation -->
    <nav>
        <a href="dashboard.php" class="active">Tableau de Bord</a>
        <a href="licencies.php">Licenciés</a>
        <a href="formules.php">Formules</a>
        <a href="personnel.php">Personnel</a>
        <a href="bureau.php">Bureau</a>
        <?php if(in_array($current_role, ['Administrateur', 'DPO'])): ?>
            <a href="historique.php">Historique</a>
        <?php endif; ?>
        <a href="logout.php">Déconnexion</a>
    </nav>

    <div class="container">
        <h1>🎾 Tableau de Bord Administration</h1>
        
        <!-- Affichage du rôle -->
        <div class="alert-info-role">
            <strong>Connecté :</strong> <?= htmlspecialchars($_SESSION['admin']) ?> 
            <span class="role-badge <?= strtolower($current_role) ?>">
                <?= htmlspecialchars($current_role) ?>
            </span>
        </div>

        <!-- Grille de statistiques -->
        <div class="stats-grid">
            <div class="stat-card">
                <div class="stat-number"><?= $nlic ?></div>
                <div>Licenciés</div>
                <a href="licencies.php" class="btn-back">Gérer</a>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $nfor ?></div>
                <div>Formules</div>
                <a href="formules.php" class="btn-back">Gérer</a>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $nsem ?></div>
                <div>Inscriptions</div>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $nperso_actif ?>/<?= $nperso ?></div>
                <div>Personnel (Actif/Total)</div>
                <a href="personnel.php" class="btn-back">Gérer</a>
            </div>
            <div class="stat-card">
                <div class="stat-number"><?= $nb_mandats_en_cours ?></div>
                <div>Mandats en Cours</div>
                <a href="bureau.php" class="btn-back">Gérer</a>
            </div>
            <?php if(in_array($current_role, ['Administrateur', 'DPO'])): ?>
            <div class="stat-card">
                <div class="stat-number"><?= $n_supprimes ?></div>
                <div>Suppressions (RGPD)</div>
                <a href="historique.php" class="btn-back">Consulter</a>
            </div>
            <?php endif; ?>
        </div>

        <!-- Actions rapides -->
        <div style="margin-top: 2rem; padding: 1.5rem; background: #f8f9ff; border-radius: 12px;">
            <h2 style="color: #23238D; margin-top: 0;">Actions Rapides</h2>
            <div style="display: grid; grid-template-columns: repeat(auto-fit, minmax(200px, 1fr)); gap: 1rem;">
                <?php if($current_role !== 'Lecteur'): ?>
                    <a href="ajout_licencie.php" class="btn-back">➕ Ajouter Licencié</a>
                    <a href="ajout_formule.php" class="btn-back">➕ Ajouter Formule</a>
                <?php endif; ?>
                <a href="personnel.php" class="btn-back">👥 Gérer Personnel</a>
                <a href="bureau.php" class="btn-back">🏛️ Gérer Bureau</a>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 Club de Tennis - Site fait par MOUTOUVIRIN Florian</p>
    </footer>
</body>
</html>