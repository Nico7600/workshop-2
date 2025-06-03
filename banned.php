<?php
session_start();

require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$languages = ['en' => 'English', 'fr' => 'Français', 'nl' => 'Nederlands'];
$selected_lang = $_GET['lang'] ?? 'fr';
if (!array_key_exists($selected_lang, $languages)) {
    $selected_lang = 'fr';
}

$translations = [
    'en' => [
        'title' => 'Access Denied',
        'heading' => 'Access Denied',
        'message' => 'You have been banned from accessing this site. Please contact the administrator for more information.',
    ],
    'fr' => [
        'title' => 'Accès Refusé',
        'heading' => 'Accès Refusé',
        'message' => 'Vous avez été banni de ce site. Veuillez contacter l\'administrateur pour plus d\'informations.',
    ],
    'nl' => [
        'title' => 'Toegang Geweigerd',
        'heading' => 'Toegang Geweigerd',
        'message' => 'U bent verbannen van deze site. Neem contact op met de beheerder voor meer informatie.',
    ],
];

$trans = $translations[$selected_lang];
?>
<div class="banned-container">
    <div class="container">
        <h1><?= htmlspecialchars($trans['heading']) ?></h1>
        <div class="message-container">
            <p><?= htmlspecialchars($trans['message']) ?></p>
        </div>
    </div>
</div>
<style>
    .banned-container {
        display: flex;
        justify-content: center;
        align-items: center;
        height: 100vh;
        background-color: #f8f9fa; /* Matches the index theme */
    }
    .container {
        text-align: center;
        background-color: #e9ecef; /* Updated to match index theme */
        padding: 25px;
        border-radius: 10px;
        border: 1px solid #ced4da; /* Added border to match theme */
        box-shadow: 0 4px 8px rgba(0, 0, 0, 0.15); /* Slightly stronger shadow */
        max-width: 450px;
        width: 100%;
    }
    h1 {
        color: #dc3545; /* Updated to a softer red */
        margin-bottom: 15px;
        font-size: 1.8rem; /* Adjusted font size */
    }
    p {
        margin: 0;
        color: #495057; /* Updated text color */
        font-size: 1rem; /* Adjusted font size */
    }
    .message-container {
        margin-top: 15px;
        padding: 15px;
        background-color: #f8f9fa; /* Updated background color */
        border-radius: 8px;
        border: 1px solid #dee2e6; /* Added border for consistency */
    }
</style>
