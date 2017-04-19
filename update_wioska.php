<?php

	require_once('config.php');
    login_check();
	
	
	
	drawVillage();
	function drawVillage()
	{
		foreach($_SESSION['player']->village as $building => $level)
		{
			switch($building)
			{
				case 'goldmine': 
					$name = "Kopalnia złota"; 
					$goldCost = round(55 * pow(2, $level));
					$crystalCost = round(70 * pow(2, $level));
					$timeCost = round(30 * pow(2, $level));
					$description = "Chociaż nigdy nie zapuszczałeś się do środka tego labiryntu, to mieszkając nieopodal przyrzekłbyś, że krasnoludzkie przyśpiewki i górnicze powiedzonka znasz lepiej niż twarze własnych rodzicieli. Kiedy tylko przedstawiciele tej rasy usłyszeli, że pod ziemią odnaleziona została żyła tego cennego surowca, całkowicie wyparowali z życia na powierzchni. Szczęście w nieszczęściu - praca tutaj nigdy nie ustaje, co potwierdza kolejny okrzyk sztygara: \"Fedrować, nie pierdolić!\"";
					$aktualny = ($level * 60) . " Złota/godzinę.";
					$nastepny = (($level+1) * 60) . " Złota/godzinę.";
					break;
				case 'crystalmine': 
					$name = "Kopalnia kryształów"; 
					$goldCost = round(80 * pow(2, $level));
					$crystalCost = round(50 * pow(2, $level));
					$timeCost = round(40 * pow(2, $level));
					$description = "Wydobycie i obróbka kryształu to nie lada zadanie. W kopalni tego kruszca zatrudniani są tylko najlepsi fachowcy, wykorzystywane są tylko najnowsze technologie, a całość otacza niemal mistyczna atmosfera profesjonalizmu. Popularne przysłowie głosi, że to właśnie w odbiciach kryształów, wychodzących spod rąk tutejszych czeladników, zobaczyć można prawdziwą magię.";
					$aktualny = ($level * 60) . " Kryształów/godzinę.";
					$nastepny = (($level+1) * 60) . " Kryształów/godzinę.";
					break;
				case 'trader':
					$name = "Rynek";
					$goldCost = round(120 * pow(3, $level));
					$crystalCost = round(20 * pow(2, $level));
					$timeCost = round(30 * pow(2, $level));
					$description = "Jedzą, piją, lulki palą; Tańce, hulanka, swawola<br>Ledwie karczmy nie rozwalą, Cha cha, chi chi, hejza, hola!<br><br>Chociaż rynek niewątpliwie stanowi miejsce do (bardzo wesołych) spotkań międzyludzkich, to nie byłoby to możliwe bez jakże doświadczonych (i żądnych zysku) rzemieślników. Można rzec: wolny rynek.";
					if($level == 0){
						$aktualny = "Brak dostępu do handlarza.";
					}
					else{
						$aktualny = "Handlarz oferuje przedmioty " . ($level) . " poziomu.";
					}
					$nastepny = "Handlarz oferuje przedmioty " . ($level+1) . " poziomu.";
					break;
				case 'magetower': 
					$name = "Wieża magów"; 
					$goldCost = round(40 * pow(2, $level));
					$crystalCost = round(90 * pow(2, $level));
					$timeCost = round(40 * pow(2, $level));
					$description = "Podobno od rozmowy z magiem gorsza jest tylko rozmowa z wyedukowanym magiem. Pomimo tego, że studia w Wieży zdają się zmieniać młodych i obiecujących kandydatów w absolutnych gburów, to ich absolwentom nie można odmówić magicznych umiejętności. Założyciel tej akademii zwykł mawiać, że na świecie nie ma rzeczy niemożliwych, istnieją tylko niewymyślone.";
					if($level == 0){
						$aktualny = "Brak dostępu do czarów.";
					}
					else{
						$aktualny = "Dostęp do czarów $level poziomu.";
					}
					$nastepny = "Dostęp do czarów " . ($level+1) . " poziomu.";
					break;
				case 'healing': 
					$name = "Chata znachorki"; 
					$goldCost = round(70 * pow(2, $level));
					$crystalCost = round(50 * pow(2, $level));
					$timeCost = round(50 * pow(2, $level));
					$description = "Osoby, które przebyły leczenie u tej pozornie zwyczajnej staruszki, często mówią, że ludzie dzielą się na dwa rodzaje: tych, którzy się jej boją, i tych, którzy będą się jej bali. Przemawianie do rozbijanych akurat w moździerzu ingredientów to jedno z normalniejszych zachowań, jakich można tutaj uświadczyć. Mimo tego, usługi znachorki mają też swoje plusy: przynajmniej nie czeka się na nie w kolejce.";
					$aktualny = ($level*30) . " HP/godzinę.";
					$nastepny = (($level+1)*30) . " HP/godzinę.";
					break;
				case 'manahealing':
					$name = "Starożytny ołtarz";
					$goldCost = round(50 * pow(2, $level));
					$crystalCost = round(60 * pow(2, $level));
					$timeCost = round(45 * pow(2, $level));
					$description = "Nikt dokładnie nie wie, jak powstało to miejsce. Z historii zatartej przez pokolenia wynika tylko, że od zawsze było kojarzone z wieloma bóstwami, a przez niektórych nawet czczone. Spacerując w pobliżu czujesz, jak napełnia cię siła duchowa.";
					$aktualny = ($level*30) . " MP/godzinę.";
					$nastepny = (($level+1)*30) . " MP/godzinę.";
					break;
				default: 
					break;
			}
			
			if($timeCost < 60){
				$format = "s";
			} else if($timeCost >= 60 and $timeCost < 3600){
				$format = "i:s";
			} else if($timeCost >= 3600){
				$format = "H:i:s";
			}
			$timeCost = gmdate($format, $timeCost);
			
			
			//Main div of each building
			echo "<div class='building'>";
			
			
				//Container of the photo (sets dimensions)
				echo "<div class='buildingFoto'>";
					//Photo of the building
					echo "<div class='fotoContainer' id='" .$building. "Foto'>";
					
						//Name of the building
						echo "<div class='buildingName' id='" .$building. "Name'>";
							echo $name;
						echo "</div>";
						
						//Level of the building
						if($level != 0){
							echo "<div class='buildingLevel' id='" .$building. "Level'>";
								echo "Poziom $level";
							echo "</div>";
						}
						
						//Upgrade button
						echo "<div class='buildingButton arrow' id='" .$building. "Button'>";
							if($level == 0){
								echo "Wybuduj";
							}
							else{
								echo "Rozbuduj";
							}
						echo "</div>";
						
						//Overlay
						if($level == 0){
							$class = "notBuilt";
						}else{
							$class = "";
						}
						echo "<div class='buildingOver $class' id='" .$building. "Over'></div>";
						
					echo "</div>";
				echo "</div>";
				

				//Costs of the building
				echo "<div class='buildingCosts' id='" .$building. "Costs'>";
					//Gold Cost
					echo "<div class='cost' id='" .$building. "GoldCost'>";
						echo "<img class='goldIcon'>";
				
						if($goldCost <= $_SESSION['player']->zloto){
							echo "<div class='okCost'>$goldCost</div>";
						}
						else{
							echo "<div class='noCost'>$goldCost</div>";
						}
					echo "</div>";
					
					//Crystal cost
					echo "<div class='cost' id='" .$building. "CrystalCost'>";
						
						echo "<img class='crystalIcon'>";
						
						if($crystalCost <= $_SESSION['player']->krysztaly){
							echo "<div class='okCost'>$crystalCost</div>";
						}
						else{
							echo "<div class='noCost'>$crystalCost</div>";
						}
					echo "</div>";
					
					//Time cost
					echo "<div class='cost' id='" . $building. "TimeCost'>";
					
						echo "<img class='timeIcon'>";
						
						echo "<div class='okCost'>$timeCost</div>";
					echo "</div>";
				echo "</div>";
				
				
				//Description of the building
				echo "<div class='buildingDescription' id='" .$building. "Description'>";
					switch($building)
					{
						case 'goldmine': break; 
					}
					echo "<div class='descTitle'>$name</div>";
					echo "<div class='descSubtitle'>";
						if($level == 0){
							echo "(Nie wybudowano)";
						}
						else{
							echo "(Poziom $level)";
						}
					echo "</div>";
					echo "<div class='divider'></div>";
					echo "<div class='description'>$description</div>";
					
					echo "<div class='bonusy'>";
						echo "<div class='aktualnyBonus'><div class='aktualnyBonusLabel'>Aktualny bonus:</div> $aktualny</div>";
						echo "<div class='nastepnyBonus'><div class='nastepnyBonusLabel'>Następny poziom:</div> $nastepny</div>";
					echo "</div>";
				echo "</div>";
			echo "</div>";
		}
	}

?>
