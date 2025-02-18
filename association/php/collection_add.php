<?php
require 'config.php';
require 'verify_login.php';

ini_set('display_errors', 1);
error_reporting(E_ALL);

// Récupérer la liste des bénévoles
$stmt_benevoles = $pdo->query("SELECT id, nom FROM benevoles ORDER BY nom");
$stmt_benevoles->execute();
$benevoles = $stmt_benevoles->fetchAll();

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $date = $_POST["date"];
    $lieu = $_POST["lieu"];
    $benevole_id = $_POST["benevole"];  // ID du bénévole choisi, modifié ici pour correspondre au formulaire

    // Insérer la collecte avec le bénévole sélectionné
    $stmt = $pdo->prepare("INSERT INTO collectes (date_collecte, lieu, id_benevole) VALUES (?, ?, ?)");
    if (!$stmt->execute([$date, $lieu, $benevole_id])) {
        die('Erreur lors de l\'insertion dans la base de données.');
    }

    header("Location: collection_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ajouter une collecte</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">

<div class="flex h-screen">
    <div class="bg-gray-300 text-white w-70 p-6">
        <!-- <h2 class="text-2xl font-bold mb-6">Dashboard</h2> -->
        <img src="Logo.png" alt="logoLC" class="w-64 mb-14">

        <ul class="list-none space-y-5">
        
            <li><a href="collection_list.php" class="list-none flex items-center py-2 px-3 bg-cyan-700 hover:bg-cyan-900 text-white rounded-lg"><i class="fas fa-tachometer-alt mr-3"></i> Tableau de bord</a></li>
            <li><a href="collection_add.php" class="flex items-center py-2 px-3 bg-cyan-700 hover:bg-cyan-900 text-white rounded-lg"><i class="fas fa-plus-circle mr-3"></i> Ajouter une collecte</a></li>
            <li><a href="volunteer_list.php" class="flex items-center py-2 px-3 bg-cyan-700 hover:bg-cyan-900 text-white rounded-lg"><i class="fa-solid fa-list mr-3"></i> Liste des bénévoles</a></li>
            <li><a href="user_add.php" class="flex items-center py-2 px-3 bg-cyan-700 hover:bg-cyan-900 text-white rounded-lg"><i class="fas fa-user-plus mr-3"></i> Ajouter un bénévole</a></li>
            <li><a href="my_account.php" class="flex items-center py-2 px-3 bg-cyan-700 hover:bg-cyan-900 text-white rounded-lg"><i class="fas fa-cogs mr-3"></i> Mon compte</a></li>
</ul>

<div class="mt-6">
            <button onclick="logout()" class="w-full bg-red-700 font-bold hover:bg-red-900 text-white py-2 rounded-lg shadow-md">
                Déconnexion
            </button>
        </div>

        <!-- <ul class="list-none p-2.5">
            <li><a href="collection_list.php" class="flex items-center py-2 px-3 font-bold hover:bg-[#007acc] rounded-lg"><i class="fas fa-tachometer-alt mr-3"></i> Tableau de bord</a></li>
            <li><a href="volunteer_list.php" class="flex items-center py-2 px-3 font-bold hover:bg-[#007acc] rounded-lg"><i class="fa-solid fa-list mr-3"></i> Liste des bénévoles</a></li>
            <li>
                <a href="user_add.php" class="flex items-center py-2 px-3 font-bold hover:bg-[#007acc] rounded-lg">
                    <i class="fas fa-user-plus mr-3"></i> Ajouter un bénévole
                </a>
            </li>
            <li><a href="my_account.php" class="flex items-center py-2 px-3 font-bold hover:bg-[#007acc] rounded-lg"><i class="fas fa-cogs mr-3"></i> Mon compte</a></li>
            
            <li><a href="logout.php" class="flex items-center py-2 px-3 bg-red-600 hover:bg-red-700 rounded-lg" onclick="return confirm('Voulez vous vraiment vous déconnecter ?')">
              Déconnexion
            </a></li>
          </ul> -->

    </div>

    <!-- Contenu principal -->
    <div class="flex-1 p-8 overflow-y-auto">
        <!-- Titre -->
        <h1 class="text-4xl font-bold text-black mb-6">Ajouter une collecte</h1>

        <!-- Formulaire -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <form method="POST" class="space-y-4">
                <!-- Date -->
                <div>
                    <label class="block text-sm text-gray-700 font-bold mb-4" >Date :</label>
                    <input type="date" name="date" required
                           class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Lieu -->
                <div>
                    <label class="block text-sm text-gray-700 font-bold mb-4">Lieu :</label>
                    <input type="text" name="lieu" required
                           class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                </div>

                <!-- Bénévole responsable -->
                <div>
                    <label class="block text-sm text-gray-700 font-bold mb-4">Bénévole Responsable :</label>
                    <select name="benevole" required
                            class="w-full p-2 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
                        <option value="">Sélectionner un bénévole</option>
                        <?php foreach ($benevoles as $benevole): ?>
                            <option value="<?= $benevole['id'] ?>" <?= $benevole['id'] ==  'selected' ?>>
                                <?= htmlspecialchars($benevole['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <!-- Boutons -->
                <div class="flex justify-end space-x-4">
                    <a href="collection_list.php" class="bg-gray-400 font-bold hover:bg-gray-600 text-white px-4 py-2 rounded-lg shadow">Annuler</a>
                    <button type="submit" class="bg-cyan-700 font-bold hover:bg-[#005a8d] font-bold hover:bg-cyan-600 text-white px-4 py-2 rounded-lg shadow">
                        ➕ Ajouter
                    </button>
                </div>
            </form>
        </div>

    </div>
</div>

</body>
</html>
