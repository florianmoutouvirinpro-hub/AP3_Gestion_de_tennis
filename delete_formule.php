<?php
require 'db.php';
session_start();

if(!isset($_SESSION['admin'])) { header('Location: auth.php'); exit; }

$current_role = $_SESSION['role'] ?? 'Administrateur';
if($current_role !== 'Administrateur') { die("Accès refusé"); }

$id = $_GET['id'] ?? 0;
$stmt = $pdo->prepare('DELETE FROM formule WHERE idformule = ?');
$stmt->execute([$id]);

header('Location: formules.php');
exit;
?>