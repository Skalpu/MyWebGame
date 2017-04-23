<?php

	require_once('config.php');
    login_check();
	
	updateBuildings();
	
	if($_POST)
	{
		upgradeBuilding($_POST['budynek']);
	}
	
	drawVillage();
	
	function drawVillage()
	{
		foreach($_SESSION['player']->village as $building => $level)
		{
			$buildingArr = initializeBuilding($building);
			drawBuilding($buildingArr);
		}
	}

	function initializeBuilding($building)
	{
		$level = $_SESSION['player']->village[$building];
		
		//Initializing building details
		switch($building)
		{
			case 'goldmine': 
				$name = "Kopalnia złota"; 
				$goldCost = round(55 * pow(2, $level));
				$crystalCost = round(70 * pow(2, $level));
				$timeCost = round(30 * pow(2, $level));
				$currentBonus = ($level * 60) . " Złota/godzinę";
				$nextBonus = (($level+1) * 60) . " Złota/godzinę";
				$description = "Chociaż nigdy nie zapuszczałeś się do środka tego labiryntu, to mieszkając nieopodal przyrzekłbyś, że krasnoludzkie przyśpiewki i górnicze powiedzonka znasz lepiej niż twarze własnych rodzicieli. Kiedy tylko przedstawiciele tej rasy usłyszeli, że pod ziemią odnaleziona została żyła tego cennego surowca, całkowicie wyparowali z życia na powierzchni. Szczęście w nieszczęściu - praca tutaj nigdy nie ustaje, co potwierdza kolejny okrzyk sztygara: \"Fedrować, nie pierdolić!\"";
				break;
			case 'crystalmine': 
				$name = "Kopalnia kryształów"; 
				$goldCost = round(80 * pow(2, $level));
				$crystalCost = round(50 * pow(2, $level));
				$timeCost = round(40 * pow(2, $level));
				$currentBonus = ($level * 60) . " Kryształów/godzinę";
				$nextBonus = (($level+1) * 60) . " Kryształów/godzinę";
				$description = "Wydobycie i obróbka kryształu to nie lada zadanie. W kopalni tego kruszca zatrudniani są tylko najlepsi fachowcy, wykorzystywane są tylko najnowsze technologie, a całość otacza niemal mistyczna atmosfera profesjonalizmu. Popularne przysłowie głosi, że to właśnie w odbiciach kryształów, wychodzących spod rąk tutejszych czeladników, zobaczyć można prawdziwą magię.";
				break;
			case 'trader':
				$name = "Rynek";
				$goldCost = round(60 * pow(3, $level));
				$crystalCost = round(20 * pow(2, $level));
				$timeCost = round(30 * pow(2, $level));
				if($level == 0){
					$currentBonus = "Brak dostępu do handlarza";
				}
				else{
					$currentBonus = "Handlarz " . ($level) . " poziomu";
				}
				$nextBonus = "Handlarz " . ($level+1) . " poziomu";
				$description = "Jedzą, piją, lulki palą; Tańce, hulanka, swawola<br>Ledwie karczmy nie rozwalą, Cha cha, chi chi, hejza, hola!<br><br>Chociaż rynek niewątpliwie stanowi miejsce do (bardzo wesołych) spotkań międzyludzkich, to nie byłoby to możliwe bez jakże doświadczonych (i żądnych zysku) rzemieślników. Możnaby rzec: wolny rynek.";
				break;
			case 'magetower': 
				$name = "Wieża magów"; 
				$goldCost = round(40 * pow(2, $level));
				$crystalCost = round(90 * pow(2, $level));
				$timeCost = round(40 * pow(2, $level));
				if($level == 0){
					$currentBonus = "Brak dostępu do czarów";
				}
				else{
					$currentBonus = "Czary $level poziomu";
				}
				$nextBonus = "Czary " . ($level+1) . " poziomu";
				$description = "Podobno od rozmowy z magiem gorsza jest tylko rozmowa z wyedukowanym magiem. Pomimo tego, że studia w Wieży zdają się zmieniać młodych i obiecujących kandydatów w absolutnych gburów, to ich absolwentom nie można odmówić magicznych umiejętności. Założyciel tej akademii zwykł mawiać, że na świecie nie ma rzeczy niemożliwych, istnieją tylko niewymyślone.";
				break;
			case 'healing': 
				$name = "Chata znachorki"; 
				$goldCost = round(70 * pow(2, $level));
				$crystalCost = round(50 * pow(2, $level));
				$timeCost = round(50 * pow(2, $level));
				$currentBonus = ($level*30) . " HP/godzinę";
				$nextBonus = (($level+1)*30) . " HP/godzinę";
				$description = "Osoby, które przebyły leczenie u tej pozornie zwyczajnej staruszki, często mówią, że ludzie dzielą się na dwa rodzaje: tych, którzy się jej boją, i tych, którzy będą się jej bali. Przemawianie do rozbijanych akurat w moździerzu ingredientów to jedno z normalniejszych zachowań, jakich można tutaj uświadczyć. Mimo tego, usługi znachorki mają też swoje plusy: przynajmniej nie czeka się na nie w kolejce.";
				break;
			case 'manahealing':
				$name = "Starożytny ołtarz";
				$goldCost = round(50 * pow(2, $level));
				$crystalCost = round(60 * pow(2, $level));
				$timeCost = round(45 * pow(2, $level));
				$currentBonus = ($level*30) . " MP/godzinę";
				$nextBonus = (($level+1)*30) . " MP/godzinę";
				$description = "Nikt dokładnie nie wie, jak powstało to miejsce. Z historii zatartej przez pokolenia wynika tylko, że od zawsze było kojarzone z wieloma bóstwami, a przez niektórych nawet czczone. Spacerując w pobliżu czujesz, jak napełnia cię siła duchowa.";
				break;
			default: break;
		}
		
		//Photo overlay
		if($level == 0){
			$photoClass = "notBuilt";
		}else{
			$photoClass = "";
		}
		
		return [
			'building' => $building,
			'level' => $level,
			'name' => $name,
			'photoClass' => $photoClass,
			'goldCost' => $goldCost,
			'crystalCost' => $crystalCost,
			'timeCost' => $timeCost,
			'description' => $description,
			'currentBonus' => $currentBonus,
			'nextBonus' => $nextBonus
		];
	}
	
	function drawBuilding($buildingArr)
	{
		//Main div of each building
		echo "<div class='building' id='" .$buildingArr['building']. "'>";
			drawBuildingPhoto($buildingArr);
			drawBuildingDescription($buildingArr);
		echo "</div>";
	}
	
	function drawBuildingPhoto($buildingArr)
	{
		//Container of the photo (sets dimensions)
		echo "<div class='buildingFoto'>";
			//Photo of the building
			echo "<div class='fotoContainer' id='" .$buildingArr['building']. "Foto'>";
				
				//Name of the building
				echo "<div class='buildingName noselect' id='" .$buildingArr['building']. "Name'>" .$buildingArr['name']. "</div>";
						
				//Level of the building (none/level)
				if($buildingArr['level'] != 0){
					echo "<div class='buildingLevel noselect' id='" .$buildingArr['building']. "Level'>";
						echo "Poziom " . $buildingArr['level'];
					echo "</div>";
				}
						
				//Upgrade button (build/upgrade)
				if($_SESSION['player']->zloto >= $buildingArr['goldCost'] and $_SESSION['player']->krysztaly >= $buildingArr['crystalCost'] and $_SESSION['player']->building == null){
					$class = "enoughCost";
				}else if($_SESSION['player']->building == $buildingArr['building']){
					$class = "";
				}else{
					$class = "notEnoughCost";
 				}
				
				echo "<div class='buildingButton $class noselect arrow' id='" .$buildingArr['building']. "Button'>";
					if($buildingArr['level'] == 0){
						echo "Wybuduj";
					}
					else{
						echo "Rozbuduj";
					}
				//End of upgrade button
				echo "</div>";
				
				//Photo overlay
				echo "<div class='buildingOver " . $buildingArr['photoClass'] . "' id='" .$buildingArr['building']. "Over'></div>";			
			
			//End of photo
			echo "</div>";
		//End of photo container
		echo "</div>";
	}
	
	function drawBuildingDescription($buildingArr)
	{
		//Container of the description
		echo "<div class='buildingDescription' id='" .$buildingArr['building']. "Description'>";
			drawDescriptionContent($buildingArr);
			drawDescriptionFooter($buildingArr);
		echo "</div>";
	}
	
	function drawDescriptionContent($buildingArr)
	{
		//Content container
		echo "<div class='descContent'>";
			//Name of the building
			echo "<div class='descTitle'>" .$buildingArr['name']. "</div>";
			//Subtitle (level/not built)
			echo "<div class='descSubtitle'>";
				if($buildingArr['level'] == 0){
					echo "(Nie wybudowano)";
				}else{
					echo "Poziom " . $buildingArr['level'];
				}
			//End of subtitle
			echo "</div>";
			//Divider
			echo "<div class='divider'></div>";
			//Description text
			echo "<div class='descText'>" . $buildingArr['description'] . "</div>";	
		//End of content container
		echo "</div>";
	}
	
	function drawDescriptionFooter($buildingArr)
	{				
		//Formatting time
		if($buildingArr['timeCost'] < 60){
			$format = "s";
		} else if($buildingArr['timeCost'] >= 60 and $buildingArr['timeCost'] < 3600){
			$format = "i:s";
		} else if($buildingArr['timeCost'] >= 3600){
			$format = "H:i:s";
		}
		
		$buildingArr['timeCost'] = gmdate($format, $buildingArr['timeCost']);
		if($format == "s"){
			$buildingArr['timeCost'] = $buildingArr['timeCost'] . "s";
		}
		
		
		//Footer container
		echo "<div class='descFooter'>";		
			//Building costs
			echo "<div class='buildingCosts'>";
				
				//Gold cost
				echo "<div class='cost goldCost'>";
					echo "<div class='goldIcon'></div>";
					if($buildingArr['goldCost'] <= $_SESSION['player']->zloto){
						$textClass = "enoughCost";
					}else{
						$textClass = "notEnoughCost";
					}
					echo "<div class='goldText $textClass'>" . $buildingArr['goldCost'] . "</div>";
				echo "</div>";
				
				//Crystal cost
				echo "<div class='cost crystalCost'>";
					echo "<div class='crystalIcon'></div>";
					if($buildingArr['crystalCost'] <= $_SESSION['player']->krysztaly){
						$textClass = "enoughCost";
					}else{
						$textClass = "notEnoughCost";
					}
					echo "<div class='crystalText $textClass'>" . $buildingArr['crystalCost'] . "</div>";
				echo "</div>";
				
				//Time cost
				echo "<div class='cost timeCost'>";
					echo "<div class='timeIcon'></div>";
					echo "<div class='timeText'>" . $buildingArr['timeCost'] . "</div>";
				echo "</div>";
			
			echo "</div>";
			
			//Building bonuses
			echo "<div class='buildingBonuses'>";
				
				//Current bonus
				echo "<div class='bonus'>";
					echo "<div class='bonusLabel'>&raquo;</div> "; 
					echo $buildingArr['currentBonus'];
				echo "</div>";
				//Next bonus
				echo "<div class='bonus'>";
					echo "<div class='bonusLabel'>+1 &raquo;</div> "; 
					echo $buildingArr['nextBonus'];
				echo "</div>";
				
			echo "</div>";
		echo "</div>";
	}
	
	function upgradeBuilding($building)
	{
		$arrBuilding = initializeBuilding($building);
		
		//Checking if player has sufficient resources
		if($_SESSION['player']->zloto >= $arrBuilding['goldCost'] and $_SESSION['player']->krysztaly >= $arrBuilding['crystalCost'])
		{
			//Checking if player isn't already building sth
			if($_SESSION['player']->building == NULL)
			{
				//Setting variables
				$now = time();
				$id = $_SESSION['player']->id;
				$start = $now;
				$until = $now + $arrBuilding['timeCost'];
				$untilDate = date("Y-m-d H:i:s", $until);
				$newGold = $_SESSION['player']->zloto - $arrBuilding['goldCost'];
				$newCrystal = $_SESSION['player']->krysztaly - $arrBuilding['crystalCost'];
				
				//Local updates
				$_SESSION['player']->building = $building;
				$_SESSION['player']->building_started = $start;
				$_SESSION['player']->building_until = $until;
				$_SESSION['player']->zloto = $newGold;
				$_SESSION['player']->krysztaly = $newCrystal;
				
				//DB updates
				$conn = connectDB();
				$conn->query("UPDATE users SET building='$building', building_started='$start', building_until='$untilDate', zloto='$newGold', krysztaly='$newCrystal' WHERE id='$id'");
				$conn->close();
				
				//Unsetting variables
				unset($now);
				unset($id);
				unset($start);
				unset($until);
				unset($untilDate);
				unset($newGold);
				unset($newCrystal);
				unset($conn);
			}
		}
		
		unset($arrBuilding);
	}
	
	function updateBuildings()
	{
		if(isset($_SESSION['player']->building) and $_SESSION['player']->building != NULL)
		{
			$now = time();
		
			if($now >= $_SESSION['player']->building_until)
			{
				//Setting variables
				$id = $_SESSION['player']->id;
				$building = $_SESSION['player']->building;
				$newLevel = $_SESSION['player']->village[$building] + 1;
					
				//Updating locally
				$_SESSION['player']->village[$building] = $newLevel;
				$_SESSION['player']->building = NULL;
				$_SESSION['player']->building_until = NULL;
			
				//Updating to DB
				$conn = connectDB();
				$conn->query("UPDATE villages SET $building=$newLevel WHERE id=$id");
				$conn->query("UPDATE users SET building=NULL, building_until=NULL WHERE id=$id");
				$conn->close();
			
				//Unsetting variables
				unset($id);
				unset($building);
				unset($newLevel);
				unset($conn);
			}
		
			unset($now);
		}
	}

?>

<script>

	$("#divPlayerBars").load('update_player_bars.php');
	initializeButtons();
	initializeCountdown();
	
	function initializeButtons()
	{
		$(".buildingButton").click(function(){
			var budynek = $(this).parent().parent().parent().attr('id');
			$("#divMainOkno").load('update_wioska.php', {budynek: budynek});
		});
	}
	function initializeCountdown()
	{
		var building = <?php echo json_encode($_SESSION['player']->building); ?>;
		
		if(building != null)
		{
			var building_until = <?php echo json_encode(date("Y-m-d H:i:s", $_SESSION['player']->building_until)); ?>;
			var building_started_seconds = <?php echo json_encode($_SESSION['player']->building_started); ?>;
			var building_until_seconds = <?php echo json_encode($_SESSION['player']->building_until); ?>;
			var buildingFoto = "#" + building + "Foto";
		
			$(buildingFoto).append("<div id='divRemainingTime'></div>");
		
			$("#divRemainingTime").countdown(building_until, function(event) {
				$(this).html(event.strftime('%H:%M:%S'))
			}).on('finish.countdown', function(event) {
				//Reload village when countdown finishes
				$("#divMainOkno").load('update_wioska.php');
			});
			
			darkenBuildings(building);
			animateBuilding(building, building_started_seconds, building_until_seconds);
		}
	}
	function animateBuilding(building, building_started_seconds, building_until_seconds)
	{
		var buildingOver = "#" + building + "Over";
		var now = new Date().getTime() / 1000;
		var total = building_until_seconds - building_started_seconds;
		var elapsed = now - building_started_seconds; 
		var procentComplete = Math.floor(elapsed/total * 100);
		
		var procentComplete = procentComplete -3;
		if(procentComplete < 0){
			procentComplete = 0;
		}
		
		var procent2 = procentComplete + 3;
		if(procent2 > 100){
			procent2 = 100;
		}
		
		var procentComplete = procentComplete.toString();
		var procent2 = procent2.toString();
		
		var myCss = "linear-gradient(45deg, rgba(0,0,0,0.0), rgba(0,0,0,0.0) " + procentComplete + "%, rgba(0,0,0,0.9) " + procent2 + "%)";
		$(buildingOver).css("background", myCss); 
		
		if(now < building_until_seconds){
			setTimeout(function(){
				animateBuilding(building, building_started_seconds, building_until_seconds);
			}, 100);
		}
	}
	function darkenBuildings(building)
	{
		var buildingOver = "#" + building + "Over";
		$(".buildingOver:not(" + buildingOver + ")").css("background", "rgba(255, 0, 0, 0.3)");
	}
	
</script>