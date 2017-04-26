<?php

    require_once('config.php');
    login_check();

	if($_POST){
		processItemMoves();
	}
	
	drawEquipment($_SESSION['player']);
	drawBackpack($_SESSION['player']);
	
	function processItemMoves()
	{
		//Setting ID of drag
		$startSlot = $_POST['start'];
		if(strpos($startSlot, 'slot') !== false)
		{
			preg_match('/(\d+)/', $startSlot, $matches);
			$startID = $matches[1];
		}
		else
		{
			$startID = $startSlot;
		}
		
		//Setting ID of drop
		$endSlot = $_POST['end'];
		if(strpos($endSlot, 'slot') !== false)
		{
			preg_match('/(\d+)/', $endSlot, $matches);
			$endID = $matches[1];
		}
		else
		{
			$endID = $endSlot;
		}
		
		//Processing item movement
		//Backpack -> backpack
		if(strpos($startSlot, 'slot') !== false and strpos($endSlot, 'slot') !== false){
			swapItems($startSlot, $startID, $endSlot, $endID, "backpack");
		}
		//Backpack -> equipment
		else if(strpos($startSlot, 'slot') !== false){
			equipItem($startSlot, $startID, $endSlot, $endID, $_SESSION['player']);
		}
		//Equipment->backpack
		else if(strpos($endSlot, 'slot') !== false){
			unequipItem($startSlot, $startID, $endSlot, $endID, $_SESSION['player']);
		}
	}
	function swapItems($startDB, $startLocal, $endDB, $endLocal, $location)
	{
		//Local updates
		$holder = $_SESSION['player']->{$location}[$startLocal];
		$_SESSION['player']->{$location}[$startLocal] = $_SESSION['player']->{$location}[$endLocal];
		$_SESSION['player']->{$location}[$endLocal] = $holder;
		
		//Setting variables
		if($_SESSION['player']->{$location}[$startLocal] != ""){
			$startVal = $_SESSION['player']->{$location}[$startLocal]->id;
		}
		else{
			$startVal = "NULL";
		}
		
		if($_SESSION['player']->{$location}[$endLocal] != ""){
			$endVal = $_SESSION['player']->{$location}[$endLocal]->id;
		}
		else{
			$endVal = "NULL";
		}
		
		
		//Database updateShop
		$id = $_SESSION['player']->id;
		$conn = connectDB();
		$conn->query("UPDATE equipment SET $startDB=$startVal, $endDB=$endVal WHERE id=$id");
		$conn->close();
		
		//Unsetting variables
		unset($holder);
		unset($startVal);
		unset($endVal);
		unset($id);
		unset($conn);
	}
	function equipItem($startDB, $startLocal, $endDB, $endLocal, Player $player)
	{
		//Checking if types match
		if($endLocal == $player->backpack[$startLocal]->slot)
		{
			//There was an item equipped
			if($player->equipment[$endLocal] != ""){
				//Unequipping it (removing stats)
				$player->unequipFromSlot($endLocal);
			}
			//Equipping the new one (adding stats)
			$player->equipFromSlot($startLocal);
			
			//Updating locally
			$holder = $player->equipment[$endLocal];
			$player->equipment[$endLocal] = $player->backpack[$startLocal];
			$player->backpack[$startLocal] = $holder;
			
			//Setting variables
			if($player->backpack[$startLocal] == ""){
				$startVal = "NULL";
			}
			else{
				$startVal = $player->backpack[$startLocal]->id;
			}
			
			$endVal = $player->equipment[$endLocal]->id;
			
			//Updating in DB
			$id = $player->id;
			$conn = connectDB();
			$conn->query("UPDATE equipment SET $startDB=$startVal, $endDB=$endVal WHERE id=$id");
			$conn->close();
			$player->updateAfterEquipmentChange();
			
			//Unsetting variables
			unset($holder);
			unset($startVal);
			unset($endVal);
			unset($id);
			unset($conn);
		}
	}
	function unequipItem($startDB, $startLocal, $endDB, $endLocal, Player $player)
	{
		//Checking if manual placement is available
		if($player->backpack[$endLocal] == "")
		{
			//Unequipping old item (removing stats)
			$player->unequipFromSlot($startLocal);
			
			//Updating locally
			$player->backpack[$endLocal] = $player->equipment[$startLocal];
			$player->equipment[$startLocal] = "";
			
			//Setting variables
			$startVal = "NULL";
			$endVal = $player->backpack[$endLocal]->id;
			
			//Updating in DB
			$id = $player->id;
			$conn = connectDB();
			$conn->query("UPDATE equipment SET $startDB=$startVal, $endDB=$endVal WHERE id=$id");
			$conn->close();
			$player->updateAfterEquipmentChange();
			
			//Unsetting variables
			unset($startVal);
			unset($endVal);
			unset($id);
			unset($conn);
		}
		//There was no space at selected slot
		else
		{	
			//We check if types match for swapping
			if($startLocal == $player->backpack[$endLocal]->slot)
			{
				//Unequipping old one (removing stats)
				$player->unequipFromSlot($startLocal);
				//Equipping new one (adding stats)
				$player->equipFromSlot($endLocal);
				
				//Updating locally
				$holder = $player->backpack[$endLocal];
				$player->backpack[$endLocal] = $player->equipment[$startLocal];
				$player->equipment[$startLocal] = $holder;
				
				//Setting variables
				$startVal = $player->equipment[$startLocal]->id;
				$endVal = $player->backpack[$endLocal]->id;
				
				//Updating in DB
				$id = $player->id;
				$conn = connectDB();
				$conn->query("UPDATE equipment SET $startDB=$startVal, $endDB=$endVal WHERE id=$id");
				$conn->close();
				$player->updateAfterEquipmentChange();
			
				//Unsetting variables
				unset($holder);
				unset($startVal);
				unset($endVal);
				unset($id);
				unset($conn);
			}
			//We try to find a free slot
			else
			{
				$freeSlot = findFreeSlot("backpack");
				//Found a free slot
				if($freeSlot != null)
				{
					$freeLocal = $freeSlot;
					$freeDB = "slot" . $freeSlot;
					//Calling the function as a recurrence
					unequipItem($startDB, $startLocal, $freeDB, $freeLocal, $player);
				}
			}
		}
	}
	function findFreeSlot($location)
	{
		$foundSlot = false;
		
		for($i = 0; $i < count($_SESSION['player']->{$location}); $i++)
		{
			if($_SESSION['player']->{$location}[$i] == "")
			{
				$foundSlot = true;
				break;
			}
		}
		
		if($foundSlot == true){
			return $i;
		}
		else{
			return null;
		}
	}
	
	
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
		$("#divMainOkno").load('update_equipment.php', {start: startSlot, end: endSlot});
	}
	
</script>