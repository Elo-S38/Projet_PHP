<?php
require 'config.php';

// Vérifier si un ID de collecte est fourni
if (!isset($_GET['id']) || empty($_GET['id'])) {
    header("Location: collection_list.php");
    exit;
}

$id = $_GET['id'];

// Récupérer les informations de la collecte
$stmt = $pdo->prepare("SELECT * FROM collectes WHERE id = ?");
$stmt->execute([$id]);
$collecte = $stmt->fetch();

if (!$collecte) {
    header("Location: collection_list.php");
    exit;
}

// Récupérer la liste des bénévoles
$stmt_benevoles = $pdo->prepare("SELECT id, nom FROM benevoles ORDER BY nom");
$stmt_benevoles->execute();
$benevoles = $stmt_benevoles->fetchAll();

$stmt_dechets = $pdo->prepare("SELECT type_dechet, quantite_kg FROM dechets_collectes WHERE id_collecte = ?");
$stmt_dechets->execute([$id]);
$dechets = $stmt_dechets->fetchAll();

foreach ($dechets as $dechet)
{
	if ($dechet["type_dechet"] === "plastique")
	{
		$plastique = $dechet;
	}
	if ($dechet["type_dechet"] === "verre")
	{
		$verre = $dechet;
	}
	if ($dechet["type_dechet"] === "métal")
	{
		$metal = $dechet;
	}
	if ($dechet["type_dechet"] === "organique")
	{
		$organique = $dechet;
	}
	if ($dechet["type_dechet"] === "papier")
	{
		$papier = $dechet;
	}
}

// Mettre à jour la collecte
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $date = $_POST["date"];
    $lieu = $_POST["lieu"];
    $benevole_id = $_POST["benevole"]; // Récupérer l'ID du bénévole 

    $stmt = $pdo->prepare("UPDATE collectes SET date_collecte = ?, lieu = ?, id_benevole = ? WHERE id = ?");
    $stmt->execute([$date, $lieu, $benevole_id, $id]);

    header("Location: collection_list.php");
    exit;
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Modifier une collecte</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

<div class="flex h-screen">
    <!-- Dashboard -->
    <div class="bg-cyan-200 text-white w-64 p-6">
        <h2 class="text-2xl font-bold mb-6">Dashboard</h2>

            <li><a href="collection_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i class="fas fa-tachometer-alt mr-3"></i> Tableau de bord</a></li>
            <li><a href="volunteer_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i class="fa-solid fa-list mr-3"></i> Liste des bénévoles</a></li>
            <li>
                <a href="user_add.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg">
                    <i class="fas fa-user-plus mr-3"></i> Ajouter un bénévole
                </a>
            </li>
            <li><a href="my_account.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i class="fas fa-cogs mr-3"></i> Mon compte</a></li>

        <div class="mt-6">
            <button onclick="logout()" class="w-full bg-red-600 hover:bg-red-700 text-white py-2 rounded-lg shadow-md">
                Déconnexion
            </button>
        </div>
    </div>

    <!-- Contenu principal -->
    <div class="flex-1 p-8 overflow-y-auto">
        <h1 class="text-4xl font-bold text-blue-900 mb-6">Modifier une collecte</h1>

        <!-- Formulaire -->
        <div class="bg-white p-6 rounded-lg shadow-lg">
            <form method="POST" class="space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700">Date :</label>
                    <input type="date" name="date" value="<?= htmlspecialchars($collecte['date_collecte']) ?>" required
                           class="w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Lieu :</label>
                    <input type="text" name="lieu" value="<?= htmlspecialchars($collecte['lieu']) ?>" required
                           class="w-full p-2 border border-gray-300 rounded-lg">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700">Bénévole :</label>
                    <select name="benevole" required
                            class="w-full p-2 border border-gray-300 rounded-lg">
                        <option value="" disabled selected>Sélectionnez un·e bénévole</option>
                        <?php foreach ($benevoles as $benevole): ?>
                            <option value="<?= $benevole['id'] ?>" <?= $benevole['id'] == $collecte['id_benevole'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($benevole['nom']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
				<div>
                    <label class="block text-sm font-medium text-gray-700">Type De Déchet :</label>
					<div class="space-y-2">
						<label class="flex items-center space-x-2">
							<input type="checkbox" name="dechets[]" value="plastique" class="form-checkbox" <?php if ($plastique) {echo 'checked';}?> >
							<span>Plastique</span>
							<input type="number" name="poids_plastique" step="0.1" placeholder="Poids en kg" class="ml-2 p-1 border rounded" value="<?= $plastique ? $plastique["quantite_kg"] : NULL ?>">
						</label>
						<label class="flex items-center space-x-2">
							<input type="checkbox" name="dechets[]" value="verre" class="form-checkbox" <?php if ($verre) {echo 'checked';}?>>
							<span>Verre</span>
							<input type="number" name="poids_verre" step="0.1" placeholder="Poids en kg" class="ml-2 p-1 border rounded" value="<?= $verre ? $verre["quantite_kg"] : NULL ?>">
						</label>
						<label class="flex items-center space-x-2">
							<input type="checkbox" name="dechets[]" value="métal" class="form-checkbox" <?php if ($metal) {echo 'checked';}?>>
							<span>Métal</span>
							<input type="number" name="poids_metal" step="0.1" placeholder="Poids en kg" class="ml-2 p-1 border rounded" value="<?= $metal ? $metal["quantite_kg"] : NULL ?>">
						</label>
						<label class="flex items-center space-x-2">
							<input type="checkbox" name="dechets[]" value="papier" class="form-checkbox" <?php if ($papier) {echo 'checked';}?>>
							<span>Papier</span>
							<input type="number" name="poids_papier" step="0.1" placeholder="Poids en kg" class="ml-2 p-1 border rounded" value="<?= $papier ? $papier["quantite_kg"] : NULL ?>">
						</label>
						<label class="flex items-center space-x-2">
							<input type="checkbox" name="dechets[]" value="organique" class="form-checkbox" <?php if ($organique) {echo 'checked';}?>>
							<span>Déchets organiques</span>
							<input type="number" name="poids_organique" step="0.1" placeholder="Poids en kg" class="ml-2 p-1 border rounded" value="<?= $organique ? $organique["quantite_kg"] : NULL ?>">
						</label>
					</div>
                </div>
				
                <div class="flex justify-end space-x-4">
                    <a href="collection_list.php" class="bg-gray-500 text-white px-4 py-2 rounded-lg">Annuler</a>
                    <button type="submit" class="bg-cyan-200 text-white px-4 py-2 rounded-lg">Modifier</button>
                </div>
            </form>
        </div>
    </div>
</div>
</body>
</html>
