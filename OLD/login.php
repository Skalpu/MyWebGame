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
                $is_Admin = get_value($conn, "SELECT is_admin FROM users WHERE UPPER(username) = UPPER('$eUsername') AND password = '$ehPassword'"); 
                
                if ($is_Admin == 1)
                {
                    header('Location:adminer-4.2.5.php');
                    exit();
                }
                else
                {
                    header('Location:main.php');
                    exit();
                }
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
    
    <div id="divWizard"><img id="wizardFoto" src="gfx/wizard.png"></div>
    <div id="divMonster"><img id="monsterFoto" src="gfx/monster.png"></div>
    <div id="divMainOkno">
        <Form action='login.php' method='post'>
			<div id="usernameLabel">Użytkownik:</div>		
			<input id="usernameForm" type='text' name='username'><br>
			<div id="passwordLabel">Hasło:</div>			
			<input id="passwordForm" type='password' name='password'><br>
			<input id="submitForm" type='submit' value='Login'>
        </Form>
        <div id="errorLabel">		<?php	if(isset($error_msg) && $error_msg != "") { echo $error_msg; }	?>		</div>
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
    

    
