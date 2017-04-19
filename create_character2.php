<?php

	//Checking if logged in
    require_once('config.php');
    login_check();
	
	
	//Checking if character isn't already created
	if (get_stat("last_login","users",$_SESSION['player']->id) != null)
    {
        header('Location:main.php');
        exit();
    }
	
	
	//Generation of initial stat distribution
	function generuj_rozdawanie_statow()
	{
		//Resetting variables
		$_SESSION['player']->sila = 10;
		$_SESSION['player']->zwinnosc = 10;
		$_SESSION['player']->celnosc = 10;
		$_SESSION['player']->kondycja = 10;
		$_SESSION['player']->inteligencja = 10;
		$_SESSION['player']->wiedza = 10;
		$_SESSION['player']->charyzma = 10;
		$_SESSION['player']->szczescie = 10;
		$_SESSION['player']->totalStats = 0;
		
		//Setting variables according to player race
		switch($_SESSION['player']->rasa)
		{
			case 'Człowiek':
				break;
			case 'Ork':
				$_SESSION['player']->sila += 3;
				$_SESSION['player']->kondycja += 3;
               	$_SESSION['player']->zwinnosc += 2;
               	$_SESSION['player']->inteligencja -= 2;
               	$_SESSION['player']->wiedza -= 2;
               	$_SESSION['player']->charyzma -= 2;
				break;
			case 'Leśny elf':
               	$_SESSION['player']->zwinnosc += 2;
               	$_SESSION['player']->celnosc += 2;
               	$_SESSION['player']->sila -= 2;
				break;
			case 'Krasnolud':
               	$_SESSION['player']->sila += 2;
               	$_SESSION['player']->kondycja += 4;
               	$_SESSION['player']->zwinnosc -= 2;
               	$_SESSION['player']->charyzma -= 2;
				break;
			case 'Wysoki elf':
               	$_SESSION['player']->inteligencja += 3;
               	$_SESSION['player']->wiedza += 3;
               	$_SESSION['player']->charyzma += 1;
				$_SESSION['player']->sila -= 2;
               	$_SESSION['player']->zwinnosc -= 2;
               	$_SESSION['player']->kondycja -= 1;
				break;
			default:      
		}
		
		//Generating the statistics for user
		for ($i = 0; $i <= 7; $i++)
		{
			$stat = '';
			$label = '';
			$hover = '';
			$value = '';
			
			switch($i)
			{
				case 0: $stat = 'sila'; $label = 'Siła'; $hover = 'Każdy punkt siły wpływa na obrażenia fizyczne w walce wręcz. Silni poszukiwacze przygód są również w stanie nosić ciężkie zbroje płytowe i korzystać z większego arsenału broni bliskodystansowych.'; $value = $_SESSION['player']->sila; break;
				case 1: $stat = 'zwinnosc'; $label = 'Zwinność'; $hover = 'Zwinność wpływa na twoją szansę na trafienie przeciwnika oraz na uniknięcie jego ataków.'; $value = $_SESSION['player']->zwinnosc; break;
				case 2: $stat = 'celnosc'; $label = 'Celność'; $hover = 'Celność wpływa na fizyczne obrażenia dystansowe, które zadajesz. Pozwala również ekwipować lepsze bronie dystansowe.'; $value = $_SESSION['player']->celnosc; break;
				case 3: $stat = 'kondycja'; $label = 'Kondycja'; $hover = 'Kondycja wpływa na Twoją maksymalną liczbę punktów zdrowia.'; $value = $_SESSION['player']->kondycja; break;
				case 4: $stat = 'inteligencja'; $label = 'Inteligencja'; $hover = 'Inteligencja wpływa na zadawane obrażenia magiczne, potencjalne efekty czarów i możliwość zakładania magicznego wyposażenia'; $value = $_SESSION['player']->inteligencja; break;
				case 5: $stat = 'wiedza'; $label = 'Wiedza'; $hover = 'Wiedza wpływa na maksymalną ilość many i czarów przygotowawczych, które możesz wybrać przed bitwą'; $value = $_SESSION['player']->wiedza; break;
				case 6: $stat = 'charyzma'; $label = 'Charyzma'; $hover = 'Charyzma jeszcze będzie coś robić'; $value = $_SESSION['player']->charyzma; break;
				case 7: $stat = 'szczescie'; $label = 'Szczęście'; $hover = 'Szczęście wpływa na szansę zadania obrażeń krytycznych'; $value = $_SESSION['player']->szczescie; break;
			}
			
			//Generating the view
			echo "<div class='statContainer noselect' id='" . $stat. "Container'>";
				echo "<div class='statLabel noselect nocursor'>" . $label . ":</div>";
				
				echo "<div class='statMinus noselect'>";
					echo '<button class="buttonMinus noselect nocursor" onclick="removeStat(\'' .$stat. '\')">-</button>';
				echo "</div>";
				
				echo "<div class='statValue noselect' id='" .$stat. "Value'>" . $value . "</div>";
				
				echo "<div class='statPlus noselect'>";
					echo '<button class="buttonPlus noselect nocursor" onclick="addStat(\'' .$stat. '\')">-</button>';
				echo "</div>";
				
				echo "<div class='statHover noselect'>" . $hover . "</div>";
			echo "</div>";
			
			//Saving total points for future cheatproofing
			$_SESSION['player']->totalStats += $value;
		}
		
		
		//Generating remaining points
		echo "<div class='statContainer noselect'></div>";
		echo "<div class='statContainer noselect'>";
		echo "<div class='statLabel noselect nocursor'>Pozostałe punkty:</div>";
		echo "<div class='statValue noselect' id='pozostale'>0</div>";
		echo "<div class='statHover noselect'>Punkty statystyk, które możesz jeszcze rozdysponować.</div>";
		echo "</div>";
	}
	
	
	//Validating sent form, creating character
	if($_POST)
	{
		$total = $_POST['sila'] + $_POST['zwinnosc'] + $_POST['celnosc'] + $_POST['kondycja'] + $_POST['inteligencja'] + $_POST['wiedza'] + $_POST['charyzma'] + $_POST['szczescie'];
		
		if($total == $_SESSION['player']->totalStats)
		{
			//Unsetting variables from previous screen because character creation is done
			unset($_SESSION['player']->totalStats);
			unset($_SESSION['iPlec']);
			unset($_SESSION['iRasa']);
			unset($_SESSION['iKlasa']);
			unset($_SESSION['iFoto']);
		
			//Getting variables from post and saving them in session		
			$_SESSION['player']->sila = $_POST['sila'];
			$_SESSION['player']->zwinnosc = $_POST['zwinnosc'];
			$_SESSION['player']->celnosc = $_POST['celnosc'];
			$_SESSION['player']->kondycja = $_POST['kondycja'];
			$_SESSION['player']->inteligencja = $_POST['inteligencja'];
			$_SESSION['player']->wiedza = $_POST['wiedza'];
			$_SESSION['player']->charyzma = $_POST['charyzma'];
			$_SESSION['player']->szczescie = $_POST['szczescie'];
			
			$conn = connectDB();
			//Setting variables in short format for easier query
			$id = $conn->real_escape_string($_SESSION['player']->id);
			$plec = $conn->real_escape_string($_SESSION['player']->plec);
			$rasa = $conn->real_escape_string($_SESSION['player']->rasa);
			$klasa = $conn->real_escape_string($_SESSION['player']->klasa);
			$foto = $conn->real_escape_string($_SESSION['player']->foto);
			$sila = $conn->real_escape_string($_SESSION['player']->sila);
			$zwinnosc = $conn->real_escape_string($_SESSION['player']->zwinnosc);
			$celnosc = $conn->real_escape_string($_SESSION['player']->celnosc);
			$kondycja = $conn->real_escape_string($_SESSION['player']->kondycja);
			$inteligencja = $conn->real_escape_string($_SESSION['player']->inteligencja);
			$wiedza = $conn->real_escape_string($_SESSION['player']->wiedza);
			$charyzma = $conn->real_escape_string($_SESSION['player']->charyzma);
			$szczescie = $conn->real_escape_string($_SESSION['player']->szczescie);
			
			$_SESSION['player']->type = 'player';
			$_SESSION['player']->level = 1;
			$_SESSION['player']->experience = 0;
			$_SESSION['player']->experiencenext = 84;
			$_SESSION['player']->remaining = 0;
			$_SESSION['player']->zloto = 100;
			$_SESSION['player']->krysztaly = 100;
			$_SESSION['player']->last_update = time();
			$_SESSION['player']->last_shop_update = time();
			$_SESSION['player']->updateMaxHP();
			$_SESSION['player']->updateMaxMana();

			$hp = $conn->real_escape_string($_SESSION['player']->hp);
			$maxhp = $conn->real_escape_string($_SESSION['player']->maxhp);
			$mana = $conn->real_escape_string($_SESSION['player']->mana);
			$maxmana = $conn->real_escape_string($_SESSION['player']->maxmana);
			
			//Updating database
			$conn->query("UPDATE users SET plec='$plec', rasa='$rasa', klasa='$klasa', foto='$foto', sila='$sila', zwinnosc='$zwinnosc', celnosc='$celnosc', kondycja='$kondycja', inteligencja='$inteligencja', wiedza='$wiedza', charyzma='$charyzma', szczescie='$szczescie', last_login=NOW(), last_update=NOW(), last_shop_update=NOW(), protected_until=NOW(), hp='$hp', maxhp='$maxhp', mana='$mana', maxmana='$maxmana', level=1 WHERE id='$id'");
			$conn->query("INSERT INTO user_mail (id) VALUES ($id)");
			$conn->query("INSERT INTO spellbooks (id) VALUES ($id)");
			$conn->query("INSERT INTO equipment (id) VALUES ($id)");
			$conn->query("INSERT INTO villages (id) VALUES ($id)");
			$_SESSION['player']->generateShop();
			$conn->close();
			
			//Moving the user to main game
			header('Location:main.php');
			exit();
		}
		else
		{
			//TODO: banowanie?
			$error_msg = "Nie oszukuj.";
		}
	}

?>



<HTML>
<Head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="index.css">
	<link rel="stylesheet" type="text/css" href="create_character2.css">
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
    
    <div id="divWizard" class="noselect"><img id="wizardFoto" src="gfx/wizard.png"></div>
    <div id="divMonster" class="noselect"><img id="monsterFoto" src="gfx/monster.png"></div>
	
	
    <div id="divMainOkno">
		<div id="divStatystyki">
			<?php generuj_rozdawanie_statow(); ?>
		</div>
		
		<div id="divButtons" class="centerLabel">
			<Form onsubmit="return validateForm();" action="create_character2.php" method="post">
				<input name="sila" id="hiddenSila" type="hidden">
				<input name="zwinnosc" id="hiddenZwinnosc" type="hidden">
				<input name="celnosc" id="hiddenCelnosc" type="hidden">
				<input name="kondycja" id="hiddenKondycja" type="hidden">
				<input name="inteligencja" id="hiddenInteligencja" type="hidden">
				<input name="wiedza" id="hiddenWiedza" type="hidden">
				<input name="charyzma" id="hiddenCharyzma" type="hidden">
				<input name="szczescie" id="hiddenSzczescie" type="hidden">
				<a href="create_character.php"><input id="backButton" class="arrow orange przycisk" name="backButton" type="button" value="Powrót"></a>
				<input id="submitButton" class="arrow orange przycisk" name="submitButton" type="submit" value="Gotowe">
			</Form>
		</div>
		
		<div id="errorLabelCreation" class='centerLabel'>		<?php	if(isset($error_msg) && $error_msg != "") { echo $error_msg; }	?>		</div>
		
	</div>
    
	
    <div id="divLogo" class="noselect"><a href="index.php"><img id="logoFoto"></a></div>
    <div id="divRejestracja" class="noselect"><a href="register.php"><img id="rejestracjaFoto"></a></div>
    <div id="divLogowanie" class="noselect"><a href="login.php"><img id="logowanieFoto"></a></div>
    
</Body>
</HTML>


<script src="jquery-ui-1.12.1/jquery-3.1.1.js"></script>
<script src="jquery-ui-1.12.1/jquery-ui.js"></script>


<script>

	//Hover handling
	$(".statLabel").hover(
		function(){
			$(this).parent().find('.statHover').show();
		},
		function(){
			$(this).parent().find('.statHover').hide();
		}
	);
	$(".statLabel").bind('mousemove', function(e){
		var top = e.pageY + 3;
		var left = e.pageX + 20;
		$(this).parent().find('.statHover').css({'top': top, 'left': left});
	});

	//Increase statistic
	function addStat(statName)
	{
		var remaining = parseInt($("#pozostale").html());
		
		if(remaining > 0)
		{
			var current = parseInt($("#" + statName + "Value").html());
			current++;
			$("#" + statName + "Value").html(current);
			
			remaining--;
			$("#pozostale").html(remaining);
		}
	}
	
	//Initial statistic values for determining minimum
	var silaInit = $("#silaValue").html();
	var zwinnoscInit = $("#zwinnoscValue").html();
	var celnosscInit = $("#celnoscValue").html();
	var kondycjaInit = $("#kondycjaValue").html();
	var inteligencjaInit = $("#inteligencjaValue").html();
	var wiedzaInit = $("#wiedzaValue").html();
	var charyzmaInit = $("#charyzmaValue").html();
	var szczescieInit = $("#szczescieValue").html();
	
	//Decrease statistic
	function removeStat(statName)
	{
		var min;
		
		switch(statName)
		{
			case 'sila': min = parseInt(silaInit) - 3; break;
			case 'zwinnosc': min = parseInt(zwinnoscInit) - 3; break;
			case 'celnosc': min = parseInt(celnosscInit) - 3; break;
			case 'kondycja': min = parseInt(kondycjaInit) - 3; break;
			case 'inteligencja': min = parseInt(inteligencjaInit) - 3; break;
			case 'wiedza': min = parseInt(wiedzaInit) - 3; break;
			case 'charyzma': min = parseInt(charyzmaInit) - 3; break;
			case 'szczescie': min = parseInt(szczescieInit) - 3; break;
		}
		
		var current = parseInt($("#" + statName + "Value").html());
		if(current > min)
		{
			current--;
			$("#" + statName + "Value").html(current);
			
			var remaining = parseInt($("#pozostale").html());
			remaining++;
			$("#pozostale").html(remaining);
		}
	}

	//Form validation before sending to php
	function validateForm()
	{
		var remaining = parseInt($("#pozostale").html());
		
		if(remaining == 0)
		{
			var setSila = $("#silaValue").html();
			var setZwinnosc = $("#zwinnoscValue").html();
			var setCelnosc = $("#celnoscValue").html();
			var setKondycja = $("#kondycjaValue").html();
			var setInteligencja = $("#inteligencjaValue").html();
			var setWiedza = $("#wiedzaValue").html();
			var setCharyzma = $("#charyzmaValue").html();
			var setSzczescie = $("#szczescieValue").html();
			
			$("#hiddenSila").val(setSila);
			$("#hiddenZwinnosc").val(setZwinnosc);
			$("#hiddenCelnosc").val(setCelnosc);
			$("#hiddenKondycja").val(setKondycja);
			$("#hiddenInteligencja").val(setInteligencja);
			$("#hiddenWiedza").val(setWiedza);
			$("#hiddenCharyzma").val(setCharyzma);
			$("#hiddenSzczescie").val(setSzczescie);
		
			return true;
		}
		else
		{
			$("#errorLabelCreation").html("Musisz rozdać wszystkie punkty statystyk.");
			
			return false;
		}
	}

</script>