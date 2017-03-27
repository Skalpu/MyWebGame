<?php

    require_once('config.php');
    login_check();
    
	if (get_stat("last_login","users",$_SESSION['id']) != null)
    {
        header('Location:main.php');
        exit();
    }
	
    if($_POST)
    {
		$conn = connectDB();
		$eUserID = $conn->real_escape_string($_SESSION['id']);
		
        $ePlec = $conn->real_escape_string($_POST['plec']);
        $eRasa = $conn->real_escape_string($_POST['rasa']);
        $eKlasa = $conn->real_escape_string($_POST['klasa']);
        $eSila = $conn->real_escape_string($_POST['silaHidden']);
        $eZwinnosc = $conn->real_escape_string($_POST['zwinnoscHidden']);
        $eCelnosc = $conn->real_escape_string($_POST['celnoscHidden']);
        $eKondycja = $conn->real_escape_string($_POST['kondycjaHidden']);
        $eInteligencja = $conn->real_escape_string($_POST['inteligencjaHidden']);
        $eWiedza = $conn->real_escape_string($_POST['wiedzaHidden']);
        $eCharyzma = $conn->real_escape_string($_POST['charyzmaHidden']);
        $eSzczescie = $conn->real_escape_string($_POST['szczescieHidden']);
        
        $maxHP = 10 * ($_POST['kondycjaHidden']);
        $HP = $maxHP;
        $maxMana = 10 * ($_POST['wiedzaHidden']);
        $mana = $maxMana;
		
        $eMaxHP = $conn->real_escape_string($maxHP);
        $eHP = $conn->real_escape_string($HP);
        $eMaxMana = $conn->real_escape_string($maxMana);
        $eMana = $conn->real_escape_string($mana);
		
		$conn->query("UPDATE users SET level=1,plec='$ePlec',rasa='$eRasa',klasa='$eKlasa',sila='$eSila',zwinnosc='$eZwinnosc',celnosc='$eCelnosc',kondycja='$eKondycja',inteligencja='$eInteligencja',wiedza='$eWiedza',charyzma='$eCharyzma',szczescie='$eSzczescie',hp='$eHP',maxhp='$eMaxHP',mana='$eMana',maxmana='$eMaxMana',last_login=NOW(),last_update=NOW() WHERE id='$eUserID'");
		$conn->query("INSERT INTO equipment (id) VALUES ('$eUserID')");
		$conn->query("INSERT INTO spellbooks (id) VALUES ('$eUserID')");
		$conn->query("INSERT INTO user_mail (id) VALUES ('$eUserID')");
		
		$conn->close();
        
        header('Location: main.php');
        exit();
    }

?>



<HTML>
<Head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="index.css">
	<link rel="stylesheet" type="text/css" href="create_character.css">
    <Title>SkalpoGra</Title>
	
</Head>

<Body>
    
    <div id="divWizard"><img id="wizardFoto" src="gfx/wizard.png"></div>
    <div id="divMonster"><img id="monsterFoto" src="gfx/monster.png"></div>
    <div id="divMainOkno">
        <Form name="kreacja" method='post' action='create_character.php' onsubmit="return validate();">
          
        <div id="divPlec">
            <div class="charCreationLabel">Płeć:</div>
            <input name="plec" id="plecFormMezczyzna" value="Mężczyzna" type="radio" checked> Mężczyzna<br>
            <input name="plec" id="plecFormKobieta" value="Kobieta" type="radio"> Kobieta<br>
        </div>    
        <div id="divRasa">
            <div class="charCreationLabel">Rasa:</div>
            <input name="rasa" id="rasaFormCzlowiek" value="Człowiek" type="radio" onclick="zmienRase('czlowiek')" checked> Człowiek<br>
            <input name="rasa" id="rasaFormOrk" value="Ork" onclick="zmienRase('ork')" type="radio"> Ork<br>
            <input name="rasa" id="rasaFormLesnyElf" value="Leśny elf" onclick="zmienRase('lesnyElf')" type="radio"> Leśny elf<br>
            <input name="rasa" id="rasaFormKrasnolud" value="Krasnolud" onclick="zmienRase('krasnolud')" type="radio"> Krasnolud<br>
            <input name="rasa" id="rasaFormWysokiElf" value="Wysoki elf" onclick="zmienRase('wysokiElf')" type="radio"> Wysoki elf<br>
        </div>   
        <div id="divKlasa">
            <div class="charCreationLabel">Klasa:</div>
            <input name="klasa" id="klasaFormZaklinacz" value="Zaklinacz" type="radio" checked> Zaklinacz<br>
            <input name="klasa" id="klasaFormDruid" value="Druid" type="radio"> Druid<br>
            <input name="klasa" id="klasaFormCzarnoksieznik" value="Czarnoksiężnik" type="radio"> Czarnoksiężnik<br>
            <input name="klasa" id="klasaFormMag" value="Mag" type="radio"> Mag<br>
            <input name="klasa" id="klasaFormZawadiaka" value="Zawadiaka" type="radio"> Zawadiaka<br>
        </div>
        
            
        <div id="divStatystyki">
            <input type="hidden" id="minSila" value="10">
            <input type="hidden" id="minZwinnosc" value="10">
            <input type="hidden" id="minCelnosc" value="10">
            <input type="hidden" id="minKondycja" value="10">
            <input type="hidden" id="minInteligencja" value="10">
            <input type="hidden" id="minWiedza" value="10">
            <input type="hidden" id="minCharyzma" value="10">
            <input type="hidden" id="minSzczescie" value="10">
            
            <input type ="hidden" id="silaHidden" name="silaHidden" value="10">
            <input type ="hidden" id="zwinnoscHidden" name="zwinnoscHidden" value="10">
            <input type ="hidden" id="celnoscHidden" name="celnoscHidden" value="10">
            <input type ="hidden" id="kondycjaHidden" name="kondycjaHidden" value="10">
            <input type ="hidden" id="inteligencjaHidden" name="inteligencjaHidden" value="10">
            <input type ="hidden" id="wiedzaHidden" name="wiedzaHidden" value="10">
            <input type ="hidden" id="charyzmaHidden" name="charyzmaHidden" value="10">
            <input type ="hidden" id="szczescieHidden" name="szczescieHidden" value="10">
            <input type ="hidden" id="pozostalePunktyHidden" name="pozostalePunktyHidden" value="0">
            
            <div class="statLabel">Siła: </div>
            <div class="statLabel">Zwinność: </div>
            <div class="statLabel">Celność: </div>
            <div class="statLabel">Kondycja: </div>
            <div class="statLabel">Inteligencja: </div>
            <div class="statLabel">Wiedza: </div>
            <div class="statLabel">Charyzma: </div>
            <div class="statLabel">Szczęście: </div>
            <div class="statLabel">Pozostałe punkty: </div>           
        </div>
            
                
        <div id="divStatystykiBoxes">
            <div id="divSila" class="afterBox">
                <input id="silaRemove" type="button" value="-" onclick="odejmijStat('silaValue','silaHidden','minSila')" class="buttonMinus">
                <div id="silaValue" class="box">10</div>
                <input id="silaAdd" type="button" value="+" onclick="dodajStat('silaValue','silaHidden')" class="buttonPlus">
            </div>
            <div id="divzwinnosc" class="afterBox">
                <input id="zwinnoscRemove" type="button" value="-" onclick="odejmijStat('zwinnoscValue','zwinnoscHidden','minZwinnosc')" class="buttonMinus">
                <div id="zwinnoscValue" class="box">10</div>
                <input id="zwinnoscAdd" type="button" value="+" onclick="dodajStat('zwinnoscValue','zwinnoscHidden')" class="buttonPlus">
            </div>
            <div id="divCelnosc" class="afterBox">
                <input id="celnoscRemove" type="button" value="-" onclick="odejmijStat('celnoscValue','celnoscHidden','minCelnosc')" class="buttonMinus">
                <div id="celnoscValue" class="box">10</div>
                <input id="celnoscAdd" type="button" value="+" onclick="dodajStat('celnoscValue','celnoscHidden')" class="buttonPlus">
            </div>
            <div id="divKondycja" class="afterBox">
                <input id="kondycjaRemove" type="button" value="-" onclick="odejmijStat('kondycjaValue','kondycjaHidden','minKondycja')" class="buttonMinus">
                <div id="kondycjaValue" class="box">10</div>
                <input id="kondycjaAdd" type="button" value="+" onclick="dodajStat('kondycjaValue','kondycjaHidden')" class="buttonPlus">
            </div>
            <div id="divInteligencja" class="afterBox">
                <input id="inteligencjaRemove" type="button" value="-" onclick="odejmijStat('inteligencjaValue','inteligencjaHidden','minInteligencja')" class="buttonMinus">
                <div id="inteligencjaValue" class="box">10</div>
                <input id="inteligencjaAdd" type="button" value="+" onclick="dodajStat('inteligencjaValue','inteligencjaHidden')" class="buttonPlus">
            </div>
            <div id="divWiedza" class="afterBox">
                <input id="wiedzaRemove" type="button" value="-" onclick="odejmijStat('wiedzaValue','wiedzaHidden','minWiedza')" class="buttonMinus">
                <div id="wiedzaValue" class="box">10</div>
                <input id="wiedzaAdd" type="button" value="+" onclick="dodajStat('wiedzaValue','wiedzaHidden')" class="buttonPlus">
            </div>
            <div id="divCharyzma" class="afterBox">
                <input id="charyzmaRemove" type="button" value="-" onclick="odejmijStat('charyzmaValue','charyzmaHidden','minCharyzma')" class="buttonMinus">
                <div id="charyzmaValue" class="box">10</div>
                <input id="charyzmaAdd" type="button" value="+" onclick="dodajStat('charyzmaValue','charyzmaHidden')" class="buttonPlus">
            </div>
            <div id="divSzczescie" class="afterBox">
                <input id="szczescieRemove" type="button" value="-" onclick="odejmijStat('szczescieValue','szczescieHidden','minSzczescie')" class="buttonMinus">
                <div id="szczescieValue" class="box">10</div>
                <input id="szczescieAdd" type="button" value="+" onclick="dodajStat('szczescieValue','szczescieHidden')" class="buttonPlus">
            </div>
            <div id="divPozostalePunkty" class="afterBox">
                <div id="pozostalePunktyValue" class="box">0</div>    
            </div>
        </div>
            
 
            
        <input id="submitForm" type='submit' value='Zapisz dane!'>
        </Form> 
        <div id="errorLabel">		<?php	if(isset($error_msg) && $error_msg != "") { echo $error_msg; }	?>		</div>
    </div>
    
    
    <div id="divLogo"><a href="index.php"><img id="logoFoto"></a></div>
    <div id="divRejestracja"><a href="register.php"><img id="rejestracjaFoto"></a></div>
    <div id="divLogowanie"><a href="login.php"><img id="logowanieFoto"></a></div>
    
</Body>
</HTML>




<script>
    document.addEventListener('DOMContentLoaded', zmienRase("czlowiek"));        
            
    function validate()
    {
        if (document.forms['kreacja']['pozostalePunktyHidden'].value != 0)
        {
            document.getElementById('errorLabel').innerHTML = "Musisz wydać wszystkie punkty statystyk.";
            return false;
        }
        else
        {
            document.getElementById('errorLabel').innerHTML = "{$err}"
            return true;
        }
    }
          
        
                                  
    function dodajStat(id_statystyki, id_hidden)
    {
        var pozostalePunkty = parseInt(document.getElementById("pozostalePunktyValue").innerHTML);
            
        if(pozostalePunkty > 0)
        {
            var aktualnaWartosc = parseInt(document.getElementById(id_statystyki).innerHTML)
            aktualnaWartosc++;
            document.getElementById(id_statystyki).innerHTML = aktualnaWartosc;
            document.getElementById(id_hidden).value = aktualnaWartosc;
                
            pozostalePunkty--;
            document.getElementById("pozostalePunktyValue").innerHTML = pozostalePunkty;
            document.getElementById("pozostalePunktyHidden").value = pozostalePunkty;
        }
    }
                    
    function odejmijStat(id_statystyki, id_hidden, id_minimum) 
    {
        var aktualnaWartosc = parseInt(document.getElementById(id_statystyki).innerHTML)
            
        if( (aktualnaWartosc-1) >= parseInt(document.getElementById(id_minimum).value) )
        {
            aktualnaWartosc--;
            document.getElementById(id_statystyki).innerHTML = aktualnaWartosc;
            document.getElementById(id_hidden).value = aktualnaWartosc;
                
            var pozostalePunkty = parseInt(document.getElementById("pozostalePunktyValue").innerHTML);
            pozostalePunkty++;
            document.getElementById("pozostalePunktyValue").innerHTML = pozostalePunkty;
            document.getElementById("pozostalePunktyHidden").value = pozostalePunkty;
        }
    }
        
          
    function zmienRase(rasa)
    {
        var sila = 10;
        var zwinnosc = 10;
        var celnosc = 10;
        var kondycja = 10;
        var inteligencja = 10;
        var wiedza = 10;
        var charyzma = 10;
        var szczescie = 10;    
            
            
        switch(rasa)
        {   
            case 'czlowiek':
                break;
            case 'ork':
                sila += 3;
                kondycja += 3;
                zwinnosc += 2;
                inteligencja -= 2;
                wiedza -= 2;
                charyzma -= 2;
                break;
            case 'lesnyElf':
                zwinnosc += 2;
                celnosc += 2;
                sila -= 2;
                break;
            case 'krasnolud':
                sila += 2;
                kondycja += 4;
                zwinnosc -= 2;
                charyzma -= 2;
                break;
            case 'wysokiElf':
                inteligencja += 3;
                wiedza += 3;
                charyzma += 1;
                sila -= 2;
                zwinnosc -= 2;
                kondycja -= 1;
                break;
            default:      
        }
                      
        var minSila = sila-3;
        var minZwinnosc = zwinnosc-3;
        var minCelnosc = celnosc-3;
        var minKondycja = kondycja-3;
        var minInteligencja = inteligencja-3;
        var minWiedza = wiedza-3;
        var minCharyzma = charyzma-3;
        var minSzczescie = szczescie-3;
        document.getElementById("minSila").value = minSila;
        document.getElementById("minZwinnosc").value = minZwinnosc;
        document.getElementById("minCelnosc").value = minCelnosc;
        document.getElementById("minKondycja").value = minKondycja;
        document.getElementById("minInteligencja").value = minInteligencja;
        document.getElementById("minWiedza").value = minWiedza;
        document.getElementById("minCharyzma").value = minCharyzma;
        document.getElementById("minSzczescie").value = minSzczescie;
            
            
        document.getElementById("silaValue").innerHTML = sila;
		document.getElementById("zwinnoscValue").innerHTML = zwinnosc;
        document.getElementById("celnoscValue").innerHTML = celnosc;		
        document.getElementById("kondycjaValue").innerHTML = kondycja;
        document.getElementById("inteligencjaValue").innerHTML = inteligencja;
        document.getElementById("wiedzaValue").innerHTML = wiedza;
        document.getElementById("charyzmaValue").innerHTML = charyzma;
        document.getElementById("szczescieValue").innerHTML = szczescie;
            
			
        document.getElementById("silaHidden").value = sila;
        document.getElementById("zwinnoscHidden").value = zwinnosc;
        document.getElementById("celnoscHidden").value = celnosc;
        document.getElementById("kondycjaHidden").value = kondycja;
        document.getElementById("inteligencjaHidden").value = inteligencja;
        document.getElementById("wiedzaHidden").value = wiedza;
        document.getElementById("charyzmaHidden").value = charyzma;
        document.getElementById("szczescieHidden").value = szczescie;
            
                   
        document.getElementById("pozostalePunktyValue").innerHTML = "0";
        document.getElementById("pozostalePunktyHidden").value = 0;
    }
</script>