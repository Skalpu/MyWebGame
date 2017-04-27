<?php

	require_once('config.php');
	require_once('spells.php');
	login_check();
	
	drawDropdowns($_SESSION['player']);
	drawSpellDisplay();
	
	if($_POST)
	{
		updateMagic($_POST['category'], $_POST['slotID'], $_POST['spellID'], $_SESSION['player']);
	}
	
	function drawDropdowns(Player $player)
	{
		$preparationAmount = calculatePreparationAmount();
		$combatAmount = calculateCombatAmount();
		
		drawPreparationDropdowns($preparationAmount, $player);
		drawCombatDropdowns($combatAmount, $player);
	}
	
	function calculatePreparationAmount()
	{
		//TODO wpływ rasy i klasy na ilość
		$amount = 5;
		return $amount;
	}
	
	function calculateCombatAmount()
	{
		//TODO wpływ rasy i klasy na ilość
		$amount = 5;
		return $amount;
	}
	
	function drawPreparationDropdowns($amount, Player $player)
	{
		//Drawing main container
		echo "<div id='divPreparation'>";
		//Drawing title
		echo "<div id='preparationTitle' class='asText centerText whiteText blackShadow noselect'>MAGIA PRZYGOTOWAWCZA</div>";
		//Drawing dropdowns
		for($i = 0; $i < $amount; $i++)
		{
			//Preparing button text
			if($player->preparationSpells[$i] != ""){
				$element = $GLOBALS['preparationSpells'][$player->preparationSpells[$i]]->element;
				$spellName = $GLOBALS['preparationSpells'][$player->preparationSpells[$i]]->name;
				$buttonText = "<div class='icon $element'></div><div class='spellName'>$spellName</div> #" . ($i+1);
			}
			else{
				$buttonText = "Czar przygotowawczy #" . ($i+1);
			}
			
			//Drawing dropdown
			echo "<div class='dropdown'>";
				//Drawing dropdown button
				echo "<div class='dropdownButton asText bold orange arrow noselect'>$buttonText</div>";
				unset($buttonText);
				//Drawing dropdown content
				echo "<div id='preparationContent$i' class='scrollable dropdownContent asText centerText'>";					
				//Drawing spells
				foreach ($GLOBALS['preparationSpells'] as $key => $spell)
				{
					//Checking if player has access to the spell
					if($player->village['magetower'] >= $spell->level)
					{
						//Checking the element of the spell
						$element = $spell->element;
						
						//Drawing dropdown option
						echo "<div class='dropdownOption whiteGradientHover arrow noselect'>";
							echo "<div class='icon $element'></div>";
							echo "<div class='spellName'>" . $spell->name . "</div>";
							echo "<div class='spellFlavor' style='display:none'>" .$spell->flavor. "</div>";
							echo "<div class='spellDescription' style='display:none'>" .$spell->description. "</div>";
							echo "<div class='spellID' style='display:none'>" .$key. "</div>";
						echo "</div>";
						unset($element);
					}
				}
				//End of dropdown content
				echo "</div>";
			//End of dropdown
			echo "</div>";
		}
		//End of main container
		echo "</div>";
	}
	
	function drawCombatDropdowns($amount, Player $player)
	{
		//Drawing main container
		echo "<div id='divCombat'>";
		//Drawing title
		echo "<div id='combatTitle' class='asText centerText whiteText blackShadow noselect'>MAGIA BITEWNA</div>";
		//Drawing dropdowns
		for($i = 0; $i < $amount; $i++)
		{
			//Preparing button text
			if($player->combatSpells[$i] != ""){
				$buttonText = "#" . ($i+1) . $player->combatSpells[$i];
			}
			else{
				$buttonText = "Czar bitewny #" . ($i+1);
			}
			
			//Drawing dropdown
			echo "<div class='dropdown'>";
				//Drawing dropdown button
				echo "<div class='dropdownButton asText bold orange arrow noselect'>$buttonText</div>";
				unset($buttonText);
				//Drawing dropdown content
				echo "<div id='combatContent$i' class='scrollable dropdownContent asText centerText'>";
				//Drawing spells
				foreach ($GLOBALS['combatSpells'] as $key => $spell)
				{
					//Checking if player has access to the spell
					if($player->village['magetower'] >= $spell->level)
					{
						//Checking the element of the spell
						$element = $spell->element;
							
						//Drawing dropdown option
						echo "<div class='dropdownOption whiteGradientHover arrow noselect'>";
							echo "<div class='icon $element'></div>";
							echo "<div class='spellName'>" . $spell->name . "</div>";
							echo "<div class='spellFlavor' style='display:none'>" .$spell->flavor. "</div>";
							echo "<div class='spellDescription' style='display:none'>" .$spell->description. "</div>";
						echo "</div>";
						unset($element);
					}
				}
				//End of dropdown content
				echo "</div>";
			//End of dropdown
			echo "</div>";
		}
		//End of main container
		echo "</div>";
	}
	
	function drawSpellDisplay()
	{
		//Main container
		echo "<div id='divSpell'>";
			//Spell title
			echo "<div id='spellTitle' class='bold fullWidth whiteGradient whiteShadow paddingVertical blackBorder centerText borderBox'>Kamienna skóra</div>";
			//Spell photo container
			echo "<div id='spellPhoto' class='fullWidth blackBorder borderBox'></div>";
			//Spell footer
			echo "<div id='spellFooter' class='fullWidth blackBorder whiteGradient paddingFull borderBox scrollable'>";
				//Spell flavor
				echo "<div id='spellFlavor' class='italic centerText'>Czerwony jak cegła...<br>I tak twardy też!</div>";
				//Divider
				echo "<div class='divider'></div>";
				//Spell description
				echo "<div id='spellDescription' class='centerText'>Twoja skóra pokrywa się skamieliną. Zyskujesz zwiększony pancerz.</div>";
			//End of spell footer
			echo "</div>";
		//End of main container
		echo "</div>";
	}
	
	function updateMagic($category, $slotID, $spellID, Player $player)
	{
		if(strpos($category, "preparation") !== false){
			$list = "preparationSpells";
		}
		else{
			$list = "combatSpells";
		}
		
		preg_match('/(\d+)/', $slotID, $matches);
		$id = $matches[1];
		
		$player->{$list}[$id] = $spellID;
	}

?>

<script>

	$("#divPlayerBars").load('update_player_bars.php');
	initializeDropdowns();
	initializeHover();
	
	function initializeDropdowns()
	{
		//Button was clicked
		$(".dropdownButton").click(function(e){
			//Checking if dropdown is currently shown or hidden
			var current = $(this).parent().find(".dropdownContent").css("display");

			//Show or hide accordingly
			if(current == "none"){
				var next = "inline-block";
				$(".dropdownContent").css("display", "none");
			}
			else{
				var next = "none";
			}

			$(this).parent().find(".dropdownContent").css("display", next);
			e.stopPropagation();
		});

		//Option was selected
		$(".dropdownOption").click(function(e){
			//Making spell name display on the button
			$(this).parent().parent().find('.dropdownButton').html($(this).html());
			//Sending update to php
			var category = $(this).parent().attr('id');
			var slotID = $(this).parent().attr('id');
			var spellID = $(this).find('.spellID').html();
			$.post('update_magic.php', {category: category, slotID: slotID, spellID: spellID});
			//Hiding the dropdown
			$(this).parent().hide();
			e.stopPropagation();
		});

		//Someone clicked outside
		$(document).click(function(){
			$(".dropdownContent").hide();
		});
	}
	function initializeHover()
	{
		$(".dropdownOption").hover(
			function(){
				//Getting required variables
				var spellName = $(this).find('.spellName').html();
				var spellFlavor = $(this).find('.spellFlavor').html();
				var spellDescription = $(this).find('.spellDescription').html();
				var spellImage = "url('gfx/spells/" + spellName + ".jpg')";
				
				//Setting
				$("#spellTitle").html(spellName);
				$("#spellFlavor").html(spellFlavor);
				$("#spellDescription").html(spellDescription);
				$("#spellPhoto").css("background-image", spellImage);
			}
		);
	}
	

</script>