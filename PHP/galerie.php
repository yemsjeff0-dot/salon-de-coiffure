<?php
$categorie_actuelle = isset($_GET['cat']) ? $_GET['cat'] : 'coupes';

$images_catalogue = [];
$titre_page = "";
$description_page = "";

switch ($categorie_actuelle) {
    case 'soins':
        $images_catalogue = glob("../image/Soins/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
        $titre_page = "Le Sanctuaire des Soins";
        $description_page = "Offrez à votre corps et à vos cheveux l'attention qu'ils méritent. Des rituels nourrissants, profonds et relaxants.";
        break;
        
    case 'manicure':
        $images_catalogue = glob("../image/Manicure-pedicure/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
        $titre_page = "Beauté des Mains & Pieds";
        $description_page = "La perfection jusqu'au bout des ongles. Profitez de nos soins de mise en beauté pour des mains douces et soignées.";
        break;
        
    case 'premium':
        $images_catalogue = glob("../image/PREMIUM/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
        $titre_page = "L'Expérience VIP";
        $description_page = "L'ultime signature Golden Touch. Des prestations sur-mesure, des produits de luxe exclusifs et un moment de détente absolue.";
        break;
        
    case 'coupes':
    default:
        $images_hommes = glob("../image/Coupes/Coiffure Homme/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
        $images_femmes = glob("../image/Coupes/Coiffure Femme/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
        $max_images = max(count($images_hommes), count($images_femmes));
        
        for ($i = 0; $i < $max_images; $i++) {
            if (isset($images_hommes[$i]))  $images_catalogue[] = $images_hommes[$i];
            if (isset($images_femmes[$i])) $images_catalogue[] = $images_femmes[$i];
        }
        $titre_page = "Coupes & Stylisme";
        $description_page = "Révélez votre personnalité. De l'élégance classique aux tendances audacieuses, nos experts visagistes subliment vos traits.";
        break;
}

$image_initiale = (count($images_catalogue) > 0) ? $images_catalogue[0] : "../image/default-salon.jpg";
?>

<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Catalogue - Golden Touch</title>
        <link rel="stylesheet" href="../CSS/galerie.css"> 
        <link rel="icon" type="image/png" href="../image/logo.png"> 
        <style>
            /* Positionnement de la Navbar */
            .navbar-vip {
                position: absolute; /* Détache la navbar du reste de la page */
                top: 30px;          /* Distance depuis le haut */
                right: 50px;        /* Distance depuis la droite */
                z-index: 1000;      /* S'assure qu'elle passe par-dessus le carrousel */
            }

            /* Alignement horizontal des liens */
            .navbar-vip ul {
                list-style-type: none; /* Enlève les puces */
                margin: 0;
                padding: 0;
                display: flex;         /* Aligne les éléments sur une ligne */
                gap: 30px;             /* Espace entre chaque lien */
            }

            /* Style des liens (Noir, Or et élégant) */
            .navbar-vip a {
                color: #b3b3b3;
                text-decoration: none;
                font-size: 14px;
                text-transform: uppercase;
                letter-spacing: 2px;
                font-weight: bold;
                transition: color 0.3s ease, border-bottom 0.3s ease;
                padding-bottom: 5px;
                border-bottom: 2px solid transparent; /* Prépare la barre dorée au survol */
            }

            /* Effet au survol et pour la page active */
            .navbar-vip a:hover,
            .navbar-vip a.actif {
                color: #d4af37; /* L'or de Golden Touch */
                border-bottom: 2px solid #d4af37;
            }
        </style>         
    </head>
    <body>
        <nav class="navbar-vip">
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="../Salon De Coiffure/service.html">Services</a></li>
                <li><a href="galerie.php">catalogue</a></li>
                <li><a href="#">Compte</a></li>
            </ul>
        </nav>

        <section class="catalogue-section">
            <div class="zone-image-principale">
                <img id="image-principale" src="<?php echo $image_initiale; ?>" alt="Aperçu du service">
            </div>

            <div class="zone-details">
                
                <div class="select-categorie">
                    <label for="categorie">Catégorie :</label>
                    <select id="categorie" name="categorie" onchange="location = 'galerie.php?cat=' + this.value;">
                        <option value="coupes" <?php if($categorie_actuelle == 'coupes') echo 'selected'; ?>>Coupes</option>
                        <option value="soins" <?php if($categorie_actuelle == 'soins') echo 'selected'; ?>>Soins</option>
                        <option value="manicure" <?php if($categorie_actuelle == 'manicure') echo 'selected'; ?>>Manucure / Pédicure</option>
                        <option value="premium" <?php if($categorie_actuelle == 'premium') echo 'selected'; ?>>Premium</option>
                    </select>
                </div>

                <div class="informations-relatives">
                    <h2 id="titre-service"><?php echo $titre_page; ?></h2>
                    <p id="description-service"><?php echo $description_page; ?></p>
                </div>

                <div class="carrousel-navigation-miniatures">
                    <button class="nav-btn-mini" onclick="glisserEtSelectionner(-1)">&#10094;</button>
                    
                    <div class="miniatures-wrapper-view">
                        <div id="miniatures-track" class="miniatures-track">
                            <?php
                            if (count($images_catalogue) > 0) {
                                foreach ($images_catalogue as $index => $chemin_image) {
                                    $classe_active = ($index == 0) ? "miniature active" : "miniature";
                                    echo "<img src='$chemin_image' class='$classe_active' onclick='changerImage(this.src, $index)'>";
                                }
                            } else {
                                echo "<p>Aucun modèle disponible.</p>";
                            }
                            ?>
                        </div>
                    </div>
                    
                    <button class="nav-btn-mini" onclick="glisserEtSelectionner(1)">&#10095;</button>
                </div>

                <a href="reservation.php?service=<?php echo $categorie_actuelle; ?>" class="bouton-reservation">Réserver ce style</a>

                <dialog id="dialog-confirmation">
                    <p>Êtes-vous sûr de vouloir réserver ce style ?</p>
                    <button id="confirmer-btn">Confirmer</button>
                    <button id="annuler-btn">Annuler</button>
                </dialog>
                <footer class="footer-catalogue">
                    <h2 id="golden">GOLDEN</h2>
                    <img src="../image/logo.png" alt="Logo Golden Touch" class="logo-catalogue">
                    <h2 id="touch">TOUCH</h2>
                </footer>
            </div>
        </section>

        <script>
                    // 1. GESTION DES OMBRES HARMONIQUES ET DE L'IMAGE PRINCIPALE
            const couleursOmbre = [
                'rgba(212, 175, 55, 0.6)',  // Or
                'rgba(100, 50, 255, 0.5)',  // Violet
                'rgba(200, 200, 200, 0.4)', // Argent
                'rgba(150, 80, 200, 0.5)'   // Violet doux
            ];

            const imagePrincipale = document.getElementById('image-principale');
            const miniaturesList = document.querySelectorAll('.miniature');
            const boutonReservation = document.querySelector('.bouton-reservation'); // On cible ton bouton

            function changerImage(source, index) {
                // A. Changement visuel de l'image
                imagePrincipale.style.opacity = 0.5;
                
                setTimeout(() => {
                    imagePrincipale.src = source;
                    imagePrincipale.style.opacity = 1;
                    
                    let couleurChoisie = couleursOmbre[index % couleursOmbre.length];
                    imagePrincipale.style.boxShadow = `0 0 50px 15px ${couleurChoisie}`;
                }, 150);

                // B. Mise à jour des miniatures actives
                miniaturesList.forEach(min => min.classList.remove('active'));
                if(miniaturesList[index]) {
                    miniaturesList[index].classList.add('active');
                }

                // C. LA NOUVEAUTÉ : Mise à jour automatique du bouton de réservation !
                let categorieActuelle = document.getElementById('categorie') ? document.getElementById('categorie').value : "Premium";
                // On modifie le lien du bouton pour qu'il transporte la bonne image
                if(boutonReservation) {
                    boutonReservation.href = `reservation.php?categorie=${encodeURIComponent(categorieActuelle)}&image=${encodeURIComponent(source)}`;
                }
            }
            
            const miniaturesTrack = document.getElementById('miniatures-track');
            let indexActif = 0; // L'image actuellement "sélectionnée"
            
            // Ajuste ces valeurs selon la taille de tes miniatures dans ton CSS
            const gapWidth = 10; 
            const miniatureWidth = 80; 
            const totalSlideWidth = miniatureWidth + gapWidth;

            function glisserEtSelectionner(direction) {
                if(miniaturesList.length === 0) return;

                // 1. On calcule la prochaine image à cibler (+1 ou -1)
                indexActif += direction;

                // 2. On bloque les extrémités pour ne pas déborder dans le vide
                if (indexActif < 0) {
                    indexActif = 0;
                    return; // On arrête l'action
                }
                if (indexActif >= miniaturesList.length) {
                    indexActif = miniaturesList.length - 1;
                    return;
                }

                // 3. On récupère la miniature qui entre dans le sélecteur
                let miniatureCible = miniaturesList[indexActif];
                let nouvelleSource = miniatureCible.getAttribute('src') || miniatureCible.src;

                // 4. On met à jour la grande image, l'ombre et le bouton 
                // (En appelant ta fonction qui marche déjà très bien !)
                changerImage(nouvelleSource, indexActif);

                // 5. On déplace le ruban (le track) pour aligner la nouvelle image
                // Le calcul dépend d'où se trouve ta "case de sélection" dans le design.
                // Par défaut, on décale d'une largeur complète vers la gauche ou la droite.
                let translationX = - (indexActif * totalSlideWidth);
                
                // Si ta case de sélection est fixée au MILIEU de 3 éléments visibles, 
                // tu peux ajouter un décalage compensatoire ici (ex: + totalSlideWidth)
                miniaturesTrack.style.transform = `translateX(${translationX}px)`;
                miniaturesTrack.style.transition = "transform 0.4s ease-in-out"; // Animation fluide
            }

            // 3. INITIALISATION (Pour que le bouton soit prêt dès le chargement de la page)
            window.onload = function() {
                if(imagePrincipale && imagePrincipale.src) {
                    let categorieActuelle = document.getElementById('categorie') ? document.getElementById('categorie').value : "Premium";
                    if(boutonReservation) {
                        boutonReservation.href = `reservation.php?categorie=${encodeURIComponent(categorieActuelle)}&image=${encodeURIComponent(imagePrincipale.src)}`;
                    }
                }
            };
        </script>

    </body>
</html>