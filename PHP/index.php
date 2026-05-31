<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="../CSS/style.css">
    <link href='http://fonts.googleapis.com/css?family=Open+Sans' rel='stylesheet' type='text/css'>
    <style>
        body{
            font-family: 'Open Sans', sans-serif;
            margin: 0;
            padding: 0;
            color: white;
        }
        .service a{
            text-decoration: none;
            color: pink;
        }
        #premium a{
            color: #f5ff80;
        }
        .service{
            display: flex;
            justify-content: space-around;
            margin-bottom: 0px;
            text-align: center;
        }
        .service h3{
            margin: 0;
            margin-top: 50px;
            text-align: center;
            cursor: pointer;
        }

        .presentation{
            text-align: center;
            margin-top: 5px;
        }

        .services div{
            position: relative;
            width: 80%;
            height: 80%;
            overflow: hidden;
            border-radius: 10px;
        }
        .slide {
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            object-fit: cover;
            opacity: 0; 
            transition: opacity 1s ease-in-out;
        }
        /* C'est cette ligne qui rend l'image visible ! */
        .slide.active {
            opacity: 1 !important; 
        }
    </style>
</head>
<body>
    <header>
        <img src="../image/logo.png" alt="logo" class="logo"> 
        <h1 class="titre-accueil">GOLDEN TOUCH</h1>
    </header>
    <section  class="section1">
        <nav>
            <ul>
                <li><a href="index.php">Accueil</a></li>
                <li><a href="../Salon De Coiffure/service.html">Services</a></li>
                <li><a href="galerie.php">catalogue</a></li>
                <!-- <li><a href="#">Compte</a></li> -->
                <li class="ip"><a href="galerie.php">Reserver-maintenant</a></li>
            </ul>
        </nav>
    </section>
    <!--<div class="diaporama">
            <img src="../image/pre.jpg" alt="">
            <img src="../image/gre.jpg" alt="">
            <img src="../image/fre.jpg" alt="">
    </div>-->
    <div class="presentation">
        <p class="gras">Reservez votre moment de beaute</p>
        <p class="petit">Salon de coiffure premium a Dschang</p>
        <button class="contact-button">Prendre un rendez-vous</button>
    </div>
    <div class="service">
        <h3><a href="galerie.php?cat=coupes">COUPES</a></h3>
        <h3><a href="galerie.php?cat=soins">SOINS</a></h3>
        <h3><a href="galerie.php?cat=manicure">MANICURE/PEDICURE</a></h3>
        <h3 id="premium"><a href="galerie.php?cat=premium">PREMIUM</a></h3>
    </div>
    <div class="services">
        <div class="diaporama">
            <?php
                // On demande à PHP de trouver toutes les images dans le dossier 'coupes' (homme et femme)
                $images_coupes_H = glob("../image/coupes/Coiffure Homme/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
                $images_coupes_F = glob("../image/coupes/Coiffure Femme/*.{jpg,jpeg,png,gif}", GLOB_BRACE);
                $images_coupes = [];

                $maxImages = max(count($images_coupes_H), count($images_coupes_F));
                
                for ($i = 0; $i < $maxImages; $i++) {
                    if (isset($images_coupes_H[$i])) {
                        $images_coupes[] = $images_coupes_H[$i];
                    }
                    if (isset($images_coupes_F[$i])) {
                        $images_coupes[] = $images_coupes_F[$i];
                    }
                }
                // S'il y a des images, on fait une boucle pour les afficher une par une
                if (count($images_coupes) > 0) {
                    foreach($images_coupes as $index => $image) {
                        $classe = ($index == 0) ? "slide active" : "slide";
                        echo "<img src='" . $image . "' alt='Soin' class='" . $classe . "' style='width: 100%; height: auto;'>";
                    }
                } else {
                    echo "<p>Aucune image pour le moment.</p>";
                }
            ?>
        </div>

        <div class="diaporama">
            <?php
                // On demande à PHP de trouver toutes les images dans le dossier 'soins'
                $images_soins = glob("../image/Soins/*.{jpg,jpeg,png,gif}", GLOB_BRACE);

                // S'il y a des images, on fait une boucle pour les afficher une par une
                if (count($images_soins) > 0) {
                    foreach($images_soins as $index => $image) {
                        $classe = ($index == 0) ? "slide active" : "slide";
                        echo "<img src='" . $image . "' alt='Soin' class='" . $classe . "' style='width: 100%; height: auto;'>";
                    }
                } else {
                    echo "<p>Aucune image pour le moment.</p>";
                }
            ?>
        </div>
        
        <div class="diaporama">
            <?php
                // On demande à PHP de trouver toutes les images dans le dossier 'manicure-pedicure'
                $images_manicure_pedicure = glob("../image/Manicure-Pedicure/*.{jpg,jpeg,png,gif}", GLOB_BRACE);

                // S'il y a des images, on fait une boucle pour les afficher une par une
                if (count($images_manicure_pedicure) > 0) {
                    foreach($images_manicure_pedicure as $index => $image) {
                        $classe = ($index == 0) ? "slide active" : "slide";
                        echo "<img src='" . $image . "' alt='Soin' class='" . $classe . "' style='width: 100%; height: auto;'>";
                    }
                } else {
                    echo "<p>Aucune image pour le moment.</p>";
                }
            ?>
        </div>
    
        <div class="diaporama">
            <?php
                // On demande à PHP de trouver toutes les images dans le dossier 'premium'
                $images_premium = glob("../image/PREMIUM/*.{jpg,jpeg,png,gif}", GLOB_BRACE);

                // S'il y a des images, on fait une boucle pour les afficher une par une
                if (count($images_premium) > 0) {
                    foreach($images_premium as $index => $image) {
                        $classe = ($index == 0) ? "slide active" : "slide";
                        echo "<img src='" . $image . "' alt='Soin' class='" . $classe . "' style='width: 100%; height: auto;'>";
                    }
                } else {
                    echo "<p>Aucune image pour le moment.</p>";
                }
            ?>
        </div>
    </div>
    
    <script>
        // On cherche tous les diaporamas de la page (au cas où tu en fasses un pour les soins aussi)
        const diaporamas = document.querySelectorAll('.diaporama');

        diaporamas.forEach(diaporama => {
            // Dans chaque diaporama, on récupère toutes les images
            let slides = diaporama.querySelectorAll('.slide');
            let indexCourant = 0;

            // S'il y a plus d'une image, on lance le chronomètre
            if(slides.length > 1) {
                setInterval(() => {
                    // On cache l'image actuelle en lui retirant la classe 'active'
                    slides[indexCourant].classList.remove('active');
                    
                    // On calcule le numéro de l'image suivante (et on repart à zéro à la fin)
                    indexCourant = (indexCourant + 1) % slides.length;
                    
                    // On affiche la nouvelle image en lui donnant la classe 'active'
                    slides[indexCourant].classList.add('active');
                }, 3000); // 3000 millisecondes = changement toutes les 3 secondes
            }
        });

        document.querySelector('.contact-button').addEventListener('click', () => {
            window.location.href = 'galerie.php';
        });
    </script>
</body>
</html>