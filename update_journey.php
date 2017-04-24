<?php

	require_once('config.php');
    login_check();

	if($_POST)
	{
		sendJourney($_POST['journey']);
	}
	
	drawJourneys();
	
	function sendJourney($journey)
	{
		$arrJourney = initializeJourney($journey);
		
		//Checking if player is currently doing something
		if($_SESSION['player']->journey == null)
		{
			//Setting variables
			$id = $_SESSION['player']->id;
			$start = time();
			$until = $start + $arrJourney['timeCost'];
			$startDate = date("Y-m-d H:i:s", $start);
			$untilDate = date("Y-m-d H:i:s", $until);
				
			//Local updates
			$_SESSION['player']->journey = $journey;
			$_SESSION['player']->journey_started = $start;
			$_SESSION['player']->journey_until = $until;

			//DB updates
			$conn = connectDB();
			$conn->query("UPDATE users SET journey='$journey', journey_started='$startDate', journey_until='$untilDate' WHERE id='$id'");
			$conn->close();
				
			//Unsetting variables
			unset($start);
			unset($until);
			unset($startDate);
			unset($untilDate);
			unset($conn);
		}
	}
	
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
				$name = "Zlecenie: polowanie";
				$timeCost = 60;
				$description = "W wiosce z której pochodzisz zachorował myśliwy. Ktoś musi szybko go zastąpić, bo kończą się zapasy mięsa. Udajesz się do lasu na proste polowanie. Kto wie, co tam znajdziesz.<br><br>Wyprawa odpowiednia dla początkujących graczy.";
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
			//($name, $stats, $kondycja, $attackName, $attackType, $dmgmin, $dmgmax, $attackspeed, $critchance, $armor, $magicdefense, $zloto, $krysztaly, $experience);
			case 'forest':
				array_push($monstersArr, Player::asMonster("Jeleń", 8, 4, "Poroża", "melee", 3, 5, 0.8, 5.0, 0, 0, 50, 10, 5));
				array_push($monstersArr, Player::asMonster("Sowa", 5, 3, "Pikowania", "melee", 2, 3, 1.0, 3.0, 0, 0, 30, 0, 3));
				array_push($monstersArr, Player::asMonster("Robak", 5, 2, "Podkopu", "melee", 1, 2, 0.7, 3.0, 0, 0, 25, 0, 3));
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
					echo "<div class='monsterFoto arrow noselect'>";
						$monster->drawFoto();
						drawHover($monster);
					echo "</div>";
				}
				
			//End of journey monsters
			echo "</div>";
		//End of footer
		echo "</div>";
	}
	
	function drawHover(Player $monster)
	{
		echo "<div class='monsterHover'>";
			echo "<div class='monsterName'>" . $monster->username . "</div>";
			echo "<div class='divider'></div>";
			echo "<div class='stats1 centerText'>";
				echo "<div class='icon' id='HPIcon'></div>";
				echo "<div class='statText monsterHP'>" . $monster->maxhp . "</div>";
				echo "<div class='icon' id='armorIcon'></div>";
				echo "<div class='statText monsterArmor'>" . $monster->armor . "</div>";
				echo "<div class='icon' id='magicDefenseIcon'></div>";
				echo "<div class='lastText monsterMagicDefense'>" . $monster->magicdefense . "</div>";
			echo "</div>";
			echo "<div class='clearLeft centerText'>";
				echo "<div class='icon' id='attackIcon'></div>";
				echo "<div class='lastText monsterAttack'>" . $monster->dmgmin . "-" . $monster->dmgmax . "</div>";
			echo "</div>";
			
		echo "</div>";
	}
	
?>

<script>
	
	$("#divPlayerBars").load('update_player_bars.php');
	initializeButtons();
	initializeHover();
	initializeCountdown();
	
	function initializeButtons()
	{
		$(".journeyButton").click(function(){
			var journey = $(this).parent().parent().parent().attr('id');
			$("#divMainOkno").load('update_journey.php', {journey: journey});
		});
	}
	function initializeHover()
	{
		$(".monsterFoto").hover(
			function(){
				$(this).find('.monsterHover').show();
			},
			function(){
				$(this).find('.monsterHover').hide();
			}
		);
		
		$(".monsterFoto").bind('mousemove', function(e){
			var top = e.pageY + 15;
			var left = e.pageX + 8;
			$(this).find('.monsterHover').css({'top': top, 'left': left});
		});
	}
	function initializeCountdown()
	{
		var journey = <?php echo json_encode($_SESSION['player']->journey); ?>;
		
		if(journey != null)
		{
			var journey_until = <?php echo json_encode(date("Y-m-d H:i:s", $_SESSION['player']->journey_until)); ?>;
			var journey_started_seconds = <?php echo json_encode($_SESSION['player']->journey_started); ?>;
			var journey_until_seconds = <?php echo json_encode($_SESSION['player']->journey_until); ?>;
			var journeyFoto = "#" + journey + "Foto";
		
			$(journeyFoto).append("<div id='divRemainingTime'></div>");
		
			$("#divRemainingTime").countdown(journey_until, function(event) {
				$(this).html(event.strftime('%H:%M:%S'))
			}).on('finish.countdown', function(event) {
				//Load combat when countdown finishes
				$("#divMainOkno").load('walka.php');
			});
			
			darkenJourneys(journey);
			animateJourney(journey, journey_started_seconds, journey_until_seconds);
		}
	}
	function animateJourney(journey, journey_started_seconds, journey_until_seconds)
	{
		var journeyOver = "#" + journey + "Over";
		var now = new Date().getTime() / 1000;
		var total = journey_until_seconds - journey_started_seconds;
		var elapsed = now - journey_started_seconds; 
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
		$(journeyOver).css("background", myCss); 
		
		if(now < journey_until_seconds){
			setTimeout(function(){
				animateJourney(journey, journey_started_seconds, journey_until_seconds);
			}, 100);
		}
	}
	function darkenJourneys(journey)
	{
		var journeyOver = "#" + journey + "Over";
		$(".journeyOver:not(" + journeyOver + ")").css("background", "rgba(255, 0, 0, 0.3)");
	}
	
</script>