<?php

    require_once('config.php');
    login_check();
	
	//Shop settings
	$itemPriceMultiplier = 5;
	$shopUpdateFrequency = 14400;
	
	if($_POST){
		processItemMoves();
	}
	
	updateShop($_SESSION['player']);
	drawBackpack($_SESSION['player']);
	drawShop($_SESSION['player']);
	drawCountdown();
				
	function processItemMoves()
	{
		//Setting ID of drag
		$startSlot = $_POST['start'];
		preg_match('/(\d+)/', $startSlot, $matches);
		$startID = $matches[1];
		
		//Setting ID of drop
		$endSlot = $_POST['end'];
		if(strpos($endSlot, 'shop') !== false or strpos($endSlot, 'slot') !== false)
		{
			preg_match('/(\d+)/', $endSlot, $matches);
			$endID = $matches[1];
		}
		else
		{
			$endID = 'sell';
		}
		
		//Processing item movement
		//Shop -> shop
		if(strpos($startSlot, 'shop') !== false and strpos($endSlot, 'shop') !== false){
			swapItems($startSlot, $startID, $endSlot, $endID, "shop");
		}
		//Shop -> backpack
		else if(strpos($startSlot, 'shop') !== false and strpos($endSlot, 'slot') !== false){
			buyItem($startSlot, $startID, $endSlot, $endID);
		}
		//Backpack -> backpack
		else if(strpos($startSlot, 'slot') !== false and strpos($endSlot, 'slot') !== false){
			swapItems($startSlot, $startID, $endSlot, $endID, "backpack");
		}
		//Backpack -> shop
		else if(strpos($startSlot, 'slot') !== false and strpos($endSlot, 'shop') !== false){
			sellItem($startSlot, $startID, $endSlot, $endID);
		}
		//Backpack -> sellSlot
		else if(strpos($startSlot, 'slot') !== false and strpos($endSlot, 'sell') !== false){
			sellItem($startSlot, $startID, $endSlot, $endID);
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
	
	function sellItem($startDB, $startLocal, $endDB, $endLocal)
	{
		//Item was moved to sellSlot, we check if there is free space in shop
		if($endLocal == "sell")
		{
			$freeSlot = findFreeSlot("shop");
			
			if($freeSlot == null){
				sellAndDelete($startDB, $startLocal);
			}
			else{
				sellAndSave($startDB, $startLocal, $freeSlot);
			}
		}
		//Item was moved to a shop slot
		else
		{
			//Checking if manual placement is available
			if($_SESSION['player']->backpack[$endLocal] == ""){
				sellAndSave($startDB, $startLocal, $endLocal);
			}
			//Checking if there's free slots elsewhere
			else
			{
				$freeSlot = findFreeSlot("shop");
				
				if($freeSlot == null){
					sellAndDelete($startDB, $startLocal);
				}
				else{
					sellAndSave($startDB, $startLocal, $freeSlot);
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
	
	function sellAndDelete($startDB, $startLocal)
	{
		//Setting variables
		$itemID = $_SESSION['player']->backpack[$startLocal]->id;
		$newGold = $_SESSION['player']->zloto + $_SESSION['player']->backpack[$startLocal]->price;
		
		//Local updates
		$_SESSION['player']->zloto = $newGold;
		$_SESSION['player']->backpack[$startLocal] = "";
		
		//DB updates
		$id = $_SESSION['player']->id;
		$conn = connectDB();
		$conn->query("UPDATE users SET zloto=$newGold WHERE id=$id");
		$conn->query("UPDATE equipment SET $startDB=NULL WHERE id=$id");
		$conn->query("DELETE FROM items WHERE id=$itemID");
		$conn->close();
		
		//Unsetting variables
		unset($newGold);
		unset($itemID);
		unset($id);
		unset($conn);
	}
	
	function sellAndSave($startDB, $startLocal, $endLocal)
	{
		//Setting variables
		$endDB = "shop" . $endLocal;
		$newGold = $_SESSION['player']->zloto + $_SESSION['player']->backpack[$startLocal]->price;
		$newPrice = $_SESSION['player']->backpack[$startLocal]->price * $GLOBALS['itemPriceMultiplier'];
		$itemID = $_SESSION['player']->backpack[$startLocal]->id;
		
		//Updating locally
		$_SESSION['player']->zloto = $newGold;
		$_SESSION['player']->backpack[$startLocal]->price = $newPrice;
		$_SESSION['player']->shop[$endLocal] = $_SESSION['player']->backpack[$startLocal];
		$_SESSION['player']->backpack[$startLocal] = "";
		
		//Updating to DB
		$id = $_SESSION['player']->id;
		$conn = connectDB();
		$conn->query("UPDATE equipment SET $startDB=NULL, $endDB=$itemID WHERE id=$id");
		$conn->query("UPDATE items SET price=$newPrice WHERE id=$itemID");
		$conn->query("UPDATE users SET zloto=$newGold WHERE id=$id");
		$conn->close();
		
		//Unsetting variables
		unset($endDB);
		unset($newPrice);
		unset($itemID);
		unset($id);
		unset($conn);
	}
	
	function buyItem($startDB, $startLocal, $endDB, $endLocal)
	{
		//Checking if player has enough gold
		if($_SESSION['player']->zloto >= $_SESSION['player']->shop[$startLocal]->price)
		{
			//Checking if manual placement is available
			if($_SESSION['player']->backpack[$endLocal] == "")
			{
				//Setting variables
				$newGold = $_SESSION['player']->zloto - $_SESSION['player']->shop[$startLocal]->price;
				$newPrice = $_SESSION['player']->shop[$startLocal]->price / $GLOBALS['itemPriceMultiplier'];
				$itemID = $_SESSION['player']->shop[$startLocal]->id;
			
				//Local updates
				$_SESSION['player']->zloto = $newGold;
				$_SESSION['player']->shop[$startLocal]->price = $newPrice;
				$_SESSION['player']->backpack[$endLocal] = $_SESSION['player']->shop[$startLocal];
				$_SESSION['player']->shop[$startLocal] = "";
				
				//DB updates
				$id = $_SESSION['player']->id;
				$conn = connectDB();
				$conn->query("UPDATE items SET price=$newPrice WHERE id=$itemID");
				$conn->query("UPDATE users SET zloto=$newGold WHERE id=$id");
				$conn->query("UPDATE equipment SET $startDB=NULL, $endDB=$itemID WHERE id=$id");
				$conn->close();
				
				//Unsetting variables
				unset($newGold);
				unset($newPrice);
				unset($itemID);
				unset($id);
				unset($conn);
			}
			//Checking if there's free slots elsewhere
			else
			{
				$freeSlot = findFreeSlot("backpack");
				
				if($freeSlot == null){
					//TODO error: not enough space
				}
				else{
					//Call recurrence with changed parameters, will then use "manual placement is available"
					$freeLocal = $freeSlot;
					$freeDB = "slot" . $freeSlot;
					buyItem($startDB, $startLocal, $freeLocal, $freeDB);
				}
			}
		}
		else
		{
			//TODO error: not enough gold
		}
	}
	
	function updateShop(Player $player)
	{
		$now = time();
		
		//Last update was saved locally, in number format
		if(is_numeric($player->last_shop_update)){
			$lastUpdate = $player->last_shop_update;
		}
		//Last update was downloaded from DB, in time format, need to convert it
		else{
			$lastUpdate = strtotime($player->last_shop_update);
		}
		
		$elapsed = $now - $lastUpdate;
		if($elapsed > $GLOBALS['shopUpdateFrequency']) 
		{
			//Updating locally
			$player->last_shop_update = $now;
			$player->generateShop();
			//Updating in DB
			$id = $player->id;
			$conn = connectDB();
			$conn->query("UPDATE users SET last_shop_update=NOW() WHERE id=$id");
			$conn->close();
			//Unsetting variables
			unset($conn);
			unset($id);
		}
	}
	
	function drawShop(Player $player)
	{
		//Checks if player has the required trader level
		if($player->village['trader'] != 0)
		{
			//Draws shop
			echo "<div id='shopOuter'><div id='shopInner'>";

			//Iterates through all the player shop slots
			foreach($player->shop as $slot => $item)
			{
				//There is no item at that shop slot, we draw a blank image
				if($item == "")
				{
					echo "<div class='itemSlot shop blank' id='shop$slot'>";
					drawBlankItem("shop", $slot);
					echo "</div>";
				}
				//We draw the item depending on rarity
				else 
				{
					$rarity = $item->rarity;
					echo "<div class='itemSlot $rarity shop' id='shop$slot'>";
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
			
			drawSellSlot();
			
			echo "</div></div>";
		}
	}
	
	function drawSellSlot()
	{
		//Draws sell slot
		echo "<div class='itemSlot sell' id='sell'>";
			echo "<div class='fotoContainer2' style='background-image: url(gfx/eq_slots/sell.png)'></div>";
		echo "</div>";
	}
	
	function drawCountdown()
	{
		if(is_numeric($_SESSION['player']->last_shop_update)){
			$last = $_SESSION['player']->last_shop_update;
		}
		else{
			$last = strtotime($_SESSION['player']->last_shop_update);
		}
			
		$next = $last + $GLOBALS['shopUpdateFrequency'];
		$next = date("Y-m-d H:i:s", $next);
		
		
		echo "<div id='divCountdown'>";
			echo "<div id='countdownLabel'>NASTĘPNA DOSTAWA ZA</div>";
			echo "<div id='countdown'>$next</div>";
		echo "</div>";
	}
	
?>

<script>

	$("#divPlayerBars").load('update_player_bars.php');

	rescaleImages();
	initializeHover();
	initializeDragDrop();
	initializeShopUpdateCountdown();
	
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
			cancel: "#sell, .blank"
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
	function initializeShopUpdateCountdown()
	{
		var until = $("#countdown").html();
		$("#countdown").countdown(until, function(event) {
			$(this).html(event.strftime('%H:%M:%S'))
		}).on('finish.countdown', function(event) {
			$("#divMainOkno").load('update_shop.php');
		});
	}
	function moveItem(poczatkowySlot, koncowySlot)
	{
		$("#divMainOkno").load('update_shop.php', {start: startSlot, end: endSlot});
	}
	
</script>