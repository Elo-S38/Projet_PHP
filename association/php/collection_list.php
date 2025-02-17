<?php
require 'config.php';

try {
    $stmt = $pdo->query("
        SELECT c.id, c.date_collecte, c.lieu, b.nom
        FROM collectes c
        LEFT JOIN benevoles b ON c.id_benevole = b.id
        ORDER BY c.date_collecte DESC
    ");

    $query = $pdo->prepare("SELECT nom FROM benevoles WHERE role = 'admin' LIMIT 1");
    $query->execute();

    $collectes = $stmt->fetchAll();

	$stmt2 = $pdo->query("
	SELECT id, id_collecte, type_dechet, quantite_kg
	FROM dechets_collectes
	");
	$dechets = $stmt2->fetchAll();

	$stmt3 = $pdo->query("
			SELECT SUM(quantite_kg)
			FROM dechets_collectes
		");
	$poids_total = $stmt3->fetchAll();

	foreach ($dechets as $dechet) {
		$type_dechet = $dechet['type_dechet'];
		$quantiteKg = $dechet['quantite_kg'];
		
		// Sum the quantities by type_dechet
		if (!isset($sumByTypeDechet[$type_dechet])) {
			$sumByTypeDechet[$type_dechet] = 0;
		}
		$sumByTypeDechet[$type_dechet] += $quantiteKg;
	}

	

    $admin = $query->fetch(PDO::FETCH_ASSOC);
    $adminNom = $admin ? htmlspecialchars($admin['nom']) : 'Aucun administrateur trouvé';

} catch (PDOException $e) {
    echo "Erreur de base de données : " . $e->getMessage();
    exit;
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
    <title>Liste des Collectes</title>
    <head>
        <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300;400;700&family=Lora:wght@400;700&family=Montserrat:wght@300;400;700&family=Open+Sans:wght@300;400;700&family=Poppins:wght@300;400;700&family=Playfair+Display:wght@400;700&family=Raleway:wght@300;400;700&family=Nunito:wght@300;400;700&family=Merriweather:wght@300;400;700&family=Oswald:wght@300;400;700&display=swap" rel="stylesheet">
    </head>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
</head>
<body class="bg-white-100 text-gray-900">
<div class="flex h-screen">
    <!-- Barre de navigation -->
    <div class="bg-stone-200 text-black w-64 p-6">
    <img src="Logo LC.png" alt="logoLC">
        <h2 class="text-2xl font-bold mb-6"></h2>
            <li><a href="collection_list.php" class="flex items-center py-2 px-3 bg-cyan-700 hover:bg-cyan-900 text-white rounded-lg"><i class="fas fa-tachometer-alt mr-3"></i> Tableau de bord</a></li>
            <li><a href="collection_add.php" class="flex items-center py-2 px-3 bg-cyan-700 hover:bg-cyan-900 text-white rounded-lg"><i class="fas fa-plus-circle mr-3"></i> Ajouter une collecte</a></li>
            <li><a href="volunteer_list.php" class="flex items-center py-2 px-3 bg-cyan-700 hover:bg-cyan-900 text-white rounded-lg"><i class="fa-solid fa-list mr-3"></i> Liste des bénévoles</a></li>
            <li><a href="user_add.php" class="flex items-center py-2 px-3 bg-cyan-700 hover:bg-cyan-900 text-white rounded-lg"><i class="fas fa-user-plus mr-3"></i> Ajouter un bénévole</a></li>
            <li><a href="my_account.php" class="flex items-center py-2 px-3 bg-cyan-700 hover:bg-cyan-900 text-white rounded-lg"><i class="fas fa-cogs mr-3"></i> Mon compte</a></li>
        <div class="mt-6">
            <button onclick="logout()" class="w-full bg-red-500 hover:bg-red-800 text-white py-2 rounded-lg shadow-md">
                Déconnexion
            </button>
        </div>
    </div>

    <!-- Contenu principal -->
     
    <div class="flex-1 p-8 overflow-y-auto">
        <!-- Titre -->
        <h1 class="text-6xl font-bold text-cyan-900 mb-6 text-center">Liste des Collectes de Déchets</h1>

        <!-- Message de notification (ex: succès de suppression ou ajout) -->
        <?php if (isset($_GET['message'])): ?>
            <div class="bg-green-100 text-green-800 p-4 rounded-md mb-6">
                <?= htmlspecialchars($_GET['message']) ?>
            </div>
        <?php endif; ?>

<!-- Cartes d'informations -->
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
    <!-- Nombre total de collectes -->
    <div class="bg-cyan-600 opacity-85 p-6 rounded-lg shadow-lg w-full">
        <h3 class="text-2xl font-semibold text-white mb-3 text-center">Total des Collectes</h3>
        <p class="text-3xl font-bold text-white text-center"><?= count($collectes) ?></p>
    </div>

    <!-- Dernière collecte -->
    <div class="bg-cyan-600 opacity-85 p-6 rounded-lg shadow-lg w-full">
        <h3 class="text-2xl font-semibold text-white mb-3 text-center">Dernière Collecte</h3>
        <p class="text-lg text-white text-center"><?= htmlspecialchars($collectes[0]['lieu']) ?></p>
        <p class="text-lg text-white text-center"><?= date('d/m/Y', strtotime($collectes[0]['date_collecte'])) ?></p>
    </div>

    <!-- Bénévole Responsable -->
    <div class="bg-cyan-600 opacity-85 p-6 rounded-lg shadow-lg w-full">
        <h3 class="text-2xl font-semibold text-white mb-3 text-center">Bénévole Admin</h3>
        <p class="text-lg text-white text-center"><?= $adminNom ?></p>
    </div>

    <!-- Total des déchets collectés -->
    <div class="bg-cyan-600 opacity-85 p-6 rounded-lg shadow-lg w-full">
        <h3 class="text-2xl font-semibold text-white mb-3 text-center">Total des déchets collectés</h3>
        <p class="text-lg text-white text-center"><?= round($poids_total[0]["SUM(quantite_kg)"], 2) . " kg"?></p>
    </div>

<!-- Totaux des déchets collectés par type de déchets et Donut (en utilisant Flexbox) -->
<div class="bg-cyan-600 opacity-85 p-6 rounded-lg shadow-lg flex flex-col lg:flex-row space-y-6 lg:space-y-0 lg:space-x-6 col-span-4 w-full">
    <div class="flex-1">
        <h3 class="text-3xl font-semibold text-white mb-3 text-center">Totaux des déchets collectés par type</h3>
        <p class="text-xl font-semibold text-white text-center">
            <?php
            // Affichage des quantités par type de déchet
            foreach ($sumByTypeDechet as $type_dechet => $quantite) {
                echo $type_dechet . ": " . $quantite . " kg" . "<br>";
            }
            ?>
        </p>
    </div>
    
    <div class="flex-1">
        <div class="w-full h-full flex justify-center items-center">
            <canvas id="monDonut" width="300" height="300" > </canvas>
        </div>
    </div>
</div>

<script>
    const ctx = document.getElementById('monDonut').getContext('2d');

    // Données PHP converties en format JavaScript
    const labels = <?php echo json_encode(array_keys($sumByTypeDechet)); ?>;
    const dataValues = <?php echo json_encode(array_values($sumByTypeDechet)); ?>;

    new Chart(ctx, {
        type: 'doughnut', // Le type 'doughnut' pour un donut
        data: {
            labels: labels, // Utilisation des labels (types de déchets)
            datasets: [{
                data: dataValues, // Utilisation des valeurs des quantités
                backgroundColor: ['#ce6a6b', '#36A2EB', '#ffbd59', '#399140', '#513653'], // Couleurs personnalisées
                borderColor: '#000000', // Bordure noire sur chaque part
                borderWidth: 1, // Épaisseur de la bordure
                hoverOffset: 4 // Effet de survol qui agrandit légèrement le secteur
            }]
        },
        options: {
        responsive: false,
        plugins: {
            legend: {
                position: 'bottom',
                labels: {
                    color: 'black',
                    font: {
                        size: 20 // Taille de police des légendes
                    }
                }
            },
            title: {
                display: false,
                text: 'Répartition des types de déchets collectés'
            }
        }
    }
});
</script>

    </div>


        <!-- Tableau des collectes -->
        <div class="overflow-hidden rounded-lg shadow-lg bg-white">
            <table class="w-full table-auto border-collapse">
                <thead class="bg-cyan-700 text-white">
                <tr>
                    <th class="py-3 px-4 text-center">Date</th>
                    <th class="py-3 px-4 text-center">Lieu</th>
                    <th class="py-3 px-4 text-center">Bénévole Responsable</th>
					<th class="py-3 px-4 text-center">Type de déchet</th>
					<th class="py-3 px-4 text-center">Quantité déchet (en kg)</th>
					<th class="py-3 px-4 text-center">Poids Total des déchets ramassés par collecte (en kg)</th>
                    <th class="py-3 px-4 text-center">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-300 text-center">
                <?php foreach ($collectes as $collecte) : ?>
					<tr class="hover:bg-gray-100 transition duration-200">
						<td class="py-3 px-4 font-bold"><?= date('d/m/Y', strtotime($collecte['date_collecte'])) ?></td>
						<td class="py-3 px-4"><?= htmlspecialchars($collecte['lieu']) ?></td>
						<td class="py-3 px-4 font-bold">
							<?= $collecte['nom'] ? htmlspecialchars($collecte['nom']) : 'Aucun bénévole' ?>
						</td>

						<!-- Concatenate all 'type_dechet' values for the same collecte into one column -->
						<td class="py-3 px-4">
							<?php
							$dechetTypes = [];
							foreach ($dechets as $dechet) :
								if ($dechet["id_collecte"] === $collecte["id"] && !empty($dechet["type_dechet"])) :
									$dechetTypes[] = $dechet["type_dechet"];
								endif;
							endforeach;
							if (empty($dechetTypes))
							{
								$dechetTypes[] = "Non défini";
							}
							// Display all type_dechet values separated by commas or line breaks
							echo implode('<br>', $dechetTypes);
							?>
						</td>

						<!-- Concatenate all 'quantite_kg' values for the same collecte into one column -->
						<td class="py-3 px-4 font-bold">
							<?php
							$quantites = [];
							foreach ($dechets as $dechet) :
								if ($dechet["id_collecte"] === $collecte["id"] && !empty($dechet["quantite_kg"])) :
									$quantites[] = $dechet["quantite_kg"];
								endif;
							endforeach;
							if (empty($quantites))
							{
								$quantites[] = 0;
							}
							// Display all quantite_kg values separated by commas or line breaks
							echo implode('<br>', $quantites);
							?>
						</td>

						<td class="py-3 px-4"><?= array_sum($quantites) ?></td>

						<td class="py-3 px-4 flex space-x-2">
							<a href="collection_edit.php?id=<?= $collecte['id'] ?>" class="bg-emerald-600 hover:bg-emerald-900 text-white px-4 py-2 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
								✏️ Modifier
							</a>
							<a href="collection_delete.php?id=<?= $collecte['id'] ?>" class="bg-red-500 hover:bg-red-700 text-white px-4 py-2 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500 transition duration-200" onclick="return confirm('Êtes-vous sûr de vouloir supprimer cette collecte ?');">
								🗑️ Supprimer
							</a>
						</td>
					</tr>
				<?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>





</body>
</html>
