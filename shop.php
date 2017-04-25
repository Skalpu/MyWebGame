<?php

    require_once('config.php');
    login_check();
	
	/*//Last update was saved locally, in number format
	if(is_numeric($_SESSION['player']->last_shop_update)){
		$last = $_SESSION['player']->last_shop_update;
	}
	//Last update was downloaded from DB, in time format
	else{
		$last = strtotime($_SESSION['player']->last_shop_update);
		$_SESSION['player']->last_shop_update = $last;
	}
	$next = $last + 14400; //4 hours
	$next = date("Y-m-d H:i:s", $next);
	$level = $_SESSION['player']->village['trader'];
	*/
?>


<HTML>

<Head>
    
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="main.css">
	<link rel="stylesheet" type="text/css" href="equipment.css">
	<script src="jquery-ui-1.12.1/jquery-3.1.1.js"></script>
	<script src="jquery-ui-1.12.1/jquery-ui.js"></script>
	<script src="jquery-ui-1.12.1/jquery.countdown.js"></script>
	<link rel="apple-touch-icon" sizes="57x57" href="/gfx/icon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/gfx/icon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/gfx/icon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/gfx/icon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/gfx/icon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/gfx/icon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/gfx/icon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/gfx/icon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/gfx/icon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="/gfx/icon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/gfx/icon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/gfx/icon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/gfx/icon/favicon-16x16.png">
	<link rel="manifest" href="/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/gfx/icon/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
    <Title>SkalpoGra</Title>
	
</Head>

<Body>

	<div id="divPlayerBars"></div>
    <div id="divMainOkno"></div>
	<div id="divRemainingTimeLabel" class="center noselect"></div>
	<div id="divRemainingTime" class="center noselect"></div>

	<nav><ul>
		<li><a href = "main.php"><div class='menuContainer' id='mainMenu'></div></a></li>
        <li><a href = "postac.php"><div class='menuContainer' id='postacMenu'></div></a></li>
        <li><a href = "equipment.php"><div class='menuContainer' id='equipmentMenu'></div></a></li>
		<li><a href = "wioska.php"><div class='menuContainer' id='wioskaMenu'></div></a></li>
		<li><a href = "shop.php" class="active"><div class='menuContainer' id='shopMenu'></div></a></li>
		<li><a href = "magia.php"><div class='menuContainer' id='magiaMenu'></div></a></li>
        <li><a href = "journey.php"><div class='menuContainer' id='wyprawaMenu'></div></a></li>
		<li><a href = "arena.php"><div class='menuContainer' id='arenaMenu'></div></a></li>
        <li><a href = "logout.php"><div class='menuContainer' id='logoutMenu'></div></a></li>
    </ul></nav>
	
</Body>

</HTML>



<script>

	document.addEventListener('DOMContentLoaded',function()
    {
        $("#divPlayerBars").load('update_player_bars.php');
		$("#divMainOkno").load('update_shop.php');
		/*
		//Initialize countdown
		startCountdown();
		
		//Initialize the shop
		$("#divMainOkno").load('update_shop.php', function() {
			rescaleImages();
			initializeDragDrop();
			initializeHover();
		});
		
		var poczatkowySlot = "";
		var koncowySlot = "";*/
    });
	
	
	
	/*function startCountdown()
	{
		var level = <?php echo json_encode($level); ?>;
		
		if(level != 0)
		{
			$("#divRemainingTimeLabel").html("NASTĘPNA DOSTAWA ZA");
			var nextUpdate = <?php echo json_encode($next); ?>;
		
			$("#divRemainingTime").countdown(nextUpdate, function(event) {
				$(this).html(event.strftime('%H:%M:%S'))
			}).on('finish.countdown', function(event) {
				//Reload shop when countdown hits 0
				$("#divMainOkno").load('update_shop.php', function() {
					rescaleImages();
					initializeDragDrop();
					initializeHover();
					startCountdown();
				});
			});
		}
	}*/

	
	
</script>