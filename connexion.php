<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si les champs requis sont remplis
    if (!empty($_POST['login']) && !empty($_POST['password'])) {
        // Vérifier les informations de connexion
        $conn = new mysqli("localhost", "root", "1020", "moduleconnexion"); // Remplacez 'votre_mot_de_passe' par le mot de passe de l'utilisateur root
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        $login = $conn->real_escape_string($_POST['login']);
        $password = $_POST['password'];
        $sql = "SELECT * FROM utilisateurs WHERE login='$login'";
        $result = $conn->query($sql);
        if ($result->num_rows > 0) {
            $row = $result->fetch_assoc();
            if (password_verify($password, $row['password'])) {
                // Connexion réussie, initialisation de la session
                $_SESSION['login'] = $login;
                $_SESSION['prenom'] = $row['prenom'];
                $_SESSION['nom'] = $row['nom'];
                header("Location: profil.php");
                exit();
            } else {
                $error = "Mot de passe incorrect.";
            }
        } else {
            $error = "Utilisateur non trouvé.";
        }
        
    } else {
        $error = "Veuillez remplir tous les champs.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion - Module Connexion</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Connexion</h1>
    </header>
    <nav>
        <ul>
           
            <li><a href="accueil.php">accueil</a></li>
            <li><a href="inscription.php">Inscription</a></li>
        </ul>
    </nav>
    <main>
        <!-- Formulaire de connexion -->
        <form action="connexion.php" method="POST">
            <label for="login">Login:</label><br>
            <input type="text" id="login" name="login" required><br>
            <label for="password">Mot de passe:</label><br>
            <input type="password" id="password" name="password" required><br>
            <input type="submit" value="Se connecter">
        </form>
        <?php
        if (isset($error)) {
            echo "<p style='color:red;'>$error</p>";
        }
        ?>
    </main>
    <footer>
        <p>&copy; 2024 Module Connexion</p>
    </footer>
</body>
</html>
