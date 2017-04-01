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
			//Creating Player objects used for the fight
			$attackers = [];
			$defenders = [];
			array_push($attackers, $_SESSION['player']);
			array_push($defenders, new Player($_POST['opponent']));
			//Getting them ready for the fight(hp regen, gold income etc)
			$attackers[0]->updateLocally();
			$defenders[0]->updateLocally();
			//Drawing HP bars etc
			drawArena($attackers[0], $defenders[0]);
			//Drawing the fight
			drawCombat($attackers, $defenders);
			//Save the results of the fight
			$attackers[0]->updateGlobally();
			$defenders[0]->updateGlobally();
			
			unset($attackers);
			unset($defenders);
		}
	}
	
	function drawArena(Player $ataker, Player $obronca)
	{
		$atakerFotoW = "10%";
		$obroncaFotoW = "10%";
		$atakerFotoH = "30%";
		$obroncaFotoH = "30%";
		$atakerFotoTop = "18%";
		$obroncaFotoTop = "18%";
		$atakerFotoLeft = "21.5%";
		$obroncaFotoRight = "21.5%";
		$barH = "2.5%";
		$bar1Top = "48%";
		$bar2Top = "50.5%";

		
		$stylAtakerHP = "position: fixed; box-sizing: border-box; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; width: $atakerFotoW; height: $barH; top: $bar1Top; left: $atakerFotoLeft;";
		$stylAtakerMP = "position: fixed; box-sizing: border-box; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; width: $atakerFotoW; height: $barH; top: $bar2Top; left: $atakerFotoLeft;";

		
		$stylObroncaHP = "position: fixed; box-sizing: border-box; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; width: $obroncaFotoW; height: $barH; top: $bar1Top; right: $obroncaFotoRight;";
		$stylObroncaMP = "position: fixed; box-sizing: border-box; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; width: $obroncaFotoW; height: $barH; top: $bar2Top; right: $obroncaFotoRight;";

		
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

	function drawCombat($attackers, $defenders)
	{
		$dead_attackers = [];
		$dead_defenders = [];
		
		while(count($attackers) > 0 and count($defenders) > 0)
		{
			echo $attackers[0]->hp . "<br>";
			
			$attackers[0]->hp -= 10;
			$defenders[0]->hp -= 10;
			
			foreach($attackers as $attKey => $att)
			{
				if($att->hp < 0)
				{
					$att->hp = 0;
					array_push($dead_attackers, $att);
					unset($attackers[$attKey]);
				}
			}
			foreach($defenders as $defKey => $def)
			{
				if($def->hp < 0)
				{
					$def->hp = 0;
					array_push($dead_defenders, $def);
					unset($defenders[$defKey]);
				}
			}
		}
	}
	
	
?>