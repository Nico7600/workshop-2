<?php
require_once __DIR__ . '/vendor/autoload.php';

use PHPMailer\PHPMailer\PHPMailer;

echo class_exists(PHPMailer::class)
    ? "✅ PHPMailer chargé avec succès"
    : "❌ PHPMailer introuvable";
