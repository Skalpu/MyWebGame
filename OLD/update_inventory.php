<?php

    require_once('config.php');
    login_check();
	
	if($_POST)
	{

		$poczSlot = $_POST['poczatkowySlot'];
		$poczID = $_POST['poczatkowyID'];
		$konSlot = $_POST['koncowySlot'];
		$konID = $_POST['koncowyID'];

		set_stat('equipment',$poczSlot,$konID,$_SESSION['id']);
		set_stat('equipment',$konSlot,$poczID,$_SESSION['id']);
	
	}
    
?>