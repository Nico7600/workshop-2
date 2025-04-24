<?php
session_start();

require_once 'vendor/autoload.php';
$dotenv = Dotenv\Dotenv::createImmutable(__DIR__);
$dotenv->load();

$dsn = 'mysql:host=' . $_ENV['DB_HOST'] . ';dbname=' . $_ENV['DB_NAME'] . ';charset=utf8';
$username = $_ENV['DB_USER'];
$password = $_ENV['DB_PASSWORD'];

try {
    $pdo = new PDO($dsn, $username, $password, [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);
} catch (PDOException $e) {
    die('Database connection failed: ' . $e->getMessage());
}

if (!isset($_SESSION['user'])) {
    header('Location: index.php');
    exit();
}

$id_perm = (int)$_SESSION['user']['id_perm'];
if (!in_array($id_perm, [2, 3, 4, 5])) {
    header('Location: index.php');
    exit();
}

$languages = ['en' => 'English', 'fr' => 'Français', 'nl' => 'Nederlands'];
$selected_lang = $_GET['lang'] ?? 'fr';
if (!array_key_exists($selected_lang, $languages)) {
    $selected_lang = 'fr';
}

$translations = [
    'fr' => [
        'user_info' => 'Informations des utilisateurs',
        'id' => 'ID',
        'name' => 'Nom',
        'email' => 'Email',
        'grade' => 'Grade',
        'actions' => 'Actions',
        'modify' => 'Modifier',
        'delete' => 'Supprimer',
        'ticket_management' => 'Gestion des tickets',
        'title' => 'Titre',
        'status' => 'Statut',
        'view' => 'Voir',
        'add_patchnote' => 'Ajouter un patchnote',
        'content' => 'Contenu',
        'add' => 'Ajouter',
        'creator' => 'Créateur',
        'message' => 'Message',
        'created_at' => 'Créé le',
        'is_closed' => 'Statut',
        'closed_at' => 'Fermé le',
        'closed_by' => 'Fermé par',
        'open' => 'Ouvert',
        'closed' => 'Fermé',
        'archive_ticket' => 'Archive Ticket',
        'patch_note' => 'Notes de mise à jour',
        'permissions' => 'Permissions',
        'role' => 'Rôles',
        'ticket' => 'Tickets',
        'ticket_message' => 'Messages des tickets',
        'columns' => [
            'id' => 'ID',
            'creator' => 'Créateur',
            'ticket_name' => 'Nom du ticket',
            'message' => 'Bericht',
            'created_at' => 'Créé le',
            'is_closed' => 'Statut',
            'closed_at' => 'Fermé le',
            'closed_by' => 'Fermé par',
            'updated_at' => 'Mis à jour le',
            'deleted_at' => 'Supprimé le',
        ],
        'yes' => 'Oui',
        'no' => 'Non',
        'toggle_permission' => 'Changer la permission',
        'ticket_id' => 'ID du ticket',
        'description' => 'Description',
        'can_reply_ticket' => 'Peut répondre aux tickets',
        'can_ban_permanently' => 'Peut bannir définitivement',
        'can_ban_temporarily' => 'Peut bannir temporairement',
        'can_post_patchnotes' => 'Peut publier des patchnotes',
        'can_manage_users' => 'Peut gérer les utilisateurs',
        'can_view_reports' => 'Peut voir les rapports',
        'can_edit_roles' => 'Peut modifier les rôles',
        'can_delete_tickets' => 'Peut supprimer les tickets',
        'no_records_found' => 'Aucun enregistrement trouvé',
        'content' => 'Contenu',
        'title' => 'Titre',
        'admin_panel' => 'Panneau d\'administration',
        'status' => 'Statut',
        'role_columns' => [
            'id' => 'ID',
            'name' => 'Nom',
            'image_tag' => 'Tag de l\'image',
            'status' => 'Statut',
            'is_active' => 'Actif',
            'created_at' => 'Créé le',
            'updated_at' => 'Mis à jour le',
            'created_by' => 'Créé par',
            'updated_by' => 'Mis à jour par',
            'deleted_at' => 'Supprimé le',
            'autorisation' => 'Autorisation',
        ],
        'phone' => 'Téléphone',
        'total_tickets' => 'Total des tickets',
        'open_tickets' => 'Tickets ouverts',
        'closed_tickets' => 'Tickets fermés',
        'ban_user' => 'Bannir l\'utilisateur',
        'manage_role' => 'Gérer le rôle',
        'view_profile' => 'Voir le profil',
        'ban_user_modal_title' => 'Bannir l\'utilisateur',
        'ban_user_modal_reason' => 'Motif :',
        'ban_user_modal_select_duration' => 'Sélectionnez la durée du bannissement :',
        'ban_user_modal_cancel' => 'Annuler',
        'ban_user_modal_permanent' => 'Permanent',
        'manage_role_modal_title' => 'Gérer le rôle',
        'manage_role_modal_close' => 'Fermer',
        'view_profile_modal_title' => 'Voir le profil',
        'view_profile_modal_close' => 'Fermer',
    ],
    'en' => [
        'user_info' => 'User Information',
        'id' => 'ID',
        'name' => 'Name',
        'email' => 'Email',
        'grade' => 'Grade',
        'actions' => 'Actions',
        'modify' => 'Modify',
        'delete' => 'Delete',
        'ticket_management' => 'Ticket Management',
        'title' => 'Title',
        'status' => 'Status',
        'view' => 'View',
        'add_patchnote' => 'Add a Patchnote',
        'content' => 'Content',
        'add' => 'Add',
        'creator' => 'Creator',
        'message' => 'Message',
        'created_at' => 'Created At',
        'is_closed' => 'Status',
        'closed_at' => 'Closed At',
        'closed_by' => 'Closed By',
        'open' => 'Open',
        'closed' => 'Closed',
        'archive_ticket' => 'Archive Ticket',
        'patch_note' => 'Patch Notes',
        'permissions' => 'Permissions',
        'role' => 'Roles',
        'ticket' => 'Tickets',
        'ticket_message' => 'Ticket Messages',
        'columns' => [
            'id' => 'ID',
            'creator' => 'Creator',
            'ticket_name' => 'Ticket Name',
            'message' => 'Message',
            'created_at' => 'Created At',
            'is_closed' => 'Status',
            'closed_at' => 'Closed At',
            'closed_by' => 'Closed By',
            'updated_at' => 'Updated At',
            'deleted_at' => 'Deleted At',
        ],
        'yes' => 'Yes',
        'no' => 'No',
        'toggle_permission' => 'Toggle Permission',
        'ticket_id' => 'Ticket ID',
        'description' => 'Description',
        'can_reply_ticket' => 'Can reply to tickets',
        'can_ban_permanently' => 'Can ban permanently',
        'can_ban_temporarily' => 'Can ban temporarily',
        'can_post_patchnotes' => 'Can post patchnotes',
        'can_manage_users' => 'Can manage users',
        'can_view_reports' => 'Can view reports',
        'can_edit_roles' => 'Can edit roles',
        'can_delete_tickets' => 'Can delete tickets',
        'no_records_found' => 'No records found',
        'content' => 'Content',
        'title' => 'Title',
        'admin_panel' => 'Admin Panel',
        'status' => 'Status',
        'role_columns' => [
            'id' => 'ID',
            'name' => 'Name',
            'image_tag' => 'Image Tag',
            'status' => 'Status',
            'is_active' => 'Active',
            'created_at' => 'Created At',
            'updated_at' => 'Updated At',
            'created_by' => 'Created By',
            'updated_by' => 'Updated By',
            'deleted_at' => 'Deleted At',
            'autorisation' => 'Authorization',
        ],
        'phone' => 'Phone',
        'total_tickets' => 'Total Tickets',
        'open_tickets' => 'Open Tickets',
        'closed_tickets' => 'Closed Tickets',
        'ban_user' => 'Ban User',
        'manage_role' => 'Manage Role',
        'view_profile' => 'View Profile',
        'ban_user_modal_title' => 'Ban User',
        'ban_user_modal_reason' => 'Reason:',
        'ban_user_modal_select_duration' => 'Select ban duration:',
        'ban_user_modal_cancel' => 'Cancel',
        'ban_user_modal_permanent' => 'Permanent',
        'manage_role_modal_title' => 'Manage Role',
        'manage_role_modal_close' => 'Close',
        'view_profile_modal_title' => 'View Profile',
        'view_profile_modal_close' => 'Close',
    ],
    'nl' => [
        'user_info' => 'Gebruikersinformatie',
        'id' => 'ID',
        'name' => 'Naam',
        'email' => 'Email',
        'grade' => 'Rang',
        'actions' => 'Acties',
        'modify' => 'Bewerken',
        'delete' => 'Verwijderen',
        'ticket_management' => 'Ticketbeheer',
        'title' => 'Titel',
        'status' => 'Status',
        'view' => 'Bekijken',
        'add_patchnote' => 'Patchnote toevoegen',
        'content' => 'Inhoud',
        'add' => 'Toevoegen',
        'creator' => 'Maker',
        'message' => 'Bericht',
        'created_at' => 'Aangemaakt op',
        'is_closed' => 'Status',
        'closed_at' => 'Gesloten op',
        'closed_by' => 'Gesloten door',
        'open' => 'Open',
        'closed' => 'Gesloten',
        'archive_ticket' => 'Archief Ticket',
        'patch_note' => 'Patchnotities',
        'permissions' => 'Machtigingen',
        'role' => 'Rollen',
        'ticket' => 'Tickets',
        'ticket_message' => 'Ticketberichten',
        'columns' => [
            'id' => 'ID',
            'creator' => 'Maker',
            'ticket_name' => 'Ticketnaam',
            'message' => 'Bericht',
            'created_at' => 'Aangemaakt op',
            'is_closed' => 'Status',
            'closed_at' => 'Gesloten op',
            'closed_by' => 'Gesloten door',
            'updated_at' => 'Bijgewerkt op',
            'deleted_at' => 'Verwijderd op',
        ],
        'yes' => 'Ja',
        'no' => 'Nee',
        'toggle_permission' => 'Permissie wijzigen',
        'ticket_id' => 'Ticket ID',
        'description' => 'Beschrijving',
        'can_reply_ticket' => 'Kan reageren op tickets',
        'can_ban_permanently' => 'Kan permanent verbannen',
        'can_ban_temporarily' => 'Kan tijdelijk verbannen',
        'can_post_patchnotes' => 'Kan patchnotes plaatsen',
        'can_manage_users' => 'Kan gebruikers beheren',
        'can_view_reports' => 'Kan rapporten bekijken',
        'can_edit_roles' => 'Kan rollen bewerken',
        'can_delete_tickets' => 'Kan tickets verwijderen',
        'no_records_found' => 'Geen records gevonden',
        'content' => 'Inhoud',
        'title' => 'Titel',
        'admin_panel' => 'Beheerderspaneel',
        'status' => 'Status',
        'role_columns' => [
            'id' => 'ID',
            'name' => 'Naam',
            'image_tag' => 'Afbeeldingstag',
            'status' => 'Status',
            'is_active' => 'Actief',
            'created_at' => 'Aangemaakt op',
            'updated_at' => 'Bijgewerkt op',
            'created_by' => 'Aangemaakt door',
            'updated_by' => 'Bijgewerkt door',
            'deleted_at' => 'Verwijderd op',
            'autorisation' => 'Autorisatie',
        ],
        'phone' => 'Telefoon',
        'total_tickets' => 'Totaal Tickets',
        'open_tickets' => 'Open Tickets',
        'closed_tickets' => 'Gesloten Tickets',
        'ban_user' => 'Gebruiker verbannen',
        'manage_role' => 'Rol beheren',
        'view_profile' => 'Profiel bekijken',
        'ban_user_modal_title' => 'Gebruiker verbannen',
        'ban_user_modal_reason' => 'Reden:',
        'ban_user_modal_select_duration' => 'Selecteer de duur van de ban:',
        'ban_user_modal_cancel' => 'Annuleren',
        'ban_user_modal_permanent' => 'Permanent',
        'manage_role_modal_title' => 'Rol beheren',
        'manage_role_modal_close' => 'Sluiten',
        'view_profile_modal_title' => 'Profiel bekijken',
        'view_profile_modal_close' => 'Sluiten',
    ],
];

$trans = $translations[$selected_lang];
?>
<!DOCTYPE html>
<html lang="<?= htmlspecialchars($selected_lang) ?>">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= $trans['admin_panel'] ?></title>
    <link href="https://fonts.googleapis.com/css2?family=Orbitron:wght@400;500;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">

    <style>
        :root {
            --purple-dark: #4c1d95;
            --red: #ef4444;
            --green: #22c55e;
            --purple: #6d28d9;
            --cyan-light: #a5f3fc;
            --gray-light: #e5e5e5;
            --hover-green: #22c55e;
            --white: #ffffff;
            --gray-dark: #2d3748;
            --hover-row: rgba(255, 255, 255, 0.1);
        }

        body {
            background-color: var(--gray-dark);
            font-family: 'Orbitron', sans-serif;
            color: var(--gray-light);
            margin: 0;
            padding: 0;
        }

        header, footer {
            background-color: var(--purple-dark);
            color: var(--white);
            padding: 1rem;
            text-align: center;
            box-shadow: 0 2px 5px rgba(0, 0, 0, 0.2);
        }

        .container {
            max-width: 1200px;
            margin: 0 auto;
            padding: 1rem;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 1rem;
            border-radius: 10px;
            overflow: hidden;
        }

        th, td {
            border: 1px solid rgba(255, 255, 255, 0.2);
            padding: 0.75rem;
            text-align: left;
        }

        th {
            background-color: var(--purple);
            color: var(--white);
            text-transform: uppercase;
            font-size: 0.9rem;
        }

        td {
            background-color: rgba(255, 255, 255, 0.05);
            transition: background-color 0.3s ease;
        }

        tr:hover td {
            background-color: var(--hover-row);
        }

        .table-container {
            overflow-x: auto;
            margin-bottom: 1.5rem;
        }

        .table-container::-webkit-scrollbar {
            height: 8px;
        }

        .table-container::-webkit-scrollbar-thumb {
            background-color: var(--purple-dark);
            border-radius: 10px;
        }

        .table-container::-webkit-scrollbar-track {
            background-color: rgba(255, 255, 255, 0.1);
        }

        /* Adjust button styles for better visibility */
        button {
            padding: 0.5rem 1rem; /* Adjust padding */
            font-size: 0.875rem; /* Ensure text is readable */
            transition: background-color 0.3s ease, transform 0.2s ease;
        }

        button:hover {
            transform: scale(1.05);
        }

        .bg-green-500:hover {
            background-color: #16a34a;
        }

        .bg-red-500:hover {
            background-color: #dc2626;
        }

        .bg-purple-500:hover {
            background-color: #7c3aed; /* Ensure hover effect is visible */
        }

        h1, h2 {
            text-align: center;
            color: var(--cyan-light);
        }

        h1 {
            font-size: 2.5rem;
            margin-bottom: 1.5rem;
        }

        h2 {
            font-size: 2rem;
            margin-bottom: 1rem;
        }

        /* Modal container styles */
        .modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            justify-content: center;
            align-items: center;
            z-index: 1000;
        }

        .modal.active {
            display: flex;
        }

        .modal-content {
            background-color: var(--gray-dark); /* Match page background */
            color: var(--gray-light); /* Match text color */
            padding: 2rem;
            border-radius: 10px;
            width: 90%;
            max-width: 400px;
            text-align: center;
            box-shadow: 0 4px 10px rgba(0, 0, 0, 0.3);
        }

        .modal-content button {
            margin: 0.5rem;
        }

        .modal-content h3 {
            color: var(--cyan-light); /* Match heading color */
        }

        .modal-content textarea {
            background-color: var(--gray-light);
            color: var(--gray-dark);
            border: 1px solid var(--purple-dark);
            border-radius: 5px;
            padding: 0.5rem;
            width: 100%;
        }
    </style>
    <script>
        // JavaScript to handle modal display
        function openModal(modalId) {
            document.getElementById(modalId).classList.add('active');
        }

        function closeModal(modalId) {
            document.getElementById(modalId).classList.remove('active');
        }

        // Function to handle banning a user
        async function banUser(userId, duration, reason) {
            const response = await fetch('ban_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ user_id: userId, duration: duration, reason: reason })
            });

            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    const banButton = document.getElementById(`banButton-${userId}`);
                    banButton.textContent = 'Unban';
                    banButton.classList.remove('bg-red-500', 'hover:bg-red-600');
                    banButton.classList.add('bg-green-500', 'hover:bg-green-600');
                    banButton.onclick = () => unbanUser(userId);
                    closeModal(`banModal-${userId}`);
                    alert(result.message);
                } else {
                    alert(result.error);
                }
            } else {
                alert('An error occurred while banning the user.');
            }
        }

        // Function to handle unbanning a user
        async function unbanUser(userId) {
            const response = await fetch('unban_user.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ user_id: userId })
            });

            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    const banButton = document.getElementById(`banButton-${userId}`);
                    banButton.textContent = 'Ban';
                    banButton.classList.remove('bg-green-500', 'hover:bg-green-600');
                    banButton.classList.add('bg-red-500', 'hover:bg-red-600');
                    banButton.onclick = () => openModal(`banModal-${userId}`);
                    alert(result.message);
                } else {
                    alert(result.error);
                }
            } else {
                alert('An error occurred while unbanning the user.');
            }
        }

        async function updateRole(userId, newRole) {
            const response = await fetch('update_role.php', {
                method: 'POST',
                headers: { 'Content-Type': 'application/json' },
                body: JSON.stringify({ user_id: userId, id_perm: newRole })
            });

            if (response.ok) {
                const result = await response.json();
                if (result.success) {
                    alert(result.message);
                    closeModal(`roleModal-${userId}`);
                    location.reload(); // Reload to reflect changes
                } else {
                    alert(result.error);
                }
            } else {
                alert('An error occurred while updating the role.');
            }
        }
    </script>
</head>
<body>
    <?php include 'header.php'; ?>
    <div class="container mx-auto mt-10">
        <h1 class="text-4xl text-center text-gray-light mb-10"><?= $trans['admin_panel'] ?></h1>

        <div class="section-container mb-10">
            <h2 class="section-title"><?= $trans['user_info'] ?></h2>
            <div class="table-container">
                <table class="table-auto w-full text-gray-light">
                    <thead>
                        <tr>
                            <?php
                            $columns = [
                                'id' => $trans['id'],
                                'name' => $trans['name'],
                                'email' => $trans['email'],
                                'phone' => $trans['phone'],
                                'ticket_count' => $trans['total_tickets'],
                                'open_ticket_count' => $trans['open_tickets'],
                                'closed_ticket_count' => $trans['closed_tickets'],
                                'created_at' => $trans['created_at'],
                                'updated_at' => $trans['updated_at'],
                                'actions' => $trans['actions'],
                            ];
                            foreach ($columns as $column => $label) {
                                echo "<th class='px-4 py-2'>" . htmlspecialchars($label) . "</th>";
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT * FROM users");
                        if ($stmt->rowCount() > 0) {
                            while ($row = $stmt->fetch()) {
                                $isBanned = $row['is_banned'] ?? false; // Assume `is_banned` column exists
                                $banDuration = $row['ban_duration'] ?? null; // Assume `ban_duration` column exists

                                echo "<tr>";
                                foreach (array_keys($columns) as $column) {
                                    if ($column === 'actions') {
                                        echo "<td class='border px-4 py-2 text-center'>
                                                <button id='banButton-{$row['id']}' onclick=\"openModal('banModal-{$row['id']}')\" class='px-4 py-2 bg-red-500 text-white rounded hover:bg-red-600'>Ban</button>
                                                <button id='roleButton-{$row['id']}' onclick=\"openModal('roleModal-{$row['id']}')\" class='px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600'>Role</button>
                                                <button id='profileButton-{$row['id']}' onclick=\"openModal('profileModal-{$row['id']}')\" class='px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600'>Profile</button>
                                              </td>";
                                    } else {
                                        echo "<td class='border px-4 py-2'>" . htmlspecialchars($row[$column] ?? '') . "</td>";
                                    }
                                }
                                echo "</tr>";

                                // Ban Modal
                                echo "<div id='banModal-{$row['id']}' class='modal'>
                                        <div class='modal-content'>
                                            <h3>{$trans['ban_user_modal_title']}: {$row['name']}</h3>
                                            <p>{$trans['ban_user_modal_select_duration']}</p>
                                            <form onsubmit=\"event.preventDefault(); banUser({$row['id']}, this.duration.value, this.reason.value);\">
                                                <div class='mb-4'>
                                                    <label for='reason' class='block text-gray-700'>{$trans['ban_user_modal_reason']}</label>
                                                    <textarea name='reason' id='reason' rows='3' class='w-full border rounded px-2 py-1' required></textarea>
                                                </div>
                                                <div class='mb-4'>
                                                    <button type='button' onclick=\"banUser({$row['id']}, '1d', document.getElementById('reason').value)\" class='px-4 py-2 bg-red-500 text-white rounded'>1 Day</button>
                                                    <button type='button' onclick=\"banUser({$row['id']}, '3d', document.getElementById('reason').value)\" class='px-4 py-2 bg-red-500 text-white rounded'>3 Days</button>
                                                    <button type='button' onclick=\"banUser({$row['id']}, '5d', document.getElementById('reason').value)\" class='px-4 py-2 bg-red-500 text-white rounded'>5 Days</button>
                                                    <button type='button' onclick=\"banUser({$row['id']}, '7d', document.getElementById('reason').value)\" class='px-4 py-2 bg-red-500 text-white rounded'>7 Days</button>
                                                    <button type='button' onclick=\"banUser({$row['id']}, '30d', document.getElementById('reason').value)\" class='px-4 py-2 bg-red-500 text-white rounded'>30 Days</button>
                                                    <button type='button' onclick=\"banUser({$row['id']}, '180d', document.getElementById('reason').value)\" class='px-4 py-2 bg-red-500 text-white rounded'>180 Days</button>
                                                    <button type='button' onclick=\"banUser({$row['id']}, '365d', document.getElementById('reason').value)\" class='px-4 py-2 bg-red-500 text-white rounded'>365 Days</button>
                                                    <button type='button' onclick=\"banUser({$row['id']}, 'permanent', document.getElementById('reason').value)\" class='px-4 py-2 bg-red-500 text-white rounded'>{$trans['ban_user_modal_permanent']}</button>
                                                </div>
                                            </form>
                                            <button onclick=\"closeModal('banModal-{$row['id']}')\" class='px-4 py-2 bg-gray-500 text-white rounded'>{$trans['ban_user_modal_cancel']}</button>
                                        </div>
                                      </div>";

                                // Role Modal
                                echo "<div id='roleModal-{$row['id']}' class='modal'>
                                        <div class='modal-content'>
                                            <h3>{$trans['manage_role_modal_title']}: {$row['name']}</h3>
                                            <p>Select a new role:</p>
                                            <form onsubmit=\"event.preventDefault(); updateRole({$row['id']}, this.role.value);\">
                                                <div class='mb-4'>
                                                    <select name='role' class='w-full border rounded px-2 py-1'>
                                                        <option value='1'" . ($row['id_perm'] == 1 ? " selected" : "") . ">Utilisateur</option>
                                                        <option value='2'" . ($row['id_perm'] == 2 ? " selected" : "") . ">Admin</option>
                                                        <option value='3'" . ($row['id_perm'] == 3 ? " selected" : "") . ">Dev</option>
                                                        <option value='4'" . ($row['id_perm'] == 4 ? " selected" : "") . ">Modo</option>
                                                        <option value='5'" . ($row['id_perm'] == 5 ? " selected" : "") . ">Guide</option>
                                                    </select>
                                                </div>
                                                <button type='submit' class='px-4 py-2 bg-purple-500 text-white rounded hover:bg-purple-600'>Update Role</button>
                                            </form>
                                            <button onclick=\"closeModal('roleModal-{$row['id']}')\" class='px-4 py-2 bg-gray-500 text-white rounded'>{$trans['manage_role_modal_close']}</button>
                                        </div>
                                      </div>";
                            }
                        } else {
                            echo "<tr><td colspan='" . count($columns) . "' class='text-center border px-4 py-2'>{$trans['no_records_found']}</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Permissions Section -->
        <div class="section-container mb-10">
            <h2 class="section-title"><?= $trans['permissions'] ?></h2>
            <div class="table-container">
                <table class="table-auto w-full text-gray-light">
                    <thead>
                        <tr>
                            <?php
                            $columns = [
                                'id_perm' => $trans['columns']['id'] ?? 'ID Permission',
                                'nom' => $trans['columns']['name'] ?? 'Nom',
                                'can_reply_ticket' => $trans['can_reply_ticket'],
                                'can_ban_permanently' => $trans['can_ban_permanently'],
                                'can_ban_temporarily' => $trans['can_ban_temporarily'],
                                'can_post_patchnotes' => $trans['can_post_patchnotes'],
                                'can_manage_users' => $trans['can_manage_users'],
                                'can_view_reports' => $trans['can_view_reports'],
                                'can_edit_roles' => $trans['can_edit_roles'],
                                'can_delete_tickets' => $trans['can_delete_tickets'],
                            ];
                            foreach ($columns as $column => $label) {
                                echo "<th class='px-4 py-2'>" . htmlspecialchars($label) . "</th>";
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT * FROM permissions");
                        if ($stmt->rowCount() > 0) {
                            while ($row = $stmt->fetch()) {
                                echo "<tr>";
                                foreach (array_keys($columns) as $column) {
                                    if (in_array($column, ['can_reply_ticket', 'can_ban_permanently', 'can_ban_temporarily', 'can_post_patchnotes', 'can_manage_users', 'can_view_reports', 'can_edit_roles', 'can_delete_tickets'])) {
                                        $buttonClass = $row[$column] ? 'bg-green-500 hover:bg-green-600' : 'bg-red-500 hover:bg-red-600';
                                        $buttonText = $row[$column] ? $trans['yes'] : $trans['no'];
                                        echo "<td class='border px-4 py-2 text-center'>
                                                <form method='POST' action='update_permission.php'>
                                                    <input type='hidden' name='id_perm' value='{$row['id_perm']}'>
                                                    <input type='hidden' name='column' value='{$column}'>
                                                    <input type='hidden' name='lang' value='" . htmlspecialchars($selected_lang) . "'>
                                                    <button type='submit' class='px-4 py-2 text-white rounded {$buttonClass}' title='{$trans['toggle_permission']}'>{$buttonText}</button>
                                                </form>
                                              </td>";
                                    } else {
                                        echo "<td class='border px-4 py-2'>" . htmlspecialchars($row[$column] ?? '') . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='" . count($columns) . "' class='text-center border px-4 py-2'>{$trans['no_records_found']}</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Role Section -->
        <div class="section-container mb-10">
            <h2 class="section-title"><?= $trans['role'] ?></h2>
            <div class="table-container">
                <table class="table-auto w-full text-gray-light">
                    <thead>
                        <tr>
                            <?php
                            $columns = [
                                'id', 'name', 'image_tag', 'is_active', 
                                'created_at', 'updated_at', 'created_by', 
                                'updated_by', 'deleted_at', 'autorisation'
                            ];
                            foreach ($columns as $column) {
                                echo "<th class='px-4 py-2'>" . htmlspecialchars($trans['role_columns'][$column] ?? $column) . "</th>";
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT * FROM role");
                        if ($stmt->rowCount() > 0) {
                            while ($row = $stmt->fetch()) {
                                echo "<tr>";
                                foreach ($columns as $column) {
                                    if ($column === 'is_active') {
                                        $value = $row[$column] ? $trans['yes'] : $trans['no'];
                                        echo "<td class='border px-4 py-2 text-center'>{$value}</td>";
                                    } elseif ($column === 'created_by') {
                                        $userStmt = $pdo->prepare("SELECT name FROM users WHERE id = :id");
                                        $userStmt->execute(['id' => $row['created_by']]);
                                        $createdByName = $userStmt->fetchColumn();
                                        echo "<td class='border px-4 py-2'>" . htmlspecialchars($createdByName ?: $trans['no_records_found']) . "</td>";
                                    } elseif ($column === 'updated_by') {
                                        $userStmt = $pdo->prepare("SELECT name FROM users WHERE id = :id");
                                        $userStmt->execute(['id' => $row['updated_by']]);
                                        $updatedByName = $userStmt->fetchColumn();
                                        echo "<td class='border px-4 py-2'>" . htmlspecialchars($updatedByName ?: $trans['no_records_found']) . "</td>";
                                    } elseif ($column === 'deleted_at') {
                                        $deletedAt = $row['deleted_at'] ?? '';
                                        if ($deletedAt) {
                                            $formattedDate = date('d/m/Y', strtotime($deletedAt));
                                            $formattedTime = date('H:i:s', strtotime($deletedAt));
                                            echo "<td class='border px-4 py-2 text-center'>{$formattedDate}<br>{$formattedTime}</td>";
                                        } else {
                                            echo "<td class='border px-4 py-2 text-center'>-</td>";
                                        }
                                    } elseif ($column === 'autorisation') {
                                        // Récupérer le nom de l'autorisation
                                        $permStmt = $pdo->prepare("SELECT nom FROM permissions WHERE id_perm = :id_perm");
                                        $permStmt->execute(['id_perm' => $row['autorisation']]);
                                        $permissionName = $permStmt->fetchColumn() ?? $trans['no_records_found'];
                                        echo "<td class='border px-4 py-2'>" . htmlspecialchars($permissionName) . "</td>";
                                    } else {
                                        echo "<td class='border px-4 py-2'>" . htmlspecialchars($row[$column] ?? '') . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='" . count($columns) . "' class='text-center border px-4 py-2'>{$trans['no_records_found']}</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Ticket Section -->
        <div class="section-container mb-10">
            <h2 class="section-title"><?= $trans['ticket'] ?></h2>
            <div class="table-container">
                <table class="table-auto w-full text-gray-light">
                    <thead>
                        <tr>
                            <?php
                            $columns = ['id', 'creator', 'ticket_name', 'message', 'created_at', 'is_closed', 'closed_at', 'closed_by', 'updated_at', 'deleted_at'];
                            foreach ($columns as $column) {
                                echo "<th class='px-4 py-2'>" . htmlspecialchars($trans['columns'][$column]) . "</th>";
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT * FROM ticket");
                        if ($stmt->rowCount() > 0) {
                            while ($row = $stmt->fetch()) {
                                echo "<tr>";
                                foreach ($columns as $column) {
                                    if (in_array($column, ['created_at', 'updated_at', 'closed_at', 'deleted_at'])) {
                                        $date = $row[$column] ?? '';
                                        if ($date) {
                                            $formattedDate = date('d/m/Y', strtotime($date));
                                            $formattedTime = date('H:i:s', strtotime($date));
                                            echo "<td class='border px-4 py-2 text-center'>{$formattedDate}<br>{$formattedTime}</td>";
                                        } else {
                                            echo "<td class='border px-4 py-2 text-center'>-</td>";
                                        }
                                    } else {
                                        echo "<td class='border px-4 py-2'>" . htmlspecialchars($row[$column] ?? '') . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='" . count($columns) . "' class='text-center border px-4 py-2'>Aucun enregistrement trouvé</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Ticket Message Section -->
        <div class="section-container mb-10">
            <h2 class="section-title"><?= $trans['ticket_message'] ?></h2>
            <div class="table-container">
                <table class="table-auto w-full text-gray-light">
                    <thead>
                        <tr>
                            <?php
                            $columns = ['id', 'ticket_id', 'message', 'created_at', 'updated_at'];
                            foreach ($columns as $column) {
                                echo "<th class='px-4 py-2'>" . htmlspecialchars($trans['columns'][$column] ?? $column) . "</th>";
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT * FROM ticket_message");
                        if ($stmt->rowCount() > 0) {
                            while ($row = $stmt->fetch()) {
                                echo "<tr>";
                                foreach ($columns as $column) {
                                    if (in_array($column, ['created_at', 'updated_at', 'closed_at', 'deleted_at'])) {
                                        $date = $row[$column] ?? '';
                                        if ($date) {
                                            $formattedDate = date('d/m/Y', strtotime($date));
                                            $formattedTime = date('H:i:s', strtotime($date));
                                            echo "<td class='border px-4 py-2 text-center'>{$formattedDate}<br>{$formattedTime}</td>";
                                        } else {
                                            echo "<td class='border px-4 py-2 text-center'>-</td>";
                                        }
                                    } else {
                                        echo "<td class='border px-4 py-2'>" . htmlspecialchars($row[$column] ?? '') . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='" . count($columns) . "' class='text-center border px-4 py-2'>Aucun enregistrement trouvé</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
        <div class="section-container mb-10">
            <h2 class="section-title"><?= $trans['archive_ticket'] ?></h2>
            <div class="table-container">
                <table class="table-auto w-full text-gray-light">
                    <thead>
                        <tr>
                            <?php
                            $columns = ['id', 'creator', 'ticket_name', 'message', 'created_at', 'is_closed', 'closed_at', 'closed_by', 'updated_at', 'deleted_at'];
                            foreach ($columns as $column) {
                                echo "<th class='px-4 py-2'>" . htmlspecialchars($trans['columns'][$column] ?? $column) . "</th>";
                            }
                            ?>
                        </tr>
                    </thead>
                    <tbody>
                        <?php
                        $stmt = $pdo->query("SELECT * FROM archive_ticket");
                        if ($stmt->rowCount() > 0) {
                            while ($row = $stmt->fetch()) {
                                echo "<tr>";
                                foreach ($columns as $column) {
                                    if (in_array($column, ['created_at', 'updated_at', 'closed_at', 'deleted_at'])) {
                                        $date = $row[$column] ?? '';
                                        if ($date) {
                                            $formattedDate = date('d/m/Y', strtotime($date));
                                            $formattedTime = date('H:i:s', strtotime($date));
                                            echo "<td class='border px-4 py-2 text-center'>{$formattedDate}<br>{$formattedTime}</td>";
                                        } else {
                                            echo "<td class='border px-4 py-2 text-center'>-</td>";
                                        }
                                    } else {
                                        echo "<td class='border px-4 py-2'>" . htmlspecialchars($row[$column] ?? '') . "</td>";
                                    }
                                }
                                echo "</tr>";
                            }
                        } else {
                            echo "<tr><td colspan='" . count($columns) . "' class='text-center border px-4 py-2'>{$trans['no_records_found']}</td></tr>";
                        }
                        ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <?php include 'footer.php'; ?>
</body>
</html>
