<?php

    require_once('config.php');
    login_check();
    
	if (get_stat("last_login","users",$_SESSION['id']) != null)
    {
        //header('Location:main.php');
        //exit();
    }
	
    if($_POST)
    {
		header('Location:main.php');
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
		<div id="divPlec">
			<div id="plecLeft" class="left arrow noselect">&larr;</div>
			<div id="plecTekst" class="center noselect">Mężczyzna</div>
			<div id="plecRight" class="right arrow noselect">&rarr;</div>
        </div>
		<div id="divRasa">
			<div id="rasaLeft" class="left arrow noselect">&larr;</div>
			<div id="rasaTekst" class="center noselect">Człowiek</div>
			<div id="rasaRight" class="right arrow noselect">&rarr;</div>
		</div>
		<div id="divKlasa">
			<div id="klasaLeft" class="left arrow noselect">&larr;</div>
			<div id="klasaTekst" class="center noselect">Barbarzyńca</div>
			<div id="klasaRight" class="right arrow noselect">&rarr;</div>
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

	var iPlec = 0;
	var iRasa = 0;
	var iKlasa = 0;
	var iFoto = 0;
	var Plec = ["Mężczyzna", "Kobieta"];
	var Rasa = ["Człowiek", "Ork", "Leśny elf", "Krasnolud", "Wysoki elf"];
	var Klasa = ["Barbarzyńca", "Wojownik", "Łotrzyk", "Łowca", "Mnich", "Paladyn", "Kleryk", "Bard", "Druid", "Czarodziej", "Czarnoksiężnik"];
	var Foto = [
		
	];
	
	
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
	}
	function updateKlasa()
	{
		$("#klasaTekst").html(Klasa[iKlasa]);
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
	
	
</script>