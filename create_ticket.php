<?php
include 'header.php';

session_start();

$lang = $_GET['lang'] ?? 'fr';
$available_languages = ['en', 'nl', 'fr'];
if (!in_array($lang, $available_languages)) {
    $lang = 'fr';
}

$translations = [
    'en' => [
        'create_ticket' => 'Create a New Ticket',
        'title' => 'Title:',
        'description' => 'Description:',
        'priority' => 'Priority:',
        'low' => 'Low',
        'medium' => 'Medium',
        'high' => 'High',
        'submit' => 'Submit',
    ],
    'nl' => [
        'create_ticket' => 'Maak een Nieuw Ticket',
        'title' => 'Titel:',
        'description' => 'Beschrijving:',
        'priority' => 'Prioriteit:',
        'low' => 'Laag',
        'medium' => 'Gemiddeld',
        'high' => 'Hoog',
        'submit' => 'Indienen',
    ],
    'fr' => [
        'create_ticket' => 'Créer un Nouveau Ticket',
        'title' => 'Titre:',
        'description' => 'Description:',
        'priority' => 'Priorité:',
        'low' => 'Faible',
        'medium' => 'Moyenne',
        'high' => 'Haute',
        'submit' => 'Envoyer',
    ],
];
$text = $translations[$lang];
?>

<!DOCTYPE html>
<html lang="<?= $lang ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $text['create_ticket'] ?></title>
    <!-- Tailwind CSS Integration -->
    <script src="https://cdn.tailwindcss.com"></script>
    <!-- Font Awesome Integration -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <!-- Orbitron Font Integration -->
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <!-- Custom CSS for consistent styling -->
    <link rel="stylesheet" href="style.css">
    <style>
        body {
            font-family: 'Orbitron', sans-serif;
        }
        .fade-in {
            animation: fadeIn 1s ease-in-out;
        }
        @keyframes fadeIn {
            from {
                opacity: 0;
            }
            to {
                opacity: 1;
            }
        }
    </style>
</head>
<body class="bg-gray-900 text-white fade-in">
    <div class="container mx-auto p-6 fade-in">
        <h1 class="text-3xl font-bold text-center mb-6 fade-in">
            <i class="fas fa-ticket-alt"></i> <?= $text['create_ticket'] ?>
        </h1>
        <form action="submit_ticket.php" method="POST" class="bg-gray-800 p-8 rounded-lg shadow-lg fade-in">
            <input type="hidden" name="creator" value="<?= htmlspecialchars($user_name) ?>">
            <div class="mb-6 fade-in">
                <label for="creator_name" class="block text-lg font-medium mb-2">
                    <i class="fas fa-user"></i> Nom:
                </label>
                <input type="text" id="creator_name" name="creator_name" class="w-full border border-gray-700 rounded-lg px-4 py-2 bg-gray-700 text-white focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-6 fade-in">
                <label for="ticket_name" class="block text-lg font-medium mb-2">
                    <i class="fas fa-heading"></i> <?= $text['title'] ?>
                </label>
                <input type="text" id="ticket_name" name="ticket_name" class="w-full border border-gray-700 rounded-lg px-4 py-2 bg-gray-700 text-white focus:ring-2 focus:ring-blue-500" required>
            </div>
            <div class="mb-6 fade-in">
                <label for="message" class="block text-lg font-medium mb-2">
                    <i class="fas fa-align-left"></i> <?= $text['description'] ?>
                </label>
                <textarea id="message" name="message" rows="5" class="w-full border border-gray-700 rounded-lg px-4 py-2 bg-gray-700 text-white focus:ring-2 focus:ring-blue-500" required></textarea>
            </div>
            <button type="submit" class="w-full bg-blue-500 text-white text-lg font-medium px-6 py-3 rounded-lg hover:bg-blue-600 focus:ring-2 focus:ring-blue-500 fade-in">
                <i class="fas fa-paper-plane"></i> <?= $text['submit'] ?>
            </button>
        </form>
    </div>
</body>
</html>
