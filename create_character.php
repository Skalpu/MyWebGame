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
	
	//Resetting variables
	$_SESSION['plec'] = "";
	$_SESSION['rasa'] = "";
	$_SESSION['klasa'] = "";
	$_SESSION['foto'] = "";
	
	//Counting portraits
	$directory = "./gfx/portrety/";
	$f = new FilesystemIterator($directory, FilesystemIterator::SKIP_DOTS);
    $filecount = iterator_count($f);
	
	//Moving to character creation 2
	if($_POST)
	{
		$_SESSION['plec'] = $_POST['plec'];
		$_SESSION['rasa'] = $_POST['rasa'];
		$_SESSION['klasa'] = $_POST['klasa'];
		$_SESSION['foto'] = $_POST['foto'];
		
		header('Location:create_character2.php');
        exit();
	}
	
?>



<HTML>
<Head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="index.css">
	<link rel="stylesheet" type="text/css" href="create_character.css">
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
			<div id="fotoTekst" class="center noselect">1/</div>
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
				<input name="submitButton" type="submit" value="Kontynuuj">
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

	//Setting pointers at 0
	var iPlec = 0;
	var iRasa = 0;
	var iKlasa = 0;
	var iFoto = 0;
	var FotoCount = <?php echo json_encode($filecount); ?>;
	
	//Filing lists
	var Plec = ["Mężczyzna", "Kobieta"];
	var Rasa = ["Człowiek", "Ork", "Leśny elf", "Krasnolud", "Wysoki elf"];
	var RasaOpis = ["Człowiek-opis", "Ork-opis", "Leśny elf-opis", "Krasnolud-opis", "Wysoki elf-opis"];
	var Klasa = ["Barbarzyńca", "Wojownik", "Łotrzyk", "Łowca", "Mnich", "Paladyn", "Kleryk", "Bard", "Druid", "Czarodziej", "Czarnoksiężnik"];
	var KlasaOpis = ["Barbarzyńca-opis", "Wojownik-opis", "Łotrzyk-opis", "Łowca-opis", "Mnich-opis", "Paladyn-opis", "Kleryk-opis", "Bard-opis", "Druid-opis", "Czarodziej-opis", "Czarnoksiężnik-opis"];
	var Foto = [];
	for(i = 0; i < FotoCount; i++)
	{
		Foto[i] = "url(gfx/portrety/" + [i] + ".jpg?";
	}
	
	//Setting initial texts/photos etc
	$("#plecTekst").html(Plec[0]);
	$("#rasaTekst").html(Rasa[0]);
	$("#klasaTekst").html(Klasa[0]);
	$("#fotoTekst").html("1/" + FotoCount);
	$("#fotoContainer").css("background-image", Foto[0] + new Date().getTime() + ")");
	$("#opisTekst").html(RasaOpis[0]);
	
	
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
	
	function validateForm()
	{
		var setPlec = $("#plecTekst").html();
		var setRasa = $("#rasaTekst").html();
		var setKlasa = $("#klasaTekst").html();
		var setFoto = iFoto;
		var cheating = false;
		
		
		if($.inArray(setPlec, Plec) == -1)
		{
			cheating = true;
		}
		else if($.inArray(setRasa, Rasa) == -1)
		{
			cheating = true;
		}
		else if($.inArray(setKlasa, Klasa) == -1)
		{
			cheating = true;
		}
		else if(setFoto < 0)
		{
			cheating = true;
		}
		else if(setFoto >= FotoCount)
		{
			cheating = true;
		}
		
		alert(cheating);
		
		if(cheating == false)
		{
			$("#hiddenPlec").val(setPlec);
			$("#hiddenRasa").val(setRasa);
			$("#hiddenKlasa").val(setKlasa);
			$("#hiddenFoto").val(setFoto);
			return true;
		}
		else
		{
			alert("Nie oszukuj");
			return false;
		}
		
	}

	
</script>