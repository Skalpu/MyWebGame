<?php
    
    require('config.php');
    login_check();

?>


<HTML>

<Head>
	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <script src="jquery-ui-1.12.1/jquery-3.1.1.js"></script>
    <script src="jquery-ui-1.12.1/jquery-ui.js"></script>
	<script src="jquery-ui-1.12.1/jquery.countdown.js"></script>
    <link rel="stylesheet" type="text/css" href="main.css">
    <link rel="stylesheet" type="text/css" href="equipment.css">
    <link rel="stylesheet" type="text/css" href="statystyki.css">
    <Title>SkalpoGra</Title>
</Head>
    
<Body>
    <div id="divMainOkno">
        <div id="itemText"><div id="Text"></div></div>
		
		<div id="sellDiv" class="equipmentSlot"><div class="imageContainer" id="sellContainer">		<?php	echo draw_item("sell",$_SESSION['id']);	?>	</div></div>
            
        <div id="helmetDiv" class="equipmentSlot"> <div class="imageContainer" id="helmetContainer">		<?php 	echo draw_item("helmet",$_SESSION['id']);     ?>   </div></div>
        <div id="amuletDiv" class="equipmentSlot"> <div class="imageContainer" id="amuletContainer">		<?php 	echo draw_item("amulet",$_SESSION['id']);     ?>   </div></div>
        <div id="lefthandDiv" class="equipmentSlot"> <div class="imageContainer" id="lefthandContainer">	<?php 	echo draw_item("lefthand",$_SESSION['id']);   ?>   </div></div>
        <div id="chestDiv" class="equipmentSlot"> <div class="imageContainer" id="chestContainer">			<?php 	echo draw_item("chest",$_SESSION['id']);      ?>   </div></div>
        <div id="righthandDiv" class="equipmentSlot"> <div class="imageContainer" id="righthandContainer">	<?php 	echo draw_item("righthand",$_SESSION['id']);  ?>   </div></div>
        <div id="glovesDiv" class="equipmentSlot"> <div class="imageContainer" id="glovesContainer">		<?php 	echo draw_item("gloves",$_SESSION['id']);     ?>   </div></div>
        <div id="beltDiv" class="equipmentSlot"> <div class="imageContainer" id="beltContainer">			<?php 	echo draw_item("belt",$_SESSION['id']);       ?>   </div></div>
        <div id="ring1Div" class="equipmentSlot"> <div class="imageContainer" id="ring1Container">			<?php 	echo draw_item("ring1",$_SESSION['id']);      ?>   </div></div>
        <div id="bootsDiv" class="equipmentSlot"> <div class="imageContainer" id="bootsContainer">			<?php 	echo draw_item("boots",$_SESSION['id']);      ?>   </div></div>
        <div id="ring2Div" class="equipmentSlot"> <div class="imageContainer" id="ring2Container">			<?php 	echo draw_item("ring2",$_SESSION['id']);      ?>   </div></div>
		
		<div id="davinci"></div>
            
        <div id="slot1Div" class="equipmentSlot"> <div class="imageContainer" id="slot1Container">			<?php 	echo draw_item("slot1",$_SESSION['id']); 	  ?>   </div></div>
        <div id="slot2Div" class="equipmentSlot"> <div class="imageContainer" id="slot2Container">			<?php 	echo draw_item("slot2",$_SESSION['id']);  	  ?>   </div></div>
        <div id="slot3Div" class="equipmentSlot"> <div class="imageContainer" id="slot3Container">			<?php 	echo draw_item("slot3",$_SESSION['id']);  	  ?>   </div></div>
        <div id="slot4Div" class="equipmentSlot"> <div class="imageContainer" id="slot4Container">			<?php 	echo draw_item("slot4",$_SESSION['id']);	  ?>   </div></div>
        <div id="slot5Div" class="equipmentSlot"> <div class="imageContainer" id="slot5Container">			<?php 	echo draw_item("slot5",$_SESSION['id']);  	  ?>   </div></div>
        <div id="slot6Div" class="equipmentSlot"> <div class="imageContainer" id="slot6Container">			<?php 	echo draw_item("slot6",$_SESSION['id']);      ?>   </div></div>
        <div id="slot7Div" class="equipmentSlot"> <div class="imageContainer" id="slot7Container">			<?php 	echo draw_item("slot7",$_SESSION['id']);      ?>   </div></div>
        <div id="slot8Div" class="equipmentSlot"> <div class="imageContainer" id="slot8Container">			<?php 	echo draw_item("slot8",$_SESSION['id']);      ?>   </div></div>
        <div id="slot9Div" class="equipmentSlot"> <div class="imageContainer" id="slot9Container">			<?php 	echo draw_item("slot9",$_SESSION['id']);      ?>   </div></div>
        <div id="slot10Div" class="equipmentSlot"> <div class="imageContainer" id="slot10Container">		<?php 	echo draw_item("slot10",$_SESSION['id']);     ?>   </div></div>
		<div id="slot11Div" class="equipmentSlot"> <div class="imageContainer" id="slot11Container">		<?php 	echo draw_item("slot11",$_SESSION['id']);     ?>   </div></div>
		<div id="slot12Div" class="equipmentSlot"> <div class="imageContainer" id="slot12Container">		<?php 	echo draw_item("slot12",$_SESSION['id']);     ?>   </div></div>
		<div id="slot13Div" class="equipmentSlot"> <div class="imageContainer" id="slot13Container">		<?php 	echo draw_item("slot13",$_SESSION['id']);     ?>   </div></div>
		<div id="slot14Div" class="equipmentSlot"> <div class="imageContainer" id="slot14Container">		<?php 	echo draw_item("slot14",$_SESSION['id']);     ?>   </div></div>
		<div id="slot15Div" class="equipmentSlot"> <div class="imageContainer" id="slot15Container">		<?php 	echo draw_item("slot15",$_SESSION['id']);     ?>   </div></div>
		
		
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
        <li><a href = "equipment.php" class="active"><img id="ekwipunekmenu"></a></li>
		<li><a href = "magia.php"><img id="magiamenu"></a></li>
        <li><a href = "wyprawa.php"><img id="wyprawamenu"></a></li>
		<li><a href = "arena.php"><img id="arenamenu"></a></li>
        <li><a href = "logout.php"><img id="logoutmenu"></a></li>
        </ul>
    </nav>
    
</Body>
    
</HTML>


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
	

    $("#Text").load("read_stats.php", {co: 'statystykiEquipment' }).hide().fadeIn("fast");
	
    $(".equipmentSlot").hover(onEnter,onLeave);
    function onEnter()
    {
        //ID itemu na którym jest myszka
        var itemID = $(this).find('input').attr('value');
        var itemType = $(this).find('input').attr('class');

        //Sprawdzanie czy nie celujemy w pusty slot
        if ((itemID != 0) && (itemID != null))
        {
            //Sprawdzanie czy jest z czym porównać
            var item2slot = "#" + itemType + "Div";
            var itemID2 = $(item2slot).find('input').attr('value');
                
            if(itemID2 != itemID)
            {
				$("#Text").load("read_stats.php", {co: 'item', id: itemID, id2: itemID2}).hide().fadeIn("fast");
            }
            else
            {
                itemID2 = 0;
				$("#Text").load("read_stats.php", {co: 'item', id: itemID, id2: itemID2}).hide().fadeIn("fast");
            }
        }
        else
        {
			$("#Text").load("read_stats.php", {co: 'statystykiEquipment' }).hide().fadeIn("fast");
        }
    }
    function onLeave()
    {
		$("#Text").load("read_stats.php", {co: 'statystykiEquipment' }).hide().fadeIn("fast");
    }
    
     
    var poczatkowySlot = '';
    var poczatkowyTyp = '';
    var poczatkowyID = '';
    var koncowySlot = '';
    var koncowyTyp = '';
    var koncowyID = '';
        
    
    $(".imageContainer").draggable({
        start: function(event, ui)
        {
            poczatkowySlot = $(this).find('input').attr('id');
            poczatkowyTyp = $(this).find('input').attr('class');
            poczatkowyID = $(this).find('input').attr('value');
        },
        opacity: 0.5,
        revert: true,
        revertDuration: 0,
        zIndex: 100,
		snap: true
    });
    
    $(".equipmentSlot").droppable({
        accept: ".imageContainer",
        tolerance: "intersect",
        drop: function(event, ui)
        {
            koncowySlot = $(this).find('input').attr('id');
            koncowyTyp = $(this).find('input').attr('class');
            koncowyID = $(this).find('input').attr('value');

			validate(poczatkowySlot, poczatkowyTyp, poczatkowyID, koncowySlot, koncowyTyp, koncowyID);
        }
    });
    
	
    function validate(poczSlot, poczTyp, poczID, konSlot, konTyp, konID)
    {
        var container1 = "#" + poczSlot + "Container";
        var container2 = "#" + konSlot + "Container";
        var image1 = "#" + poczSlot + "Image";
        var image2 = "#" + konSlot + "Image";
        var src1 = $(image1).attr("src");
        var src2 = $(image2).attr("src");
        var hidden1 = "#" + poczSlot;
        var hidden2 = "#" + konSlot;
            

        //To samo miejsce xD
        if(poczSlot == konSlot)
        {
            //Cofamy na defaultowe miejsce
            $(container1).css({"left": "0", "top": "0"});
        }
		//Ktoś przenosi ikonkę sprzedawania xD
		else if(poczSlot.includes("sell"))
		{
			//Cofamy na defaultowe miejsce
            $(container1).css({"left": "0", "top": "0"});
		}
		else if(konSlot.includes("sell"))
		{
			//Ktoś sprzedaje
			var cena = 5;
			
			zamien_sloty(image1,image2,src1,src2,hidden1,hidden2,poczTyp,poczID,konTyp,konID,poczSlot,konSlot);
			
			$.ajax({
				type: "POST",
				url: "sell_item.php",
				data: {poczatkowySlot: poczSlot, poczatkowyID: poczID, cena: cena}
			});
		}
        //Dwa luźne sloty, zamieniamy
        else if(poczSlot.includes("slot") && konSlot.includes("slot"))
        {
            updatuj_baze(poczSlot, poczID, konSlot, konID);
            zamien_sloty(image1,image2,src1,src2,hidden1,hidden2,poczTyp,poczID,konTyp,konID,poczSlot,konSlot);
        }
        //Ze slotu do ekwipunku gracza
        else if(poczSlot.includes("slot") && !konSlot.includes("slot"))
        {
            //Typy się zgadzają
            if(konSlot.includes(poczTyp))
            {
                updatuj_baze(poczSlot, poczID, konSlot, konID);
                zamien_sloty(image1,image2,src1,src2,hidden1,hidden2,poczTyp,poczID,konTyp,konID,poczSlot,konSlot);
            }
        }
        //Z ekwipunku gracza na slot
        else if(konSlot.includes("slot") && poczID != 0)
        {
            //Slot jest pusty
            if(konID == 0)
            {
                updatuj_baze(poczSlot, poczID, konSlot, konID);
                zamien_sloty(image1,image2,src1,src2,hidden1,hidden2,poczTyp,poczID,konTyp,konID,poczSlot,konSlot);
            }
            //Slot zajęty, ale ten sam typ, zamieniamy
            else if(poczTyp == konTyp)
            {
                updatuj_baze(poczSlot, poczID, konSlot, konID);
                zamien_sloty(image1,image2,src1,src2,hidden1,hidden2,poczTyp,poczID,konTyp,konID,poczSlot,konSlot);
            }
            //Slot zajęty, innym typem, szukamy wolnego slota
            else
            {
                for (var i=1; i<=15; i++)
                {
                    var konSlotSearch = "slot" + i;
                    var hiddenSearch = "#slot" + i;
                    var divSearch = "#slot" + i + "Div";
                    var konidSearch = $(divSearch).find('input').attr('value');
                    //Jest pusty slot
                    if(konidSearch == 0)
                    {
                        updatuj_baze(poczSlot, poczID, konSlotSearch, konidSearch);
                        var imageSearch = hiddenSearch + "Image";
                        var srcSearch = $(imageSearch).attr("src");
                        var kontypSearch = $(hiddenSearch).attr('class');
                            
                        zamien_sloty(image1,imageSearch,src1,srcSearch,hidden1,hiddenSearch,poczTyp,poczID,kontypSearch,konidSearch,poczSlot,konSlot);
                        break;
                    }
                }
            }
        }
        //Z ekwipunku na ekwipunek
        else
        {
            //Zamiana ringów miejscami
            if(poczSlot.includes("ring") && konSlot.includes("ring"))
            {
                updatuj_baze(poczSlot, poczID, konSlot, konID);
                zamien_sloty(image1,image2,src1,src2,hidden1,hidden2,poczTyp,poczID,konTyp,konID,poczSlot,konSlot);
            }
        }
    }
   
    function zamien_sloty(image1,image2,src1,src2,hidden1,hidden2,poczTyp,poczID,konTyp,konID,poczSlot,konSlot)
    {
        //Oba sloty zawierają itemy, zamieniamy
        if(poczID != 0 && konID != 0)
        {
            $(image1).attr("src", src2);
            $(image2).attr("src", src1);
			$(hidden1).attr("class", konTyp);
			$(hidden1).attr("value", konID);
			$(hidden2).attr("class", poczTyp);
			$(hidden2).attr("value", poczID);
        }
        //Dwa puste sloty, nic nie robimy
        else if (poczID == 0 && konID == 0)
        {
            
        }
        //Przeniesienie na pusty slot albo na sell
        else if (konID == 0)
        {
			//Wstawiamy puste zdjęcie na slot z którego zaczynamy 
			if(poczSlot.includes("slot"))
			{
				nazwa_pustego = "gfx/eq_slots/slot.png";
			}
			else
			{
				nazwa_pustego = "gfx/eq_slots/" + poczSlot + "_slot_000000.png";
			}
			$(image1).attr("src", nazwa_pustego);
			
			
			//Sprawdzamy czy końcowy slot jest sprzedażowy
			if(konSlot.includes("sell"))
			{
				$(hidden1).attr("class", 0);
				$(hidden1).attr("value", 0);
			}
			else
			{
				$(image2).attr("src", src1);
				$(hidden1).attr("class", konTyp);
				$(hidden1).attr("value", konID);
				$(hidden2).attr("class", poczTyp);
				$(hidden2).attr("value", poczID);
			}
        }
		//Zaczęliśmy z pustego
        else if (poczID == 0)
        {
            nazwa_pustego = "gfx/eq_slots/slot.png";
            $(image1).attr("src", src2);
            $(image2).attr("src", nazwa_pustego)
			$(hidden1).attr("class", konTyp);
			$(hidden1).attr("value", konID);
			$(hidden2).attr("class", poczTyp);
			$(hidden2).attr("value", poczID);
        }

    }
    
    function updatuj_baze(poczSlot, poczID, konSlot, konID)
    {
        $.ajax({
            type: "POST",
            url: "update_inventory.php",
            data: {poczatkowySlot: poczSlot, koncowySlot: konSlot, poczatkowyID: poczID, koncowyID: konID}
        });
    }
    

</script>
    