<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$availableLangs = ['fr', 'en', 'nl'];

if (isset($_GET['lang']) && in_array($_GET['lang'], $availableLangs)) {
    $lang = $_GET['lang'];
    setcookie('lang', $lang, time() + (86400 * 30), "/");
} else {
    $lang = $_COOKIE['lang'] ?? 'fr';
    if (!in_array($lang, $availableLangs)) {
        $lang = 'fr';
    }
}

$translations = [
    'fr' => [
        'tickets' => 'Tickets',
        'create_ticket' => 'Créer un ticket',
        'ticket_view' => 'Voir mes tickets',
        'archive_ticket' => 'Tickets archivés',
        'support' => 'Support',
        'about_us' => 'Qui sommes-nous',
        'patchnote' => 'Patchnote',
        'search' => 'Rechercher',
        'connect' => 'Connecter',
        'login' => 'Se connecter',
        'register' => 'S\'inscrire',
        'profil' => 'Profil',
    ],
    'en' => [
        'tickets' => 'Tickets',
        'create_ticket' => 'Create a ticket',
        'ticket_view' => 'View my tickets',
        'archive_ticket' => 'Archived tickets',
        'support' => 'Support',
        'about_us' => 'About us',
        'patchnote' => 'Patchnote',
        'search' => 'Search',
        'connect' => 'Connect',
        'login' => 'Login',
        'register' => 'Register',
        'profil' => 'Profile',
    ],
    'nl' => [
        'tickets' => 'Kaartjes',
        'create_ticket' => 'Maak een ticket',
        'ticket_view' => 'Bekijk mijn tickets',
        'archive_ticket' => 'Gearchiveerde tickets',
        'support' => 'Ondersteuning',
        'about_us' => 'Over ons',
        'patchnote' => 'Patchnote',
        'search' => 'Zoeken',
        'connect' => 'Verbinden',
        'login' => 'Inloggen',
        'register' => 'Registreren',
        'profil' => 'Profiel',
    ],
];
$text = $translations[$lang];
$currentPage = basename($_SERVER['PHP_SELF']);
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
    <style>
        .nav-active {
            position: relative;
        }

        .nav-active::after {
            content: '';
            display: block;
            position: absolute;
            left: 0;
            right: 0;
            bottom: -8px;
            height: 6px;
            background: #8b5cf6;
            background: linear-gradient(90deg, #38bdf8 0%, #8b5cf6 50%, #ef4444 100%);
            border-radius: 8px;
            box-shadow: 0 2px 16px 0 #8b5cf680;
            animation: underlineGrow 0.5s cubic-bezier(.4, 0, .2, 1);
            transition: background 0.3s, height 0.3s;
        }

        .main-nav-link:hover,
        .main-nav-link:focus {
            position: relative;
        }

        .main-nav-link:hover::after,
        .main-nav-link:focus::after {
            content: '';
            display: block;
            position: absolute;
            left: 0;
            right: 0;
            bottom: -8px;
            height: 6px;
            background: #8b5cf6;
            background: linear-gradient(90deg, #38bdf8 0%, #8b5cf6 50%, #ef4444 100%);
            border-radius: 8px;
            box-shadow: 0 2px 16px 0 #8b5cf680;
            animation: underlineGrow 0.5s cubic-bezier(.4, 0, .2, 1);
            transition: background 0.3s, height 0.3s;
            z-index: 1;
        }

        .main-nav-link.nav-active:hover::after,
        .main-nav-link.nav-active:focus::after {
            display: none;
        }

        @keyframes underlineGrow {
            from {
                width: 0;
                opacity: 0;
            }

            to {
                width: 100%;
                opacity: 1;
            }
        }

        /* Responsive nav */
        @media (max-width: 1024px) {
            .container {
                flex-direction: column;
                align-items: stretch;
            }

            .main-nav-row {
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
            }

            .main-nav-link,
            .main-nav-link>span {
                justify-content: flex-start;
                width: 100%;
                text-align: left;
            }

            .main-nav-link {
                padding: 0.75rem 0.5rem;
            }

            .flex.items-center.space-x-6 {
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
            }

            .flex.items-center.space-x-4 {
                flex-direction: column;
                align-items: stretch;
                gap: 0.5rem;
                margin-top: 0.5rem;
            }

            #ticketsDropdown,
            #profileDropdown {
                left: 50% !important;
                transform: translateX(-50%) !important;
                width: 95vw !important;
                min-width: 0 !important;
                max-width: 400px;
            }
        }

        @media (max-width: 640px) {
            .container {
                padding-left: 0.5rem !important;
                padding-right: 0.5rem !important;
            }

            .main-nav-link,
            .main-nav-link>span {
                font-size: 1.1rem;
            }

            #profileDropdown .profile-header img {
                width: 40px;
                height: 40px;
            }
        }

        /* Dropdown profil amélioré */
        #profileDropdown {
            min-width: 260px;
            padding: 0.5rem 0;
        }

        #profileDropdown .profile-header {
            display: flex;
            align-items: center;
            gap: 1rem;
            padding: 1rem 1.25rem;
            border-bottom: 1px solid #374151;
            background: linear-gradient(90deg, #1e293b 60%, #8b5cf6 100%);
        }

        #profileDropdown .profile-header img {
            width: 48px;
            height: 48px;
            border-radius: 50%;
            border: 2px solid #8b5cf6;
            background: #fff;
            object-fit: cover;
        }

        #profileDropdown .profile-header .profile-info {
            display: flex;
            flex-direction: column;
            gap: 0.15rem;
        }

        #profileDropdown .profile-header .profile-role {
            font-size: 0.95rem;
            color: #a5b4fc;
            font-weight: 600;
        }

        #profileDropdown .profile-header .profile-name {
            font-size: 1.08rem;
            color: #fff;
            font-weight: 700;
        }

        #profileDropdown a {
            display: flex;
            align-items: center;
            gap: 0.7rem;
            padding: 0.8rem 1.25rem;
            color: #fff;
            background: none;
            border: none;
            transition: background 0.18s, color 0.18s;
            font-size: 1rem;
        }

        #profileDropdown a:not(:last-child) {
            border-bottom: 1px solid #374151;
        }

        #profileDropdown a:hover {
            background: linear-gradient(90deg, #8b5cf6 0%, #38bdf8 100%);
            color: #fff;
            box-shadow: 0 2px 8px #8b5cf680;
        }

        #profileDropdown a .fa-solid {
            min-width: 20px;
        }

        #profileDropdown a.text-red-400:hover {
            background: linear-gradient(90deg, #ef4444 0%, #8b5cf6 100%);
            color: #fff;
        }
    </style>
</head>

<body>
    <nav class="bg-gray-800 shadow-md">
        <div class="container mx-auto flex justify-between items-center px-4 py-3 flex-wrap">
            <a class="text-white text-xl font-bold uppercase flex items-center space-x-2" href="index.php">
                <img src="uploads/logo.png" alt="Logo" class="w-8 h-8">
                <span>MyTicket</span>
            </a>
            <div class="main-nav-row flex items-center space-x-6 w-full lg:w-auto mt-2 lg:mt-0">
                <div class="relative w-full lg:w-auto">
                    <button id="ticketsDropdownButton"
                        class="main-nav-link text-white flex items-center space-x-2 hover:text-gray-300 hover-animate-red <?= in_array($currentPage, ['create_ticket.php', 'view_tickets.php', 'archive_tickets.php']) ? 'nav-active' : '' ?> w-full lg:w-auto"
                        onclick="toggleDropdown('ticketsDropdown')">
                        <i class="fa-solid fa-ticket text-red-500"></i>
                        <span><?= $text['tickets'] ?></span>
                        <i id="ticketsArrow" class="fas fa-chevron-down"></i>
                    </button>
                    <div id="ticketsDropdown"
                        class="hidden absolute top-full left-1/2 transform -translate-x-1/2 bg-gray-700 text-white rounded-lg shadow-lg mt-2 z-20 w-64 overflow-visible">
                        <a href="create_ticket.php"
                            class="block px-6 py-3 hover:bg-gray-600 rounded-t-lg transition-colors flex items-center space-x-2 group">
                            <i class="fas fa-plus-circle text-green-500 group-hover:scale-110 group-hover:rotate-6 transition-transform"></i>
                            <span class="group-hover:text-green-500 transition-colors"><?= $text['create_ticket'] ?></span>
                        </a>
                        <a href="view_tickets.php"
                            class="block px-6 py-3 hover:bg-gray-600 transition-colors flex items-center space-x-2 group">
                            <i class="fas fa-list text-green-500 group-hover:scale-110 group-hover:rotate-6 transition-transform"></i>
                            <span class="group-hover:text-green-500 transition-colors"><?= $text['ticket_view'] ?></span>
                        </a>
                        <a href="archive_tickets.php"
                            class="block px-6 py-3 hover:bg-gray-600 rounded-b-lg transition-colors flex items-center space-x-2 group">
                            <i class="fas fa-archive text-red-500 group-hover:scale-110 group-hover:rotate-6 transition-transform"></i>
                            <span class="group-hover:text-red-500 transition-colors"><?= $text['archive_ticket'] ?></span>
                        </a>
                    </div>
                </div>
                <a href="support.php"
                    class="main-nav-link text-white hover:text-purple-400 flex items-center space-x-2 <?= $currentPage === 'support.php' ? 'nav-active' : '' ?> w-full lg:w-auto">
                    <i class="fa-solid fa-headset text-purple-400"></i>
                    <span><?= $text['support'] ?></span>
                </a>
                <a href="about_us.php"
                    class="main-nav-link text-white hover:text-purple-400 flex items-center space-x-2 <?= $currentPage === 'about_us.php' ? 'nav-active' : '' ?> w-full lg:w-auto">
                    <i class="fa-solid fa-users text-blue-400"></i>
                    <span><?= $text['about_us'] ?></span>
                </a>
                <a href="patchnote.php"
                    class="main-nav-link text-white hover:text-cyan-400 flex items-center space-x-2 <?= $currentPage === 'patchnote.php' ? 'nav-active' : '' ?> w-full lg:w-auto">
                    <i class="fa-solid fa-file-alt text-cyan-400"></i>
                    <span><?= $text['patchnote'] ?></span>
                </a>
            </div>
            <div class="flex items-center space-x-4 w-full lg:w-auto mt-2 lg:mt-0">
                <?php if (!empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                <div class="relative w-full lg:w-auto">
                    <button id="profileDropdownButton" onclick="toggleDropdown('profileDropdown')"
                        class="text-white flex items-center space-x-2 hover:text-gray-300 w-full lg:w-auto">
                        <i class="fa-solid fa-circle-user text-2xl align-middle"
                           style="background: linear-gradient(90deg,#38bdf8 0%,#8b5cf6 50%,#ef4444 100%);
                                  -webkit-background-clip: text;
                                  -webkit-text-fill-color: transparent;
                                  background-clip: text;
                                  text-fill-color: transparent;">
                        </i>
                        <span
                            style="background: linear-gradient(90deg, #38bdf8 0%, #8b5cf6 50%, #ef4444 100%);
                                   -webkit-background-clip: text;
                                   -webkit-text-fill-color: transparent;
                                   background-clip: text;
                                   text-fill-color: transparent;">
                            <?= htmlspecialchars($_SESSION['user']['name'] ?? $text['connect']) ?>
                        </span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div id="profileDropdown"
                        class="hidden absolute top-full left-1/2 transform -translate-x-1/2 bg-gray-700 text-white rounded-xl shadow-2xl mt-2 z-20 overflow-visible">
                        <div class="profile-header">
                            <?php
                                // Récupération du chemin de la photo de profil comme dans profile.php
                                $profilePic = '';
                                if (!empty($_SESSION['user']['id'])) {
                                    // Connexion à la base pour récupérer la colonne profile_picture
                                    try {
                                        require_once __DIR__ . '/DatabaseConnection.php';
                                        $pdo = DatabaseConnection::getInstance()->getConnection();
                                        $stmt = $pdo->prepare('SELECT profile_picture FROM users WHERE id = :id');
                                        $stmt->execute(['id' => $_SESSION['user']['id']]);
                                        $row = $stmt->fetch(PDO::FETCH_ASSOC);
                                        $profilePic = $row && !empty($row['profile_picture']) ? $row['profile_picture'] : '';
                                    } catch (Exception $e) {
                                        $profilePic = '';
                                    }
                                }
                                if (empty($profilePic) || !file_exists(__DIR__ . '/' . $profilePic)) {
                                    $profilePic = 'uploads/default_profile.png';
                                }
                                $roleLabels = [
                                    1 => 'Utilisateur',
                                    2 => 'Admin',
                                    3 => 'Dev',
                                    4 => 'Modo',
                                    5 => 'Guide',
                                ];
                                $id_perm = $_SESSION['user']['id_perm'] ?? 1;
                            ?>
                            <img src="<?= htmlspecialchars($profilePic) ?>" alt="profil">
                            <div class="profile-info">
                                <span class="profile-name"><?= htmlspecialchars($_SESSION['user']['name'] ?? $text['connect']) ?></span>
                                <span class="profile-role"><?= $roleLabels[$id_perm] ?? 'Utilisateur' ?></span>
                            </div>
                        </div>
                        <a href="profile.php">
                            <i class="fa-solid fa-user text-blue-400"></i>
                            <span><?= $text['profil'] ?></span>
                        </a>
                        <a href="view_tickets.php">
                            <i class="fa-solid fa-ticket text-red-400"></i>
                            <span><?= $text['tickets'] ?></span>
                        </a>
                        <?php if (in_array($id_perm, [2, 3, 4, 5])): ?>
                        <a href="admin.php" class="font-semibold text-green-400">
                            <i class="fa-solid fa-tools text-green-400"></i>
                            <span> Accès Admin</span>
                        </a>
                        <?php endif; ?>
                        <a href="logout.php" class="text-red-400">
                            <i class="fa-solid fa-sign-out-alt text-red-400"></i>
                            <span>Déconnexion</span>
                        </a>
                    </div>
                </div>
                <?php else: ?>
                <a href="login.php" class="text-white hover:text-green-400 w-full lg:w-auto"><?= $text['login'] ?></a>
                <a href="register.php" class="text-white hover:text-blue-400 w-full lg:w-auto"><?= $text['register'] ?></a>
                <?php endif; ?>
                <form method="GET" class="w-full lg:w-auto">
                    <select name="lang" onchange="this.form.submit()"
                        class="bg-gray-700 text-white rounded px-2 py-1 w-full lg:w-auto">
                        <option value="fr" <?= $lang === 'fr' ? 'selected' : '' ?>>🇫🇷 Fr</option>
                        <option value="en" <?= $lang === 'en' ? 'selected' : '' ?>>🇬🇧 En</option>
                        <option value="nl" <?= $lang === 'nl' ? 'selected' : '' ?>>🇳🇱 Nl</option>
                    </select>
                </form>
            </div>
        </div>
    </nav>
    <script>
        function toggleDropdown(id) {
            const el = document.getElementById(id);
            el.classList.toggle('hidden');
        }
        document.addEventListener('click', (e) => {
            const dropdowns = ['ticketsDropdown', 'profileDropdown'];
            dropdowns.forEach(id => {
                const el = document.getElementById(id);
                const button = document.getElementById(id + 'Button');
                if (el && !el.contains(e.target) && (!button || !button.contains(e.target))) {
                    el.classList.add('hidden');
                }
            });
        });
    </script>
</body>

</html>