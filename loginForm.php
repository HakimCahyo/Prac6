<?php
	session_start();
	if (isset($_SESSION['username'])) {
		if ($_SESSION["username"] == "INFS") {
			header("Location: index.php");
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="utf-8">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<link rel="stylesheet" href="css/bootstrap.min.css">
		<link rel="stylesheet" href="css/lightbox.css">
		<link rel="stylesheet" href="css/weew.css">

		<title>My Restaurant - Login</title>
	</head>
	<body>
		<!-- Header -->
		<nav class="navbar navbar-default">
			<div class="container">
				<div class="row-fluid">
					<div class="span6 navbar-header">
						<a class="navbar-brand">Login</a>
					</div>
				</div>
			</div>
		</nav>

		<div class="container">
			<div class="login-box">
				<?php
					if (isset($_SESSION['wrongCred']) && $_SESSION['wrongCred']) {
						echo('<small style="color: red; display: inline">Incorrect username/password</small><br>');
						unset($_SESSION["wrongCred"]);
					} else {
						echo('<small style="color: red; display: none">Incorrect username/password</small><br>');
					}
				?>
				<form method="POST" action="login.php">
					Username: <input type="text" name="username" size="20" /><br>
					Password: <input type="password" name="password" size="20" /><br>
					<input type="submit" value="Submit" name="submit"/>
					<input type="reset" value="Clear"/>
				</form>
			</div>
		</div>

		<!-- Load JS files -->
		<script src="js/jquery-1.11.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
	</body>
</html>