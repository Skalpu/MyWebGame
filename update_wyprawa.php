<?php

	require_once('config.php');
    login_check();

	
	drawJourneys();
	
	function drawJourneys()
	{
		$journeys = ['forest','lake','darkforest'];
		
		foreach ($journeys as $journey)
		{
			$journeyArr = initializeJourney($journey);
			drawJourney($journeyArr);
		}
	}
	
	function initializeJourney($journey)
	{
		switch($journey)
		{
			case 'forest':
				$location = "Las";
				$name = "Zlecenie: patrol okolicy";
				$timeCost = 60;
				$description = "";
				break;
			case 'lake':
				$location = "Jezioro";
				$name = "Badanie: dno jeziora";
				$timeCost = 90;
				$description = "";
				break;
			case 'darkforest':
				$location = "Mroczny las";
				$name = "Zlecenie: nocne mary";
				$timeCost = 120;
				$description = "";
				break;
			default: break;
		}
		
		$monsters = initializeMonsters($journey);
		
		return [
			'journey' => $journey,
			'location' => $location,
			'name' => $name,
			'monsters' => $monsters,
			'description' => $description,
			'timeCost' => $timeCost,
			'photoClass' => "",
		];
	}
	
	function initializeMonsters($journey)
	{
		$monstersArr = [];
		
		switch($journey)
		{
			//($name, $stats, $kondycja, $attackName, $attackType, $dmgmin, $dmgmax, $attackspeed, $critchance, $armor, $zloto, $krysztaly, $experience);
			
			case 'forest':
				array_push($monstersArr, Player::asMonster("Jeleń", 8, 5, "Poroża", "melee", 3, 5, 0.8, 5.0, 0, 50, 10, 5));
				array_push($monstersArr, Player::asMonster("Sowa", 5, 3, "Pikowania", "melee", 1, 3, 1.0, 3.0, 0, 30, 0, 3));
				array_push($monstersArr, Player::asMonster("Robak", 5, 2, "Podkopu", "melee", 2, 3, 0.7, 3.0, 0, 25, 0, 3));
				break;
			default: 
				break;
		}
		
		return $monstersArr;
	}
	
	function drawJourney($journeyArr)
	{
		//Main div of each journey
		echo "<div class='journey' id='" .$journeyArr['journey']. "'>";
			drawJourneyPhoto($journeyArr);
			drawJourneyDescription($journeyArr);
		echo "</div>";
	}
	
	function drawJourneyPhoto($journeyArr)
	{
		//Container of the photo (sets dimensions)
		echo "<div class='journeyFoto'>";
			//Photo of the journey
			echo "<div class='fotoContainer' id='" .$journeyArr['journey']. "Foto'>";
				
				//Name of the journey
				echo "<div class='journeyLocation noselect' id='" .$journeyArr['journey']. "Name'>" .$journeyArr['location']. "</div>";
	
				//Travel button
				if($_SESSION['player']->journey == $journeyArr['journey'] or $_SESSION['player']->journey == null){
					$class = "";
				}else{
					$class = "inTravel";
 				}
				
				echo "<div class='journeyButton $class noselect arrow' id='" .$journeyArr['journey']. "Button'>";
					echo "Wyrusz";
				//End of upgrade button
				echo "</div>";
				
				//Photo overlay
				echo "<div class='journeyOver " . $journeyArr['photoClass'] . "' id='" .$journeyArr['journey']. "Over'></div>";			
			
			//End of photo
			echo "</div>";
		//End of photo container
		echo "</div>";
	}

	function drawJourneyDescription($journeyArr)
	{
		//Container of the description
		echo "<div class='journeyDescription' id='" .$journeyArr['journey']. "Description'>";
			drawDescriptionContent($journeyArr);
			drawDescriptionFooter($journeyArr);
		echo "</div>";
	}
	
	function drawDescriptionContent($journeyArr)
	{
		//Content container
		echo "<div class='descContent'>";
			//Name of the building
			echo "<div class='descTitle'>" .$journeyArr['name']. "</div>";
			//Divider
			echo "<div class='divider'></div>";
			//Description text
			echo "<div class='descText'>" . $journeyArr['description'] . "</div>";	
		//End of content container
		echo "</div>";
	}
	
	function drawDescriptionFooter($journeyArr)
	{
		//Formatting time
		if($journeyArr['timeCost'] < 60){
			$format = "s";
		} else if($journeyArr['timeCost'] >= 60 and $journeyArr['timeCost'] < 3600){
			$format = "i:s";
		} else if($journeyArr['timeCost'] >= 3600){
			$format = "H:i:s";
		}
		
		$journeyArr['timeCost'] = gmdate($format, $journeyArr['timeCost']);
		if($format == "s"){
			$journeyArr['timeCost'] = $journeyArr['timeCost'] . "s";
		}
		
		
		//Footer container
		echo "<div class='descFooter'>";		
			//Journey costs
			echo "<div class='journeyCosts'>";

				//Time cost
				echo "<div class='cost timeCost'>";
					echo "<div class='timeIcon'></div>";
					echo "<div class='timeText'>" . $journeyArr['timeCost'] . "</div>";
				echo "</div>";
			
			//End of journey costs
			echo "</div>";
			
			//Journey monsters
			echo "<div class='journeyMonsters'>";
				
				foreach($journeyArr['monsters'] as $monster)
				{
					echo "<div class='monsterFoto'>";
						$monster->drawFoto("monster");
					echo "</div>";
				}
				
			//End of journey monsters
			echo "</div>";
		//End of footer
		echo "</div>";
	}
?>