<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Bénévoles</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdn.jsdelivr.net/npm/@fortawesome/fontawesome-free/css/all.min.css" rel="stylesheet">
</head>
<!-- <body class="bg-gray-100 text-gray-900"> Basic-->
<body class="bg-gray-100 text-gray-900 font-sans">
<div class="flex h-screen">
    <!-- Barre de navigation -->
    <!-- <div class="bg-cyan-200 text-white w-64 p-6">
        <h2 class="text-2xl font-bold mb-6">Dashboard</h2> -->
        <div class="bg-[#005a8d] text-white w-70 p-6">
    <h2 class="text-2xl font-bold mb-6">Dashboard</h2>

<!-- Hello -->
 <ul class="list-none p-2.5">
    <li><a href="collection_list.php" class="menu-item flex items-center py-2 px-3 text-white font-bold hover:bg-black hover:text-white transition-colors duration-300"><i class="fas fa-tachometer-alt mr-3"></i> Tableau de bord</a></li>
<li><a href="collection_add.php" class="menu-item flex items-center py-2 px-3 rounded-lg text-white font-bold hover:bg-black
 hover:text-white transition-colors duration-300"><i class="fas fa-plus-circle mr-3"></i> Ajouter une collecte</a></li>
<li><a href="volunteer_list.php" class="menu-item flex items-center py-2 px-3 rounded-lg text-white font-bold hover:bg-black
 hover:text-white transition-colors duration-300"><i class="fa-solid fa-list mr-3"></i> Liste des bénévoles</a></li>
<li><a href="user_add.php" class="menu-item flex items-center py-2 px-3 rounded-lg text-white font-bold hover:bg-black hover:text-white transition-colors duration-300"><i class="fas fa-user-plus mr-3"></i> Ajouter un bénévole</a></li>
<li><a href="my_account.php" class="menu-item flex items-center py-2 px-3 rounded-lg text-white font-bold hover:bg-black hover:text-white transition-colors duration-300"><i class="fas fa-cogs mr-3"></i> Mon compte</a></li>
</ul>
<div class="mt-6">
    <button onclick="logout()" class="w-full bg-red-700 font-bold hover:bg-red-900 text-white py-2 rounded-lg shadow-md transition-colors duration-300">
        Déconnexion
    </button>
</div>
</div>

<!-- Contenu principal -->
    <div class="flex-1 p-8 overflow-y-auto">
        <!-- Titre -->
        <!-- <h1 class="text-[28px] text-black font-sans">Liste des bénévoles</h1> -->
        <h1 class="text-[28px] font-bold text-black mb-4" style="font-family: Arial, sans-serif;">Liste des bénévoles</h1>


        <!-- Tableau des admin -->
        <div class="overflow-hidden rounded-lg shadow-lg bg-white">
            <table class="w-full table-auto border-collapse">
                <thead class="bg-[#005a8d] text-white">
                <tr>
                    <th class="py-3 px-4 text-left">Nom</th>
                    <th class="py-3 px-4 text-left">Email</th>
                    <th class="py-3 px-4 text-left">Rôle</th>
                    <th class="py-3 px-4 text-left">Actions</th>
                </tr>
                </thead>
                <tbody class="divide-y divide-gray-300">
                <tr class="hover:bg-gray-100 transition duration-200">
                    <td class="py-3 px-4">Nom de l'admin</td>
                    <td class="py-3 px-4">email@example.com</td>
                    <td class="py-3 px-4">Admin</td>
                    <td class="py-3 px-4 flex space-x-2">
                        <a href="#"
                           class="bg-cyan-700 font-bold hover:bg-[#005a8d] text-white px-4 py-2 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-blue-500 transition duration-200">
                            ✏️ Modifier
                        </a>
                        <a href="#"
                           class="bg-red-700 font-bold hover:bg-red-900 text-white px-4 py-2 rounded-lg shadow-lg focus:outline-none focus:ring-2 focus:ring-red-500 transition duration-200">
                            🗑️ Supprimer
                        </a>
                    </td>
                </tr>
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>