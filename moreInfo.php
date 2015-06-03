<?php
	session_start();
	//DB values and credentials, import from db_config.php
	require_once('DBConfig.php');

	//initialise variables
	$location = array();
	$name;
	$address;
	$phone;
	$flag = true;
	$comments = array();

	//connect to db
	$db = mysqli_connect(DBSERVER, DBUSER, DBPASS, DATABASE)
		or die("Unable to connect to MySQL");

	//initialise required variables for places api call
	$apikey = "AIzaSyDT9OoPQ-q53N4jaHPmW4GYMxoPcm2BpMM";
	$placeRef = $_GET["ref"];

	//query db to determine if this particular restaurant has already been saved to the db
	$query = "SELECT * FROM `restaurant` WHERE `id`='$placeRef'";
	$result = mysqli_query($db, $query);
	//got restaurant data i.e. restaurant already in database
	if (mysqli_num_rows($result) > 0) {
		//get result and store values in variables
		while ($row = mysqli_fetch_row($result)) {
			$location["lat"] = $row[5];
			$location["lng"] = $row[6];
			$name = $row[2];
			$address = $row[3];
			$phone = $row[4];
		}
	} else {
		//no rest. data i.e. first time user clicked on more info for this restaurant i.e. need to store data to db
		//get places information given the initial variables
		$googleQuery = file_get_contents("https://maps.googleapis.com/maps/api/place/details/json?placeid=".$placeRef."&key=".$apikey);
		$googleQuery = json_decode($googleQuery, true);
		if ($googleQuery["status"] == "OK") {
			$place = $googleQuery["result"];
			//get location coords information
			$location["lat"] = $place["geometry"]["location"]["lat"];
			$location["lng"] = $place["geometry"]["location"]["lng"];
			$name = $place["name"];
			$address = $place["formatted_address"];
			$phone = $place["formatted_phone_number"];
			//insert data into database
			$query = "INSERT INTO `restaurant` (`id`, `name`, `address`, `phone`, `lat`, `long`) VALUES ('$placeRef','$name','$address','$phone','$location[lat]','$location[lng]')";
			$result = mysqli_query($db, $query);
			// if (!$result) {
			// 	echo ("<script>alert('Something went wrong while inserting data.');</script>");
			// }
		}
	}

	//check for comments
	$query = "SELECT * FROM `comment` WHERE `id`='$placeRef'";
	$result = mysqli_query($db, $query);
	if (mysqli_num_rows($result) > 0) {
		$flag = false;
		//get result and store values in variables
		while ($row = mysqli_fetch_row($result)) {
			array_push($comments, $row[1]);
		}
	}
?>
<!DOCTYPE html>
<html>
	<head>
		<meta charset="UTF-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<meta name="description" content="For Wis Prac 1">
		<meta name="author" content="Hakim C">
		<link rel="icon" href="img/demopage/favicon.ico">

	    <title>My Restaurant's of choice - More Info</title>

	    <!-- Bootstrap core CSS -->
	    <link href="css/bootstrap.min.css" rel="stylesheet">

	    <!-- Custom styles for this template -->
	    <link href="css/index-css.css" rel="stylesheet">

	    <!-- Just for debugging purposes. Don't actually copy these 2 lines! -->
	    <!--[if lt IE 9]><script src="../../assets/js/ie8-responsive-file-warning.js"></script><![endif]-->
	    <script src="js/ie-emulation-modes-warning.js"></script>

	    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
	    <!--[if lt IE 9]>
	      <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
	      <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
	    <![endif]-->

	    <!-- Lightbox CSS -->
	    <link rel="stylesheet" href="css/lightbox.css">
	</head>
	<body>
		<!-- Header -->
		<nav class="navbar navbar-inverse navbar-fixed-top">
			<div class="row-fluid">
				<div class="span6 navbar-header">
					<a class="navbar-brand">My Restaurants</a>
				</div>
				<div class="span6 pull-right">
					<div class="input-group navbar-brand" style="max-width:400px">
						<input type="text" class="form-control" name="search_text" placeholder="Search for..." id="searchTxt">
						<span class="input-group-btn">
							<button class="btn btn-default" type="button" id="searchBtn">Search</button>
						</span>
					</div>
					<?php
						if (isset($_SESSION['username'])) {
							echo ("<a class='navbar-brand' href='logout.php'><small>Logout</small></a>");
						} else {
							echo ("<a class='navbar-brand' href='loginForm.php'><small>Login</small></a>");
						}
					?>
				</div>
			</div>
		</nav>

		<!-- Main -->
		<div class="container">
			<small><p id="userLocation" class="text-right" style="margin:0"></p></small>
			<div class="row-fluid">
				<!-- Map -->
				<div class="col-lg-8">
					<h3>Map</h3>
					<div id="map-wrapper">
						<div id="map-canvas"></div>
					</div>
				</div>

				<!-- Details list -->
				<div class="col-lg-4">
					<h3>Details</h3>
					<p id="lat" style="display:none"><?php echo $location["lat"]; ?></p>
					<p id="lng" style="display:none"><?php echo $location["lng"]; ?></p>
					<p id="ref" style="display:none"><?php echo $placeRef; ?></p>
					<table id="detailsTable">
						<tr>
							<td><strong><?php echo $name; ?></strong></td>
						</tr>
						<tr>
							<td><?php echo $address; ?></td>
						</tr>
						<tr>
							<td><?php echo $phone; ?></td>
						</tr>
					</table>
				</div>
			</div>
			<div class="row-fluid">
				<div class="col-lg-12">
					<h3>Comments</h3>
					<table style="min-width:100%" id="commentsTable">
						<tr>
							<!-- List of comments -->
							<?php
								if ($flag) {
									echo ("<small id='nocomm'>No comments yet..</small>");
								} else {
									for ($i=0; $i<sizeof($comments); $i++) {
										echo ("<tr><td>".$comments[$i]."</td><td><em><small>Anon</small></em></td></tr>");
									}
								}
							?>
						</tr>
						<!-- Comment input row -->
						<tr>
							<td colspan="2">
								<div class="input-group" style="max-width:100%; min-width:100%">
									<input type="text" class="form-control" id="comm_text">
									<span class="input-group-btn">
										<button class="btn btn-default" type="button" onclick="postComm()">Post comment</button>
									</span>
								</div>
							</td>
						</tr>
					</table>
				</div>
			</div>
		</div>

		<!-- Load JS files -->
		<script src="js/jquery-1.11.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/lightbox.min.js"></script>
		<script src="js/MapMoreInfo.js"></script>
		<script type="text/javascript">
			$("#searchBtn").click(function() {
				var searchQuery = $("#searchTxt").val();
				searchPlaces(searchQuery);
			});
		</script>
	</body>
</html>