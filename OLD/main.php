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

    <?php update_logic($_SESSION['id']); ?>
	<?php echo drawWyprawa($_SESSION['id']); ?>
	<?php echo drawMail($_SESSION['id']); ?>
    <?php echo drawHealthBar($_SESSION['id']); ?>
    <?php echo drawManaBar($_SESSION['id']);   ?>
    <?php echo drawExpBar($_SESSION['id']);    ?> 
	<?php echo drawGold($_SESSION['id']); ?>
	<?php echo drawCrystals($_SESSION['id']); ?>
	
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



<script>	
	
	$(document).ready(function() {
		setTimeout(podlicz_bary, 10000);
	});
	
	
	var until = $("#wyprawaTekst").html();
	var lokacja = $("#wyprawaContainer").attr('class');
	if(lokacja != 'false')
	{
		$("#wyprawaTekst").countdown(until).on('update.countdown', function(event) {
			$(this).html(event.strftime('%M:%S'));
			$("#wyprawaContainer").css("opacity","1.0");
		}).on('finish.countdown', function(event) {
			$("#divMainOkno").load('walka.php', {miejsce: lokacja, typ_walki: 'wyprawa'});
			$("<link/>", {
				rel: "stylesheet",
				type: "text/css",
				href: "walka.css"
			}).appendTo("head");
			$("#wyprawaContainer").css("opacity","0");
		});
	}
	
	
	
	function podlicz_bary()
	{
		//HP
		var str = $("#textHP").html();
		var akt = str.substring(str.indexOf(' '),str.indexOf('/'));
		var max = str.substring(str.indexOf('/') + 1,str.length);
		
		if (parseInt(akt) < parseInt(max))
		{
			akt++;
			var cale = "HP: " + akt + "/" + max;
			$("#textHP").html(cale);
			
			var proc = akt/max;
			var nowaDlugosc = proc * 300;
			$("#innerHP").css("width",nowaDlugosc);
		}
		
		//Mana
		var str = $("#textMana").html();
		var akt = str.substring(str.indexOf(' '),str.indexOf('/'));
		var max = str.substring(str.indexOf('/') + 1,str.length);
		
		if (parseInt(akt) < parseInt(max))
		{
			akt++;
			var cale = "MP: " + akt + "/" + max;
			$("#textMana").html(cale);
			
			var proc = akt/max;
			var nowaDlugosc = proc * 300;
			$("#innerMana").css("width",nowaDlugosc);
		}
		
		//Gold
		var str = $("#zlotoTekst").html();
		str++;
		$("#zlotoTekst").html(str);
		
		//Krysztaly
		var str = $("#krysztalyTekst").html();
		str++;
		$("#krysztalyTekst").html(str);
		
		
		
		setTimeout(podlicz_bary, 10000);
	}
	
</script>