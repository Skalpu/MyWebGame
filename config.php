<?php   
	
	abstract class Character
	{
		public $username;
		
		public $sila;
		public $zwinnosc;
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
    function debug_to_console( $data ) 
    {
        if ( is_array( $data ) )
        $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
        else
        $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

        echo $output;
    }
	
	
	
    
    


    function get_value ($my_SQLI_Connection, $sql_code)
    {
        $result = $my_SQLI_Connection->query($sql_code);
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
	function insert_time($sekundy, $field, $userID)
	{
		$conn = connectDB();
		
		$time = date("Y-m-d H:i:s", $sekundy);
		$time = $conn->real_escape_string($time);
		$field = $conn->real_escape_string($field);
		$userID = $conn->real_escape_string($userID);
		
		$conn->query("UPDATE users SET $field = '$time' WHERE id = $userID");

	}
    function set_stat ($table, $stat, $value, $ID)
    {
        $conn = connectDB();
        
        $escapedStat = $conn->real_escape_string($stat);
        $escapedID = $conn->real_escape_string($ID);
        $escapedValue = $conn->real_escape_string($value);
        $escapedTable = $conn->real_escape_string($table);
        
        $conn->query("UPDATE $escapedTable SET $escapedStat = '$escapedValue' WHERE id = $escapedID");
        $conn->close();
    }



    function login_check()
    {   
        session_start();
        
        if (isset($_SESSION['authenticated']) && $_SESSION['authenticated'] == true)
        {
            //user jest zalogowany
        }
        else
        {
            header('Location: login.php');
        }
    }
    function update_logic($userID)
    {
		//POBIERA CZAS OSTATNIEGO UPDATE
        $last_updateSTR = get_stat('last_update','users',$userID); 
        $last_date = strtotime($last_updateSTR);
		$wyprawa_untilSTR = get_stat('wyprawa_until','users',$userID);
		$wyprawa_until = strtotime($wyprawa_untilSTR);
        $now_date = get_current_time();
		        
        //LICZY ILOŚĆ NALEŻNYCH UPDATÓW
        $secs = $now_date - $last_date;
        $iloscUpdatow = $secs/10;
        
        if ($iloscUpdatow >= 1)
        {
			$conn = connectDB();
			$eUserID = $conn->real_escape_string($userID);
			$result = $conn->query("SELECT hp,maxhp,mana,maxmana,zloto,krysztaly FROM users WHERE id = $userID");
			$row = mysqli_fetch_row($result);
			
            $hp = $row[0];
            $maxhp = $row[1];
			$mana = $row[2];
			$maxMana = $row[3];
            
			//ODNOWA HP I MANY
            if (($hp + $iloscUpdatow) > $maxhp)			{$hp = $maxhp;}
            else										{$hp += $iloscUpdatow;}
			if (($mana + $iloscUpdatow) > $maxMana)		{$mana = $maxMana;}
			else										{$mana += $iloscUpdatow;}
            
            //PRZYROST GOLDA
			$zloto = $row[4];
            $zloto += $iloscUpdatow;
            //PRZYROST KRYSZTALOW
            $krysztaly = $row[5];
            $krysztaly += $iloscUpdatow;
			
			
            $conn->query("UPDATE users SET hp=$hp, mana=$mana, zloto=$zloto, krysztaly=$krysztaly, last_update=NOW() WHERE id=$eUserID");
			$conn->close();
			
			
			if($wyprawa_until < $now_date)
			{
				//header("Location: wyprawa.php");
				//exit;
			}
        }

    }
    function drawHealthBar($userID)
    {
		$player = sum_player($userID);
        $current = $player['hp'];
        $max = $player['kondycja'] * 10;
        
        $height = 15;
        $width = 300;
        $text_height = '8%';
        
        $percent = round(($current/$max)*100);
        $color = color($current, $max);
        return "<div id='healthBar'><div style='border-radius: 0px; -webkit-box-shadow: inset 0 2px 5px #AAA; border: 2px solid; background: SeaShell; width: ".$width."px; height: ".$height."px;'><div id='innerHP' style='width: " . $percent . "%; background-color: " . $color . "; border-radius: 0px; height:".$height."px;'><div id='textHP' style='position:absolute; left:0; top:".$text_height."; width:100%; text-align: center;'>"."HP: ".$current."/".$max."</div></div></div></div>";
    }
    function drawManaBar($userID)
    {
 		$player = sum_player($userID);
        $current = $player['mana'];
        $max = $player['wiedza'] * 10;
        
        $height = 15;
        $width = 300;
        $text_height = '8%';
        
        $percent = round(($current/$max)*100);
        $color = 'mediumPurple';
        
        return "<div id='manaBar'><div style='border-radius: 0px; -webkit-box-shadow: inset 0 2px 5px #AAA; border: 2px solid; background: SeaShell; width: ".$width."px; height: ".$height."px;'><div id='innerMana' style='width: " . $percent . "%; background-color: " . $color . "; border-radius: 0px; height:".$height."px;'><div id='textMana' style='position:absolute; left:0; top:".$text_height."; width:100%; text-align: center;'>"."MP: ".$current."/".$max."</div></div></div></div>";
    }
    function drawExpBar($userID)
    {
        $current = get_stat('exp', 'users', $userID);
        $max = get_stat('exp_next', 'users', $userID);
        
        $height = 15;
        $width = 300;
        $text_height = '8%';
        
        $percent = round(($current/$max)*100);
        $color = 'gold';
        
        return "<div id='expBar'><div style='border-radius: 0px; -webkit-box-shadow: inset 0 2px 5px #AAA; border: 2px solid; background: SeaShell; width: ".$width."px; height: ".$height."px;'><div id='innerExp' style='width: " . $percent . "%; background-color: " . $color . "; border-radius: 0px; height:".$height."px;'><div id='textExp' style='position:absolute; left:0; top:".$text_height."; width:100%; text-align: center;'>"."Exp: ".$current."/".$max."</div></div></div></div>";
    }
	function drawGold($userID)
	{
		$zloto = get_stat('zloto','users',$userID);
		$kod = "<div id='zlotoContainer'><img style='height: 100%' src='/gfx/gold.png'><div id='zlotoTekst'>" . $zloto . "</div></div>";
		return $kod;
	}
	function drawCrystals($userID)
	{
		$krysztaly = get_stat('krysztaly','users',$userID);
		$kod = "<div id='krysztalyContainer'><img style='height: 100%' src='/gfx/crystals.png'><div id='krysztalyTekst'>" . $krysztaly . "</div></div>";
		return $kod;
	}
	/*function drawWyprawa($userID)
	{
		$until = get_stat('wyprawa_until','users',$userID);
		{
			if ($until != null)
			{
				echo "<div id='wyprawaContainer' style='width: 100px; height: 100px; border: 2px solid red;'>";
				echo "<img src='wyprawaImage'>";
				echo "<div id='wyprawaTimer'>";
			}
		}
	}*/
	function drawMail($userID)
	{
		$unread = 0;
		$conn = connectDB();
		$result = $conn->query("SELECT msg1,msg2,msg3,msg4,msg5,msg6,msg7,msg8,msg9,msg10 FROM user_mail WHERE id = $userID");
		$IDmsg = mysqli_fetch_row($result);
		
		for($i = 0; $i < 10; $i++)
		{
			if ($IDmsg[$i] != null)
			{
				$idquery = $IDmsg[$i];
				$is_read = get_value($conn, "SELECT is_read FROM messages WHERE id = $idquery");
				if ($is_read[0] == 0)
				{
					$unread++;
				}
			}
		}
		
		$conn->close();
		$kolor = '';
		if($unread > 0)		{$kolor = "red";}
		else				{$kolor = "white";}
		
		$kod = "<a href='mail.php'><div id='mailContainer'><img style='height: 100%' src='/gfx/mail.png'><div id='mailTekst' style='color: " .$kolor . ";'>" . $unread . "</div></div></a>";
		return $kod;
	}
	function drawWyprawa($userID)
	{
		$conn = connectDB();
		$result = $conn->query("SELECT destination,wyprawa_until FROM users WHERE id=$userID");
		$row = mysqli_fetch_row($result);

		$kod = "<a href='wyprawa.php'><div id='wyprawaContainer' class='" .$row[0].	"'><img style='height: 100%' src='/gfx/wyprawa.png'><div id='wyprawaTekst' style='color: white;'>" 	.$row[1].	"</div></div></a>";
		
		$conn->close();
		return $kod;
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
	


	
    function draw_item($slot, $userID)
    {
		//SLOT SELL
        if($slot == "sell")
		{
			$id_image = "sellImage";
			$nazwa_pliku = 'gfx/eq_slots/sell.png';
			$main_typ_itemu = 0;
			$id_itemu = 0;
		}
		else
		{
			$id_itemu = get_stat($slot,'equipment',$userID);
			
			//SLOT
			if ($id_itemu == 0)
			{
				//SLOT LUŹNY
				if (strpos($slot, 'slot') !== false)
				{
					$id_image = $slot . "Image";
					$nazwa_pliku = 'gfx/eq_slots/slot.png';
					$main_typ_itemu = 0;
				}
				//SLOT EKWIPUNKU
				else
				{
					$id_image = $slot . "Image";
					$nazwa_pliku = 'gfx/eq_slots/' . $slot . '_slot_000000.png';
					$main_typ_itemu = 0;
				}
			}
			//ITEM
			else
			{
				$conn = connectDB();
				$result = $conn->query("SELECT type,main_type,image_id FROM items WHERE id = $id_itemu");
				$row = mysqli_fetch_row($result);
				$conn->close();
				
				$id_image = $slot . "Image";
				$typ_itemu = $row[0];
				$main_typ_itemu = $row[1];
				$id_zdjecia_itemu = $row[2];
				$nazwa_pliku = 'gfx/itemy/' . $typ_itemu . '_' . $id_zdjecia_itemu . '.png';
				
			}
			
		}

        return "<img class='itemImage' id='" . $id_image . "' src='" . $nazwa_pliku . "'>" . "<input type='hidden' class ='" . $main_typ_itemu ."' id='" . $slot . "' value='" .$id_itemu ."'>";
    }
    function read_item_stats($itemID)
    {
        $rezultat = '';
		$item = sum_item($itemID);

        $rezultat = $rezultat . "<span class='name " .$item['rarity'] ."'>" . $item['name'] . "</span><br>";
		$rezultat = $rezultat . "<span class='rzadkosctyp'>" . $item['rarity'] . " " . $item['type'] . "</span><br>";
		$rezultat = $rezultat . "<img src='/gfx/divider.png' style='position: relative; left: 0%; width: 300px;'><br>";
		
		if($item['main_type'] == 'lefthand')
		{
			$dps = (($item['damagemin'] + $item['damagemax'])/2 + $item['damage_ogien'] + $item['damage_woda'] + $item['damage_powietrze'] + $item['damage_ziemia']) * $item['attackspeed'] * (($item['base_crit'] + 100) / 100);
			$dps = round($dps, 1);
			
			$rezultat = $rezultat . 										 "<span class='damagemin'>" . 			$item['damagemin'] 			. "</span>";
			$rezultat = $rezultat . 										 "-<span class='damagemax'>" . 			$item['damagemax'] 			. "</span> obrażeń fizycznych<br>";
			if($item['damage_ogien'] != 0) 			{$rezultat = $rezultat . "<span class='damage_ogien'>" . 		$item['damage_ogien'] 		. "</span> obrażeń od ognia<br>";}
			if($item['damage_woda'] != 0) 			{$rezultat = $rezultat . "<span class='damage_woda'>" . 		$item['damage_woda'] 		. "</span> obrażeń od wody<br>";}
			if($item['damage_powietrze'] != 0) 		{$rezultat = $rezultat . "<span class='damage_powietrze'>" . 	$item['damage_powietrze'] 	. "</span> obrażeń od powietrza<br>";}
			if($item['damage_ziemia'] != 0) 		{$rezultat = $rezultat . "<span class='damage_ziemia'>" . 		$item['damage_ziemia'] 		. "</span> obrażeń od ziemi<br>";}
			$rezultat = $rezultat . 										 "<span class='attackspeed'>" . 		$item['attackspeed'] 		. "</span> ataków na sekundę<br>";
			$rezultat = $rezultat . 										 "<span class='dps'>" . 				$dps 						. "</span> DPS<br>";
			$rezultat = $rezultat . 										 "<span class='base_crit'>" . 			$item['base_crit'] 			. "</span>% na traf.kryt.<br>";
		}
		else
		{
			$rezultat = $rezultat . 										 "+<span class='armor'>" . 				$item['armor'] 				. "</span> pancerza<br>";
			if($item['sila'] != 0)					{$rezultat = $rezultat . "+<span class='sila'>" . 				$item['sila'] 				. "</span> siły<br>";}
			if($item['zwinnosc'] != 0)				{$rezultat = $rezultat . "+<span class='zwinnosc'>" . 			$item['zwinnosc'] 			. "</span> zwinności<br>";}
			if($item['celnosc'] != 0)				{$rezultat = $rezultat . "+<span class='celnosc'>" . 			$item['celnosc'] 			. "</span> celności<br>";}
			if($item['kondycja'] != 0)				{$rezultat = $rezultat . "+<span class='kondycja'>" . 			$item['kondycja'] 			. "</span> kondycji<br>";}
			if($item['inteligencja'] != 0)			{$rezultat = $rezultat . "+<span class='inteligencja'>" . 		$item['inteligencja'] 		. "</span> inteligencji<br>";}
			if($item['wiedza'] != 0)				{$rezultat = $rezultat . "+<span class='wiedza'>" . 			$item['wiedza'] 			. "</span> wiedzy<br>";}
			if($item['charyzma'] != 0)				{$rezultat = $rezultat . "+<span class='charyzma'>" . 			$item['charyzma'] 			. "</span> charyzmy<br>";}
			if($item['szczescie'] != 0)				{$rezultat = $rezultat . "+<span class='szczescie'>" . 			$item['szczescie'] 			. "</span> szczęścia<br>";}
			
			if($item['crit_chance'] != 0)			{$rezultat = $rezultat . "+<span class='crit_chance'>" . 		$item['crit_chance'] 		. "</span>% do szansy na traf.kryt.<br>";}
			if($item['crit_damage'] != 0)			{$rezultat = $rezultat . "+<span class='crit_damage'>" . 		$item['crit_damage'] 		. "</span>% obrażeń kryt.<br>";}
		}
        
		$rezultat = $rezultat . "<img src='/gfx/divider.png' style='position: relative; left: 0%; width: 300px;'><br>";
		$rezultat = $rezultat . "<span class='cena'>Cena: " . $item['cena'] . " szt. zł.</span><br>";
        
        return $rezultat;
    }
    function read_stats($ID,$typDanychDoWyswietlenia)
    {
		$conn = connectDB();
		
        if ($typDanychDoWyswietlenia == 'zdjecie')
        {
			$result = $conn->query("SELECT plec,rasa FROM users WHERE id=$ID");
			$row = mysqli_fetch_row($result);
			
            $plec = $row[0];
            $rasa = $row[1];
            
            if (($rasa == 'Człowiek') and ($plec == 'Mężczyzna'))      			{   $zdjecie = '/gfx/portrety/humanmale.jpg';  }
            else if (($rasa == 'Człowiek') and ($plec == 'Kobieta'))      		{   $zdjecie = '/gfx/portrety/humanfemale.jpg';  }
            else if (($rasa == 'Ork') and ($plec == 'Mężczyzna'))     			{   $zdjecie = '/gfx/portrety/orcmale.jpg';  }
            else if (($rasa == 'Ork') and ($plec == 'Kobieta'))     			{   $zdjecie = '/gfx/portrety/orcfemale.png';  }
            else if (($rasa == 'Leśny elf') and ($plec == 'Mężczyzna'))     	{   $zdjecie = '/gfx/portrety/lesnyelfmale.jpg';  }
            else if (($rasa == 'Leśny elf') and ($plec == 'Kobieta'))      		{   $zdjecie = '/gfx/portrety/lesnyelffemale.jpg';  }
            else if (($rasa == 'Krasnolud') and ($plec == 'Mężczyzna'))      	{   $zdjecie = '/gfx/portrety/krasnoludmale.jpg';  }
            else if (($rasa == 'Krasnolud') and ($plec == 'Kobieta'))      		{   $zdjecie = '/gfx/portrety/krasnoludfemale.jpg';  }
            else if (($rasa == 'Wysoki elf') and ($plec == 'Mężczyzna'))      	{   $zdjecie = '/gfx/portrety/wysokielfmale.jpg';  }
            else if (($rasa == 'Wysoki elf') and ($plec == 'Kobieta'))      	{   $zdjecie = '/gfx/portrety/wysokielffemale.jpg';  }
            
            $rezultat = "<img id='charImage' src='" .$zdjecie . "'>";
        }
        else if ($typDanychDoWyswietlenia == 'zdjeciePotwora')
        {
            $name = get_stat('name','monsters',$ID);
            $rezultat = "<img id='charImage' src='/gfx/monsters/" .$name. ".jpg'>";
        }
        else if ($typDanychDoWyswietlenia == 'podstawowe')
        {
			$result = $conn->query("SELECT username,exp,exp_next,level,plec,rasa,klasa FROM users WHERE id = $ID");
			$row = mysqli_fetch_row($result);
			
            $username = $row[0];
            $exp = $row[1];
            $exp_next = $row[2];
            $level = $row[3];
            $plec = $row[4];
            $rasa =	$row[5];
            $klasa = $row[6];
            
            $rezultat = '';
            $rezultat = $rezultat . "<span class='username'>" . $username . "</span><br>";
            $rezultat = $rezultat . "<span class='rasaklasa'>" . $rasa . " " . $klasa . "</span><br>";
        }
        else if ($typDanychDoWyswietlenia == 'statystyki')
        {
			$result = $conn->query("SELECT sila,zwinnosc,celnosc,kondycja,inteligencja,wiedza,charyzma,szczescie FROM users WHERE id = $ID");
			$row = mysqli_fetch_row($result);
			
            $sila = $row[0];
            $zwinnosc = $row[1];
            $celnosc = $row[2];
            $kondycja = $row[3];
            $inteligencja = $row[4];
            $wiedza = $row[5];
            $charyzma = $row[6];
            $szczescie = $row[7];
            
            $rezultat = '';
            $rezultat = $rezultat . "Siła: " . $sila . "<br>";
            $rezultat = $rezultat . "Zwinność: " . $zwinnosc . "<br>";
            $rezultat = $rezultat . "Celność: " . $celnosc . "<br>";
            $rezultat = $rezultat . "Kondycja: " . $kondycja . "<br>";
            $rezultat = $rezultat . "Inteligencja: " . $inteligencja . "<br>";
            $rezultat = $rezultat . "Wiedza: " . $wiedza . "<br>";
            $rezultat = $rezultat . "Charyzma: " . $charyzma . "<br>";
            $rezultat = $rezultat . "Szczęście: " . $szczescie . "<br>";
        }
        else if ($typDanychDoWyswietlenia == 'statystykiEquipment')
        {
			$czas_rundy = 5;
			$player = sum_player($ID);
			
            $rezultat = '';
            $rezultat = $rezultat . "Siła: <span class='stat'>" . $player['sila'] . "</span><br>";
            $rezultat = $rezultat . "Zwinność: <span class='stat'>" . $player['zwinnosc'] . "</span><br>";
            $rezultat = $rezultat . "Celność: <span class='stat'>" . $player['celnosc'] . "</span><br>";
            $rezultat = $rezultat . "Kondycja: <span class='stat'>" . $player['kondycja'] . "</span><br>";
            $rezultat = $rezultat . "Inteligencja: <span class='stat'>" . $player['inteligencja'] . "</span><br>";
            $rezultat = $rezultat . "Wiedza: <span class='stat'>" . $player['wiedza'] . "</span><br>";
            $rezultat = $rezultat . "Charyzma: <span class='stat'>" . $player['charyzma'] . "</span><br>";
            $rezultat = $rezultat . "Szczęście: <span class='stat'>" . $player['szczescie'] . "</span><br>";
			

			$rezultat = $rezultat . "<br>";
			if($player['weapon_type'] == 'melee')
			{
				$dmgMin = round($player['damagemin'] * ( ($player['sila']+100) / 100 ));
				$rezultat = $rezultat . "Min. obrażenia fizyczne: <span class='stat'>" . $dmgMin . "</span><br>";
				$dmgMax = round($player['damagemax'] * ( ($player['sila']+100) / 100 ));
				$rezultat = $rezultat . "Max. obrażenia fizyczne: <span class='stat'>" . $dmgMax . "</span><br>";
			}
			else if($player['weapon_type'] == 'ranged')
			{
				$dmgMin = round($player['damagemin'] * ( ($player['celnosc']+100) / 100 ));
				$rezultat = $rezultat . "Min. obrażenia fizyczne: <span class='stat'>" . $dmgMin . "</span><br>";
				$dmgMax = round($player['damagemax'] * ( ($player['celnosc']+100) / 100 ));
				$rezultat = $rezultat . "Max. obrażenia fizyczne: <span class='stat'>" . $dmgMax . "</span><br>";
			}
			if ($player['damage_ogien'] != 0) {$rezultat = $rezultat . "Obrażenia od ognia: <span class='stat'>" . $player['damage_ogien'] . "</span><br>";}
			if ($player['damage_woda'] != 0) {$rezultat = $rezultat . "Obrażenia od wody: <span class='stat'>" . $player['damage_woda'] . "</span><br>";}
			if ($player['damage_powietrze'] != 0) {$rezultat = $rezultat . "Obrażenia od powietrza: <span class='stat'>" . $player['damage_powietrze'] . "</span><br>";}
			if ($player['damage_ziemia'] != 0) {$rezultat = $rezultat . "Obrażenia od ziemi: <span class='stat'>" . $player['damage_ziemia'] . "</span><br>";}
			$liczba_akcji = round($player['attackspeed'] * $czas_rundy);
			$rezultat = $rezultat . "Ilość ataków na turę: <span class='stat'>" . $liczba_akcji . "</span><br>";
			$szansaKryt = $player['base_crit'] * ((100 + $player['szczescie']) / 100);
			$szansaKryt = round($szansaKryt, 1);
			$rezultat = $rezultat . "Szansa na traf. krytyczne: <span class='stat'>" . $szansaKryt . "%</span><br>";
			
			$rezultat = $rezultat . "Szansa na unik: <span class='stat'>" . $player['zwinnosc'] . "%</span><br>";
			$redukcja = ($player['armor'] / ($player['armor'] + (10*$dmgMax))) * 100;
			$redukcja = round($redukcja);
			$rezultat = $rezultat . "Red. obr. fiz. przeciwnika (traf. za " . $dmgMax . "): <span class='stat'>" . $redukcja . "%</span><br>";
		
			
        }
        
        return $rezultat;
    }
	
	
	
	
	function sum_monster($monsterID)
	{
		$spellbook = 
		[
			'czar1' => 0,
			'czar2' => 0,
			'czar3' => 0,
			'czar4' => 0,
			'czar5' => 0,
			'czar6' => 0,
			'czar7' => 0,
			'czar8' => 0,
			'czar9' => 0,
			'czar10' => 0,
			'priorytet1' => '',
			'priorytet2' => '',
			'priorytet3' => '',
			'priorytet4' => '',
			'priorytet5' => '',
			'priorytet6' => '',
			'priorytet7' => '',
			'priorytet8' => '',
			'priorytet9' => '',
			'priorytet10' => '',
		];
		
		$conn = connectDB();
		$result = $conn->query("SELECT name,hp,attack_name,weapon_type,damagemin,damagemax,damage_ogien,damage_woda,damage_powietrze,damage_ziemia,attackspeed,base_crit,armor,sila,zwinnosc,celnosc,kondycja,inteligencja,wiedza,charyzma,szczescie,crit_chance,crit_damage FROM monsters WHERE id=$monsterID");
		$row = mysqli_fetch_row($result);
		$conn->close();
		
		$monster_stats = [
			'name' => $row[0],
			'hp' => $row[1],
			'maxhp' => $row[1],
			'mana' => 0,
			'strona' => '',
			'type' => 'monster',
			'attack_name' => $row[2],
			'weapon_type' => $row[3],
			'damagemin' => $row[4],
			'damagemax' => $row[5],
			'damage_ogien' => $row[6],
			'damage_woda' => $row[7],
			'damage_powietrze' => $row[8],
			'damage_ziemia' => $row[9],
			'attackspeed' => $row[10],
			'base_crit' => $row[11],
			'armor' => $row[12],
			'sila' => $row[13],
			'zwinnosc' => $row[14],
			'celnosc' => $row[15],
			'kondycja' => $row[16],
			'inteligencja' => $row[17],
			'wiedza' => $row[18],
			'charyzma' => $row[19],
			'szczescie' => $row[20],
			'crit_chance' => $row[21],
			'crit_damage' => $row[22],
			'spellbook' => $spellbook,
		];
		
		return $monster_stats;
	}
	function sum_player($userID)
	{
		$eq_stats = sum_equipment($userID);
		
		$id_broni = get_stat('lefthand','equipment',$userID);
		if ($id_broni == 0) 
		{ 
			$attack_name = "Pięści"; 
			$weapon_type = "melee";
			$damagemin = 1; 
			$damagemax = 3; 
			$attackspeed = 1; 
			$base_crit = 5;
		}
		else 
		{			
			$attack_name = get_stat('name','items',$id_broni); 
			$konkretny_typ = get_stat('type','items',$id_broni);
			if($konkretny_typ == 'sztylet') {$weapon_type = "melee";}
			else if($konkretny_typ == 'miecz') {$weapon_type = "melee";}
			else if($konkretny_typ == 'topór') {$weapon_type = "melee";}
			else if($konkretny_typ == 'łuk') {$weapon_type = "ranged";}
			$damagemin = 0; 
			$damagemax = 0; 
			$attackspeed = 0; 
			$base_crit = 0;
		}
		
		$conn = connectDB();
		$result = $conn->query("SELECT czar1,czar2,czar3,czar4,czar5,czar6,czar7,czar8,czar9,czar10,priorytet1,priorytet2,priorytet3,priorytet4,priorytet5,priorytet6,priorytet7,priorytet8,priorytet9,priorytet10 FROM spellbooks WHERE id = $userID");
		$row = mysqli_fetch_row($result);
		
		$spellbook = 
		[
			'czar1' => $row[0],
			'czar2' => $row[1],
			'czar3' => $row[2],
			'czar4' => $row[3],
			'czar5' => $row[4],
			'czar6' => $row[5],
			'czar7' => $row[6],
			'czar8' => $row[7],
			'czar9' => $row[8],
			'czar10' => $row[9],
			'priorytet1' => $row[10],
			'priorytet2' => $row[11],
			'priorytet3' => $row[12],
			'priorytet4' => $row[13],
			'priorytet5' => $row[14],
			'priorytet6' => $row[15],
			'priorytet7' => $row[16],
			'priorytet8' => $row[17],
			'priorytet9' => $row[18],
			'priorytet10' => $row[19],
		];
		
		$result = $conn->query("SELECT username,hp,mana,sila,zwinnosc,celnosc,kondycja,inteligencja,wiedza,charyzma,szczescie,maxhp FROM users WHERE id=$userID");
		$row = mysqli_fetch_row($result);
		$conn->close();
		
		$player_stats = 
		[
			'name' => $row[0],
			'hp' => $row[1],
			'mana' => $row[2],
			'strona' => '',
			'type' => 'player',
			'maxhp' => $row[11],
			'weapon_type' => $weapon_type,
			'attack_name' => $attack_name,
			'damagemin' => $eq_stats['damagemin'] + $damagemin,
			'damagemax' => $eq_stats['damagemax'] + $damagemax,
			'damage_ogien' => $eq_stats['damage_ogien'],
			'damage_woda' => $eq_stats['damage_woda'],
			'damage_powietrze' => $eq_stats['damage_powietrze'],
			'damage_ziemia' => $eq_stats['damage_ziemia'],
			'attackspeed' => $eq_stats['attackspeed'] + $attackspeed,
			'base_crit' => $eq_stats['base_crit'] + $base_crit,
			'armor' => $eq_stats['armor'],
			'sila' => $eq_stats['sila'] + $row[3],
			'zwinnosc' => $eq_stats['zwinnosc'] + $row[4],
			'celnosc' => $eq_stats['celnosc'] + $row[5],
			'kondycja' => $eq_stats['kondycja'] + $row[6],
			'inteligencja' => $eq_stats['inteligencja'] + $row[7],
			'wiedza' => $eq_stats['wiedza'] + $row[8],
			'charyzma' => $eq_stats['charyzma'] + $row[9],
			'szczescie' => $eq_stats['szczescie'] + $row[10],
			'crit_chance' => $eq_stats['crit_chance'],
			'crit_damage' => $eq_stats['crit_damage'],
			'spellbook' => $spellbook,
		];
		
		return $player_stats;
	}
	function sum_equipment($userID)
	{
		$conn = connectDB();
		$result = $conn->query("SELECT helmet,amulet,lefthand,chest,righthand,gloves,belt,ring1,boots,ring2 FROM equipment WHERE id = $userID");
		$row = mysqli_fetch_row($result);
		$conn->close();
		
		$items = [
		'helmet' => sum_item($row[0]),
		'amulet' => sum_item($row[1]),
		'lefthand' => sum_item($row[2]),
		'chest' => sum_item($row[3]),
		'righthand' => sum_item($row[4]),
		'gloves' => sum_item($row[5]),
		'belt' => sum_item($row[6]),
		'ring' => sum_item($row[7]),
		'boots' => sum_item($row[8]),
		'ring2' => sum_item($row[9]),
		];
		
		$total_stats = [
		'damagemin' => 0,
		'damagemax' => 0,
		'damage_ogien' => 0,
		'damage_woda' => 0,
		'damage_powietrze' => 0,
		'damage_ziemia' => 0,
		'attackspeed' => 0,
		'base_crit' => 0,
		'armor' => 0,
		'sila' => 0,
		'zwinnosc' => 0,
		'celnosc' => 0,
		'kondycja' => 0,
		'inteligencja' => 0,
		'wiedza' => 0,
		'charyzma' => 0,
		'szczescie' => 0,
		'crit_chance' => 0,
		'crit_damage' => 0,
		];
		
		foreach($items as &$item)
		{
			$total_stats['damagemin'] += $item['damagemin'];
			$total_stats['damagemax'] += $item['damagemax'];
			$total_stats['damage_ogien'] += $item['damage_ogien'];
			$total_stats['damage_woda'] += $item['damage_woda'];
			$total_stats['damage_powietrze'] += $item['damage_powietrze'];
			$total_stats['damage_ziemia'] += $item['damage_ziemia'];
			$total_stats['attackspeed'] += $item['attackspeed'];
			$total_stats['base_crit'] += $item['base_crit'];
			$total_stats['armor'] += $item['armor'];
			$total_stats['sila'] += $item['sila'];
			$total_stats['zwinnosc'] += $item['zwinnosc'];
			$total_stats['celnosc'] += $item['celnosc'];
			$total_stats['kondycja'] += $item['kondycja'];
			$total_stats['inteligencja'] += $item['inteligencja'];
			$total_stats['wiedza'] += $item['wiedza'];
			$total_stats['charyzma'] += $item['charyzma'];
			$total_stats['szczescie'] += $item['szczescie'];
			$total_stats['crit_chance'] += $item['crit_chance'];
			$total_stats['crit_damage'] += $item['crit_damage'];
		}
		
		return $total_stats;
	}
	function sum_item($itemID)
	{
		$conn = connectDB();
		$result = $conn->query("SELECT name,rarity,type,main_type,cena,damagemin,damagemax,damage_ogien,damage_woda,damage_powietrze,damage_ziemia,attackspeed,base_crit,armor,sila,zwinnosc,celnosc,kondycja,inteligencja,wiedza,charyzma,szczescie,crit_chance,crit_damage FROM items WHERE id = $itemID");
		$row = mysqli_fetch_row($result);
		$conn->close();
		
		if ($itemID != 0)
		{
			$item = [
			'name' => $row[0],
			'rarity' => $row[1],
			'type' => $row[2],
			'main_type' => $row[3],
			'cena' => $row[4],
			'damagemin' => $row[5],
			'damagemax' => $row[6],
			'damage_ogien' => $row[7],
			'damage_woda' => $row[8],
			'damage_powietrze' => $row[9],
			'damage_ziemia' => $row[10],
			'attackspeed' => $row[11],
			'base_crit' => $row[12],
			'armor' => $row[13],
			'sila' => $row[14],
			'zwinnosc' => $row[15],
			'celnosc' => $row[16],
			'kondycja' => $row[17],
			'inteligencja' => $row[18],
			'wiedza' => $row[19],
			'charyzma' => $row[20],
			'szczescie' => $row[21],
			'crit_chance' => $row[22],
			'crit_damage' => $row[23],
			];
		}
		else
		{
			$item = [
			'name' => '',
			'rarity' => '',
			'type' => '',
			'main_type' => '',
			'cena' => 0,
			'damagemin' => 0,
			'damagemax' => 0,
			'damage_ogien' => 0,
			'damage_woda' => 0,
			'damage_powietrze' => 0,
			'damage_ziemia' => 0,
			'attackspeed' => 0,
			'base_crit' => 0,
			'armor' => 0,
			'sila' => 0,
			'zwinnosc' => 0,
			'celnosc' => 0,
			'kondycja' => 0,
			'inteligencja' => 0,
			'wiedza' => 0,
			'charyzma' => 0,
			'szczescie' => 0,
			'crit_chance' => 0,
			'crit_damage' => 0,
			];
		}
		
		return $item;
	}


	
	function send_message($fromID, $fromName, $toID, $toName, $message, $title, $read)
	{
		$conn = connectDB();
		$fromID = $conn->real_escape_string($fromID);
		$toID = $conn->real_escape_string($toID);
		$fromName = $conn->real_escape_string($fromName);
		$toName = $conn->real_escape_string($toName);
		$message = $conn->real_escape_string($message);
		if ($title == '') {$title = 'Wiadomość bez tytułu';}
		$title = $conn->real_escape_string($title);
		$conn->query("INSERT INTO messages (fromID,toID,fromName,toName,message,date,title,is_read) VALUES ('$fromID','$toID','$fromName','$toName','$message',NOW(),'$title','$read')");
		$last_id = $conn->insert_id;
		
		
		$zapisano = false;
		
		for ($i = 1; $i <= 10; $i++)
		{
			$tab = 'msg' . $i;
			$isNull = get_value($conn,"SELECT $tab FROM user_mail WHERE id = $toID");
			
			if ($isNull == null)
			{
				set_stat('user_mail',$tab,$last_id,$toID);
				$zapisano = true;
				break;
			}
		}
		
		if ($zapisano == false)
		{
			$result = $conn->query("SELECT msg1,msg2,msg3,msg4,msg5,msg6,msg7,msg8,msg9,msg10 FROM user_mail WHERE id = $toID");
			$row = mysqli_fetch_row($result);
			$msg1 = $row[0];
			$msg2 = $row[1];
			$msg3 = $row[2];
			$msg4 = $row[3];
			$msg5 = $row[4];
			$msg6 = $row[5];
			$msg7 = $row[6];
			$msg8 = $row[7];
			$msg9 = $row[8];
			$msg10 = $row[9];
			
			
			$conn->query("UPDATE user_mail SET msg1=$msg2, msg2=$msg3, msg3=$msg4, msg4=$msg5, msg5=$msg6, msg6=$msg7, msg7=$msg8, msg8=$msg9, msg9=$msg10, msg10=$last_id WHERE id=$toID");
			$conn->query("DELETE FROM messages WHERE id=$msg1");
		}
		
		$conn->close();
	}
    
?>