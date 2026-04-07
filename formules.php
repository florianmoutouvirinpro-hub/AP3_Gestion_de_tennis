<?php
require 'db.php';
session_start();

if(!isset($_SESSION['admin'])) {
    header('Location: auth.php');
    exit;
}

$current_role = $_SESSION['role'] ?? 'Administrateur';
$success = $error = '';

$formules = $pdo->query('SELECT * FROM formule ORDER BY libelleformule')->fetchAll();

if($_POST && $current_role !== 'Lecteur') {
    $libelle = $_POST['libelle'] ?? '';
    if($libelle) {
        $stmt = $pdo->prepare('INSERT INTO formule (libelleformule) VALUES (?)');
        try {
            $stmt->execute([$libelle]);
            $success = "Formule ajoutée !";
            header('Location: formules.php');
        } catch(Exception $e) {
            $error = "Erreur : " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Formules - Club de Tennis</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav>
        <a href="dashboard.php">Tableau de Bord</a>
        <a href="licencies.php">Licenciés</a>
        <a href="formules.php" class="active">Formules</a>
        <a href="personnel.php">Personnel</a>
        <a href="bureau.php">Bureau</a>
        <?php if(in_array($current_role, ['Administrateur', 'DPO'])): ?>
            <a href="historique.php">Historique</a>
        <?php endif; ?>
        <a href="logout.php">Déconnexion</a>
    </nav>

    <div class="container">
        <h1>📊 Gestion des Formules</h1>

        <?php if($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
        <?php endif; ?>
        <?php if($error): ?>
            <div class="alert" style="background: #f8d7da; color: #842029;"><?= $error ?></div>
        <?php endif; ?>

        <?php if($current_role !== 'Lecteur'): ?>
        <a href="ajout_formule.php" class="btn-back" style="background: #4caf50; color: white; padding: 0.75rem 1.5rem;">
            ➕ Ajouter une Formule
        </a>
        <?php endif; ?>

        <div class="table-responsive">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Libellé</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach($formules as $f): ?>
                    <tr>
                        <td><?= $f['idformule'] ?></td>
                        <td><?= htmlspecialchars($f['libelleformule']) ?></td>
                        <td>
                            <?php if($current_role === 'Administrateur'): ?>
                                <a href="delete_formule.php?id=<?= $f['idformule'] ?>" 
                                   onclick="return confirm('Supprimer ?')" style="color: #dc3545;">🗑️</a>
                            <?php else: ?>
                                <span style="color: #ccc;">🗑️</span>
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
