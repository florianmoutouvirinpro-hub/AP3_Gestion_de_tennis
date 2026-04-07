<?php
require 'db.php';
session_start();

if(!isset($_SESSION['admin'])) { header('Location: auth.php'); exit; }

$current_role = $_SESSION['role'] ?? 'Administrateur';

if($current_role === 'Lecteur') {
    die("<div class='alert alert-restricted'>Accès refusé : lecture seule</div>");
}

$success = $error = '';

if($_POST) {
    $libelle = $_POST['libelle'] ?? '';
    if($libelle) {
        $stmt = $pdo->prepare('INSERT INTO formule (libelleformule) VALUES (?)');
        try {
            $stmt->execute([$libelle]);
            $success = "Formule ajoutée !";
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
    <title>Ajouter Formule - Club de Tennis</title>
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
        <h1>➕ Ajouter une Formule</h1>

        <?php if($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
            <a href="formules.php" class="btn-back">Retour aux formules</a>
        <?php else: ?>
            <?php if($error): ?>
                <div class="alert" style="background: #f8d7da; color: #842029;"><?= $error ?></div>
            <?php endif; ?>
            <form method="POST" style="max-width: 400px;">
                <div class="form-group">
                    <label for="libelle">Libellé de la Formule</label>
                    <input type="text" id="libelle" name="libelle" required>
                </div>
                <button type="submit">Ajouter</button>
            </form>
        <?php endif; ?>
    </div>

    <footer>
        <p>&copy; 2026 Club de Tennis - Site fait par MOUTOUVIRIN Florian</p>
    </footer>
</body>
</html>