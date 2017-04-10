<?php

	require_once('config.php');
    login_check();
	$_SESSION['player']->updateLocally();
	
	
	if($_POST)
	{
		if($_POST['type'] == 'arena')
		{
			//Creating Player objects used for the fight
			$attackers = [];
			$defenders = [];
			array_push($attackers, $_SESSION['player']);
			array_push($defenders, Player::withID($_POST['opponent']));
			//Getting them ready for the fight(hp regen, gold income etc)
			foreach($attackers as $att)
			{
				$att->updateLocally();
			}
			foreach($defenders as $def)
			{
				$def->updateLocally();
			}
			//Drawing HP bars etc
			drawArena($attackers[0], $defenders[0]);
			//Drawing the fight
			$result = drawCombat($attackers, $defenders, "arena");
			$attackers = $result["attackers"];
			$defenders = $result["defenders"];
			$iterator = $result["iterator"];
			//Save the results of the fight
			foreach($attackers as $att)
			{
				$att->updateStatsGlobally();
				
				if($att->id == $_SESSION['player']->id and $att->username == $_SESSION['player']->username)
				{
					$_SESSION['player'] = $att;
				}
			}
			foreach($defenders as $def)
			{
				$def->updateStatsGlobally();
			}
			
			
			unset($attackers);
			unset($defenders);
		}
		else if($_POST['type'] == 'wyprawa')
		{
			//Creating Player objects used for the fight
			$attackers = [];
			$defenders = [];
			array_push($attackers, $_SESSION['player']);
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
		$atakerFotoLeft = "21.75%";
		$obroncaFotoRight = "21.75%";
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
		$attacker->drawHP("HP" . $attacker->id, $stylAtakerHP);
		$attacker->drawMP("MP" . $attacker->id, $stylAtakerMP);
		
		
		echo "<div style='position: fixed; box-sizing: border-box; -webkit-box-sizing: border-box; -moz-box-sizing: border-box; width: $obroncaFotoW; height: $obroncaFotoH; top: $obroncaFotoTop; right: $obroncaFotoRight;'>";
		$defender->drawFoto();
		echo "</div>";
		$defender->drawHP("HP" . $defender->id, $stylObroncaHP);
		$defender->drawMP("MP" . $defender->id, $stylObroncaMP);		
	}

	function drawCombat($attackers, $defenders, $fightType)
	{
		$dead = [];
		$round = 0;
		$round_time = 5;
		$iterator = 0;
		
		//Initial settings
		$result = initializeFighters($attackers, $defenders);
		$attackers = $result["attackers"];
		$defenders = $result["defenders"];
		
		echo "<div id='combatWindow'>";
		
		//Fight loop
		while(count($attackers) > 0 and count($defenders) > 0)
		{
			//Checking for new round
			$result = endRound($attackers, $defenders, $round, $round_time, $iterator);
			$attackers = $result["attackers"];
			$defenders = $result["defenders"];
			$round = $result["round"];
			$iterator = $result["iterator"];
			
			//Melee fight
			$result = meleeFight($attackers, $defenders, $dead, $iterator);
			$attackers = $result["attackers"];
			$defenders = $result["defenders"];
			$dead = $result["dead"];	
			$iterator = $result["iterator"];
		}
		
		echo "</div>";
		
		
		if(count($attackers) == 0)
		{
			$winner = "defenders";
		}
		else if(count($defenders) == 0)
		{
			$winner = "attackers";
		}
		
		//Aftermath (unequipping fists etc), readding dead to original lists, granting gold/items
		$result = aftermath($attackers, $defenders, $dead, $winner, $fightType);
		$attackers = $result["attackers"];
		$defenders = $result["defenders"];
		unset($dead);
		unset($winner);
		unset($round);
		unset($round_time);
		
		
		return [
			"iterator" => $iterator,
			"attackers" => $attackers,
			"defenders" => $defenders,
		];
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
	
	function endRound($attackers, $defenders, $round, $round_time, $iterator)
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
			$iterator++; 
			//$tekst = generateTekst("round",
			$tekst = "";
			
			if($round != 1)
			{
				$tekst = $tekst . "<br>";
			}
			
			
			//Generating hidden fields for jquery
			$tekst = $tekst . "<span style='text-decoration: underline;'>Runda: $round</span><br><br>";
			echo "<div style='display: none;' class='$iterator' id='tekst$iterator'>" .$tekst. "</div>";

			
			foreach($attackers as $att)
			{
				$att->time_remaining = $round_time;
			}
			foreach($defenders as $def)
			{
				$def->time_remaining = $round_time;
			}
			
			unset($tekst);
		}
		
		
	
		return [
			"attackers" => $attackers,
			"defenders" => $defenders,
			"round" => $round,
			"iterator" => $iterator
		];
	}
	
	function meleeFight($attackers, $defenders, $dead, $iterator)
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
			//Checking if attacker isn't a mage
			if($fig->spells_only == false)
			{
				//Check if the attacker is still alive
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
					$result = hit($fig, $fighters[$i], $iterator);
					$fig = $result["attacker"];
					$fighters[$i] = $result["defender"];
					$iterator = $result["iterator"];
				
					//Checking if opponent survived
					if($fighters[$i]->hp <= 0)
					{
						array_push($dead, $fighters[$i]);
						unset($fighters[$i]);
					}
				}
			}
		}
		
		//Rearranging the arrays
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
			"dead" => $dead,
			"iterator" => $iterator
		];
	}
	
	function hit(Player $attacker, Player $defender, $iterator)
	{
		//Checking if attacker has enough movement
		if($attacker->time_remaining >= 1/$attacker->attackspeed)
		{
			$iterator++;
			$attacker->time_remaining -= 1/$attacker->attackspeed;
			$attacker->did_move = true;
			
			
			//Randomising base damage
			$dmg = rand($attacker->dmgmin, $attacker->dmgmax);
			//Adding basestats to damage
			if($attacker->equipment["lefthand"]->type == "melee")
			{
				$dmg = $dmg * ( ($attacker->sila + 100) / 100 );
			}
			else if($attacker->equipment["lefthand"]->type == "ranged")
			{
				$dmg = $dmg * ( ($attacker->celnosc + 100) / 100 );
			}
			//Accounting for armor
			if($defender->armor != 0)
			{
				$mitigation = ($defender->armor / ($defender->armor + (10*$dmg)));
				$dmg -= ($dmg * $mitigation);
			}
			
			
			$dmg = round($dmg);
			$defender->hp -= $dmg;
			if($defender->hp < 0)
			{
				$defender->hp = 0;
			}
			
			
			$tekst = $attacker->username . " uderza " . $defender->username . " za pomocą " . $attacker->equipment["lefthand"]->name . " zadając " . $dmg . " obrażeń!<br>";
			
			//Generating hidden fields for jquery
			echo "<div style='display: none;' class='$iterator' id='co$iterator'>hit</div>";
			echo "<div style='display: none;' class='$iterator' id='kogo$iterator'>" .$defender->id. "</div>";
			echo "<div style='display: none;' class='$iterator' id='pozostalo$iterator'>" .$defender->hp. "</div>";
			echo "<div style='display: none;' class='$iterator' id='max$iterator'>" .$defender->maxhp. "</div>";
			echo "<div style='display: none;' class='$iterator' id='tekst$iterator'>" .$tekst. "</div>";
			
			unset($dmg);
			unset($mitigation);
			unset($tekst);
		}
		else
		{
			$attacker->did_move = false;
		}
		
		return [
			"attacker" => $attacker,
			"defender" => $defender,
			"iterator" => $iterator
		];
	}

	
	
	
	function aftermath($attackers, $defenders, $dead, $winner, $fightType)
	{
		//Readding dead to original lists
		foreach($dead as $d)
		{
			if($d->side == "attacker")
			{
				array_push($attackers, $d);
			}
			else if($d->side == "defender")
			{
				array_push($defenders, $d);
			}
		}
		
		
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
		
		
		//Granting items
		if($fightType == "arena" or $fightType == "wyprawa")
		{
			if($winner == "attackers")
			{
				foreach($attackers as $att)
				{
					$item = generateItem(1);
					$att->addToBackpack($item);
					unset($item);
				}
			}
			else if($winner == "defenders")
			{
				foreach($defenders as $def)
				{
					$item = generateItem(1);
					$def->addToBackpack($item);
					unset($item);
				}
			}
		}
		
		
		return [
			"attackers" => $attackers,
			"defenders" => $defenders,
		];
	}

?>


<HTML>
<Head>
	<link rel="stylesheet" type="text/css" href="walka.css">
</Head>
</HTML>


<script>

	var iterator = <?php echo json_encode($iterator); ?>;
	
	iterate(0);
	
	function iterate(i)
	{
		var co = $("#co" + i).html();
		
		if(co == "hit")
		{
			var pozostalo = $("#pozostalo" + i).html();
			var max = $("#max" + i).html();
			var tekst = "HP: " + pozostalo + " / " + max;
			var kogo = $("#kogo" + i).html();
			var procent = pozostalo/max * 100;
			var kolor = color(pozostalo, max);
			
			$(".barText.HP" + kogo).html(tekst);
			$(".bar.HP" + kogo).find($(".innerBar")).css("width", procent + "%");
			$(".bar.HP" + kogo).find($(".innerBar")).css("background-color", kolor);
			
			var plik = Math.floor(Math.random() * 3) + 1;			
			var audio = new Audio('/sounds/hit' + plik + '.mp3');
			audio.play();
		}
		
		$("#tekst" + i).show();
		$("#combatWindow").animate({
			scrollTop: $("#combatWindow").get(0).scrollHeight}, 300);
		
		if(i < iterator)
		{
			i++;
			setTimeout(function(){
				iterate(i);
			}, 300);
		}
	}
	
	function color(current, max)
    {
        var percent = Math.round((current/max)*100);
        var green = Math.round((percent*255)/100);
        var red = 255-green;
        return "rgb(" + red + ", " + green + ", 00)";
    }
	
</script>