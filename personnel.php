<?php
require 'db.php';
session_start();

if(!isset($_SESSION['admin'])) { header('Location: auth.php'); exit; }

$current_role = $_SESSION['role'] ?? 'Administrateur';

$personnel = $pdo->query('
    SELECT p.*, l.nom, l.prenom, tp.libelle as type_personnel
    FROM personnel p
    JOIN licencie l ON p.fk_id_licencie = l.idlicencie
    JOIN type_personnel tp ON p.fk_id_type = tp.id_type
    ORDER BY l.nom, l.prenom
')->fetchAll();

$licencies = $pdo->query('SELECT idlicencie, CONCAT(nom, " ", prenom) as fullname FROM licencie ORDER BY nom')->fetchAll(PDO::FETCH_KEY_PAIR);
$types = $pdo->query('SELECT * FROM type_personnel')->fetchAll(PDO::FETCH_KEY_PAIR);

$success = $error = '';

if($_POST && $current_role !== 'Lecteur') {
    $id_lic = $_POST['id_licencie'] ?? 0;
    $id_type = $_POST['id_type'] ?? 0;
    $date_ent = $_POST['date_entree'] ?? '';
    $date_sort = $_POST['date_sortie'] ?? null;
    $actif = isset($_POST['actif']) ? 1 : 0;
    $remarques = $_POST['remarques'] ?? '';

    $stmt = $pdo->prepare('
        INSERT INTO personnel (fk_id_licencie, fk_id_type, date_entree, date_sortie, actif, remarques)
        VALUES (?, ?, ?, ?, ?, ?)
    ');
    
    try {
        $stmt->execute([$id_lic, $id_type, $date_ent, $date_sort ?: null, $actif, $remarques]);
        $success = "Personnel ajouté avec succès !";
    } catch (Exception $e) {
        $error = "Erreur : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Personnel - Club de Tennis</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav>
        <a href="dashboard.php">Tableau de Bord</a>
        <a href="licencies.php">Licenciés</a>
        <a href="formules.php">Formules</a>
        <a href="personnel.php" class="active">Personnel</a>
        <a href="bureau.php">Bureau</a>
        <?php if(in_array($current_role, ['Administrateur', 'DPO'])): ?>
            <a href="historique.php">Historique</a>
        <?php endif; ?>
        <a href="logout.php">Déconnexion</a>
    </nav>

    <div class="container">
        <h1>👥 Gestion du Personnel</h1>

        <?php if($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="alert" style="background: #f8d7da; color: #842029;"><?= $error ?></div>
        <?php endif; ?>

        <?php if($current_role !== 'Lecteur'): ?>
        <div style="background: #f8f9ff; padding: 2rem; border-radius: 12px; margin-bottom: 2rem;">
            <h2>➕ Ajouter un Membre du Personnel</h2>
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
                    <label for="id_type">Type de Personnel</label>
                    <select id="id_type" name="id_type" required>
                        <option value="">-- Sélectionner --</option>
                        <?php foreach($types as $id => $type): ?>
                            <option value="<?= $id ?>"><?= htmlspecialchars($type) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="form-group">
                    <label for="date_entree">Date d'Entrée</label>
                    <input type="date" id="date_entree" name="date_entree" required>
                </div>
                <div class="form-group">
                    <label for="date_sortie">Date de Sortie (optionnel)</label>
                    <input type="date" id="date_sortie" name="date_sortie">
                </div>
                <div class="form-group">
                    <label for="actif">
                        <input type="checkbox" id="actif" name="actif" checked>
                        Actif
                    </label>
                </div>
                <div class="form-group">
                    <label for="remarques">Remarques</label>
                    <textarea id="remarques" name="remarques" rows="3"></textarea>
                </div>
                <button type="submit">Ajouter</button>
            </form>
        </div>
        <?php endif; ?>

        <h2>Liste du Personnel</h2>
        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Prénom</th>
                        <th>Type</th>
                        <th>Date Entrée</th>
                        <th>Date Sortie</th>
                        <th>Statut</th>
                        <th>Remarques</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($personnel as $p): ?>
                    <tr>
                        <td><?= htmlspecialchars($p['nom']) ?></td>
                        <td><?= htmlspecialchars($p['prenom']) ?></td>
                        <td><?= htmlspecialchars($p['type_personnel']) ?></td>
                        <td><?= $p['date_entree'] ?></td>
                        <td><?= $p['date_sortie'] ?? '-' ?></td>
                        <td>
                            <?php if($p['actif']): ?>
                                <span class="formule-tag current">Actif</span>
                            <?php else: ?>
                                <span style="color: #999;">Inactif</span>
                            <?php endif; ?>
                        </td>
                        <td><?= htmlspecialchars($p['remarques'] ?? '-') ?></td>
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