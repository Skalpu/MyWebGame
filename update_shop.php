<?php

    require_once('config.php');
    login_check();
	$_SESSION['player']->updateLocally();
	
	//TODO
	if($_POST)
	{
		
	}
	
	
	drawBackpack($_SESSION['player']);
	drawShop($_SESSION['player']);
	
?>