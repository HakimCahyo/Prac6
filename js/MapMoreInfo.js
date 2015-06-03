var map;
var geocoder;
var placesServ;
var center;
var placeholder = document.getElementById("userLocation");

//initialise map
function init() {
	//map options
	//default map options to use if geolocation service is not supported
	var mapOpts = {
		center: {lat: -27.4980876, lng: 152.9933706},
		zoom: 15
	}

	//get user position
	if (navigator.geolocation) {
		navigator.geolocation.getCurrentPosition(swooty);
	} else {
		placeholder.innerHTML = "Geolocation service is not supported by your browser."
	}
	map = new google.maps.Map(document.getElementById("map-canvas"), mapOpts);
	map.setTilt(45);

	//set map center to the place's geoposition
	var placeLat = document.getElementById("lat").innerHTML;
	var placeLng = document.getElementById("lng").innerHTML;
	//update map center position
	center = new google.maps.LatLng(placeLat, placeLng);
	map.setCenter(center);
	//place a marker on the map
	var marker = new google.maps.Marker({
		position: center,
		map: map
	});
}

//callback function for geolocation service and getting places information simultaneously
function swooty(pos) {
	geocoder = new google.maps.Geocoder();
	geocoder.geocode({'latLng': {lat: pos.coords.latitude, lng: pos.coords.longitude}}, function(results, status) {
		if (status == google.maps.GeocoderStatus.OK) {
			var result = results[0];
			//gets first (most detailed address information) object from geocode result
			var swiggity = result.address_components.length;
			var street, suburb;
			for (var i=0; i<swiggity; i++) {
				var temp = result.address_components[i];
				if (temp.types.indexOf("route") >= 0) {
					street = result.address_components[i].long_name;
				}
				if (temp.types.indexOf("locality") >= 0) {
					suburb = result.address_components[i].short_name;
				}
			}
			placeholder.innerHTML = "Hi there, you are at " + street + ", " + suburb + ".";
		} else {
			placeholder.innerHTML = "Geolocation service failed to get your location information.";
		}
	});
}

//ajax call to put comment to database
function postComm() {
	var commText = $("#comm_text").val();
	var ref = $("#ref").html();
	if (commText == "") {
		alert("Please make sure to write a comment first!");
	} else {
		var takatinn = new XMLHttpRequest();
		takatinn.onreadystatechange = function() {
			var bleh;
			if (takatinn.readyState == 4 && takatinn.status == 200) {
				var result = JSON.parse(takatinn.responseText);
				if (result.success) {
					//clear comments table of previous comments
					$('#commentsTable').html('');
					$('#nocomm').hide();
					for (var i=0; i<result.comments.length; i++) {
						bleh = bleh + "<tr><td>"+result.comments[i]+"</td><td><em><small>Anon</small></em></td></tr>";
						if (i==result.comments.length-1) {
							bleh = bleh + "<tr>" +
								"<td colspan='2'>" +
									"<div class='input-group' style='max-width:100%; min-width:100%'>"+
										"<input type='text' class='form-control' id='comm_text'>"+
										"<span class='input-group-btn'>"+
											"<button class='btn btn-default' type='button' onclick='postComm()'>Post comment</button>"+
										"</span>"+
									"</div>"+
								"</td>"+
							"</tr>";
						}
					}
					$('#commentsTable').html(bleh);
				} else {
					alert("Something went wrong trying to put comment into the database.");
				}
			}
		};
		takatinn.open("GET", "putComment.php?text="+commText+"&ref="+ref, true);
		takatinn.send();
	}
}

//load map to html page only after the page has finished loading other elements
function loadMap() {
	var script = document.createElement("script");
	script.type = "text/javascript";
	script.src = "http://maps.googleapis.com/maps/api/js?callback=init";
	document.body.appendChild(script);
}

window.onload = loadMap;