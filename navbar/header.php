<?php
session_start();

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
        'view_tickets' => 'Voir mes tickets',
        'archive_ticket' => 'Tickets archivés',
        'support' => 'Support',
        'about_us' => 'Qui sommes-nous',
        'patchnote' => 'Patchnote',
        'search' => 'Rechercher',
        'connect' => 'Connecter',
        'login' => 'Se connecter',
        'register' => 'S\'inscrire',
    ],
    'en' => [
        'tickets' => 'Tickets',
        'create_ticket' => 'Create a ticket',
        'view_tickets' => 'View my tickets',
        'archive_ticket' => 'Archived tickets',
        'support' => 'Support',
        'about_us' => 'About us',
        'patchnote' => 'Patchnote',
        'search' => 'Search',
        'connect' => 'Connect',
        'login' => 'Login',
        'register' => 'Register',
    ],
    'nl' => [
        'tickets' => 'Kaartjes',
        'create_ticket' => 'Maak een ticket',
        'view_tickets' => 'Bekijk mijn tickets',
        'archive_ticket' => 'Gearchiveerde tickets',
        'support' => 'Ondersteuning',
        'about_us' => 'Over ons',
        'patchnote' => 'Patchnote',
        'search' => 'Zoeken',
        'connect' => 'Verbinden',
        'login' => 'Inloggen',
        'register' => 'Registreren',
    ],
];
$text = $translations[$lang];
?>
<!DOCTYPE html>
<html lang="<?= $lang ?>">

<head>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://kit.fontawesome.com/a076d05399.js" crossorigin="anonymous"></script>
</head>

<body>
    <nav class="bg-gray-800 shadow-md">
        <div class="container mx-auto flex justify-between items-center px-4 py-3">
            <!-- Logo à gauche -->
            <a class="text-white text-xl font-bold uppercase" href="index.php">MyTicket</a>

            <!-- Menu principal au centre -->
            <div class="flex items-center space-x-6">
                <div class="relative">
                    <button onclick="toggleDropdown('ticketsDropdown')" class="text-white flex items-center space-x-2 hover:text-gray-300">
                        <i class="fa-solid fa-ticket text-red-500"></i>
                        <span><?= $text['tickets'] ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div id="ticketsDropdown" class="hidden absolute bg-gray-700 text-white rounded-lg shadow-lg mt-2 z-10 w-64">
                        <a href="create_ticket.php" class="block px-6 py-3 hover:bg-gray-600 flex items-center space-x-2">
                            <i class="fa-solid fa-plus text-green-400"></i>
                            <span><?= $text['create_ticket'] ?></span>
                        </a>
                        <a href="view_tickets.php" class="block px-6 py-3 hover:bg-gray-600 flex items-center space-x-2">
                            <i class="fa-solid fa-eye text-blue-400"></i>
                            <span><?= $text['view_tickets'] ?></span>
                        </a>
                        <a href="archive_tickets.php" class="block px-6 py-3 hover:bg-gray-600 flex items-center space-x-2">
                            <i class="fa-solid fa-box-archive text-yellow-400"></i>
                            <span><?= $text['archive_ticket'] ?></span>
                        </a>
                    </div>
                </div>
                <a href="support.php" class="text-white hover:text-purple-400 flex items-center space-x-2">
                    <i class="fa-solid fa-headset text-purple-400"></i>
                    <span><?= $text['support'] ?></span>
                </a>
                <a href="about_us.php" class="text-white hover:text-purple-400 flex items-center space-x-2">
                    <i class="fa-solid fa-users text-blue-400"></i>
                    <span><?= $text['about_us'] ?></span>
                </a>
                <a href="patchnote.php" class="text-white hover:text-cyan-400 flex items-center space-x-2">
                    <i class="fa-solid fa-file-alt text-cyan-400"></i>
                    <span><?= $text['patchnote'] ?></span>
                </a>
            </div>

            <!-- Boutons à droite -->
            <div class="flex items-center space-x-4">
                <?php if (!empty($_SESSION['logged_in']) && $_SESSION['logged_in'] === true): ?>
                    <button id="profileDropdownButton" onclick="toggleDropdown('profileDropdown')" class="text-white flex items-center space-x-2 hover:text-gray-300">
                        <i class="fas fa-user-circle text-2xl"></i>
                        <span><?= htmlspecialchars($_SESSION['user']['name'] ?? 'Profil') ?></span>
                        <i class="fas fa-chevron-down"></i>
                    </button>
                    <div id="profileDropdown" class="hidden absolute right-0 bg-gray-700 text-white rounded-lg shadow-lg mt-2 z-10 w-56">
                        <div class="flex justify-between items-center px-4 py-2 border-b border-gray-600">
                            <?php
                            $roleImages = [
                                1 => 'utilisateur.png',
                                2 => 'admin.png',
                                3 => 'dev.png',
                                4 => 'modo.png',
                                5 => 'guide.png',
                            ];
                            $roleLabels = [
                                1 => 'Utilisateur',
                                2 => 'Admin',
                                3 => 'Dev',
                                4 => 'Modo',
                                5 => 'Guide',
                            ];
                            $id_perm = $_SESSION['user']['id_perm'] ?? 1;
                            ?>
                            <span><?= $roleLabels[$id_perm] ?? 'Utilisateur' ?></span>
                            <img src="images_statut/<?= $roleImages[$id_perm] ?? 'utilisateur.png' ?>" alt="statut" class="w-[69px] h-[34px]">
                        </div>
                        <a href="profile.php" class="block px-4 py-2 hover:bg-gray-600 flex items-center space-x-2">
                            <i class="fa-solid fa-user text-blue-400"></i>
                            <span>Mon Profil</span>
                        </a>
                        <a href="view_tickets.php" class="block px-4 py-2 hover:bg-gray-600 flex items-center space-x-2">
                            <i class="fa-solid fa-ticket text-red-400"></i>
                            <span>Mes Tickets</span>
                        </a>
                        <?php if (in_array($id_perm, [2, 3, 4, 5])): ?>
                            <a href="admin.php" class="block px-4 py-2 hover:bg-gray-600 flex items-center space-x-2 font-semibold text-green-400">
                                <i class="fa-solid fa-tools text-green-400"></i>
                                <span>🛠 Accès Admin</span>
                            </a>
                        <?php endif; ?>
                        <a href="logout.php" class="block px-4 py-2 hover:bg-gray-600 flex items-center space-x-2 text-red-400">
                            <i class="fa-solid fa-sign-out-alt text-red-400"></i>
                            <span>Déconnexion</span>
                        </a>
                    </div>
                <?php else: ?>
                    <a href="login.php" class="text-white hover:text-green-400"><?= $text['login'] ?></a>
                    <a href="register.php" class="text-white hover:text-blue-400"><?= $text['register'] ?></a>
                <?php endif; ?>
                <form method="GET">
                    <select name="lang" onchange="this.form.submit()" class="bg-gray-700 text-white rounded px-2 py-1">
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