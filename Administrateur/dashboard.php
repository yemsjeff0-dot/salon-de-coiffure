<?php
// 1. SÉCURITÉ : Vérification de la session
session_start();
if (!isset($_SESSION['admin_connecte']) || $_SESSION['admin_connecte'] !== true) {
    header("Location: admin_login.php");
    exit();
}

$message_upload = "";

// Si le formulaire d'upload a été soumis
if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'upload_image') {
    
    $dossier_cible = $_POST['dossier_destination']; // Ex: "images/coupes/coiffure homme/"
    
    // On s'assure que le dossier existe, sinon on le crée (sécurité)
    if (!is_dir($dossier_cible)) {
        mkdir($dossier_cible, 0777, true);
    }
    
    // Nettoyage du nom du fichier pour éviter les espaces et caractères bizarres
    $fichier_nom = basename($_FILES["nouvelle_image"]["name"]);
    $fichier_nom = preg_replace('/[^a-zA-Z0-9.\-_]/', '', $fichier_nom); 
    
    $chemin_final = rtrim($dossier_cible, '/') . '/' . $fichier_nom;
    $imageFileType = strtolower(pathinfo($chemin_final, PATHINFO_EXTENSION));
    
    // 1. Vérifier si c'est bien une image
    $check = getimagesize($_FILES["nouvelle_image"]["tmp_name"]);
    if($check !== false) {
        // 2. Vérifier le format
        if($imageFileType == "jpg" || $imageFileType == "png" || $imageFileType == "jpeg" || $imageFileType == "webp") {
            // 3. Déplacer l'image du dossier temporaire vers le bon dossier
            if (move_uploaded_file($_FILES["nouvelle_image"]["tmp_name"], $chemin_final)) {
                $message_upload = "<div class='alert alert-success'>L'image <strong>$fichier_nom</strong> a été ajoutée avec succès dans le dossier !</div>";
            } else {
                $message_upload = "<div class='alert alert-danger'>Erreur lors de l'enregistrement de l'image.</div>";
            }
        } else {
            $message_upload = "<div class='alert alert-danger'>Seuls les formats JPG, JPEG, PNG et WEBP sont autorisés.</div>";
        }
    } else {
        $message_upload = "<div class='alert alert-danger'>Le fichier envoyé n'est pas une image valide.</div>";
    }
}

$host = "127.0.0.1";
$port = 3307;
$user = "root";
$password = "";
$dbname = "golden_touch";

$conn = new mysqli($host, $user, $password, $dbname, $port);

if ($conn->connect_error) {
    die("Erreur de connexion : " . $conn->connect_error);
}

// RÉCUPÉRATION DES RÉSERVATIONS
$sql = "SELECT * FROM reservations ORDER BY date_rdv ASC, heure_rdv ASC";
$result = $conn->query($sql);
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>Tableau de Bord - Golden Touch</title>
        <style>
            body {
                background-color: #0b0b0b;
                color: #ffffff;
                font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
                margin: 0;
                padding: 30px;
            }
            .header-dashboard {
                display: flex;
                justify-content: space-between;
                align-items: center;
                border-bottom: 1px solid #d4af37;
                padding-bottom: 20px;
                margin-bottom: 40px;
            }
            h1 {
                color: #d4af37;
                margin: 0;
                text-transform: uppercase;
                letter-spacing: 2px;
                font-size: 24px;
            }
            .btn-logout {
                background-color: transparent;
                color: #dc3545;
                border: 1px solid #dc3545;
                padding: 10px 20px;
                text-decoration: none;
                border-radius: 4px;
                font-weight: bold;
                text-transform: uppercase;
                font-size: 12px;
                transition: all 0.3s;
            }
            .btn-logout:hover {
                background-color: #dc3545;
                color: #fff;
            }
            .table-container {
                background-color: #141414;
                border-radius: 8px;
                padding: 20px;
                box-shadow: 0 10px 30px rgba(0,0,0,0.5);
                overflow-x: auto;
            }
            table {
                width: 100%;
                border-collapse: collapse;
                text-align: left;
            }
            th {
                color: #d4af37;
                text-transform: uppercase;
                font-size: 13px;
                letter-spacing: 1px;
                padding: 15px;
                border-bottom: 2px solid #333;
            }
            td {
                padding: 15px;
                border-bottom: 1px solid #222;
                color: #e0e0e0;
                font-size: 15px;
                vertical-align: middle;
            }
            tr:hover {
                background-color: #1c1c1c;
            }
            .badge-service {
                background-color: rgba(212, 175, 55, 0.1);
                color: #d4af37;
                padding: 4px 10px;
                border-radius: 20px;
                font-size: 12px;
                border: 1px solid rgba(212, 175, 55, 0.3);
                font-weight: bold;
            }
            
            /* Style de la zone image cliquable */
            .Lien-image-style {
                display: inline-block;
                transition: transform 0.2s;
            }
            .Lien-image-style:hover {
                transform: scale(1.1); /* Zoom léger au survol */
            }
            .img-miniature {
                width: 60px;
                height: 60px;
                object-fit: cover;
                border-radius: 6px;
                border: 2px solid #333;
                box-shadow: 0 4px 8px rgba(0,0,0,0.3);
                transition: border-color 0.2s;
            }
            .Lien-image-style:hover .img-miniature {
                border-color: #d4af37; /* L'or au survol */
            }

            /* Bouton d'action Supprimer */
            .btn-delete {
                background-color: #721c24;
                color: #f8d7da;
                border: 1px solid #f5c6cb;
                padding: 6px 12px;
                text-decoration: none;
                border-radius: 4px;
                font-size: 13px;
                font-weight: bold;
                transition: all 0.2s;
            }
            .btn-delete:hover {
                background-color: #dc3545;
                color: #fff;
                border-color: #dc3545;
            }
            .no-data {
                text-align: center;
                padding: 40px;
                color: #888;
                font-style: italic;
            }
            .upload-container {
                background-color: #141414;
                border: 1px dashed #d4af37;
                border-radius: 8px;
                padding: 20px;
                margin-bottom: 30px;
            }
            .upload-container h3 {
                color: #d4af37;
                margin-top: 0;
                font-size: 18px;
            }
            .upload-form {
                display: flex;
                gap: 15px;
                align-items: center;
                flex-wrap: wrap;
            }
            .upload-form select, .upload-form input[type="file"] {
                padding: 10px;
                background-color: #1f1f1f;
                border: 1px solid #333;
                color: white;
                border-radius: 4px;
            }
            .btn-upload {
                background-color: #d4af37;
                color: #000;
                border: none;
                padding: 10px 20px;
                font-weight: bold;
                border-radius: 4px;
                cursor: pointer;
                transition: 0.3s;
            }
            .btn-upload:hover {
                background-color: #f3cf65;
            }
            .alert {
                padding: 15px;
                margin-bottom: 20px;
                border-radius: 4px;
            }
            .alert-success {
                background-color: rgba(40, 167, 69, 0.1);
                color: #28a745;
                border: 1px solid #28a745;
            }
            .alert-danger {
                background-color: rgba(220, 53, 69, 0.1);
                color: #dc3545;
                border: 1px solid #dc3545;
            }
        </style>
    </head>
    <body>
        <div class="header-dashboard">
            <h1>Gestion des Rendez-vous — Golden Touch</h1>
            <a href="logout.php" class="btn-logout">Déconnexion</a>
        </div>

        <?php if(!empty($message_upload)) echo $message_upload; ?>

        <div class="upload-container">
            <h3>Ajouter une nouvelle image au catalogue</h3>
            
            <form action="dashboard.php" method="POST" enctype="multipart/form-data" class="upload-form">
                <input type="hidden" name="action" value="upload_image">
                
                <select name="dossier_destination" required>
                    <option value=""> Choisir la catégorie</option>
                    <option value="../image/Coupes/Coiffure Homme">Coiffure Homme</option>
                    <option value="../image/Coupes/Coiffure Femme">Coiffure Femme</option>
                    <option value="../image/Manicure-pedicure">Manucure & Pédicure</option>
                    <option value="../image/Soins">Soin</option>
                    <option value="../image/PREMIUM">PREMIUM</option>
                </select>

                <input type="file" name="nouvelle_image" accept="image/png, image/jpeg, image/jpg, image/webp" required>
                
                <button type="submit" class="btn-upload">Téléverser l'image</button>
            </form>
        </div>

        <div class="table-container">
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Client</th>
                        <th>Téléphone</th>
                        <th>Catégorie</th>
                        <th>Aperçu Style</th>
                        <th>Date</th>
                        <th>Heure</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if ($result->num_rows > 0): ?>
                        <?php while($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><strong>#<?php echo $row['id']; ?></strong></td>
                                <td><?php echo htmlspecialchars($row['nom_client']); ?></td>
                                <td><?php echo htmlspecialchars($row['telephone']); ?></td>
                                <td><span class="badge-service"><?php echo htmlspecialchars($row['categorie_service']); ?></span></td>
                                <td>
                                    <a href="<?php echo htmlspecialchars($row['image_style']); ?>" target="_blank" class="Lien-image-style" title="Cliquez pour agrandir">
                                        <img src="<?php echo htmlspecialchars($row['image_style']); ?>" class="img-miniature" alt="Style choisi">
                                    </a>
                                </td>
                                <td><?php echo date('d/m/Y', strtotime($row['date_rdv'])); ?></td>
                                <td><strong><?php echo date('H:i', strtotime($row['heure_rdv'])); ?></strong></td>
                                <td>
                                    <a href="supprimer.php?id=<?php echo $row['id']; ?>" class="btn-delete" onclick="return confirm('Êtes-vous sûr de vouloir annuler ce rendez-vous ?');">Supprimer</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    <?php else: ?>
                        <tr>
                            <td colspan="8" class="no-data">Aucun rendez-vous enregistré pour le moment.</td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>

    </body>
</html>
<?php $conn->close(); ?>
