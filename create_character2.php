//TODO LAST LOGIN UPDATE ON CREATE

<?php

	//Checking if logged in
    require_once('config.php');
    login_check();
	
	//Checking if character isn't already created
	if (get_stat("last_login","users",$_SESSION['id']) != null)
    {
        header('Location:main.php');
        exit();
    }
	
	//Generating initial stat distribution
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
			
			echo "<div class='statContainer' id='" . $stat. "Container'>";
				echo "<div class='statLabel'>" . $label . ":</div>";
				
				echo "<div class='statMinus'>";
					echo "<button class='buttonMinus' onclick=''>-</button>";
				echo "</div>";
				
				echo "<div class='statValue'>" . $value . "</div>";
				
				echo "<div class='statPlus'>";
					echo "<button class='buttonPlus' onclick=''>+</button>";
				echo "</div>";
				
				echo "<div class='statHover'>" . $hover . "</div>";
			echo "</div>";
		}
		
		//Generating remaining points
		echo "<div class='statContainer'></div>";
		echo "<div class='statContainer'>";
		echo "<div class='statLabel'>Pozostałe punkty:</div>";
		echo "<div class='statValue'>0</div>";
	}

?>



<HTML>
<Head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="index.css">
	<link rel="stylesheet" type="text/css" href="create_character2.css">
    <Title>SkalpoGra</Title>
	
</Head>

<Body>
    
    <div id="divWizard" class="noselect"><img id="wizardFoto" src="gfx/wizard.png"></div>
    <div id="divMonster" class="noselect"><img id="monsterFoto" src="gfx/monster.png"></div>
	
	
    <div id="divMainOkno">
		<div id="divStatystyki">
			<?php generuj_rozdawanie_statow(); ?>
		</div>
	</div>
    
	
    <div id="divLogo" class="noselect"><a href="index.php"><img id="logoFoto"></a></div>
    <div id="divRejestracja" class="noselect"><a href="register.php"><img id="rejestracjaFoto"></a></div>
    <div id="divLogowanie" class="noselect"><a href="login.php"><img id="logowanieFoto"></a></div>
    
</Body>
</HTML>


<script src="jquery-ui-1.12.1/jquery-3.1.1.js"></script>
<script src="jquery-ui-1.12.1/jquery-ui.js"></script>


<script>

</script>