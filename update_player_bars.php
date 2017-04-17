<?php

	require_once('config.php');
    login_check();
	
	$_SESSION['player']->updateLocally();
	$_SESSION['player']->drawMail();
	$_SESSION['player']->drawGold();
	$_SESSION['player']->drawCrystals();
	$_SESSION['player']->drawHP("mainHP", "");
	$_SESSION['player']->drawMP("mainMP", "");
	$_SESSION['player']->drawEXP("mainEXP", "");
	
	//Last update is saved locally, in number format
	if(is_numeric($_SESSION['player']->last_update))
	{
		$last = $_SESSION['player']->last_update;
	}
	//Last update was downloaded from DB, in time format
	else
	{
		$last = strtotime($_SESSION['player']->last_update);
	}

?>

<script>
	
	//TODO update barów na żywo TUTAJ
	
</script>