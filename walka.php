<?php

	require_once('config.php');
    login_check();
	$_SESSION['player']->updateLocally();
	
	/*HIDDEN DIVY NUMEROWANE PO KOLEI KTO KOGO ATAKUJE, W TYM TEKST DANEJ RUNDY, PRZETWARZANY DOPIERO PRZEZ JQUERY I APPENDOWANY DO DIVA
	SCHOWANE DO JAKIEGOŚ JEDNEGO CONTAINER DIVA z display:none
	
	NP KTO_1 = 124(id skalpa)
	KOGO_1 = 130(id kogos)
	HIT_1 = FINAL DMG (ktory mozna odjac od hp bara)
	MANA_COST_1 = KOSZT MANY(ktory mozna odjac od mana bara)
	CRIT_1 = true/false(krytnal czy nie)
	
	MONSTERY TO NOWE OBIEKTY KLASY PLAYER
	dodać zmienną type="player" albo type="monster"
	ID PRZYDZIELANE PODCZAS ALOKACJI POTWORA DO DANEJ WALKI, TAK ZEBY NIE DUBLOWAŁY SIĘ
	CZYLI MAMY LISTĘ NP LAS
	Las = [
		0=>new Player(...)
		1=>new Player(...)
	];
	W NIEJ POTWORY, JAKAŚ ILOŚĆ JEST PRZYDZIELANA DO LISTY "OBROŃCY"
	DOPIERO WTEDY USTAWIAMY OBROŃCY[0]->id = 0
	UWAGA: MOŻE BYĆ GRACZ Z ID=0 I CO WTEDY?*/
	
	if($_POST)
	{
		if($_POST['type'] == 'arena')
		{
			$ataker = $_SESSION['player'];
			$obronca = new Player($_POST['opponent']);
			drawArena($ataker, $obronca);
		}
	}
	
	function drawArena(Player $ataker, Player $obronca)
	{
		$atakerFotoW = "10%";
		$atakerFotoH = "30%";		
		$obroncaFotoW = "10%";
		$obroncaFotoH = "30%";
		$atakerFotoTop = "22%";
		$atakerFotoLeft = "21%";
		$obroncaFotoTop = "22%";
		$obroncaFotoRight = "21%";
		$barH = "2.5%";
		
		
		$stylAtakerHP = "position: fixed; box-sizing: border-box; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; width: $atakerFotoW; height: $barH; top: 52%; left: $atakerFotoLeft;";
		$stylAtakerMP = "position: fixed; box-sizing: border-box; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; width: $atakerFotoW; height: $barH; top: 54.5%; left: $atakerFotoLeft;";
		
		$stylObroncaHP = "position: fixed; box-sizing: border-box; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; width: $obroncaFotoW; height: $barH; top: 52%; right: $obroncaFotoRight;";
		$stylObroncaMP = "position: fixed; box-sizing: border-box; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; width: $obroncaFotoW; height: $barH; top: 54.5%; right: $obroncaFotoRight;";
		
		
		
		echo "<div style='position: fixed; box-sizing: border-box; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; width: $atakerFotoW; height: $atakerFotoH; top: $atakerFotoTop; left: $atakerFotoLeft;'>";
			$ataker->drawFoto();
		echo "</div>";
		$ataker->drawHP("atakerHP", $stylAtakerHP);
		$ataker->drawMP("atakerMP", $stylAtakerMP);
		
		
		echo "<div style='position: fixed; box-sizing: border-box; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; width: $obroncaFotoW; height: $obroncaFotoH; top: $obroncaFotoTop; right: $obroncaFotoRight;'>";
			$obronca->drawFoto();
		echo "</div>";
		$obronca->drawHP("obroncaHP", $stylObroncaHP);
		$obronca->drawMP("obroncaMP", $stylObroncaMP);

		
	}
?>