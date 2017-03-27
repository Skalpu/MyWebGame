<?php

    require('config.php');
    login_check();
    
	if($_POST)
	{
		$co = $_POST['co'];

		switch($co)
		{
			case 'item':
				echo "<br>";
				echo read_item_stats($_POST['id']);
				if($_POST['id2'] != 0)
				{
					echo "<br><span style='font-style: oblique;'>Założone: </span><br><br>";
					echo read_item_stats($_POST['id2']);
				}
				break;
			case 'statystyki':
				echo "<br>";
				echo read_stats($_SESSION['id'],'statystyki');
				break;
			case 'statystykiEquipment':
				echo "<br>";
				echo read_stats($_SESSION['id'],'statystykiEquipment');
			default:
		}
	}
    
?>