<?php
	//DB values and credentials, import from db_config.php
	require_once('db_config.php');
	//init variables
	$count = 0;
	$url = $_SERVER['REQUEST_URI'];
	//connect to db
	$db = mysqli_connect(DBSERVER, DBUSER, DBPASS, DATABASE)
		or die("Unable to connect to MySQL");

	//query db to get visitor counter data
	$query = "SELECT * FROM `counter`";
	$result = mysqli_query($db, $query);
	// if got value
	if (mysqli_num_rows($result) > 0) {
		//get result and store values in variables
		$row = mysqli_fetch_row($result);
		$count = $row[1];
		$count = intval($count) + 1;
		//update counter value
		$query = "UPDATE `counter` SET `counts`=$count WHERE `counter`='1'";
		$result = mysqli_query($db, $query);
	} else {
		die ("Something wrong with the MySQL database");
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
		
		<title>Visitor Counter</title>
	</head>
	<body>
		<?php
			echo "<h3>Hello there! You are visitor number ".$count."</h3>";
			header("Refresh: 2; URL=$url");
		?>
	</body>
</html>