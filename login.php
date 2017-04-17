<?php

    if($_POST)
    {
		require_once("config.php");  
		
		$conn = connectDB();
		$username = $_POST['username'];
        $password = $_POST['password'];
		$eUsername = $conn->real_escape_string($username);
		$ehPassword = $conn->real_escape_string(md5($password));
		
		$result = $conn->query("SELECT 1 FROM users WHERE UPPER(username) = UPPER('$eUsername') AND password = '$ehPassword'");
		$count = $result->num_rows;
		
		//Found a player
		if($count == 1)
		{
			session_start();
			$_SESSION['authenticated'] = true;
            $userID = get_value($conn, "SELECT id FROM users WHERE UPPER(username) = UPPER('$eUsername') AND password = '$ehPassword'");
			$last_login = get_value($conn, "SELECT last_login FROM users WHERE UPPER(username) = UPPER('$eUsername') AND password = '$ehPassword'");
			
			
			//Not created yet, create character
			if ($last_login == NULL)
            {
				$conn->close();
                header('Location:create_character.php');
                exit();
            }
			//All ok, move to main game
			else
			{
				$conn->query("UPDATE users SET last_login = NOW() WHERE id=$userID");
				$conn->close();

				$player = Player::withID($userID);
				$_SESSION['player'] = $player;
				
				header('Location:main.php');
                exit();
			}
		}
		//Didn't find a player, wrong login/pw
		else
		{
			$conn->close();
			$error_msg = "Nieprawidłowy login lub hasło, spróbuj ponownie.";
		}
	}

?>


<HTML>
<Head>

	<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
    <link rel="stylesheet" type="text/css" href="index.css">
    <link rel="stylesheet" type="text/css" href="loginregister.css">
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
    
    <div id="divWizard" class="noselect"><img id="wizardFoto"></div>
    <div id="divMonster" class="noselect"><img id="monsterFoto"></div>
    <div id="divMainOkno">
		<div id="usernameLabel" class='centerLabel'>Użytkownik:</div>	
		<div id="passwordLabel" class='centerLabel'>Hasło:</div>		
		<div id="errorLabelLogin" class='centerLabel'>		<?php	if(isset($error_msg) && $error_msg != "") { echo $error_msg; }	?>		</div>
	
        <Form action='login.php' method='post'>
			<input id="usernameForm" class='centerForm' type='text' name='username'>
			<input id="passwordForm" class='centerForm' type='password' name='password'>
			<input id="submitFormLogin" class='centerForm orange przycisk' type='submit' value='Logowanie'>
        </Form>
       
    </div>
    
    
    <div id="divLogo" class="noselect"><a href="index.php"><img id="logoFoto"></a></div>
    <div id="divRejestracja" class="noselect"><a href="register.php"><img id="rejestracjaFoto"></a></div>
    <div id="divLogowanie" class="noselect"><a href="login.php"><img id="logowanieFoto"></a></div>
    
</Body>
</HTML>
    
	
	
	
<script>

    document.addEventListener('DOMContentLoaded',function()
    {
        document.getElementById('usernameForm').focus();
    });

</script>
    

    
