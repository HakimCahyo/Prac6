<?php
	session_start();
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
		 <!-- Bootstrap core CSS -->
	    <link href="css/bootstrap.min.css" rel="stylesheet">

	    <!-- Custom styles for this template -->
		<link rel="stylesheet" href="css/index-css.css">

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
	    <style type="text/css">
	    html,
	    body,
	    #map-canvas {
	        height: 500px;
	        margin: 0;
	        padding: 0;
	    }
	    </style>

		<title>My Restaurant</title>
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
		<!-- hitwebcounter Code START -->
<a href="http://www.hitwebcounter.com/links.php" target="_blank">
<img src="http://hitwebcounter.com/counter/counter.php?page=6083103&style=0001&nbdigits=5&type=page&initCount=0" title="http://www.hitwebcounter.com/" Alt="http://www.hitwebcounter.com/"   border="0" >
</a><br/>
<!-- hitwebcounter.com --><a href="http://www.hitwebcounter.com/" title="Counter For Wordpress"
target="_blank" style="font-family: Geneva, Arial, Helvetica;
font-size: 8px; color: #9F9F97; text-decoration: underline ;"><code>Counter For Wordpress</code>
</a>
			<small><p id="userLocation" class="text-right" style="margin:0"></p></small>
			<!-- Map -->
			<div class="col-lg-8">
				<h3>Map</h3>
				<div id="map-wrapper">
					<div id="map-canvas"></div>
				</div>
			</div>

			<!-- Restaurant list -->
			<div class="col-lg-4">
				<h3>Restaurants</h3>
				<table id="restaurantsTable">
				</table>
			</div>
		</div>

		<!-- Load JS files -->
		<script src="js/jquery-1.11.2.min.js"></script>
		<script src="js/bootstrap.min.js"></script>
		<script src="js/lightbox.min.js"></script>
		<script src="js/maps.js"></script>
		<script type="text/javascript">
			$("#searchBtn").click(function() {
				var searchQuery = $("#searchTxt").val();
				searchPlaces(searchQuery);
			});
		</script>
	</body>
</html>