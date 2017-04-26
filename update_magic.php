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
		//TODO wpływ rasy, klasy na poziom magii
		$magicLevel = $player->village['magetower'];
		
		
		//Drawing main container
		echo "<div id='divPreparation'>";
		
		//Drawing dropdowns
		for($i = 0; $i < $preparationAmount; $i++)
		{
			//Preparing button text
			if($player->preparationMagic[$i] != ""){
				$buttonText = $player->preparationMagic[$i]->name;
			}
			else{
				$buttonText = "Czar przygotowawczy " . ($i+1);
			}
			
			//Drawing dropdown
			echo "<div class='dropdown'>";
				//Drawing dropdown button
				echo "<button onclick='showDropdown()' class='dropdownButton orange arrow'>$buttonText</button>";
				//Drawing dropdown content
				echo "<div id='preparationDropdown$i' class='dropdownContent'>";
					foreach ($GLOBALS['preparationSpells'] as $key => $spell)
					{
						if($magicLevel >= $spell->level)
						{
							if($key == (count($GLOBALS['preparationSpells']) - 1)){
								$class = "dropdownLast";
							}
							else{
								$class = "";
							}
							
							echo "<div class='dropdownOption $class whiteGradient arrow'>" . $spell->name . "</div>";
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
		$amount = $_SESSION['player']->village['magetower'];
		return $amount;
	}
	
	function calculateCombatAmount()
	{
		//TODO wpływ rasy i klasy na ilość
		$amount = $_SESSION['player']->village['magetower'];
		return $amount;
	}

?>

<script>

	$("#divPlayerBars").load('update_player_bars.php');
	initializeDropdowns();
	
	function initializeDropdowns()
	{
		//Show dropdown on button click
		$(".dropdownButton").click(function() {
			var current = $(this).parent().find(".dropdownContent").css("display");
			
			if(current == "none"){
				var next = "block";
			}
			else{
				var next = "none";
			}
			
			$(this).parent().find(".dropdownContent").css("display", next);
		});
		
		//Hide dropdown on click outside
		$(document).mouseup(function (e){
			var container = $(".dropdownContent");
			
			if(!container.is(e.target) && container.has(e.target).length === 0){
				container.hide();
			}
		});
	}
	

</script>