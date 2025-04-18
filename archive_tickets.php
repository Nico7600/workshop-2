<?php
session_start();
include 'db_connection.php';

if (!isset($db) || !$db) {
    http_response_code(500);
    echo "Erreur : La connexion à la base de données n'a pas été établie.";
    exit;
}

if (!isset($_SESSION['user']['id'])) {
    http_response_code(403);
    echo "Erreur : Vous devez être connecté pour effectuer cette action.";
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $ticketId = intval($_GET['id']);
    $userId = $_SESSION['user']['id'];

    // Mise à jour du ticket pour l'archiver
    $stmt = $db->prepare("UPDATE ticket SET is_close = 1, close_at = NOW(), close_by = :close_by WHERE id = :id AND creator = :creator");
    $stmt->bindParam(":id", $ticketId, PDO::PARAM_INT);
    $stmt->bindParam(":close_by", $userId, PDO::PARAM_INT);
    $stmt->bindParam(":creator", $userId, PDO::PARAM_INT);

    if ($stmt->execute()) {
        http_response_code(200);
        echo "Le ticket a été archivé avec succès.";
    } else {
        http_response_code(500);
        echo "Erreur : Impossible d'archiver le ticket.";
    }
} else {
    http_response_code(400);
    echo "Erreur : Requête invalide.";
}
?>
