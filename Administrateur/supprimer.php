<?php
// 1. SÉCURITÉ : Seul l'admin connecté peut supprimer
session_start();
if (!isset($_SESSION['admin_connecte']) || $_SESSION['admin_connecte'] !== true) {
    header("Location: admin_login.php");
    exit();
}

// 2. Vérification qu'un ID valide a été envoyé dans l'URL
if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id_rdv = intval($_GET['id']);

    // 3. CONNEXION BDD (Port 3307)
    $host = "127.0.0.1";
    $port = 3307;
    $user = "root";
    $password = "";
    $dbname = "golden_touch";

    $conn = new mysqli($host, $user, $password, $dbname, $port);

    if ($conn->connect_error) {
        die("Erreur de connexion : " . $conn->connect_error);
    }

    // 4. Requête SQL sécurisée pour supprimer la ligne
    $stmt = $conn->prepare("DELETE FROM reservations WHERE id = ?");
    $stmt->bind_param("i", $id_rdv);
    
    if ($stmt->execute()) {
        // Suppression réussie, on recharge le tableau de bord
        header("Location: dashboard.php?statut=supprime");
    } else {
        echo "Erreur lors de la suppression du rendez-vous.";
    }

    $stmt->close();
    $conn->close();
} else {
    // Si pas d'ID, retour direct
    header("Location: dashboard.php");
}
exit();