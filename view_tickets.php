<?php
session_start();
include 'db_connection.php';

if (!isset($db) || !$db) {
    die("Erreur : La connexion à la base de données n'a pas été établie.");
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

$lang = $_GET['lang'] ?? ($_COOKIE['lang'] ?? 'fr');
$available_languages = ['en', 'nl', 'fr'];
if (!in_array($lang, $available_languages)) {
    $lang = 'fr';}

$translations = [
    'fr' => [
        'my_tickets' => 'Mes Tickets',
        'title' => 'Titre',
        'message' => 'Message',
        'created_at' => 'Créé le',
        'no_tickets' => 'Aucun ticket trouvé.',
        'tickets_count' => [
            'one' => 'Vous avez actuellement 1 ticket ouvert.',
            'many' => 'Vous avez actuellement %d tickets ouverts.',
        ],
        'open' => 'Ouvrir',
        'archive' => 'Archiver',
    ],
    'en' => [
        'my_tickets' => 'My Tickets',
        'title' => 'Title',
        'message' => 'Message',
        'created_at' => 'Created At',
        'no_tickets' => 'No tickets found.',
        'tickets_count' => [
            'one' => 'You currently have 1 open ticket.',
            'many' => 'You currently have %d open tickets.',
        ],
        'open' => 'Open',
        'archive' => 'Archive',
    ],
    'nl' => [
        'my_tickets' => 'Mijn Tickets',
        'title' => 'Titel',
        'message' => 'Bericht',
        'created_at' => 'Aangemaakt op',
        'no_tickets' => 'Geen tickets gevonden.',
        'tickets_count' => [
            'one' => 'U heeft momenteel 1 open ticket.',
            'many' => 'U heeft momenteel %d open tickets.',
        ],
        'open' => 'Openen',
        'archive' => 'Archiveren',
    ],
];

$text_ui = $translations[$lang];

if (!isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user']['id'];

$itemsPerPage = 8; 
$page = isset($_GET['page']) && is_numeric($_GET['page']) ? (int)$_GET['page'] : 1;
$offset = ($page - 1) * $itemsPerPage;

$stmt = $db->prepare("SELECT COUNT(*) FROM ticket WHERE creator = :creator AND is_close = 0");
$stmt->bindParam(":creator", $userId, PDO::PARAM_INT);
$stmt->execute();
$totalTickets = $stmt->fetchColumn();

$stmt = $db->prepare("SELECT id, ticket_name, message, created_at FROM ticket WHERE creator = :creator AND is_close = 0 ORDER BY created_at DESC LIMIT :limit OFFSET :offset");
$stmt->bindParam(":creator", $userId, PDO::PARAM_INT);
$stmt->bindValue(":limit", $itemsPerPage, PDO::PARAM_INT);
$stmt->bindValue(":offset", $offset, PDO::PARAM_INT);
$stmt->execute();
$tickets = $stmt->fetchAll(PDO::FETCH_ASSOC);

include 'header.php';
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $text_ui['my_tickets'] ?></title>
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
        <h1 class="text-3xl font-bold text-center mb-6"><?= $text_ui['my_tickets'] ?></h1>
        <div class="scrollable-container">
            <?php if (empty($tickets)): ?>
                <p class="text-left text-gray-400"><?= $text_ui['no_tickets'] ?></p>
            <?php else: ?>
                <div class="tickets-grid">
                    <?php foreach ($tickets as $ticket): ?>
                        <div class="ticket-container bg-gray-800 border border-gray-700 rounded-lg p-6 shadow-lg hover:shadow-xl transition-shadow font-orbitron relative">
                            <h2 class="text-2xl font-semibold text-cyan-400 font-bold text-left">
                                <?= htmlspecialchars($ticket['ticket_name']) ?>
                            </h2>
                            <p class="text-sm text-gray-500 absolute bottom-2 left-4 text-left">
                                <?= $text_ui['created_at'] ?>: <?= date('d/m/Y à H:i:s', strtotime($ticket['created_at'])) ?>
                            </p>
                            <div class="absolute inset-y-0 right-4 flex items-center space-x-4">
                                <a href="open_ticket.php?id=<?= urlencode($ticket['id']) ?>" 
                                   class="text-white bg-green-700 hover:bg-green-800 focus:outline-none focus:ring-4 focus:ring-green-300 font-medium rounded-full text-sm px-6 py-3 text-center dark:bg-green-600 dark:hover:bg-green-700 dark:focus:ring-green-800">
                                    <i class="fas fa-folder-open"></i> <?= $text_ui['open'] ?>
                                </a>
                                <button onclick="confirmArchive(<?= htmlspecialchars($ticket['id']) ?>)" 
                                        class="text-white bg-red-700 hover:bg-red-800 focus:outline-none focus:ring-4 focus:ring-red-300 font-medium rounded-full text-sm px-6 py-3 text-center dark:bg-red-600 dark:hover:bg-red-700 dark:focus:ring-red-800">
                                    <i class="fas fa-archive"></i> <?= $text_ui['archive'] ?>
                                </button>
                            </div>
                        </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
        <div class="flex justify-center mt-6">
            <?php if ($page > 1): ?>
                <a href="?page=<?= $page - 1 ?>&lang=<?= $lang ?>" 
                   class="text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-6 py-3 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Précédent
                </a>
            <?php endif; ?>
            <?php if ($page * $itemsPerPage < $totalTickets): ?>
                <a href="?page=<?= $page + 1 ?>&lang=<?= $lang ?>" 
                   class="ml-4 text-white bg-blue-700 hover:bg-blue-800 focus:outline-none focus:ring-4 focus:ring-blue-300 font-medium rounded-full text-sm px-6 py-3 text-center dark:bg-blue-600 dark:hover:bg-blue-700 dark:focus:ring-blue-800">
                    Suivant
                </a>
            <?php endif; ?>
        </div>
        <p class="text-center text-gray-400 mt-4">
            <?php if ($totalTickets === 1): ?>
                <?= $text_ui['tickets_count']['one'] ?>
            <?php else: ?>
                <?= sprintf($text_ui['tickets_count']['many'], $totalTickets) ?>
            <?php endif; ?>
        </p>
    </div>
    <script>
        function confirmArchive(ticketId) {
            if (confirm("Êtes-vous sûr de vouloir archiver ce ticket ?")) {
                fetch(`archive_ticket.php?id=${ticketId}`, { method: 'POST' })
                    .then(response => {
                        if (response.ok) {
                            alert("Le ticket a été archivé avec succès.");
                            location.reload();
                        } else {
                            alert("Une erreur s'est produite lors de l'archivage du ticket.");
                        }
                    })
                    .catch(error => {
                        console.error("Erreur:", error);
                        alert("Une erreur s'est produite.");
                    });
            }
        }
    </script>
    <?php include 'footer.php'; ?>
</body>
</html>