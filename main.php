<?php

    require_once('config.php');
    login_check();
	
?>


<HTML>

<Head>
    
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="main.css">
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
    <div id="divMainOkno" style="overflow-Y: scroll;">
	
	<?php  

	echo '<pre>';
	var_dump($_SESSION['player']);
	echo '</pre>';
	
	?>
		<!-- TODO:
		
		- CHANGE HOW SHOP UPDATES USING NEW METHOD FROM VILLAGE
		- DIVIDE BUILDING DESCRIPTION CONTENT INTO TITLE AND CONTENT (THAT SCROLLS)
		- MOVE SELL ITEM TO UPDATE_SHOP
		- 2H != 1H bronie, kołczany itp
		- GENERACJA LOSOWYCH MODÓW W ITEMACH
		- DODAĆ KWATERY ROBOTNICZE DO WIOSKI
		- NAPRAWIĆ .CSS ARENY
		- BUGFIX: INNY TIMEZONE PHP A INNY SQL
		- RÓŻNE DŹWIĘKI PRZY WALCE
		- SUMMONY<br>
		- KARCZMA Z QUESTAMI<br>
		- NAPADY != ARENA<br>
		- RACIALE I KLASOWE
		- CHANGE NAVIGATION FROM LOADING SITES TO LOADING INTO DIVMAINOKNO
		
		-->
    </div>

	<nav><ul>
		<li><a href = "main.php" class="active"><div class='menuContainer' id='mainMenu'></div></a></li>
        <li><a href = "postac.php"><div class='menuContainer' id='postacMenu'></div></a></li>
        <li><a href = "equipment.php"><div class='menuContainer' id='equipmentMenu'></div></a></li>
		<li><a href = "wioska.php"><div class='menuContainer' id='wioskaMenu'></div></a></li>
		<li><a href = "shop.php"><div class='menuContainer' id='shopMenu'></div></a></li>
		<li><a href = "magia.php"><div class='menuContainer' id='magiaMenu'></div></a></li>
        <li><a href = "wyprawa.php"><div class='menuContainer' id='wyprawaMenu'></div></a></li>
		<li><a href = "arena.php"><div class='menuContainer' id='arenaMenu'></div></a></li>
        <li><a href = "logout.php"><div class='menuContainer' id='logoutMenu'></div></a></li>
    </ul></nav>
	
</Body>

</HTML>



<script>

	document.addEventListener('DOMContentLoaded',function()
    {
        $("#divPlayerBars").load('update_player_bars.php');
    });
	
</script>