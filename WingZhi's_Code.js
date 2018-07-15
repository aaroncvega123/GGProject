// Extract location Url numbers -> ids


var ids = [];
var locUrls = document.querySelectorAll("tbody tr")
for (i = 0; i < locUrls.length; i++) {
	var locUrl = locUrls[i].getAttribute("onclick")
	var num = locUrl.split("(");
	ids.push(num[1].split(",")[0]);
}

// Extract Company names
var titles = [];
var ele = document.getElementsByClassName("text-left");
for (i = 1; i < ele.length - 3; i++) {
	titles.push(ele[i]["innerText"]);
}

// Extract locations and years
var locations = [];
var years = [];

var loc = document.querySelectorAll(".text-left + td");
var year = document.querySelectorAll(".text-left + td + td");

for (i = 0; i < loc.length; i++) {
	locations.push(loc[i]["innerText"]);
	years.push(year[i]["innerText"]);
}

// var script = document.createElement("script");
// script.src = "http://api.map.baidu.com/api?v=2.0&ak=AVpUxExAZfNTcMV8Wn1uccLu";
// document.head.appendChild(script);
function sleep(milliseconds) {
	var start = new Date().getTime();
	for (var i = 0; i < 1e7; i++) {
		if ((new Date().getTime() - start) > milliseconds){
			break;
		}
	}
}

var coordinate = [];

var start = 0;
var index = start;
var end = 450;

for (var i = start; i < end; i++) {
	if(i >= locations.length){
		break;
	}
	//sleep(500);	
	var myGeo = new BMap.Geocoder();
	var pointReal = false;
	myGeo.getPoint(locations[i], function(point){	
		//var currentIndex = i;

		if(point){
			var coord = [];
			coord.push(point.lng);
			coord.push(point.lat);
			coordinate[index] = coord;
			index++;				
		}
		else{
			var coord = [];
			coord.push(0);
			coord.push(0);
			coordinate[index] = coord;
			index++;	
		}
	});
}
alert("Coordinates length: " + coordinate.length);


//Getting baidu coordinates one by one (copy and paste code into console)
var coordinate = [];
var index = 0;
var i = 0;

//repeatedly copy and paste this into console to fill coordinate[]
console.log(coordinate.length);
if(coordinate.length < locations.length){
	var myGeo = new BMap.Geocoder();
	myGeo.getPoint(locations[i], function(point){	
		//var currentIndex = i;

		if(point){
			var coord = [];
			coord.push(point.lng);
			coord.push(point.lat);
			coordinate[index] = coord;
			index++;				
		}
		else{
			var coord = [];
			coord.push(0);
			coord.push(0);
			coordinate[index] = coord;
			index++;	
		}
	})
	i++;
}


///
var coordinate = [];

var ids_trimmed = [];
var titles_trimmed = [];
var locations_trimmed = [];
var years_trimmed = [];

for (i = 0; i < locations.length; i++) {
	var myGeo = new BMap.Geocoder();
	var pointPushed = false;
	var finished = false;
	myGeo.getPoint(location[i], function(point){	
		if(point){
			var coord = [];
			coord.push(point.lng);
			coord.push(point.lat);
			coordinate.push(coord);
		}
		else{
			alert("location:" + location[i] + " i: " + i);
		}
	});
}

for (i = 0; i < coordinate.length; i++) {
	var myGeo = new BMap.Geocoder();
	myGeo.getLocation(new BMap.Point(coordinate[i][0], coordinate[i][1]), function(result){	
		if(result){
			location_to_push = "";
			for(var j = 0; j < result.addressComponents.city.length; j++){
				if(result.addressComponents.city[j] != '市'){
					location_to_push += result.addressComponents.city[j];
				}
			}
			location_to_push += " / ";
			for(var j = 0; j < result.addressComponents.province.length; j++){
				if(result.addressComponents.province[j] != '省'){
					location_to_push += result.addressComponents.province[j];
				}
			}
			locations_trimmed.push(location_to_push)
		}
	});
}

var myGeo = new BMap.Geocoder();
myGeo.getLocation(new BMap.Point(coordinate[1][0], coordinate[1][1]), function(result){	
	if(result){
		console.log(result.addressComponents.city);
	}
});

console.log(indexes.length);
console.log(coordinate.length);

// var jsonIds = JSON.stringify(ids);
// var jsonTitles = JSON.stringify(titles);
// var jsonLocs = JSON.stringify(locations);
// var jsonYears = JSON.stringify(years);
// var mydiv = document.getElementById('ipe').innerHTML 
// = '<form id="geti"  action="http://www.lovegreenguide.com/saveIPE.php" method="post"><input name="ids" type="hidden" value="'+ jsonIds +'" /><input name="names" type="hidden" value="'+ jsonTitles +'" /><input name="locations" type="hidden" value="'+ jsonLocs +'" /><input name="years" type="hidden" value="'+ jsonYears +'" />/form>';
// var f=document.getElementById('geti');
// if(f){
// 	f.submit();
// }

// $.ajax({
// 	type:'POST',
// 	data: {ids: jsonIds},
// 	url:'http://www.lovegreenguide.com/saveIPE.php',
// 	cache: false,
// 	success: function() {
// 		alert("Sent");
// 	}
// });


function sendGet(compID, compName, compLocation, year, lng, lat) {
	var ajax = new XMLHttpRequest();
	var params = new FormData();
	params.append("compID", compID);
	params.append("compName", compName);
	params.append("compLocation", compLocation);
	params.append("year", year);
	params.append("longitude", lng);
	params.append("latitude", lat);
 	ajax.open("POST", "http://www.lovegreenguide.com/saveIPE.php", true);
 	ajax.send(params);
}

// use ordered coordinates
var len = ids.length;
for (i = 0; i < len; i++) {
	sendGet(ids[i], titles[i], locations[i], years[i], orderedCoordinates[i][0], orderedCoordinates[i][1]);
}

// Send data to backend server
var len = ids.length;
for (i = 0; i < len; i++) {
	sendGet(ids[i], titles[i], locations[i], years[i], coordinate[i][0], coordinate[i][1]);
}

// Use trimmed arrays
var len = ids_trimmed.length;
for (i = 0; i < len; i++) {
	sendGet(ids_trimmed[i], titles_trimmed[i], locations_trimmed[i], years_trimmed[i], coordinate[i][0], coordinate[i][1]);
}

