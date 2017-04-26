<?php

    require_once('config.php');
    login_check();

	
	function drawTable()
	{
		//Settings for the search
		$level_brackets = 5;
		$level_brackets_used = 0;
		$players_amount = 10;
		
		//Generating labels on top of the table
		echo "<table id='tabelaGracze'>";
		echo "<tr>";
		echo "<th id='graczLabel'>Gracz</th>";
		echo "<th id='levelLabel'>Poziom</th>";
		echo "<th id='ostatnioLabel'>Ostatnia aktywność</th>";
		echo "<th id='akcjaLabel'>Akcja</th>";
		echo "</tr>";
		
		//Checking if player has enough HP (20%)
		if($_SESSION['player']->hp / $_SESSION['player']->maxhp > 0.2)
		{
			$level = $_SESSION['player']->level;
			$id = $_SESSION['player']->id;
			$conn = connectDB();
			$num_rows = 0;
			
			//Searching for users on similar level
			while($num_rows < $players_amount and $level_brackets_used < $level_brackets)
			{
				//Setting minLevel for search
				if($level - $level_brackets > 0)
				{
					$levelMin = $level - $level_brackets;
				}
				else
				{
					$levelMin = 1;
				}
				//Setting maxLevel for search
				$levelMax = $level + $level_brackets;
				//Running the query
				$result = $conn->query("SELECT id,username,level,last_update FROM users WHERE level>=$levelMin AND level<=$levelMax AND id!=$id AND protected_until<NOW() LIMIT $players_amount");
				$num_rows = mysqli_num_rows($result);
				//Increasing the level range for this loop
				$level_brackets_used++;
			}
			
			//Generating table content
			for($i = 0; $i < $num_rows; $i++)
			{
				$row = mysqli_fetch_assoc($result);
				echo "<tr>";
				echo "<th>" . $row['username'] . "</th>";
				echo "<th>" . $row['level'] . "</th>";
				echo "<th>" . $row['last_update'] . "</th>";
				echo "<th> <button class='orange przycisk	' onclick='attack(" . $row['id'] . ")'>Atak</button></th>";
				echo "</tr>";
			}
		
			//Unsetting variables to save memory
			$conn->close();
			unset($level);
			unset($levelMin);
			unset($levelMax);
			unset($id);
			unset($row);
			unset($num_rows);
			unset($result);
			unset($level_brackets);
			unset($level_brackets_used);
			unset($players_amount);
		}

		echo "</table>";
	}
	
?>


<HTML>

<Head>
    
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="main.css">
	<link rel="stylesheet" type="text/css" href="arena.css">
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
    <div id="divMainOkno">	<?php drawTable(); ?>	</div>
	
	<nav><ul>
		<li><a href = "main.php"><div class='menuContainer' id='mainMenu'></div></a></li>
        <li><a href = "postac.php"><div class='menuContainer' id='postacMenu'></div></a></li>
        <li><a href = "equipment.php"><div class='menuContainer' id='equipmentMenu'></div></a></li>
		<li><a href = "wioska.php"><div class='menuContainer' id='wioskaMenu'></div></a></li>
		<li><a href = "shop.php"><div class='menuContainer' id='shopMenu'></div></a></li>
		<li><a href = "magic.php"><div class='menuContainer' id='magiaMenu'></div></a></li>
        <li><a href = "journey.php"><div class='menuContainer' id='wyprawaMenu'></div></a></li>
		<li><a href = "arena.php" class="active"><div class='menuContainer' id='arenaMenu'></div></a></li>
        <li><a href = "logout.php"><div class='menuContainer' id='logoutMenu'></div></a></li>
    </ul></nav>
	
</Body>

</HTML>



<script>

	document.addEventListener('DOMContentLoaded',function()
    {
        $("#divPlayerBars").load('update_player_bars.php');
    });

	function attack(opponentID)
	{
		$("#tabelaGracze").fadeOut();
		$("#divMainOkno").load('walka.php', {type: 'arena', opponent: opponentID});		
	}
	
</script>