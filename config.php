<?php   

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
		public function updateLocally()
		{
			$now = time();
			
			if(isset($this->last_update))
			{
				//Was saved in session
				$last = $this->last_update;
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
		public function updateDB()
		{
			$this->updateLocally();
		}
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
		
		
		//Sets the class object by downloadinng all player data from SQL server - use for existing players
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
?>