<?php

    require_once('config.php');
    login_check();
	
	//Last update was saved locally, in number format
	if(is_numeric($_SESSION['player']->last_shop_update)){
		$last = $_SESSION['player']->last_shop_update;
	}
	//Last update was downloaded from DB, in time format
	else{
		$last = strtotime($_SESSION['player']->last_shop_update);
		$_SESSION['player']->last_shop_update = $last;
	}
	$next = $last + 14400; //4 hours
	$next = date("Y-m-d H:i:s", $next);
	
?>


<HTML>

<Head>
    
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="main.css">
	<link rel="stylesheet" type="text/css" href="equipment.css">
	<script src="jquery-ui-1.12.1/jquery-3.1.1.js"></script>
	<script src="jquery-ui-1.12.1/jquery-ui.js"></script>
	<script src="jquery-ui-1.12.1/jquery.countdown.js"></script>
	<link rel="apple-touch-icon" sizes="57x57" href="/gfx/icon/apple-icon-57x57.png">
	<link rel="apple-touch-icon" sizes="60x60" href="/gfx/icon/apple-icon-60x60.png">
	<link rel="apple-touch-icon" sizes="72x72" href="/gfx/icon/apple-icon-72x72.png">
	<link rel="apple-touch-icon" sizes="76x76" href="/gfx/icon/apple-icon-76x76.png">
	<link rel="apple-touch-icon" sizes="114x114" href="/gfx/icon/apple-icon-114x114.png">
	<link rel="apple-touch-icon" sizes="120x120" href="/gfx/icon/apple-icon-120x120.png">
	<link rel="apple-touch-icon" sizes="144x144" href="/gfx/icon/apple-icon-144x144.png">
	<link rel="apple-touch-icon" sizes="152x152" href="/gfx/icon/apple-icon-152x152.png">
	<link rel="apple-touch-icon" sizes="180x180" href="/gfx/icon/apple-icon-180x180.png">
	<link rel="icon" type="image/png" sizes="192x192"  href="/gfx/icon/android-icon-192x192.png">
	<link rel="icon" type="image/png" sizes="32x32" href="/gfx/icon/favicon-32x32.png">
	<link rel="icon" type="image/png" sizes="96x96" href="/gfx/icon/favicon-96x96.png">
	<link rel="icon" type="image/png" sizes="16x16" href="/gfx/icon/favicon-16x16.png">
	<link rel="manifest" href="/manifest.json">
	<meta name="msapplication-TileColor" content="#ffffff">
	<meta name="msapplication-TileImage" content="/gfx/icon/ms-icon-144x144.png">
	<meta name="theme-color" content="#ffffff">
    <Title>SkalpoGra</Title>
	
</Head>

<Body>

	<div id="divPlayerBars"></div>
    <div id="divMainOkno"></div>
	<div id="divRemainingTimeLabel" class="center noselect">NASTĘPNA DOSTAWA ZA</div>
	<div id="divRemainingTime" class="center noselect"></div>

	<nav><ul>
		<li><a href = "main.php"><div class='menuContainer' id='mainMenu'></div></a></li>
        <li><a href = "postac.php"><div class='menuContainer' id='postacMenu'></div></a></li>
        <li><a href = "equipment.php"><div class='menuContainer' id='equipmentMenu'></div></a></li>
		<li><a href = "wioska.php"><div class='menuContainer' id='wioskaMenu'></div></a></li>
		<li><a href = "shop.php" class="active"><div class='menuContainer' id='shopMenu'></div></a></li>
		<li><a href = "magia.php"><div class='menuContainer' id='magiaMenu'></div></a></li>
        <li><a href = "wyprawa.php"><div class='menuContainer' id='wyprawaMenu'></div></a></li>
		<li><a href = "arena.php"><div class='menuContainer' id='arenaMenu'></div></a></li>
        <li><a href = "logout.php"><div class='menuContainer' id='logoutMenu'></div></a></li>
    </ul></nav>
	
</Body>

</HTML>



<script>

	document.addEventListener('DOMContentLoaded',function()
    {
        $("#divPlayerBars").load('update_player_bars.php');
		
		//Initialize countdown
		startCountdown();
		
		//Initialize the shop
		$("#divMainOkno").load('update_shop.php', function() {
			rescaleImages();
			initializeDragDrop();
			initializeHover();
		});
		
		var poczatkowySlot = "";
		var koncowySlot = "";
    });
	
	
	
	function startCountdown()
	{
		var nextUpdate = <?php echo json_encode($next); ?>;
		
		$("#divRemainingTime").countdown(nextUpdate, function(event) {
			$(this).html(event.strftime('%H:%M:%S'))
		}).on('finish.countdown', function(event) {
			//Reload shop when countdown hits 0
			$("#divMainOkno").load('update_shop.php', function() {
				rescaleImages();
				initializeDragDrop();
				initializeHover();
				startCountdown();
			});
		});
	}

	function moveItem(poczatkowySlot, koncowySlot)
	{
		$("#divMainOkno").load('update_shop.php', {poczatek: poczatkowySlot, koniec: koncowySlot}, function() {
			rescaleImages();
			initializeDragDrop();
			initializeHover();
		});
	}
	
	function initializeDragDrop()
	{
		$(".fotoContainer2").draggable({
			start: function(event, ui)
			{
				poczatkowySlot = $(this).parent().attr('id');
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
				koncowySlot = $(this).attr('id');
				moveItem(poczatkowySlot, koncowySlot);
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
	
</script>