<?php
session_start();

require_once 'DatabaseConnection.php';

try {
    $pdo = DatabaseConnection::getInstance()->getConnection();
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}

try {
    $stmt = $pdo->query('SELECT 1 FROM users LIMIT 1');
} catch (PDOException $e) {
    die('Erreur : La table "users" n\'est pas accessible. ' . $e->getMessage());
}

$translations = [
    'fr' => [
        'title' => 'Modifier le profil',
        'name' => 'Nom',
        'email' => 'Email',
        'phone' => 'Téléphone',
        'profile_picture' => 'Image de profil',
        'current_password' => 'Mot de passe actuel',
        'new_password' => 'Nouveau mot de passe',
        'update' => 'Mettre à jour',
        'current_password_incorrect' => 'Mot de passe actuel incorrect.',
        'profile_updated' => 'Profil mis à jour avec succès.',
        'profile_update_error' => 'Erreur lors de la mise à jour du profil : ',
        'statistics' => 'Statistiques',
        'total_tickets' => 'Tickets Total',
        'open_tickets' => 'Tickets Ouverts',
        'closed_tickets' => 'Tickets Fermés',
        'last_3_open_tickets' => 'Mes 3 derniers tickets ouverts',
        'no_open_ticket' => 'Aucun ticket ouvert.',
        'open' => 'Ouvrir',
        'opened' => 'Ouvert',
    ],
    'en' => [
        'title' => 'Edit Profile',
        'name' => 'Name',
        'email' => 'Email',
        'phone' => 'Phone',
        'profile_picture' => 'Profile Picture',
        'current_password' => 'Current Password',
        'new_password' => 'New Password',
        'update' => 'Update',
        'current_password_incorrect' => 'Current password is incorrect.',
        'profile_updated' => 'Profile updated successfully.',
        'profile_update_error' => 'Error updating profile: ',
        'statistics' => 'Statistics',
        'total_tickets' => 'Total Tickets',
        'open_tickets' => 'Open Tickets',
        'closed_tickets' => 'Closed Tickets',
        'last_3_open_tickets' => 'My last 3 open tickets',
        'no_open_ticket' => 'No open ticket.',
        'open' => 'Open',
        'opened' => 'Opened',
    ],
    'nl' => [
        'title' => 'Profiel bewerken',
        'name' => 'Naam',
        'email' => 'E-mail',
        'phone' => 'Telefoon',
        'profile_picture' => 'Profielfoto',
        'current_password' => 'Huidig wachtwoord',
        'new_password' => 'Nieuw wachtwoord',
        'update' => 'Bijwerken',
        'current_password_incorrect' => 'Huidig wachtwoord is onjuist.',
        'profile_updated' => 'Profiel succesvol bijgewerkt.',
        'profile_update_error' => 'Fout bij het bijwerken van het profiel: ',
        'statistics' => 'Statistieken',
        'total_tickets' => 'Totaal Tickets',
        'open_tickets' => 'Open Tickets',
        'closed_tickets' => 'Gesloten Tickets',
        'last_3_open_tickets' => 'Mijn laatste 3 open tickets',
        'no_open_ticket' => 'Geen open ticket.',
        'open' => 'Openen',
        'opened' => 'Open',
    ],
];

$lang = $_GET['lang'] ?? 'fr';
if (!array_key_exists($lang, $translations)) {
    $lang = 'fr';
}
$t = $translations[$lang];

include 'header.php';

$currentUser = null;
if (isset($_SESSION['user']['id'])) {
    try {
        $stmt = $pdo->prepare('
            SELECT 
                id, 
                name, 
                email, 
                phone, 
                profile_picture, 
                ticket_count, 
                open_ticket_count, 
                closed_ticket_count
            FROM users
            WHERE id = :id
        ');
        $stmt->execute(['id' => $_SESSION['user']['id']]);
        $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Erreur lors de la récupération des informations utilisateur : ' . $e->getMessage();
    }
}

$userCredentials = null;
if (isset($_SESSION['user']['id'])) {
    try {
        $stmt = $pdo->prepare('SELECT name, password FROM users WHERE id = :id');
        $stmt->execute(['id' => $_SESSION['user']['id']]);
        $userCredentials = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Erreur lors de la récupération des informations de connexion : ' . $e->getMessage();
    }
}

$alert = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE name = :name');
        $stmt->execute(['name' => $username]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Erreur lors de la récupération de l\'utilisateur : ' . $e->getMessage();
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update_profile'])) {
    $newUsername = $_POST['name'] ?? '';
    $newEmail = $_POST['email'] ?? '';
    $newPhone = $_POST['phone'] ?? '';
    $profilePicture = $_FILES['profile_picture'] ?? null;
    $currentPassword = $_POST['current_password'] ?? '';
    $newPassword = $_POST['password'] ?? '';

    try {
        // Vérification du mot de passe actuel
        $stmt = $pdo->prepare('SELECT password, profile_picture FROM users WHERE id = :user_id');
        $stmt->execute(['user_id' => $_SESSION['user']['id']]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        // Gestion des erreurs de saisie
        $errors = [];
        if (empty($newUsername)) {
            $errors[] = $t['name'] . ' est requis.';
        }
        if (empty($newEmail)) {
            $errors[] = $t['email'] . ' est requis.';
        } elseif (!filter_var($newEmail, FILTER_VALIDATE_EMAIL)) {
            $errors[] = $t['email'] . ' invalide.';
        }
        if (!empty($newPhone) && !preg_match('/^\+?[0-9]{7,15}$/', $newPhone)) {
            $errors[] = $t['phone'] . ' invalide.';
        }
        if (empty($currentPassword)) {
            $errors[] = $t['current_password'] . ' est requis.';
        }

        if (!empty($errors)) {
            $alert = '<div class="alert alert-error px-4 py-2 rounded mb-4 text-center">' . implode('<br>', array_map('htmlspecialchars', $errors)) . '</div>';
            return;
        }

        if (!$user || !password_verify($currentPassword, $user['password'])) {
            $alert = '<div class="alert alert-error px-4 py-2 rounded mb-4 text-center">' . $t['current_password_incorrect'] . '</div>';
            return;
        }

        $updateQuery = 'UPDATE users SET ';
        $params = [];

        if ($newUsername) {
            $updateQuery .= 'name = :new_name, ';
            $params['new_name'] = $newUsername;
        }

        if ($newEmail) {
            $updateQuery .= 'email = :new_email, ';
            $params['new_email'] = $newEmail;
        }

        if ($newPhone) {
            $updateQuery .= 'phone = :new_phone, ';
            $params['new_phone'] = $newPhone;
        }

        // Gestion de l'upload de la photo de profil avec nom du compte
        $profilePicturePath = $user['profile_picture'] ?? 'uploads/default_profile.png';
        if ($profilePicture && $profilePicture['error'] === UPLOAD_ERR_OK) {
            $uploadDir = __DIR__ . '/uploads/';
            $extension = strtolower(pathinfo($profilePicture['name'], PATHINFO_EXTENSION));
            // Nettoyer le nom d'utilisateur pour le nom de fichier
            $safeName = preg_replace('/[^a-zA-Z0-9_\-]/', '_', $newUsername ?: $user['name']);
            $uniqueName = $safeName . '.' . $extension;
            $uploadFile = $uploadDir . $uniqueName;
            // Supprimer l'ancienne photo si elle existe et n'est pas la par défaut
            if (!empty($user['profile_picture']) && 
                file_exists(__DIR__ . '/' . $user['profile_picture']) && 
                $user['profile_picture'] !== 'uploads/default_profile.png') {
                unlink(__DIR__ . '/' . $user['profile_picture']);
            }
            if (move_uploaded_file($profilePicture['tmp_name'], $uploadFile)) {
                $profilePicturePath = 'uploads/' . $uniqueName;
                $updateQuery .= 'profile_picture = :profile_picture, ';
                $params['profile_picture'] = $profilePicturePath;
            }
        } elseif (empty($user['profile_picture']) || !file_exists(__DIR__ . '/' . $user['profile_picture'])) {
            // Si pas d'image, mettre l'image par défaut
            $profilePicturePath = 'uploads/default_profile.png';
            $updateQuery .= 'profile_picture = :profile_picture, ';
            $params['profile_picture'] = $profilePicturePath;
        }

        if (!empty($newPassword)) {
            $hashedPassword = password_hash($newPassword, PASSWORD_DEFAULT);
            $updateQuery .= 'password = :password, ';
            $params['password'] = $hashedPassword;
        }

        $updateQuery = rtrim($updateQuery, ', ') . ' WHERE id = :user_id';
        $params['user_id'] = $_SESSION['user']['id'];

        $stmt = $pdo->prepare($updateQuery);
        $stmt->execute($params);

        $alert = '<div class="alert alert-success bg-green-600 text-white px-4 py-2 rounded mb-4 text-center">' . $t['profile_updated'] . '</div>';
    } catch (PDOException $e) {
        $alert = '<div class="alert alert-error px-4 py-2 rounded mb-4 text-center">' . $t['profile_update_error'] . htmlspecialchars($e->getMessage()) . '</div>';
    }
}

$userOpenTickets = [];
if (isset($_SESSION['user']['id'])) {
    try {
        $stmt = $pdo->prepare('SELECT id, ticket_name, created_at FROM ticket WHERE creator = :user_id AND (is_closed = 0 OR is_close = 0) ORDER BY created_at DESC LIMIT 3');
        $stmt->execute(['user_id' => $_SESSION['user']['id']]);
        $userOpenTickets = $stmt->fetchAll(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        // Optionally handle error
    }
}
?>
<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($lang); ?>">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MyTicket : <?php echo htmlspecialchars($t['title']); ?></title>
    <link rel="icon" type="image/png" href="uploads/logo.png">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #1a202c;
            font-family: 'Orbitron', sans-serif;
            padding: 0 !important;
        }

        .card {
            transition: transform 0.3s ease, box-shadow 0.3s ease;
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 10px;
        }

        .card:hover {
            transform: translateY(-10px);
            box-shadow: 0 10px 20px rgba(0, 0, 0, 0.3);
        }

        .form-container {
            background-color: #2d3748;
            padding: 2rem;
            border-radius: 0.5rem;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            display: flex;
            flex-direction: column;
            justify-content: stretch;
            height: 100%;
            width: 100%;
            min-width: 0;
            margin-left: 0 !important;
            margin-right: 0 !important;
        }

        .form-container h1 {
            color: #63b3ed;
        }

        .form-input {
            padding: 0.75rem;
            font-size: 1rem;
        }

        .alert-success {
            border-left: 6px solid #38a169;
        }
        .alert-error {
            border-left: 6px solid #e53e3e;
        }
        .profile-pic-container {
            position: relative;
            display: inline-block;
            cursor: pointer;
        }
        .profile-pic-container input[type="file"] {
            display: none;
        }
        .profile-pic-overlay {
            position: absolute;
            top: 0; left: 0; right: 0; bottom: 0;
            background: rgba(30,41,59,0.65);
            color: #fff;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 9999px;
            opacity: 0;
            transition: opacity 0.2s, background 0.2s;
            font-size: 1.2rem;
            font-weight: 600;
            pointer-events: none;
        }
        .profile-pic-container:hover .profile-pic-overlay {
            opacity: 1;
            pointer-events: auto;
        }
        .profile-pic-container:hover img {
            filter: blur(1.5px) brightness(0.7);
            transition: filter 0.2s;
        }
        .stat-card {
            transition: transform 0.2s, box-shadow 0.2s;
        }
        .stat-card:hover {
            transform: scale(1.05);
            box-shadow: 0 8px 24px rgba(0,0,0,0.25);
        }
        .input-icon {
            position: absolute;
            left: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
        }
        .input-with-icon input {
            padding-left: 2.5rem;
        }
        .toggle-password {
            position: absolute;
            right: 0.75rem;
            top: 50%;
            transform: translateY(-50%);
            cursor: pointer;
            color: #a0aec0;
        }

        @media (min-width: 768px) {
            .flex-row > .form-container,
            .form-container {
                height: 100%;
                min-height: 100%;
            }
            .w-full {
                max-width: 100% !important;
            }
        }
    </style>
</head>

<body class="text-gray-100 flex flex-col min-h-screen">
    <div class="flex-grow">
        <!-- Ajout de h-full et items-stretch pour aligner les hauteurs -->
        <div class="w-full mt-10 flex flex-col md:flex-row gap-10 px-0 md:px-8 items-stretch h-full" style="max-width:100%;">
            <!-- Statistiques à gauche élargi -->
            <div class="md:w-3/5 w-full flex flex-col gap-8 mb-4 md:mb-0 h-full">
                <div class="form-container h-full" style="min-height:unset; padding:1.2rem;">
                    <h1 class="text-3xl font-bold mb-6 text-center tracking-wide flex items-center justify-center gap-3">
                        <i class="fas fa-chart-bar text-cyan-400"></i>
                        <?php echo htmlspecialchars($t['statistics']); ?>
                    </h1>
                    <div id="stats-section" class="grid grid-cols-1 md:grid-cols-3 gap-6">
                        <div class="bg-gradient-to-br from-cyan-700 via-cyan-800 to-gray-900 p-6 rounded-2xl shadow-xl text-center stat-card flex flex-col items-center border border-cyan-500/30 hover:scale-105 transition-transform duration-200">
                            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-cyan-900 mb-2 shadow-inner">
                                <i class="fas fa-ticket-alt text-cyan-300 text-2xl"></i>
                            </div>
                            <p id="stat-total" class="text-3xl font-extrabold text-cyan-100 mb-1"><?php echo htmlspecialchars($currentUser['ticket_count'] ?? 0); ?></p>
                            <span class="text-cyan-300 font-semibold text-base"><?php echo htmlspecialchars($t['total_tickets']); ?></span>
                        </div>
                        <div class="bg-gradient-to-br from-green-700 via-green-800 to-gray-900 p-6 rounded-2xl shadow-xl text-center stat-card flex flex-col items-center border border-green-500/30 hover:scale-105 transition-transform duration-200">
                            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-green-900 mb-2 shadow-inner">
                                <i class="fas fa-folder-open text-green-300 text-2xl"></i>
                            </div>
                            <p id="stat-open" class="text-3xl font-extrabold text-green-100 mb-1"><?php echo htmlspecialchars($currentUser['open_ticket_count'] ?? 0); ?></p>
                            <span class="text-green-300 font-semibold text-base"><?php echo htmlspecialchars($t['open_tickets']); ?></span>
                        </div>
                        <div class="bg-gradient-to-br from-red-700 via-red-800 to-gray-900 p-6 rounded-2xl shadow-xl text-center stat-card flex flex-col items-center border border-red-500/30 hover:scale-105 transition-transform duration-200">
                            <div class="flex items-center justify-center w-12 h-12 rounded-full bg-red-900 mb-2 shadow-inner">
                                <i class="fas fa-check-circle text-red-200 text-2xl"></i>
                            </div>
                            <p id="stat-closed" class="text-3xl font-extrabold text-red-100 mb-1"><?php echo htmlspecialchars($currentUser['closed_ticket_count'] ?? 0); ?></p>
                            <span class="text-red-200 font-semibold text-base"><?php echo htmlspecialchars($t['closed_tickets']); ?></span>
                        </div>
                    </div>
                </div>
                <!-- Nouveau conteneur : Mes 3 derniers tickets ouverts, amélioré et centré -->
                <div class="form-container flex flex-col items-center justify-center mb-12 h-full" style="min-height:unset; padding:1.2rem;">
                    <h2 class="text-2xl font-bold mb-4 text-blue-300 tracking-wide flex items-center gap-2 justify-center">
                        <i class="fas fa-history text-blue-400"></i>
                        <?php echo htmlspecialchars($t['last_3_open_tickets']); ?>
                    </h2>
                    <div class="max-h-64 overflow-y-auto w-full md:w-[95%]">
                        <?php if (empty($userOpenTickets)) : ?>
                            <div class="text-gray-400 italic text-center py-4 flex items-center justify-center gap-2">
                                <i class="fas fa-info-circle text-blue-400"></i>
                                <?php echo htmlspecialchars($t['no_open_ticket']); ?>
                            </div>
                        <?php else: ?>
                            <ul class="space-y-3">
                                <?php foreach ($userOpenTickets as $ticket): ?>
                                    <li class="bg-gray-800 rounded-lg px-4 py-3 flex flex-col gap-1 hover:bg-blue-800 transition group border border-blue-700/30 shadow-sm">
                                        <div class="flex flex-col gap-1 w-full">
                                            <div class="flex items-center gap-3">
                                                <span class="flex items-center justify-center w-8 h-8 rounded-full bg-blue-700 shadow-inner mr-2">
                                                    <i class="fas fa-ticket-alt text-blue-200"></i>
                                                </span>
                                                <span class="font-semibold text-blue-200 text-base truncate" title="<?php echo htmlspecialchars($ticket['ticket_name']); ?>">
                                                    <?php echo htmlspecialchars($ticket['ticket_name']); ?>
                                                </span>
                                                <span class="text-xs text-gray-400 flex items-center gap-1 ml-4 justify-center w-40 text-center">
                                                    <i class="far fa-calendar-alt"></i>
                                                    <?php echo date('d/m/Y H:i', strtotime($ticket['created_at'])); ?>
                                                </span>
                                                <span class="text-xs text-blue-300 flex items-center gap-1 ml-4">
                                                    <i class="fas fa-folder-open"></i>
                                                    <?php echo htmlspecialchars($t['opened']); ?>
                                                </span>
                                                <span class="flex-1 flex justify-end items-center ml-4">
                                                    <a href="open_ticket.php?id=<?php echo urlencode($ticket['id']); ?>" class="inline-block bg-blue-600 hover:bg-blue-700 text-white text-xs font-bold py-1 px-3 rounded transition shadow group-hover:scale-105 flex items-center h-8" target="_blank" rel="noopener">
                                                        <i class="fas fa-folder-open"></i> <span class="ml-1"><?php echo htmlspecialchars($t['open']); ?></span>
                                                    </a>
                                                </span>
                                            </div>
                                        </div>
                                    </li>
                                <?php endforeach; ?>
                            </ul>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <!-- Profil à droite élargi -->
            <div class="md:w-2/5 w-full flex flex-col justify-stretch form-container mb-10 h-full" style="min-height:100%;">
                <h1 class="text-3xl font-bold mb-8 text-center flex items-center justify-center gap-3">
                    <i class="fas fa-user-circle text-blue-400"></i>
                    <?php echo htmlspecialchars($t['title']); ?>
                </h1>
                <!-- Notification positionnée en haut à droite -->
                <div id="alert-container" style="position: fixed; top: 2rem; right: 2rem; z-index: 9999; min-width: 320px; max-width: 90vw;">
                    <?php if (!empty($alert)) : 
                        // On retire le <div class="alert ..."> de $alert s'il existe déjà pour éviter le double cadre
                        if (preg_match('/^<div class="alert.*?">(.*?)<\/div>$/is', $alert, $matches)) {
                            $alertContent = $matches[1];
                            $alertClass = '';
                            if (strpos($alert, 'alert-success') !== false) $alertClass .= ' alert-success';
                            if (strpos($alert, 'alert-error') !== false) $alertClass .= ' alert-error';
                    ?>
                        <div class="alert<?php echo $alertClass; ?>">
                            <span class="alert-close" onclick="closeAlert(this)" style="position:absolute;top:0.5rem;right:1rem;cursor:pointer;font-size:1.5rem;">&times;</span>
                            <?php echo $alertContent; ?>
                        </div>
                    <?php } else { ?>
                        <div class="alert">
                            <span class="alert-close" onclick="closeAlert(this)" style="position:absolute;top:0.5rem;right:1rem;cursor:pointer;font-size:1.5rem;">&times;</span>
                            <?php echo $alert; ?>
                        </div>
                    <?php }
                    endif; ?>
                </div>
                <script>
                function closeAlert(el) {
                    const alert = el.closest('.alert');
                    if (alert) alert.remove();
                }
                // Fermeture automatique après 10s
                window.addEventListener('DOMContentLoaded', function() {
                    const alert = document.querySelector('#alert-container .alert');
                    if (alert) {
                        setTimeout(function() {
                            if (alert && document.body.contains(alert)) {
                                alert.style.transition = "opacity 0.5s";
                                alert.style.opacity = 0;
                                setTimeout(() => { if (alert.parentNode) alert.remove(); }, 600);
                            }
                        }, 10000);
                    }
                });
                </script>
                <form id="profile-form" action="" method="POST" enctype="multipart/form-data" class="space-y-8" autocomplete="off">
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-300"><?php echo htmlspecialchars($t['name']); ?></label>
                            <div class="relative input-with-icon">
                                <i class="fas fa-user input-icon"></i>
                                <input type="text" name="name" id="name" value="<?php echo htmlspecialchars($currentUser['name'] ?? ''); ?>" class="form-input mt-1 block w-full bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-300"><?php echo htmlspecialchars($t['email']); ?></label>
                            <div class="relative input-with-icon">
                                <i class="fas fa-envelope input-icon"></i>
                                <input type="email" name="email" id="email" value="<?php echo htmlspecialchars($currentUser['email'] ?? ''); ?>" class="form-input mt-1 block w-full bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                            </div>
                        </div>
                        <div>
                            <label for="phone" class="block text-sm font-medium text-gray-300"><?php echo htmlspecialchars($t['phone']); ?></label>
                            <div class="relative input-with-icon">
                                <i class="fas fa-phone input-icon"></i>
                                <input 
                                    type="tel" 
                                    name="phone" 
                                    id="phone" 
                                    value="<?php echo htmlspecialchars($currentUser['phone'] ?? ''); ?>" 
                                    class="form-input mt-1 block w-full bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                                    pattern="^\+?[0-9]{7,15}$"
                                    title="Veuillez entrer un numéro de téléphone valide (chiffres uniquement, 7 à 15 chiffres, peut commencer par +)">
                            </div>
                        </div>
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-300"><?php echo htmlspecialchars($t['new_password']); ?></label>
                            <div class="relative input-with-icon">
                                <i class="fas fa-key input-icon"></i>
                                <input type="password" name="password" id="password" placeholder="<?php echo htmlspecialchars($t['new_password']); ?>" class="form-input mt-1 block w-full bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                                <span class="toggle-password" onclick="togglePassword('password', this)"><i class="fas fa-eye"></i></span>
                            </div>
                        </div>
                    </div>
                    <!-- Placement de l'image de profil centré et sur toute la largeur -->
                    <div class="w-full flex flex-col items-center justify-center mb-0">
                        <label for="profile_picture" class="block text-sm font-medium text-gray-300 mb-2"><?php echo htmlspecialchars($t['profile_picture']); ?></label>
                        <?php
                            $profilePic = $currentUser['profile_picture'] ?? '';
                            if (empty($profilePic) || !file_exists(__DIR__ . '/' . $profilePic)) {
                                $profilePic = 'uploads/default_profile.png';
                            }
                        ?>
                        <label for="profile_picture" class="profile-pic-container mb-1 mx-auto w-fit cursor-pointer" style="cursor:pointer;">
                            <img src="<?php echo htmlspecialchars($profilePic); ?>" alt="<?php echo htmlspecialchars($t['profile_picture']); ?>" class="w-28 h-28 rounded-full border-4 border-blue-400 shadow-lg object-cover transition duration-200">
                            <div class="profile-pic-overlay flex flex-col items-center justify-center">
                                <i class="fas fa-image text-2xl mb-1"></i>
                                <span class="text-xs font-semibold">Changer</span>
                            </div>
                            <input type="file" name="profile_picture" id="profile_picture" class="hidden" accept="image/*">
                        </label>
                        <div id="upload-progress" class="hidden mt-2 w-full max-w-xs">
                            <div class="w-full bg-gray-600 rounded-full h-2.5">
                                <div class="bg-blue-500 h-2.5 rounded-full" style="width: 0%;" id="progress-bar"></div>
                            </div>
                        </div>
                    </div>
                    <hr class="my-1 border-gray-600">
                    <div class="flex justify-center">
                        <div class="w-full">
                            <label for="current_password" class="block text-sm font-medium text-gray-300 text-center"><?php echo htmlspecialchars($t['current_password']); ?></label>
                            <div class="relative input-with-icon">
                                <i class="fas fa-lock input-icon"></i>
                                <input type="password" name="current_password" id="current_password" placeholder="<?php echo htmlspecialchars($t['current_password']); ?>" class="form-input mt-1 block w-full bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm text-center" required>
                                <span class="toggle-password" onclick="togglePassword('current_password', this)"><i class="fas fa-eye"></i></span>
                            </div>
                        </div>
                    </div>
                    <div>
                        <button type="submit" name="update_profile" class="w-full bg-gradient-to-r from-green-400 to-blue-500 text-white py-2 px-4 rounded-md hover:from-green-500 hover:to-blue-600 font-bold shadow-lg transition-all duration-200"><?php echo htmlspecialchars($t['update']); ?></button>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
    <style>
        /* Style amélioré pour la notification */
        #alert-container {
            top: 5.5rem !important;
            position: fixed;
            right: 2rem;
            z-index: 9999;
            min-width: 320px;
            max-width: 90vw;
            /* Suppression de la transition sur top pour éviter le "bougé" du header */
            /* transition: top 0.3s; */
        }
        /* Suppression de la classe sticky qui modifiait top */
        /* #alert-container.sticky {
            top: 0 !important;
        } */
        #alert-container .alert {
            box-shadow: 0 8px 32px 0 rgba(0,0,0,0.45);
            border-radius: 0.85rem;
            font-size: 1.08rem;
            font-family: 'Orbitron', sans-serif;
            padding: 1.2rem 2.5rem 1.2rem 1.5rem;
            position: relative;
            overflow: hidden;
            border: none;
            min-height: 3.2rem;
            margin-bottom: 0.5rem;
            opacity: 1;
            transition: opacity 0.5s;
        }
        #alert-container .alert-success {
            background: linear-gradient(90deg, #22c55e 60%, #16a34a 100%);
            color: #f0fdf4;
            border-left: 8px solid #16a34a;
        }
        #alert-container .alert-error {
            background: linear-gradient(90deg, #ef4444 60%, #b91c1c 100%);
            color: #fff1f2;
            border-left: 8px solid #b91c1c;
        }
        #alert-container .alert .alert-close {
            color: #fff;
            font-weight: bold;
            z-index: 2;
            background: none;
            border: none;
            outline: none;
            opacity: 0.8;
            transition: opacity 0.2s;
        }
        #alert-container .alert .alert-close:hover {
            opacity: 1;
            color: #fbbf24;
        }
        #alert-container .alert .alert-progress {
            display: none !important;
        }
    </style>
    <script>
        // Afficher/Masquer mot de passe
        function togglePassword(fieldId, el) {
            const input = document.getElementById(fieldId);
            if (input.type === "password") {
                input.type = "text";
                el.innerHTML = '<i class="fas fa-eye-slash"></i>';
            } else {
                input.type = "password";
                el.innerHTML = '<i class="fas fa-eye"></i>';
            }
        }

        // Barre de progression upload image (simulation, car PHP gère l'upload)
        document.getElementById('profile_picture').addEventListener('change', function() {
            const progress = document.getElementById('upload-progress');
            const bar = document.getElementById('progress-bar');
            progress.classList.remove('hidden');
            bar.style.width = '0%';
            let percent = 0;
            const interval = setInterval(() => {
                percent += 10;
                bar.style.width = percent + '%';
                if (percent >= 100) clearInterval(interval);
            }, 40);
        });

        // Progression du formulaire (remplissage des champs)
        function updateFormProgress() {
            const fields = [
                'name', 'email', 'phone', 'password', 'current_password'
            ];
            let filled = 0;
            fields.forEach(id => {
                const el = document.getElementById(id);
                if (el && el.value.trim() !== '') filled++;
            });
            const percent = Math.round((filled / fields.length) * 100);
            document.getElementById('form-progress-bar').style.width = percent + '%';
        }
        document.querySelectorAll('#profile-form input').forEach(input => {
            input.addEventListener('input', updateFormProgress);
        });
        updateFormProgress();

        // Soumission AJAX du formulaire pour actualisation en direct
        document.getElementById('profile-form').addEventListener('submit', function(e) {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);

            // Affiche une barre de progression sur le bouton
            const btn = form.querySelector('button[type="submit"]');
            const originalBtn = btn.innerHTML;
            btn.innerHTML = '<i class="fas fa-spinner fa-spin"></i>';

            fetch('', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(html => {
                // Extraire le nouvel alert et stats de la réponse HTML
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                // Notification
                const newAlert = doc.querySelector('#alert-container');
                document.getElementById('alert-container').innerHTML = newAlert ? newAlert.innerHTML : '';
                // Statistiques
                ['stat-total', 'stat-open', 'stat-closed'].forEach(id => {
                    const newVal = doc.getElementById(id);
                    if (newVal) document.getElementById(id).textContent = newVal.textContent;
                });
                // Image de profil
                const newImg = doc.querySelector('.profile-pic-container img');
                if (newImg) {
                    document.querySelector('.profile-pic-container img').src = newImg.src;
                }
                btn.innerHTML = originalBtn;
            })
            .catch(() => {
                btn.innerHTML = originalBtn;
            });
        });
    </script>
</body>
</html>