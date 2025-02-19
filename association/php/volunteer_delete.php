<?php
require 'config.php';

if (isset($_GET['id']) && is_numeric($_GET['id'])) {
    $id = (int) $_GET['id'];

	if ($_SESSION["user_id"] == $id)
	{
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
	}
    try {
        $pdo->beginTransaction();

		$stmt1 = $pdo->prepare("DELETE dechets_collectes FROM dechets_collectes JOIN collectes ON dechets_collectes.id_collecte = collectes.id WHERE collectes.id_benevole = :id");
		$stmt1->bindParam(':id', $id);
		$stmt1->execute();

		$stmt2 = $pdo->prepare("DELETE FROM collectes WHERE id_benevole = :id");
		$stmt2->bindParam(':id', $id);
		$stmt2->execute();

		$stmt3 = $pdo->prepare("DELETE FROM benevoles WHERE id = :id");
		$stmt3->bindParam(':id', $id);
		$stmt3->execute();

		$pdo->commit();

		if ($_SESSION["role"] === "participant")
		{
			header("Location: volunteer_list.php?success=1");
		}
		elseif ($_SESSION["role"] === "admin")
		{
			header("Location: admin_list.php?success=1");
		}
		echo "Record and its related references were successfully deleted.";
	} 
	catch (PDOException $e) {
        die("Erreur: " . $e->getMessage());
    }
} else {
    echo "ID invalide.";
}
?>