<?php
require 'db.php';
session_start();

if(!isset($_SESSION['admin'])) {
    header('Location: auth.php');
    exit;
}

$current_role = $_SESSION['role'] ?? 'Administrateur';

$licencies = $pdo->query('
    SELECT l.*, GROUP_CONCAT(f.libelleformule SEPARATOR ", ") as formules
    FROM licencie l
    LEFT JOIN inscription i ON l.idlicencie = i.fk_idlicencie
    LEFT JOIN formule f ON i.fk_idformule = f.idformule
    GROUP BY l.idlicencie
    ORDER BY l.nom, l.prenom
')->fetchAll();
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Licenciés - Club de Tennis</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav>
        <a href="dashboard.php">Tableau de Bord</a>
        <a href="licencies.php" class="active">Licenciés</a>
        <a href="formules.php">Formules</a>
        <a href="personnel.php">Personnel</a>
        <a href="bureau.php">Bureau</a>
        <?php if(in_array($current_role, ['Administrateur', 'DPO'])): ?>
            <a href="historique.php">Historique</a>
        <?php endif; ?>
        <a href="logout.php">Déconnexion</a>
    </nav>

    <div class="container">
        <h1>📋 Gestion des Licenciés</h1>
        
        <?php if($current_role !== 'Lecteur'): ?>
        <a href="ajout_licencie.php" class="btn-back" style="background: #4caf50; color: white; padding: 0.75rem 1.5rem;">
            ➕ Ajouter un Licencié
        </a>
        <?php endif; ?>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>N° Licence</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Email</th>
                        <th>Téléphone</th>
                        <th>Formules</th>
                        <th>Date Adhésion</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($licencies as $l): ?>
                    <tr>
                        <td><?= htmlspecialchars($l['numerolicence']) ?></td>
                        <td><?= htmlspecialchars($l['nom']) ?></td>
                        <td><?= htmlspecialchars($l['prenom']) ?></td>
                        <td><?= htmlspecialchars($l['email']) ?></td>
                        <td><?= htmlspecialchars($l['telephone'] ?? '-') ?></td>
                        <td>
                            <?php if($l['formules']): ?>
                                <span class="formule-tag current"><?= htmlspecialchars($l['formules']) ?></span>
                            <?php else: ?>
                                <span class="no-formule">Aucune</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($l['dateadhesion']) ?></td>
                        <td>
                            <a href="affecter_formule.php?id=<?= $l['idlicencie'] ?>" title="Affecter Formule">✏️</a>
                            <?php if($current_role === 'Administrateur'): ?>
                                <a href="delete_licencie.php?id=<?= $l['idlicencie'] ?>" 
                                   onclick="return confirm('Supprimer ce licencié ?')" title="Supprimer" style="color: #dc3545;">🗑️</a>
                            <?php else: ?>
                                <span style="color: #ccc; cursor: not-allowed;">🗑️</span>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 Club de Tennis - Site fait par MOUTOUVIRIN Florian</p>
    </footer>
</body>
</html>