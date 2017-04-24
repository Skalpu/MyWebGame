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
	
	
	//Resetting pointers
	if(!isset($_SESSION['iPlec']))
	{
		$_SESSION['iPlec'] = 0;
		$_SESSION['iRasa'] = 0;
		$_SESSION['iKlasa'] = 0;
		$_SESSION['iFoto'] = 0;
	}
	
	
	//Counting portraits
	$directory = "./gfx/portrety/";
	$f = new FilesystemIterator($directory, FilesystemIterator::SKIP_DOTS);
    $filecount = iterator_count($f);
	
	
	//Moving to character creation 2
	if($_POST)
	{	
		$_SESSION['player']->plec = $_POST['plec'];
		$_SESSION['player']->rasa = $_POST['rasa'];
		$_SESSION['player']->klasa = $_POST['klasa'];
		$_SESSION['player']->foto = "portrety/" . $_POST['foto'];
		$_SESSION['iPlec'] = $_POST['iplec'];
		$_SESSION['iRasa'] = $_POST['irasa'];
		$_SESSION['iKlasa'] = $_POST['iklasa'];
		$_SESSION['iFoto'] = $_POST['ifoto'];

		header('Location:create_character2.php');
        exit();
	}
	
?>



<HTML>
<Head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="index.css">
	<link rel="stylesheet" type="text/css" href="create_character.css">
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
		<div id="divPlec" class="centerLabel">
			<div id="plecLeft" class="left arrow noselect">&larr;</div>
			<div id="plecTekst" class="center noselect"></div>
			<div id="plecRight" class="right arrow noselect">&rarr;</div>
        </div>
		<div id="divRasa" class="centerLabel">
			<div id="rasaLeft" class="left arrow noselect">&larr;</div>
			<div id="rasaTekst" class="center noselect"></div>
			<div id="rasaRight" class="right arrow noselect">&rarr;</div>
		</div>
		<div id="divKlasa" class="centerLabel">
			<div id="klasaLeft" class="left arrow noselect">&larr;</div>
			<div id="klasaTekst" class="center noselect"></div>
			<div id="klasaRight" class="right arrow noselect">&rarr;</div>
		</div>
		<div id="divFoto">
			<div id="fotoContainer"></div>
			<div id="fotoLeft" class="left arrow noselect">&larr;</div>
			<div id="fotoTekst" class="center noselect"></div>
			<div id="fotoRight" class="right arrow noselect">&rarr;</div>
		</div>
		<div id="divOpis">
			<div id="opisTekst"></div>
		</div>
		<div id="divContinue" class="centerLabel">
			<Form onsubmit="return validateForm();" action="create_character.php" method="post">
				<input name="plec" id="hiddenPlec" type="hidden">
				<input name="rasa" id="hiddenRasa" type="hidden">
				<input name="klasa" id="hiddenKlasa" type="hidden">
				<input name="foto" id="hiddenFoto" type="hidden">
				<input name="iplec" id="hiddeniplec" type="hidden">
				<input name="irasa" id="hiddenirasa" type="hidden">
				<input name="iklasa" id="hiddeniklasa" type="hidden">
				<input name="ifoto" id="hiddenifoto" type="hidden">
				<input class="arrow orange przycisk" name="submitButton" type="submit" value="Dalej">
			</Form>
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
	
	//Setting initial pointers
	var iPlec = parseInt(<?php echo json_encode($_SESSION['iPlec']); ?>);
	var iRasa = parseInt(<?php echo json_encode($_SESSION['iRasa']); ?>);
	var iKlasa = parseInt(<?php echo json_encode($_SESSION['iKlasa']); ?>);
	var iFoto = parseInt(<?php echo json_encode($_SESSION['iFoto']); ?>);
	var FotoCount = <?php echo json_encode($filecount); ?>;
    
	//Filing lists
	var Plec = ["Mężczyzna", "Kobieta"];
	var Rasa = ["Człowiek", "Ork", "Leśny elf", "Krasnolud", "Wysoki elf"];
	var czlowiekOpis = "Ludzie są najbardziej wszechstronną spośród ras Vangardu.";
	var orkOpis = "Silni, zwinni i niebywale wytrzymali - orkowie to rasa naturalnych wojowników, którym nie straszny jest żaden konflikt. Wojaczkowy tryb życia nie sprzyja jednak edukacji, dlatego z niektórymi przedstawicielami tej rasy trudno się czasem dogadać.";
	var RasaOpis = [czlowiekOpis, orkOpis, "Leśny elf-opis", "Krasnolud-opis", "Wysoki elf-opis"];
	
	
	var Klasa = ["Barbarzyńca", "Wojownik", "Łotrzyk", "Łowca", "Mnich", "Paladyn", "Kleryk", "Bard", "Druid", "Czarodziej", "Czarnoksiężnik"];
	var KlasaOpis = ["Barbarzyńca-opis", "Wojownik-opis", "Łotrzyk-opis", "Łowca-opis", "Mnich-opis", "Paladyn-opis", "Kleryk-opis", "Bard-opis", "Druid-opis", "Czarodziej-opis", "Czarnoksiężnik-opis"];
	var Foto = [];
	for(i = 0; i < FotoCount; i++)
	{
		Foto[i] = "url(gfx/portrety/" + [i] + ".jpg?";
	}
	
	//Setting initial texts/photos etc
	$("#plecTekst").html(Plec[iPlec]);
	$("#rasaTekst").html(Rasa[iRasa]);
	$("#klasaTekst").html(Klasa[iKlasa]);
	$("#fotoTekst").html((iFoto+1) + "/" + FotoCount);
	$("#fotoContainer").css("background-image", Foto[iFoto] + new Date().getTime() + ")");
	$("#opisTekst").html(RasaOpis[iRasa]);
	
	
	//Function that travels the lists and loops around edges
	function travelList(direction, list, pointer, onSuccess)
	{
		if(direction == "left")
		{
			if(window[pointer] == 0)
			{
				window[pointer] = list.length - 1;
			}
			else
			{
				window[pointer]--;
			}
		}
		else if(direction == "right")
		{
			if(window[pointer] == list.length - 1)
			{
				window[pointer] = 0;
			}
			else
			{
				window[pointer]++;
			}
		}
		
		onSuccess();
	}
	function updatePlec()
	{
		$("#plecTekst").html(Plec[iPlec]);
	}
	function updateRasa()
	{
		$("#rasaTekst").html(Rasa[iRasa]);
		$("#opisTekst").html(RasaOpis[iRasa]);
	}
	function updateKlasa()
	{
		$("#klasaTekst").html(Klasa[iKlasa]);
		$("#opisTekst").html(KlasaOpis[iKlasa]);
	}
	function updateFoto()
	{
		var numer = iFoto + 1;
		var tekst = numer + "/" + FotoCount;
		$("#fotoTekst").html(tekst);
		$("#fotoContainer").css("background-image", Foto[iFoto] + new Date().getTime() + ")");
	}

	
	$("#plecLeft").click(function(){
		travelList("left", Plec, "iPlec", updatePlec);
	});
	$("#plecRight").click(function(){
		travelList("right", Plec, "iPlec", updatePlec);
	});
	$("#rasaLeft").click(function(){
		travelList("left", Rasa, "iRasa", updateRasa);
	});
	$("#rasaRight").click(function(){
		travelList("right", Rasa, "iRasa", updateRasa);
	});
	$("#klasaLeft").click(function(){
		travelList("left", Klasa, "iKlasa", updateKlasa);
	});
	$("#klasaRight").click(function(){
		travelList("right", Klasa, "iKlasa", updateKlasa);
	});
	$("#fotoLeft").click(function(){
		travelList("left", Foto, "iFoto", updateFoto);
	});
	$("#fotoRight").click(function(){
		travelList("right", Foto, "iFoto", updateFoto);
	});
	
	
	//Form validation before passing to php
	function validateForm()
	{
		var setPlec = $("#plecTekst").html();
		var setRasa = $("#rasaTekst").html();
		var setKlasa = $("#klasaTekst").html();
		var setFoto = iFoto;
		
		var valid = true;
		
		//Checking validity
		if($.inArray(setPlec, Plec) == -1 || $.inArray(setRasa, Rasa) == -1 || $.inArray(setKlasa, Klasa) == -1 || setFoto < 0 || setFoto >= FotoCount)
		{
			valid = false;
		}
		
		if(valid == true)
		{
			$("#hiddenPlec").val(setPlec);
			$("#hiddenRasa").val(setRasa);
			$("#hiddenKlasa").val(setKlasa);
			$("#hiddenFoto").val(setFoto);
			$("#hiddeniplec").val(iPlec);
			$("#hiddenirasa").val(iRasa);
			$("#hiddeniklasa").val(iKlasa);
			$("#hiddenifoto").val(iFoto);
			return true;
		}
		else
		{
			return false;
		}	
	}
	
</script>