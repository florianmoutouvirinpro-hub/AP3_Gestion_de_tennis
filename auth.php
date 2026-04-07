<?php
session_start();

// Si déjà connecté
if(isset($_SESSION['admin'])) {
    header('Location: dashboard.php');
    exit;
}

require 'db.php';

$error = '';

if($_POST) {
    $login = $_POST['login'] ?? '';
    $mdp = $_POST['mdp'] ?? '';

    $stmt = $pdo->prepare('
        SELECT a.id, a.login, a.password, 
               COALESCE(r.libelle_role, "Administrateur") as libelle_role
        FROM admin a 
        LEFT JOIN role r ON a.fk_id_role = r.id_role
        WHERE a.login = ?
    ');
    $stmt->execute([$login]);
    $user = $stmt->fetch();

    if ($user && password_verify($mdp, $user['password'])) {
        $_SESSION['admin'] = $user['login'];
        $_SESSION['admin_id'] = $user['id'];
        $_SESSION['role'] = $user['libelle_role'];
        header('Location: dashboard.php');
        exit;
    } else {
        $error = "Identifiants incorrects";
    }
}
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Club de Tennis</title>
    <link rel="stylesheet" href="css/style.css">
    <style>
        /* ✅ MODALE RGPD */
        .rgpd-modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            animation: fadeIn 0.3s ease-in;
        }

        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }

        .rgpd-modal-content {
            background-color: white;
            margin: 5% auto;
            padding: 2rem;
            border-radius: 12px;
            width: 90%;
            max-width: 900px;
            max-height: 80vh;
            overflow-y: auto;
            box-shadow: 0 10px 40px rgba(0, 0, 0, 0.3);
            animation: slideDown 0.3s ease-out;
        }

        @keyframes slideDown {
            from {
                transform: translateY(-50px);
                opacity: 0;
            }
            to {
                transform: translateY(0);
                opacity: 1;
            }
        }

        .rgpd-modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 3px solid #23238D;
            padding-bottom: 1rem;
            margin-bottom: 1.5rem;
        }

        .rgpd-modal-header h1 {
            margin: 0;
            color: #23238D;
            font-size: 2rem;
        }

        .rgpd-close-btn {
            background: none;
            border: none;
            font-size: 2rem;
            cursor: pointer;
            color: #999;
            transition: color 0.3s;
        }

        .rgpd-close-btn:hover {
            color: #23238D;
        }

        .rgpd-modal-body {
            color: #333;
            line-height: 1.6;
        }

        .rgpd-modal-body h2 {
            color: #23238D;
            border-bottom: 2px solid #e1e5e9;
            padding-bottom: 0.5rem;
            margin-top: 1.5rem;
            margin-bottom: 1rem;
        }

        .rgpd-modal-body h3 {
            color: #667eea;
            margin-top: 1rem;
        }

        .rgpd-modal-body ul {
            padding-left: 2rem;
        }

        .rgpd-modal-body li {
            margin-bottom: 0.5rem;
        }

        .rgpd-badge {
            display: inline-block;
            background: #007bff;
            color: white;
            padding: 0.5rem 1rem;
            border-radius: 20px;
            font-size: 0.9rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }

        .rgpd-contact-box {
            background: #f8f9ff;
            padding: 1.5rem;
            border-left: 4px solid #23238D;
            border-radius: 8px;
            margin: 1rem 0;
        }

        .rgpd-table {
            width: 100%;
            border-collapse: collapse;
            margin: 1rem 0;
        }

        .rgpd-table th {
            background: #23238D;
            color: white;
            padding: 0.75rem;
            text-align: left;
        }

        .rgpd-table td {
            padding: 0.75rem;
            border-bottom: 1px solid #e1e5e9;
        }

        .rgpd-table tr:nth-child(even) {
            background: #f8f9ff;
        }

        .rgpd-modal-footer {
            margin-top: 2rem;
            padding-top: 2rem;
            border-top: 2px solid #e1e5e9;
            text-align: center;
        }

        .rgpd-modal-footer button {
            background: #23238D;
            color: white;
            border: none;
            padding: 0.75rem 2rem;
            border-radius: 8px;
            cursor: pointer;
            font-weight: 600;
            font-size: 1rem;
            transition: background 0.3s;
        }

        .rgpd-modal-footer button:hover {
            background: #1a1a6b;
        }

        /* ✅ LIEN RGPD SUR PAGE DE LOGIN */
        .rgpd-link {
            display: inline-block;
            margin-top: 1rem;
            color: #23238D;
            text-decoration: none;
            font-weight: 600;
            font-size: 0.95rem;
            cursor: pointer;
            transition: color 0.3s;
        }

        .rgpd-link:hover {
            color: #667eea;
            text-decoration: underline;
        }

        .rgpd-icon {
            margin-right: 0.5rem;
        }
    </style>
</head>
<body>
    <div class="container" style="margin-top: 5rem;">
        <h1>🎾 Connexion Administration</h1>
        
        <?php if($error): ?>
            <div class="alert alert-danger"><?= htmlspecialchars($error) ?></div>
        <?php endif; ?>

        <form method="POST">
            <div class="form-group">
                <label for="login">Identifiant</label>
                <input type="text" id="login" name="login" required>
            </div>
            <div class="form-group">
                <label for="mdp">Mot de passe</label>
                <input type="password" id="mdp" name="mdp" required>
            </div>
            <button type="submit">Se Connecter</button>
        </form>

        <!-- ✅ LIEN POUR OUVRIR LA MODALE RGPD -->
        <div style="text-align: center; margin-top: 1.5rem;">
            <a onclick="openRgpdModal()" class="rgpd-link">
                <span class="rgpd-icon">🔐</span>Politique de Confidentialité & RGPD
            </a>
        </div>
    </div>

    <!-- ✅ MODALE RGPD COMPLÈTE -->
    <div id="rgpdModal" class="rgpd-modal">
        <div class="rgpd-modal-content">
            <div class="rgpd-modal-header">
                <div>
                    <span class="rgpd-badge">🔐 CONFORME RGPD</span>
                    <h1>Politique de Confidentialité</h1>
                </div>
                <button class="rgpd-close-btn" onclick="closeRgpdModal()">&times;</button>
            </div>

            <div class="rgpd-modal-body">
                <p><strong>Date d'entrée en vigueur :</strong> 21 janvier 2026</p>

                <h2>1️⃣ Responsable du Traitement des Données</h2>
                <div class="rgpd-contact-box">
                    <p><strong>Club de Tennis de Nîmes</strong></p>
                    <p>📍 Adresse : À définir par le club</p>
                    <p>📧 Email : contact@clubtennis.fr</p>
                    <p>📞 Téléphone : À définir</p>
                    <p><strong>Délégué à la Protection des Données (DPO) :</strong> dpo@clubtennis.fr</p>
                </div>

                <h2>2️⃣ Données Collectées</h2>
                <p>Nous collectons les données personnelles suivantes :</p>
                <ul>
                    <li>✅ <strong>Données d'Identification :</strong> Nom, Prénom, Numéro de licence</li>
                    <li>✅ <strong>Données de Contact :</strong> Email, Téléphone</li>
                    <li>✅ <strong>Données Personnelles :</strong> Adresse, Date de naissance</li>
                    <li>✅ <strong>Données d'Adhésion :</strong> Date d'adhésion, Formules</li>
                </ul>

                <h2>3️⃣ Base Légale du Traitement</h2>
                <p>Le traitement des données est fondé sur :</p>
                <ul>
                    <li>✅ Consentement explicite du licencié</li>
                    <li>✅ Obligation contractuelle (gestion des licences)</li>
                    <li>✅ Obligation légale (assurances)</li>
                    <li>✅ Intérêts légitimes du club</li>
                </ul>

                <h2>4️⃣ Durée de Conservation</h2>
                <table class="rgpd-table">
                    <thead>
                        <tr>
                            <th>Type de Données</th>
                            <th>Durée de Conservation</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Données de licenciés actifs</td>
                            <td>Durée de l'adhésion + 1 an</td>
                        </tr>
                        <tr>
                            <td>Données historiques (suppression)</td>
                            <td>3 ans (conformité RGPD)</td>
                        </tr>
                        <tr>
                            <td>Données de connexion (logs)</td>
                            <td>6 mois</td>
                        </tr>
                        <tr>
                            <td>Données de mandats bureau</td>
                            <td>5 ans (comptabilité)</td>
                        </tr>
                    </tbody>
                </table>

                <h2>5️⃣ Droits des Personnes (RGPD)</h2>
                <p>Conformément au RGPD, vous disposez des droits suivants :</p>
                <ul>
                    <li>✅ <strong>Droit d'Accès :</strong> Demander une copie de vos données</li>
                    <li>✅ <strong>Droit de Rectification :</strong> Corriger vos données inexactes</li>
                    <li>✅ <strong>Droit à l'Oubli :</strong> Demander la suppression de vos données</li>
                    <li>✅ <strong>Droit à la Limitation :</strong> Restreindre le traitement</li>
                    <li>✅ <strong>Droit à la Portabilité :</strong> Recevoir vos données en format structuré</li>
                    <li>✅ <strong>Droit d'Opposition :</strong> Vous opposer au traitement</li>
                    <li>✅ <strong>Droit de Retrait du Consentement :</strong> Retirer votre consentement</li>
                </ul>

                <h2>6️⃣ Exercer Vos Droits</h2>
                <div class="rgpd-contact-box">
                    <p><strong>Pour exercer l'un de vos droits, contactez :</strong></p>
                    <p>📧 <strong>DPO :</strong> dpo@clubtennis.fr</p>
                    <p>📮 <strong>Adresse :</strong> Club de Tennis, Nîmes</p>
                    <p><strong>Délai de réponse :</strong> 30 jours</p>
                </div>

                <h2>7️⃣ Sécurité des Données</h2>
                <ul>
                    <li>✅ <strong>Chiffrement :</strong> Connexion HTTPS</li>
                    <li>✅ <strong>Authentification :</strong> Mots de passe hachés en bcrypt</li>
                    <li>✅ <strong>Contrôle d'Accès :</strong> Rôles (Admin, DPO, Lecteur)</li>
                    <li>✅ <strong>Protection SQL :</strong> PDO + Prepared Statements</li>
                    <li>✅ <strong>Protection XSS :</strong> htmlspecialchars()</li>
                    <li>✅ <strong>Sauvegardes :</strong> Régulières et sécurisées</li>
                    <li>✅ <strong>Logs de Suppression :</strong> Trigger RGPD automatique</li>
                </ul>

                <h2>8️⃣ Partage des Données</h2>
                <p>Vos données ne sont <strong>JAMAIS partagées</strong> avec :</p>
                <ul>
                    <li>❌ Des tiers commerciaux</li>
                    <li>❌ Des agences de marketing</li>
                    <li>❌ Des partenaires externes</li>
                </ul>

                <h2>9️⃣ Historique RGPD et Suppressions</h2>
                <p>Conformément à l'article 17 du RGPD :</p>
                <ul>
                    <li>✅ <strong>Trigger Automatique :</strong> Toute suppression est enregistrée</li>
                    <li>✅ <strong>Traçabilité :</strong> Date, heure et détails conservés</li>
                    <li>✅ <strong>Accès Restreint :</strong> Seuls Admin et DPO peuvent consulter</li>
                    <li>✅ <strong>Archivage :</strong> Conservation 3 ans minimum</li>
                </ul>

                <h2>🔟 Contact CNIL</h2>
                <div class="rgpd-contact-box">
                    <p><strong>Commission Nationale de l'Informatique et des Libertés</strong></p>
                    <p>📍 3 Place de Fontenoy, 75007 Paris</p>
                    <p>🌐 www.cnil.fr</p>
                    <p>📧 plainte@cnil.fr</p>
                </div>

                <div style="text-align: center; margin-top: 2rem; padding-top: 2rem; border-top: 2px solid #e1e5e9; color: #666;">
                    <p><strong>Cette application respecte strictement la réglementation RGPD.</strong></p>
                    <p style="font-size: 0.9rem;">Dernière mise à jour : 21 janvier 2026</p>
                </div>
            </div>

            <div class="rgpd-modal-footer">
                <button onclick="closeRgpdModal()">Fermer</button>
            </div>
        </div>
    </div>

    <footer>
        <p>&copy; 2026 Club de Tennis - Site fait par MOUTOUVIRIN Florian</p>
    </footer>

    <script>
        // ✅ GESTION DE LA MODALE RGPD
        function openRgpdModal() {
            document.getElementById('rgpdModal').style.display = 'block';
        }

        function closeRgpdModal() {
            document.getElementById('rgpdModal').style.display = 'none';
        }

        // Fermer la modale en cliquant en dehors
        window.onclick = function(event) {
            const modal = document.getElementById('rgpdModal');
            if (event.target == modal) {
                modal.style.display = 'none';
            }
        }

        // Fermer la modale avec la touche Échap
        document.addEventListener('keydown', function(event) {
            if (event.key === 'Escape') {
                document.getElementById('rgpdModal').style.display = 'none';
            }
        });
    </script>
</body>
</html>