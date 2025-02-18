<?php 

require 'config.php';
require 'verify_login.php';

$id = $_SESSION["user_id"];

try {
	$stmt = $pdo->prepare("SELECT * FROM benevoles WHERE id = ?");
	$stmt->execute([$id]);
	$user = $stmt->fetch();
}
catch (PDOException $e) 
{
    die("Erreur: " . $e->getMessage());
}

if ($_SERVER["REQUEST_METHOD"] === "POST")
{
	$name = $_POST["nom"];
	$email = $_POST["email"];
	$current_password = $_POST["current_password"];
	$new_password = $_POST["new_password"];
	$confirm_password = $_POST["confirm_password"];

	if (!($new_password === $confirm_password))
	{
		$new_password_error = "Le nouveau mot de passe est différent du mot de passe confirmé";
	}
	if (!password_verify($current_password, $user["mot_de_passe"]))
	{
		$current_password_error = "Le mot de passe actuel n'est pas le bon";
	}
	if (!($email === $user["email"]))
	{
		$stmt = $pdo->prepare("SELECT * FROM benevoles WHERE email = ?");
		$stmt->execute([$email]);
		$user_with_same_mail = $stmt->fetchAll();
		if (!empty($user_with_same_mail))
		{
			$email_error = "Email déjà pris";
		}
	}
	if (empty($new_password_error) && empty($current_password_error) && empty($email_error))
	{
		try
		{
			$password = password_hash($new_password, PASSWORD_DEFAULT);
			$stmt = $pdo->prepare("UPDATE benevoles SET nom = ?, email = ?, mot_de_passe = ? WHERE id = ?");
			$stmt->execute([$name, $email, $password, $id]);

			$stmt = $pdo->prepare("SELECT * FROM benevoles WHERE id = ?");
			$stmt->execute([$id]);
			$user = $stmt->fetch();
			$success = "Vos paramètres ont été changés avec succès";
		}
		catch (PDOException $e) 
		{
			die("Erreur: " . $e->getMessage());
		}
	}
}

?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Paramètres</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">
<div class="flex h-screen">

    <!-- Barre de navigation -->
    <div class="bg-cyan-200 text-white w-64 p-6">
        <h2 class="text-2xl font-bold mb-6">Dashboard</h2>

        <li><a href="collection_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i
                        class="fas fa-tachometer-alt mr-3"></i> Tableau</a></li>
        <li><a href="collection_add.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i
                        class="fas faplus-circle mr-3"></i> Ajouter</a></li>
        <li><a href="volunteer_list.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg"><i
                        class="fa-solid fa-list mr-3"></i> Liste</a></li>
        <li>
            <a href="user_add.php" class="flex items-center py-2 px-3 hover:bg-blue-800 rounded-lg">
                <i class="fas fa-user-plus mr-3"></i> Ajouter
            </a>
        </li>
        <li><a href="my_account.php" class="flex items-center py-2 px-3 bg-blue-800 rounded-lg"><i
                        class="fas fa-cogs mr-3"></i>Perso</a></li>

		<li><a href="logout.php" class="flex items-center py-2 px-3 bg-red-600 hover:bg-red-700 rounded-lg" onclick="return confirm('Voulez vous vraiment vous déconnecter ?')">
			Déconnexion
		</a></li>
    </div>

    <!-- Contenu principal -->
    <div class="flex-1 p-8 overflow-y-auto">
        <!-- Titre -->
        <h1 class="text-4xl font-bold text-center text-blue-800 mb-6">Paramètres</h1>
		
        <!-- Message du succès ou d'erreur -->

		<?php if (!empty($success)) : ?>
            <div class="text-green-600 text-center mb-4">
                <?= htmlspecialchars($success) ?>
            </div>
        <?php endif; ?>

		<?php if (!empty($email_error)) : ?>
            <div class="text-red-600 text-center mb-4">
                <?= htmlspecialchars($email_error) ?>
            </div>
        <?php endif; ?>

        <?php if (!empty($current_password_error)) : ?>
            <div class="text-red-600 text-center mb-4">
                <?= htmlspecialchars($current_password_error) ?>
            </div>
        <?php endif; ?>

		<?php if (!empty($new_password_error)) : ?>
            <div class="text-red-600 text-center mb-4">
                <?= htmlspecialchars($new_password_error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" action="my_account.php" id="settings-form" class="space-y-6">
			<!--Champ nom  -->
			<div>
                <label for="nom" class="block text-sm font-medium text-gray-700">Nom</label>
                <input type="text" name="nom" id="nom" value="<?= $user["nom"] ?>" required
                       class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>
            <!-- Champ Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                <input type="email" name="email" id="email" value="<?= $user["email"] ?>" required
                       class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Champ Mot de passe actuel -->
            <div>
                <label for="current_password" class="block text-sm font-medium text-gray-700">Mot de passe
                    actuel</label>
                <input type="password" name="current_password" id="current_password" required
                       class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Champ Nouveau Mot de passe -->
            <div>
                <label for="new_password" class="block text-sm font-medium text-gray-700">Nouveau mot de passe</label>
                <input type="password" name="new_password" id="new_password"
                       class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Champ Confirmer le nouveau Mot de passe -->
            <div>
                <label for="confirm_password" class="block text-sm font-medium text-gray-700">Confirmer le mot de
                    passe</label>
                <input type="password" name="confirm_password" id="confirm_password"
                       class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            <!-- Boutons -->
            <div class="flex justify-between items-center">
                <a href="collection_list.php" class="text-sm text-blue-600 hover:underline">Retour à la liste des
                    collectes</a>
                <button type="submit"
                        class="bg-cyan-200 hover:bg-cyan-600 text-white px-6 py-2 rounded-lg shadow-md">
                    Mettre à jour
				</button>
            </div>
        </form>
    </div>
</div>
</body>
</html>

