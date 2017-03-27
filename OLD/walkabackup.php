<?php

    require_once('config.php');
    login_check();
	
	if($_POST)
	{
		//WYPRAWA
		if(isset($_POST['miejsce']))
		{
			$monsterID = LosujPotwora($_POST['miejsce']);
			$conn = connectDB();
			set_stat('users','destination','false',$_SESSION['id']);
			$eID = $conn->real_escape_string($_SESSION['id']);
			$conn->query("UPDATE users SET wyprawa_until=NULL WHERE id = '$eID'");
			$conn->close();
		
			echo "<div id='divAtakera'>";
			echo read_stats($_SESSION['id'], 'zdjecie');
			echo "</div>";
		
			echo "<div id='divObroncy'>";
			echo read_stats($monsterID, 'zdjeciePotwora');
			echo "</div>";
		
			echo "<div id='divVs'></div>";
			echo "<div id='hpAtakeraContainer'></div>";
			echo "<div id='hpObroncyContainer'></div>";
		
			echo "<div id='walkaOkno'>";
			$atakerHP = get_stat('hp','users',$eID);
			$atakerMaxHP = get_stat('maxhp','users',$eID);
			$obroncaHP = get_stat('hp','monsters',$monsterID);
			$obroncaMaxHP = get_stat('hp','monsters',$monsterID);
			
			$arr = Attack('player','monster',$_SESSION['id'],$monsterID);
			$battle = $arr['battle'];
			$atakername = $arr['atakername'];
			$obroncaname = $arr['obroncaname'];
			echo "</div>";
		}
		//ARENA
		else
		{
			$opponentID = $_POST['opponent'];
			
			echo "<div id='divAtakera'>";
			echo read_stats($_SESSION['id'], 'zdjecie');
			echo "</div>";
			
			echo "<div id='divObroncy'>";
			echo read_stats($opponentID, 'zdjecie');
			echo "</div>";
			
			echo "<div id='divVs'></div>";
			echo "<div id='hpAtakeraContainer'></div>";
			echo "<div id='hpObroncyContainer'></div>";
			update_logic($opponentID);
			$atakerHP = get_stat('hp','users',$_SESSION['id']);
			$atakerMaxHP = get_stat('maxhp','users',$_SESSION['id']);
			$obroncaHP = get_stat('hp','users',$_POST['opponent']);
			$obroncaMaxHP = get_stat('maxhp','users',$_POST['opponent']);
			
			echo "<div id='walkaOkno'>";
			$arr = Attack('player','player',$_SESSION['id'],$opponentID);
			$battle = $arr['battle'];
			$atakername = $arr['atakername'];
			$obroncaname = $arr['obroncaname'];
			echo "</div>";
		}
		
	}
	function LosujPotwora($miejsce)
	{
		$conn = connectDB();
		$eMiejsce = $conn->real_escape_string($miejsce);
		$result = $conn->query("SELECT id FROM monsters WHERE miejsce = '$eMiejsce'");
		$count = $result->num_rows;
		$count--;
		
		$rows = [];
		$i = 0;
	
		while($row = $result->fetch_row())
		{
			$rows[$i] = $row;
			$i++;
		}
		
		$conn->close();
		
		$wylosowany = rand(0,$count);
		return $rows[$wylosowany][0];
	}
	
	
	

	function koniec_walki($zwyciezca, $rezultat)
	{
		$span_koniec_walki = "<span style='font-weight: bold;'>";
		$endSpan = "</span>";
		
		array_push($rezultat, "<br>" . $span_koniec_walki . $zwyciezca['name'] . " zwycięża!" . $endSpan . "<br>");
		return $rezultat;
	}
	
	function znajdz_czar_startowy($ataker, $obronca, $rezultat)
	{
		for($i = 1; $i <= 10; $i++)
		{
			$prior = 'priorytet' . $i;
			$cz = 'czar' . $i;
		
			//ZNALEZIONO CZAR STARTOWY
			if($ataker['spellbook'][$prior] == 'start')
			{
				$ataker['wykonano_czary_startowe'] = false;
				$id_spella = $ataker['spellbook'][$cz];
				$array = cast_spell($ataker, $obronca, $id_spella, $rezultat);
				$ataker = $array['ataker'];
				$obronca = $array['obronca'];
				$rezultat = $array['rezultat'];
				$ataker['spellbook'][$prior] = ''; //USTAWIAMY ŻE JUŻ WYKONANY
				break;
			}
		
			//ZNALEZIONO CZAR SPAMOWALNY, USTAWIAMY NA PRZYSZLOSC
			if ($ataker['spellbook'][$prior] == 'spam')
			{
				$ataker['tylko_czary'] = true;
			}
		
			//NIE ZNALEZIONO WIECEJ CZAROW, = WYKONANO WSZYSTKIE STARTOWE
			if ($i == 10)
			{
				$ataker['wykonano_czary_startowe'] = true;
				$ataker['wykonano_ruch'] = false;
			}
		}
		
		return [
		'ataker' => $ataker,
		'obronca' => $obronca,
		'rezultat' => $rezultat,
		];
	}
	
	function znajdz_czar_spamowalny($ataker, $obronca, $rezultat)
	{
		for ($i = 1; $i <= 10; $i++)
		{
			$prior = 'priorytet' . $i;
			$cz = 'czar' . $i;
							
			//ZNALEZIONO CZAR SPAMOWALNY
			if($ataker['spellbook'][$prior] == 'spam')
			{
				$ataker['tylko_czary'] = true;
				$id_spella = $ataker['spellbook'][$cz];
				$array = cast_spell($ataker, $obronca, $id_spella, $rezultat);
				$ataker = $array['ataker'];
				$obronca = $array['obronca'];
				$rezultat = $array['rezultat'];
				//ZABRAKŁO MANY WIEC USUWAMY TEN CZAR Z PRZYSZLYCH PROB
				if($ataker['mial_mane'] == false)
				{
					$ataker['spellbook'][$prior] = '';		
				}
				break;
								
			}
			//NIE ZNALEZIONO WIECEJ CZAROW, = WYKONANO MAGIE
			else if ($i == 10)
			{
				$ataker['tylko_czary'] = false;
				$ataker['wykonano_ruch'] = false;
			}
		}
		
		return [
		'ataker' => $ataker,
		'obronca' => $obronca,
		'rezultat' => $rezultat,
		];
	}
	
	function cast_spell($ataker, $obronca, $spellID, $rezultat)
	{
		//KOLORY
        $endSpan = "</span>";
		$span_total_obrazen = "<span style='font-weight: bold;'>";
		$span_crit = "<span style='font-weight: bold;'>";
        $span_obrazen_fizycznych = "<span style='color: black;'>";
		$span_obrazen_ogien = "<span style='color: red;'>";
		$span_obrazen_woda = "<span style='color: darkblue;'>";
		$span_obrazen_powietrze = "<span style='color: SkyBlue ;'>";
		$span_obrazen_ziemia = "<span style='color: SaddleBrown;'>";
        $span_zycia = "<span style='color: red;'>";
        $span_koniec_walki = "<span style='font-weight: bold;'>";
		
		
		$czary = [];
		//				0:NAZWA					1:EFEKT				2:CZAS		3:MANA			4:DAMAGEMIN		5:DAMAGEMAX		6:ELEMENT		7:AFFECT
		$czary[1] =	[	'Płomyk', 				'obronca_damage', 	1,			10,				3, 				5,				'ogien',		''];
		$czary[2] = [	'Piorun',				'obronca_damage',	1,			10,				5,				7,				'powietrze',	''];
		$czary[3] = [	'Rzut głazem',			'obronca_damage',	1,			10,				7,				9,				'ziemia',		''];
		$czary[4] = [	'Magiczny pancerz',		'ataker_buff',		2,			20,				15,				20,				'ziemia',		'armor'];
		
		
		//GRACZ MA WYSTARCZAJĄCO RUCHU I MANY, NIC NIE ZMIENIAMY
		if ((($ataker['pozostaly_czas'] - $czary[$spellID][2]) >= 0) and (($ataker['mana'] - $czary[$spellID][3]) >= 0))
		{			
			switch($czary[$spellID][6])
			{
				case 'ogien':
					$span_obrazen_czaru = $span_obrazen_ogien;
					$typ_obrazen = "od ognia! ";
					break;
				case 'woda':
					$span_obrazen_czaru = $span_obrazen_woda;
					$typ_obrazen = "od wody! ";
					break;
				case 'powietrze':
					$span_obrazen_czaru = $span_obrazen_powietrze;
					$typ_obrazen = "od powietrza! ";
					break;
				case 'ziemia':
					$span_obrazen_czaru = $span_obrazen_ziemia;
					$typ_obrazen = "od ziemi! ";
					break;
				default:
			}
	
			if ($czary[$spellID][1] == 'obronca_damage')
			{
				$damage = rand($czary[$spellID][4], $czary[$spellID][5]);
				$damage = $damage * (($ataker['inteligencja'] + 100) / 100);
				$damage = round($damage);
				$obronca['hp'] -= $damage;
				
				if ($obronca['hp'] > 0) 					{$obronca['tekst_zycia'] = "Pozostało " . $span_zycia . $obronca['hp'] . $endSpan . " życia.";}
				else 										{$obronca['tekst_zycia'] = $obronca['tekst_nick'] . " umiera!";}
			
				array_push($rezultat, $ataker['tekst_nick'] . " używa czaru " . $span_obrazen_czaru . $czary[$spellID][0] . $endSpan . " i zadaje " . $span_obrazen_czaru . $damage . $endSpan . " obrażeń " . $typ_obrazen .  $obronca['tekst_zycia'] . "<br>");
			}
			else if ($czary[$spellID][1] == 'ataker_buff')
			{
				$buff = rand($czary[$spellID][4], $czary[$spellID][5]);
				$buff = round($buff);
				
				$ataker[$czary[$spellID][7]] += $buff;
				array_push($rezultat, $ataker['tekst_nick'] . " używa czaru " . $span_obrazen_czaru . $czary[$spellID][0] . $endSpan . "! Statystyka wzrasta o " . $span_obrazen_czaru . $buff . $endSpan . " punktów!<br>");
			}
			
			
			
			$ataker['pozostaly_czas'] -= $czary[$spellID][2];
			$ataker['mana'] -= $czary[$spellID][3];
			$ataker['wykonano_ruch'] = true;
			$ataker['mial_mane'] = true;
		}
		//GRACZ MA WYSTARCZAJĄCO RUCHU ALE ZA MAŁO MANY NA TEN SPELL, USUWAMY SPELL Z JEGO PRIORYTETÓW, PRZECHODZIMY DO NASTĘPNEGO SPELLA ALBO MELEE
		else if ((($ataker['pozostaly_czas'] - $czary[$spellID][2]) >= 0) and (($ataker['mana'] - $czary[$spellID][3]) < 0))
		{
			$ataker['mial_mane'] = false;
			$ataker['wykonano_ruch'] = true;
		}
		//ZABRAKŁO MU RUCHU
		else
		{
			$ataker['wykonano_ruch'] = false;
		}
		
		return [
			'ataker' => $ataker,
			'obronca' => $obronca,
			'rezultat' => $rezultat,
		];
	}
	
	function hit($ataker, $obronca, $rezultat)
	{
		//KOLORY
        $endSpan = "</span>";
		$span_total_obrazen = "<span style='font-weight: bold;'>";
		$span_crit = "<span style='font-weight: bold;'>";
        $span_obrazen_fizycznych = "<span style='color: black;'>";
		$span_obrazen_ogien = "<span style='color: red;'>";
		$span_obrazen_woda = "<span style='color: darkblue;'>";
		$span_obrazen_powietrze = "<span style='color: SkyBlue ;'>";
		$span_obrazen_ziemia = "<span style='color: SaddleBrown;'>";
        $span_zycia = "<span style='color: red;'>";
        $span_koniec_walki = "<span style='font-weight: bold;'>";
		
		
		//AKCJA
		if(($ataker['pozostaly_czas'] - (1/$ataker['attackspeed'])) >= 0)
		{
			$ataker['pozostaly_czas'] -= (1/$ataker['attackspeed']);
			$ataker['wykonano_ruch'] = true;
			
			
			//LOSOWANIE DMG MIN-MAX
			$damage = rand($ataker['damagemin'], $ataker['damagemax']);
			//WPŁYW STATYSTYK NA DMG
			if($ataker['weapon_type'] == 'melee')
			{
				$damage = $damage * ( ($ataker['sila']+100) / 100 );
			}
			else if($ataker['weapon_type'] == 'ranged')
			{
				$damage = $damage * ( ($ataker['celnosc']+100) / 100);
			}
			//WPLŁYW ARMORA NA DMG
			if($obronca['armor'] != 0)
			{
				$mitigation = ($obronca['armor'] / ($obronca['armor'] + (10*$damage)));
				$damage -= ($damage * $mitigation);
			}
			//SZANSA NA KRYT
			$critChance = $ataker['base_crit'] * ((100 + $ataker['szczescie']) / 100) * ((100 + $ataker['crit_chance']) / 100);
			$critRoll = rand(0,100);
			$critModifier = 2 + ($ataker['crit_damage'] / 100);
			if ($critRoll <= $critChance)
			{
				$tekst_crit = $span_crit . " Trafienie krytyczne! " . $endSpan;
				$damage *= $critModifier;
			}
			else
			{
				$tekst_crit = "";
			}
			$damage = round($damage);
			$total_damage = ($damage + $ataker['damage_ogien'] + $ataker['damage_woda'] + $ataker['damage_powietrze'] + $ataker['damage_ziemia']);
			//SZANSA NA UNIK
			$hitChance = ($ataker['zwinnosc'] / ($obronca['zwinnosc'] * 1.1)) * 100;
			$dodgeChance = 100 - $hitChance;
			$dodgeRoll = rand(0,100);
			if ($dodgeRoll <= $dodgeChance)
			{
				$tekst_unik = $obronca['tekst_nick'] . " wykonuje unik!";
			}
			else
			{
				$tekst_unik = "";
				$obronca['hp'] -= $total_damage;
			}
					
			//TEKST
			if ($obronca['hp'] > 0) 					{$obronca['tekst_zycia'] = ". Pozostało " . $span_zycia . $obronca['hp'] . $endSpan . " życia.";}
			else 										{$obronca['tekst_zycia'] = ". " . $obronca['tekst_nick'] . " umiera!";}
					
			if($damage != 0) 							{$tekst_obrazen_fizycznych = " " . $span_obrazen_fizycznych . $damage . $endSpan . " fizycznych";}
			else										{$tekst_obrazen_fizycznych = "";}
			$tekst_obrazen_elementalnych = "";
			if ($ataker['damage_ogien'] != 0) 			{$tekst_obrazen_elementalnych = " " . $tekst_obrazen_elementalnych . $span_obrazen_ogien . $ataker['damage_ogien'] . $endSpan . " od ognia ";}
			if ($ataker['damage_woda'] != 0) 			{$tekst_obrazen_elementalnych = " " . $tekst_obrazen_elementalnych . $span_obrazen_woda . $ataker['damage_woda'] . $endSpan . " od wody ";}
			if ($ataker['damage_powietrze'] != 0) 		{$tekst_obrazen_elementalnych = " " . $tekst_obrazen_elementalnych . $span_obrazen_powietrze . $ataker['damage_powietrze'] . $endSpan . " od powietrza ";}
			if ($ataker['damage_ziemia'] != 0) 			{$tekst_obrazen_elementalnych = " " . $tekst_obrazen_elementalnych . $span_obrazen_ziemia . $ataker['damage_ziemia'] . $endSpan . " od ziemi ";}
			if ($tekst_obrazen_elementalnych == "") 	{$tekst_obrazen = "Zadaje " . $span_total_obrazen . $total_damage . $endSpan . " obrażeń";}
			else 										{$tekst_obrazen = "Zadaje " . $span_total_obrazen . $total_damage . $endSpan . " obrażeń (" . $tekst_obrazen_fizycznych . $tekst_obrazen_elementalnych . ")";}
			if ($tekst_unik != "")						{$tekst_obrazen = ""; 	$tekst_crit = ""; 	$obronca['tekst_zycia'] = "";}
					
			array_push($rezultat, $ataker['tekst_nick'] . " atakuje " . $obronca['tekst_nick'] . " za pomocą " . $ataker['tekst_nazwa_ataku'] . "! " . $tekst_unik . $tekst_crit . $tekst_obrazen . $obronca['tekst_zycia'] . "<br>");
        
		}
		else
		{
			$ataker['wykonano_ruch'] = false;
		}
		    
		return [
			'ataker' => $ataker,
			'obronca' => $obronca,
			'rezultat' => $rezultat,
		];
	}


	
	
	
    function Attack($typ_atakera, $typ_obroncy, $id_atakera, $id_obroncy)
    {
		//ZBIERANIE DANYCH O WALCZĄCYCH
        if($typ_atakera == 'player')			{update_logic($id_atakera);				$ataker = sum_player($id_atakera);}
		if($typ_obroncy == 'player')			{update_logic($id_obroncy);				$obronca = sum_player($id_obroncy);}
		else if($typ_obroncy == 'monster')		{$obronca = sum_monster($id_obroncy);}
		
		
		//KOLORY
		$endSpan = "</span>";
		$span_rundy = "<span style='font-weight: bold; color: dimGray;'>";
        $span_nicku_atakera = "<span style='color: darkgreen; font-weight: bold;'>";
        $span_nicku_obroncy = "<span style='color: darkorange;'>";
		$span_nazwy_ataku_atakera = "<span style='color: darkblue;'>";
        $span_nazwy_ataku_obroncy = "<span style='color: brown;'>";
		$ataker['tekst_nick'] = $span_nicku_atakera . $ataker['name'] . $endSpan;
		$obronca['tekst_nick'] = $span_nicku_obroncy . $obronca['name'] . $endSpan;
		$ataker['tekst_nazwa_ataku'] = $span_nazwy_ataku_atakera . $ataker['attack_name'] . $endSpan;
		$obronca['tekst_nazwa_ataku'] = $span_nazwy_ataku_obroncy . $obronca['attack_name'] . $endSpan;
		
		
        $czas_rundy = 5;
		$runda = 0;
		$rezultat = [];
		
		
		$ataker['wykonano_ruch'] = false;
		$obronca['wykonano_ruch'] = false;
		$ataker['wykonano_czary_startowe'] = false;
		$obronca['wykonano_czary_startowe'] = false;
		$ataker['tylko_czary'] = true;
		$obronca['tylko_czary'] = true;
		$ataker['mial_mane'] = true;
		$ataker['mial_mane'] = true;


		//WALKA
		while(($ataker['hp'] > 0) and ($obronca['hp'] > 0))
		{
			
			if(($ataker['wykonano_ruch'] == false) and ($obronca['wykonano_ruch'] == false))
			{
				$runda++;
				if ($runda == 1)		{array_push($rezultat, $span_rundy . "Runda " . $runda . $endSpan . "<br><br>");}
				else					{array_push($rezultat, "<br>" . $span_rundy . "Runda " . $runda . $endSpan . "<br><br>");}
				$ataker['pozostaly_czas'] = $czas_rundy;
				$obronca['pozostaly_czas'] = $czas_rundy;
				$ataker['wykonano_ruch'] = true;
				$obronca['wykonano_ruch'] = true;
			}
			
			//MAGIA STARTOWA
			$arr = znajdz_czar_startowy($ataker, $obronca, $rezultat);
			$ataker = $arr['ataker'];
			$obronca = $arr['obronca'];
			$rezultat = $arr['rezultat'];
			if($obronca['hp'] <= 0)
			{
				$rezultat = koniec_walki($ataker, $rezultat);
				break;
			}
	
			$arr = znajdz_czar_startowy($obronca, $ataker, $rezultat);
			$ataker = $arr['obronca'];
			$obronca = $arr['ataker'];
			$rezultat = $arr['rezultat'];
			if($ataker['hp'] <= 0)
			{
				$rezultat = koniec_walki($obronca, $rezultat);
				break;
			}
			
			
			
			//MAGIA DALSZA
			if($ataker['wykonano_czary_startowe'] == true)
			{
				$arr = znajdz_czar_spamowalny($ataker, $obronca, $rezultat);
				$ataker = $arr['ataker'];
				$obronca = $arr['obronca'];
				$rezultat = $arr['rezultat'];
				
				if($obronca['hp'] <= 0)
				{
					$rezultat = koniec_walki($ataker, $rezultat);
					break;
				}
			}
			//usleep(1000000);
			if($obronca['wykonano_czary_startowe'] == true)
			{
				$arr = znajdz_czar_spamowalny($obronca, $ataker, $rezultat);
				$ataker = $arr['obronca'];
				$obronca = $arr['ataker'];
				$rezultat = $arr['rezultat'];
				if($ataker['hp'] <= 0)
				{
					$rezultat = koniec_walki($obronca, $rezultat);
					break;
				}
			}
			
			
			
			//ATAKI BRONIĄ
			if($ataker['tylko_czary'] == false)
			{
				$array = hit($ataker, $obronca, $rezultat);
				$ataker = $array['ataker'];
				$obronca = $array['obronca'];
				$rezultat = $array['rezultat'];
				if($obronca['hp'] <= 0)
				{
					$rezultat = koniec_walki($ataker, $rezultat);
					break;
				}
			}
			//usleep(1000000);
			if($obronca['tylko_czary'] == false)
			{
				$array = hit($obronca, $ataker, $rezultat);
				$ataker = $array['obronca'];
				$obronca = $array['ataker'];
				$rezultat = $array['rezultat'];
				if($ataker['hp'] <= 0)
				{
					$rezultat = koniec_walki($obronca, $rezultat);
					break;
				}
			}
			
			
			
		}
		
		
        //PO WALCE
		//USTAWIAMY HP I MANĘ GRACZA ATAKUJĄCEGO
        if($typ_atakera == 'player')    
        {   
			if($ataker['hp'] < 0)
			{
				set_stat('users','hp', 0, $id_atakera);
			}
			else
			{
				set_stat('users','hp', $ataker['hp'], $id_atakera);
			}
			
			set_stat('users','mana', $ataker['mana'], $id_atakera);
        }
		//USTAWIAMY HP I MANĘ GRACZA BRONIĄCEGO I STATUS PROTECTED, DODAJEMY EXPA I GOLDA ZWYCIĘZCY
        if($typ_obroncy == 'player')
        {
			if($obronca['hp'] < 0)
			{
				set_stat('users','hp', 0, $id_obroncy);
			}
			else
			{
				set_stat('users','hp', $obronca['hp'], $id_obroncy);
			}
			
			set_stat('users','mana',$obronca['mana'],$id_obroncy);
			
			$sekundy = get_current_time();
			$sekundy += 900;
			insert_time($sekundy,'protected_until',$id_obroncy);
			
			
			$rezultat = DodajGolda($typ_atakera, $typ_obroncy, $id_atakera, $id_obroncy, $rezultat, $ataker['hp'], $obronca['hp']);
			$rezultat = DodajExpa($typ_atakera, $typ_obroncy, $id_atakera, $id_obroncy, $rezultat, $ataker['hp'], $obronca['hp']);
			
			$msg = join("", $rezultat);
			$title = 'Raport z walki: ' . $obronca['name'];
			send_message(0,'System',$id_atakera,$ataker['name'],$msg,$title,1);
			$title = 'Raport z walki: ' . $ataker['name'];
			send_message(0,'System',$id_obroncy,$obronca['name'],$msg,$title,0);
        }
		//JEŻELI POKONALIŚMY POTWORA TO GENERACJA LOOTU, ZLOTA I EXPA
		else if ($typ_obroncy == 'monster')
		{
			if($obronca['hp'] <= 0)
			{
				$rezultat = GenerujLoot($typ_obroncy, $id_obroncy, $id_atakera, $rezultat);
				$rezultat = DodajGolda($typ_atakera, $typ_obroncy, $id_atakera, $id_obroncy, $rezultat, $ataker['hp'], $obronca['hp']);
				$rezultat = DodajExpa($typ_atakera, $typ_obroncy, $id_atakera, $id_obroncy, $rezultat, $ataker['hp'], $obronca['hp']);
			}
			
			$msg = join("", $rezultat);
			send_message(0,'System',$id_atakera,$ataker['name'],$msg,'Raport z wyprawy',1);
		}
        
		
		
		
		
		return [
			'battle' => $rezultat,
			'atakername' => $ataker['name'],
			'obroncaname' => $obronca['name'],
		];
    }

	
	
	
	
    function DodajExpa($typ_atakera, $typ_obroncy, $id_atakera, $id_obroncy, $rezultat, $hp_atakera, $hp_obroncy)
    {
        $span_zdobylesExpa = "<span style='color:darkgreen;'>";
        $span_zdobylesLevel = "<span style='font-weight: bold; color:darkslateblue;'>";
        $endSpan = "</span>";
        
        if($typ_atakera == 'player')
        {
            //WALKA PvE
            if($typ_obroncy == 'monster')
            {
                $aktualnyExp = get_stat('exp','users',$id_atakera);
                $exp_next = get_stat('exp_next','users',$id_atakera);
                $expZaPotwora = get_stat('exp','monsters',$id_obroncy);
                $aktualnyExp += $expZaPotwora;
				$pozostale_punkty = get_stat('punkty_do_rozdania','users',$id_atakera);
                array_push($rezultat, "<br>" . "Zdobywasz " . $span_zdobylesExpa . $expZaPotwora . $endSpan . " doświadczenia!<br>");
				
				//LEVEL UP?
				if ($aktualnyExp >= $exp_next)
				{
					$exp_next = $exp_next * 2;
					$aktualnyLevel = get_stat('level','users',$id_atakera);
					$aktualnyLevel++;
					set_stat('users','level',$aktualnyLevel,$id_atakera);
					set_stat('users','exp_next',$exp_next,$id_atakera);
					set_stat('users','exp','0',$id_atakera);
					$pozostale_punkty = get_stat('punkty_do_rozdania','users',$id_atakera);
					$pozostale_punkty += 3;
					set_stat('users','punkty_do_rozdania',$pozostale_punkty,$id_atakera);
				
					$hp = get_stat('maxhp','users',$id_atakera);
					set_stat('users','hp','$hp',$id_atakera);
					array_push($rezultat,"<br>" . $span_zdobylesLevel . "Gratulacje! Osiągnąłeś " .$aktualnyLevel . " poziom doświadczenia!" .$endSpan);
				}	
            }
            //WALKA PVP
            else
            {
				//JEŻELI WYGRAŁ ATAKER
				if($hp_atakera > 0)
				{
					$przeciwnik_next = get_stat('exp_next','users',$id_obroncy);
					$zdobyty_exp = round(0.05 * $przeciwnik_next);
					$aktualnyExp = get_stat('exp','users',$id_atakera);
					$exp_next = get_stat('exp_next','users',$id_atakera);
					$aktualnyExp += $zdobyty_exp;
					set_stat('users','exp',$aktualnyExp,$id_atakera);
					array_push($rezultat, "<br>" . "Zdobywasz " . $span_zdobylesExpa . $zdobyty_exp . $endSpan . " doświadczenia!<br>");
					
					//LEVEL UP?
					if ($aktualnyExp >= $exp_next)
					{
						$exp_next = $exp_next * 2;
						$aktualnyLevel = get_stat('level','users',$id_atakera);
						$aktualnyLevel++;
						set_stat('users','level',$aktualnyLevel,$id_atakera);
						set_stat('users','exp_next',$exp_next,$id_atakera);
						set_stat('users','exp','0',$id_atakera);
						$pozostale_punkty = get_stat('punkty_do_rozdania','users',$id_atakera);
						$pozostale_punkty += 3;
						set_stat('users','punkty_do_rozdania',$pozostale_punkty,$id_atakera);
				
						$hp = get_stat('maxhp','users',$id_atakera);
						set_stat('users','hp','$hp',$id_atakera);
						array_push($rezultat,"<br>" . $span_zdobylesLevel . "Gratulacje! Osiągnąłeś " .$aktualnyLevel . " poziom doświadczenia!" .$endSpan);
					}	
				}
				//OBROŃCA WYGRAŁ
				else if ($hp_obroncy > 0)
				{
					$przeciwnik_next = get_stat('exp_next','users',$id_atakera);
					$zdobyty_exp = round(0.05 * $przeciwnik_next);
					$aktualnyExp = get_stat('exp','users',$id_obroncy);
					$exp_next = get_stat('exp_next','users',$id_obroncy);
					$aktualny_exp += $zdobyty_exp;
					set_stat('users','exp',$aktualnyExp,$id_obroncy);
					
					//LEVEL UP?
					if ($aktualnyExp >= $exp_next)
					{
						$exp_next = $exp_next * 2;
						$aktualnyLevel = get_stat('level','users',$id_obroncy);
						$aktualnyLevel++;
						set_stat('users','level',$aktualnyLevel,$id_obroncy);
						set_stat('users','exp_next',$exp_next,$id_obroncy);
						set_stat('users','exp','0',$id_obroncy);
						$pozostale_punkty = get_stat('punkty_do_rozdania','users',$id_obroncy);
						$pozostale_punkty += 3;
						set_stat('users','punkty_do_rozdania',$pozostale_punkty,$id_obroncy);
				
						$hp = get_stat('maxhp','users',$id_obroncy);
						set_stat('users','hp','$hp',$id_obroncy);
					}	
				}
				
            }
            
            
        }
        
		return $rezultat;
    }

    function DodajGolda($typ_atakera, $typ_obroncy, $id_atakera, $id_obroncy, $rezultat, $hp_atakera, $hp_obroncy)
    {
        $span_zdobywaszZloto = "<span style='color:saddleBrown;'>";
        $spanEnd = "</span>";
        
        if($typ_obroncy == 'monster')
        {
            //PRZYDZIELANIE GOLDA
            $maxGoldPotwora = get_stat('zloto','monsters',$id_obroncy);
            $przydzielonyGold = rand(0, $maxGoldPotwora);
            $aktualnyGold = get_stat('zloto','users',$id_atakera);
            $aktualnyGold += $przydzielonyGold;
            set_stat('users','zloto',$aktualnyGold,$id_atakera);
            array_push($rezultat,"<br>Zdobywasz " .$span_zdobywaszZloto .$przydzielonyGold .$spanEnd ." złota!");
        }
		else
		{
			//ATAKER WYGRAŁ
			if ($hp_atakera > 0)
			{
				$goldPrzeciwnika = get_stat('zloto','users',$id_obroncy);
				$przydzielonyGold = round(0.3*$goldPrzeciwnika);
				$aktualnyGoldAtakera = get_stat('zloto','users',$id_atakera);
				$aktualnyGoldAtakera += $przydzielonyGold;
				set_stat('users','zloto',$aktualnyGoldAtakera,$id_atakera);
				$aktualnyGoldObroncy = get_stat('zloto','users',$id_obroncy);
				$aktualnyGoldObroncy -= $przydzielonyGold;
				set_stat('users','zloto',$aktualnyGoldObroncy,$id_obroncy);
				
				array_push($rezultat,"<br>Zabierasz " .$span_zdobywaszZloto .$przydzielonyGold .$spanEnd ." złota!");
			}
		}
            
		return $rezultat;
    }

    function GenerujLoot($typ_obroncy, $id_obroncy, $id_atakera, $rezultat)
    {
        $span_zdobyty_loot = "<span style='color:darkblue;'>";
        $span_brak_miejsca = "<span style='color:darkred;'>";
        $endSpan = "</span>";
        
        //WALKA PvE
        if($typ_obroncy == 'monster')
        {
            //DEKLARACJA ZMIENNYCH
            $loot_tier = get_stat('loot_tier','monsters',$id_obroncy);
            $name = "";
            $rarity = "";
            $type = "";
            $main_type = "";
            $image_id = $loot_tier;
			$cena = 0;
            $damagemin = 0;
            $damagemax = 0;
			$damage_ogien = 0;
			$damage_woda = 0;
			$damage_powietrze = 0;
			$damage_ziemia = 0;
            $attackspeed = 0;
			$base_crit = 0;
            $armor = 0;
            $sila = 0;
            $zwinnosc = 0;
            $celnosc = 0;
            $kondycja = 0;
            $inteligencja = 0;
            $wiedza = 0;
            $charyzma = 0;
            $szczescie = 0;
			$crit_chance = 0;
			$crit_damage = 0;
            
			
			//LOSOWANIE PODSTAWOWYCH STATYSTYK
            $typ_itemu = rand(0,4);
            switch($typ_itemu)
            {
                case 0:   //Sword     
                    $dmg_min_min_itemu = ($loot_tier * 5) - 2;
                    $dmg_min_max_itemu = ($loot_tier * 5);
                    $damagemin = rand($dmg_min_min_itemu, $dmg_min_max_itemu);
                    $dmg_max_min_itemu = ($loot_tier * 5) + 1;
                    $dmg_max_max_itemu = ($loot_tier * 5) + 3;
                    $damagemax = rand($dmg_max_min_itemu, $dmg_max_max_itemu);
                    $attack_speed_min_itemu = 1.0;
                    $attack_speed_max_itemu = 1.2;
                    $attackspeed = round(random_float($attack_speed_min_itemu, $attack_speed_max_itemu),1);
					$base_crit_min_itemu = 3 + $loot_tier;
					$base_crit_max_itemu = 5 + $loot_tier;
					$base_crit = rand($base_crit_min_itemu, $base_crit_max_itemu);
                    $type = "miecz";
                    $main_type = "lefthand";
					$names = [
						1=>"Zardzewiały miecz", 2=>"Miedziany miecz",
					];
                    $name = $names[$loot_tier];
                    break;
                case 1:   //Dagger      
                    $dmg_min_min_itemu = ($loot_tier * 5) - 4;
                    $dmg_min_max_itemu = ($loot_tier * 5) - 2;
                    $damagemin = rand($dmg_min_min_itemu, $dmg_min_max_itemu);
                    $dmg_max_min_itemu = ($loot_tier * 5) - 1;
                    $dmg_max_max_itemu = ($loot_tier * 5);
                    $damagemax = rand($dmg_max_min_itemu, $dmg_max_max_itemu);
                    $attack_speed_min_itemu = 1.4;
                    $attack_speed_max_itemu = 1.6;
                    $attackspeed = round(random_float($attack_speed_min_itemu, $attack_speed_max_itemu),1);
					$base_crit_min_itemu = 4 + $loot_tier;
					$base_crit_max_itemu = 6 + $loot_tier;
					$base_crit = rand($base_crit_min_itemu, $base_crit_max_itemu);
                    $type = "sztylet";
                    $main_type = "lefthand";
                    $names = [
						1=>"Zardzewiały sztylet", 2=>"Miedziany sztylet",
					];
                    $name = $names[$loot_tier];
                    break;
				case 2:   //Axe     
                    $dmg_min_min_itemu = ($loot_tier * 5) + 1;
                    $dmg_min_max_itemu = ($loot_tier * 5) + 2;
                    $damagemin = rand($dmg_min_min_itemu, $dmg_min_max_itemu);
                    $dmg_max_min_itemu = ($loot_tier * 5) + 3;
                    $dmg_max_max_itemu = ($loot_tier * 5) + 5;
                    $damagemax = rand($dmg_max_min_itemu, $dmg_max_max_itemu);
                    $attack_speed_min_itemu = 0.7;
                    $attack_speed_max_itemu = 1.0;
                    $attackspeed = round(random_float($attack_speed_min_itemu, $attack_speed_max_itemu),1);
					$base_crit_min_itemu = 3 + $loot_tier;
					$base_crit_max_itemu = 6 + $loot_tier;
					$base_crit = rand($base_crit_min_itemu, $base_crit_max_itemu);
                    $type = "topór";
                    $main_type = "lefthand";
                    $names = [
						1=>"Zardzewiały toporek", 2=>"Miedziany topór",
					];
                    $name = $names[$loot_tier];
                    break;
				case 3:  //Bow
					$dmg_min_min_itemu = ($loot_tier * 5) - 2;
                    $dmg_min_max_itemu = ($loot_tier * 5);
                    $damagemin = rand($dmg_min_min_itemu, $dmg_min_max_itemu);
                    $dmg_max_min_itemu = ($loot_tier * 5) + 1;
                    $dmg_max_max_itemu = ($loot_tier * 5) + 3;
                    $damagemax = rand($dmg_max_min_itemu, $dmg_max_max_itemu);
                    $attack_speed_min_itemu = 0.8;
                    $attack_speed_max_itemu = 1.1;
                    $attackspeed = round(random_float($attack_speed_min_itemu, $attack_speed_max_itemu),1);
					$base_crit_min_itemu = 5 + $loot_tier;
					$base_crit_max_itemu = 8 + $loot_tier;
					$base_crit = rand($base_crit_min_itemu, $base_crit_max_itemu);
                    $type = "łuk";
                    $main_type = "lefthand";
                    $names = [
						1=>"Prosty łuk", 2=>"Krótki łuk",
					];
                    $name = $names[$loot_tier];
					break;
                case 4:              //Chest        7-15
                    $pancerz_min_itemu = ($loot_tier * 7);
                    $pancerz_max_itemu = ($loot_tier * 15);
                    $armor = rand($pancerz_min_itemu, $pancerz_max_itemu);
                    $type = "pancerz";
                    $main_type = "chest";
                    $names = [
						1=>"Kamizelka", 2=>"Napierśnik",
					];
                    $name = $names[$loot_tier];
                    break;
                default:
            }
			
			
			$chance_normal = 0;      //76%
            $chance_magic = 0.76;    //18%
            $chance_rare = 0.94;     //5%
            $chance_unique = 0.99;   //1%
			$cena_multiplier = 0;
			
			
			//LOSOWANIE MODÓW OD RZADKOŚCI
			$rarity_roll = random_float(0,1);
            if(($rarity_roll >= $chance_normal) and ($rarity_roll < $chance_magic))
            {
                $rarity = 'Normalny';
				$cena_multiplier = 1;
            }
            else if (($rarity_roll >= $chance_magic) and ($rarity_roll < $chance_rare))
            {
                $rarity = 'Magiczny';
				$cena_multiplier = 2;
				
				//MODY BRONI
				if($main_type == "lefthand")
				{
					$mod = rand(0,3);
					switch($mod)
					{
						case 0: 
							$damage_ogien = rand(1,5);
							$name = $name . " żaru";
							break;
						case 1:
							$damage_woda = rand(1,5);
							$name = $name . " potoku";
							break;
						case 2:
							$damage_powietrze = rand(1,5);
							$name = $name . " zefiru";
							break;
						case 3:
							$damage_ziemia = rand(1,5);
							$name = $name . " natury";
							break;
						default:
					}
				}
				//MODY RESZTY EQ
				else
				{
					$mod = rand(0,7);
					switch($mod)
					{
						case 0: 
							$sila = rand(1,3);
							$name = $name . " krzepy";
							break;
						case 1:
							$zwinnosc = rand(1,3);
							$name = $name . " chyżości";
							break;
						case 2:
							$celnosc = rand(1,3);
							$name = $name . " trafności";
							break;
						case 3:
							$kondycja = rand(1,3);
							$name = $name . " wytrzymałości";
							break;
						case 4: 
							$inteligencja = rand(1,3);
							$name = $name . " pomyślunku";
							break;
						case 5:
							$wiedza = rand(1,3);
							$name = $name . " oczytania";
							break;
						case 6:
							$charyzma = rand(1,3);
							$name = $name . " oczarowania";
							break;
						case 7:
							$szczescie = rand(1,3);
							$name = $name . " pomyślności";
							break;
						default:
					}
				}
            }
            else if (($rarity_roll >= $chance_rare) and ($rarity_roll < $chance_unique))
            {
                $rarity = 'Rzadki';
				$cena_multiplier = 3;
				
				if($main_type == "lefthand")
				{
					$mod = rand(0,3);
					switch($mod)
					{
						case 0: 
							$damage_ogien = rand(5,10);
							$name = $name . " pożogi";
							break;
						case 1:
							$damage_woda = rand(5,10);
							$name = $name . " jeziora";
							break;
						case 2:
							$damage_powietrze = rand(5,10);
							$name = $name . " wichru";
							break;
						case 3:
							$damage_ziemia = rand(5,10);
							$name = $name . " uskoku";
							break;
						default:
					}
				}
            }
            else if ($rarity_roll >= $chance_unique)
            {
                $rarity = 'Unikalny';
				$cena_multiplier = 5;
				
				if($main_type == "lefthand")
				{
					$mod = rand(0,3);
					switch($mod)
					{
						case 0: 
							$damage_ogien = rand(10,15);
							$name = $name . " inferno";
							break;
						case 1:
							$damage_woda = rand(10,15);
							$name = $name . " powodzi";
							break;
						case 2:
							$damage_powietrze = rand(10,15);
							$name = $name . " huraganu";
							break;
						case 3:
							$damage_ziemia = rand(10,15);
							$name = $name . " lawiny";
							break;
						default:
					}
				}
            }
            
			$cena = rand(1,($loot_tier*5)) * $cena_multiplier;
            
			
			//PRZYGOTOWANIE DO ZAPISU W BAZIE DANYCH
            $conn = connectDB();
            $name = $conn->real_escape_string($name); 
            $rarity = $conn->real_escape_string($rarity);
            $type = $conn->real_escape_string($type); 
            $main_type = $conn->real_escape_string($main_type);
            
            
			//ZAPIS W BAZIE DANYCH JEŻELI JEST WOLNY SLOT
            for ($i = 1; $i <= 15; $i++)
            {
                $slotName = 'slot' . $i;
                
                $checkSlot = get_stat($slotName,'equipment',$id_atakera);
                if ($checkSlot == 0)
                {
                    $conn->query("INSERT INTO items (name, rarity, type, main_type, image_id, cena, damagemin, damagemax, damage_ogien, damage_woda, damage_powietrze, damage_ziemia, attackspeed, base_crit, armor, sila, zwinnosc, celnosc, kondycja, inteligencja, wiedza, charyzma, szczescie, crit_chance, crit_damage) VALUES ('$name','$rarity','$type','$main_type','$image_id','$cena','$damagemin','$damagemax','$damage_ogien','$damage_woda','$damage_powietrze','$damage_ziemia','$attackspeed','$base_crit','$armor','$sila','$zwinnosc','$celnosc','$kondycja','$inteligencja','$wiedza','$charyzma','$szczescie','$crit_chance','$crit_damage')");
                    $last_id = $conn->insert_id;
                    
                    set_stat('equipment',$slotName,$last_id,$id_atakera);
                    array_push($rezultat,"<br>Zdobyłeś " . $span_zdobyty_loot . $name . $endSpan . "!");
                    break;
                }
                else if ($i == 15)
                {
                    array_push($rezultat,"<br>" . $span_brak_miejsca . "Brak miejsca w ekwipunku, odrzucono " .$endSpan .$span_zdobyty_loot . $name . $endSpan . ".");
                }
            }
            
			$conn->close();
            
        }
		
		return $rezultat;
    }
	
    function random_float ($min,$max) 
    {
        return ($min+lcg_value()*(abs($max-$min)));
    }

?>





<script src="jquery-ui-1.12.1/jquery-3.1.1.js"></script>
<script src="jquery-ui-1.12.1/jquery-ui.js"></script>


<script>

	//ANIMACJA WEJŚCIOWA
	$("#divAtakera").animate({
		left: "495px",
		opacity: "1.0",
	}, 1000, function() {
		
	});
	$("#divObroncy").animate({
		right: "495px",
		opacity: "1.0",
	}, 1000, function() {
		//$("#divVs").fadeIn();
	});
	
	
	$("#divVs").stop(true,true).delay(700).animate({
		opacity: "1.0",
	}, 1000);
	$("#walkaOkno").stop(true,true).delay(700).animate({
		opacity: "1.0",
	}, 1000);
	$("#hpAtakeraContainer").stop(true,true).delay(700).animate({
		opacity: "1.0",
	}, 1000);
	$("#hpObroncyContainer").stop(true,true).delay(700).animate({
		opacity: "1.0",
	}, 1000);
	
	
	//HP BARY ATAKERA I OBROŃCY
	var atakerHP = <?php echo json_encode($atakerHP); ?>;
	var atakerMaxHP = <?php echo json_encode($atakerMaxHP); ?>;
	var procAtakera = Math.round((atakerHP / atakerMaxHP) * 100);
	var obroncaHP = <?php echo json_encode($obroncaHP); ?>;
	var obroncaMaxHP = <?php echo json_encode($obroncaMaxHP); ?>;
	var procObroncy = Math.round((obroncaHP / obroncaMaxHP) * 100);
	$("#hpAtakeraContainer").append("<div id='outerHPatakera' style='border-radius: 15px; -webkit-box-shadow: inset 0 2px 5px #AAA; border: 2px solid; background: SeaShell; width: 100%; height: 100%;'>");
	$("#outerHPatakera").append("<div id='innerHPatakera' style='width:" + procAtakera + "%; height: 100%; background-color: green; border-radius: 15px;'>");
	$("#innerHPatakera").append("<div id='textHPatakera' style='text-align: center; position: absolute; width: 100%'>");
	$("#textHPatakera").append("HP: " + atakerHP + "/" + atakerMaxHP);
	$("#hpAtakeraContainer").append("</div></div></div>");
	$("#innerHPatakera").css("background-color", color(atakerHP, atakerMaxHP));
	
	$("#hpObroncyContainer").append("<div id='outerHPobroncy' style='border-radius: 15px; -webkit-box-shadow: inset 0 2px 5px #AAA; border: 2px solid; background: SeaShell; width: 100%; height: 100%;'>");
	$("#outerHPobroncy").append("<div id='innerHPobroncy' style='width:" + procObroncy + "%; height: 100%; background-color: green; border-radius: 15px;'>");
	$("#innerHPobroncy").append("<div id='textHPobroncy' style='text-align: center; position: absolute; width: 100%'>");
	$("#textHPobroncy").append("HP: " + obroncaHP + "/" + obroncaMaxHP);
	$("#hpObroncyContainer").append("</div></div></div>");
	$("#innerHPobroncy").css("background-color", color(obroncaHP, obroncaMaxHP));
	
	
	var battle = <?php echo json_encode($battle); ?>;
	var ataker = <?php echo json_encode($atakername); ?>;
	var obronca = <?php echo json_encode($obroncaname); ?>;
	var atakeratakuje = ataker + "</span> atakuje ";
	var obroncaatakuje = obronca + "</span> atakuje ";
	var atakerczaruje = ataker + "</span> używa czaru";
	var obroncaczaruje = obronca + "</span> używa czaru";
	$("#divAtakera").css("background","red");
	$("#divObroncy").css("background","red");
	
	
	//ILE SEKUND MA TRWAĆ WALKA
	var maxLengthSeconds = 30;
	var szybkosc = maxLengthSeconds / battle.length * 1000;
	if (szybkosc > 1000)	{szybkosc = 1000;}
	var szybkoscFade = 300 * szybkosc/1000;
	var szybkoscShake = 25 * szybkosc/1000;

	
	var i = 0;
	printuj();

	
	function printuj()
	{
		flashRed();
		shakeOnCrit();
		updateHPonHit(obroncaatakuje, obroncaczaruje, "#textHP", "#innerHP", 300);
		updateHPonHit(obroncaatakuje, obroncaczaruje, "#textHPatakera", "#innerHPatakera", 120);
		updateHPonHit(atakeratakuje, atakerczaruje, "#textHPobroncy", "#innerHPobroncy", 120);
		
		$("#walkaOkno").append(battle[i]);
		$("#walkaOkno").scrollTop($("#walkaOkno").get(0).scrollHeight);
		
		if(i+1 < battle.length)
		{
			i++;
			setTimeout(printuj, szybkosc);
		}
	}
	function shakeOnCrit()
	{
		if (~battle[i].indexOf("Trafienie krytyczne!"))
		{
			$("#walkaOkno").delay(0).animate({
				left: "+=5px",
			},szybkoscShake);
			$("#walkaOkno").delay(szybkoscShake).animate({
				left: "-=10px",
			},szybkoscShake);
			$("#walkaOkno").delay(szybkoscShake*2).animate({
				left: "+=5px",
			},szybkoscShake);
			
		}
	}
	function flashRed()
	{
		if(~battle[i].indexOf(obroncaatakuje) && ~battle[i].indexOf("Zadaje"))
		{
			$("#divAtakera").find('img').fadeTo(szybkoscFade, 0.7);
			$("#divAtakera").find('img').fadeTo(szybkoscFade, 1.0);
		}
		else if (~battle[i].indexOf(atakeratakuje) && ~battle[i].indexOf("Zadaje"))
		{
			$("#divObroncy").find('img').fadeTo(szybkoscFade, 0.7);
			$("#divObroncy").find('img').fadeTo(szybkoscFade, 1.0);
		}
		else if (~battle[i].indexOf(obroncaczaruje) && ~battle[i].indexOf("zadaje"))
		{
			$("#divAtakera").find('img').fadeTo(szybkoscFade, 0.7);
			$("#divAtakera").find('img').fadeTo(szybkoscFade, 1.0);
		}
		else if (~battle[i].indexOf(atakerczaruje) && ~battle[i].indexOf("zadaje"))
		{
			$("#divObroncy").find('img').fadeTo(szybkoscFade, 0.7);
			$("#divObroncy").find('img').fadeTo(szybkoscFade, 1.0);
		}
	}
	function updateHPonHit(tekst1, tekst2, textHPdiv, innerHPdiv, dlugoscPaska)
	{
		if(~battle[i].indexOf(tekst1) || ~battle[i].indexOf(tekst2))
		{
			if(~battle[i].indexOf("Pozostało"))
			{
				var str = battle[i];
				var index1 = str.indexOf("Pozostało <span style='color: red;'>") + "Pozostało <span style='color: red;'>".length;
				var index2 = str.indexOf("</span> życia");
				var substr = str.substring(index1, index2);
				var aktHP = parseInt(substr);
				
				var str = $(textHPdiv).html();
				var substr = str.substring(str.indexOf('/') + 1,str.length);
				var maxHP = parseInt(substr);
				
				var cale = "HP: " + aktHP + "/" + maxHP;
				$(textHPdiv).html(cale);
				
				var proc = aktHP/maxHP;
				var nowaDlugosc = proc * dlugoscPaska;
				$(innerHPdiv).css("width", nowaDlugosc);
				$(innerHPdiv).css("background-color",color(aktHP,maxHP));
			}
			else if(~battle[i].indexOf("umiera"))
			{
				var aktHP = 0;
				
				var str = $(textHPdiv).html();
				var substr = str.substring(str.indexOf('/') + 1,str.length);
				var maxHP = parseInt(substr);
				
				var cale = "HP: " + aktHP + "/" + maxHP;
				$(textHPdiv).html(cale);
				
				var proc = aktHP/maxHP;
				var nowaDlugosc = proc * dlugoscPaska;
				$(innerHPdiv).css("width", nowaDlugosc);
				//$(innerHPdiv).css("background-color",color(aktHP,maxHP));
			}
		}
	}
	function color(current, max)
    {
        var percent = Math.round((current/max)*100);

        var green = Math.round((percent*255)/100);
        var red = 255-green;

        return "rgb(" + red + ", " + green + ", 00)";
    }
</script>
