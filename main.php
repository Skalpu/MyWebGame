<?php

    require_once('config.php');
    login_check();	

?>


<HTML>

<Head>
    
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="main.css">
    <Title>SkalpoGra</Title>
	
</Head>

<Body>

    <div id="divMainOkno">
		- WIOSKA?<br>
		- GENERACJA ITEMÓW<br>
		- SUMMONY<br>
		- KARCZMA Z QUESTAMI<br>
		- NAPADY != ARENA<br>
		- POPRAWIĆ EKWIPUNEK UŻYWAJĄC JSON??<br>
		- RACIALE I KLASOWE<br>
		- HANDLARZ<br>
		- PODLICZANIE MAX HP I MAX MANY PRZY ZAŁOŻENIU ITEMU<br>
		- CRON JOBS NA WYPRAWY<br>
    </div>

    <?php update_logic($_SESSION['player']); ?>
	<?php //echo drawWyprawa($_SESSION['id']); ?>
	<?php //drawMail($_SESSION['player']); ?>
    <?php drawHealthBar($_SESSION['player']); ?>
    <?php drawManaBar($_SESSION['player']);   ?>
    <?php drawExpBar($_SESSION['player']);    ?> 
	<?php drawGold($_SESSION['player']); ?>
	<?php drawCrystals($_SESSION['player']); ?>
	
	<nav>
    <ul>
        <!--<li><a href = "main.php" class="active"><img id="mainmenu"></a></li>-->
		<li><a href = "main.php" class="active"><div class='menuContainer' id='mainMenu'></div></a></li>
        <li><a href = "postac.php"><div class='menuContainer' id='postacMenu'></div></a></li>
        <li><a href = "equipment.php"><div class='menuContainer' id='equipmentMenu'></div></a></li>
		<li><a href = "magia.php"><div class='menuContainer' id='magiaMenu'></div></a></li>
        <li><a href = "wyprawa.php"><div class='menuContainer' id='wyprawaMenu'></div></a></li>
		<li><a href = "arena.php"><div class='menuContainer' id='arenaMenu'></div></a></li>
        <li><a href = "logout.php"><div class='menuContainer' id='logoutMenu'></div></a></li>
    </ul>
    </nav>
	
</Body>

</HTML>



<script src="jquery-ui-1.12.1/jquery-3.1.1.js"></script>
<script src="jquery-ui-1.12.1/jquery-ui.js"></script>
<script src="jquery-ui-1.12.1/jquery.countdown.js"></script>

<script>	
	
</script>