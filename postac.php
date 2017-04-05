<?php

    require_once('config.php');
    login_check();
	$_SESSION['player']->updateLocally();
	drawGame($_SESSION['player']);
	$_SESSION['player']->remaining = 1;
	
	
	function drawStatystyki()
	{
		if($_SESSION['player']->remaining > 0)
		{
			$_SESSION['totalStats'] = 0;
		}
		
		//Statystyki Label
		echo "<div class='center noselect' id='statystykiLabel'>Statystyki</div>";
		echo "<div class='statContainer noselect'></div>";
		echo "<div class='statContainer noselect'></div>";
		
		
		//Generacja
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
			
			if($_SESSION['player']->remaining > 0)
			{
				//Statistics with + and - buttons
				echo "<div class='statContainer noselect' id='" . $stat. "Container'>";
					echo "<div class='statLabel noselect nocursor'>" . $label . ":</div>";
				
					echo "<div class='statPlus noselect'>";
						echo '<button class="buttonPlus noselect arrow" onclick="addStat(\'' .$stat. '\')">+</button>';
					echo "</div>";
					
					echo "<div class='statValue noselect' id='" .$stat. "Value'>" . $value . "</div>";
				
					echo "<div class='statMinus noselect'>";
						echo '<button class="buttonMinus noselect arrow" onclick="removeStat(\'' .$stat. '\')">-</button>';
					echo "</div>";
				
					echo "<div class='statHover noselect'>" . $hover . "</div>";
				echo "</div>";
				
				$_SESSION['totalStats'] += $value;
			}
			else
			{
				//Just statistics values
				echo "<div class='statContainer noselect' id='" . $stat. "Container'>";
					echo "<div class='statLabel noselect nocursor'>" . $label . ":</div>";
					echo "<div class='statValue noselect' id='" .$stat. "Value'>" . $value . "</div>";
					echo "<div class='statHover noselect'>" . $hover . "</div>";
				echo "</div>";
			}
			
			unset($stat);
			unset($label);
			unset($hover);
			unset($value);
		}
		
		//Remaining points
		if($_SESSION['player']->remaining > 0)
		{
			echo "<div class='statContainer noselect'></div>";
			echo "<div class='statContainer noselect center centerText'>Pozostałe punkty: <div id='pozostale'>" .$_SESSION['player']->remaining. "</div></div>";
			echo "<div class='statContainer noselect'></div>";
			drawButton();
			
			$_SESSION['totalStats'] += $_SESSION['player']->remaining;
		}	
	}
	
	
	
	function drawButton()
	{
		if($_SESSION['player']->remaining > 0)
		{
			//Generating submit button
			echo '<Form onsubmit="return validateForm();" action="postac.php" method="post">';
				echo '<input name="sila" type="hidden" id="hiddenSila">';
				echo '<input name="zwinnosc" type="hidden" id="hiddenZwinnosc">';
				echo '<input name="celnosc" type="hidden" id="hiddenCelnosc">';
				echo '<input name="kondycja" type="hidden" id="hiddenKondycja">';
				echo '<input name="inteligencja" type="hidden" id="hiddenInteligencja">';
				echo '<input name="wiedza" type="hidden" id="hiddenWiedza">';
				echo '<input name="charyzma" type="hidden" id="hiddenCharyzma">';
				echo '<input name="szczescie" type="hidden" id="hiddenSzczescie">';
				echo '<input name="remaining" type="hidden" id="hiddenRemaining">';
				echo '<input class="orange przycisk center arrow" type="submit" value="Zapisz" id="buttonStats">';
			echo '</Form>';	
		}
	}
	
	//Form was sent
	if($_POST)
	{
		//Getting new values from POST
		$_SESSION['player']->sila = $_POST['sila'];
		$_SESSION['player']->zwinnosc = $_POST['zwinnosc'];
		$_SESSION['player']->celnosc = $_POST['celnosc'];
		$_SESSION['player']->kondycja = $_POST['kondycja'];
		$_SESSION['player']->inteligencja = $_POST['inteligencja'];
		$_SESSION['player']->wiedza = $_POST['wiedza'];
		$_SESSION['player']->charyzma = $_POST['charyzma'];
		$_SESSION['player']->szczescie = $_POST['szczescie'];
		$_SESSION['player']->remaining = $_POST['remaining'];
		
		$total = $_POST['sila'] + $_POST['zwinnosc'] + $_POST['celnosc'] + $_POST['kondycja'] + $_POST['inteligencja'] + $_POST['wiedza'] + $_POST['charyzma'] + $_POST['szczescie'] + $_POST['remaining'];
		
		if($total == $_SESSION['totalStats'])
		{
			unset($_SESSION['totalStats']);
			unset($total);
			
			//Updating max hp and max mana
			$_SESSION['player']->updateHP();
			$_SESSION['player']->updateMana();
			//Sending the update to SQL server
			$_SESSION['player']->updateLocally();
			$_SESSION['player']->updateGlobally();
		}
		else
		{
			//TODO: Ban za cheating?
		}
	}
?>

<HTML>

<Head>
    
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="main.css">
	<link rel="stylesheet" type="text/css" href="postac.css">
    <Title>SkalpoGra</Title>
	
</Head>

<Body>

    <div id="divMainOkno">
		<div id='zdjecie'>	<?php $_SESSION['player']->drawFoto(); ?> 	</div>
		<div id='podpis'>												
			<div id='nick'>		<?php echo $_SESSION['player']->username; ?>		</div>
			<div id='podnick'> 	<?php echo $_SESSION['player']->rasa . " " . $_SESSION['player']->klasa; ?>		</div>
		</div>
		<div id='statystyki'>
			<?php drawStatystyki(); ?>
		</div>
    </div>

	
	<nav>
    <ul>
		<li><a href = "main.php"><div class='menuContainer' id='mainMenu'></div></a></li>
        <li><a href = "postac.php" class="active"><div class='menuContainer' id='postacMenu'></div></a></li>
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
		var top = e.pageY + 2;
		var left = e.pageX + 2;
		$(this).parent().find('.statHover').css({'top': top, 'left': left});
	});

	var remaining = <?php echo json_encode($_SESSION['player']->remaining); ?>;
	
	//Checking if there are unassigned, remaining points
	if(remaining > 0)
	{
		//Initial statistic values for determining minimum
		var silaInit = $("#silaValue").html();
		var zwinnoscInit = $("#zwinnoscValue").html();
		var celnosscInit = $("#celnoscValue").html();
		var kondycjaInit = $("#kondycjaValue").html();
		var inteligencjaInit = $("#inteligencjaValue").html();
		var wiedzaInit = $("#wiedzaValue").html();
		var charyzmaInit = $("#charyzmaValue").html();
		var szczescieInit = $("#szczescieValue").html();
		
		$(".statValue").css("text-align","center");
	}
	else
	{
		//$(".statValue").css("width","auto");
	}
	
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
	
	//Decrease statistic
	function removeStat(statName)
	{
		var min;
		
		switch(statName)
		{
			case 'sila': min = parseInt(silaInit); break;
			case 'zwinnosc': min = parseInt(zwinnoscInit); break;
			case 'celnosc': min = parseInt(celnosscInit); break;
			case 'kondycja': min = parseInt(kondycjaInit); break;
			case 'inteligencja': min = parseInt(inteligencjaInit); break;
			case 'wiedza': min = parseInt(wiedzaInit); break;
			case 'charyzma': min = parseInt(charyzmaInit); break;
			case 'szczescie': min = parseInt(szczescieInit); break;
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
		var setSila = $("#silaValue").html();
		var setZwinnosc = $("#zwinnoscValue").html();
		var setCelnosc = $("#celnoscValue").html();
		var setKondycja = $("#kondycjaValue").html();
		var setInteligencja = $("#inteligencjaValue").html();
		var setWiedza = $("#wiedzaValue").html();
		var setCharyzma = $("#charyzmaValue").html();
		var setSzczescie = $("#szczescieValue").html();
		var setRemaining = $("#pozostale").html();
			
		$("#hiddenSila").val(setSila);
		$("#hiddenZwinnosc").val(setZwinnosc);
		$("#hiddenCelnosc").val(setCelnosc);
		$("#hiddenKondycja").val(setKondycja);
		$("#hiddenInteligencja").val(setInteligencja);
		$("#hiddenWiedza").val(setWiedza);
		$("#hiddenCharyzma").val(setCharyzma);
		$("#hiddenSzczescie").val(setSzczescie);
		$("#hiddenRemaining").val(setRemaining);
		
		return true;
	}

</script>