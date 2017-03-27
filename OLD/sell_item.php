<?php

    require_once('config.php');
    login_check();
	
	if($_POST)
	{
	
		$poczSlot = $_POST['poczatkowySlot'];
		$poczID = $_POST['poczatkowyID'];
		$cena = $_POST['cena'];
	
		$conn = connectDB();
		$ePoczID = $conn->real_escape_string($poczID);
		$eUserID = $conn->real_escape_string($_SESSION['id']);
		$ePoczSlot = $conn->real_escape_string($poczSlot);
		$zloto = get_stat("zloto","users",$_SESSION['id']);
		$zloto += $cena;
		set_stat("users","zloto",$zloto,$_SESSION['id']);
		$conn->query("DELETE FROM items WHERE id = '$ePoczID'");
		$conn->query("UPDATE equipment SET $ePoczSlot = 0 WHERE id = $eUserID");
		$conn->close();
	
	}
	
?>