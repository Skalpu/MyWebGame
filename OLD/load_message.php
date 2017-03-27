<?php

	require_once('config.php');
    login_check();

	if($_POST)
	{
		$conn = connectDB();
		$IDmsg = $_POST['idmsg'];
		
		set_stat('messages','is_read',1,$IDmsg);
		$message = get_value($conn, "SELECT message FROM messages WHERE id = $IDmsg");
		$conn->close();
		
		echo $message;
	}
?>