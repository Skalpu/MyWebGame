<?php

	require_once('config.php');
	require_once('spells.php');
	login_check();
	
	drawMagicDropdowns($_SESSION['player']);
	drawMagicDisplay();
	
	function drawMagicDropdowns(Player $player)
	{
		$preparationAmount = calculatePreparationAmount();
		$combatAmount = calculateCombatAmount();
		
		//Drawing main container
		echo "<div id='divPreparation'>";
		//Drawing title
		echo "<div id='preparationTitle' class='noselect'>MAGIA PRZYGOTOWAWCZA</div>";
		//Drawing dropdowns
		for($i = 0; $i < $preparationAmount; $i++)
		{
			//Preparing button text
			if($player->preparationMagic[$i] != ""){
				$buttonText = $player->preparationMagic[$i]->name;
			}
			else{
				$buttonText = "Czar przygotowawczy #" . ($i+1);
			}
			
			//Drawing dropdown
			echo "<div class='dropdown'>";
			
				//Drawing dropdown button
				echo "<div class='dropdownButton orange arrow noselect'>$buttonText</div>";
				unset($buttonText);
				
					//Drawing dropdown content
					echo "<div id='preparationContent$i' class='dropdownContent'>";					
					//Drawing spells
					foreach ($GLOBALS['preparationSpells'] as $key => $spell)
					{
						//Checking if player has access to the spell
						if($player->village['magetower'] >= $spell->level)
						{
							//Checking if this isn't the last dropdown
							if($key == (count($GLOBALS['preparationSpells']) - 1)){
								$class = "dropdownLast";
							}
							else{
								$class = "";
							}
							//Checking the element of the spell
							$element = $spell->element;
							
							echo "<div class='dropdownOption $class whiteGradient arrow noselect'><div class='icon $element'></div><div class='spellName'>" . $spell->name . "</div></div>";
							unset($class);
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
	
	function drawMagicDisplay()
	{
		
	}
	
	function calculatePreparationAmount()
	{
		//TODO wpływ rasy i klasy na ilość
		$amount = 5; //$_SESSION['player']->village['magetower'];
		return $amount;
	}
	
	function calculateCombatAmount()
	{
		//TODO wpływ rasy i klasy na ilość
		$amount = 5; //$_SESSION['player']->village['magetower'];
		return $amount;
	}

?>

<script>

	$("#divPlayerBars").load('update_player_bars.php');
	initializeDropdowns();
	
	function initializeDropdowns()
	{
		//Button was clicked
		$(".dropdownButton").click(function(e){
			//Checking if dropdown is currently shown or hidden
			var current = $(this).parent().find(".dropdownContent").css("display");
		
			//Show or hide accordingly
			if(current == "none"){
				var next = "block";
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
			$(this).parent().hide();
			e.stopPropagation();
		});

		//Someone clicked outside
		$(document).click(function(){
			$(".dropdownContent").hide();
		});
	}

</script>