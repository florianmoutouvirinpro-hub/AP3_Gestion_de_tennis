<?php
// ✅ SCRIPT POUR GÉNÉRER LES HASHS BCRYPT CORRECTS

$passwords = [
    'dpo' => 'dpo',
    'lecteur' => 'lecteur',
    'admin' => 'admin'
];

echo "=== HASHS BCRYPT GÉNÉRÉS ===\n\n";

foreach ($passwords as $login => $password) {
    $hash = password_hash($password, PASSWORD_BCRYPT);
    echo "Login: $login\n";
    echo "Mot de passe: $password\n";
    echo "Hash: $hash\n";
    echo "Vérification: " . (password_verify($password, $hash) ? "✅ OK" : "❌ ERREUR") . "\n";
    echo "---\n";
}

// ========== SQL À COPIER ==========
echo "\n=== SQL À EXÉCUTER DANS phpMyAdmin ===\n\n";

$sql_lines = [];
foreach ($passwords as $login => $password) {
    $hash = password_hash($password, PASSWORD_BCRYPT);
    $role_id = ($login === 'admin') ? 1 : (($login === 'dpo') ? 3 : 2);
    $sql_lines[] = "INSERT INTO \`admin\` (\`login\`, \`password\`, \`fk_id_role\`) VALUES ('$login', '$hash', $role_id);";
}

echo implode("\n", $sql_lines) . "\n";
?>