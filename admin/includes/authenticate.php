<?php 
	
	session_start();
	
	if (!isset($_SESSION['authenticated']) || $_SESSION['authenticated'] != 'aString') {
        header('Location: login.php');
        exit;
    }