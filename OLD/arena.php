<?php

    require_once('config.php');
    login_check();
	
	function generate_table()
	{
		$widelki_levela = 5;
		$widelki_wykorzystano = 0;
		$ile_graczy = 15;
		
		$conn = connectDB();
		$eID = $conn->real_escape_string($_SESSION['id']);
		$playerLevel = get_value($conn, "SELECT level FROM users WHERE id = $eID");
		$playerHP = get_value($conn, "SELECT hp FROM users WHERE id = $eID");
		$playerMaxHP = get_value($conn, "SELECT maxhp FROM users WHERE id = $eID");
		$playerProcent = $playerHP/$playerMaxHP;
		
		if ($playerProcent > 0.15)
		{
	
		$result = $conn->query("SELECT * FROM users WHERE level=$playerLevel AND id!=$eID AND protected_until<NOW() LIMIT $ile_graczy");
		$num_rows = mysqli_num_rows($result);
		
		while(($num_rows < $ile_graczy) and ($widelki_wykorzystano < $widelki_levela))
		{
			$widelki_wykorzystano++;
			if ($playerLevel - $widelki_wykorzystano > 0)
			{
				$levelMin = $playerLevel - $widelki_wykorzystano;
			}
			else
			{
				$levelMin = 1;
			}
			
			$levelMax = $playerLevel + $widelki_wykorzystano;
			
			$result = $conn->query("SELECT * FROM users WHERE level>=$levelMin AND level<=$levelMax AND id!=$eID AND protected_until<NOW() LIMIT $ile_graczy");
			$num_rows = mysqli_num_rows($result);
			
		}
		
		echo "<table id='tabelaGracze'>";
		echo "<tr>";
		echo "<th id='graczLabel'>Gracz</th>";
		echo "<th id='levelLabel'>Level</th>";
		echo "<th id='ostatnioLabel'>Ostatnia aktywność</th>";
		echo "<th id='akcjaLabel'>Akcja</th>";
		echo "</tr>";
		
		for($i = 0; $i < $num_rows; $i++)
		{
			$row = mysqli_fetch_assoc($result);
			echo "<tr>";
			echo "<th>" . $row['username'] . "</th>";
			echo "<th>" . $row['level'] . "</th>";
			echo "<th>" . $row['last_update'] . "</th>";
			echo "<th> <button onclick='atakuj(" . $row['id'] . ")'>Atak</button></th>";
			echo "</tr>";

		}
		
		echo "</table>";
		
		}
		
		
		else
		{
			echo "<div id='notEnoughHP'><span>Musisz mieć przynajmniej 15% życia aby atakować na arenie.</span></div>";
		}
	}

?>


<HTML>

<Head>
    
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="main.css">
	<link rel="stylesheet" type="text/css" href="walka.css">
	<link rel="stylesheet" type="text/css" href="arena.css">
    <Title>SkalpoGra</Title>
	
</Head>

<Body>

    <div id="divMainOkno">
		<?php generate_table() ?>
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
        <li><a href = "wyprawa.php"><img id="wyprawamenu"></a></li>
		<li><a href = "arena.php" class="active"><img id="arenamenu"></a></li>
        <li><a href = "logout.php"><img id="logoutmenu"></a></li>
        </ul>
    </nav>
	
</Body>

</HTML>

<script src="jquery-ui-1.12.1/jquery-3.1.1.js"></script>
<script src="jquery-ui-1.12.1/jquery-ui.js"></script>
<script src="jquery-ui-1.12.1/jquery.countdown.js"></script>

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
	
	
	function atakuj(oponentID)
	{
		$("#tabelaGracze").fadeOut();
		$("#divMainOkno").load('walka.php', {opponent: oponentID});
	}
	
</script>