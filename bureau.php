<?php
require 'db.php';
session_start();

if(!isset($_SESSION['admin'])) { header('Location: auth.php'); exit; }

$current_role = $_SESSION['role'] ?? 'Administrateur';

$mandats_actuels = $pdo->query('
    SELECT m.*, l.nom, l.prenom, fb.libelle as fonction
    FROM mandat_bureau m
    JOIN licencie l ON m.fk_id_licencie = l.idlicencie
    JOIN fonction_bureau fb ON m.fk_id_fonction = fb.id_fonction
    WHERE m.date_fin IS NULL
    ORDER BY fb.libelle
')->fetchAll();

$mandats_historique = $pdo->query('
    SELECT m.*, l.nom, l.prenom, fb.libelle as fonction
    FROM mandat_bureau m
    JOIN licencie l ON m.fk_id_licencie = l.idlicencie
    JOIN fonction_bureau fb ON m.fk_id_fonction = fb.id_fonction
    WHERE m.date_fin IS NOT NULL
    ORDER BY m.date_fin DESC
')->fetchAll();

$licencies = $pdo->query('SELECT idlicencie, CONCAT(nom, " ", prenom) as fullname FROM licencie ORDER BY nom')->fetchAll(PDO::FETCH_KEY_PAIR);
$fonctions = $pdo->query('SELECT * FROM fonction_bureau')->fetchAll(PDO::FETCH_KEY_PAIR);

$success = $error = '';

if($_POST && $current_role !== 'Lecteur') {
    $id_lic = $_POST['id_licencie'] ?? 0;
    $id_func = $_POST['id_fonction'] ?? 0;
    $date_deb = $_POST['date_debut'] ?? '';
    $date_fin = $_POST['date_fin'] ?? null;

    if($id_func == 1) {
        $check = $pdo->prepare('SELECT COUNT(*) FROM mandat_bureau WHERE fk_id_fonction = 1 AND date_fin IS NULL AND fk_id_licencie != ?');
        $check->execute([$id_lic]);
        if($check->fetchColumn() > 0) {
            $error = "Un président est déjà en fonction !";
        } else {
            $stmt = $pdo->prepare('INSERT INTO mandat_bureau (fk_id_licencie, fk_id_fonction, date_debut, date_fin) VALUES (?, ?, ?, ?)');
            $stmt->execute([$id_lic, $id_func, $date_deb, $date_fin ?: null]);
            $success = "Mandat créé avec succès !";
        }
    } else {
        $stmt = $pdo->prepare('INSERT INTO mandat_bureau (fk_id_licencie, fk_id_fonction, date_debut, date_fin) VALUES (?, ?, ?, ?)');
        $stmt->execute([$id_lic, $id_func, $date_deb, $date_fin ?: null]);
        $success = "Mandat créé avec succès !";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Bureau - Club de Tennis</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav>
        <a href="dashboard.php">Tableau de Bord</a>
        <a href="licencies.php">Licenciés</a>
        <a href="formules.php">Formules</a>
        <a href="personnel.php">Personnel</a>
        <a href="bureau.php" class="active">Bureau</a>
        <?php if(in_array($current_role, ['Administrateur', 'DPO'])): ?>
            <a href="historique.php">Historique</a>
        <?php endif; ?>
        <a href="logout.php">Déconnexion</a>
    </nav>

    <div class="container">
        <h1>🏛️ Gestion du Bureau du Club</h1>

        <?php if($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="alert" style="background: #f8d7da; color: #842029;"><?= $error ?></div>
        <?php endif; ?>

        <h2>👥 Composition Actuelle</h2>
        <div class="stats-grid">
            <?php foreach($mandats_actuels as $m): ?>
            <div class="stat-card">
                <h3><?= htmlspecialchars($m['fonction']) ?></h3>
                <p><strong><?= htmlspecialchars($m['prenom'] . ' ' . $m['nom']) ?></strong></p>
                <p style="font-size: 0.9rem; color: rgba(255,255,255,0.8);">Depuis le <?= $m['date_debut'] ?></p>
            </div>
            <?php endforeach; ?>
        </div>

        <?php if($current_role !== 'Lecteur'): ?>
        <div style="background: #f8f9ff; padding: 2rem; border-radius: 12px; margin: 2rem 0;">
            <h2>➕ Attribuer un Mandat</h2>
            <form method="POST" style="max-width: 600px;">
                <div class="form-group">
                    <label for="id_licencie">Licencié</label>
                    <select id="id_licencie" name="id_licencie" required>
                        <option value="">-- Sélectionner --</option>
                        <?php foreach($licencies as $id => $name): ?>
                            <option value="<?= $id ?>"><?= htmlspecialchars($name) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="id_fonction">Fonction</label>
                    <select id="id_fonction" name="id_fonction" required>
                        <option value="">-- Sélectionner --</option>
                        <?php foreach($fonctions as $id => $func): ?>
                            <option value="<?= $id ?>"><?= htmlspecialchars($func) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date_debut">Date de Début</label>
                    <input type="date" id="date_debut" name="date_debut" required>
                </div>
                <div class="form-group">
                    <label for="date_fin">Date de Fin (optionnel)</label>
                    <input type="date" id="date_fin" name="date_fin">
                </div>
                <button type="submit">Attribuer Mandat</button>
            </form>
        </div>
        <?php endif; ?>

        <h2>📜 Historique des Mandats</h2>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Fonction</th>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Début</th>
                        <th>Fin</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($mandats_historique as $m): ?>
                    <tr>
                        <td><?= htmlspecialchars($m['fonction']) ?></td>
                        <td><?= htmlspecialchars($m['nom']) ?></td>
                        <td><?= htmlspecialchars($m['prenom']) ?></td>
                        <td><?= $m['date_debut'] ?></td>
                        <td><?= $m['date_fin'] ?></td>
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