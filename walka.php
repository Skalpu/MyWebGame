<?php

	require_once('config.php');
    login_check();
	$_SESSION['player']->updateLocally();

	if($_POST)
	{
		if($_POST['type'] == 'arena')
		{
			$ataker = $_SESSION['player'];
			$obronca = new Player($_POST['opponent']);
			drawArena($ataker, $obronca);
		}
	}
	
	function drawArena(Player $ataker, Player $obronca)
	{
		$atakerFotoW = "10%";
		$atakerFotoH = "30%";		
		$obroncaFotoW = "10%";
		$obroncaFotoH = "30%";
		$atakerFotoTop = "22%";
		$atakerFotoLeft = "21%";
		$obroncaFotoTop = "22%";
		$obroncaFotoRight = "21%";
		
		
		echo "<div style='position: fixed; box-sizing: border-box; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; width: $atakerFotoW; height: $atakerFotoH; top: $atakerFotoTop; left: $atakerFotoLeft;'>";
			$ataker->drawFoto();
		echo "</div>";
	
	
		echo "<div style='position: fixed; box-sizing: border-box; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; width: $obroncaFotoW; height: $obroncaFotoH; top: $obroncaFotoTop; right: $obroncaFotoRight;'>";
			$obronca->drawFoto();
		echo "</div>"; 
	}
?>