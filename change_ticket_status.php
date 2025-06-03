<?php
session_start();
include 'db_connection.php';

if (!isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user']['id'];
$roleId = $_SESSION['user']['role_id'] ?? null;

if (!$roleId) {
    header("Location: view_tickets.php");
    exit;
}

$stmt = $db->prepare("SELECT can_view_all_tickets FROM role WHERE id = :role_id");
$stmt->bindParam(":role_id", $roleId, PDO::PARAM_INT);
$stmt->execute();
$canViewAll = (bool)$stmt->fetchColumn();

if (!$canViewAll) {
    header("Location: view_tickets.php");
    exit;
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['ticket_id'], $_POST['status'])) {
    $ticketId = (int)$_POST['ticket_id'];
    $status = $_POST['status'];
    $allowed = ['pending', 'in_progress', 'taken', 'validated', 'refused'];
    if (in_array($status, $allowed)) {
        $stmt = $db->prepare("UPDATE ticket SET status = :status WHERE id = :ticket_id");
        $stmt->bindParam(":status", $status, PDO::PARAM_STR);
        $stmt->bindParam(":ticket_id", $ticketId, PDO::PARAM_INT);
        $stmt->execute();

        // Envoi de mail à l'utilisateur
        require_once __DIR__ . '/mail_utils.php';
        $ticketInfoStmt = $db->prepare("SELECT creator FROM ticket WHERE id = :ticket_id");
        $ticketInfoStmt->bindParam(":ticket_id", $ticketId, PDO::PARAM_INT);
        $ticketInfoStmt->execute();
        $creatorId = $ticketInfoStmt->fetchColumn();
        if ($creatorId) {
            $userMailStmt = $db->prepare("SELECT email FROM users WHERE id = :id");
            $userMailStmt->bindParam(":id", $creatorId, PDO::PARAM_INT);
            $userMailStmt->execute();
            $userMail = $userMailStmt->fetchColumn();
            if ($userMail) {
                sendTicketNotification(
                    $userMail,
                    "Changement de statut du ticket #$ticketId",
                    $ticketId,
                    $userId
                );
            }
        }
    }
}

header("Location: view_tickets.php");
exit;
