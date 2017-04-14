<?php

    require_once('config.php');
    login_check();
	$_SESSION['player']->updateLocally();
	drawGame($_SESSION['player']);
	
?>


<HTML>

<Head>
    
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="main.css">
	<link rel="stylesheet" type="text/css" href="equipment.css">
    <Title>SkalpoGra</Title>
	
</Head>

<Body>

    <div id="divMainOkno"></div>

	<nav>
    <ul>
		<li><a href = "main.php"><div class='menuContainer' id='mainMenu'></div></a></li>
        <li><a href = "postac.php"><div class='menuContainer' id='postacMenu'></div></a></li>
        <li><a href = "equipment.php" class="active"><div class='menuContainer' id='equipmentMenu'></div></a></li>
		<li><a href = "magia.php"><div class='menuContainer' id='magiaMenu'></div></a></li>
        <li><a href = "wyprawa.php"><div class='menuContainer' id='wyprawaMenu'></div></a></li>
		<li><a href = "arena.php"><div class='menuContainer' id='arenaMenu'></div></a></li>
        <li><a href = "logout.php"><div class='menuContainer' id='logoutMenu'></div></a></li>
    </ul>
    </nav>
	
</Body>

</HTML>


<script src="jquery-ui-1.12.1/jquery-3.1.1.js"></script>
<script src="jquery-ui-1.12.1/jquery-ui.js"></script>
<script src="jquery-ui-1.12.1/jquery.countdown.js"></script>


<script>

	var poczatkowySlot = "";
	var koncowySlot = "";
	
	$("#divMainOkno").load('update_equipment.php', function() {
		rescaleImages();
		initializeDragDrop();
		initializeHover();
	});
	
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
	
	function moveItem(poczatkowySlot, koncowySlot)
	{
		$("#divMainOkno").load('update_equipment.php', {poczatek: poczatkowySlot, koniec: koncowySlot}, function() {
			rescaleImages();
			initializeDragDrop();
			initializeHover();
		});
	}
</script>