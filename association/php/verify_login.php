<?php

if (!isset($_SESSION["user_id"]) || !isset($_SESSION["nom"]) || !isset($_SESSION["role"]))
{
	header("Location: login.php");
	exit;
}

?>