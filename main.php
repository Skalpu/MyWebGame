<?php

    require_once('config.php');
    login_check();
	$_SESSION['player']->updateLocally();
	drawGame($_SESSION['player']);
	
?>


<HTML>

<Head>
    
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="main.css">
    <Title>SkalpoGra</Title>
	
</Head>

<Body>

    <div id="divMainOkno" style="overflow-Y: scroll;">
	<?php  
	
	echo '<pre>';
	var_dump($_SESSION['player']);
	echo '</pre>';
	
	?>
		<!-- 
		TODO:
		- HANDLARZ
		- RÓŻNE DŹWIĘKI PRZY WALCE
		- WIOSKA?<br>
		- SUMMONY<br>
		- KARCZMA Z QUESTAMI<br>
		- NAPADY != ARENA<br>
		- RACIALE I KLASOWE<br>
		- HANDLARZ<br>-->
    </div>

	
	<nav>
    <ul>
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