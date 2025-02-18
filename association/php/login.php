<?php

require 'config.php';

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $email = $_POST["email"];
    $password = $_POST["password"];

    // Vérifier si l'utilisateur existe dans la table `benevoles`
    $stmt = $pdo->prepare("SELECT * FROM benevoles WHERE email = ?");
    $stmt->execute([$email]);
    $user = $stmt->fetch();


    // Vérification du mot de passe (si hashé en BDD)
    if ($user && password_verify($password, $user['mot_de_passe'])) {
        $_SESSION["user_id"] = $user["id"];
        $_SESSION["nom"] = $user["nom"];
        $_SESSION["role"] = $user["role"];

		if ($_SESSION["role"] === "participant")
		{
			header("Location: volunteer_list.php");
			exit;
		}
        elseif ($_SESSION["role"] === "admin")
		{
			header("Location: admin_list.php");
        	exit;
		}
    } else {
        $error = "Identifiants incorrects";
    }
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Connexion</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 text-gray-900">

<div class="flex min-h-screen bg-gray-100">
    <!-- Partie gauche : Logo + Slogan -->
    <div class="flex-1 flex flex-col justify-start pt-20 items-center  bg-gray space-y-10">
        <!-- Agrandir le logo -->
        <img src="Logo.png" alt="Logo" class="w-120 h-120 object-contain">

        <!-- Texte slogan -->
        <h1 class="text-center text-4xl font-bold text-gray-800 font-[Pacifico]">
            Chaque geste compte, chaque main aide! <br> Merci à vous!
        </h1>
    </div>

    <!-- Partie droite : Formulaire (à compléter si besoin) -->
    <div class="flex-1 flex justify-center items-center bg-gray">
        <div class="bg-white p-12 rounded-lg shadow-2xl w-full sm:w-[600px] h-[400px]">        
        
     

        <?php if (!empty($error)) : ?>
            <div class="text-red-600 text-center mb-4">
                <?= htmlspecialchars($error) ?>
            </div>
        <?php endif; ?>

        <form method="POST" class="space-y-10">
            <div>
                <label for="email" class="block text-lg font-bold text-gray-700 mb-4">Email</label>
                <input type="email" name="email" id="email" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div>
                <label for="password" class="block text-lg font-bold text-gray-700 mb-4">Mot de passe</label>
                <input type="password" name="password" id="password" required class="w-full p-3 border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500">
            </div>

            <div class="flex justify-between items-center">
                <a href="#" class="text-sm text-blue-600 hover:underline hover:font-bold">Mot de passe oublié ?</a>
                <button type="submit" class="bg-cyan-700 font-bold hover:bg-[#005a8d] text-white px-6 py-2 rounded-lg shadow-md">
                    Se connecter
                </button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
