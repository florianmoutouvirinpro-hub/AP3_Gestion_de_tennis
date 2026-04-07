<?php
require 'db.php';
session_start();

if(!isset($_SESSION['admin'])) {
    header('Location: auth.php');
    exit;
}

$current_role = $_SESSION['role'] ?? 'Administrateur';

if($current_role === 'Lecteur') {
    die("<div class='alert alert-restricted'>Accès refusé : vous êtes en mode lecture seule</div>");
}

$success = $error = '';

if($_POST) {
    $numero = $_POST['numero'] ?? '';
    $nom = $_POST['nom'] ?? '';
    $prenom = $_POST['prenom'] ?? '';
    $email = $_POST['email'] ?? '';
    $tel = $_POST['tel'] ?? '';
    $adresse = $_POST['adresse'] ?? '';
    $daten = $_POST['daten'] ?? '';

    $stmt = $pdo->prepare('
        INSERT INTO licencie (numerolicence, nom, prenom, email, telephone, adresse, datenaissance, dateadhesion)
        VALUES (?, ?, ?, ?, ?, ?, ?, NOW())
    ');
    
    try {
        $stmt->execute([$numero, $nom, $prenom, $email, $tel, $adresse, $daten]);
        $success = "Licencié ajouté avec succès !";
    } catch (Exception $e) {
        $error = "Erreur lors de l'ajout : " . $e->getMessage();
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter Licencié - Club de Tennis</title>
    <link rel="stylesheet" href="css/style.css">
</head>
<body>
    <nav>
        <a href="dashboard.php">Tableau de Bord</a>
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
        <h1>➕ Ajouter un Licencié</h1>

        <?php if($success): ?>
            <div class="alert alert-success"><?= $success ?></div>
            <a href="licencies.php" class="btn-back">Retour à la liste</a>
        <?php else: ?>
            <?php if($error): ?>
                <div class="alert" style="background: #f8d7da; color: #842029; border: 1px solid #f5c2c7;"><?= $error ?></div>
            <?php endif; ?>

            <form method="POST">
                <div class="form-group">
                    <label for="numero">Numéro de Licence</label>
                    <input type="text" id="numero" name="numero" required>
                </div>
                <div class="form-group">
                    <label for="nom">Nom</label>
                    <input type="text" id="nom" name="nom" required>
                </div>
                <div class="form-group">
                    <label for="prenom">Prénom</label>
                    <input type="text" id="prenom" name="prenom" required>
                </div>
                <div class="form-group">
                    <label for="email">Email</label>
                    <input type="email" id="email" name="email" required>
                </div>
                <div class="form-group">
                    <label for="tel">Téléphone</label>
                    <input type="tel" id="tel" name="tel">
                </div>
                <div class="form-group">
                    <label for="adresse">Adresse</label>
                    <textarea id="adresse" name="adresse" rows="3"></textarea>
                </div>
                <div class="form-group">
                    <label for="daten">Date de Naissance</label>
                    <input type="date" id="daten" name="daten">
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