<?php
	include("db.php");
?>
<!DOCTYPE html>
<html>
<head>
	<link href="WriteReview.css" type="text/css" rel="stylesheet" />
	<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
	<meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
   
	<script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=AVpUxExAZfNTcMV8Wn1uccLu"></script>
	<script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
	<title>Explore Reviews</title>
	<link rel="shortcut icon" href="green-pin.png">

	<!-- Latest compiled and minified CSS -->
     <!-- <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0-alpha.6/css/bootstrap.min.css" integrity="sha384-rwoIResjU2yc3z8GV/NPeZWAv56rSmLldC3R/AZzGRnGxQQKnKkoFVhFQhNUwEyJ" crossorigin="anonymous"> -->
     <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">

    <!-- jQuery library -->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

    <!-- Latest compiled JavaScript -->

    <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
</head>
<body>
	<?php
		include("header_b.php");				
	?>
	<!--
     	<nav class="navbar navbar-default" style="margin-bottom:0px; background-color: white; border: white; ">
                  <div class="container-fluid">
                    <div class="navbar-header">
                      
                      <a class="navbar-brand" href="index.php" style="color:green; font-size:25px; padding-top:10px;"><span><img src="green-pin.png" alt="green" width="28" height="35"/></span> LoveGreenGuide</a>
                    </div>

                    <div class="navbar-collapse collapse in" id="bs-example-navbar-collapse-1" aria-expanded="true" >
                     
                      <form action="search-all.php" class="navbar-form navbar-right" role="search"  >
                                                              <div class="form-group">
                                                                  <label class="sr-only" for="s_company">company:</label>
                                                                  <input type="text" class="form-control input-sm" id="suggestId" name="s_company" placeholder="Company Name / (Location + Company Name) / Industry / Product " size="62">
                                                              </div>
                                                              <div class="form-group" >
                                                                  <label class="sr-only" for="s_location">location</label>
                                                                  <input type="text" class="form-control input-sm" name="s_location" placeholder="Near Location">
                                                              </div>
                        <button type="submit" class="btn btn-default btn-sm">Submit</button>
                      </form>
               
                    </div>

                    <div id="searchResultPanel" style="border:1px solid #C0C0C0;width:150px;height:auto; display:none;"></div>
                    <div id="getCompany"></div>
                    
                  </div>
        </nav>




                  <nav class="navbar navbar-default" style="border-top-left-radius: 0; border-top-right-radius: 0; margin-bottom:0px; border: none;">
                    <div class="container-fluid">
                      <div class="navbar-header">
                        <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar" >
                          <span class="icon-bar"></span>
                          <span class="icon-bar"></span>
                          <span class="icon-bar"></span> 
                        </button>
                        -->
                        <!--<a class="navbar-brand" href="index.php" style="color:lightgreen; font-size:22px; padding-top:2px;"><span><img src="green-pin_2.png" alt="green" width="25" height="30"/></span> LoveGreenGuide</a>-->
                        <!--
                        <a class="navbar-brand" href="index.php" ><span class="glyphicon glyphicon-home"></span></a>


                      </div>
                      <div class="collapse navbar-collapse" id="myNavbar" >
                        <ul class="nav navbar-nav">
                          
                          <li id="profile"><a href="profile.php">My Reviews</a></li>
                          <li id="review"><a href="WriteReview.php">Write a Community Review</a></li> 
                          <li id="map"><a href="map.php">Explore Reviews on a Map</a></li> 
                          <li id="guideline"><a href="guidelines.php">Guidelines</a></li>
                        </ul>
                        <ul class="nav navbar-nav navbar-right">
                          <li><a href="signup.php"><span class="glyphicon glyphicon-user"></span> Sign Up</a></li>
                          <li><a href="user.php"><span class="glyphicon glyphicon-log-in"></span> LogIn/Out</a></li>
                          <li><a href="user.php">中文</a></li>
                        </ul>
                      </div>
                    </div>
                  </nav>
-->

                  <div class="container-fluid"> 
                  		  <form class="form-inline" role="form">
							  <div class="form-group">
							    <label for="go_location">Please search the city or company in the dropdown menu and click it. System'll bring the city or company location on the map:</label>
							    <input type="text" class="form-control input-sm" id="suggestId_2" size="60" placeholder="Search Baidu Map - Company Name or (Location + Company Name)">
							  </div>
							  <div id="searchResultPanel_2" style="border:1px solid #C0C0C0;width:150px;height:auto; display:none;"></div>
						  </form>

						  <form action="map_go.php" class="form-inline" role="form">
							  <div class="form-group">
							    <label for="industry">Please key in the industry or product you'd like to search on the map and click "Search Reviews on map".</label>
							    <input name="industry" type="text" class="form-control input-sm" size="30" placeholder="Industry">
							  </div>
							  <div class="form-group">
							  	<label class="sr-only" for="product">product</label>
							    <input name="product" type="text" class="form-control input-sm" size="30" placeholder="Product">
							  </div>
							  
							  <button type="submit" class="btn btn-default btn-sm">Search Reviews on map</button>
						  </form>
                  		  
		          </div>    
                  <div id="allmap"></div>
                  <!-- <ul class="mapType">                                                         
	                    <li class="selected"><a href=""><i class=""></i>Reviews with known source</a></li>
	                    <li class="selected"><a href=""><i class=""></i>Reviews with unknown source</a></li>
	                    <li class="selected"><a href=""><i class=""></i>China EPA enforcement data</a></li>
	               </ul> -->

                  <div class="container-fluid text-center">
                  		<h4>Location History - Click a mark on the above map and check out its reviews.</h4>
                  </div>          
                  
                  <div id="loading" style="display: none">
						<img src="loading.gif" />
						Loading ...
				  </div>
				  <div id="list"></div>
				  <div id="page"></div>
				  <div id="getMap_point"></div>

				  <!--
				  <div class="headfoot">
				                <p>
				                    <q>Share your feeling about the environment to the world!</q> - Green Guide<br />
				                    All pages and content &copy; Copyright Green Guide Inc.
				                </p>
				    
				                <div id="w3c">
				                    <a href="https://webster.cs.washington.edu/validate-html.php">
				                        <img src="https://webster.cs.washington.edu/images/w3c-html.png" alt="Valid HTML" /></a>
				                    <a href="https://webster.cs.washington.edu/validate-css.php">
				                        <img src="https://webster.cs.washington.edu/images/w3c-css.png" alt="Valid CSS" /></a>
				                    <a href="https://webster.cs.washington.edu/jslint/?referer">
				                        <img src="https://webster.cs.washington.edu/images/w3c-js.png" alt="Valid CSS" /></a>
				                </div>
			      </div>
			  -->
				<?php	 
				  	include("footer.php");
			  	?>	
                  
      
</body>
</html>

<?php
			try{

				  //$db=new PDO("mysql:dbname=myGreenGuide;host=us-cdbr-azure-west-b.cleardb.com","b4f6ad8be99b99","0ba0581c");
				  //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				  
				  
				  $rows=$db->query("select lng, lat, company, address, city, AVG(rating) as avg_r, COUNT(id) as num_r from review where status=1 GROUP BY lng,lat,company ");
				  $a=array();
				  foreach ($rows as $row) {
				 
						$a[]=$row;
				  } 

				  $cities=$db->query("select distinct CompanyLocation from ipe_data");

				  $ipeData=array();

				   foreach ($cities as $city) {

				  	$groups = $db->query("select CompanyID, CompanyName, CompanyLocation, Year, Longitude, Latitude from ipe_data where CompanyLocation ='$city[0]'");
				  	$eachR=array();
				  	foreach ($groups as $each) {
				  		$eachR[] = $each;
				  	}
				  	$ipeData[] = $eachR;
				  }

				  // $rowsIpe=$db->query("select CompanyID, CompanyName, CompanyLocation, Year from ipe_data");
				  
				  // foreach ($rowsIpe as $rowIpe) {
						// $ipeData[]=$rowIpe;
				  //  }
				}
			  catch(Exception $e){
				  die(print_r($e));
			  		die("Sorry, error occured. Please try again.");
			  }
  
?>



<script type="text/javascript">
	"use strict";
	// 百度地图API功能	
	var data = <?php echo json_encode($a) ?>;

	var ipe = <?php echo json_encode($ipeData) ?>;

	//alert(data[0].company);
	var map = new BMap.Map("allmap");
	//map.centerAndZoom(new BMap.Point(116.417854,39.921988), 15);
	map.centerAndZoom(new BMap.Point(114, 34), 5);

	// 定义一个控件类,即function
	function ZoomControl(){
	  // 默认停靠位置和偏移量
	  this.defaultAnchor = BMAP_ANCHOR_TOP_RIGHT;
	  this.defaultOffset = new BMap.Size(5, 5);
	}

	// 通过JavaScript的prototype属性继承于BMap.Control
	ZoomControl.prototype = new BMap.Control();

	// 自定义控件必须实现自己的initialize方法,并且将控件的DOM元素返回
	// 在本方法中创建个div元素作为控件的容器,并将其添加到地图容器中
	ZoomControl.prototype.initialize = function(map){
	  // 创建一个DOM元素
	  
	  var div = document.createElement("div");
	  var userData = document.createElement("button");
	  var epaData = document.createElement("button");

	  div.className = "container";
	  userData.className = "btn btn-default btn-sm";
	  epaData.className = "btn btn-default btn-sm";

	  // 添加文字说明
	  userData.appendChild(document.createTextNode("Community Reviews"));
	  epaData.appendChild(document.createTextNode("China EPA enforcement data"));

	  div.appendChild(userData);
	  div.appendChild(epaData);
	  // 设置样式
	  // ul.style.cursor = "pointer";
	  // ul.style.border = "1px solid gray";
	  // ul.style.backgroundColor = "white";

	  // toggle Community Review visulization layer
	  userData.onclick = function(e){
		review_flag = (-1) * review_flag;
		// reviewMarkers.forEach(funciton(marker) {
		// 	if (review_flag == 1) {
		// 		marker.show();
		// 	} else {
		// 		marker.hide();
		// 	}
		// });
		setMarkerLayer();
	  }

	  // toggle EPA data visualization layer
	  epaData.onclick = function(e) {
	  	epa_flag = (-1) * epa_flag;
		// epaMarkers.forEach(funciton(marker) {
		// 	if (epa_flag == 1) {
		// 		marker.show();
		// 	} else {
		// 		marker.hide();
		// 	}
		// });
	  	setMarkerLayer();
	  }

	  // 添加DOM元素到地图中
	  map.getContainer().appendChild(div);
	  // 将DOM元素返回
	  return div;
	}
	// 创建控件
	var myZoomCtrl = new ZoomControl();
	// 添加到地图当中
	map.addControl(myZoomCtrl);

	document.getElementById("map").className="active";
	
	addReviewMaker();
	addEPAMarker();
	var review_flag = 1;
	var epa_flag = 1;

	function setMarkerLayer() {
		if (review_flag === 1 && epa_flag === 1) {
			addReviewMaker();
			addEPAMarker();
		} else if (review_flag === 1 && epa_flag === -1) {
			map.clearOverlays();
			addReviewMaker();
		} else if (review_flag === -1 && epa_flag === 1) {
			map.clearOverlays();
			addEPAMarker();
		} else {
			map.clearOverlays();
		}
	}

	function mycity() {
	    if(document.getElementById("field").value){
	    	map.centerAndZoom(document.getElementById("field").value,15);
		}else{
			map.centerAndZoom(new BMap.Point(116.417854,39.921988), 15);
		}
	}
	 
	function addReviewMaker() {
		for(var i=0;i<data.length;i++){
		
			var m_color;
			//设置marker图标为水滴
			if (data[i].avg_r<-2){
				m_color="red";
			}
			else if(data[i].avg_r>=-2 && data[i].avg_r<-1 ){
				m_color="orange";
			}else if(data[i].avg_r>=-1 && data[i].avg_r<0){
				m_color="yellow";
			}else if(data[i].avg_r==0){
				m_color="white";
			}else if(data[i].avg_r>0 && data[i].avg_r<=1){
				m_color="aqua";
			}else if(data[i].avg_r>1 && data[i].avg_r<=2){
				m_color="lime";
			}else{
				m_color="green";
			}
			//alert(m_color);
			
			//var m_color="red";
			var marker = new BMap.Marker(new BMap.Point(data[i].lng,data[i].lat), {
			  // 指定Marker的icon属性为Symbol
			  icon: new BMap.Symbol(BMap_Symbol_SHAPE_POINT, {
				scale: 1.5,//图标缩放大小
				fillColor: m_color,//填充颜色
				fillOpacity: 0.8//填充透明度
			  })
			});
			
			var num = data[i].avg_r;
			var n = parseFloat(num).toFixed(2);
					
			var content = "<h4 onclick='win_com_click("+data[i].lng+","+data[i].lat+")' style='margin:0 0 5px 0;padding:0.2em 0; cursor: pointer; text-decoration: underline;'>"+data[i].company+"</h4>" + 
		"<p style='margin:0;line-height:1.5;font-size:13px;text-indent:2em;'>"+data[i].address+"</p>"+ "<p style='margin:0;line-height:1.5;font-size:13px;text-indent:2em;'>"+data[i].city+"</p>"+"<p style='margin:0;line-height:1.5;font-size:13px;font-weight:bold;text-indent:2em;'>"+"Rating: "+n+"</p>" + "<p onclick='win_com_click("+data[i].lng+","+data[i].lat+")' style='margin:0;line-height:1.5;color: orange;font-size:13px;text-indent:2em; cursor: pointer; text-decoration: underline;'>"+data[i].num_r+" Reviews"+"</p>";
		
			map.addOverlay(marker);               // 将标注添加到地图中
			addClickHandler(content,marker);
			addOverHandler(content,marker);
		}
	}
	
	var opts = {
				width : 260,     // 信息窗口宽度
				height: 130,     // 信息窗口高度
				//title : data[i].company , // 信息窗口标题
				enableMessage:true,//设置允许信息窗发送短息
				offset: new BMap.Size(10,-25)
			   };
			   		   	
				
		// 将地址解析结果显示在地图上,并调整地图视野
	
	function addEPAMarker() {
		// display IPE data
		for(var i=0;i<ipe.length;i++){
			
			// var point_color = "blue";
			//设置marker图标为水滴
			// var location = ipe[i][0].CompanyLocation;

			var lng = ipe[i][0].Longitude, lat = ipe[i][0].Latitude;

			var pCompany = new BMap.Marker(new BMap.Point(lng,lat), {
			  // 指定Marker的icon属性为Symbol
			  icon: new BMap.Symbol(BMap_Symbol_SHAPE_BACKWARD_CLOSED_ARROW, {
				scale: 1.6,//图标缩放大小
				fillColor: "blue",//填充颜色
				fillOpacity: 0.8//填充透明度
			  })
			});

			var curCity = ipe[i][0].CompanyLocation;
					
			// var content = "<h4 onclick='win_com_click("+lng+","+lat+")' style='margin:0 0 5px 0;padding:0.2em 0; cursor: pointer; text-decoration: underline;'>"+ipe[i].CompanyName+"</h4>" + "<p style='margin:0;line-height:1.5;font-size:13px;text-indent:2em;'>"+ipe[i].CompanyLocation+"</p>";

			var content = "<div style='height:130px;overflow-y:scroll'>";
			for (var j = 0; j < ipe[i].length; j++) {
				var link = "http://www.ipe.org.cn/IndustryRecord/regulatory-record.aspx?companyId=" + ipe[i][j].CompanyID + "&dataType=0&isyh=0";
				content += "<a id='clink' href="+link+" onclick='win_com_click("+lng+","+lat+")' style='margin:0 0 5px 0;padding:0.2em 0; cursor: pointer; text-decoration: underline;'>"+ipe[i][j].CompanyName+"</a>" + "<p style='margin:0;line-height:1.5;font-size:13px;text-indent:2em;'>"+ curCity +"</p>"
				
			}
			content += "</div>"
		
		
			map.addOverlay(pCompany);               // 将标注添加到地图中
			addClickHandler(content,pCompany);
			addOverHandler_ipe(content,pCompany,curCity);
			
		}
	}	

	function getBoundary(city){       
		var bdary = new BMap.Boundary();
		bdary.get(city, function(rs){       //获取行政区域  
			var count = rs.boundaries.length; //行政区域的点有多少个
			if (count === 0) {
				alert('未能获取当前输入行政区域');
				return ;
			}
          	var pointArray = [];
			for (var i = 0; i < count; i++) {
				var ply = new BMap.Polygon(rs.boundaries[i], {strokeWeight: 2, strokeColor: "#ff0000"}); //建立多边形覆盖物
				map.addOverlay(ply);  //添加覆盖物
				// pointArray = pointArray.concat(ply.getPath());

				ply.addEventListener('mouseout', function(clickEvent) {
					map.removeOverlay(ply);
				});
			}    
			// map.setViewport(pointArray);    //调整视野                
		});   
	}
	

	function win_com_click(lng,lat){
			var mydiv = document.getElementById('getMap_point').innerHTML = '<form id="map_point"  action="map_point.php"><input name="lng" type="hidden" value="'+ lng +'" /><input name="lat" type="hidden" value="'+ lat +'" /></form>';
	        var f=document.getElementById('map_point');
	        if(f){
	        f.submit();
	    	}
	}	
			   
	
	function addClickHandler(content,marker){
		marker.addEventListener("click",function(e){	
			pointInfo(e)			
			}			
		);
	}

	function addOverHandler(content,marker){
		marker.addEventListener("mouseover",function(e){
			openInfo(content,e)	
			}			
		);
	}

	function addOverHandler_ipe(content,marker,curCity) {
		marker.addEventListener("mouseover", function(e) {
			openInfo(content,e),
			getBoundary(curCity);
		});
	}
	
	
	function openInfo(content,e){
		
		var p = e.target;
		var point = new BMap.Point(p.getPosition().lng, p.getPosition().lat);
		var infoWindow = new BMap.InfoWindow(content,opts);  // 创建信息窗口对象 
		map.openInfoWindow(infoWindow,point); //开启信息窗口

		//alert(content);
	}
	
	function pointInfo(e){
		//toggleLoadingMessage();
		var pi = e.target;
		//var params = new FormData();
			//params.append("lng", pi.getPosition().lng);
			//params.append("lat", pi.getPosition().lat);
			//alert(pi.getPosition().lng);
			//alert(pi.getPosition().lat);
			/*
			var ajax = new XMLHttpRequest();
			ajax.onload = pInfoGet;
			ajax.open("GET", "ajax.php?lng="+ pi.getPosition().lng +"&lat="+ pi.getPosition().lat+ "&page="+"1", true);
			//ajax.send(params);
			ajax.send();
			*/

			var mydiv = document.getElementById('getMap_point').innerHTML = '<form id="map_point"  action="map_point.php"><input name="lng" type="hidden" value="'+ pi.getPosition().lng +'" /><input name="lat" type="hidden" value="'+ pi.getPosition().lat +'" /></form>';
	        var f=document.getElementById('map_point');
	        if(f){
	        f.submit();
	    	}

	}
	
	function pInfoGet() {
		//alert("3");
		toggleLoadingMessage();
		if (this.status == 200) {
			//alert("4");
			//alert(this.responseText);
			var json = JSON.parse(this.responseText);
			// set page list
			setPage(json.review_count, json.lng, json.lat);
			setList(json);
		} else {
			alert("HTTP error " + this.status + ": " + this.statusText + "\n" + this.responseText);
		}
	}


	function setPage(count, lng, lat){
				//alert(count);
				var squareArea=document.getElementById("page");

				while (squareArea.hasChildNodes()) {   
					squareArea.removeChild(squareArea.firstChild);
				}

				for(var i=0; i<count/10;i++){
					//alert(i);
					var square = document.createElement("div");
					square.className="page_note";
					square.data = {				
								lng:lng,
								lat:lat	
						}
					
					square.innerHTML=i+1;
					square.onmouseover=squareOver;
					square.onclick=squareClick;
					square.onmouseout=squareOut;

					squareArea.appendChild(square);
				}
	}


			function squareOver(){
				var no_line = document.querySelectorAll("#page div");
			    for (var i = 0; i < no_line.length; i++) {
			        no_line[i].style.textDecoration = "none";
			    }  
				
				this.style.textDecoration="underline";
				this.style.cursor="pointer";
	
			}

			function squareOut(){
				var no_line = document.querySelectorAll("#page div");
			    for (var i = 0; i < no_line.length; i++) {
			        no_line[i].style.textDecoration = "none";
			    }  
			}

			function squareClick(){
				toggleLoadingMessage();
				var ajax = new XMLHttpRequest();
				ajax.onload = showList;
				ajax.open("GET", "ajax.php?lng="+ this.data.lng +"&lat="+ this.data.lat+ "&page="+this.innerHTML, true);
				ajax.send();
			}


			function showList(){
				toggleLoadingMessage();
				if (this.status == 200) {
					//alert("showlist");
					//alert(this.responseText);
					var json = JSON.parse(this.responseText);
					setList(json);	
				}
				else {
					alert("HTTP error " + this.status + ": " + this.statusText + "\n" + this.responseText);
				}
			}


	function setList(json){

			var b_color = document.querySelectorAll("#page div");
			for (var c=0; c<json.review_count/10; c++){	
					b_color[c].style.border="";
			}
			for (var c=0; c<json.review_count/10; c++){
						//alert("c"+c);
						//alert("innerHTML"+b_color[c].innerHTML);
						//alert("page"+json.page);
					if (b_color[c].innerHTML==json.page)
					{
						b_color[c].style.border="2px solid blue";
					}
			}

			var list=document.getElementById("list");
			while (list.hasChildNodes()) {   
				list.removeChild(list.firstChild);
			}

			
			for (var i = 0; i < json.all.length; i++) {
				
				
				var info = document.createElement("div");
				info.className="info";
				var p = document.createElement("div");
				p.className = "info_p";	
				
				var pic=["_3.png","_2.png","_1.png","0.png","1.png","2.png","3.png"];
				var pic_rate= parseInt(json.all[i].review.rating)+3;
				
				var r_info= "Company Name: " + json.all[i].review.company +"<br/>" + "Company Address: "+ json.all[i].review.address +"<br/>" + "City: "+json.all[i].review.city+"<br/>" + "Rating: "+json.all[i].review.rating+"<br/>" + "<img src='"+ pic[pic_rate] +"' width='228' height='30' /><br/>"+ "Reviews: "+json.all[i].review.review +"<br/>"+ "Environment Type: "+json.all[i].review.water+" " +json.all[i].review.air+" "+json.all[i].review.waste+" "+json.all[i].review.land+" "+json.all[i].review.living+" "+json.all[i].review.other+"<br/>"+"Related News, Videos, or links: "+json.all[i].review.news+"<br/>"+"Time: "+json.all[i].review.time+"<br/>";
				
				p.innerHTML = r_info;			
															
				info.appendChild(p);


				var image_div = document.createElement("div");
				image_div.className="image_div";
				image_div.data = {				
						id: json.all[i].review.id				  	  
				}
		    	image_div.setAttribute("id", "image_div"+json.all[i].review.id);
		    	info.appendChild(image_div);


		    	if (json.all[i].img_count>0){
						var btn_v = document.createElement("BUTTON");				
				    	btn_v.innerHTML="View Image";   			
				    	btn_v.className="btn_v";
				    	btn_v.data = {				
								id: json.all[i].review.id				  	  
						}
				    	btn_v.onclick=btnClick_v;
				    	btn_v.setAttribute("id", "btn_v"+json.all[i].review.id);
				}

				//alert(json.all[i].img_count);
				var img_c_div = document.createElement("div");
				img_c_div.innerHTML="Image Count: "+ json.all[i].img_count;
				img_c_div.className="img_c";

				/*			
					for (var j = 0; j < json.all[i].all_image.length; j++) {											
						var img = document.createElement("img");						
						img.setAttribute("src", "data:image/jpg;base64,"+json.all[i].all_image[j].image);						
						img.className="img";
						img.onmouseover=imgOver;
											
						image_div.appendChild(img);												
					}
				*/				
				
	
				var ask=document.createElement("div");
				ask.innerHTML="Was this review …?";
				ask.className="ask";
				//info.appendChild(ask);


				var btn = document.createElement("BUTTON");
				if(json.all[i].review.help){
    				btn.innerHTML="Helpful : "+ json.all[i].review.help;
    			}
    			else{
    				btn.innerHTML="Helpful";
    			}
    			
    			btn.className="btn_true";
    			//alert(json.all[i].review.id);
    			//alert(json.all[i].review.help);
    			btn.data = {				
				  id: json.all[i].review.id,	
				  vote: json.all[i].review.help		  
				}
    			btn.onclick=btnClick;
    			btn.setAttribute("id", "btn"+json.all[i].review.id );
    			//info.appendChild(btn);


    			var report = document.createElement("BUTTON");
    			var r = document.createTextNode("Report as Inappropriate");
    			
    			report.appendChild(r);
    			report.className="btn_r";
    			report.data = {				
				  id: json.all[i].review.id		  
				}
    			report.onclick=rClick;
    			//info.appendChild(report);

    			var btn_c = document.createElement("div");
    			btn_c.className="btn_mc";

    			btn_c.appendChild(ask);
    			btn_c.appendChild(btn);
    			btn_c.appendChild(report);

    			if (json.all[i].img_count>0){
		    		btn_c.appendChild(btn_v);
		    	}
		    	btn_c.appendChild(img_c_div);

    			info.appendChild(btn_c);


    			var text = document.createElement("div");
    			text.setAttribute("id", json.all[i].review.id );
    			text.className="text";

    			info.appendChild(text);




				list.appendChild(info);				
			}
				
		  
		
	}


			function imgOver(){
				
				var no_border =this.parentNode.childNodes;
			    for (var i = 0; i < no_border.length; i++) {
			        no_border[i].className="img";
			    }  
				this.className="img_over";
			}	


			function imgOut(){
				var no_border =this.parentNode.childNodes;
			    for (var i = 0; i < no_border.length; i++) {
			        no_border[i].className="img";
			    }  
			}		


			function btnClick(){
				//alert(this.data.id);
				var id=this.data.id;
				var vote_c=this.data.vote;
				//alert(vote_c);
				
		        var params = new FormData();
				params.append("id", id);		
				params.append("vote_c", vote_c);		
				
				var ajax = new XMLHttpRequest();
				ajax.onload = vote;
				ajax.open("POST", "vote.php", true);
				ajax.send(params);
			}


			function btnClick_v(){
					toggleLoadingMessage();
					var id=this.data.id;					
					//alert("view id: "+id);

					var params = new FormData();
					params.append("id", id);			
							
					var ajax = new XMLHttpRequest();
					ajax.onload = view;
					ajax.open("POST", "view.php", true);
					ajax.send(params);

			}


			function view() {
						toggleLoadingMessage();
						if (this.status == 200) {

								var json = JSON.parse(this.responseText);
								//alert(this.responseText);
								var image_div=document.getElementById("image_div"+json.id );  
								
								for (var j = 0; j < json.all_image.length; j++) {											
									var img = document.createElement("img");						
									img.setAttribute("src", "data:image/jpg;base64,"+json.all_image[j].image);						
									img.className="img";
									img.onmouseover=imgOver;	
									img.onmouseout=imgOut;					
									image_div.appendChild(img);												
								}	
								document.getElementById("btn_v"+json.id).disabled = true;	
						}
						else{
							alert("error in ajax");
						}
			}


			function vote() {
				//alert("3");
				if (this.status == 200) {
					//alert("4");
					//alert(this.responseText);
					var json = JSON.parse(this.responseText);

					//alert("json.vote_c"+json.vote_c);
					if(json.set){
						var show=document.getElementById(json.id);
						show.innerHTML="Thank you for the voting!";	
						var v_btn=document.getElementById("btn"+json.id);	
						v_btn.data.vote=json.vote_c;				
					}
					else{
						var show=document.getElementById(json.id);
						show.innerHTML="You have voted this review. Thank you!";
					}
					var btn_c=document.getElementById("btn"+json.id);
					btn_c.innerHTML="Helpful : "+json.vote_c;
				}
				else{
					alert("error in ajax");
				}
			}


			function rClick(){
				//alert(this.data.id);
				var id=this.data.id;
				
		        var params = new FormData();
				params.append("id", id);				
				
				var ajax = new XMLHttpRequest();
				ajax.onload = getr;
				ajax.open("POST", "report.php", true);
				ajax.send(params);

			}

			function getr() {
				//alert("3");
				if (this.status == 200) {
					//var json = JSON.parse(this.responseText);
					//alert(this.responseText);
					var show=document.getElementById(this.responseText);
					show.innerHTML="This review has been reported to admin.";
				}
				else{

				}
			}



	function toggleLoadingMessage() {
		var load = document.getElementById("loading");
		if (load.style.display) {
			load.style.display = "";
		} else {
				load.style.display = "none";
		}
	}	
	
	
	
	// 百度地图API功能
	function G(id) {
		return document.getElementById(id);
	}
	
	var ac = new BMap.Autocomplete(    //建立一个自动完成的对象
		{"input" : "suggestId"
		,"location" : map
	});

	ac.addEventListener("onhighlight", function(e) {  //鼠标放在下拉列表上的事件
	var str = "";
		var _value = e.fromitem.value;
		var value = "";
		if (e.fromitem.index > -1) {
			value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
		}    
		str = "FromItem<br />index = " + e.fromitem.index + "<br />value = " + value;
		
		value = "";
		if (e.toitem.index > -1) {
			_value = e.toitem.value;
			value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
		}    
		str += "<br />ToItem<br />index = " + e.toitem.index + "<br />value = " + value;
		G("searchResultPanel").innerHTML = str;
	});

	var myValue;
	ac.addEventListener("onconfirm", function(e) {    //鼠标点击下拉列表后的事件
	var _value = e.item.value;
		myValue = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
		G("searchResultPanel").innerHTML ="onconfirm<br />index = " + e.item.index + "<br />myValue = " + myValue;
		
		setPlace();
	});
	
	
	function setPlace(){
		//map.clearOverlays();    //清除地图上所有覆盖物
		function myFun(){
			var pp = local.getResults().getPoi(0).point;    //获取第一个智能搜索的结果
			
			senGet(local.getResults().getPoi(0).title, pp.lng, pp.lat);
			
		}
		var local = new BMap.LocalSearch(map, { //智能搜索
		  onSearchComplete: myFun
		});
		local.search(myValue);
	}
	
	
	function senGet(company, lng, lat)
    {
        var mydiv = document.getElementById('getCompany').innerHTML = '<form id="getC"  action="company.php"><input name="company" type="hidden" value="'+ company +'" /><input name="lng" type="hidden" value="'+ lng +'" /><input name="lat" type="hidden" value="'+ lat +'" /></form>';
        var f=document.getElementById('getC');
        if(f){
        f.submit();
            //alert('submitted!');
        }
    }
	
	

	
	
	// 添加带有定位的导航控件
  var navigationControl = new BMap.NavigationControl({
    // 靠左上角位置
    anchor: BMAP_ANCHOR_TOP_LEFT,
    // LARGE类型
    type: BMAP_NAVIGATION_CONTROL_LARGE,
    // 启用显示定位
    enableGeolocation: true
  });
  map.addControl(navigationControl);
  // 添加定位控件
  
   map.disableScrollWheelZoom();
  //禁用滚轮放大缩小



  // 百度地图API功能
	
	var ac_2 = new BMap.Autocomplete(    //建立一个自动完成的对象
		{"input" : "suggestId_2"
		,"location" : map
	});

	ac_2.addEventListener("onhighlight", function(e) {  //鼠标放在下拉列表上的事件
	var str = "";
		var _value = e.fromitem.value;
		var value = "";
		if (e.fromitem.index > -1) {
			value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
		}    
		str = "FromItem<br />index = " + e.fromitem.index + "<br />value = " + value;
		
		value = "";
		if (e.toitem.index > -1) {
			_value = e.toitem.value;
			value = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
		}    
		str += "<br />ToItem<br />index = " + e.toitem.index + "<br />value = " + value;
		G("searchResultPanel_2").innerHTML = str;
	});

	var myValue_2;
	ac_2.addEventListener("onconfirm", function(e) {    //鼠标点击下拉列表后的事件
	var _value = e.item.value;
		myValue_2 = _value.province +  _value.city +  _value.district +  _value.street +  _value.business;
		G("searchResultPanel_2").innerHTML ="onconfirm<br />index = " + e.item.index + "<br />myValue = " + myValue_2;
		toggleLoadingMessage();
		setPlace_2();
	});
	
	var star;
	function setPlace_2(){
		map.removeOverlay(star);
		function myFun(){
			var pp = local.getResults().getPoi(0).point;    //获取第一个智能搜索的结果
			
			//alert("marker的位置是" + pp.lng + "," + pp.lat);
			//alert("marker的位置是" + local.getResults().getPoi(0).title );
			//alert("marker的位置是" + local.getResults().getPoi(0).address);
						
			map.centerAndZoom(pp, 15);
						
			var point = new BMap.Point(pp.lng, pp.lat);
			
			star = new BMap.Marker( point, {
				// 初始化五角星symbol
				icon: new BMap.Symbol(BMap_Symbol_SHAPE_STAR, {
				scale: 1.2,
				fillColor: "pink",
				fillOpacity: 0.3
			  })
			});
			map.addOverlay(star); 
			toggleLoadingMessage();
		}
		var local = new BMap.LocalSearch(map, { //智能搜索
		  onSearchComplete: myFun
		});
		local.search(myValue_2);
	}
	
</script>
