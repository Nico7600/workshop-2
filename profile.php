<?php
session_start();

require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

try {
    $pdo = new PDO(
        sprintf('mysql:host=%s;dbname=%s', $_ENV['DB_HOST'], $_ENV['DB_NAME']),
        $_ENV['DB_USER'],
        $_ENV['DB_PASSWORD']
    );
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch (PDOException $e) {
    die('Erreur de connexion à la base de données : ' . $e->getMessage());
}

if (!$pdo) {
    die('Impossible de se connecter à la base de données.');
}

include 'header.php';

$currentUser = null;
if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare('SELECT * FROM users WHERE id = :id');
        $stmt->execute(['id' => $_SESSION['user_id']]);
        $currentUser = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Erreur lors de la récupération des informations utilisateur : ' . $e->getMessage();
    }
}

$userCredentials = null;
if (isset($_SESSION['user_id'])) {
    try {
        $stmt = $pdo->prepare('SELECT name, password FROM users WHERE id = :id');
        $stmt->execute(['id' => $_SESSION['user_id']]);
        $userCredentials = $stmt->fetch(PDO::FETCH_ASSOC);
    } catch (PDOException $e) {
        echo 'Erreur lors de la récupération des informations de connexion : ' . $e->getMessage();
    }
}

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
    $newUsername = $_POST['new_username'] ?? '';
    $profilePicture = $_FILES['profile_picture'] ?? null;
    $passwordConfirmation = $_POST['password_confirmation'] ?? '';

    if ($newUsername || $profilePicture) {
        try {
            // Vérification du mot de passe
            $stmt = $pdo->prepare('SELECT password FROM users WHERE id = :user_id');
            $stmt->execute(['user_id' => $_SESSION['user_id']]);
            $user = $stmt->fetch(PDO::FETCH_ASSOC);

            if (!$user || !password_verify($passwordConfirmation, $user['password'])) {
                echo 'Mot de passe incorrect.';
                return;
            }

            $updateQuery = 'UPDATE users SET ';
            $params = [];

            if ($newUsername) {
                $updateQuery .= 'name = :new_name ';
                $params['new_name'] = $newUsername;
            }

            if ($profilePicture && $profilePicture['error'] === UPLOAD_ERR_OK) {
                $uploadDir = __DIR__ . '/uploads/';
                $uploadFile = $uploadDir . basename($profilePicture['name']);
                if (move_uploaded_file($profilePicture['tmp_name'], $uploadFile)) {
                    if ($newUsername) $updateQuery .= ', ';
                    $updateQuery .= 'profile_picture = :profile_picture ';
                    $params['profile_picture'] = 'uploads/' . basename($profilePicture['name']);
                }
            }

            $updateQuery .= 'WHERE id = :user_id';
            $params['user_id'] = $_SESSION['user_id'];

            $stmt = $pdo->prepare($updateQuery);
            $stmt->execute($params);

            echo 'Profil mis à jour avec succès.';
        } catch (PDOException $e) {
            echo 'Erreur lors de la mise à jour du profil : ' . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="fr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil utilisateur</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;700&display=swap" rel="stylesheet">
    <style>
        body {
            background-color: #1a202c;
            font-family: 'Orbitron', sans-serif;
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
    </style>
</head>

<body class="text-gray-100 flex flex-col min-h-screen">
    <div class="flex-grow">
        <div class="max-w-4xl mx-auto mt-10 bg-gray-800 p-6 rounded-lg shadow-md">
            <h1 class="text-2xl font-bold mb-4 text-cyan-400">Modifier le profil</h1>
            <form action="" method="POST" enctype="multipart/form-data" class="space-y-4">
                <div>
                    <label for="current_username" class="block text-sm font-medium text-gray-300">Nom d'utilisateur actuel</label>
                    <input type="text" id="current_username" value="<?php echo htmlspecialchars($currentUser['name'] ?? ''); ?>" class="mt-1 block w-full bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" disabled>
                </div>
                <div>
                    <label for="new_username" class="block text-sm font-medium text-gray-300">Nouveau nom d'utilisateur</label>
                    <input type="text" name="new_username" id="new_username" class="mt-1 block w-full bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm">
                </div>
                <div>
                    <label for="profile_picture" class="block text-sm font-medium text-gray-300">Nouvelle photo de profil</label>
                    <input type="file" name="profile_picture" id="profile_picture" class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                </div>
                <div>
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-300">Confirmez votre mot de passe</label>
                    <input type="password" name="password_confirmation" id="password_confirmation" class="mt-1 block w-full bg-gray-700 text-white border-gray-600 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm" required>
                </div>
                <div>
                    <button type="submit" name="update_profile" class="w-full bg-green-500 text-white py-2 px-4 rounded-md hover:bg-green-600">Mettre à jour</button>
                </div>
            </form>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>

</html>