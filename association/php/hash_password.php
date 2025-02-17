<?php
$password = "13102005Evan69!"; // Change le mot de passe ici
$hash = password_hash($password, PASSWORD_DEFAULT);

echo "Mot de passe hashé : " . $hash;
?>
