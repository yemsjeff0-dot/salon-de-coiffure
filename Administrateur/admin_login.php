<?php
// 1. Démarrage de la session (C'est obligatoire et ça doit TOUJOURS être la première ligne)
session_start();

// 2. Définition de ton mot de passe secret (Tu peux le changer ici)
$mot_de_passe_secret = "Golden2026"; 
$message_erreur = "";

// 3. Vérification : si l'admin a validé le formulaire
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // On vérifie si le mot de passe tapé correspond au mot de passe secret
    if (isset($_POST['password']) && $_POST['password'] === $mot_de_passe_secret) {
        
        // C'est le bon mot de passe ! On donne le "bracelet VIP"
        $_SESSION['admin_connecte'] = true;
        
        // On le téléporte directement vers le tableau de bord
        header("Location: dashboard.php");
        exit(); // On stoppe la lecture du reste de la page
        
    } else {
        // Mauvais mot de passe
        $message_erreur = "Mot de passe incorrect. Accès refusé.";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Accès Administrateur - Golden Touch</title>
    <style>
        body {
            background-color: #0b0b0b;
            color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .login-container {
            background-color: #141414;
            border: 1px solid #d4af37;
            border-radius: 8px;
            padding: 40px;
            width: 100%;
            max-width: 400px;
            box-shadow: 0 10px 30px rgba(212, 175, 55, 0.15);
            text-align: center;
        }
        h2 {
            color: #d4af37;
            margin-top: 0;
            text-transform: uppercase;
            letter-spacing: 3px;
            margin-bottom: 30px;
        }
        .form-group {
            margin-bottom: 20px;
            text-align: left;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #b3b3b3;
            font-size: 14px;
        }
        input[type="password"] {
            width: 100%;
            padding: 12px;
            background-color: #1f1f1f;
            border: 1px solid #333;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            box-sizing: border-box;
            text-align: center;
            letter-spacing: 2px;
        }
        input[type="password"]:focus {
            border-color: #d4af37;
            outline: none;
        }
        .btn-submit {
            background-color: #d4af37;
            color: #000;
            border: none;
            width: 100%;
            padding: 14px;
            font-size: 16px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 1px;
            cursor: pointer;
            border-radius: 4px;
            transition: background 0.3s;
            margin-top: 10px;
        }
        .btn-submit:hover {
            background-color: #f3cf65;
        }
        .erreur {
            color: #dc3545;
            background-color: rgba(220, 53, 69, 0.1);
            padding: 10px;
            border: 1px solid #dc3545;
            border-radius: 4px;
            margin-bottom: 20px;
            font-size: 14px;
        }
    </style>
</head>
<body>

<div class="login-container">
    <h2>Espace Privé</h2>
    
    <?php if(!empty($message_erreur)): ?>
        <div class="erreur"><?php echo $message_erreur; ?></div>
    <?php endif; ?>

    <form action="admin_login.php" method="POST">
        <div class="form-group">
            <label for="password">Clé de sécurité</label>
            <input type="password" id="password" name="password" required placeholder="••••••••">
        </div>
        <button type="submit" class="btn-submit">Déverrouiller</button>
    </form>
</div>

</body>
</html>