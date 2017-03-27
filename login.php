<?php
	
    if($_POST)
    {
		require_once("config.php");  
		$error_msg = "";
		
		$conn = connectDB();
		$username = $_POST['username'];
        $password = $_POST['password'];
		$eUsername = $conn->real_escape_string($username);
		$ehPassword = $conn->real_escape_string(md5($password));
		
		$result = $conn->query("SELECT 1 FROM users WHERE UPPER(username) = UPPER('$eUsername') AND password = '$ehPassword'");
		$count = $result->num_rows;
		
		if($count == 1)
		{
			session_start();
			$_SESSION['authenticated'] = true;
            $_SESSION['username'] = $username;
			$userID = get_value($conn, "SELECT id FROM users WHERE UPPER(username) = UPPER('$eUsername') AND password = '$ehPassword'");
			$_SESSION['id'] = $userID;
			
			$last_login = get_value($conn, "SELECT last_login FROM users WHERE UPPER(username) = UPPER('$eUsername') AND password = '$ehPassword'");
			if ($last_login == NULL)
            {
                header('Location:create_character.php');
                exit();
            }
			else
			{
				$conn->query("UPDATE users SET last_login = NOW() WHERE UPPER(username) = UPPER('$eUsername') AND password = '$ehPassword'");
                header('Location:main.php');
                exit();
			}
		}
		else
		{
			$error_msg = "Nieprawidłowy login lub hasło, spróbuj ponownie.";
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
    
    <div id="divWizard"><img id="wizardFoto"></div>
    <div id="divMonster"><img id="monsterFoto"></div>
    <div id="divMainOkno">
		<div id="usernameLabel" class='centerLabel'>Użytkownik:</div>	
		<div id="passwordLabel" class='centerLabel'>Hasło:</div>		
		<div id="errorLabelLogin" class='centerLabel'>		<?php	if(isset($error_msg) && $error_msg != "") { echo $error_msg; }	?>		</div>
	
        <Form action='login.php' method='post'>
			<input id="usernameForm" class='centerForm' type='text' name='username'>
			<input id="passwordForm" class='centerForm' type='password' name='password'>
			<input id="submitFormLogin" class='centerForm' type='submit' value='Logowanie'>
        </Form>
       
    </div>
    
    
    <div id="divLogo"><a href="index.php"><img id="logoFoto"></a></div>
    <div id="divRejestracja"><a href="register.php"><img id="rejestracjaFoto"></a></div>
    <div id="divLogowanie"><a href="login.php"><img id="logowanieFoto"></a></div>
    
</Body>
</HTML>
    
	
	
	
<script>

    document.addEventListener('DOMContentLoaded',function()
    {
        document.getElementById('usernameForm').focus();
    });

</script>
    

    
