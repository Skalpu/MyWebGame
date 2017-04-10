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
		initializeDragDrop();
	});
	
	function initializeDragDrop()
	{
		$(".fotoContainer2").draggable({
			start: function(event, ui)
			{
				poczatkowySlot = $(this).parent().attr('id');
			},
			revert: true,
			revertDuration: 100,
			opacity: 0.5,
			zIndex: 100,
			snap: true
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
	
	function moveItem(poczatkowySlot, koncowySlot)
	{
		$("#divMainOkno").load('update_equipment.php', {poczatek: poczatkowySlot, koniec: koncowySlot}, function() {
			initializeDragDrop();
		});
	}
</script>