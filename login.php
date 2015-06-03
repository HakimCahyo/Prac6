<?php
	session_start();
	if (($_REQUEST['username']=="infs" || $_REQUEST['username']=="INFS") && $_REQUEST['password']=="3202") {
		$_SESSION['username'] = "INFS";
		header("Location: index.php");
	} else {
		$_SESSION['wrongCred'] = true;
		header("Location: loginForm.php");
	}
?>