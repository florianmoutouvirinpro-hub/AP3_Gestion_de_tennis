<?php
require 'db.php';
session_start();

if(!isset($_SESSION['admin'])) {
    header('Location: auth.php');
    exit;
}

$current_role = $_SESSION['role'] ?? 'Administrateur';

// Accès DPO ET Admin seulement
if(!in_array($current_role, ['Administrateur', 'DPO'])) {
    die("<div class='alert alert-restricted'>Accès refusé : rôle DPO ou Administrateur requis</div>");
}

$supprimes = $pdo->query('
    SELECT * FROM historique 
    ORDER BY date_suppression DESC
')->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Historique RGPD - Club de Tennis</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav>
        <a href="dashboard.php">Tableau de Bord</a>
        <a href="licencies.php">Licenciés</a>
        <a href="formules.php">Formules</a>
        <a href="personnel.php">Personnel</a>
        <a href="bureau.php">Bureau</a>
        <a href="historique.php" class="active">Historique</a>
        <a href="logout.php">Déconnexion</a>
    </nav>

    <div class="container">
        <h1>📋 Historique des Suppressions (Conformité RGPD)</h1>
        <p style="color: #666; font-style: italic;">Accès réservé au DPO et Administrateurs</p>

        <div class="table-responsive">
            <table class="historique-table">
                <thead>
                    <tr>
                        <th>Numéro</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Date Suppression</th>
                        <th>Motif</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if(empty($supprimes)): ?>
                    <tr><td colspan="7" style="text-align: center; padding: 2rem;">Aucune suppression enregistrée</td></tr>
                    <?php else: ?>
                        <?php foreach($supprimes as $h): ?>
                        <tr class="historique-item deleted">
                            <td><?= htmlspecialchars($h['numero_licence'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($h['nom'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($h['prenom'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($h['email'] ?? 'N/A') ?></td>
                            <td><?= htmlspecialchars($h['telephone'] ?? '-') ?></td>
                            <td><?= $h['date_suppression'] ?></td>
                            <td><?= htmlspecialchars($h['motif_suppression'] ?? '-') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 Club de Tennis - Site fait par MOUTOUVIRIN Florian</p>
    </footer>
</body>
</html>