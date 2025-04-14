<?php
session_start();

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

require_once 'vendor/autoload.php';
use Dotenv\Dotenv;

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

// Vérifie que la session contient les infos nécessaires
if (!isset($_SESSION['user']['id'], $_SESSION['user']['id_perm'])) {
     error_log("[ERREUR SESSION] L'utilisateur n'est pas connecté ou la session est incomplète.");
    echo "Vous n'êtes pas connecté.";
    exit;
}

try {
    // Connexion à la base de données
    $dsn = sprintf("mysql:host=%s;dbname=%s;charset=utf8", $_ENV['DB_HOST'], $_ENV['DB_NAME']);
    $db = new PDO($dsn, $_ENV['DB_USER'], $_ENV['DB_PASSWORD']);
    $db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    error_log("[ERREUR DB] Connexion échouée : " . $e->getMessage());
    echo "Erreur de connexion à la base de données. Veuillez réessayer plus tard.";
    exit;
}

try {
    $userId = $_SESSION['user']['id'];
    $query = $db->prepare("SELECT id_perm FROM users WHERE id = ?");
    $query->execute([$userId]);
    $user = $query->fetch(PDO::FETCH_ASSOC);

    if (!$user) {
        error_log("[ERREUR UTILISATEUR] Aucun utilisateur trouvé avec l'ID : $userId");
        echo "Accès refusé. Utilisateur introuvable.";
        exit;
    }

    $permission = (int)$user['id_perm'];
    error_log("[DEBUG] Permission utilisateur récupérée : $permission");

    // Autorise uniquement certaines permissions
    $permissionsAutorisees = [2, 3, 4, 5];
    if (!in_array($permission, $permissionsAutorisees, true)) {
        error_log("[ERREUR PERMISSION] ID $userId a une permission non autorisée : $permission");
        echo "Accès refusé. Vous n'avez pas les droits suffisants.";
        exit;
    }
} catch (PDOException $e) {
    error_log("[ERREUR DB] Erreur lors de la récupération de la permission : " . $e->getMessage());
    echo "Erreur lors de la vérification des permissions.";
    exit;
}

$languages = ['en' => 'English', 'fr' => 'Français', 'nl' => 'Nederlands'];
$selected_lang = $_GET['lang'] ?? 'fr';
if (!array_key_exists($selected_lang, $languages)) {
    $selected_lang = 'fr';
}
?>

<!DOCTYPE html>
<html lang="<?php echo htmlspecialchars($selected_lang); ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
</head>
<body class="bg-gray-100 text-gray-800">
    <div class="container mx-auto p-4">
        <header class="flex justify-between items-center bg-blue-500 text-white p-4 rounded">
            <h1 class="text-xl font-bold">Admin Panel</h1>
            <div class="flex items-center space-x-4">
                <form method="GET" class="flex items-center">
                    <label for="lang" class="text-sm mr-2">Language:</label>
                    <select name="lang" id="lang" class="text-sm bg-white border rounded px-2 py-1" onchange="this.form.submit()">
                        <?php foreach ($languages as $lang_code => $lang_name): ?>
                            <option value="<?php echo $lang_code; ?>" <?php echo $lang_code === $selected_lang ? 'selected' : ''; ?>>
                                <?php echo $lang_name; ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </form>
                <a href="logout.php" class="text-sm bg-red-500 px-3 py-1 rounded hover:bg-red-600">
                    <i class="fas fa-sign-out-alt"></i> Logout
                </a>
            </div>
        </header>

        <main class="mt-6">
            <div class="bg-white shadow rounded p-6">
                <h2 class="text-2xl font-semibold mb-4">Welcome, Admin</h2>
                <p class="text-gray-600">Here you can manage the application.</p>
                <div class="mt-4">
                    <button class="bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600">
                        <i class="fas fa-plus"></i> Add New Item
                    </button>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
