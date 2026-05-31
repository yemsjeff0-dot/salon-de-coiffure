<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <title>Administration - Ajout d'images</title>
    <style>
        body { 
            font-family: Arial; 
            background-color: #222; 
            color: #fff; 
            padding: 50px; 
        }
        .form-container { 
            background: #333; 
            padding: 20px; 
            border-radius: 8px; 
            max-width: 500px; 
        }
        select, input, button { 
            width: 100%; 
            padding: 10px; 
            margin-top: 10px; 
            margin-bottom: 20px; 
        }
        button { 
            background-color: #d4af37; 
            color: black; 
            border: none; 
            font-weight: bold; 
            cursor: pointer; 
        }
    </style>
</head>
<body>

    <div class="form-container">
        <h2>Ajouter une image au catalogue</h2>
        
        <form action="admin.php" method="POST" enctype="multipart/form-data">
            
            <label>1. Choisir la catégorie du service :</label>
            <select name="categorie" required>
                <option value="coupes">Coupes</option>
                <option value="soins">Soins</option>
                <option value="manicure">Manicure/Pédicure</option>
                <option value="premium">Premium</option>
            </select>

            <label>2. Sélectionner l'image (JPG, PNG) :</label>
            <input type="file" name="mon_image" accept="../image/*" required>

            <button type="submit" name="envoyer">Uploader l'image</button>
        </form>

        <?php
        if(isset($_POST['envoyer'])) {
    
            // __DIR__ donne le chemin réel actuel : C:\xampp\htdocs\Projet-De-Salon-De-Coiffure-main\PHP
            // dirname(__DIR__) remonte d'un cran : C:\xampp\htdocs\Projet-De-Salon-De-Coiffure-main
            $racine_projet = dirname(__DIR__);
            
            // On construit le chemin absolu complet vers le dossier images
            $dossier_destination = $racine_projet . "/image/" . $_POST['categorie'] . "/";
            
            // Sécurité : On crée le dossier s'il n'existe pas
            if (!file_exists($dossier_destination)) {
                mkdir($dossier_destination, 0777, true);
            }
            
            // On récupère le nom du fichier
            $nom_image = basename($_FILES["mon_image"]["name"]);
            
            // Le chemin absolu final où ranger l'image
            $chemin_final = $dossier_destination . $nom_image;

            // On déplace le fichier
            if(move_uploaded_file($_FILES["mon_image"]["tmp_name"], $chemin_final)) {
                echo "<p style='color: #4CAF50; font-weight: bold;'>BINGO ! L'image a été ajoutée avec succès !</p>";
            } else {
                // Si ça rate encore, ce message va nous afficher le chemin exact qui bloque
                echo "<p style='color: #f44336; font-weight: bold;'>Oups ! PHP n'a pas réussi à écrire ici : " . $dossier_destination . "</p>";
            }
        }
        ?>
    </div>

</body>
</html>