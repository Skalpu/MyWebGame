<?php   

	class Item
	{
		public $id;
		public $name;
		public $equipped = false;
		public $owner;
		
		public $rarity;
		public $tier;
		public $type;
		public $subtype;
		public $photo;
		
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
		
		//TODO
		public function saveToDB()
		{
			$conn = connectDB();
			//INSERT INTO while saving ID
			//$this->id = last_id
			$conn->close();
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
		
		public $hp;
		public $maxhp;
		public $mana;
		public $maxmana;
		public $zloto;
		public $krysztaly;
		
		public $unread;
		public $last_update;
		
		public $inventory = [
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
		//TODO: finish
		public $equipment = [
			'helmet' => "",
			'amulet' => "",
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
			
			$this->updateHP();
			$this->updateMana();
			$this->updateGlobally();
			
			$item->equipped = true;
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
			
			$this->updateHP();
			$this->updateMana();
			$this->updateGlobally();
			
			$item->equipped = false;
		}
		public function addToInventory(Item $item)
		{
			for($i = 0; $i < 15; $i++)
			{
				if($this->inventory[$i] == "")
				{
					$this->inventory[$i] = $item;
					$item->saveToDB();
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
		
		//HOW TO USE UPDATES
		//BEFORE FIGHT USE updateLocally - to count the user's gold, have him regen hp etc
		//AFTER FIGHT USE updateGlobally - to save his new hp, increased/decreased gold etc in database.
		
		//UPDATES THE OBJECT WITHIN SESSION (hp regen, gold income etc), BASED ON LAST DATABASE UPDATE
		//USE ON EVERY RELOAD, BEFORE FIGHTS (TO HAVE THE LATEST GOLD AMOUNT)
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
		
		//UPDATES EVERYTHING AND SAVES IT INTO DATABASE
		//USE AFTER EVENTS(ATTACKS, STAT INCREASES ETC), THAT PERMANENTLY CHANGE THE CHARACTER
		public function updateGlobally()
		{
			//$this->updateLocally();
			
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
			echo "<div class='fotoContainer' style='background-image: " . $fotoPath . ";'></div>";
			
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
		public function drawHP()
		{
			$current = round($this->hp);
			$max = round($this->maxhp);
			$percent = round( ($current/$max) * 100);
			$color = color($current, $max);
		
			echo "<div class='hp bar'>";
				echo "<div class='outerBar'>";
					echo "<div class='innerBar' style='width: " .$percent. "%; background-color: " .$color. ";'></div>";
				echo "</div>";
			echo "</div>";
		
			echo "<div class='hp barText'>";
				echo "HP: " . $current . "/" . "$max";
			echo "</div>";
			
			unset($current);
			unset($max);
			unset($percent);
			unset($color);
		}
		public function drawMP()
		{
			$current = round($this->mana);
			$max = round($this->maxmana);
			$percent = round( ($current/$max) * 100 );
		
			echo "<div class='mana bar'>";
				echo "<div class='outerBar'>";
					echo "<div class='innerBar' id='innerMana' style='width: " .$percent. "%;'></div>";
				echo "</div>";
			echo "</div>";
		
			echo "<div class='mana barText'>";
				echo "MP: " .$current. "/" .$max;
			echo "</div>";
			
			unset($current);
			unset($max);
			unset($percent);
		}
		public function drawEXP()
		{
			$current = round($this->experience);
			$max = round($this->experiencenext);
			$percent = round( ($current/$max) * 100);
		
			echo "<div class='exp bar'>";
				echo "<div class='outerBar'>";
					echo "<div class='innerBar' id='innerExp' style='width: " .$percent. "%;'></div>";
				echo "</div>";
			echo "</div>";
		
			echo "<div class='exp barText'>";
				echo "EXP: " .$current. "/" .$max;
			echo "</div>";
			
			unset($current);
			unset($max);
			unset($percent);
		}
		
		
		
		
		//Sets the class object by downloading all player data from SQL server - use for existing players
		public function __construct($id)
		{
			$conn = connectDB();
			$result = $conn->query("SELECT * FROM users WHERE id='$id'");
			$row = mysqli_fetch_assoc($result);
			$conn->close();
			
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
		$player->drawMail();
		$player->drawGold();
		$player->drawCrystals();
		$player->drawHP();
		$player->drawMP();
		$player->drawEXP();
	}
	function drawDivider()
	{
		echo "<div class='splitter'></div>";
	}
	
?>