<?php
session_start();
session_destroy(); // Détruit le bracelet VIP
header("Location: admin_login.php"); // Renvoie à la page de connexion
exit();