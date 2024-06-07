<?php
session_start();

// Vérifier si l'utilisateur est connecté en tant qu'admin
if (!isset($_SESSION['login']) || $_SESSION['login'] !== 'admin') {
    header("Location: connexion.php");
    exit();
}

// Récupérer les informations de tous les utilisateurs
$conn = new mysqli("localhost", "root", "1020", "moduleconnexion");
$sql = "SELECT * FROM utilisateurs";
$result = $conn->query($sql);
$conn->close();
?>