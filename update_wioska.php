<?php

	require_once('config.php');
    login_check();
	
	drawVillage();
	
	function drawVillage()
	{
		foreach($_SESSION['player']->village as $building => $level)
		{
			echo "<div class='building'>";
				echo "<div class='buildingFoto'>";
					echo "<div class='fotoContainer' id='" .$building. "Foto'>";
					
						echo "<div class='buildingName' id='" .$building. "Name'>";
							switch($building)
							{
								case 'goldmine': echo "Kopalnia złota"; break;
								case 'crystalmine': echo "Kopalnia kryształów"; break;
								case 'magetower': echo "Wieża magów"; break;
								case 'healing': echo "Chata znachorki"; break;
							}
						echo "</div>";
						echo "<div class='buildingLevel' id='" .$building. "Level'>";
							echo "Poziom $level";
						echo "</div>";
						echo "<div class='buildingButton' id='" .$building. "Button'>";
							if($level == 0){
								echo "Wybuduj";
							}
							else{
								echo "Rozbuduj";
							}
						echo "</div>";
						
						if($level == 0)
						{
							$class = "notBuilt";
						}
						echo "<div class='buildingOver $class' id='" .$building. "Over'></div>";
					echo "</div>";
				echo "</div>";
				
			echo "</div>";
		}
	}

?>
