<?php
	session_start();
	//empties username session variable and destroy session
	unset($_SESSION["username"]);
	session_destroy();
	header("Location: index.php");
?>