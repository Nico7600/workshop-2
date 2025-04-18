<?php
session_start();
include 'db_connection.php';

if (!isset($db) || !$db) {
    die("Erreur : La connexion à la base de données n'a pas été établie.");
}

$lang = $_GET['lang'] ?? ($_COOKIE['lang'] ?? 'fr');
$available_languages = ['en', 'nl', 'fr'];
if (!in_array($lang, $available_languages)) {
    $lang = 'fr';
}

$translations = [
    'fr' => [
        'my_tickets' => 'Mes Tickets',
        'title' => 'Titre',
        'message' => 'Message',
        'created_at' => 'Créé le',
        'no_tickets' => 'Aucun ticket trouvé.',
    ],
    'en' => [
        'my_tickets' => 'My Tickets',
        'title' => 'Title',
        'message' => 'Message',
        'created_at' => 'Created At',
        'no_tickets' => 'No tickets found.',
    ],
    'nl' => [
        'my_tickets' => 'Mijn Tickets',
        'title' => 'Titel',
        'message' => 'Bericht',
        'created_at' => 'Aangemaakt op',
        'no_tickets' => 'Geen tickets gevonden.',
    ],
];

$text_ui = $translations[$lang];

if (!isset($_SESSION['user']['id'])) {
    header("Location: login.php");
    exit;
}

$userId = $_SESSION['user']['id'];

$stmt = $db->prepare("SELECT ticket_name, message, created_at FROM ticket WHERE creator = :creator ORDER BY created_at DESC");
$stmt->bindParam(":creator", $userId, PDO::PARAM_INT);
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
    </style>
</head>
<body class="flex flex-col min-h-screen bg-gray-900 text-gray-100 font-orbitron">
    <div class="container mx-auto flex-grow p-6 font-orbitron">
        <h1 class="text-3xl font-bold text-center mb-6"><?= $text_ui['my_tickets'] ?></h1>
        <?php if (empty($tickets)): ?>
            <p class="text-center text-gray-400"><?= $text_ui['no_tickets'] ?></p>
        <?php else: ?>
            <?php foreach ($tickets as $ticket): ?>
                <div class="bg-gray-800 border border-gray-700 rounded-lg p-4 mb-4 shadow-lg hover:shadow-xl transition-shadow font-orbitron relative">
                    <h2 class="text-xl font-semibold text-cyan-400 text-center font-bold underline"><?= htmlspecialchars($ticket['ticket_name']) ?></h2>
                    <p class="mt-2 text-gray-300 text-center"><?= htmlspecialchars($ticket['message']) ?></p>
                    <p class="text-sm text-gray-500 absolute bottom-2 left-2">
                        <?= $text_ui['created_at'] ?>: <?= date('d/m/Y à H:i:s', strtotime($ticket['created_at'])) ?>
                    </p>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>