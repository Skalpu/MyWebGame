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
	
	function drawArena(Player $attacker, Player $defender)
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
		$attacker->drawFoto();
		echo "</div>";
		$attacker->drawHP("atakerHP", $stylAtakerHP);
		$attacker->drawMP("atakerMP", $stylAtakerMP);
		
		
		echo "<div style='position: fixed; box-sizing: border-box; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; width: $obroncaFotoW; height: $obroncaFotoH; top: $obroncaFotoTop; right: $obroncaFotoRight;'>";
		$defender->drawFoto();
		echo "</div>";
		$defender->drawHP("obroncaHP", $stylObroncaHP);
		$defender->drawMP("obroncaMP", $stylObroncaMP);		
	}

	function drawCombat($attackers, $defenders)
	{
		$dead = [];
		$round = 0;
		$round_time = 5;
		
		//Initial settings
		$result = initializeFighters($attackers, $defenders);
		$attackers = $result["attackers"];
		$defenders = $result["defenders"];
		
		echo "<div id='combatWindow'>";
		
		//Fight loop
		while(count($attackers) > 0 and count($defenders) > 0)
		{
			//Checking for new round
			$result = endRound($attackers, $defenders, $round, $round_time);
			$attackers = $result["attackers"];
			$defenders = $result["defenders"];
			$round = $result["round"];
			
			//Melee fight
			$result = meleeFight($attackers, $defenders, $dead);
			$attackers = $result["attackers"];
			$defenders = $result["defenders"];
			$dead = $result["dead"];	
		}
		
		echo "</div>";
		
		//Aftermath
		$result = aftermath($attackers, $defenders);
		$attackers = $result["attackers"];
		$defenders = $result["defenders"];
	}
	
	
	function initializeFighters($attackers, $defenders)
	{
		$fists = new Item();
		$fists->name = "Pięści";
		$fists->slot = "lefthand";
		$fists->type = "melee";
		$fists->subtype = "none";
		$fists->dmgmin = 3;
		$fists->dmgmax = 5;
		$fists->attackspeed = 1;
		$fists->critchance = 3;
		
		
		foreach($attackers as $att)
		{
			$att->did_move = false;
			$att->side = "attacker";
			$att->spells_only = false;
			
			if($att->equipment["lefthand"] == "")
			{
				$att->equipItem($fists, false);
			}
		}
		foreach($defenders as $def)
		{
			$def->did_move = false;
			$def->side = "defender";
			$def->spells_only = false;
			
			if($def->equipment["lefthand"] == "")
			{
				$def->equipItem($fists, false);
			}
		}
		
		
		return [
			"attackers" => $attackers,
			"defenders" => $defenders
		];
	}
	
	function endRound($attackers, $defenders, $round, $round_time)
	{
		//Checking if attackers moved
		$attackers_moved = false;
		foreach($attackers as $att)
		{
			if ($att->did_move == true)
			{
				$attackers_moved = true;
			}
		}
		
		//Checking if defenders moved
		$defenders_moved = false;
		foreach($defenders as $def)
		{
			if ($def->did_move == true)
			{
				$defenders_moved = true;
			}
		}
		
		
		//Nobody moved -> new turn
		if($attackers_moved == false and $defenders_moved == false)
		{
			$round++;
			
			foreach($attackers as $att)
			{
				$att->time_remaining = $round_time;
			}
			foreach($defenders as $def)
			{
				$def->time_remaining = $round_time;
			}
		}
		
	
		return [
			"attackers" => $attackers,
			"defenders" => $defenders,
			"round" => $round
		];
	}
	
	function meleeFight($attackers, $defenders, $dead)
	{
		$fighters = [];
		
		//Randomising the attack order
		foreach($attackers as $att)
		{
			array_push($fighters, $att);
		}
		foreach($defenders as $def)
		{
			array_push($fighters, $def);
		}
		
		shuffle($fighters);
		foreach($fighters as $k => $fig)
		{
			//Checking if player isn't a mage
			if($fig->spells_only == false)
			{
				//Check if the player is still alive
				if($fig->hp <= 0)
				{
					array_push($dead, $fig);
					unset($fighters[$k]);
				}
				else
				{
					//Looking for an opponent
					if($fig->side == "attacker")
					{
						for($i = 0; $i < count($fighters); $i++)
						{
							if($fighters[$i]->side == "defender")
							{
								break;
							}
						}
					}
					else if($fig->side == "defender")
					{
						for($i = 0; $i < count($fighters); $i++)
						{
							if($fighters[$i]->side == "attacker")
							{
								break;
							}
						}
					}
				
					//Found an opponent, do melee hit
					$result = hit($fig, $fighters[$i]);
					$fig = $result["attacker"];
					$fighters[$i] = $result["defender"];
				
				}
			}
		}
		
		unset($attackers);
		unset($defenders);
		$attackers = [];
		$defenders = [];
		
		foreach($fighters as $fig)
		{
			if($fig->side == "attacker")
			{
				array_push($attackers, $fig);
			}
			else if($fig->side == "defender")
			{
				array_push($defenders, $fig);
			}
		}
		
		return [
			"attackers" => $attackers,
			"defenders" => $defenders,
			"dead" => $dead
		];
	}
	
	function hit(Player $attacker, Player $defender)
	{
		//echo $attacker->equipment["lefthand"]->name . "<br>";
		//$defender->hp -= 5;
		//echo "$attacker->username atakuje $defender->username za 5. Pozostało $defender->hp <br>";
		
		//Checking if player has enough movement
		if($attacker->time_remaining >= 1/$attacker->attackspeed)
		{
			$attacker->time_remaining -= 1/$attacker->attackspeed;
			$attacker->did_move = true;
			
			//Randomising base damage
			$dmg = rand($attacker->dmgmin, $attacker->dmgmax);
			
			
		}
		
		
		return [
			"attacker" => $attacker,
			"defender" => $defender
		];
	}

	function aftermath($attackers, $defenders)
	{
		//Unequipping fists
		foreach($attackers as $att)
		{
			if($att->equipment["lefthand"] != "")
			{
				if($att->equipment["lefthand"]->name == "Pięści")
				{
					$att->unequipItem($att->equipment["lefthand"], false);
				}
			}
		}
		foreach($defenders as $def)
		{
			if($def->equipment["lefthand"] != "")
			{
				if($def->equipment["lefthand"]->name == "Pięści")
				{
					$def->unequipItem($def->equipment["lefthand"], false);
				}
			}
		}
		
		return [
			"attackers" => $attackers,
			"defenders" => $defenders
		];
	}

?>

<HTML>
<Head>
	<link rel="stylesheet" type="text/css" href="walka.css">
</Head>
</HTML>