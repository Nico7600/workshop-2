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

// Gestion de la langue
$lang = $_GET['lang'] ?? ($_COOKIE['lang'] ?? 'fr');
$available_languages = ['en', 'nl', 'fr'];
if (!in_array($lang, $available_languages)) {
    $lang = 'fr';
}

$translations = [
    'fr' => [
        'archived_tickets' => 'Tickets Archivés',
        'no_tickets' => 'Aucun ticket archivé trouvé.',
        'created_at' => 'Créé le',
    ],
    'en' => [
        'archived_tickets' => 'Archived Tickets',
        'no_tickets' => 'No archived tickets found.',
        'created_at' => 'Created at',
    ],
    'nl' => [
        'archived_tickets' => 'Gearchiveerde Tickets',
        'no_tickets' => 'Geen gearchiveerde tickets gevonden.',
        'created_at' => 'Aangemaakt op',
    ],
];

$text_ui = $translations[$lang];

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_GET['id'])) {
    $ticketId = (int)$_GET['id'];
    $userId = $_SESSION['user']['id'];

    try {
        // Fetch the ticket details
        $stmt = $db->prepare("SELECT * FROM ticket WHERE id = :id AND creator = :creator AND is_close = 0");
        $stmt->bindParam(':id', $ticketId, PDO::PARAM_INT);
        $stmt->bindParam(':creator', $userId, PDO::PARAM_INT);
        $stmt->execute();
        $ticket = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($ticket) {
            // Insert the ticket into the archive_ticket table
            $archiveStmt = $db->prepare("INSERT INTO archive_ticket (id, ticket_name, message, created_at, creator) VALUES (:id, :ticket_name, :message, :created_at, :creator)");
            $archiveStmt->bindParam(':id', $ticket['id'], PDO::PARAM_INT);
            $archiveStmt->bindParam(':ticket_name', $ticket['ticket_name'], PDO::PARAM_STR);
            $archiveStmt->bindParam(':message', $ticket['message'], PDO::PARAM_STR);
            $archiveStmt->bindParam(':created_at', $ticket['created_at'], PDO::PARAM_STR);
            $archiveStmt->bindParam(':creator', $ticket['creator'], PDO::PARAM_INT);
            $archiveStmt->execute();

            // Remove the ticket from the ticket table
            $deleteStmt = $db->prepare("DELETE FROM ticket WHERE id = :id");
            $deleteStmt->bindParam(':id', $ticketId, PDO::PARAM_INT);
            $deleteStmt->execute();

            http_response_code(200);
            exit("Ticket archived successfully");
        } else {
            http_response_code(404);
            exit("Ticket not found");
        }
    } catch (PDOException $e) {
        error_log("Database error: " . $e->getMessage());
        http_response_code(500);
        exit("Une erreur s'est produite lors de l'archivage du ticket.");
    } catch (Exception $e) {
        error_log("General error: " . $e->getMessage());
        http_response_code(500);
        exit("Une erreur inattendue s'est produite.");
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    try {
        // Récupérer tous les tickets archivés
        $stmt = $db->prepare("SELECT * FROM archive_ticket WHERE is_close = 1");
        if ($stmt->execute()) {
            $archivedTickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
        } else {
            throw new Exception("Erreur : Impossible de récupérer les tickets archivés.");
        }
    } catch (Exception $e) {
        http_response_code(500);
        echo $e->getMessage();
        exit;
    }
} else {
    http_response_code(400);
    echo "Erreur : Requête invalide.";
    exit;
}

include 'header.php';
?>

<!DOCTYPE html>
<html lang="<?= htmlspecialchars($lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($text_ui['archived_tickets']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <style>
        .font-orbitron {
            font-family: 'Orbitron', sans-serif;
        }
        .scrollable-container {
            max-height: calc(100vh - 100px); 
            overflow-y: auto;
        }
        .ticket-container {
            min-height: 112px;
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative; 
        }
        .ticket-container h2 {
            font-size: 1.5rem;
            position: relative; 
            text-align: left;
            margin: 0; 
        }
        .tickets-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr); 
            gap: 1.5rem; 
        }
    </style>
</head>
<body class="flex flex-col min-h-screen bg-gray-900 text-gray-100 font-orbitron">
    <div class="container mx-auto flex-grow p-6 font-orbitron h-full">
        <h1 class="text-3xl font-bold text-center mb-6"><?= htmlspecialchars($text_ui['archived_tickets']) ?></h1>
        <div class="scrollable-container">
            <?php if (empty($archivedTickets)): ?>
                <p class="text-center text-gray-400"><?= htmlspecialchars($text_ui['no_tickets']) ?></p>
            <?php else: ?>
                <div class="tickets-grid">
                    <?php foreach ($archivedTickets as $ticket): ?>
                        <div class="ticket-container bg-gray-800 border border-gray-700 rounded-lg p-6 shadow-lg hover:shadow-xl transition-shadow font-orbitron relative">
                            <h2 class="text-2xl font-semibold text-cyan-400 font-bold text-left">
                                <?= htmlspecialchars($ticket['ticket_name']) ?>
                            </h2>
                            <p class="text-sm text-gray-500 absolute bottom-2 left-4 text-left">
                                <?= htmlspecialchars($text_ui['created_at']) ?>: <?= date('d/m/Y à H:i:s', strtotime($ticket['created_at'])) ?>
                            </p>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>