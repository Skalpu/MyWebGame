<?php   
	
	class Player
	{
		public $id;
		public $username;
		
		public $plec;
		public $rasa;
		public $klasa;
		public $foto;
		
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
	}
	
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
	
	function get_current_time()
    {
        $conn = connectDB();
        $nowSTR = get_value($conn, "SELECT NOW()"); 
        $conn->close();
        
        return strtotime($nowSTR);
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
    
	function update_logic(Player $player)
    {
		$id = $player->id;

		//Gets the date of last updates
        $last_updateSTR = get_stat('last_update','users',$id); 
        $last_update = strtotime($last_updateSTR);
		$wyprawa_untilSTR = get_stat('wyprawa_until','users',$id);
		$wyprawa_until = strtotime($wyprawa_untilSTR);
        $now_date = get_current_time();
		        
        //Calculates due updates
        $secs = $now_date - $last_update;
        $iloscUpdatow = $secs/10;
        
		//Do an update
        if ($iloscUpdatow >= 1)
        {
			//Gets current statistics
			$conn = connectDB();
			$result = $conn->query("SELECT hp,maxhp,mana,maxmana,zloto,krysztaly FROM users WHERE id = $id");
			$row = mysqli_fetch_row($result);
	
            $hp = $row[0];
            $maxhp = $row[1];
			$mana = $row[2];
			$maxMana = $row[3];
            
			//HP and mana regen
            if (($hp + $iloscUpdatow) > $maxhp)			
			{
				$hp = $maxhp;
			}
            else										
			{
				$hp += $iloscUpdatow;
			}
			
			if (($mana + $iloscUpdatow) > $maxMana)		
			{
				$mana = $maxMana;
			}
			else										
			{
				$mana += $iloscUpdatow;
			}
            
            //Gold income
			$zloto = $row[4];
            $zloto += $iloscUpdatow;
			
            //Crystal income
            $krysztaly = $row[5];
            $krysztaly += $iloscUpdatow;
			
			//Sets new statistics in DB
            $conn->query("UPDATE users SET hp=$hp, mana=$mana, zloto=$zloto, krysztaly=$krysztaly, last_update=NOW() WHERE id=$id");
			$conn->close();
			//Sets new statistics in SESSION
			$player->hp = $hp;
			$player->mana = $mana;
			$player->zloto = $zloto;
			$player->krysztaly = $krysztaly;
			
			if($wyprawa_until < $now_date)
			{
				//TODO
				//header("Location: wyprawa.php");
				//exit;
			}
        }
    }
	
	
	
	
	function drawHealthBar(Player $player)
    {
		$current = round($player->hp);
		$max = round($player->maxhp);
		$percent = round( ($current/$max) * 100 );
		$color = color($current, $max);
		
		echo "<div id='healthBar'>";
			echo "<div id='outerHealthBar'>";
				echo "<div id='innerHealthBar' style='width: " .$percent. "%; background-color: " .$color. ";'>";
			
				echo "</div>";
			echo "</div>";
		echo "</div>";
		
		echo "<div id='healthBarText'>";
			echo "HP: " . $current . "/" . "$max";
		echo "</div>";
    }
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
?>