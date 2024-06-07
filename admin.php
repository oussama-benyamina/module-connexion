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

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Administration - Module Connexion</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Administration</h1>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="profil.php">Profil</a></li>
        </ul>
    </nav>
    <main>
        <!-- Contenu de la page d'administration -->
        <h2>Liste des utilisateurs</h2>
        <table>
            <tr>
                <th>ID</th>
                <th>Login</th>
                <th>Prénom</th>
                <th>Nom</th>
            </tr>
            <?php while ($row = $result->fetch_assoc()) : ?>
                <tr>
                    <td><?php echo $row['id']; ?></td>
                    <td><?php echo $row['login']; ?></td>
                    <td><?php echo $row['prenom']; ?></td>
                    <td><?php echo $row['nom']; ?></td>
                </tr>
            <?php endwhile; ?>
        </table>
    </main>
    <footer>
        <p>&copy; 2024 Module Connexion</p>
    </footer>
</body>
</html>
