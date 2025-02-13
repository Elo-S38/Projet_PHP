<?php
require 'config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

    try {
        $pdo = new PDO("mysql:host=localhost;dbname=gestion_collectes", "Michel69", "Michel69007", [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION
        ]);

		$pdo->beginTransaction();

		$stmt2 = $pdo->prepare("DELETE FROM dechets_collectes WHERE id_collecte = :id");
		$stmt2->bindParam(':id', $id);
		$stmt2->execute();

        $stmt = $pdo->prepare("DELETE FROM collectes WHERE id = :id");
        $stmt->bindParam(':id', $id, PDO::PARAM_INT);
		$stmt->execute();

		$pdo->commit();

        header("Location: collection_list.php?success=1");
        exit();
    } catch (PDOException $e) {
        die("Erreur: " . $e->getMessage());
    }
} else {
    echo "ID invalide.";
}
?>