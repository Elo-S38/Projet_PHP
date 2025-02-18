<?php
require 'config.php';
require 'verify_login.php';

if (isset($_GET["delete"]))
{
	echo "<script>confirm('Vous ne pouvez pas supprimer votre propre compte')</script>";
}

try {
    $stmt = $pdo->query("
        SELECT *
		FROM benevoles
		ORDER BY nom
    ");

    $benevoles = $stmt->fetchAll();

} catch (PDOException $e) {
    echo "Erreur de base de données : " . $e->getMessage();
    exit;
}

$stmt2 = $pdo->query("
	SELECT d.quantite_kg, d.id_collecte, c.id, c.id_benevole, b.id
	FROM collectes c
	JOIN benevoles b ON b.id = c.id_benevole
	JOIN dechets_collectes d ON d.id_collecte = c.id
	");

$total = $stmt2->fetchAll();

foreach ($total as $tot) {
    $idBenevole = $tot['id_benevole'];
    $quantiteKg = $tot['quantite_kg'];
    
    // Sum the quantities by id_benevole
    if (!isset($sumByBenevole[$idBenevole])) {
        $sumByBenevole[$idBenevole] = 0;
    }
    $sumByBenevole[$idBenevole] += $quantiteKg;
}

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Bénévoles</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-gray-100 text-gray-900">
<div class="flex h-screen">
    <!-- Barre de navigation -->
    <div class="bg-gray-300 text-white w-70 p-6">
        <!-- <h2 class="text-2xl font-bold mb-6">Dashboard</h2> -->
        <img src="Logo.png" alt="logoLC" class="w-64 mb-14">
        <ul class="list-none space-y-5">
        
            <li><a href="collection_list.php" class="list-none flex items-center py-2 px-3 bg-cyan-700 hover:bg-cyan-900 text-white rounded-lg"><i class="fas fa-tachometer-alt mr-3"></i> Tableau de bord</a></li>
			<li><a href="volunteer_list.php" class="flex items-center py-2 px-3 bg-cyan-700 hover:bg-cyan-900 text-white rounded-lg"><i class="fa-solid fa-list mr-3"></i> Liste des bénévoles</a></li>
            <li><a href="my_account.php" class="flex items-center py-2 px-3 bg-cyan-700 hover:bg-cyan-900 text-white rounded-lg"><i class="fas fa-cogs mr-3"></i> Mon compte</a></li>
			<li><a href="logout.php" class="flex items-center py-2 px-3 bg-red-600 hover:bg-red-700 rounded-lg" onclick="return confirm('Voulez vous vraiment vous déconnecter ?')">Déconnexion</a></li>
		</ul>
    </div>

    <!-- Contenu principal -->
    <div class="flex-1 p-8 overflow-y-auto">
        <!-- Titre -->
        <h1 class="text-4xl font-bold text-black mb-10">Liste des Bénévoles</h1>

        <!-- Tableau des bénévoles -->
        <div class="overflow-hidden rounded-lg shadow-lg bg-white">
            <table class="w-full table-auto border-collapse">
                <thead class="bg-[#005a8d] text-white">
                <tr>
                    <th class="py-3 px-4 text-left">Nom</th>
                    <th class="py-3 px-4 text-left">Email</th>
                    <th class="py-3 px-4 text-left">Rôle</th>
					<th class="py-3 px-4 text-left">Poids total déchets ramassées (en kg)</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                 <?php foreach ($benevoles as $benevole) : ?> <!-- boucle pour afficher les benevoles -->
                    <tr class="hover:bg-gray-100 transition duration-200">
                        <td class="py-3 px-4"><?= $benevole["nom"] ?></td>
                        <td class="py-3 px-4"><?= htmlspecialchars($benevole['email']) ?></td>
                        <td class="py-3 px-4">
                            <?= $benevole['role'] ? htmlspecialchars($benevole['role']) : 'Aucun rôle' ?>
                        </td>
						<td class="py-3 px-4"> <?= isset($sumByBenevole[$benevole["id"]]) ? $sumByBenevole[$benevole["id"]] : 0 ?> </td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>

