<?php
session_start();

// Vérifier si l'utilisateur est connecté
if (!isset($_SESSION['login'])) {
    header("Location: connexion.php");
    exit();
}

// Récupérer les informations de l'utilisateur
$conn = new mysqli("localhost", "root", "1020", "moduleconnexion"); // Mot de passe ajouté
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$login = $conn->real_escape_string($_SESSION['login']);
$sql = "SELECT * FROM utilisateurs WHERE login='$login'";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    $row = $result->fetch_assoc();
} else {
    echo "Utilisateur non trouvé.";
    exit();
}

// Traitement du formulaire de modification de profil
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Vérifier si les champs requis sont remplis
    if (!empty($_POST['prenom']) && !empty($_POST['nom'])) {
        // Récupérer les nouvelles valeurs
        $new_prenom = $conn->real_escape_string($_POST['prenom']);
        $new_nom = $conn->real_escape_string($_POST['nom']);
        
        // Initialiser la requête de mise à jour
        $sql = "UPDATE utilisateurs SET prenom='$new_prenom', nom='$new_nom'";

        // Vérifier si le mot de passe est également mis à jour
        if (!empty($_POST['new_password']) && !empty($_POST['confirm_password'])) {
            if ($_POST['new_password'] === $_POST['confirm_password']) {
                $hashed_password = password_hash($_POST['new_password'], PASSWORD_DEFAULT);
                $sql .= ", password='$hashed_password'";
            } else {
                echo "Les nouveaux mots de passe ne correspondent pas.";
                $conn->close();
                exit();
            }
        }

        // Ajouter la clause WHERE et exécuter la requête
        $sql .= " WHERE login='$login'";
        if ($conn->query($sql) === TRUE) {
            // Mettre à jour les informations de session si nécessaire
            $_SESSION['prenom'] = $new_prenom;
            $_SESSION['nom'] = $new_nom;
            header("Location: profil.php?success=1");
            exit();
        } else {
            echo "Erreur: " . $sql . "<br>" . $conn->error;
        }
    } else {
        echo "Veuillez remplir tous les champs obligatoires.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil - Module Connexion</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        /* Styles de base */
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f4f4f4;
        }

        header {
            background-color: #4CAF50;
            color: white;
            padding: 1rem;
            text-align: center;
        }

        nav ul {
            list-style-type: none;
            padding: 0;
            background-color: #333;
        }

        nav ul li {
            display: inline;
            margin-right: 10px;
        }

        nav ul li a {
            color: white;
            text-decoration: none;
            padding: 14px 20px;
            display: inline-block;
        }

        nav ul li a:hover {
            background-color: #575757;
        }

        main {
            padding: 1rem;
            max-width: 800px;
            margin: 20px auto;
            background-color: white;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }

        footer {
            text-align: center;
            padding: 1rem;
            background-color: #333;
            color: white;
           
            bottom: 0;
            width: 100%;
        }

        /* Styles du formulaire */
        form {
            display: flex;
            flex-direction: column;
        }

        label {
            margin: 10px 0 5px;
        }

        input[type="text"],
        input[type="password"] {
            padding: 10px;
            margin-bottom: 15px;
            border: 1px solid #ccc;
            border-radius: 4px;
            width: 100%;
            box-sizing: border-box;
        }

        input[type="submit"] {
            background-color: #4CAF50;
            color: white;
            padding: 10px 15px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
        }

        input[type="submit"]:hover {
            background-color: #45a049;
        }

        /* Styles spécifiques à la page profil */
        h2 {
            margin-bottom: 20px;
        }

        .error {
            color: red;
            margin-bottom: 20px;
        }

        .success {
            color: green;
            margin-bottom: 20px;
        }

    </style>
</head>
<body>
    <header>
        <h1>Profil <?php echo ($row['prenom']); ?></h1>
    </header>
    <nav>
        <ul>
           
            <li><a href="admin.php">Admin</a></li>
            <li><a href="index.php">Déconnexion</a></li>
        </ul>
    </nav>
    <main>
        <!-- Formulaire de modification de profil -->
        <h2>Modifier votre profil</h2>
        <form action="profil.php" method="POST">
            <label for="prenom">Prénom:</label><br>
            <input type="text" id="prenom" name="prenom" value="<?php echo htmlspecialchars($row['prenom']); ?>" required><br>
            <label for="nom">Nom:</label><br>
            <input type="text" id="nom" name="nom" value="<?php echo htmlspecialchars($row['nom']); ?>" required><br>
            <label for="new_password">Nouveau mot de passe:</label><br>
            <input type="password" id="new_password" name="new_password"><br>
            <label for="confirm_password">Confirmer le nouveau mot de passe:</label><br>
            <input type="password" id="confirm_password" name="confirm_password"><br>
            <input type="submit" value="Mettre à jour">
        </form>

        
    </main>
    <footer>
        <p>&copy; 2024 Module Connexion</p>
    </footer>
</body>
</html>
