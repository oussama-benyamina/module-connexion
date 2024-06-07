<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si les champs requis sont remplis
    if (!empty($_POST['login']) && !empty($_POST['prenom']) && !empty($_POST['nom']) && !empty($_POST['password']) && !empty($_POST['confirm_password'])) {
        // Vérifier si les mots de passe correspondent
        if ($_POST['password'] === $_POST['confirm_password']) {
            // Hasher le mot de passe
            $hashed_password = password_hash($_POST['password'], PASSWORD_DEFAULT);

            // Insertion dans la base de données
            $conn = new mysqli("localhost", "root", "1020", "moduleconnexion");
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            $login = $conn->real_escape_string($_POST['login']);
            $prenom = $conn->real_escape_string($_POST['prenom']);
            $nom = $conn->real_escape_string($_POST['nom']);
            $password = $hashed_password;

            $sql = "INSERT INTO utilisateurs (login, prenom, nom, password) VALUES ('$login', '$prenom', '$nom', '$password')";
            if ($conn->query($sql) === TRUE) {
                header("Location: connexion.php");
                exit();
            } else {
                $error = "Erreur: " . $sql . "<br>" . $conn->error;
            }
            $conn->close();
        } else {
            $error = "Les mots de passe ne correspondent pas.";
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
    <title>Inscription - Module Connexion</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <header>
        <h1>Inscription</h1>
    </header>
    <nav>
        <ul>
            <li><a href="index.php">Accueil</a></li>
            <li><a href="connexion.php">Connexion</a></li>
        </ul>
    </nav>
    <main>
        <!-- Formulaire d'inscription -->
        <form action="inscription.php" method="POST">
            <label for="login">Login:</label><br>
            <input type="text" id="login" name="login" required><br>
            <label for="prenom">Prénom:</label><br>
            <input type="text" id="prenom" name="prenom" required><br>
            <label for="nom">Nom:</label><br>
            <input type="text" id="nom" name="nom" required><br>
            <label for="password">Mot de passe:</label><br>
            <input type="password" id="password" name="password" required><br>
            <label for="confirm_password">Confirmez le mot de passe:</label><br>
            <input type="password" id="confirm_password" name="confirm_password" required><br>
            <input type="submit" value="S'inscrire">
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
