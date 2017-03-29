<?php
//TODO: Max username/password length

    if($_POST)
    {
        require_once("config.php");
		$error_msg = "";
		$username = $_POST['username'];
        $password = $_POST['password'];
        $confirm = $_POST['confirm'];

        $conn = connectDB();
                
        $eUsername = $conn->real_escape_string($username);                    
        $result = $conn->query("SELECT 1 FROM users WHERE UPPER(username) = UPPER('$eUsername')");       
        $count = $result->num_rows;                                                                              

        if ($count >= 1)
        {
			$error_msg = "Taki użytkownik już istnieje.";
		}
        else 
        {
            $eUsername = $conn->real_escape_string($username);
            $ehPassword = $conn->real_escape_string(md5($password));
            $conn->query("INSERT INTO users(username,password) VALUES ('$eUsername','$ehPassword')");
			$userID = get_value($conn, "SELECT id FROM users WHERE UPPER(username) = UPPER('$eUsername') AND password = '$ehPassword'");
			$conn->close();
			
			session_start();
			$player = new Player();
			$player->username = $username;
			$player->id = $userID;
			
			$_SESSION['authenticated'] = true;
			$_SESSION['player'] = $player;

			header('Location:create_character.php');
            exit();
        }
            
        $conn->close();  
    }
    
?>




<HTML>
<Head>

    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="index.css">
    <link rel="stylesheet" type="text/css" href="loginregister.css">
    <Title>SkalpoGra</Title>
    
</Head>
    
<Body>

    <div id="divWizard" class="noselect"><img id="wizardFoto"></div>
    <div id="divMonster" class="noselect"><img id="monsterFoto"></div>
    <div id="divMainOkno">
		<div id="usernameLabel" class='centerLabel'>Użytkownik:</div>
		<div id="passwordLabel" class='centerLabel'>Hasło:</div>
		<div id="confirmLabel" class='centerLabel'>Potwierdź hasło:</div>
		<div id="errorLabelRegister" class='centerLabel'>		<?php	if(isset($error_msg) && $error_msg != "") { echo $error_msg; }	?>		</div>
		
			<Form name="rejestracja" onsubmit="return validateForm();" method="post" action="register.php">
				<input type='text' class='centerForm' name='username' id='usernameForm'>
				<input type='password' class='centerForm' name='password' id='passwordForm'>
				<input type='password' class='centerForm' name='confirm' id='confirmForm'>
				<input type='submit' class='centerForm' value='Rejestracja' id='submitFormRegister'>
			</Form>
        
    </div>
    
    
    <div id="divLogo" class="noselect"><a href="index.php"><img id="logoFoto"></a></div>
    <div id="divRejestracja" class="noselect"><a href="register.php"><img id="rejestracjaFoto"></a></div>
    <div id="divLogowanie" class="noselect"><a href="login.php"><img id="logowanieFoto"></a></div>
    
</Body>
</HTML>
    
    
            
    
	
<script>
    
	//Focus on "username" when page loaded
    document.addEventListener('DOMContentLoaded',function()
    {
        document.getElementById('usernameForm').focus();
    });
        
        
    //Client side form validation
    function validateForm()
    {
		var error_msg = "";
		
		
        if (document.forms["rejestracja"]["username"].value === "")
        {
			error_msg = "Nazwa użytkownika nie może być pusta!";
        }
        else if (document.forms["rejestracja"]["password"].value === "")
        {
			error_msg = "Hasło nie może być puste!";
        }
		else if (document.forms["rejestracja"]["confirm"].value === "")
		{
			error_msg = "Hasło nie może być puste!";
		}
		else if (document.forms["rejestracja"]["password"].value !== document.forms["rejestracja"]["confirm"].value)
        {
			error_msg = "Hasła nie są jednakowe.";
        }
        else if (document.forms["rejestracja"]["password"].value.length < 7)
        {
			error_msg = "Hasło musi być dłuższe niż 6 znaków.";    
        }
		
		
		if(error_msg == "")
		{
			return true;
		}
		else
		{
			document.getElementById("errorLabelRegister").innerHTML = error_msg;
			return false;
		}
    }
</script>
    
    
    
