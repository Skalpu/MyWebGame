<?php
    
    require_once('config.php');
    login_check();
	
	$punkty_do_rozdania = get_stat('punkty_do_rozdania','users',$_SESSION['id']);	
	
	function show_level_up()
	{
		$punkty_do_rozdania = get_stat('punkty_do_rozdania','users',$_SESSION['id']);
		
		$conn = connectDB();
		$id = $_SESSION['id'];
		$result = $conn->query("SELECT sila,zwinnosc,celnosc,kondycja,inteligencja,wiedza,charyzma,szczescie FROM users WHERE id=$id");
		$row = mysqli_fetch_row($result);	
			
		for ($i = 0; $i <= 7; $i++)
		{
			$stat = '';
			$label = '';
			$hover = '';
				
			switch($i)
			{
				case 0: $stat = 'sila'; $label = 'Siła'; $hover = 'Każdy punkt siły wpływa na obrażenia fizyczne w walce wręcz. Silni poszukiwacze przygód są również w stanie nosić ciężkie zbroje płytowe i korzystać z większego arsenału broni bliskodystansowych.'; break;
				case 1: $stat = 'zwinnosc'; $label = 'Zwinność'; $hover = 'Zwinność wpływa na twoją szansę na trafienie przeciwnika oraz na uniknięcie jego ataków.'; break;
				case 2: $stat = 'celnosc'; $label = 'Celność'; $hover = 'Celność wpływa na fizyczne obrażenia dystansowe, które zadajesz. Pozwala również ekwipować lepsze bronie dystansowe.'; break;
				case 3: $stat = 'kondycja'; $label = 'Kondycja'; $hover = 'Kondycja wpływa na Twoją maksymalną liczbę punktów zdrowia.'; break;
				case 4: $stat = 'inteligencja'; $label = 'Inteligencja'; $hover = 'Inteligencja wpływa na zadawane obrażenia magiczne, potencjalne efekty czarów i możliwość zakładania magicznego wyposażenia'; break;
				case 5: $stat = 'wiedza'; $label = 'Wiedza'; $hover = 'Wiedza wpływa na maksymalną ilość many i czarów przygotowawczych, które możesz wybrać przed bitwą'; break;
				case 6: $stat = 'charyzma'; $label = 'Charyzma'; break;
				case 7: $stat = 'szczescie'; $label = 'Szczęście'; $hover = 'Szczęście wpływa na szansę zadania obrażeń krytycznych'; break;
			}
			
			if($punkty_do_rozdania > 0)
			{
				echo "<div class='statContainer' id='" .$stat. "Container'>	<div class='statLabel'>" .$label . ":</div>	<div class='statMinus'><button class='buttonMinus' onclick=\"odejmijStat('$stat',".$row[$i].")\">-</button></div>		<div class='statValue'>" .$row[$i].	"</div>		<div class='statPlus'><button class='buttonPlus' onclick=\"dodajStat('$stat')\">+</button></div>	<div class='statHover'>" .$hover. "</div>	</div>";
			}
			else
			{
				echo "<div class='statContainer'>	<div class='statLabel'>" .$label . ":</div>	<div class='statValue'>" .$row[$i].	"</div>		<div class='statHover'>" .$hover. "</div>	</div>";
			}
		}
		
		if($punkty_do_rozdania > 0)
		{
			echo "<br><div class='statContainer'>	<div class='statLabel'>Pozostałe punkty:</div>	<div class='statValue' id='pozostale'>" .$punkty_do_rozdania . "</div></div>";
			echo "<div class='statContainer'><button id='zapisz' onclick=\"zapisz()\">Zapisz</button></div>";
		}
			
		$conn->close();
	}
	
	if($_POST)
	{
		$sila = $_POST['sila'];
		$zwinnosc = $_POST['zwinnosc'];
		$celnosc = $_POST['celnosc'];
		$kondycja = $_POST['kondycja'];
		$inteligencja = $_POST['inteligencja'];
		$wiedza = $_POST['wiedza'];
		$charyzma = $_POST['charyzma'];
		$szczescie = $_POST['szczescie'];
		$pozostale = $_POST['pozostale'];
		$id = $_SESSION['id'];
		
		$conn = connectDB();
		$maxhp = $kondycja * 10;
		$maxmana = $wiedza * 10;
		$conn->query("UPDATE users SET maxhp=$maxhp, maxmana=$maxmana, sila=$sila,zwinnosc=$zwinnosc,celnosc=$celnosc,kondycja=$kondycja,inteligencja=$inteligencja,wiedza=$wiedza,charyzma=$charyzma,szczescie=$szczescie,punkty_do_rozdania=$pozostale WHERE id=$id");
		$conn->close();
	}
	
?>

<HTML>

<Head>
    
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="main.css">
	<link rel="stylesheet" type="text/css" href="postac.css">
	<link rel="stylesheet" type="text/css" href="statystyki.css">
    <Title>SkalpoGra</Title>
	
</Head>
    
<Body>
        
    <div id="divMainOkno">
        <div id="divCharImage">          						<?php  echo read_stats($_SESSION['id'], 'zdjecie');   		?>    </div>
        <div id="divPodstawowe"><div id="podstawoweDane">       <?php  echo read_stats($_SESSION['id'], 'podstawowe');   	?>    </div></div>
		<div id="divLevelUp"> <?php show_level_up() ?>	</div>
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
        <li><a href = "postac.php" class="active"><img id="postacmenu"></a></li>
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
	

	
	
	$(".statLabel").hover(
		function(){
			$(this).parent().find('.statHover').show();
		},
		function(){
			$(this).parent().find('.statHover').hide();
		}
	);
	$(".statLabel").bind('mousemove', function(e){
		var top = e.pageY + 10;
		var left = e.pageX + 20;
		$(this).parent().find('.statHover').css({'top': top, 'left': left});
	});
	
	
	
	var punkty_do_rozdania = <?php echo json_encode($punkty_do_rozdania); ?>;
	if (punkty_do_rozdania == 0)
	{
		$(".statValue").css("text-align","right");
		$(".statValue").css("float","none");
		$(".statValue").css("left", "auto");
		$(".statValue").css("right", "3%");
	}
	
	function odejmijStat(stat, initial)
	{
		var container = "#" + stat + "Container";
		var aktualna = $(container).find('.statValue').html();

		if (aktualna > initial)
		{
			aktualna--;
			$(container).find('.statValue').html(aktualna);
			
			var pozostale = $("#pozostale").html();
			pozostale++;
			$("#pozostale").html(pozostale);
		}
	}
	function dodajStat(stat)
	{
		var container = "#" + stat + "Container";
		var aktualna = $(container).find('.statValue').html();
		
		var pozostale = $("#pozostale").html();
		if (pozostale > 0)
		{
			pozostale--;
			$("#pozostale").html(pozostale);
			
			aktualna++;
			$(container).find('.statValue').html(aktualna);
		}	
	}
	function zapisz()
	{
		$.ajax({
			type: "POST",
			url: "postac.php",
			data: {'sila': $("#silaContainer").find('.statValue').html(),
			'zwinnosc': $("#zwinnoscContainer").find('.statValue').html(),
			'celnosc': $("#celnoscContainer").find('.statValue').html(),
			'kondycja': $("#kondycjaContainer").find('.statValue').html(),
			'inteligencja': $("#inteligencjaContainer").find('.statValue').html(),
			'wiedza': $("#wiedzaContainer").find('.statValue').html(),
			'charyzma': $("#charyzmaContainer").find('.statValue').html(),
			'szczescie': $("#szczescieContainer").find('.statValue').html(),
			'pozostale': $("#pozostale").html()
			}
		});
		location.reload();
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