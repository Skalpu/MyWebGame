<?php

    require_once('config.php');
    login_check();
	$_SESSION['player']->updateLocally();

	if($_POST)
	{
		//BP -> ???
		if(strpos($_POST['poczatek'], 'bp') !== false)
		{
			
			//BP -> BP
			if(strpos($_POST['koniec'], 'bp') !== false)
			{
				preg_match('/(\d+)/', $_POST['poczatek'], $matches);
				$idPocz = $matches[1];
				preg_match('/(\d+)/', $_POST['koniec'], $matches);
				$idKon = $matches[1];

				//Swapping
				$holder = $_SESSION['player']->backpack[$idKon];
				$_SESSION['player']->backpack[$idKon] = $_SESSION['player']->backpack[$idPocz];
				$_SESSION['player']->backpack[$idPocz] = $holder;
				
				unset($holder);
				unset($idPocz);
				unset($idKon);
			}
			
			//BP -> EQ
			else
			{
				preg_match('/(\d+)/', $_POST['poczatek'], $matches);
				$idPocz = $matches[1];
				$idKon = $_POST['koniec'];
				
				//Checking if item types matches
				if($_SESSION['player']->backpack[$idPocz]->slot == $idKon)
				{
					//Swapping
					$holder = $_SESSION['player']->equipment[$idKon];
					$_SESSION['player']->equipment[$idKon] = $_SESSION['player']->backpack[$idPocz];
					$_SESSION['player']->backpack[$idPocz] = $holder;
					unset($holder);
				}
				
				unset($idPocz);
				unset($idKon);
			}
		}
		
		// EQ -> ???
		else
		{
			// EQ -> BP
			if(strpos($_POST['koniec'], 'bp') !== false)
			{
				preg_match('/(\d+)/', $_POST['koniec'], $matches);
				$idKon = $matches[1];
				$idPocz = $_POST['poczatek'];
				
				//BP is empty, moving
				if($_SESSION['player']->backpack[$idKon] == "")
				{
					$_SESSION['player']->backpack[$idKon] = $_SESSION['player']->equipment[$idPocz];
					$_SESSION['player']->equipment[$idPocz] = "";
				}
				//BP is not empty
				else
				{
					//Check types, see if we can swap
					if($_SESSION['player']->equipment[$idPocz]->slot == $_SESSION['player']->backpack[$idKon]->slot)
					{
						//Swapping
						$holder = $_SESSION['player']->backpack[$idKon];
						$_SESSION['player']->backpack[$idKon] = $_SESSION['player']->equipment[$idPocz];
						$_SESSION['player']->equipment[$idPocz] = $holder;
						unset($holder);
					}
					//Types not equal, check if other slots are empty
					else
					{
						for($i = 0; $i < count($_SESSION['player']->backpack); $i++)
						{
							if($_SESSION['player']->backpack[$i] == "")
							{
								//Found empty slot, moving
								$_SESSION['player']->backpack[$i] = $_SESSION['player']->equipment[$idPocz];
								$_SESSION['player']->equipment[$idPocz] = "";
							}
						}
					}
				}
				
				unset($idKon);
				unset($idPocz);
			}
		}
	}
	
	drawEquipment($_SESSION['player']); 
	drawBackpack($_SESSION['player']);
?>