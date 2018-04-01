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

var coordinate = [];
for (i = 0; i < locations.length; i++) {
	var myGeo = new BMap.Geocoder();
	myGeo.getPoint(locations[i], function(point){
		if (point) {
			var coord = [];
			coord.push(point.lng);
			coord.push(point.lat);
			coordinate.push(coord);
		}
	});
}

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


// Send data to backend server
var len = ids.length;
for (i = 0; i < len; i++) {
	sendGet(ids[i], titles[i], locations[i], years[i], coordinate[i][0], coordinate[i][1]);
}
