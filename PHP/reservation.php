<?php
// 1. CONFIGURATION DE LA BASE DE DONNÉES (Port 3307)
$host = "127.0.0.1";
$port = 3307;
$user = "root";
$password = "";
$dbname = "golden_touch";

$success_message = "";
$error_message = "";

// Récupération des informations du catalogue (via l'URL en GET)
$categorie_selectionnee = isset($_GET['categorie']) ? htmlspecialchars($_GET['categorie']) : 'Non spécifiée';
$image_selectionnee = isset($_GET['image']) ? htmlspecialchars($_GET['image']) : '';

// 2. TRAITEMENT DU FORMULAIRE LORS DU CLIC SUR "CONFIRMER"
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Connexion à MySQL avec le port 3307
    $conn = new mysqli($host, $user, $password, $dbname, $port);

    // Vérification de la connexion
    if ($conn->connect_error) {
        $error_message = "Erreur de connexion à la base de données : " . $conn->connect_error;
    } else {
        // Sécurisation des données reçues
        $nom_client = $conn->real_escape_string($_POST['nom_client']);
        $telephone = $conn->real_escape_string($_POST['telephone']);
        $categorie_service = $conn->real_escape_string($_POST['categorie_service']);
        $image_style = $conn->real_escape_string($_POST['image_style']);
        $date_rdv = $conn->real_escape_string($_POST['date_rdv']);
        $heure_rdv = $conn->real_escape_string($_POST['heure_rdv']);

        // 1. Contrainte de date : Pas de réservation dans le passé
        $date_actuelle = date('Y-m-d');
        if ($date_rdv < $date_actuelle) {
            die("<h3>Erreur : Vous ne pouvez pas réserver à une date passée. Veuillez reculer d'une page et choisir une autre date.</h3>");
        }

        // 2. Contrainte d'horaire : Heures d'ouverture du salon (ex: 09h00 à 19h00)
        if ($heure_rdv < "09:00" || $heure_rdv > "19:00") {
            die("<h3>Erreur : Le salon est ouvert uniquement de 09h00 à 19h00.</h3>");
        }

        // 3. Contrainte de chevauchement : Écart d'au moins 1 heure (60 minutes)
        // On cherche tous les rendez-vous déjà enregistrés pour la même date
        $sql_check = "SELECT heure_rdv FROM reservations WHERE date_rdv = ?";
        $stmt_check = $conn->prepare($sql_check);
        $stmt_check->bind_param("s", $date_rdv);
        $stmt_check->execute();
        $result_check = $stmt_check->get_result();

        $conflit = false;
        // On convertit l'heure demandée en "timestamp" (nombre de secondes) pour faire des maths facilement
        $nouvelle_heure = strtotime($heure_rdv); 

        while ($row = $result_check->fetch_assoc()) {
            $heure_existante = strtotime($row['heure_rdv']);
            
            // On calcule la différence absolue entre les deux heures, divisée par 60 pour l'avoir en minutes
            $diff_minutes = abs($nouvelle_heure - $heure_existante) / 60;
            
            // Si la différence est inférieure à 60 minutes, il y a conflit !
            if ($diff_minutes < 60) {
                $conflit = true;
                break; 
            }
        }
        $stmt_check->close();

        if ($conflit) {
            die("<h3>Erreur : Ce créneau est indisponible. Veuillez choisir une heure avec au moins 1 heure d'écart par rapport aux rendez-vous existants.</h3>");
        }

        // Requête SQL d'insertion
        $sql = "INSERT INTO reservations (nom_client, telephone, categorie_service, image_style, date_rdv, heure_rdv) 
                VALUES ('$nom_client', '$telephone', '$categorie_service', '$image_style', '$date_rdv', '$heure_rdv')";

        if ($conn->query($sql) === TRUE) {
            $success_message = "Votre réservation a été enregistrée avec succès ! Le salon Golden Touch vous attend.";
        } else {
            $error_message = "Erreur lors de l'enregistrement : " . $conn->error;
        }

        $conn->close();
    }
}
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Réservation - Golden Touch</title>
    <style>
        body {
            background-color: #0b0b0b;
            color: #ffffff;
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            margin: 0;
            padding: 40px 20px;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }
        .container {
            background-color: #141414;
            border: 1px solid #d4af37;
            border-radius: 8px;
            padding: 30px;
            max-width: 500px;
            width: 100%;
            box-shadow: 0 10px 25px rgba(212, 175, 55, 0.1);
        }
        h2 {
            color: #d4af37;
            text-align: center;
            margin-top: 0;
            text-transform: uppercase;
            letter-spacing: 2px;
        }
        .style-preview {
            text-align: center;
            margin-bottom: 20px;
        }
        .style-preview img {
            max-width: 150px;
            border-radius: 6px;
            border: 2px solid #d4af37;
            margin-top: 10px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        label {
            display: block;
            margin-bottom: 8px;
            color: #b3b3b3;
            font-size: 14px;
        }
        input[type="text"], input[type="tel"], input[type="date"], input[type="time"] {
            width: 100%;
            padding: 12px;
            background-color: #1f1f1f;
            border: 1px solid #333;
            border-radius: 4px;
            color: white;
            font-size: 16px;
            box-sizing: border-box;
        }
        input:focus {
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
        }
        .btn-submit:hover {
            background-color: #f3cf65;
        }
        .alert {
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
            text-align: center;
        }
        .alert-success {
            background-color: rgba(40, 167, 69, 0.2);
            border: 1px solid #28a745;
            color: #28a745;
        }
        .alert-error {
            background-color: rgba(220, 53, 69, 0.2);
            border: 1px solid #dc3545;
            color: #dc3545;
        }
        .back-link {
            display: block;
            text-align: center;
            margin-top: 20px;
            color: #b3b3b3;
            text-decoration: none;
            font-size: 14px;
        }
        .back-link:hover {
            color: #d4af37;
        }
    </style>
</head>
<body>

<div class="container">
    <h2>Finaliser votre style</h2>
    
    <?php if(!empty($success_message)): ?>
        <div class="alert alert-success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <?php if(!empty($error_message)): ?>
        <div class="alert alert-error"><?php echo $error_message; ?></div>
    <?php endif; ?>

    <?php if(empty($success_message)): ?>
        <form action="reservation.php" method="POST">
            
            <input type="hidden" name="categorie_service" value="<?php echo $categorie_selectionnee; ?>">
            <input type="hidden" name="image_style" value="<?php echo $image_selectionnee; ?>">

            <div class="style-preview">
                <p>Service : <strong><?php echo $categorie_selectionnee; ?></strong></p>
                <?php if(!empty($image_selectionnee)): ?>
                    <img src="<?php echo $image_selectionnee; ?>" alt="Style sélectionné">
                <?php endif; ?>
            </div>

            <div class="form-group">
                <label for="nom_client">Votre Nom complet</label>
                <input type="text" id="nom_client" name="nom_client" required placeholder="Ex: yems jeff">
            </div>

            <div class="form-group">
                <label for="telephone">Numéro de téléphone</label>
                <input type="tel" id="telephone" name="telephone" required placeholder="Ex: 661234567">
            </div>

            <div class="form-group">
                <label for="date_rdv">Date du rendez-vous</label>
                <input type="date" id="date_rdv" name="date_rdv" required min="<?php echo date('Y-m-d'); ?>">
            </div>

            <div class="form-group">
                <label for="heure_rdv">Heure souhaitée</label>
                <input type="time" id="heure_rdv" name="heure_rdv" required>
            </div>

            <button type="submit" class="btn-submit">Confirmer mon rendez-vous</button>
        </form>
    <?php endif; ?>

    <a href="galerie.php" class="back-link">Retourner au catalogue</a>
</div>

</body>
</html>