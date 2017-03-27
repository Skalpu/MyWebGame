<?php

    require_once('config.php');
    login_check();
		
	if($_POST)
	{
		$conn = connectDB();
		$eID = $conn->real_escape_string($_SESSION['id']);
		
		$czar1 = $conn->real_escape_string($_POST['czar1']);
		$czar2 = $conn->real_escape_string($_POST['czar2']);
		$czar3 = $conn->real_escape_string($_POST['czar3']);
		$priorytet1 = $conn->real_escape_string($_POST['priorytet1']);
		$priorytet2 = $conn->real_escape_string($_POST['priorytet2']);
		$priorytet3 = $conn->real_escape_string($_POST['priorytet3']);
		debug_to_console($priorytet1);
		
		$conn->query("UPDATE spellbooks SET czar1=$czar1, czar2=$czar2, czar3=$czar3, priorytet1='$priorytet1', priorytet2='$priorytet2', priorytet3='$priorytet3' WHERE id=$eID");
		$conn->close();
	}
		
	function generuj_dropdown($dropdown)
	{
		$id_spella = get_stat($dropdown,'spellbooks',$_SESSION['id']);
		
		
		$czary = [];
		//				0:NAZWA					1:EFEKT				2:CZAS		3:MANA			4:DAMAGEMIN		5:DAMAGEMAX		6:ELEMENT		7:AFFECT
		$czary[1] =	[	'Płomyk', 				'obronca_damage', 	1,			10,				3, 				5,				'ogien',		''];
		$czary[2] = [	'Piorun',				'obronca_damage',	1,			10,				5,				7,				'powietrze',	''];
		$czary[3] = [	'Rzut głazem',			'obronca_damage',	1,			10,				7,				9,				'ziemia',		''];
		$czary[4] = [	'Magiczny pancerz',		'ataker_buff',		2,			20,				15,				20,				'ziemia',		'armor'];
		
		
		$num = count($czary);
		for($i = 1; $i <= $num; $i++)
		{
			if($id_spella == $i)
			{
				echo "<option value='" . $i . "' selected>" . $czary[$i][0] . "</option>";
			}
			else
			{
				echo "<option value='" . $i . "'>" . $czary[$i][0] . "</option>";
			}
		}
	}
	
	function generuj_priorytety($dropdown)
	{
		$priorytet = get_stat($dropdown,'spellbooks',$_SESSION['id']);
		
		if($priorytet == '')
		{
			echo "<option value='' selected>Nie rzucaj</option>";
			echo "<option value='start'>Czar przygotowawczy</option>";
			echo "<option value='spam'>Czar bitewny</option>";
		}
		else if($priorytet == 'start')
		{
			echo "<option value=''>Nie rzucaj</option>";
			echo "<option value='start' selected>Czar przygotowawczy</option>";
			echo "<option value='spam'>Czar bitewny</option>";
		}
		else if($priorytet == 'spam')
		{
			echo "<option value=''>Nie rzucaj</option>";
			echo "<option value='start'>Czar przygotowawczy</option>";
			echo "<option value='spam' selected>Czar bitewny</option>";
		}
		
	}

?>


<HTML>

<Head>
    
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="main.css">
	<link rel="stylesheet" type="text/css" href="magia.css">
    <Title>SkalpoGra</Title>
	
</Head>

<Body>

    <div id="divMainOkno">
		<Form action='magia.php' method='post'>
		<select id='czar1' name='czar1'><?php generuj_dropdown('czar1'); ?></select>	<Select id='priorytet1' name='priorytet1'><?php generuj_priorytety('priorytet1'); ?></Select>
		<select id='czar2' name='czar2'><?php generuj_dropdown('czar2'); ?></select>	<Select id='priorytet2' name='priorytet2'><?php generuj_priorytety('priorytet2'); ?></Select>
		<select id='czar3' name='czar3'><?php generuj_dropdown('czar3'); ?></select>	<Select id='priorytet3' name='priorytet3'><?php generuj_priorytety('priorytet3'); ?></Select>
		<input id='submit' type='submit' value='Zapisz'>
		</Form>
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
		<li><a href = "magia.php" class="active"><img id="magiamenu"></a></li>
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