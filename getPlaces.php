<?php
	//variable to return to the ajax call from client javascript
	$response = array();

	//initialise required variables for places api call
	$apikey = "AIzaSyDT9OoPQ-q53N4jaHPmW4GYMxoPcm2BpMM";
	$coords = $_GET["coords"];
	echo (json_encode($coords));
	//get places information given the initial variables
	$googleQuery = file_get_contents("https://maps.googleapis.com/maps/api/place/nearbysearch/json?location=".$coords."&rankby=distance&type=restaurant&key=".$apikey);
	$googleQuery = json_decode($query, true);
	if ($googleQuery["status"] == "OK") {
		echo (json_encode("coords"));
		$places = $googleQuery["results"];
		//get only 10 restaurants max
		(sizeof($places) > 10 ? $reps=10 : $reps=sizeof($places));
		for ($i=0; $i<$reps; $i++) {
			$tempPlace = array();
			$tempLoc = array();
			//get location coords information
			$tempLoc["lat"] = $places[$i]["geometry"]["location"]["lat"];
			$tempLoc["lng"] = $places[$i]["geometry"]["location"]["lng"];
			$tempPlace["location"] = $tempLoc;
			//get name information
			$tempPlace["name"] = $places[$i]["name"];
			//get address information
			$tempPlace["address"] = $places[$i]["vicinity"];
			//get unique identifier information
			$tempPlace["id"] = $places[$i]["place_id"];
			array_push($response, $tempPlace);
		}
	}

	echo (json_encode($response));
?>