<?php   

	class Item
	{
		public $id;
		public $name;
		public $rarity;
		public $tier;
		public $slot;
		public $type;
		public $subtype;
		public $foto;
		
		public $dmgmin = 0;
		public $dmgmax = 0;
		public $attackspeed = 0;
		public $critchance = 0;
		public $armor = 0;
		public $magicdefense = 0;
		public $movepenalty = 0;
		public $dmgogien = 0;
		public $dmgwoda = 0;
		public $dmgpowietrze = 0;
		public $dmgziemia = 0;
		public $sila = 0;
		public $zwinnosc = 0;
		public $celnosc = 0;
		public $kondycja = 0;
		public $inteligencja = 0;
		public $wiedza = 0;
		public $charyzma = 0;
		public $szczescie = 0;
		public $price = 0;
		
		public function drawHover()
		{
			echo "<div class='itemHover'>";
				$rarityName = $this->rarity . "Name";
				echo "<div class='itemName $rarityName'>" .$this->name. "</div>";
				echo "<div class='divider'></div>";
				
				if($this->dmgmin != 0)
				{
					echo "<div class='itemDamage'>" .$this->dmgmin. "-" .$this->dmgmax. "</div> obrażeń fizycznych<br>";
				}
				if($this->attackspeed != 0)
				{
					echo "<div class='itemAttackSpeed'>" .$this->attackspeed. "</div> ataków na sekundę<br>";
				}
				if($this->critchance != 0)
				{
					echo "<div class='itemCritChance'>" .$this->critchance. "%</div> szansy na traf. kryt.<br>";
				}
				if($this->armor != 0)
				{
					echo "<div class='itemArmor'>+" .$this->armor. "</div> pancerza<br>";
				}
				if($this->magicdefense != 0)
				{
					echo "<div class='itemMagicDefense'>+" .$this->magicdefense. "</div> odporności na magię<br>";
				}
				if($this->movepenalty != 0)
				{
					echo "<div class='itemMovement'>" .$this->movepenalty. "</div> do ruchu<br>";
				}
				if($this->dmgogien !=0)
				{
					echo "<div class='itemDmgOgien'>+" .$this->dmgogien. "</div> obrażeń od ognia<br>";
				}
				if($this->dmgwoda !=0)
				{
					echo "<div class='itemDmgWoda'>+" .$this->dmgwoda. "</div> obrażeń od wody<br>";
				}
				if($this->dmgpowietrze !=0)
				{
					echo "<div class='itemDmgPowietrze'>+" .$this->dmgpowietrze. "</div> obrażeń od powietrza<br>";
				}
				if($this->dmgziemia !=0)
				{
					echo "<div class='itemDmgZiemia'>+" .$this->dmgziemia. "</div> obrażeń od ziemi<br>";
				}
				if($this->sila !=0)
				{
					echo "<div class='itemSila'>+" .$this->sila. "</div> siły<br>";
				}
				if($this->zwinnosc !=0)
				{
					echo "<div class='itemZwinnosc'>+" .$this->zwinnosc. "</div> zwinności<br>";
				}
				if($this->celnosc !=0)
				{
					echo "<div class='itemCelnosc'>+" .$this->celnosc. "</div> celności<br>";
				}
				if($this->kondycja !=0)
				{
					echo "<div class='itemKondycja'>+" .$this->kondycja. "</div> kondycji<br>";
				}
				if($this->inteligencja !=0)
				{
					echo "<div class='itemInteligencja'>+" .$this->inteligencja. "</div> inteligencji<br>";
				}
				if($this->wiedza !=0)
				{
					echo "<div class='itemWiedza'>+" .$this->wiedza. "</div> wiedzy<br>";
				}
				if($this->charyzma !=0)
				{
					echo "<div class='itemCharyzma'>+" .$this->charyzma. "</div> charyzmy<br>";
				}
				if($this->szczescie !=0)
				{
					echo "<div class='itemSzczescie'>+" .$this->szczescie. "</div> szczęścia<br>";
				}
				
				echo "<div class='divider'></div>";
				echo "<div class='itemPrice'>Cena: " .$this->price. " szt. zł.</div>";
			
			echo "</div>";
		}
		public function drawHoverCompare(Item $compare)
		{
			echo "<div class='itemHover'>";
				$rarityName = $this->rarity . "Name";
				echo "<div class='itemName $rarityName'>" .$this->name. "</div>";
				echo "<div class='divider'></div>";
				
				if($this->dmgmin != 0 or $compare->dmgmin != 0)
				{
					if($this->dmgmin < $compare->dmgmin)
					{
						$arrow1 = "<span class='greenArrow'>&nearr;</span>";
					}
					else if($this->dmgmin > $compare->dmgmin)
					{
						$arrow1 = "<span class='redArrow'>&searr;</span>";
					}
					else
					{
						$arrow1 = "<span class='equalArrow'>&#10070;</span>";
					}
					
					if($this->dmgmax < $compare->dmgmax)
					{
						$arrow2 = "<span class='greenArrow'>&nearr;</span>";
					}
					else if($this->dmgmax > $compare->dmgmax)
					{
						$arrow2 = "<span class='redArrow'>&searr;</span>";
					}
					else
					{
						$arrow2 = "<span class='equalArrow'>&#10070;</span>";
					}
					echo "<div class='itemDamage'>" .$this->dmgmin. "-" .$this->dmgmax. "</div> obrażeń fizycznych (założony: " .$compare->dmgmin. $arrow1 . "-" .$compare->dmgmax. $arrow2 . ")<br>";
				}
				if($this->attackspeed != 0 or $compare->attackspeed != 0)
				{
					if($this->attackspeed < $compare->attackspeed)
					{
						$arrow1 = "<span class='greenArrow'>&nearr;</span>";
					}
					else if($this->attackspeed > $compare->attackspeed)
					{
						$arrow1 = "<span class='redArrow'>&searr;</span>";
					}
					else
					{
						$arrow1 = "<span class='equalArrow'>&#10070;</span>";
					}
					
					echo "<div class='itemAttackSpeed'>" .$this->attackspeed. "</div> ataków na sekundę (założony: " .$compare->attackspeed. $arrow1 . ")<br>";
				}
				if($this->critchance != 0 or $compare->critchance != 0)
				{
					if($this->critchance < $compare->critchance)
					{
						$arrow1 = "<span class='greenArrow'>&nearr;</span>";
					}
					else if($this->critchance > $compare->critchance)
					{
						$arrow1 = "<span class='redArrow'>&searr;</span>";
					}
					else
					{
						$arrow1 = "<span class='equalArrow'>&#10070;</span>";
					}
					
					echo "<div class='itemCritChance'>" .$this->critchance. "%</div> szansy na traf. kryt. (założony: " .$compare->critchance. $arrow1 . ")<br>";
				}
				if($this->armor != 0 or $compare->armor != 0)
				{
					if($this->armor < $compare->armor)
					{
						$arrow1 = "<span class='greenArrow'>&nearr;</span>";
					}
					else if($this->armor > $compare->armor)
					{
						$arrow1 = "<span class='redArrow'>&searr;</span>";
					}
					else
					{
						$arrow1 = "<span class='equalArrow'>&#10070;</span>";
					}
					echo "<div class='itemArmor'>+" .$this->armor. "</div> pancerza (założony: " .$compare->armor. $arrow1 . ")<br>";
				}
				if($this->magicdefense != 0 or $compare->magicdefense != 0)
				{
					if($this->magicdefense < $compare->magicdefense)
					{
						$arrow1 = "<span class='greenArrow'>&nearr;</span>";
					}
					else if($this->magicdefense > $compare->magicdefense)
					{
						$arrow1 = "<span class='redArrow'>&searr;</span>";
					}
					else
					{
						$arrow1 = "<span class='equalArrow'>&10070;</span>";
					}
					echo "<div class='itemMagicDefense'>+" .$this->magicdefense. "</div> odporności na magię (założony: " .$compare->magicdefense. $arrow1 . ")<br>";
				}
				if($this->movepenalty != 0 or $compare->movepenalty != 0)
				{
					if($this->movepenalty < $compare->movepenalty)
					{
						$arrow1 = "<span class='greenArrow'>&nearr;</span>";
					}
					else if($this->movepenalty > $compare->movepenalty)
					{
						$arrow1 = "<span class='redArrow'>&searr;</span>";
					}
					else
					{
						$arrow1 = "<span class='equalArrow'>&#10070;</span>";
					}
					
					echo "<div class='itemMovement'>" .$this->movepenalty. "</div> do ruchu (założony: " .$compare->movepenalty. $arrow1 . ")<br>";
				}
				if($this->dmgogien !=0 or $compare->dmgogien != 0)
				{
					if($this->dmgogien < $compare->dmgogien)
					{
						$arrow1 = "<span class='greenArrow'>&nearr;</span>";
					}
					else if($this->dmgogien > $compare->dmgogien)
					{
						$arrow1 = "<span class='redArrow'>&searr;</span>";
					}
					else
					{
						$arrow1 = "<span class='equalArrow'>&#10070;</span>";
					}
					
					echo "<div class='itemDmgOgien'>+" .$this->dmgogien. "</div> obrażeń od ognia (założony: " .$compare->dmgogien. $arrow1 . ")<br>";
				}
				if($this->dmgwoda !=0 or $compare->dmgwoda != 0)
				{
					if($this->dmgwoda < $compare->dmgwoda)
					{
						$arrow1 = "<span class='greenArrow'>&nearr;</span>";
					}
					else if($this->dmgwoda > $compare->dmgwoda)
					{
						$arrow1 = "<span class='redArrow'>&searr;</span>";
					}
					else
					{
						$arrow1 = "<span class='equalArrow'>&#10070;</span>";
					}
					
					echo "<div class='itemDmgWoda'>+" .$this->dmgwoda. "</div> obrażeń od wody (założony: " .$compare->dmgwoda . $arrow1 . ")<br>";
				}
				if($this->dmgpowietrze !=0 or $compare->dmgpowietrze != 0)
				{
					if($this->dmgpowietrze < $compare->dmgpowietrze)
					{
						$arrow1 = "<span class='greenArrow'>&nearr;</span>";
					}
					else if($this->dmgpowietrze > $compare->dmgpowietrze)
					{
						$arrow1 = "<span class='redArrow'>&searr;</span>";
					}
					else
					{
						$arrow1 = "<span class='equalArrow'>&#10070;</span>";
					}
					
					echo "<div class='itemDmgPowietrze'>+" .$this->dmgpowietrze. "</div> obrażeń od powietrza (założony: " .$compare->dmgpowietrze . $arrow1 . ")<br>";
				}
				if($this->dmgziemia !=0 or $compare->dmgziemia != 0)
				{
					if($this->dmgziemia < $compare->dmgziemia)
					{
						$arrow1 = "<span class='greenArrow'>&nearr;</span>";
					}
					else if($this->dmgziemia > $compare->dmgziemia)
					{
						$arrow1 = "<span class='redArrow'>&searr;</span>";
					}
					else
					{
						$arrow1 = "<span class='equalArrow'>&#10070;</span>";
					}
					
					echo "<div class='itemDmgZiemia'>+" .$this->dmgziemia. "</div> obrażeń od ziemi (założony: " .$compare->dmgziemia . $arrow1 . ")<br>";
				}
				if($this->sila !=0 or $compare->sila != 0)
				{
					if($this->sila < $compare->sila)
					{
						$arrow1 = "<span class='greenArrow'>&nearr;</span>";
					}
					else if($this->sila > $compare->sila)
					{
						$arrow1 = "<span class='redArrow'>&searr;</span>";
					}
					else
					{
						$arrow1 = "<span class='equalArrow'>&#10070;</span>";
					}
					
					echo "<div class='itemSila'>+" .$this->sila. "</div> siły (założony: " .$compare->sila . $arrow1 . ")<br>";
				}
				if($this->zwinnosc !=0 or $compare->zwinnosc != 0)
				{
					if($this->zwinnosc < $compare->zwinnosc)
					{
						$arrow1 = "<span class='greenArrow'>&nearr;</span>";
					}
					else if($this->zwinnosc > $compare->zwinnosc)
					{
						$arrow1 = "<span class='redArrow'>&searr;</span>";
					}
					else
					{
						$arrow1 = "<span class='equalArrow'>&#10070;</span>";
					}
					
					echo "<div class='itemZwinnosc'>+" .$this->zwinnosc. "</div> zwinności (założony: " .$compare->zwinnosc . $arrow1 . ")<br>";
				}
				if($this->celnosc !=0 or $compare->celnosc != 0)
				{
					if($this->celnosc < $compare->celnosc)
					{
						$arrow1 = "<span class='greenArrow'>&nearr;</span>";
					}
					else if($this->celnosc > $compare->celnosc)
					{
						$arrow1 = "<span class='redArrow'>&searr;</span>";
					}
					else
					{
						$arrow1 = "<span class='equalArrow'>&#10070;</span>";
					}
					
					echo "<div class='itemCelnosc'>+" .$this->celnosc. "</div> celności (założony: " .$compare->celnosc . $arrow1 . ")<br>";
				}
				if($this->kondycja !=0 or $compare->kondycja != 0)
				{
					if($this->kondycja < $compare->kondycja)
					{
						$arrow1 = "<span class='greenArrow'>&nearr;</span>";
					}
					else if($this->kondycja > $compare->kondycja)
					{
						$arrow1 = "<span class='redArrow'>&searr;</span>";
					}
					else
					{
						$arrow1 = "<span class='equalArrow'>&#10070;</span>";
					}
					
					echo "<div class='itemKondycja'>+" .$this->kondycja. "</div> kondycji (założony: " .$compare->kondycja . $arrow1 . ")<br>";
				}
				if($this->inteligencja !=0 or $compare->inteligencja != 0)
				{
					if($this->inteligencja < $compare->inteligencja)
					{
						$arrow1 = "<span class='greenArrow'>&nearr;</span>";
					}
					else if($this->inteligencja > $compare->inteligencja)
					{
						$arrow1 = "<span class='redArrow'>&searr;</span>";
					}
					else
					{
						$arrow1 = "<span class='equalArrow'>&#10070;</span>";
					}
					
					echo "<div class='itemInteligencja'>+" .$this->inteligencja. "</div> inteligencji (założony: " .$compare->inteligencja . $arrow1 . ")<br>";
				}
				if($this->wiedza !=0 or $compare->wiedza != 0)
				{
					if($this->wiedza < $compare->wiedza)
					{
						$arrow1 = "<span class='greenArrow'>&nearr;</span>";
					}
					else if($this->wiedza > $compare->wiedza)
					{
						$arrow1 = "<span class='redArrow'>&searr;</span>";
					}
					else
					{
						$arrow1 = "<span class='equalArrow'>&#10070;</span>";
					}
					
					echo "<div class='itemWiedza'>+" .$this->wiedza. "</div> wiedzy (założony: " .$compare->wiedza . $arrow1 . ")<br>";
				}
				if($this->charyzma !=0 or $compare->charyzma != 0)
				{
					if($this->charyzma < $compare->charyzma)
					{
						$arrow1 = "<span class='greenArrow'>&nearr;</span>";
					}
					else if($this->charyzma > $compare->charyzma)
					{
						$arrow1 = "<span class='redArrow'>&searr;</span>";
					}
					else
					{
						$arrow1 = "<span class='equalArrow'>&#10070;</span>";
					}
					
					echo "<div class='itemCharyzma'>+" .$this->charyzma. "</div> charyzmy (założony: " .$compare->charyzma . $arrow1 . ")<br>";
				}
				if($this->szczescie !=0 or $compare->szczescie != 0)
				{
					if($this->szczescie < $compare->szczescie)
					{
						$arrow1 = "<span class='greenArrow'>&nearr;</span>";
					}
					else if($this->szczescie > $compare->szczescie)
					{
						$arrow1 = "<span class='redArrow'>&searr;</span>";
					}
					else
					{
						$arrow1 = "<span class='equalArrow'>&#10070;</span>";
					}
					
					echo "<div class='itemSzczescie'>+" .$this->szczescie. "</div> szczęścia (założony: " .$compare->szczescie . $arrow1 . ")<br>";
				}
			
				echo "<div class='divider'></div>";
				echo "<div class='itemPrice'>Cena: " .$this->price. " szt. zł.</div>";
			
			echo "</div>";
		}
		public function drawFoto($divID)
		{
			$fotoPath = "url(gfx/itemy/" . $this->foto . ".png)";
			echo "<div class='fotoContainer2' id='" .$divID. "' style='background-image: " . $fotoPath . ";'></div>";
			unset($fotoPath);
		}
		public static function withID($id)
		{
			$instance = new self();
			$instance->loadByID($id);
			return $instance;
		}
		protected function loadByID($id)
		{
			$conn = connectDB();
			$result = $conn->query("SELECT * FROM items WHERE id='$id'");
			$row = mysqli_fetch_assoc($result);
			$conn->close();
			unset($conn);
			
			$this->id = $id;
			$this->name = $row['name'];
			$this->rarity = $row['rarity'];
			$this->tier = $row['tier'];
			$this->slot = $row['slot'];
			$this->type = $row['type'];
			$this->subtype = $row['subtype'];
			$this->dmgmin = $row['dmgmin'];
			$this->dmgmax = $row['dmgmax'];
			$this->attackspeed = $row['attackspeed'];
			$this->critchance = $row['critchance'];
			$this->armor = $row['armor'];
			$this->magicdefense = $row['magicdefense'];
			$this->dmgogien = $row['dmgogien'];
			$this->dmgwoda = $row['dmgwoda'];
			$this->dmgpowietrze = $row['dmgpowietrze'];
			$this->dmgziemia = $row['dmgziemia'];
			$this->sila = $row['sila'];
			$this->zwinnosc = $row['zwinnosc'];
			$this->celnosc = $row['celnosc'];
			$this->kondycja = $row['kondycja'];
			$this->inteligencja = $row['inteligencja'];
			$this->wiedza = $row['wiedza'];
			$this->charyzma = $row['charyzma'];
			$this->szczescie = $row['szczescie'];
			$this->movepenalty = $row['movepenalty'];
			$this->price = $row['price'];
			
			if($this->rarity != "legendary")
			{
				$this->foto = "" . $this->subtype . $this->tier;
			}
			else
			{
				$this->foto = "legendary/" . $this->subtype . $this->tier;
			}
		}
		public function saveToDB()
		{
			$conn = connectDB();
			
			$name = $conn->real_escape_string($this->name);
			$rarity = $conn->real_escape_string($this->rarity);
			$tier = $conn->real_escape_string($this->tier);
			$slot = $conn->real_escape_string($this->slot);
			$type = $conn->real_escape_string($this->type);
			$subtype = $conn->real_escape_string($this->subtype);
			$dmgmin = $conn->real_escape_string($this->dmgmin);
			$dmgmax = $conn->real_escape_string($this->dmgmax);
			$attackspeed = $conn->real_escape_string($this->attackspeed);
			$critchance = $conn->real_escape_string($this->critchance);			
			$armor = $conn->real_escape_string($this->armor);
			$magicdefense = $conn->real_escape_string($this->magicdefense);
			$dmgogien = $conn->real_escape_string($this->dmgogien);
			$dmgwoda = $conn->real_escape_string($this->dmgwoda);
			$dmgpowietrze = $conn->real_escape_string($this->dmgpowietrze);
			$dmgziemia = $conn->real_escape_string($this->dmgziemia);
			$sila = $conn->real_escape_string($this->sila);
			$zwinnosc = $conn->real_escape_string($this->zwinnosc);
			$celnosc = $conn->real_escape_string($this->celnosc);
			$kondycja = $conn->real_escape_string($this->kondycja);
			$inteligencja = $conn->real_escape_string($this->inteligencja);
			$wiedza = $conn->real_escape_string($this->wiedza);
			$charyzma = $conn->real_escape_string($this->charyzma);
			$szczescie = $conn->real_escape_string($this->szczescie);
			$movepenalty = $conn->real_escape_string($this->movepenalty);
			$price = $conn->real_escape_string($this->price);
			
			$conn->query("INSERT INTO items (name, rarity, tier, slot, type, subtype, dmgmin, dmgmax, attackspeed, critchance, armor, magicdefense, dmgogien, dmgwoda, dmgpowietrze, dmgziemia, sila, zwinnosc, celnosc, kondycja, inteligencja, wiedza, charyzma, szczescie, movepenalty, price) VALUES ('$name', '$rarity', '$tier', '$slot', '$type', '$subtype', '$dmgmin', '$dmgmax', '$attackspeed', '$critchance', '$armor', '$magicdefense', '$dmgogien', '$dmgwoda', '$dmgpowietrze', '$dmgziemia', '$sila', '$zwinnosc', '$celnosc', '$kondycja', '$inteligencja', '$wiedza', '$charyzma', '$szczescie', '$movepenalty','$price')");
			$this->id = $conn->insert_id;
			$conn->close();
			
			unset($conn);
			unset($name);
			unset($rarity);
			unset($tier);
			unset($slot);
			unset($type);
			unset($subtype);
			unset($dmgmin);
			unset($dmgmax);
			unset($attackspeed);
			unset($critchance);
			unset($armor);
			unset($dmgogien);
			unset($dmgwoda);
			unset($dmgpowietrze);
			unset($dmgziemia);
			unset($sila);
			unset($zwinnosc);
			unset($celnosc);
			unset($kondycja);
			unset($inteligencja);
			unset($wiedza);
			unset($charyzma);
			unset($szczescie);
			unset($movepenalty);
			unset($price);
		}
	}
	class Player
	{
		public $id;
		public $username;
		public $type;
		public $plec;
		public $rasa;
		public $klasa;
		public $foto;
		public $level;
		public $experience;
		public $experiencenext;
		public $remaining;

		public $sila = 0;
		public $zwinnosc = 0;
		public $celnosc = 0;
		public $kondycja = 0;
		public $inteligencja = 0;
		public $wiedza = 0;
		public $charyzma = 0;
		public $szczescie = 0;
		
		//DB stats
		public $hp = 0;
		public $maxhp = 0;
		public $mana = 0;
		public $maxmana = 0;
		public $zloto = 0;
		public $krysztaly = 0;		
		
		//Updates
		public $unread;
		public $last_update;
		public $last_shop_update;
		public $building;
		public $building_started;
		public $building_until;
		public $journey;
		public $journey_started;
		public $journey_until;
		
		
		//Combat settings
		public $side;
		public $did_move;
		public $time_remaining;
		public $spells_only;
		
		//Combat stats
		public $dmgmin = 0;
		public $dmgmax = 0;
		public $attackspeed = 0;
		public $critchance = 0;		
		public $armor = 0;
		public $magicdefense = 0;
		public $movepenalty = 0;
		
		//Inventory
		public $backpack = [
			0 => "",
			1 => "",
			2 => "",
			3 => "",
			4 => "",
			5 => "",
			6 => "",
			7 => "",
			8 => "",
			9 => "",
			10 => "",
			11 => "",
			12 => "",
			13 => "",
			14 => "",
		];
		public $shop = [
			0 => "",
			1 => "",
			2 => "",
			3 => "",
			4 => "",
			5 => "",
			6 => "",
			7 => "",
			8 => "",
			9 => "",
			10 => "",
			11 => "",
			12 => "",
			13 => "",
			14 => "",
		];
		public $equipment = [
			'amulet' => "",
			'helmet' => "",
			'ring' => "",
			'lefthand' => "",
			'chest' => "",
			'righthand' => "",
			'gloves' => "",
			'belt' => "",
			'boots' => ""
		];
		public $village = [
			'goldmine' => 1,
			'crystalmine' => 1,
			'trader' => 1,
			'magetower' => 0,
			'healing' => 0,
			'manahealing' => 0,
		];
		
		
		public function addToBackpack(Item $item)
		{
			for($i = 0; $i < count($this->backpack); $i++)
			{
				if($this->backpack[$i] == "")
				{
					$this->backpack[$i] = $item;
					//Saving item itself to database
					$item->saveToDB();
					//Saving player backpack to database
					$conn = connectDB();
					$id = $this->id;
					$itemID = $item->id;
					$slot = "slot" . $i;
					$conn->query("UPDATE equipment SET $slot=$itemID WHERE id=$id");
					$conn->close();
					unset($conn);
					unset($id);
					unset($itemID);
					unset($slot);
					break;
				}
			}
		}
		public function equipItem(Item $item)
		{
			$this->sila += $item->sila;
			$this->zwinnosc += $item->zwinnosc;
			$this->celnosc += $item->celnosc;
			$this->kondycja += $item->kondycja;
			$this->inteligencja += $item->inteligencja;
			$this->wiedza += $item->wiedza;
			$this->charyzma += $item->charyzma;
			$this->szczescie += $item->szczescie;
			
			$this->dmgmin += $item->dmgmin;
			$this->dmgmax += $item->dmgmax;
			$this->attackspeed += $item->attackspeed;
			$this->critchance += $item->critchance;
			$this->armor += $item->armor;
			$this->magicdefense += $item->magicdefense;
			$this->movepenalty += $item->movepenalty;
			
			$this->updateHP();
			$this->updateMana();
			
			$this->equipment[$item->slot] = $item;
		}
		public function equipFromSlot($slot)
		{
			$this->equipItem($this->backpack[$slot]);
		}
		public function unequipItem(Item $item)
		{
			$this->sila -= $item->sila;
			$this->zwinnosc -= $item->zwinnosc;
			$this->celnosc -= $item->celnosc;
			$this->kondycja -= $item->kondycja;
			$this->inteligencja -= $item->inteligencja;
			$this->wiedza -= $item->wiedza;
			$this->charyzma -= $item->charyzma;
			$this->szczescie -= $item->szczescie;
			
			$this->dmgmin -= $item->dmgmin;
			$this->dmgmax -= $item->dmgmax;
			$this->attackspeed -= $item->attackspeed;
			$this->critchance -= $item->critchance;
			$this->armor -= $item->armor;
			$this->magicdefense -= $item->magicdefense;
			$this->movepenalty -= $item->movepenalty;
			
			$this->updateHP();
			$this->updateMana();
			
			$this->equipment[$item->slot] = "";
		}
		public function unequipFromSlot($slot)
		{
			if($this->equipment[$slot] != "")
			{
				$this->unequipItem($this->equipment[$slot]);
			}
		}
		public function generateShop()
		{
			$conn = connectDB();
			$userID = $this->id;
			
			//Removing current
			for($i = 0; $i < count($this->shop); $i++)
			{
				if($this->shop[$i] != "")
				{
					$id = $this->shop[$i]->id;
					$conn->query("DELETE FROM items WHERE id=$id");
				}
				
				$this->shop = "";
			}
			
			//Generating new
			$itemsNumber = rand(5,15);
			for($i = 0; $i < $itemsNumber; $i++)
			{
				$item = generateItem($this->village['trader']);
				$item->price = $item->price * 5;
				$item->saveToDB();
				
				$this->shop[$i] = $item;
				$itemID = $this->shop[$i]->id;
				$slotName = "shop" . $i;
				
				$conn->query("UPDATE equipment set $slotName=$itemID WHERE id=$userID");
				unset($item);
			}
			for($i; $i < 15; $i++)
			{
				$slotName = "shop" . $i;
				$this->shop[$i] = "";
				
				$conn->query("UPDATE equipment SET $slotName=NULL WHERE id=$userID");
			}
			
			$conn->close();
			unset($conn);
			unset($userID);
		}
		
		
		public function updateMaxHP()
		{
			$this->maxhp = $this->kondycja * 10;
			$this->hp = $this->maxhp;
		}
		public function updateMaxMana()
		{
			$this->maxmana = $this->wiedza * 10;
			$this->mana = $this->maxmana;
		}
		public function updateHP()
		{
			$this->maxhp = $this->kondycja * 10;
			
			if($this->hp > $this->maxhp)
			{
				$this->hp = $this->maxhp;
			}
		}
		public function updateMana()
		{
			$this->maxmana = $this->wiedza * 10;
			
			if($this->mana > $this->maxmana)
			{
				$this->mana = $this->maxmana;
			}
		}
		
		
		//HP regen, gold income etc. Use before fights and on every reload
		public function updateLocally()
		{
			$now = time();
			//Last update is saved locally, in number format
			if(is_numeric($this->last_update)){
				$last = $this->last_update;
			}
			//Last update was downloaded from DB, in time format
			else{
				$last = strtotime($this->last_update);
				$this->last_update = $last;
			}
			
			$seconds = $now-$last;
			if($seconds > 10)
			{
				$this->last_update = $now;
				
				$updates = round($seconds/10);
				$this->hpRegen($updates);
				$this->mpRegen($updates);
				$this->goldRegen($updates);
				$this->crystalsRegen($updates);
			}
			
			unset($now);
			unset($last);
			unset($seconds);
			unset($updates);
		}
		//Saves to DB, use after permanent stat updates (fights, equipping, level up etc)
		public function updateStatsGlobally()
		{
			$id = $this->id;
			$level = $this->level;
			$experience = $this->experience;
			$experiencenext = $this->experiencenext;
			$remaining = $this->remaining;
		
			$sila = $this->sila;
			$zwinnosc = $this->zwinnosc;
			$celnosc = $this->celnosc;
			$kondycja = $this->kondycja;
			$inteligencja = $this->inteligencja;
			$wiedza = $this->wiedza;
			$charyzma = $this->charyzma;
			$szczescie = $this->szczescie;
		
			$hp = $this->hp;
			$maxhp = $this->maxhp;
			$mana = $this->mana;
			$maxmana = $this->maxmana;
			$zloto = $this->zloto;
			$krysztaly = $this->krysztaly;
			
			$dmgmin = $this->dmgmin;
			$dmgmax = $this->dmgmax;
			$attackspeed = $this->attackspeed;
			$critchance = $this->critchance;
			$armor = $this->armor;
			$magicdefense = $this->magicdefense;
			$movepenalty = $this->movepenalty;
			
			$conn=connectDB();
			$conn->query("UPDATE users SET level=$level, experience=$experience, experiencenext=$experiencenext, remaining=$remaining, sila=$sila, zwinnosc=$zwinnosc, celnosc=$celnosc, kondycja=$kondycja, inteligencja=$inteligencja, wiedza=$wiedza, charyzma=$charyzma, szczescie=$szczescie, hp=$hp, maxhp=$maxhp, mana=$mana, maxmana=$maxmana, zloto=$zloto, krysztaly=$krysztaly, dmgmin=$dmgmin, dmgmax=$dmgmax, attackspeed=$attackspeed, critchance=$critchance, armor=$armor, magicdefense=$magicdefense, movepenalty=$movepenalty, last_update=NOW() WHERE id=$id");
			$conn->close();
			
			unset($id);
			unset($level);
			unset($experience);
			unset($experiencenext);
			unset($remaining);
			unset($sila);
			unset($zwinnosc);
			unset($celnosc);
			unset($kondycja);
			unset($inteligencja);
			unset($wiedza);
			unset($charyzma);
			unset($szczescie);
			unset($hp);
			unset($maxhp);
			unset($mana);
			unset($maxmana);
			unset($zloto);
			unset($krysztaly);
			unset($movepenalty);
		}
		//TODO: WATCH VIDEO FROM PHONE
		public function updateMail()
		{
			$conn = connectDB();
			$userID = $this->id;
			$result = $conn->query("SELECT * FROM user_mail WHERE id=$userID");
			$row = mysqli_fetch_row($result);
			
			$unread = 0;
			//Iterates through the user's message slots
			for($i = 1; $i < 11; $i++)
			{
				//There is a message
				if($row[$i] != null)
				{
					//Get the message ID
					$msgID = $row[$i];
					//Check if that message was read
					$is_read = get_value($conn, "SELECT is_read FROM messages WHERE id=$msgID");
					if($is_read == 0)
					{
						$unread++;
					}
					
					unset($msgID);
				}
			}
			
			$conn->close();
			$this->unread = $unread;
			
			unset($userID);
			unset($result);
			unset($row);
			unset($unread);
		}
		
		
		public function hpRegen($times)
		{
			//1 times = 10 seconds
			//30/h/lvl
			$base = 0.1;
			
			$perUpdate = (($this->village['healing'] * 30) / 360) + $base;
			$this->hp += ($times * $perUpdate);
			
			if($this->hp > $this->maxhp){
				$this->hp = $this->maxhp;
			}
			
			unset($perUpdate);
			unset($base);
		}
		public function mpRegen($times)
		{
			//1 times = 10 seconds
			//30/h/lvl
			$base = 0.1;
			
			$perUpdate = (($this->village['manahealing'] * 30) / 360) + $base;
			$this->mana += ($times * $perUpdate);
			
			if($this->mana > $this->maxmana){
				$this->mana = $this->maxmana;
			}
			
			unset($perUpdate);
			unset($base);
		}
		public function goldRegen($times)
		{
			//1 times = 10 seconds
			//60/h/lvl
			$base = 0.2;
			
			$perUpdate = (($this->village['goldmine'] * 60) / 360) + $base;
			$this->zloto += ($times * $perUpdate);
			
			unset($perUpdate);
			unset($base);
		}
		public function crystalsRegen($times)
		{
			//1 times = 10 seconds
			//60/h/lvl
			$base = 0.2;
			
			$perUpdate = (($this->village['crystalmine'] * 60) / 360) + $base;
			$this->krysztaly += ($times * $perUpdate);
			
			unset($perUpdate);
			unset($base);
		}
		
		
		public function drawFoto()
		{
			$fotoPath = "url(gfx/" . $this->foto . ".jpg)";
			echo "<div class='fotoContainer' id='" .$this->id. "Foto' style='background-image: " . $fotoPath . ";'></div>";
			
			unset($fotoPath);
		}
		public function drawJourney()
		{
			if($this->journey != null)
			{
				echo "<div id='journeyContainer'>";
					echo "<img style='height: 70%' src='/gfx/journey.png'>";
					echo "<div id='journeyTekst'></div>";
				echo "</div>";
			}
		}
		public function drawMail()
		{
			$this->updateMail();
			
			if($this->unread > 0)
			{
				$color = 'red';
			}
			else
			{
				$color = 'white';
			}
			
			echo "<div id='mailContainer'>";
				echo "<img style='height: 70%' src='/gfx/mail.png'>";
				echo "<div id='mailTekst' style='color: $color'>" .$this->unread. "</div>";
			echo "</div>";
			
			unset($color);
		}
		public function drawGold()
		{
			$zloto = round($this->zloto);
			echo "<div id='zlotoContainer'>";
				echo "<img style='height: 70%' src='/gfx/gold.png'>";
				echo "<div id='zlotoTekst'>" .$zloto. "</div>";
			echo "</div>";
			
			unset($zloto);
		}
		public function drawCrystals()
		{
			$krysztaly = round($this->krysztaly);
			echo "<div id='krysztalyContainer'>";
				echo "<img style='height: 70%' src='/gfx/crystals.png'>";
				echo "<div id='krysztalyTekst'>" .$krysztaly. "</div>";
			echo "</div>";
			
			unset($krysztaly);
		}
		public function drawHP($nazwa, $style)
		{
			$current = round($this->hp);
			$max = round($this->maxhp);
			$percent = round( ($current/$max) * 100);
			$color = color($current, $max);
		
			echo "<div class='bar' id='bar$nazwa' style='" .$style. "'>";
				echo "<div class='outerBar'>";
					echo "<div class='innerBar' style='width: " .$percent. "%; background-color: " .$color. ";'></div>";
				echo "</div>";
				
				echo "<div class='barText' id='barText$nazwa'>";
					echo "HP: " . $current . " / " . "$max";
				echo "</div>";
			echo "</div>";
			
			unset($current);
			unset($max);
			unset($percent);
			unset($color);
			unset($nazwa);
			unset($style);
		}
		public function drawMP($nazwa, $style)
		{
			$current = round($this->mana);
			$max = round($this->maxmana);
			$percent = round( ($current/$max) * 100 );
		
			echo "<div class='bar' id='bar$nazwa' style='" .$style. "'>";
				echo "<div class='outerBar'>";
					echo "<div class='innerBar' id='innerMana' style='width: " .$percent. "%;'></div>";
				echo "</div>";
				
				echo "<div class='barText' id='barText$nazwa'>";
					echo "MP: " .$current. " / " .$max;
				echo "</div>";
			echo "</div>";
		
			
			
			unset($current);
			unset($max);
			unset($percent);
			unset($nazwa);
			unset($style);
		}
		public function drawEXP($nazwa, $style)
		{
			$current = round($this->experience);
			$max = round($this->experiencenext);
			$percent = round( ($current/$max) * 100);
		
			echo "<div class='bar' id='bar$nazwa' style='" .$style. "'>";
				echo "<div class='outerBar'>";
					echo "<div class='innerBar' id='innerExp' style='width: " .$percent. "%;'></div>";
				echo "</div>";
				
				echo "<div class='barText' id='barText$nazwa'>";
					echo "EXP: " .$current. " / " .$max;
				echo "</div>";
			echo "</div>";
		
			
			
			unset($current);
			unset($max);
			unset($percent);
			unset($nazwa);
			unset($style);
		}
		
		
		//Sets the class object by downloading all player data from SQL server - use for existing players
		public static function withID($id, $downloadItems, $downloadVillage)
		{
			$instance = new self();
			$instance->loadByID($id, $downloadItems, $downloadVillage);
			return $instance;
		}
		//TODO too much data being loaded for fight only (shop, equipment, inventory, update times etc not needed)
		protected function loadByID($id, $downloadItems, $downloadVillage)
		{
			$conn = connectDB();
			$result = $conn->query("SELECT * FROM users WHERE id='$id'");
			$row = mysqli_fetch_assoc($result);
			$conn->close();
			unset($conn);
			
			$this->id = $id;
			$this->type = "player";
			$this->username = $row['username'];
			$this->plec = $row['plec'];
			$this->rasa = $row['rasa'];
			$this->klasa = $row['klasa'];
			$this->foto = "portrety/" . $row['foto'];
			
			$this->level = $row['level'];
			$this->experience = $row['experience'];
			$this->experiencenext = $row['experiencenext'];
			$this->remaining = $row['remaining'];
			
			$this->sila = $row['sila'];
			$this->zwinnosc = $row['zwinnosc'];
			$this->celnosc = $row['celnosc'];
			$this->kondycja = $row['kondycja'];
			$this->inteligencja = $row['inteligencja'];
			$this->wiedza = $row['wiedza'];
			$this->charyzma = $row['charyzma'];
			$this->szczescie = $row['szczescie'];
			
			$this->hp = $row['hp'];
			$this->maxhp = $row['maxhp'];
			$this->mana = $row['mana'];
			$this->maxmana = $row['maxmana'];
			$this->zloto = $row['zloto'];
			$this->krysztaly = $row['krysztaly'];
			
			$this->last_update = $row['last_update'];
			$this->last_shop_update = $row['last_shop_update'];
			$this->building = $row['building'];
			$this->building_started = $row['building_started'];
			$this->building_until = $row['building_until'];
			$this->journey = $row['journey'];
			$this->journey_started = $row['journey_started'];
			$this->journey_until = $row['journey_until'];
			
			$this->dmgmin = $row['dmgmin'];
			$this->dmgmax = $row['dmgmax'];
			$this->attackspeed = $row['attackspeed'];
			$this->critchance = $row['critchance'];
			$this->armor = $row['armor'];
			$this->magicdefense = $row['magicdefense'];
			$this->movepenalty = $row['movepenalty'];
			
			if($downloadItems == true){
				$this->loadItems($id);
			}
			if($downloadVillage == true){
				$this->loadVillage($id);
			}
		}
		protected function loadItems($id)
		{
			$conn = connectDB();
			$result = $conn->query("SELECT * FROM equipment WHERE id='$id'");
			$row = mysqli_fetch_assoc($result);
			$conn->close();
			unset($conn);
			
			//Backpack loading
			for($i = 0; $i < count($this->backpack); $i++)
			{
				$slotName = "slot" . $i;
				if($row[$slotName] != NULL)
				{
					$this->backpack[$i] = Item::withID($row[$slotName]);
				}
			}
			
			//Equipment loading
			foreach($this->equipment as $slot => $item)
			{
				if($row[$slot] != NULL)
				{
					$this->equipment[$slot] = Item::withID($row[$slot]);
				}
			}
			
			//Shop loading
			for($i = 0; $i < count($this->shop); $i++)
			{
				$slotName = "shop" . $i;
				if($row[$slotName] != NULL)
				{
					$this->shop[$i] = Item::withID($row[$slotName]);
				}
			}
		}
		protected function loadVillage($id)
		{
			$conn = connectDB();
			$result = $conn->query("SELECT * FROM villages WHERE id='$id'");
			$row = mysqli_fetch_assoc($result);
			$conn->close();
			unset($conn);
			
			foreach($this->village as $building => $level)
			{
				$this->village[$building] = $row[$building];
			}
		}
		
		
		//Sets the class as a monster, for fight purposes
		public static function asMonster($name, $stats, $kondycja, $attackName, $attackType, $dmgmin, $dmgmax, $attackspeed, $critchance, $armor, $magicdefense, $zloto, $krysztaly, $experience)
		{
			$instance = new self();
			$instance->loadAsMonster($name, $stats, $kondycja, $attackName, $attackType, $dmgmin, $dmgmax, $attackspeed, $critchance, $armor, $magicdefense, $zloto, $krysztaly, $experience);
			return $instance;
		}
		protected function loadAsMonster($name, $stats, $kondycja, $attackName, $attackType, $dmgmin, $dmgmax, $attackspeed, $critchance, $armor, $magicdefense, $zloto, $krysztaly, $experience)
		{
			$this->username = $name;
			$this->type = "monster";
			$this->foto = "monsters/" . $name;
			$this->sila = $stats;
			$this->zwinnosc = $stats;
			$this->celnosc = $stats;
			$this->kondycja = $kondycja;
			$this->inteligencja = $stats;
			$this->wiedza = $stats;
			$this->charyzma = $stats;
			$this->szczescie = $stats;
			
			$this->zloto = $zloto;
			$this->krysztaly = $krysztaly;
			$this->experiencenext = $experience;
			$this->updateMaxHP();
			$this->updateMaxMana();
			
			$bron = new Item();
			$bron->slot = "lefthand";
			$bron->name = $attackName;
			$bron->subtype = $attackType;
			$bron->dmgmin = $dmgmin;
			$bron->dmgmax = $dmgmax;
			$bron->attackspeed = $attackspeed;
			$bron->critchance = $critchance;
			$bron->armor = $armor;
			$bron->magicdefense = $magicdefense;
			
			$this->equipItem($bron);
		}
	}
	
	
	/* ------------- DATABASE FUNCTIONS ----------------- */
    function connectDB()
    {
        $dbhost = '127.0.0.1';
        $dbuser = 'root';
        $dbpass = '';
        $dbname = 'mydb';
        
        $conn = new mysqli($dbhost,$dbuser,$dbpass,$dbname);
        $conn->set_charset("utf8");
        if ($conn->connect_errno)
        {
            return $conn->connect_error;
            exit();
        }
        
        return $conn;
    }
    function get_value($my_SQLI_Connection, $SQL_code)
    {
        $result = $my_SQLI_Connection->query($SQL_code);
        $value = $result->fetch_array(MYSQLI_NUM);
		
        return is_array($value) ? $value[0] : "";
    }
	function get_stat($statName, $table, $ID)
    {
        $conn = connectDB();

        $escapedStatName = $conn->real_escape_string($statName);
        $escapedID = $conn->real_escape_string($ID);
        $escapedTable = $conn->real_escape_string($table);
        
        return get_value($conn, "SELECT $escapedStatName FROM $escapedTable WHERE id = $escapedID");
        $conn->close();
    }
    function login_check()
    {   
        session_start();
        
        if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] == true)
        {
            //User is logged in
        }
        else
        {
			//Kick out
            header('Location:login.php');
        }
    }
	
	
	/* -------------- DRAWING FUNCTIONS -------------------- */
	function color($current, $max)
    {
        $percent = round(($current/$max)*100);

        $green = round(($percent*255)/100);
        $red = 255-$green;
        if ($percent < 0) {
	       $rgb = "rgb(255, 0, 00)";
        }
        return "rgb(" . $red . ", " . $green . ", 00)";
    }
	function generateItem($tier)
	{
		$item = new Item();
		$item->tier = $tier;
		
		// RARITY GENERATION
		$normalMin = 0;
		$normalMax = 70;
		$magicMin = 71;
		$magicMax = 94;
		$rareMin = 95;
		$rareMax = 98;
		$legendaryMin = 99;
		$legendaryMax = 100;
		
		$rarityRoll = rand(0, 100);
		if($rarityRoll >= $normalMin and $rarityRoll <= $normalMax)
		{
			$item->rarity = "normal";
		}
		else if($rarityRoll >= $magicMin and $rarityRoll <= $magicMax)
		{
			$item->rarity = "magic";
		}
		else if($rarityRoll >= $rareMin and $rarityRoll <= $rareMax)
		{
			$item->rarity = "rare";
		}
		else if($rarityRoll >= $legendaryMin)
		{
			$item->rarity = "legendary";
		}
		
		// SLOT GENERATION
		$itemSlots = ['helmet', 'amulet', 'lefthand', 'lefthand', 'lefthand', 'lefthand', 'chest', 'righthand', 'belt', 'gloves', 'ring', 'boots'];
		$slotRoll = rand(0, count($itemSlots) - 1);
		$item->slot = $itemSlots[$slotRoll];

		// TYPE GENERATION
		switch($item->slot)
		{
			case 'helmet': $itemTypes = ['strHelmet', 'dexHelmet', 'intHelmet'];
				break;
			case 'amulet': $itemTypes = ['strAmulet', 'dexAmulet', 'intAmulet'];
				break;
			case 'lefthand': $itemTypes = ['str1H', 'str2H', 'dex1H', 'dex2H', 'int1H', 'int2H'];
				break;
			case 'chest': $itemTypes = ['strChest', 'dexChest', 'intChest'];
				break;
			case 'righthand': $itemTypes = ['strShield', 'dexShield', 'intShield', 'dexOff'];
				break;
			case 'belt': $itemTypes = ['strBelt', 'dexBelt', 'intBelt'];
				break;
			case 'gloves': $itemTypes = ['strGloves', 'dexGloves', 'intGloves'];
				break;
			case 'ring': $itemTypes = ['strRing', 'dexRing', 'intRing'];
				break;
			case 'boots': $itemTypes = ['strBoots', 'dexBoots', 'intBoots'];
				break;
			default:
				break;
		}
		$typeRoll = rand(0, count($itemTypes) - 1);
		$item->type = $itemTypes[$typeRoll];
		
		// SUBTYPE GENERATION
		if($item->slot == 'lefthand')
		{
			switch($item->type)
			{
				case 'str1H': $itemSubtypes = ['mace','axe'];
					break;
				case 'str2H': $itemSubtypes = ['sword2H','mace2H','axe2H'];
					break;
				case 'dex1H': $itemSubtypes = ['sword','dagger'];
					break;
				case 'dex2H': $itemSubtypes = ['bow'];
					break;
				case 'int1H': $itemSubtypes = ['scepter', 'wand'];
					break;
				case 'int2H': $itemSubtypes = ['staff'];
					break;
				default: 
					break;
			}
		}
		else 
		{
			$itemSubtypes = [$item->type];
		}
		$subtypeRoll = rand(0, count($itemSubtypes) - 1);
		$item->subtype = $itemSubtypes[$subtypeRoll];
		
		// NAME AND STAT GENERATION
		switch($item->subtype)
		{
			case 'strHelmet': $itemNames = ["Hełm żołdaka", "Gladiatorski hełm", "Zamknięty hełm", "Rogaty hełm"];
				$item->armor = rand($tier * 5, $tier * 7);
				$item->magicdefense = rand($tier * 1, $tier * 2);
				$item->movepenalty = -0.2;
				break;
			case 'dexHelmet': $itemNames = ["Kaptur", "Bandana", "Przepaska"];
				$item->armor = rand($tier * 3, $tier * 5);
				$item->magicdefense = rand($tier * 3, $tier * 4);
				$item->movepenalty = -0.1;
				break;
			case 'intHelmet': $itemNames = ["Diadem", "Obręcz"];
				$item->armor = rand($tier * 1, $tier * 3);
				$item->magicdefense = rand($tier * 5, $tier * 6);
				$item->movepenalty = 0;
				break;
			case 'strAmulet': $itemNames = ["Amulet wojownika"];
				$item->sila = rand($tier * 1, $tier * 3);
				break;
			case 'dexAmulet': $itemNames = ["Amulet łotra"];
				$item->zwinnosc = rand($tier * 1, $tier * 3);
				break;
			case 'intAmulet': $itemNames = ["Amulet maga"];
				$item->inteligencja = rand($tier * 1, $tier * 3);
				break;
			case 'sword': $itemNames = ["Krótki miecz", "Miecz półtoraręczny", "Rapier", "Szabla"];
				$item->dmgmin = rand($tier * 5, $tier * 6);
				$item->dmgmax = rand($tier * 7, $tier * 8);
				$item->attackspeed = (rand(110, 120)/100);
				$item->critchance = (rand(50,60)/10);
				break;
			case 'mace': $itemNames = ["Morgensztern", "Pałka", "Młot", "Młot bitewny", "Buława ceremonialna", "Skałołamacz"];
				$item->dmgmin = rand($tier * 6, $tier * 7);
				$item->dmgmax = rand($tier * 8, $tier * 9);
				$item->attackspeed = (rand(95, 110)/100);
				$item->critchance = (rand(50,60)/10);
				break;
			case 'axe': $itemNames = ["Siekierka", "Topór", "Tasak", "Topór bojowy", "Tomahawk"];
				$item->dmgmin = rand($tier * 5, $tier * 8);
				$item->dmgmax = rand($tier * 8, $tier * 10);
				$item->attackspeed = (rand(95, 110)/100);
				$item->critchance = (rand(40,50)/10);
				break;
			case 'sword2H': $itemNames = ["Długi miecz", "Wielki miecz", "Dwuręczny miecz"];
				$item->dmgmin = rand($tier * 12, $tier * 13);
				$item->dmgmax = rand($tier * 14, $tier * 15);
				$item->attackspeed = (rand(90, 110)/100);
				$item->critchance = (rand(50,60)/10);
				break;
			case 'mace2H': $itemNames = ["Berdysz", "Pika", "Halabarda", "Glewia"];
				$item->dmgmin = rand($tier * 13, $tier * 14);
				$item->dmgmax = rand($tier * 15, $tier * 16);
				$item->attackspeed = (rand(95, 110)/100);
				$item->critchance = (rand(50,60)/10);
				break;
			case 'axe2H': $itemNames = ["Wielki topór", "Topór dwuręczny"];
				$item->dmgmin = rand($tier * 10, $tier * 13);
				$item->dmgmax = rand($tier * 14, $tier * 16);
				$item->attackspeed = (rand(95, 110)/100);
				$item->critchance = (rand(40,50)/10);
				break;
			case 'dagger': $itemNames = ["Kozik", "Nożyk", "Nóż", "Sztylet", "Kolec"];
				$item->dmgmin = rand($tier * 3, $tier * 5);
				$item->dmgmax = rand($tier * 5, $tier * 6);
				$item->attackspeed = (rand(120, 140)/100);
				$item->critchance = (rand(60,80)/10);
				break;
			case 'bow': $itemNames = ["Krótki łuk", "Łuk myśliwski", "Długi łuk"];
				$item->dmgmin = rand($tier * 7, $tier * 9);
				$item->dmgmax = rand($tier * 10, $tier * 11);
				$item->attackspeed = (rand(90, 110)/100);
				$item->critchance = (rand(60,70)/10);
				break;
			case 'scepter': $itemNames = ["Kostur", "Berło"];
				$item->dmgmin = rand($tier * 5, $tier * 6);
				$item->dmgmax = rand($tier * 7, $tier * 8);
				$item->attackspeed = (rand(110, 120)/100);
				$item->critchance = (rand(50,60)/10);
				break;
			case 'wand': $itemNames = ["Różdżka"];
				$item->dmgmin = rand($tier * 5, $tier * 6);
				$item->dmgmax = rand($tier * 7, $tier * 8);
				$item->attackspeed = (rand(110, 120)/100);
				$item->critchance = (rand(50,60)/10);
				break;
			case 'staff': $itemNames = ["Laska"];
				$item->dmgmin = rand($tier * 12, $tier * 14);
				$item->dmgmax = rand($tier * 14, $tier * 16);
				$item->attackspeed = (rand(100, 120)/100);
				$item->critchance = (rand(50,60)/10);
				break;
			case 'strChest': $itemNames = ["Kolczuga", "Zbroja płytowa", "Ciężka zbroja"];
				$item->armor = rand($tier * 12, $tier * 15);
				$item->magicdefense = rand($tier * 2, $tier * 4);
				$item->movepenalty = -0.3;
				break;
			case 'dexChest': $itemNames = ["Płaszcz", "Płaszcz myśliwski", "Lekki pancerz"];
				$item->armor = rand($tier * 9, $tier * 11);
				$item->magicdefense = rand($tier * 3, $tier * 6);
				$item->movepenalty = -0.15;
				break;
			case 'intChest': $itemNames = ["Szaty maga", "Szata", "Koszula"];
				$item->armor = rand($tier * 5, $tier * 8);
				$item->magicdefense = rand($tier * 4, $tier * 8);
				$item->movepenalty = 0;
				break;
			case 'strShield': $itemNames = ["Tarcza"];
				$item->armor = rand($tier * 10, $tier * 12);
				$item->magicdefense = rand($tier * 2, $tier * 4);
				$item->movepenalty = -0.2;
				break;
			case 'dexShield': $itemNames = ["Puklerz"];
				$item->armor = rand($tier * 6, $tier * 8);
				$item->magicdefense = rand($tier * 3, $tier * 5);
				$item->movepenalty = -0.1;
				break;
			case 'intShield': $itemNames = ["Osłona maga"];
				$item->armor = rand($tier * 3, $tier * 6);
				$item->magicdefense = rand($tier * 4, $tier * 6);
				$item->movepenalty = 0;
				break;
			case 'dexOff': $itemNames = ["Strzały", "Kołczan"];
				$item->dmgmin = rand($tier * 2, $tier * 4);
				$item->dmgmax = rand($tier * 4, $tier * 5);
				break;
			case 'strBelt': $itemNames = ["Wzmacniany pas"];
				$item->armor = rand($tier * 3, $tier * 5);
				$item->movepenalty = -0.15;
				break;
			case 'dexBelt': $itemNames = ["Skórzany pas"];
				$item->armor = rand($tier * 2, $tier * 4);
				$item->movepenalty = -0.1;
				break;
			case 'intBelt': $itemNames = ["Pas alchemika"];
				$item->armor = rand($tier * 1, $tier * 3);
				$item->movepenalty = 0;
				break;
			case 'strGloves': $itemNames = ["Rękawice płytowe"];
				$item->armor = rand($tier * 3, $tier * 5);
				$item->magicdefense = rand($tier * 1, $tier * 2);
				$item->movepenalty = -0.15;
				break;
			case 'dexGloves': $itemNames = ["Rękawiczki", "Skórzane rękawice"];
				$item->armor = rand($tier * 2, $tier * 4);
				$item->magicdefense = rand($tier * 2, $tier * 3);
				$item->movepenalty = -0.1;
				break;
			case 'intGloves': $itemNames = ["Aksamitne rękawice", "Rękawice maga"];
				$item->armor = rand($tier * 1, $tier * 3);
				$item->magicdefense = rand($tier * 3, $tier * 4);
				$item->movepenalty = 0;
				break;
			case 'strRing': $itemNames = ["Pierścień wojownika"];
				$item->kondycja = rand($tier * 1, $tier * 3);
				break;
			case 'dexRing': $itemNames = ["Pierścień łotra"];
				$item->movepenalty = (rand(10,30) / 100);
				break;
			case 'intRing': $itemNames = ["Pierścień maga"];
				$item->wiedza = rand($tier * 1, $tier * 3);
				break;
			case 'strBoots': $itemNames = ["Wzmacniane buty", "Nogawice płytowe"];
				$item->armor = rand($tier * 3, $tier * 5);
				$item->magicdefense = rand($tier * 1, $tier * 2);
				$item->movepenalty = -0.15;
				break;
			case 'dexBoots': $itemNames = ["Skórzane buty"];
				$item->armor = rand($tier * 2, $tier * 4);
				$item->magicdefense = rand($tier * 2, $tier * 3);
				$item->movepenalty = -0.1;
				break;
			case 'intBoots': $itemNames = ["Trzewiczki", "Inkrustrowane buty"];
				$item->armor = rand($tier * 1, $tier * 3);
				$item->magicdefense = rand($tier * 3, $tier * 4);
				$item->movepenalty = 0;
				break;
			default: 
				break;
		}
		$nameRoll = rand(0, count($itemNames) - 1);
		$item->name = $itemNames[$nameRoll];
		
		// TODO RANDOM MODS GENERATION
		/*if($item->rarity != "normal")
		{
			$modsList = ['ogien','woda','powietrze','ziemia','fizyczny','sila','zwinnosc','celnosc','kondycja','inteligencja','wiedza','charyzma','szczescie','ruch','pancerz','critchance','dodge'];
			$modRoll = rand(0, count($modsList) - 1);
			switch($modsList[$modRoll])
			{
				case 'ogien':
					if($item->rarity == "magic")
					{
						$item->name = $item->name . " płomyka";
					}
					
			}
		}*/
		/*$modsNamesMagic = ['płomyka','kropli','podmuchu'];
		$modsNamesRare = ['ogniska','deszczu','zefiru'];
		$modsNamesLegendary = ['inferno','ulewy'];*/
		//random amount of mods depending on rarity?
		//$modRoll = rand(0, count($modsList) - 1);	
		
		// FOTO GENERATION
		if($item->rarity != "legendary")
		{
			$item->foto = "" . $item->subtype . $tier;
		}
		else
		{
			$item->foto = "legendary/" . $item->subtype . $tier;
		}
		
		// PRICE GENERATION
		switch($item->rarity)
		{
			case 'normal': $multiplier = 1;
				break;
			case 'magic': $multiplier = 3;
				break;
			case 'rare': $multiplier = 7;
				break;
			case 'legendary': $multiplier = 20;
				break;
		}
		$item->price = rand(3,7) * $tier * $multiplier;
		
		//TODO unset variables
		
		return $item;
	}
	function drawBlankItem($slot, $divID)
	{
		//"Normal" blanks
		if($slot != "backpack" or $slot != "shop")
		{
			$fotoPath = "url(gfx/eq_slots/" . $slot . "_slot_000000.png)";
			echo "<div class='fotoContainer2 blank' id='" .$divID. "' style='background-image: " . $fotoPath . ";'></div>";
			unset($fotoPath); 
		}
		//Blanks with pre-filled image (for equipment)
		else
		{
			echo "<div class='fotoContainer2' id='" .$divID. "'></div>";
		}
	}
	function drawEquipment(Player $player)
	{
		echo "<div id='equipmentOuter'>";
		echo "<div id='equipmentInner'>";
		
		//Iterates through all the player equipment slots
		foreach($player->equipment as $slot => $item)
		{			
			//There is no item in that slot, we draw a blank image
			if($item == "")
			{
				//Echoes out a div with the slot name, e.g. helmet, chest
				echo "<div class='itemSlot arrow equipment blank' id='$slot'>";
				drawBlankItem($slot, $slot);
				echo "</div>";
			}
			//We draw the item depending on rarity
			else 
			{
				//Echoes out a div with that slot name, e.g. 1, 2
				$rarity = $item->rarity;
				echo "<div class='itemSlot arrow $rarity equipment' id='$slot'>";
				$item->drawFoto($slot);
				$item->drawHover();
				echo "</div>";
				unset($rarity);
			}
		}		
			
		echo "</div>";
		echo "</div>";
	}
	function drawBackpack(Player $player)
	{
		echo "<div id='backpackOuter'>";
		echo "<div id='backpackInner'>";
		
		//Iterates through all the player backpack slots
		foreach($player->backpack as $slot => $item)
		{
			//There is no item at that backpack slot, we draw a blank image
			if($item == "")
			{
				//Echoes out a div with that slot name (EMPTY), e.g. slot1, slot2
				echo "<div class='itemSlot arrow backpack blank' id='slot$slot'>";
				drawBlankItem("backpack", $slot);
				echo "</div>";
			}
			//We draw the item depending on rarity
			else 
			{
				//Echoes out a div with that slot name WITH AN ITEM INSIDE, e.g. slot1, slot2
				$rarity = $item->rarity;
				
				echo "<div class='itemSlot arrow $rarity backpack' id='slot$slot'>";
					$item->drawFoto($slot);
					//Drawing hover with comparison to equipped item
					if($player->equipment[$item->slot] != ""){
						$item->drawHoverCompare($player->equipment[$item->slot]);
					}
					//Drawing normal hover
					else{
						$item->drawHover();
					}
				echo "</div>";
				
				unset($rarity);
			}
		}
		
		echo "</div>";
		echo "</div>";
	}
	
?>