<?php
    session_start();
    $_SESSION['authenticated'] = false;
    $_SESSION = array();
    session_destroy();
    
    header('Location:index.php');
?>