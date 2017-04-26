<?php

    require_once('config.php');
    login_check();

	if($_POST){
		processItemMoves();
	}
	
	drawEquipment($_SESSION['player']);
	drawBackpack($_SESSION['player']);
	
	
	/*if($_POST)
	{
		//BP ->
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
				
				//Saving to DB
				$conn = connectDB();
				$id = $_SESSION['player']->id;
				$slotPocz = "slot" . $idPocz;
				$slotKon = "slot" . $idKon;
				if($_SESSION['player']->backpack[$idPocz] == "")
				{
					$valPocz = "NULL";
				}
				else
				{
					$valPocz = $_SESSION['player']->backpack[$idPocz]->id;
				}
				
				if($_SESSION['player']->backpack[$idKon] == "")
				{
					$valKon = "NULL";
				}
				else
				{
					$valKon = $_SESSION['player']->backpack[$idKon]->id;
				}
				$conn->query("UPDATE equipment SET $slotPocz=$valPocz, $slotKon=$valKon where ID=$id");
				$conn->close();
				
				unset($holder);
				unset($idPocz);
				unset($idKon);
				unset($conn);
				unset($slotPocz);
				unset($slotKon);
				unset($valPocz);
				unset($valKon);
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
					//There is no item at this slot
					if($_SESSION['player']->equipment[$idKon] == "")
					{
						$_SESSION['player']->equipFromSlot($idPocz);
						$_SESSION['player']->backpack[$idPocz] = "";
						
						//Saving to DB
						$conn = connectDB();
						$id = $_SESSION['player']->id;
						$slotPocz = "slot" . $idPocz;
						$slotKon = $idKon;
						$valPocz = "NULL";
						$valKon = $_SESSION['player']->equipment[$idKon]->id;
						$conn->query("UPDATE equipment SET $slotPocz=$valPocz, $slotKon=$valKon WHERE id=$id");
						$conn->close();
						$_SESSION['player']->updateStatsGlobally();
						unset($conn);
						unset($id);
						unset($slotPocz);
						unset($slotKon);
						unset($valPocz);
						unset($valKon);
					}
					//There is an item at this slot, swapping
					else
					{
						$holder = $_SESSION['player']->equipment[$idKon];
						$_SESSION['player']->unequipFromSlot($idKon);
						$_SESSION['player']->equipFromSlot($idPocz);
						$_SESSION['player']->backpack[$idPocz] = $holder;
						unset($holder);
						
						//Saving to DB
						$conn = connectDB();
						$id = $_SESSION['player']->id;
						$slotPocz = "slot" . $idPocz;
						$slotKon = $idKon;
						$valPocz = $_SESSION['player']->backpack[$idPocz]->id;
						$valKon = $_SESSION['player']->equipment[$idKon]->id;
						$conn->query("UPDATE equipment SET $slotPocz=$valPocz, $slotKon=$valKon WHERE id=$id");
						$conn->close();
						$_SESSION['player']->updateStatsGlobally();
						unset($conn);
						unset($id);
						unset($slotPocz);
						unset($slotKon);
						unset($valPocz);
						unset($valKon);
					}
				}
				
				unset($idPocz);
				unset($idKon);
			}
		}
		
		//EQ ->
		else
		{
			//EQ -> BP
			if(strpos($_POST['koniec'], 'bp') !== false)
			{
				preg_match('/(\d+)/', $_POST['koniec'], $matches);
				$idKon = $matches[1];
				$idPocz = $_POST['poczatek'];
				
				//BP is empty, moving
				if($_SESSION['player']->backpack[$idKon] == "")
				{
					$_SESSION['player']->backpack[$idKon] = $_SESSION['player']->equipment[$idPocz];
					$_SESSION['player']->unequipFromSlot($idPocz);
					
					//Saving to DB
					$conn = connectDB();
					$id = $_SESSION['player']->id;
					$slotPocz = $idPocz;
					$slotKon = "slot" . $idKon;
					$valPocz = "NULL";
					$valKon = $_SESSION['player']->backpack[$idKon]->id;
					$conn->query("UPDATE equipment SET $slotPocz=$valPocz, $slotKon=$valKon WHERE id=$id");
					$conn->close();
					$_SESSION['player']->updateStatsGlobally();
					unset($conn);
					unset($id);
					unset($slotPocz);
					unset($slotKon);
					unset($valPocz);
					unset($valKon);
				}
				//BP is not empty
				else
				{
					//Check types, see if we can swap
					if($_SESSION['player']->equipment[$idPocz]->slot == $_SESSION['player']->backpack[$idKon]->slot)
					{
						//Swapping
						$holder = $_SESSION['player']->equipment[$idPocz];
						$_SESSION['player']->unequipFromSlot($idPocz);
						$_SESSION['player']->equipFromSlot($idKon);
						$_SESSION['player']->backpack[$idKon] = $holder;
						unset($holder);
						
						//Saving to DB
						$conn = connectDB();
						$id = $_SESSION['player']->id;
						$slotPocz = $idPocz;
						$slotKon = "slot" . $idKon;
						$valPocz = $_SESSION['player']->backpack[$idPocz]->id;
						$valKon = $_SESSION['player']->equipment[$idKon]->id;
						$conn->query("UPDATE equipment SET $slotPocz=$valPocz, $slotKon=$valKon WHERE id=$id");
						$conn->close();
						$_SESSION['player']->updateStatsGlobally();
						unset($conn);
						unset($id);
						unset($slotPocz);
						unset($slotKon);
						unset($valPocz);
						unset($valKon);
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
								$_SESSION['player']->unequipFromSlot($idPocz);
								
								//Saving to DB
								$conn = connectDB();
								$id = $_SESSION['player']->id;
								$slotPocz = $idPocz;
								$slotKon = "slot" . $i;
								$valPocz = "NULL";
								$valKon = $_SESSION['player']->backpack[$i]->id;
								$conn->query("UPDATE equipment SET $slotPocz=$valPocz, $slotKon=$valKon WHERE id=$id");
								$conn->close();
								$_SESSION['player']->updateStatsGlobally();
								unset($conn);
								unset($id);
								unset($slotPocz);
								unset($slotKon);
								unset($valPocz);
								unset($valKon);
								
								break;
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
	drawBackpack($_SESSION['player']);*/
	
?>

<script>

	$("#divPlayerBars").load('update_player_bars.php');

	rescaleImages();
	initializeHover();
	initializeDragDrop();
	
	function rescaleImages()
	{
		$(".fotoContainer2").each(function() {
			var currObj = $(this);
			var img = new Image;
			img.src = currObj.css('background-image').replace(/url\(|\)$/ig, "").replace(/"/g, "").replace(/'/g, "");
			img.onload = function() {
				if(img.width < currObj.width() && img.height < currObj.height())
				{
					currObj.css('background-size', 'auto auto');
				}
			}
		});
	}
	function initializeHover()
	{
		$(".fotoContainer2").hover(
			function(){
				$(this).parent().find('.itemHover').show();
			},
			function(){
				$(this).parent().find('.itemHover').hide();
			}
		);
		
		$(".fotoContainer2").bind('mousemove', function(e){
			var top = e.pageY + 15;
			var left = e.pageX + 8;
			$(this).parent().find('.itemHover').css({'top': top, 'left': left});
		});
	}
	function initializeDragDrop()
	{
		$(".fotoContainer2").draggable({
			start: function(event, ui)
			{
				//Set startSlot & hide hover
				startSlot = $(this).parent().attr('id');
				$(this).parent().find('.itemHover').hide();
			},
			revert: true,
			revertDuration: 0,
			opacity: 0.5,
			zIndex: 100,
			cancel: ".blank"
		});
	
		$(".itemSlot").droppable({
			accept: ".fotoContainer2",
			tolerance: "intersect",
		
			drop: function(event, ui)
			{
				//Set endSlot & move item
				endSlot = $(this).attr('id');
				moveItem(startSlot, endSlot);
			}
		});
	}
	function moveItem(poczatkowySlot, koncowySlot)
	{
		$("#divMainOkno").load('update_shop.php', {start: startSlot, end: endSlot});
	}
	
</script>