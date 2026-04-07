<?php
require 'db.php';
session_start();

if(!isset($_SESSION['admin'])) { header('Location: auth.php'); exit; }

$current_role = $_SESSION['role'] ?? 'Administrateur';

if($current_role === 'Lecteur') {
    die("<div class='alert alert-restricted'>Accès refusé : lecture seule</div>");
}

$error = '';
$id_lic = $_GET['id'] ?? 0;
$licencie = $pdo->prepare('SELECT * FROM licencie WHERE idlicencie = ?');
$licencie->execute([$id_lic]);
$lic = $licencie->fetch();

if(!$lic) die("Licencié non trouvé");

$formules = $pdo->query('SELECT * FROM formule')->fetchAll();
$inscriptions = $pdo->prepare('
    SELECT i.*, f.libelleformule
    FROM inscription i
    JOIN formule f ON i.fk_idformule = f.idformule
    WHERE i.fk_idlicencie = ?
');
$inscriptions->execute([$id_lic]);
$inscs = $inscriptions->fetchAll();

if($_POST) {
    $id_form = $_POST['id_formule'] ?? 0;
    $stmt = $pdo->prepare('INSERT INTO inscription (fk_idlicencie, fk_idformule) VALUES (?, ?)');
    try {
        $stmt->execute([$id_lic, $id_form]);
        header('Location: affecter_formule.php?id='.$id_lic);
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
    <title>Affecter Formule - Club de Tennis</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav>
        <a href="dashboard.php">Tableau de Bord</a>
        <a href="licencies.php">Licenciés</a>
        <a href="formules.php">Formules</a>
        <a href="personnel.php">Personnel</a>
        <a href="bureau.php">Bureau</a>
        <a href="logout.php">Déconnexion</a>
    </nav>

    <div class="container">
        <h1>📊 Affecter Formules à <?= htmlspecialchars($lic['prenom'] . ' ' . $lic['nom']) ?></h1>

        <?php if($error): ?>
            <div class="alert" style="background: #f8d7da; color: #842029;"><?= $error ?></div>
        <?php endif; ?>

        <form method="POST" style="max-width: 500px;">
            <div class="form-group">
                <label for="id_formule">Sélectionner une Formule</label>
                <select id="id_formule" name="id_formule" required>
                    <option value="">-- Choisir --</option>
                    <?php foreach($formules as $f): ?>
                        <option value="<?= $f['idformule'] ?>"><?= htmlspecialchars($f['libelleformule']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            <button type="submit">Affecter</button>
        </form>

        <h2 style="margin-top: 2rem;">Formules Actuelles</h2>
        <div class="formules-list">
            <?php if(empty($inscs)): ?>
                <p class="no-formule">Aucune formule affectée</p>
            <?php else: ?>
                <?php foreach($inscs as $i): ?>
                    <span class="formule-tag current"><?= htmlspecialchars($i['libelleformule']) ?></span>
                <?php endforeach; ?>
            <?php endif; ?>
        </div>
        <a href="licencies.php" class="btn-back">Retour</a>
    </div>

    <footer>
        <p>&copy; 2026 Club de Tennis - Site fait par MOUTOUVIRIN Florian</p>
    </footer>
</body>
</html>