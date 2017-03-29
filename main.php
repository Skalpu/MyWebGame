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
		- ZMODULARYZOWAĆ GENERACJĘ ITEMÓW<br>
		- ZMODULARYZOWAĆ DODAWANIE ZŁOTA I EXPA<br>
		- WALKA Z KILKOMA NARAZ<br>
		- SUMMONY<br>
		- POPRAWIĆ CHAR CREATION<br>
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
        <li><a href = "main.php" class="active"><img id="mainmenu"></a></li>
        <li><a href = "postac.php"><img id="postacmenu"></a></li>
        <li><a href = "equipment.php"><img id="ekwipunekmenu"></a></li>
		<li><a href = "magia.php"><img id="magiamenu"></a></li>
        <li><a href = "wyprawa.php"><img id="wyprawamenu"></a></li>
		<li><a href = "arena.php"><img id="arenamenu"></a></li>
        <li><a href = "logout.php"><img id="logoutmenu"></a></li>
        </ul>
    </nav>
	
</Body>

</HTML>



<script src="jquery-ui-1.12.1/jquery-3.1.1.js"></script>
<script src="jquery-ui-1.12.1/jquery-ui.js"></script>
<script src="jquery-ui-1.12.1/jquery.countdown.js"></script>

<script>	
	
</script>