<?php
    
    require_once('config.php');
    login_check();
	
	
	if($_POST)
	{
		$aktualnyCel = get_stat('destination','users',$_SESSION['id']);
		
		if ($aktualnyCel == 'false')
		{
			$sekundy = get_current_time();
			$sekundy += $_POST['sekundy'];

			insert_time($sekundy,'wyprawa_until',$_SESSION['id']);
			set_stat('users','destination',$_POST['miejsce'],$_SESSION['id']);
		}
		
	}
	
	function czasWyprawy()
	{
		$wyprawaUntil = get_stat('wyprawa_until','users',$_SESSION['id']);
		$rezultat = "<input type='hidden' id='czasWyprawy' value='" .$wyprawaUntil. "'>";
		echo $rezultat;
	}
	function destination()
	{
		$aktualnyCel = get_stat('destination','users',$_SESSION['id']);		
		$rezultat = "<input type='hidden' id='destination' value='" .$aktualnyCel. "'>";
		echo $rezultat;
	}

?>

<HTML>

<Head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script src="jquery-ui-1.12.1/jquery-3.1.1.js"></script>
    <script src="jquery-ui-1.12.1/jquery-ui.js"></script>
	<script src="jquery-ui-1.12.1/jquery.countdown.js"></script>
    <link rel="stylesheet" type="text/css" href="main.css">
	<link rel="stylesheet" type="text/css" href="wyprawa.css">
	<link rel="stylesheet" type="text/css" href="walka.css">
	<link rel="stylesheet" type="text/css" href="statystyki.css">
    <Title>SkalpoGra</Title>
</Head>

<Body>

	<div id="divMainOkno">
		<ul id="listaMiejsc">	
			<div id="timer"></div>
			<?php czasWyprawy() ?>
			<?php destination() ?>
			<li id="easyForest">		<img id="easyForestImage"><div id='easyForestInformacje'><span class='quickInfo'>3 sekundy</span><br>		<span class='quickInfo'>Prosty Las</span></div>		</li>
			<li id="mediumForest">		<img id="mediumForestImage"><div id='mediumForestInformacje'><span class='quickInfo'>30 sekund</span><br>		<span class='quickInfo'>Średni Las</span></div>		</li>
			<li id="hardForest">		<img id="hardForestImage"><div id='hardForestInformacje'><span class='quickInfo'>3 sekundy</span><br>		<span class='quickInfo'>Trudny Las</span></div>		</li>
		</ul>
		
		<div id="errorLabel">		</div>
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
        <li><a href = "main.php"><img id="mainmenu"></a></li>
        <li><a href = "postac.php"><img id="postacmenu"></a></li>
        <li><a href = "equipment.php"><img id="ekwipunekmenu"></a></li>
		<li><a href = "magia.php"><img id="magiamenu"></a></li>
        <li><a href = "wyprawa.php" class="active"><img id="wyprawamenu"></a></li>
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
		var akt = str.substring(str.indexOf(' '), str.indexOf('/'));
		var max = str.substring(str.indexOf('/') + 1, str.length);
		
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
	
	
	
	
	var cel_init = $("#destination").attr("value");
	var czas_init = $("#czasWyprawy").attr("value");
	
	
	if (cel_init != 'false')
	{
		var active_div = "#" + cel_init;
		
		$("#listaMiejsc > li").removeClass("active");
		$(active_div).addClass("active"); 
		$("#listaMiejsc > li").addClass("darken");
		$(active_div).removeClass("darken");

		
		$("#timer").countdown(czas_init).on('update.countdown', function(event) {
			$(this).html(event.strftime('%H:%M:%S'));
		}).on('finish.countdown', function(event) {
			$("#listaMiejsc").fadeOut();
			$("#divMainOkno").load('walka.php', {miejsce: cel_init, typ_walki: 'wyprawa'});
		});
		
		
		var timer = $("#timer").detach();
		$(active_div + "Informacje").append(timer);
		$("#timer").show();
	}
	
	
	
	$("#easyForest").click(function()
	{
		if(cel_init == 'false')
		{
			$.ajax({
			type: "POST",
			url: "wyprawa.php",
			data: {miejsce: 'easyForest', sekundy: 3},
			});

			$("#divMainOkno").load(location.href + "#divMainOkno");
		}
	});
	$("#mediumForest").click(function()
	{
		if(cel_init == 'false')
		{
			$.ajax({
			type: "POST",
			url: "wyprawa.php",
			data: {miejsce: 'mediumForest', sekundy: 30},
			});
			
			$("#divMainOkno").load(location.href + "#divMainOkno");
		}
	});

</script>