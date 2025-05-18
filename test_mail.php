<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once __DIR__ . '/mail_utils.php';

// Test direct de la fonction
sendTicketNotification(
    'ton_email@domaine.com',
    'Test direct depuis test_mail.php',
    'Ceci est un test manuel.',
    1 // ID utilisateur existant
);

echo "✅ Mail envoyé (si aucune erreur).";
