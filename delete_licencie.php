<?php
require 'db.php';
session_start();

if(!isset($_SESSION['admin'])) { header('Location: auth.php'); exit; }

$current_role = $_SESSION['role'] ?? 'Administrateur';
if($current_role !== 'Administrateur') { die("Accès refusé"); }

$id = $_GET['id'] ?? 0;

// Supprimer les inscriptions associées
$stmt = $pdo->prepare('DELETE FROM inscription WHERE fk_idlicencie = ?');
$stmt->execute([$id]);

// Supprimer le personnel associé
$stmt = $pdo->prepare('DELETE FROM personnel WHERE fk_id_licencie = ?');
$stmt->execute([$id]);

// Supprimer les mandats associés
$stmt = $pdo->prepare('DELETE FROM mandat_bureau WHERE fk_id_licencie = ?');
$stmt->execute([$id]);

// Supprimer le licencié (TRIGGER déclenché automatiquement)
$stmt = $pdo->prepare('DELETE FROM licencie WHERE idlicencie = ?');
$stmt->execute([$id]);

header('Location: licencies.php');
exit;
?>