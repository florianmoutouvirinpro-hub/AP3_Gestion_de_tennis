<?php
session_start();
require_once 'db.php';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = $_POST['login'] ?? '';
    $password = $_POST['password'] ?? '';
    $stmt = $pdo->prepare('SELECT * FROM admin WHERE login = ?');
    $stmt->execute([$login]);
    $admin = $stmt->fetch();
    if ($admin && password_verify($password, $admin['password'])) {
        $_SESSION['admin'] = $admin['login'];
        header('Location: dashboard.php');
        exit;
    } else {
        $msg = "Identifiants invalides";
    }
}
?>
