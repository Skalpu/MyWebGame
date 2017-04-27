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
				$element = $GLOBALS['combatSpells'][$player->combatSpells[$i]]->element;
				$spellName = $GLOBALS['combatSpells'][$player->combatSpells[$i]]->name;
				$buttonText = "<div class='icon $element'></div><div class='spellName'>$spellName</div> #" . ($i+1);
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
		//Setting variables
		if(strpos($category, "preparation") !== false){
			$list = "preparationSpells";
			$dbField = "preparation";
		}
		else{
			$list = "combatSpells";
			$dbField = "combat";
		}
		
		preg_match('/(\d+)/', $slotID, $matches);
		$id = $matches[1];
		$dbField = $dbField . $id;
		$playerID = $player->id;
		
		//Local updates
		$player->{$list}[$id] = $spellID;
		
		//DB updates
		$conn = connectDB();
		$conn->query("UPDATE spellbooks SET $dbField=$spellID WHERE id=$playerID");
		$conn->close();
		
		//Unsetting variables
		unset($list);
		unset($dbField);
		unset($id);
		unset($playerID);
		unset($conn);
	}

?>

<script>

	$("#divPlayerBars").load('update_player_bars.php');
	
	var preparationH = $("#preparationContent0").css("height");
	var combatH = $("#combatContent0").css("height");
	$(".dropdownContent").css("height", "0px");
	
	initializeDropdowns(preparationH, combatH);
	initializeHover();
	
	function initializeDropdowns(preparationH, combatH)
	{
		//Button was clicked
		$(".dropdownButton").click(function(e){
			//Checking if dropdown is currently shown or hidden
			var current = $(this).parent().find(".dropdownContent").css("height");
			var type = $(this).parent().find(".dropdownContent").attr("id");
			
			//Show or hide accordingly
			if(current	== "0px"){
				$(".dropdownContent").animate({
					height: "0px",
				}, 150);

				if(~type.indexOf("preparation")){
					var next = preparationH;
				}
				else if(~type.indexOf("combat")){
					var next = combatH;
				}
			}
			else{
				var next = "0px"; 
			}
		
			$(this).parent().find(".dropdownContent").animate({
				height: next,
			}, 200);

			e.stopPropagation();
		});

		//Option was selected
		$(".dropdownOption").click(function(e){
			//Updating locally
			var type = $(this).parent().attr("id");
			var id = type.match(/\d+/);
			id = parseInt(id);
			id += 1;
			var spellName = $(this).html();
			var newName = spellName + " #" + id;
			$(this).parent().parent().find('.dropdownButton').html(newName);
			
			//Updating to PHP
			var category = $(this).parent().attr('id');
			var slotID = $(this).parent().attr('id');
			var spellID = $(this).find('.spellID').html();
			$.post('update_magic.php', {category: category, slotID: slotID, spellID: spellID});
			
			//Hiding the dropdown
			$(this).parent().animate({
				height: "0px",
			}, 200);
			e.stopPropagation();
		});

		//Someone clicked outside
		$(document).click(function(){
			$(".dropdownContent").animate({
				height: "0px",
			}, 200);
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