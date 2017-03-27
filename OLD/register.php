<?php

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
                
            $error_msg = 'Gratulacje, możesz teraz się zalogować.';
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
        <Form name="rejestracja" method='post' action='register.php' onsubmit="return validate();">
        <div id="usernameLabel">Użytkownik:</div>
		<input type='text' name='username' id='usernameForm'><br>
        <div id="passwordLabel">Hasło:</div>
		<input type='password' name='password' id='passwordForm'><br>
        <div id="confirmLabel">Potwierdź hasło:</div>
		<input type='password' name='confirm' id='confirmForm'><br>
        <input type='submit' value='Rejestracja' id='submitForm2'>
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
        
        
    //CLIENT SIDE VALIDATION
    function validate()
    {
        if (document.forms['rejestracja']['username'].value == "")
        {
            document.getElementById('errorLabel').innerHTML = "Nazwa użytkownika nie może być pusta!";
            return false;
        }
        else if (document.forms['rejestracja']['password'].value == "")
        {
            document.getElementById('errorLabel').innerHTML = "Hasło nie może być puste!";
            return false;
        }
		else if (document.forms['rejestracja']['password'].value != document.forms['rejestracja']['confirm'].value)
        {
            document.getElementById('errorLabel').innerHTML = "Hasła nie są jednakowe.";
            return false;
        }
        else if (document.forms['rejestracja']['password'].value.length < 7)
        {
            document.getElementById('errorLabel').innerHTML = "Hasło musi być dłuższe niż 6 znaków";    
            return false;
        }
        else
        {
            document.getElementById('errorLabel').innerHTML = "{$err}";
            return true;    
        }
    }
</script>
    
    
    
