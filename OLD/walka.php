<?php

    require_once('config.php');
    login_check();
	
	
	if($_POST)
	{
		$typy_atakerow = [];
		$typy_obroncow = [];
		$id_atakerow = [];
		$id_obroncow = [];
		
		if(isset($_POST['miejsce']))
		{
			//ATAKER
			array_push($typy_atakerow, 'player');
			array_push($id_atakerow, $_SESSION['id']);
			$conn = connectDB();
			$id = $_SESSION['id'];
			$conn->query("UPDATE users SET wyprawa_until=NULL, destination='false' WHERE id=$id");
			$conn->close();
			
			//POTWORY
			$iloscMin = 1;
			$iloscMax = 1;
			
			if(strpos($_POST['miejsce'], 'easy') !== false)
			{
				$iloscMin = 3;
				$iloscMax = 5;
			}
			if(strpos($_POST['miejsce'], 'medium') !== false)
			{
				$iloscMin = 1;
				$iloscMax = 2;
			}
			if(strpos($_POST['miejsce'], 'hard') !== false)
			{
				$iloscMin = 2;
				$iloscMax = 3;
			}
			
			$ilosc = rand($iloscMin, $iloscMax);
			for($i = 0; $i < $ilosc; $i++)
			{
				array_push($typy_obroncow, 'monster');
				array_push($id_obroncow, losuj_potwora($_POST['miejsce']));
			}
		}
		
		
		
		
		$iloscAtakerow = count($typy_atakerow);
		$iloscObroncow = count($typy_obroncow);
		$atakerzy = [];
		$obroncy = [];
		
		
		echo "<div id='atakerzy' style='display: none'>";
		for($i = 0; $i < $iloscAtakerow; $i++)
		{
			if($typy_atakerow[$i] == 'player') {$ataker = sum_player($id_atakerow[$i]);}
			else 							   {$ataker = sum_monster($id_atakerow[$i]);}
			array_push($atakerzy, $ataker);
			
			echo "<div class='ataker' id='" . $i . "'>";
			
				echo "<div class='imageContainer'>";
				if($typy_atakerow[$i] == 'player') 	{echo read_stats($id_atakerow[$i], 'zdjecie');}
				else								{echo read_stats($id_atakerow[$i], 'zdjeciePotwora');}
				echo "</div>";
				echo "<div class='healthBar'></div>";
			
			echo "</div>";
		}
		echo "</div>";
		
		
		echo "<div id='obroncy' style='display: none'>";
		for($i = 0; $i < $iloscObroncow; $i++)	
		{
			if($typy_obroncow[$i] == 'player') {$obronca = sum_player($id_obroncow[$i]);}
			else 							   {$obronca = sum_monster($id_obroncow[$i]);}
			array_push($obroncy, $obronca);
			
			echo "<div class='obronca' id='" . $i . "'>";
			
				echo "<div class='imageContainer'>";
				if($typy_obroncow[$i] == 'player') 	{echo read_stats($id_obroncow[$i], 'zdjecie');}
				else								{echo read_stats($id_obroncow[$i], 'zdjeciePotwora');}
				echo "</div>";
				echo "<div class='healthBar'></div>";
			
			echo "</div>";
		}
		echo "</div>";
		
		
		
		$arr = Attack_multi($typy_atakerow, $typy_obroncow, $id_atakerow, $id_obroncow, $_POST['typ_walki']);
		$battle = $arr['rezultat'];
		
		
		echo "<div id='walkaOkno'>";
		echo "</div>";
	}
	
	function losuj_potwora($miejsce)
	{
		$conn = connectDB();
		$eMiejsce = $conn->real_escape_string($miejsce);
		$result = $conn->query("SELECT id FROM monsters WHERE miejsce='$eMiejsce'");
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
	
	function Attack_multi($typy_atakerow, $typy_obroncow, $id_atakerow, $id_obroncow, $typ_walki)
	{
		$czas_rundy = 5;
		$runda = 0;
		$rezultat = [];
		$polegli = [];
		
		$arr = inicjalizuj_walczacych($typy_atakerow, $typy_obroncow, $id_atakerow, $id_obroncow);
		$atakerzy = $arr['atakerzy'];
		$obroncy = $arr['obroncy'];
		$atakerzyInitial = $arr['atakerzy'];
		$obroncyInitial = $arr['obroncy'];
		$zwyciezca = '';
		
		
		while((count($atakerzy) > 0) and (count($obroncy) > 0))
		{
			//KONIEC RUNDY?
			$arr = nastepna_runda($rezultat, $runda, $atakerzy, $obroncy);
			$rezultat = $arr['rezultat'];
			$runda = $arr['runda'];
			$atakerzy = $arr['atakerzy'];
			$obroncy = $arr['obroncy'];
			
			//MAGIA STARTOWA
			$arr = magia_startowa($atakerzy, $obroncy, $rezultat, $polegli);
			$atakerzy = $arr['atakerzy'];
			$obroncy = $arr['obroncy'];
			$rezultat = $arr['rezultat'];
			$polegli = $arr['polegli'];
			
			if(count($atakerzy) == 0)
			{
				$zwyciezca = 'obroncy';
				$rezultat = koniec_walki($zwyciezca, $atakerzyInitial, $obroncyInitial, $rezultat);
				break;
			}
			else if(count($obroncy) == 0)
			{
				$zwyciezca = 'atakerzy';
				$rezultat = koniec_walki($zwyciezca, $atakerzyInitial, $obroncyInitial, $rezultat);
				break;
			}
			
			//MAGIA BITEWNA
			$arr = magia_spamowalna($atakerzy, $obroncy, $rezultat, $polegli);
			$atakerzy = $arr['atakerzy'];
			$obroncy = $arr['obroncy'];
			$rezultat = $arr['rezultat'];
			$polegli = $arr['polegli'];
			
			if(count($atakerzy) == 0)
			{
				$zwyciezca = 'obroncy';
				$rezultat = koniec_walki($zwyciezca, $atakerzyInitial, $obroncyInitial, $rezultat);
				break;
			}
			else if(count($obroncy) == 0)
			{
				$zwyciezca = 'atakerzy';
				$rezultat = koniec_walki($zwyciezca, $atakerzyInitial, $obroncyInitial, $rezultat);
				break;
			}
			
			//HITY
			$arr = ataki_bronia($atakerzy, $obroncy, $rezultat, $polegli);
			$atakerzy = $arr['atakerzy'];
			$obroncy = $arr['obroncy'];
			$rezultat = $arr['rezultat'];
			$polegli = $arr['polegli'];
			
			if(count($atakerzy) == 0)
			{
				$zwyciezca = 'obroncy';
				$rezultat = koniec_walki($zwyciezca, $atakerzyInitial, $obroncyInitial, $rezultat);
				break;
			}
			else if(count($obroncy) == 0)
			{
				$zwyciezca = 'atakerzy';
				$rezultat = koniec_walki($zwyciezca, $atakerzyInitial, $obroncyInitial, $rezultat);
				break;
			}
			
		}
		
		ustaw_graczy($atakerzy, $obroncy, $polegli, $typ_walki);
		$rezultat = dodaj_expa($zwyciezca, $atakerzyInitial, $obroncyInitial, $rezultat);
		$rezultat = dodaj_golda($zwyciezca, $atakerzyInitial, $obroncyInitial, $rezultat);
		$rezultat = dodaj_loot($zwyciezca, $atakerzyInitial, $obroncyInitial, $rezultat, $typ_walki);
		wyslij_wiadomosci($atakerzyInitial, $obroncyInitial, $typ_walki, $rezultat);
		
		
		return [
			'rezultat' => $rezultat,
		];
	}
	
	function inicjalizuj_walczacych($typy_atakerow, $typy_obroncow, $id_atakerow, $id_obroncow)
	{
		//KOLORY
		$endSpan = "</span>";
		$span_rundy = "<span style='font-weight: bold; color: dimGray;'>";
        $span_nicku_atakera = "<span style='color: darkgreen; font-weight: bold;'>";
        $span_nicku_obroncy = "<span style='color: darkorange;'>";
		$span_nazwy_ataku_atakera = "<span style='color: darkblue;'>";
        $span_nazwy_ataku_obroncy = "<span style='color: brown;'>";
		
		
		$ilosc_atakerow = count($typy_atakerow);
		$ilosc_obroncow = count($typy_obroncow);
		$atakerzy = [
		];
		$obroncy = [
		];
		
		
		for($i = 0; $i < $ilosc_atakerow; $i++)
		{
			if($typy_atakerow[$i] == 'player')
			{
				update_logic($id_atakerow[$i]);
				$atakerzy[$i] = sum_player($id_atakerow[$i]);
				$atakerzy[$i]['wykonano_ruch'] = false;
				$atakerzy[$i]['wykonano_czary_startowe'] = false;
				$atakerzy[$i]['tylko_czary'] = false;
				$atakerzy[$i]['mial_mane'] = true;
				$atakerzy[$i]['tekst_nick'] = $span_nicku_atakera . $atakerzy[$i]['name'] . $endSpan;
				$atakerzy[$i]['tekst_nazwa_ataku'] = $span_nazwy_ataku_atakera . $atakerzy[$i]['attack_name'] . $endSpan;
				$atakerzy[$i]['strona'] = 'Ataker';
				$atakerzy[$i]['typ'] = 'player';
				$atakerzy[$i]['id'] = $id_atakerow[$i];
			}
		}
		
		for($j = 0; $j < $ilosc_obroncow; $j++)
		{
			if($typy_obroncow[$j] == 'player')
			{
				update_logic($id_obroncow[$j]);
				$obroncy[$j] = sum_player($id_obroncow[$j]);
				$obroncy[$j]['typ'] = 'player';
			}
			else if($typy_obroncow[$j] == 'monster')
			{
				$obroncy[$j] = sum_monster($id_obroncow[$j]);
				$obroncy[$j]['typ'] = 'monster';
			}
			
			$obroncy[$j]['wykonano_ruch'] = false;
			$obroncy[$j]['wykonano_czary_startowe'] = false;
			$obroncy[$j]['tylko_czary'] = false;
			$obroncy[$j]['mial_mane'] = true;
			$obroncy[$j]['tekst_nick'] = $span_nicku_obroncy . $obroncy[$j]['name'] . $endSpan;
			$obroncy[$j]['tekst_nazwa_ataku'] = $span_nazwy_ataku_obroncy . $obroncy[$j]['attack_name'] . $endSpan;
			$obroncy[$j]['strona'] = 'Obronca';
			$obroncy[$j]['id'] = $id_obroncow[$j];
		}
		
		$arr = [
			'atakerzy' => $atakerzy,
			'obroncy' => $obroncy
		];
		
		return $arr;
	}
	
	function nastepna_runda($rezultat, $runda, $atakerzy, $obroncy)
	{
		//KOLORY
		$endSpan = "</span>";
		$span_rundy = "<span style='font-weight: bold; color: dimGray;'>";
		$czas_rundy = 5;
		
		
		//SPRAWDZAMY CZY ATAKERZY SIĘ RUSZYLI
		$atakerzy_sie_ruszyli = false;
		for ($i = 0; $i < count($atakerzy); $i++)
		{
			if($atakerzy[$i]['wykonano_ruch'] == true)
			{
				$atakerzy_sie_ruszyli = true;
			}
		}
		
		//SPRAWDZAMY CZY OBROŃCY SIĘ RUSZYLI
		$obroncy_sie_ruszyli = false;
		for ($j = 0; $j < count($obroncy); $j++)
		{
			if($obroncy[$j]['wykonano_ruch'] == true)
			{
				$obroncy_sie_ruszyli = true;
			}
		}
		
		//NIKT SIĘ NIE RUSZYŁ -> NOWA TURA
		if (($atakerzy_sie_ruszyli == false) and ($obroncy_sie_ruszyli == false))
		{
			$runda++;
			if ($runda == 1)		{array_push($rezultat, $span_rundy . "Runda " . $runda . $endSpan . "<br><br>");}
			else					{array_push($rezultat, "<br>" . $span_rundy . "Runda " . $runda . $endSpan . "<br><br>");}
			
			
			//PRZYDZIELAMY RUCH ATAKEROM
			for ($i = 0; $i < count($atakerzy); $i++)
			{
				$atakerzy[$i]['wykonano_ruch'] = true;
				$atakerzy[$i]['pozostaly_czas'] = $czas_rundy;
			}
			//PRZYDZIELAMY RUCH OBROŃCOM
			for ($j = 0; $j < count($obroncy); $j++)
			{
				$obroncy[$j]['wykonano_ruch'] = true;
				$obroncy[$j]['pozostaly_czas'] = $czas_rundy;
			}
		}
		
		
		
		$koniec = [
			'rezultat' => $rezultat,
			'runda' => $runda,
			'atakerzy' => $atakerzy,
			'obroncy' => $obroncy
		];
		return $koniec;
	}
	
	function magia_startowa($atakerzy, $obroncy, $rezultat, $polegli)
	{
		$walczacy = [];
		for($i = 0; $i < count($atakerzy); $i++)
		{
			array_push($walczacy, $atakerzy[$i]);
		}
		for($i = 0; $i < count($obroncy); $i++)
		{
			array_push($walczacy, $obroncy[$i]);
		}
		shuffle($walczacy);
		
		
		
		
		foreach($walczacy as $k => $v)
		{
			if($walczacy[$k]['wykonano_czary_startowe'] == false)
			{
				for($c = 1; $c <= 10; $c++)
				{
					$prior = 'priorytet' . $c;
					$czar = 'czar' . $c;
				
					//ZNALEZIONO CZAR SPAMOWALNY, USTAWIAMY NA PRZYSZLOSC
					if($walczacy[$k]['spellbook'][$prior] == 'spam')
					{
						$walczacy[$k]['tylko_czary'] = true;
					}
				
					//ZNALEZIONO CZAR STARTOWY
					if($walczacy[$k]['spellbook'][$prior] == 'start')
					{
						//SZUKAMY PRZECIWNIKA
						if($walczacy[$k]['strona'] == 'Ataker')
						{
							for($i = 0; $i < count($walczacy); $i++)
							{
								if($walczacy[$i]['strona'] == 'Obronca')
								{
									break;
								}
							}
						}
						else
						{
							for($i = 0; $i < count($walczacy); $i++)
							{
								if($walczacy[$i]['strona'] == 'Ataker')
								{
									break;
								}
							}
						}
						
						//RZUCAMY CZAR
						$walczacy[$k]['wykonano_czary_startowe'] = false;
						$id_spella = $walczacy[$k]['spellbook'][$czar];
						$arr = cast_spell($walczacy[$k], $walczacy[$i], $id_spella, $rezultat);
						$walczacy[$k] = $arr['ataker'];
						$walczacy[$i] = $arr['obronca'];
						$rezultat = $arr['rezultat'];
						
						
						$walczacy[$k]['spellbook'][$prior] = '';
					
						//USUWAMY MARTWYCH
						if($walczacy[$i]['hp'] <= 0)
						{
							array_push($polegli, $walczacy[$i]);
							unset($walczacy[$i]);
						}
						
						break;
					}
					
					//SKOŃCZYLIŚMY MAGIĘ STARTOWĄ DLA DANEGO GRACZA
					if($c == 10)
					{
						$walczacy[$k]['wykonano_czary_startowe'] = true;
						$walczacy[$k]['wykonano_ruch'] = false;
					}
				}
			}
		}
		
		
		unset($atakerzy);
		unset($obroncy);
		$atakerzy = [];
		$obroncy = [];
		
		
		foreach($walczacy as $k => $v)
		{
			if($v['strona'] == 'Ataker')
			{
				array_push($atakerzy, $walczacy[$k]);
			}
			else
			{
				array_push($obroncy, $walczacy[$k]);
			}
		}
		
		return [
			'atakerzy' => $atakerzy,
			'obroncy' => $obroncy,
			'rezultat' => $rezultat,
			'polegli' => $polegli
		];
	}
	
	function magia_spamowalna($atakerzy, $obroncy, $rezultat, $polegli)
	{
		$walczacy = [];
		for($i = 0; $i < count($atakerzy); $i++)
		{
			array_push($walczacy, $atakerzy[$i]);
		}
		for($i = 0; $i < count($obroncy); $i++)
		{
			array_push($walczacy, $obroncy[$i]);
		}
		shuffle($walczacy);
		
		
		
		foreach($walczacy as $k => $v)
		{	
			if(($v['wykonano_czary_startowe'] == true) and ($v['tylko_czary'] == true))
			{
				for($c = 1; $c <= 10; $c++)
				{
					$prior = 'priorytet' . $c;
					$czar = 'czar' . $c;
					
					//ZNALEZIONO CZAR SPAMOWALNY
					if($v['spellbook'][$prior] == 'spam')
					{
						//SZUKAMY PRZECIWNIKA
						if($v['strona'] == 'Ataker')
						{
							for($i = 0; $i < count($walczacy); $i++)
							{
								if($walczacy[$i]['strona'] == 'Obronca')
								{
									break;
								}
							}
						}
						else
						{
							for($i = 0; $i < count($walczacy); $i++)
							{
								if($walczacy[$i]['strona'] == 'Ataker')
								{
									break;
								}
							}
						}
						
						//RZUCAMY CZAR
						$v['tylko_czary'] = true;
						$id_spella = $v['spellbook'][$czar];
						$arr = cast_spell($v, $walczacy[$i], $id_spella, $rezultat);
						$walczacy[$k] = $arr['ataker'];
						$walczacy[$i] = $arr['obronca'];
						$rezultat = $arr['rezultat'];
						
						//ZABRAKŁO MANY WIEC USUWAMY TEN CZAR Z PRZYSZLYCH PROB
						if($v['mial_mane'] == false)
						{
							$v['spellbook'][$prior] = '';
						}
						
						//USUWAMY MARTWYCH
						if($walczacy[$i]['hp'] <= 0)
						{
							array_push($polegli, $walczacy[$i]);
							unset($walczacy[$i]);
						}
						
						break;
					}
					
					//SKOŃCZYLIŚMY DLA DANEGO GRACZA
					if($c == 10)
					{
						$v['tylko_czary'] = false;
						$v['wykonano_ruch'] = false;
					}
				}
			}
		}
		
		
		unset($atakerzy);
		unset($obroncy);
		$atakerzy = [];
		$obroncy = [];
		
		
		foreach($walczacy as $k => $v)
		{
			if($v['strona'] == 'Ataker')
			{
				array_push($atakerzy, $walczacy[$k]);
			}
			else
			{
				array_push($obroncy, $walczacy[$k]);
			}
		}
		
		return [
			'atakerzy' => $atakerzy,
			'obroncy' => $obroncy,
			'rezultat' => $rezultat,
			'polegli' => $polegli
		];

	}

	function ataki_bronia($atakerzy, $obroncy, $rezultat, $polegli)
	{
		$walczacy = [];
		for($i = 0; $i < count($atakerzy); $i++)
		{
			array_push($walczacy, $atakerzy[$i]);
		}
		for($i = 0; $i < count($obroncy); $i++)
		{
			array_push($walczacy, $obroncy[$i]);
		}
		shuffle($walczacy);
		
		
		foreach($walczacy as $k => $v)
		{
			if($v['tylko_czary'] == false)
			{	
				//SZUKAMY PRZECIWNIKA
				if($v['strona'] == 'Ataker')
				{
					for($i = 0; $i < count($walczacy); $i++)
					{
						if($walczacy[$i]['strona'] == 'Obronca')
						{
							break;
						}
					}
				}
				else
				{
					for($i = 0; $i < count($walczacy); $i++)
					{
						if($walczacy[$i]['strona'] == 'Ataker')
						{
							break;
						}
					}
				}
				
				$arr = hit($v, $walczacy[$i], $rezultat);
				$walczacy[$k] = $arr['ataker'];
				$walczacy[$i] = $arr['obronca'];
				$rezultat = $arr['rezultat'];
				
				//USUWAMY MARTWYCH
				if($walczacy[$i]['hp'] <= 0)
				{
					array_push($polegli, $walczacy[$i]);
					unset($walczacy[$i]);
				}
						
				break;
			}
		}
		
		
		unset($atakerzy);
		unset($obroncy);
		$atakerzy = [];
		$obroncy = [];
		
		
		foreach($walczacy as $k => $v)
		{
			if($v['strona'] == 'Ataker')
			{
				array_push($atakerzy, $walczacy[$k]);
			}
			else
			{
				array_push($obroncy, $walczacy[$k]);
			}
		}
		
		return [
			'atakerzy' => $atakerzy,
			'obroncy' => $obroncy,
			'rezultat' => $rezultat,
			'polegli' => $polegli
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
			$ataker['name'] = "DZIALAJ KURWA";
	
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
			
				array_push($rezultat, $ataker['tekst_nick'] . " używa czaru " . $span_obrazen_czaru . $czary[$spellID][0] . $endSpan . " i zadaje " . $obronca['tekst_nick'] . " " . $span_obrazen_czaru . $damage . $endSpan . " obrażeń " . $typ_obrazen .  $obronca['tekst_zycia'] . "<br>");
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
	
	function dodaj_expa($zwyciezca, $atakerzyInitial, $obroncyInitial, $rezultat)
	{
		$span_zdobylesExpa = "<span style='color:darkgreen;'>";
        $span_zdobylesLevel = "<span style='font-weight: bold; color:darkslateblue;'>";
        $endSpan = "</span>";
		
		if($zwyciezca == 'atakerzy')
		{
			$array_zwyciezcow = $atakerzyInitial;
			$array_przegranych = $obroncyInitial;
		}
		else if($zwyciezca == 'obroncy')
		{
			$array_zwyciezcow = $obroncyInitial;
			$array_przegranych = $atakerzyInitial;
		}
		
		
		$exp_nalezny = 0;
		
		for($i = 0; $i < count($array_przegranych); $i++)
		{
			if($array_przegranych[$i]['typ'] == 'monster')
			{
				$exp_nalezny += get_stat('exp','monsters',$array_przegranych[$i]['id']);
			}
			else if($array_przegranych[$i]['typ'] == 'player')
			{
				$exp_nalezny += round(0.05 * get_stat('exp_next','users',$array_przegranych[$i]['id']));
			}
		}
		
		$exp_each = round($exp_nalezny / count($array_zwyciezcow));
		
		for($i = 0; $i < count($array_zwyciezcow); $i++)
		{
			if($array_zwyciezcow[$i]['typ'] == 'player')
			{
				$id = $array_zwyciezcow[$i]['id'];
				
				$aktualny_exp = get_stat('exp','users',$id);
				$exp_next = get_stat('exp_next','users',$id);
				$aktualny_exp += $exp_each;
				
				array_push($rezultat, $array_zwyciezcow[$i]['tekst_nick'] . " zdobywa " . $span_zdobylesExpa . $exp_each . $endSpan . " punktów doświadczenia!" . "<br>");
				
				if($aktualny_exp >= $exp_next)
				{
					$aktualnyLevel = get_stat('level','users',$id);
					$pozostale_punkty = get_stat('punkty_do_rozdania','users',$id);
					$maxhp = get_stat('maxhp','users',$id);
					$maxmana = get_stat('maxmana','users',$id);
					$aktualnyLevel += 1;
					$pozostale_punkty += 3;
					$exp_next = round($exp_next * 1.7);
					
					$conn = connectDB();
					$conn->query("UPDATE users SET level=$aktualnyLevel, exp=$aktualny_exp, exp_next=$exp_next, punkty_do_rozdania=$pozostale_punkty, hp=$maxhp, mana=$maxmana WHERE id=$id");
					$conn->close();			

					array_push($rezultat, $array_zwyciezcow[$i]['tekst_nick'] . " osiągnął " . $span_zdobylesLevel . $aktualnyLevel . $endSpan . " poziom!" . "<br>");
				}
				else
				{
					set_stat('users','exp',$aktualny_exp,$id);
				}
			}
		}
		
		return $rezultat;
	}

	function dodaj_golda($zwyciezca, $atakerzyInitial, $obroncyInitial, $rezultat)
	{
		$span_zdobywaszZloto = "<span style='color:saddleBrown;'>";
        $spanEnd = "</span>";
		
		if($zwyciezca == 'atakerzy')
		{
			$array_zwyciezcow = $atakerzyInitial;
			$array_przegranych = $obroncyInitial;
		}
		else if($zwyciezca == 'obroncy')
		{
			$array_zwyciezcow = $obroncyInitial;
			$array_przegranych = $atakerzyInitial;
		}
		
		
		
		$zloto_nalezne = 0;
		
		for($i = 0; $i < count($array_przegranych); $i++)
		{
			if($array_przegranych[$i]['typ'] == 'monster')
			{
				$zloto_nalezne += rand(0, get_stat('zloto','monsters',$array_przegranych[$i]['id']));
			}
			else if($array_przegranych[$i]['typ'] == 'player')
			{
				$zloto_nalezne += round(0.3 * get_stat('zloto','users',$array_przegranych[$i]['id']));
				$aktualne_zloto = get_stat('zloto','users',$array_przegranych[$i]['id']);
				$aktualne_zloto -= round(0.3 * $aktualne_zloto);
				set_stat('users','zloto',$aktualne_zloto,$array_przegranych[$i]['id']);
			}
		}
		
		$zloto_each = round($zloto_nalezne / count($array_zwyciezcow));
		
		for($i = 0; $i < count($array_zwyciezcow); $i++)
		{
			if($array_zwyciezcow[$i]['typ'] == 'player')
			{
				$id = $array_zwyciezcow[$i]['id'];
				$aktualne_zloto = get_stat('zloto','users',$id);
				$aktualne_zloto += $zloto_each;
				set_stat('users','zloto',$aktualne_zloto,$id);
				
				array_push($rezultat, $array_zwyciezcow[$i]['tekst_nick'] . " zdobywa " .$span_zdobywaszZloto . $zloto_each . $spanEnd . " złota!<br>");
			}
		}
		
		return $rezultat;
	}
	
	function dodaj_loot($zwyciezca, $atakerzyInitial, $obroncyInitial, $rezultat, $typ_walki)
	{
		if($typ_walki == 'wyprawa')
		{
			$span_zdobyty_loot = "<span style='color:darkblue;'>";
			$span_brak_miejsca = "<span style='color:darkred;'>";
			$endSpan = "</span>";
		
			if($zwyciezca == 'atakerzy')
			{
				$array_zwyciezcow = $atakerzyInitial;
				$array_przegranych = $obroncyInitial;
			}
			else if($zwyciezca == 'obroncy')
			{
				$array_zwyciezcow = $obroncyInitial;
				$array_przegranych = $atakerzyInitial;
			}
		}
		
		return $rezultat;
	}
	
	function ustaw_graczy($atakerzy, $obroncy, $polegli, $typ_walki)
	{	
		for($i = 0; $i < count($polegli); $i++)
		{
			if($polegli[$i]['typ'] == 'player')
			{
				set_stat('users','hp','0',$polegli[$i]['id']);
				set_stat('users','mana',$polegli[$i]['mana'],$polegli[$i]['id']);
				
				if($typ_walki == 'arena')
				{
					$sekundy = get_current_time();
					$sekundy += 900;
					insert_time($sekundy,'protected_until',$polegli[$i]['id']);
				}
			}
		}
		
		for($i = 0; $i < count($atakerzy); $i++)
		{
			if($atakerzy[$i]['typ'] == 'player')
			{
				set_stat('users','hp',$atakerzy[$i]['hp'],$atakerzy[$i]['id']);
				set_stat('users','mana',$atakerzy[$i]['mana'],$atakerzy[$i]['id']);
			}
		}
		
		for($i = 0; $i < count($obroncy); $i++)
		{
			if($obroncy[$i]['typ'] == 'player')
			{
				set_stat('users','hp',$obroncy[$i]['hp'],$obroncy[$i]['id']);
				set_stat('users','mana',$obroncy[$i]['mana'],$obroncy[$i]['id']);
			}
		}
	}
	
	function wyslij_wiadomosci($atakerzyInitial, $obroncyInitial, $typ_walki, $rezultat)
	{
		$msg = join("", $rezultat);
		
		if($typ_walki == 'arena')
		{
			$title = 'Raport z areny';
		}
		else if($typ_walki == 'wyprawa')
		{
			$title = 'Raport z wyprawy';
		}
		
		for($i = 0; $i < count($atakerzyInitial); $i++)
		{
			send_message(0,'System',$atakerzyInitial[$i]['id'],$atakerzyInitial[$i]['name'],$msg,$title,1);
		}
		for($i = 0; $i < count($obroncyInitial); $i++)
		{
			send_message(0,'System',$obroncyInitial[$i]['id'],$obroncyInitial[$i]['name'],$msg,$title,0);
		}
	}
	
	function koniec_walki($zwyciezca, $atakerzyInitial, $obroncyInitial, $rezultat)
	{
		$msg = '';
		
		if($zwyciezca == 'atakerzy')
		{
			if(count($atakerzyInitial) > 1)
			{
				$msg = "<br><span style='font-weight: bold;'>Agresorzy zwyciężają!</span><br>";
			}
			else
			{
				$msg = "<br><span style='font-weight: bold;'>" . $atakerzyInitial[0]['name'] . " zwycięża!</span><br>";
			}
		}
		else
		{
			if(count($obroncyInitial) > 1)
			{
				$msg = "<br><span style='font-weight: bold;'>Obrońcy zwyciężają!</span><br>";
			}
			else
			{
				$msg = "<br><span style='font-weight: bold;'>" . $obroncyInitial[0]['name'] . " zwycięża!</span><br>";
			}
		}
		
		array_push($rezultat, $msg);
		return $rezultat;
	}

?>

<script src="jquery-ui-1.12.1/jquery-3.1.1.js"></script>
<script src="jquery-ui-1.12.1/jquery-ui.js"></script>