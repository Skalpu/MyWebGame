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
			
			$conn->query("INSERT INTO items (name, rarity, tier, slot, type, subtype, dmgmin, dmgmax, attackspeed, critchance, armor, dmgogien, dmgwoda, dmgpowietrze, dmgziemia, sila, zwinnosc, celnosc, kondycja, inteligencja, wiedza, charyzma, szczescie) VALUES ('$name', '$rarity', '$tier', '$slot', '$type', '$subtype', '$dmgmin', '$dmgmax', '$attackspeed', '$critchance', '$armor', '$dmgogien', '$dmgwoda', '$dmgpowietrze', '$dmgziemia', '$sila', '$zwinnosc', '$celnosc', '$kondycja', '$inteligencja', '$wiedza', '$charyzma', '$szczescie')");
			$this->id = $conn->insert_id;
			
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
			
			$conn->close();
			unset($conn);
		}
		public function drawFoto($divID)
		{
			$fotoPath = "url(gfx/itemy/" . $this->foto . ".png)";
			echo "<div class='fotoContainer2' id='" .$divID. "' style='background-image: " . $fotoPath . ";'></div>";
			unset($fotoPath);
		}
	}
	class Player
	{
		public $id;
		public $username;
		
		public $plec;
		public $rasa;
		public $klasa;
		public $foto;
		
		public $level;
		public $experience;
		public $experiencenext;
		public $remaining;
		
		public $sila;
		public $zwinnosc;
		public $celnosc;
		public $kondycja;
		public $inteligencja;
		public $wiedza;
		public $charyzma;
		public $szczescie;
		
		//DB stats
		public $hp;
		public $maxhp;
		public $mana;
		public $maxmana;
		public $zloto;
		public $krysztaly;		
		public $unread;
		public $last_update;
		
		//Combat settings
		public $side;
		public $did_move;
		public $time_remaining;
		public $spells_only;
		
		//Combat stats
		public $dmgmin;
		public $dmgmax;
		public $attackspeed;
		public $critchance;		
		public $armor;
		
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
			
			if(isset($this->last_update))
			{
				//Was saved in session
				if(is_int($this->last_update))
				{
					$last = $this->last_update;
				}
				else
				{
					$last = strtotime($this->last_update);
				}
			}
			else
			{
				//Wasn't saved, read from SQL
				$conn = connectDB();
				$id = $this->id;
				$last = get_value($conn, "SELECT last_update FROM users WHERE id=$id");
				$last = strtotime($last);
				$this->last_update = $last;
				$conn->close();
				unset($id);
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
			
			$conn=connectDB();
			$conn->query("UPDATE users SET level=$level, experience=$experience, experiencenext=$experiencenext, remaining=$remaining, sila=$sila, zwinnosc=$zwinnosc, celnosc=$celnosc, kondycja=$kondycja, inteligencja=$inteligencja, wiedza=$wiedza, charyzma=$charyzma, szczescie=$szczescie, hp=$hp, maxhp=$maxhp, mana=$mana, maxmana=$maxmana, zloto=$zloto, krysztaly=$krysztaly, last_update=NOW() WHERE id=$id");
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
			$this->hp += $times;
			if($this->hp > $this->maxhp)
			{
				$this->hp = $this->maxhp;
			}
		}
		public function mpRegen($times)
		{
			$this->mana += $times;
			if($this->mana > $this->maxmana)
			{
				$this->mana = $this->maxmana;
			}
		}
		public function goldRegen($times)
		{
			$this->zloto += $times;
		}
		public function crystalsRegen($times)
		{
			$this->krysztaly += $times;
		}
		
		
		public function drawFoto()
		{
			$fotoPath = "url(gfx/portrety/" . $this->foto . ".jpg)";
			echo "<div class='fotoContainer' id='" .$this->id. "' style='background-image: " . $fotoPath . ";'></div>";
		
			unset($fotoPath);
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
		
			echo "<div class='bar " .$nazwa. "' style='" .$style. "'>";
				echo "<div class='outerBar'>";
					echo "<div class='innerBar' style='width: " .$percent. "%; background-color: " .$color. ";'></div>";
				echo "</div>";
			echo "</div>";
		
			echo "<div class='barText " .$nazwa. "' style='" .$style. "'>";
				echo "HP: " . $current . " / " . "$max";
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
		
			echo "<div class='bar " .$nazwa. "' style='" .$style. "'>";
				echo "<div class='outerBar'>";
					echo "<div class='innerBar' id='innerMana' style='width: " .$percent. "%;'></div>";
				echo "</div>";
			echo "</div>";
		
			echo "<div class='barText " .$nazwa. "' style='" .$style. "'>";
				echo "MP: " .$current. " / " .$max;
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
		
			echo "<div class='bar " .$nazwa. "' style='" .$style. "'>";
				echo "<div class='outerBar'>";
					echo "<div class='innerBar' id='innerExp' style='width: " .$percent. "%;'></div>";
				echo "</div>";
			echo "</div>";
		
			echo "<div class='barText " .$nazwa. "' style='" .$style. "'>";
				echo "EXP: " .$current. " / " .$max;
			echo "</div>";
			
			unset($current);
			unset($max);
			unset($percent);
			unset($nazwa);
			unset($style);
		}
		
		
		//Sets the class object by downloading all player data from SQL server - use for existing players
		public static function withID($id)
		{
			$instance = new self();
			$instance->loadByID($id);
			return $instance;
		}
		protected function loadByID($id)
		{
			$conn = connectDB();
			$result = $conn->query("SELECT * FROM users WHERE id='$id'");
			$row = mysqli_fetch_assoc($result);
			$conn->close();
			unset($conn);
			
			$this->id = $id;
			$this->username = $row['username'];
			$this->plec = $row['plec'];
			$this->rasa = $row['rasa'];
			$this->klasa = $row['klasa'];
			$this->foto = $row['foto'];
			
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
			
			$this->dmgmin = $row['dmgmin'];
			$this->dmgmax = $row['dmgmax'];
			$this->attackspeed = $row['attackspeed'];
			$this->critchance = $row['critchance'];
			$this->armor = $row['armor'];
			$this->loadItems($id);	
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
				if($row[$slotName] != "NULL")
				{
					$this->backpack[$i] = Item::withID($row[$slotName]);
				}
			}
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
	function drawGame(Player $player)
	{
		echo "<div id='bary'>";
		$player->drawMail();
		$player->drawGold();
		$player->drawCrystals();
		$player->drawHP("mainHP", "");
		$player->drawMP("mainMP", "");
		$player->drawEXP("mainEXP", "");
		echo "</div>";
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
		else if($rarityRoll >= $legendaryMin and $rarityRoll <= $legendaryMax)
		{
			$item->rarity = "legendary";
		}
		
		// SLOT GENERATION
		$itemSlots = ['helmet', 'amulet', 'lefthand', 'chest', 'righthand', 'belt', 'gloves', 'ring', 'boots'];
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
		
		// NAME GENERATION
		switch($item->subtype)
		{
			case 'strHelmet': $itemNames = ["Hełm żołdaka", "Gladiatorski hełm", "Zamknięty hełm", "Rogaty hełm"];
				break;
			case 'dexHelmet': $itemNames = ["Kaptur", "Bandana", "Przepaska"];
				break;
			case 'intHelmet': $itemNames = ["Diadem", "Obręcz"];
				break;
			case 'strAmulet': $itemNames = ["Amulet"];
				break;
			case 'dexAmulet': $itemNames = ["Amulet"];
				break;
			case 'intAmulet': $itemNames = ["Amulet"];
				break;
			case 'sword': $itemNames = ["Krótki miecz", "Miecz półtoraręczny", "Rapier", "Szabla"];
				break;
			case 'mace': $itemNames = ["Morgensztern", "Pałka", "Młot", "Młot bitewny", "Buława ceremonialna", "Skałołamacz"];
				break;
			case 'axe': $itemNames = ["Siekierka", "Topór", "Tasak", "Topór bojowy", "Tomahawk"];
				break;
			case 'sword2H': $itemNames = ["Długi miecz", "Wielki miecz", "Dwuręczny miecz"];
				break;
			case 'mace2H': $itemNames = ["Berdysz", "Pika", "Halabarda", "Glewia"];
				break;
			case 'axe2H': $itemNames = ["Wielki topór", "Topór dwuręczny"];
				break;
			case 'dagger': $itemNames = ["Kozik", "Nożyk", "Nóż", "Sztylet", "Kolec"];
				break;
			case 'bow': $itemNames = ["Krótki łuk", "Łuk myśliwski", "Długi łuk"];
				break;
			case 'scepter': $itemNames = ["Kostur", "Berło"];
				break;
			case 'wand': $itemNames = ["Różdżka"];
				break;
			case 'staff': $itemNames = ["Laska"];
				break;
			case 'strChest': $itemNames = ["Kolczuga", "Zbroja płytowa", "Ciężka zbroja"];
				break;
			case 'dexChest': $itemNames = ["Płaszcz", "Płaszcz myśliwski", "Lekki pancerz"];
				break;
			case 'intChest': $itemNames = ["Szaty maga", "Szata", "Koszula"];
				break;
			case 'strShield': $itemNames = ["Tarcza"];
				break;
			case 'dexShield': $itemNames = ["Puklerz"];
				break;
			case 'intShield': $itemNames = ["Puklerz"];
				break;
			case 'dexOff': $itemNames = ["Strzały", "Kołczan"];
				break;
			case 'strBelt': $itemNames = ["Wzmacniany pas"];
				break;
			case 'dexBelt': $itemNames = ["Skórzany pas"];
				break;
			case 'intBelt': $itemNames = ["Pas alchemika"];
				break;
			case 'strGloves': $itemNames = ["Rękawice płytowe"];
				break;
			case 'dexGloves': $itemNames = ["Rękawiczki", "Skórzane rękawice"];
				break;
			case 'intGloves': $itemNames = ["Aksamitne rękawice", "Rękawice maga"];
				break;
			case 'strRing': $itemNames = ["Pierścień"];
				break;
			case 'dexRing': $itemNames = ["Pierścień"];
				break;
			case 'intRing': $itemNames = ["Pierścień"];
				break;
			case 'strBoots': $itemNames = ["Wzmacniane buty", "Nogawice płytowe"];
				break;
			case 'dexBoots': $itemNames = ["Skórzane buty"];
				break;
			case 'intBoots': $itemNames = ["Trzewiczki", "Inkrustrowane buty"];
				break;
			default: 
				break;
		}
		$nameRoll = rand(0, count($itemNames) - 1);
		$item->name = $itemNames[$nameRoll];
		
		// STAT GENERATION
		
		// RANDOM MODS GENERATION
		
		// FOTO GENERATION
		if($item->rarity != "legendary")
		{
			$item->foto = "" . $item->subtype . $tier;
		}
		else
		{
			$item->foto = "legendary/" . $item->subtype . $tier;
		}
		
		return $item;
	}
	function drawBlankItem($slot, $divID)
	{
		if($slot != "backpack")
		{
			$fotoPath = "url(gfx/eq_slots/" . $slot . "_slot_000000.png)";
			echo "<div class='fotoContainer2' id='" .$divID. "' style='background-image: " . $fotoPath . ";'></div>";
			unset($fotoPath); 
		}
		else
		{
			echo "<div class='fotoContainer2' id='" .$divID. "'></div>";
		}
	}
	function drawEquipment(Player $player)
	{
		echo "<div id='equipment'>";
		
		//Iterates through all the player equipment slots
		foreach($player->equipment as $slot => $item)
		{			
			//There is no item in that slot, we draw a blank image
			if($item == "")
			{
				//Echoes out a div with the slot name, e.g. helmet, chest
				echo "<div class='itemSlot equipment blank' id='$slot'>";
				drawBlankItem($slot, $slot);
				echo "</div>";
			}
			//We draw the item depending on rarity
			else 
			{
				//Echoes out a div with that slot name, e.g. 1, 2
				$rarity = $item->rarity;
				echo "<div class='itemSlot $rarity equipment' id='$slot'>";
				$item->drawFoto($slot);
				echo "</div>";
				unset($rarity);
			}
		}		
			
		echo "</div>";
	}
	function drawBackpack(Player $player)
	{
		echo "<div id='backpack'>";
		
		//Iterates throught all the player backpack slots
		foreach($player->backpack as $slot => $item)
		{
			//There is no item at that backpack slot, we draw a blank image
			if($item == "")
			{
				//Echoes out a div with that slot name (EMPTY), e.g. bp1, bp2
				echo "<div class='itemSlot backpack blank' id='bp$slot'>";
				drawBlankItem("backpack", $slot);
				echo "</div>";
			}
			//We draw the item depending on rarity
			else 
			{
				//Echoes out a div with that slot name WITH AN ITEM INSIDE, e.g. bp1, bp2
				$rarity = $item->rarity;
				echo "<div class='itemSlot $rarity backpack' id='bp$slot'>";
				$item->drawFoto($slot);
				echo "</div>";
				unset($rarity);
			}
		}
		
		echo "</div>";
	}
	
?>