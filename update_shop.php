<?php

    require_once('config.php');
    login_check();

	//TODO
	if($_POST)
	{
		//BP ->
		if(strpos($_POST['poczatek'], 'bp') !== false)
		{
			
			
			//------------------
			//BP -> BP
			//------------------
			if(strpos($_POST['koniec'], 'bp') !== false)
			{
				preg_match('/(\d+)/', $_POST['poczatek'], $matches);
				$idPocz = $matches[1];
				preg_match('/(\d+)/', $_POST['koniec'], $matches);
				$idKon = $matches[1];

				//Setting variables
				$conn = connectDB();
				$id = $_SESSION['player']->id;
				$slotPocz = "slot" . $idPocz;
				$slotKon = "slot" . $idKon;
				
				//Updating locally
				$holder = $_SESSION['player']->backpack[$idKon];
				$_SESSION['player']->backpack[$idKon] = $_SESSION['player']->backpack[$idPocz];
				$_SESSION['player']->backpack[$idPocz] = $holder;
				
				//Updating to DB
				if($_SESSION['player']->backpack[$idPocz] == ""){
					$valPocz = "NULL";
				}
				else{
					$valPocz = $_SESSION['player']->backpack[$idPocz]->id;
				}
				
				if($_SESSION['player']->backpack[$idKon] == ""){
					$valKon = "NULL";
				}
				else{
					$valKon = $_SESSION['player']->backpack[$idKon]->id;
				}
				$conn->query("UPDATE equipment SET $slotPocz=$valPocz, $slotKon=$valKon where ID=$id");
				$conn->close();
				
				//Unsetting variables
				unset($holder);
				unset($idPocz);
				unset($idKon);
				unset($conn);
				unset($slotPocz);
				unset($slotKon);
				unset($valPocz);
				unset($valKon);
			}
			
			
			//------------------
			//BP -> sell or BP -> shop
			//------------------
			else if($_POST['koniec'] == 'sell' or strpos($_POST['koniec'], 'shop') !== false)
			{
				preg_match('/(\d+)/', $_POST['poczatek'], $matches);
				$idPocz = $matches[1];
				
				//Selling the item
				$_SESSION['player']->sellFromSlot($idPocz);
				
				unset($idPocz);
			}
			
			
		}
		//Shop -> 
		else if(strpos($_POST['poczatek'], 'shop') !== false)
		{
			
			//------------------
			//Shop -> BP
			//------------------
			if(strpos($_POST['koniec'], 'bp') !== false)
			{
				preg_match('/(\d+)/', $_POST['poczatek'], $matches);
				$idPocz = $matches[1];
				preg_match('/(\d+)/', $_POST['koniec'], $matches);
				$idKon = $matches[1];
				
				//BP slot is empty
				if($_SESSION['player']->backpack[$idKon] == "")
				{
					//Player has enough gold
					if($_SESSION['player']->zloto >= $_SESSION['player']->shop[$idPocz]->price)
					{
						//Setting variables
						$conn = connectDB();
						$id = $_SESSION['player']->id;
						$zloto = $_SESSION['player']->zloto - $_SESSION['player']->shop[$idPocz]->price;
						$price = $_SESSION['player']->shop[$idPocz]->price / 5;
						$itemID = $_SESSION['player']->shop[$idPocz]->id;
						
						//Updating locally
						$_SESSION['player']->zloto = $zloto;
						$_SESSION['player']->shop[$idPocz]->price = $price;
						$_SESSION['player']->backpack[$idKon] = $_SESSION['player']->shop[$idPocz];
						$_SESSION['player']->shop[$idPocz] = "";
						
						//Updating to DB
						$slotPocz = "shop" . $idPocz;
						$slotKon = "slot" . $idKon;
						$valPocz = "NULL";
						$valKon = $_SESSION['player']->backpack[$idKon]->id;
						$conn->query("UPDATE users SET zloto=$zloto WHERE id=$id");
						$conn->query("UPDATE equipment SET $slotPocz=$valPocz, $slotKon=$valKon WHERE id=$id");
						$conn->query("UPDATE items SET price=$price WHERE id=$itemID");
						$conn->close();
						
						//Unsetting variables
						unset($conn);
						unset($id);
						unset($itemID);
						unset($price);
						unset($zloto);
						unset($slotPocz);
						unset($slotKon);
						unset($valPocz);
						unset($valKon);
					}
					//Player doesn't have enough gold
					else
					{
						//TODO: show error?
					}
				}
				//That BP slot is not empty
				else
				{
					for($i = 0; $i < count($_SESSION['player']->backpack); $i++)
					{
						//Found an empty slot
						if($_SESSION['player']->backpack[$i] == "")
						{
							//Player has enough gold
							if($_SESSION['player']->zloto >= $_SESSION['player']->shop[$idPocz]->price)
							{
								//Setting variables
								$conn = connectDB();
								$id = $_SESSION['player']->id;
								$zloto = $_SESSION['player']->zloto - $_SESSION['player']->shop[$idPocz]->price;
								$price = $_SESSION['player']->shop[$idPocz]->price / 5;
								$itemID = $_SESSION['player']->shop[$idPocz]->id;
								
								//Updating locally
								$_SESSION['player']->zloto = $zloto;
								$_SESSION['player']->shop[$idPocz]->price = $price;
								$_SESSION['player']->backpack[$i] = $_SESSION['player']->shop[$idPocz];
								$_SESSION['player']->shop[$idPocz] = "";
								
								//Updating to DB
								$slotPocz = "shop" . $idPocz;
								$slotKon = "slot" . $i;
								$valPocz = "NULL";
								$valKon = $_SESSION['player']->backpack[$i]->id;
								$conn->query("UPDATE users SET zloto=$zloto WHERE id=$id");
								$conn->query("UPDATE equipment SET $slotPocz=$valPocz, $slotKon=$valKon WHERE id=$id");
								$conn->query("UPDATE items SET price=$price WHERE id=$itemID");
								$conn->close();
						
								//Unsetting variables
								unset($conn);
								unset($id);
								unset($itemID);
								unset($price);
								unset($zloto);
								unset($slotPocz);
								unset($slotKon);
								unset($valPocz);
								unset($valKon);
							}
							//Player doesn't have enough gold
							else
							{
								//TODO: show error?
							}
							
							break;
						}
					}
				}
			}
			
			//------------------
			//Shop -> Shop
			//------------------
			else if(strpos($_POST['koniec'], 'shop') !== false)
			{
				preg_match('/(\d+)/', $_POST['poczatek'], $matches);
				$idPocz = $matches[1];
				preg_match('/(\d+)/', $_POST['koniec'], $matches);
				$idKon = $matches[1];
				
				//Setting variables
				$conn = connectDB();
				$id = $_SESSION['player']->id;
				
				//Updating locally
				$holder = $_SESSION['player']->shop[$idPocz];
				$_SESSION['player']->shop[$idPocz] = $_SESSION['player']->shop[$idKon];
				$_SESSION['player']->shop[$idKon] = $holder;
				
				//Updating to DB
				$slotPocz = "shop" . $idPocz;
				$slotKon = "shop" . $idKon;
				if($_SESSION['player']->shop[$idPocz] == ""){
					$valPocz = "NULL";
				}
				else{
					$valPocz = $_SESSION['player']->shop[$idPocz]->id;
				}
				
				if($_SESSION['player']->shop[$idKon] == ""){
					$valKon = "NULL";
				}
				else{
					$valKon = $_SESSION['player']->shop[$idKon]->id;
				}
				$conn->query("UPDATE equipment SET $slotPocz=$valPocz, $slotKon=$valKon WHERE id=$id");
				
				//Unsetting variables
				unset($conn);
				unset($id);
				unset($holder);
				unset($slotPocz);
				unset($slotKon);
				unset($valPocz);
				unset($valKon);
			}
		}
	}
	
	function updateShop()
	{
		$now = time();
		
		//Last update was saved locally, in number format
		if(is_numeric($_SESSION['player']->last_shop_update)){
			$last = $_SESSION['player']->last_shop_update;
		}
		//Last update was downloaded from DB, in time format
		else{
			$last = strtotime($_SESSION['player']->last_shop_update);
			$_SESSION['player']->last_shop_update = $last;
		}
		
		$seconds = $now-$last;
		if($seconds > 14400) //4 hours
		{
			//Updating locally
			$_SESSION['player']->last_shop_update = $now;
			$_SESSION['player']->generateShop();
			//Updating in DB
			$conn = connectDB();
			$id = $_SESSION['player']->id;
			$conn->query("UPDATE users SET last_shop_update=NOW() WHERE id=$id");
			//Unsetting variables
			$conn->close();
			unset($conn);
			unset($id);
		}
	}
	function drawShop(Player $player)
	{
		//Draws sell slot
		echo "<div id='sellOuter'><div id='sellInner'>";
			echo "<div class='itemSlot arrow sell' id='sell'>";
				echo "<div class='fotoContainer2' style='background-image: url(gfx/eq_slots/sell.png)'></div>";
			echo "</div>";
		echo "</div></div>";
		
		
		//Draws shop
		if($player->village['trader'] != 0)
		{
			
			echo "<div id='shopOuter'><div id='shopInner'>";
			//Iterates through all the player shop slots
			foreach($player->shop as $slot => $item)
			{
				//There is no item at that shop slot, we draw a blank image
				if($item == "")
				{
					echo "<div class='itemSlot arrow shop blank' id='shop$slot'>";
					drawBlankItem("shop", $slot);
					echo "</div>";
				}
				//We draw the item depending on rarity
				else 
				{
					$rarity = $item->rarity;
					echo "<div class='itemSlot arrow $rarity shop' id='shop$slot'>";
					$item->drawFoto($slot);
					//Drawing hover with comparison to equipped item
					if($player->equipment[$item->slot] != "")
					{
						$item->drawHoverCompare($player->equipment[$item->slot]);
					}
					//Drawing normal hover
					else
					{
						$item->drawHover();
					}
					echo "</div>";
					unset($rarity);
				}
			}
			echo "</div></div>";
			
		}
	}
	
	
	updateShop();
	drawBackpack($_SESSION['player']);
	drawShop($_SESSION['player']);
	
?>