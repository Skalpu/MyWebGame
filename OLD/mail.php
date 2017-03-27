<?php

    require_once('config.php');
    login_check();
	
	$message = '';
	
	function generate_mailbox()
	{
		$conn = connectDB();
		$ID = $_SESSION['id'];
		
		$result = $conn->query("SELECT msg1,msg2,msg3,msg4,msg5,msg6,msg7,msg8,msg9,msg10 FROM user_mail WHERE id = $ID");
		$IDmsg = mysqli_fetch_row($result);
		
		echo "<table id='tabelaWiadomosci'>";
		echo "<tr>";
		echo "<th id='titleLabel'>Tytuł</th>";
		echo "<th id='fromLabel'>Nadawca</th>";
		echo "<th id='dateLabel'>Otrzymano</th>";
		echo "</tr>";
		
		for ($i = 9; $i >= 0 ; $i--)
		{
			if ($IDmsg[$i] != null)
			{
				$idquery = $IDmsg[$i];
				
				$result2 = $conn->query("SELECT title,fromName,date FROM messages WHERE id = $idquery");
				$msg = mysqli_fetch_row($result2);
				
				echo "<tr>";
				echo "<th class='msgTitle' id='" . $idquery . "'>" . $msg[0] . "</th>";
				echo "<th>" . $msg[1] . "</th>";
				echo "<th>" . $msg[2] . "</th>";
				echo "</tr>";
			}
		}
		
		echo "</table>";
		
		$conn->close();
	}
	
?>


<HTML>

<Head>
    
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="main.css">
	<link rel="stylesheet" type="text/css" href="mail.css">
    <Title>SkalpoGra</Title>
	
</Head>

<Body>

    <div id="divMainOkno">
		<div id="walkaOkno"></div>
		<?php
			generate_mailbox();
		?>
    </div>

    <?php update_logic($_SESSION['id']); ?>
	<?php echo drawWyprawa($_SESSION['id']); ?>
	<?php echo drawMail($_SESSION['id']); ?>
    <?php echo drawHealthBar($_SESSION['id']); ?>
    <?php echo drawManaBar($_SESSION['id']);   ?>
    <?php echo drawExpBar($_SESSION['id']);    ?> 
	<?php echo drawGold($_SESSION['id']); ?>
	<?php echo drawCrystals($_SESSION['id']); ?>
	
	<nav>
    <ul>
        <li><a href = "main.php"><img id="mainmenu"></a></li>
        <li><a href = "postac.php"><img id="postacmenu"></a></li>
        <li><a href = "equipment.php"><img id="ekwipunekmenu"></a></li>
		<li><a href = "magia.php"><img id="magiamenu"></a></li>
        <li><a href = "wyprawa.php"><img id="wyprawamenu"></a></li>
		<li><a href = "arena.php"><img id="arenamenu"></a></li>
        <li><a href = "logout.php"><img id="logoutmenu"></a></li>
        </ul>
    </nav>
	
</Body>

</HTML>

<script src="jquery-ui-1.12.1/jquery-3.1.1.js"></script>
<script src="jquery-ui-1.12.1/jquery-ui.js"></script>
<script src="jquery-ui-1.12.1/jquery.countdown.js"></script>

<script>	
	
	$(document).ready(function() {
		setTimeout(podlicz_bary, 10000);
	});
	
	
	var until = $("#wyprawaTekst").html();
	var lokacja = $("#wyprawaContainer").attr('class');
	if(lokacja != 'false')
	{
		$("#wyprawaTekst").countdown(until).on('update.countdown', function(event) {
			$(this).html(event.strftime('%M:%S'));
			$("#wyprawaContainer").css("opacity","1.0");
		}).on('finish.countdown', function(event) {
			$("#divMainOkno").load('walka.php', {miejsce: lokacja, typ_walki: 'wyprawa'});
			$("<link/>", {
				rel: "stylesheet",
				type: "text/css",
				href: "walka.css"
			}).appendTo("head");
			$("#wyprawaContainer").css("opacity","0");
		});
	}
	
	
	$(".msgTitle").click(function()	{
		$("#walkaOkno").css("opacity", "1");
		$("#walkaOkno").load("load_message.php", {'idmsg': $(this).attr('id')});
	});
	
	
	function podlicz_bary()
	{
		//HP
		var str = $("#textHP").html();
		var akt = str.substring(str.indexOf(' '),str.indexOf('/'));
		var max = str.substring(str.indexOf('/') + 1,str.length);
		
		if (parseInt(akt) < parseInt(max))
		{
			akt++;
			var cale = "HP: " + akt + "/" + max;
			$("#textHP").html(cale);
			
			var proc = akt/max;
			var nowaDlugosc = proc * 300;
			$("#innerHP").css("width",nowaDlugosc);
		}
		
		//Mana
		var str = $("#textMana").html();
		var akt = str.substring(str.indexOf(' '),str.indexOf('/'));
		var max = str.substring(str.indexOf('/') + 1,str.length);
		
		if (parseInt(akt) < parseInt(max))
		{
			akt++;
			var cale = "MP: " + akt + "/" + max;
			$("#textMana").html(cale);
			
			var proc = akt/max;
			var nowaDlugosc = proc * 300;
			$("#innerMana").css("width",nowaDlugosc);
		}
		
		//Gold
		var str = $("#zlotoTekst").html();
		str++;
		$("#zlotoTekst").html(str);
		
		//Krysztaly
		var str = $("#krysztalyTekst").html();
		str++;
		$("#krysztalyTekst").html(str);
		
		
		
		setTimeout(podlicz_bary, 10000);
	}
	
</script>