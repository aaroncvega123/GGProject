<?php  
  		  include("db.php");
  		  session_start();
		  //$company=$_GET["company"];
          
		  $lng=htmlspecialchars($_GET["lng"]);
		  $lat=htmlspecialchars($_GET["lat"]);
		  
		  
		  try{
				  //$db=new PDO("mysql:dbname=myGreenGuide;host=us-cdbr-azure-west-b.cleardb.com","b4f6ad8be99b99","0ba0581c");
				  //$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
				 
				  //$rows=$con->query("select * from review where lng='$lng' and lat='$lat' and status=1 order by id DESC LIMIT 10 OFFSET 0 ");
				  
				 	//This part is for extracting all ratings to be displayed by rating bars
				  $rows = $db->prepare("select rating from review where lng=:lng and lat=:lat and status=1 order by id");
				  $rows->bindParam(':lng', $lng);
				  $rows->bindParam(':lat', $lat);
				  $rows->execute();

				  	$all_ratings;
					foreach($rows as $row){
						$all_ratings[] = $row;
					}


				  //$all_ratings = array();
				  //$i = 0;
				  //foreach ($rows as $row){
						//$all_ratings[$i] = $db->query("select rating from review where review_id=$row[0]");
						//$i++;
				  //}


				  $rows = $db->prepare("select * from review where lng=:lng and lat=:lat and status=1 order by id DESC LIMIT 10 OFFSET 0 ");
				  $rows->bindParam(':lng', $lng);
				  $rows->bindParam(':lat', $lat);
				  $rows->execute();
				  //print_r("pass1");
				  //$r_count=$con->query("select COUNT(id) from review where lng='$lng' and lat='$lat' and status=1");

				  $r_count = $db->prepare("select COUNT(id) from review where lng=:lng and lat=:lat and status=1 ");
				  $r_count->bindParam(':lng', $lng);
				  $r_count->bindParam(':lat', $lat);
				  $r_count->execute();

				  $review_count=$r_count -> fetchColumn();


				  
				  $all=array();
				  //print_r("pass2");
				  foreach ($rows as $row) {
				  		
						//$p_image= array();
						//$images=$con->query("select id from image where review_id=$row[0] ");
						$images_c=$db->query("select COUNT(id) from image where review_id=$row[0] ");

						$i_water;
						$waters=$db->query("select * from water_issue where review_id=$row[0] ");
						if($waters){	
							  foreach($waters as $water)	
							  {
								  $i_water=$water;
								 
							  }
						}
						//print_r("pass3");
						$i_air;
						$airs=$db->query("select * from air_issue where review_id=$row[0] ");
						if($airs){	
							  foreach($airs as $air)	
							  {
								  $i_air=$air;
								 
							  }
						}
						//print_r("pass4");
						$i_solid;
						$solids=$db->query("select * from solid_issue where review_id=$row[0] ");
						if($solids){	
							  foreach($solids as $solid)	
							  {
								  $i_solid=$solid;
								 
							  }
						}
						/*
						if($images){	
							  foreach($images as $image)	
							  {
								  $p_image[]=$image;
								 
							  }
						}*/
						$all[]=array("review"=>$row, "img_count" => $images_c -> fetchColumn(), "water"=> $i_water, "air"=>$i_air, "solid"=> $i_solid);
						//print_r($all);
				  } 

				  //$all[]=array_slice($all, (($rows_c -> fetchColumn())-(1*10))-1, 10)
				  
				  //print_r("pass5");
				  $p_map=$db->query("select lng, lat, company, address, city, AVG(rating) as avg_r, COUNT(id) as num_r from review where status=1 GROUP BY lng,lat,company ");
				  $a=array();
				  foreach ($p_map as $p) {				 
						$a[]=$p;
				  } 

			  }
		  catch(Exception $e){
			  die(print_r($e));
		  		//die("Sorry. Error occurred. Please try again.");
		  }
				  
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

<DOCTYPE! html>
<html lang="en">
    <head>
        <link href="WriteReview.css" type="text/css" rel="stylesheet" />
        <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="initial-scale=1.0, user-scalable=no" />
    
        <script type="text/javascript" src="http://api.map.baidu.com/api?v=2.0&ak=AVpUxExAZfNTcMV8Wn1uccLu"></script>
        <script src="http://libs.baidu.com/jquery/1.9.0/jquery.js"></script>
    
        <!-- Latest compiled and minified CSS -->
        <link rel="stylesheet" href="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/css/bootstrap.min.css">
    
        <!-- jQuery library -->
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
    
        <!-- Latest compiled JavaScript -->
        <script src="http://maxcdn.bootstrapcdn.com/bootstrap/3.3.5/js/bootstrap.min.js"></script>
    
        <title>Explore Reviews</title>
        <link rel="shortcut icon" href="green-pin.png">
    </head>
    <body>
        <style>
            #overlay{
                pointer-events:none;
                position: absolute;
                z-index: 1;
                top:0;
                bottom:0;
            }
            #newmap{
                position:static;
                top:0;
                bottom:0;
            }
            #upper-wraper{
                position: absolute;
                z-index: 1;
                box-shadow:2px 2px 2px #525252;
            }
            #lower-wrapper{
                top:0;
                bottom:0;
            }
            #infoDiv{
                box-shadow:2px 2px 2px #525252;
                overflow-y:auto;
                overflow-x:hidden;
				font-family:Calibri,black,sans-serif;
            }

            #infoDiv::-webkit-scrollbar-track
            {
                -webkit-box-shadow: inset 0 0 6px rgba(0,0,0,0.3);
                background-color: #F5F5F5;
            }
            
            #infoDiv::-webkit-scrollbar
            {
                width: 3px;
                background-color: #727272;
            }
            
            #infoDiv::-webkit-scrollbar-thumb
            {
                background-color: #727272;
            }

            .widget{
                pointer-events:all;
                box-shadow:2px 2px 2px #525252;
            }
            .green{
                background-color:green;
            }
            .silver{
                background-color:rgb(240, 240, 240);
            }
			.white{
				background-color:white;
			}
			.googleBlue{
				background-color:rgb(66, 133, 244);
			}
            .no-space-horizontal{
                padding-left:0px;
                padding-right:0px;
            }
            .no-space-vertical{
                padding-top:0px;
                padding-bottom:0px;
            }
            .add-margin-vertical{
                padding-top:5px;
                padding-bottom:5px;
            }
            .add-margin-horizontal{
                padding-left:10px;
                padding-right:10px;
            }
            .stylish-input-group .input-group-addon{
                background: white !important; 
            }
            .stylish-input-group .form-control{
                border-right:0; 
                box-shadow:0 0 0; 
                border-color:#ccc;
            }
            .stylish-input-group button{
                border:0;
                background:transparent;
            }
			h3{
				margin-top: 0px;
			}
			.carousel-control.left, .carousel-control.right {
				background-image:none !important;
				filter:none !important;
			}
        </style>
        <div id="global-wrapper" style="height:100vh">
            <div id="upper-wrapper">
                <div id="silver-bar"class="col-xs-12 silver" style="height:50px">
                    <div class="col-xs-3"><h1 style="margin-top:4px; font-weight:bold;">Tester</h1></div>
                    <div class="col-xs-7"></div>
                    <div class="col-xs-2">Buttons here</div>
                </div>
                <div id="green-bar" class="col-xs-12 green" style="height:60px">
                    <div class="container" style="margin-top:12px;">
                        <div class="row">
                            <div class="col-xs-12 col-sm-10 col-md-8 col-sm-offset-1 col-md-offset-2">
                                <div id="imaginary_container"> 
                                    <div class="input-group stylish-input-group">
                                        <input type="text" class="form-control"  placeholder="Search" >
                                        <span class="input-group-addon">
                                            <button type="submit" >
                                                <span class="glyphicon glyphicon-search"></span>
                                            </button>  
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div id="lower-wrapper">
                <div id="overlay" class="col-md-12 col-sm-12 col-xs-12 no-space-horizontal" style="margin-top:110px">
                    <div id="infoDiv" class="container-fluid col-lg-5 col-md-5 col-sm-6 col-xs-12 white no-space-horizontal widget">
                        <div class="no-wrap googleBlue" style="padding-top:10px">
                            <div class="col-xs-10"></div>
                            <div class="col-xs-2">
                                <div style="float:right" id ="closeInfo">
                                    <span><a class="glyphicon glyphicon-triangle-left" style="color:white"></a></span>
								</div>
                            </div>
                        </div>
                        <div class="container-fluid googleBlue" style="color:white"><div id="count_c"></div></div>
                        <div id="loading" style="display: none">
                                <img src="loading.gif" />
                                Loading ...
                        </div>
                        <div id="list">


						</div>
                        <div class="container-fluid"><div id="page"></div></div>
                        <div id="getMap_point"></div>                        
                    </div>
                    <div id="toggleButton" class="widget silver" style="height:60px; width:30px; text-align:center;">
                        <a style="color:gray; margin-top:70%;" class="glyphicon glyphicon-triangle-right"></a>
                    </div>

                </div>
                <div id="newmap" class="map_s col-md-12 container-fluid"></div>            
            </div>
        </div>

        <script type="text/javascript">
            
            function setDivHeightsOnResize(){
                var lowerContentHeight = $(window).height() - ($("#silver-bar").height() + $("#green-bar").height());
                $("#overlay").css({height: lowerContentHeight});
                $("#newmap").css({height: lowerContentHeight});
                $("#infoDiv").css({height: lowerContentHeight}); 
                //alert($(window).height());
                //alert(lowerContentHeight);               
            }
            setDivHeightsOnResize();

            $(window).resize(function(){
                setDivHeightsOnResize();
            });

            $("#toggleButton").hide();  

            $("#closeInfo").click(function(e){
                $("#infoDiv").animate({width: 'toggle'}, 0);
                $("#toggleButton").show();        
            });   
            $("#toggleButton").click(function(e){
                $("#infoDiv").animate({width: 'toggle'}, 0);
                $("#toggleButton").hide();        
            });   
        </script>
    </body>
</html>

<script type="text/javascript">
	"use strict";
	// 百度地图API功能	
	var data = <?php echo json_encode($a) ?>;
	var all = <?php echo json_encode($all) ?>;
	var lng = <?php echo json_encode($lng) ?>;
	var lat = <?php echo json_encode($lat) ?>;
	var r_count = <?php echo json_encode($review_count) ?>;

	var all_ratings = <?php echo json_encode($all_ratings) ?>;

	var ipe = <?php echo json_encode($ipeData) ?>;
	//console.log(ipe);

	//alert(all_ratings[0]);

	//alert(all[0].water.Water_Issue_ID);
	//alert(all[0].review.company);
	
	//var all_page1 = all.slice(all.length-10-1, all.length);
	var rate;
	for(var c=0;c<data.length;c++){
		if(data[c].company==all[0].review.company){
			rate = data[c].avg_r;
		}
	}

	//company name: all[i].review.company
	//address: all[i].review.address
	//city: all[i].review.city
	//industry: all[i].review.industry
	//product: all[i].review.product

	var count_c=document.getElementById("count_c");
	count_c.innerHTML = "<h1>" + all[0].review.company + "</h1>";
	count_c.innerHTML += "<div class=\"row\"><div id=\"barsDiv\" class=\"col-xs-8\"></div><div class=\"col-xs-4\"><h1>"+parseFloat(rate).toFixed(2)+"</h1><h4>" + r_count + " reviews</h4></div></div>";
	count_c.innerHTML += "<div>Address: " + all[0].review.address + "</br>City: " + all[0].review.city + "</br>Industry: " + all[0].review.industry + "</br>Product: " + all[0].review.product + "</div></br>";
	//count.className="count";
	//count_c.className="text-warning";
	//count_c.style.borderBottom="1px solid silver";
	count_c.style.marginTop = "3px";

	var barsDiv = document.getElementById("barsDiv");
	for(var i = 0; i < 7; i++){
		var figure = 3 - i;
		if(figure >0){
			figure = "+" + figure;
		}
		if(i == 6){
			barsDiv.innerHTML += " <div class=\"row\" style=\"margin-bottom:10px\"><div class=\"col-xs-1\" style=\"text-align:right;\">" + figure + "</div><div class=\"col-xs-10\"><div id=\"#"+figure+"\" style=\"background-color: orange; width: 1%;\">&emsp;</div></div></div> ";
		}
		else{
			barsDiv.innerHTML += " <div class=\"row\" style=\"margin-bottom:2px\"><div class=\"col-xs-1\" style=\"text-align:right;\">" + figure + "</div><div class=\"col-xs-10\"><div id=\"#"+figure+"\" style=\"background-color: orange; width: 1%\">&emsp;</div></div></div> ";	
		}
	}

	
	//document.getElementById("map").className="active";
	
	var map = new BMap.Map("newmap");
	map.enableScrollWheelZoom(true);     //开启鼠标滚轮缩放
	/*
	var navigationControl = new BMap.NavigationControl({
		// 靠左上角位置
		anchor: BMAP_ANCHOR_BOTTOM_RIGHT,
		// LARGE类型
		type: BMAP_NAVIGATION_CONTROL_LARGE,
		// 启用显示定位
		enableGeolocation: true
	});*/
	
	var offset1 = {
			anchor: BMAP_ANCHOR_BOTTOM_RIGHT,
			offset : new BMap.Size(0, 15),
			type :BMAP_NAVIGATION_CONTROL_LARGE
	}; // Offset
	map.addControl(new BMap.NavigationControl(offset1));

	var star;

	if(data.length>0){
				map.centerAndZoom(new BMap.Point(lng,lat), 15);

				
				var p = new BMap.Point(lng, lat);
				star = new BMap.Marker( p, {
				// 初始化五角星symbol
				icon: new BMap.Symbol(BMap_Symbol_SHAPE_STAR, {
				scale: 1.2,
				fillColor: "pink",
				fillOpacity: 0.3
				  })
				});
				map.addOverlay(star); 

				
				var opts = {
							//width : 280,     // 信息窗口宽度
							//height: 112,     // 信息窗口高度
							width : 260,     // 信息窗口宽度
							height: 130,     // 信息窗口高度
							//title : data[i].company , // 信息窗口标题
							enableMessage:true,//设置允许信息窗发送短息
							offset: new BMap.Size(10,-25)
						   };
						   
				var m_color;	

				//this loop places all the markers on the map	   	
				for(var i=0;i<data.length;i++){
					
					//alert(data[i]);
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
					
					var content = "<h4 onclick='win_com_click("+data[i].lng+","+data[i].lat+")' style='margin:0 0 5px 0;padding:0.2em 0; cursor: pointer; text-decoration: underline;'>" +data[i].company+"</h4>" + 
				"<p style='margin:0;line-height:1.5;font-size:13px;text-indent:2em'>"+data[i].address+"</p>"+ "<p style='margin:0;line-height:1.5;font-size:13px;text-indent:2em'>"+data[i].city+"</p>"+"<p style='margin:0;line-height:1.5;font-size:13px;font-weight: bold;text-indent:2em'>"+"Rating: "+n+"</p>" +"<p onclick='win_com_click("+data[i].lng+","+data[i].lat+")' style='margin:0;line-height:1.5;color: orange;font-size:13px;text-indent:2em; cursor: pointer; text-decoration: underline;'>"+data[i].num_r+" Reviews"+"</p>";
				
					
					map.addOverlay(marker);               // 将标注添加到地图中
					addClickHandler(content,marker);
					addOverHandler(content,marker);
					//alert("hello");

					/*
					toggleLoadingMessage();
					var ajax = new XMLHttpRequest();
					ajax.onload = pInfoGet;
					ajax.open("GET", "ajax_com.php?company="+data[i].company+"&lng="+ data[i].lng +"&lat="+ data[i].lat+"&page="+"1", true);
					ajax.send();
					*/
				}

				//I commented this out. I don't know why it's being run.
				setPage(r_count, all[0].review.company, lng, lat, 1);

				setList(1);			
				
		} else {
					//map.centerAndZoom(new BMap.Point(116.417854,39.921988), 15);
					map.centerAndZoom(new BMap.Point(114, 32), 5);

	}


	var allCities = [];
	//For setting ipe markers
	if(ipe.length>0){
		var opts = {
			//width : 280,     // 信息窗口宽度
			//height: 112,     // 信息窗口高度
			width : 260,     // 信息窗口宽度
			height: 130,     // 信息窗口高度
			//title : data[i].company , // 信息窗口标题
			enableMessage:true,//设置允许信息窗发送短息
			offset: new BMap.Size(10,-25)
		};

		function ComplexCustomOverlay(point, text, color, content, curCity){
			this._point = point;
			this._text = text;
			this._color = color;
			this._content = content;
			this._curCity = curCity;
		}
		ComplexCustomOverlay.prototype = new BMap.Overlay();
		ComplexCustomOverlay.prototype.initialize = function(mapTemp){
			this._map = mapTemp;
			var div = this._div = document.createElement("div");
			div.style.position = "absolute";
			div.style.zIndex = BMap.Overlay.getZIndex(this._point.lat);
			div.style.fontSize = "12px";
			div.style.borderRadius = "5px";			
			div.style.boxShadow = "2px 2px #525252";
			div.style.backgroundColor = this._color;
			div.style.color = "white";
			div.style.height = "18px";
			div.style.paddingBottom = "22px";
			div.style.paddingTop = "5px";
			div.style.paddingLeft = "5px";
			div.style.paddingRight = "5px";
			div.style.lineHeight = "18px";
			div.style.whiteSpace = "nowrap";
			div.style.MozUserSelect = "none";

			var span = this._span = document.createElement("span");
			div.appendChild(span);
			span.appendChild(document.createTextNode(this._text));      
			var that = this;
			
			var lng = this._point.lng;
			var lat = this._point.lat;
			var hello = "hello world"
			var content = this._content;
			var curCity = this._curCity;

			div.onmouseover = function(){
				var opts = {
					//width : 280,     // 信息窗口宽度
					//height: 112,     // 信息窗口高度
					width : 260,     // 信息窗口宽度
					height: 130,     // 信息窗口高度
					//title : data[i].company , // 信息窗口标题
					enableMessage:true,//设置允许信息窗发送短息
					offset: new BMap.Size(10,-25)
				};
				//alert(content);
				div.style.cursor = "pointer";	

				var point = new BMap.Point(lng, lat);
				var infoWindow = new BMap.InfoWindow(content, opts);  // 创建信息窗口对象
				map.openInfoWindow(infoWindow, point); //开启信息窗口

				getBoundary(curCity);
			};

			

			div.onclick = function(){
				alert("Hello");

				var mydiv = document.getElementById('getMap_point').innerHTML = '<form id="map_point"  action="map_point.php"><input name="lng" type="hidden" value="'+ lng +'" /><input name="lat" type="hidden" value="'+ lat +'" /></form>';
				var f = document.getElementById('map_point');
				if(f) { f.submit() };
			};

			//var arrow = this._arrow = document.createElement("div");
			//arrow.style.background = "url(http://map.baidu.com/fwmap/upload/r/map/fwmap/static/house/images/label.png) no-repeat";
			/*arrow.style.position = "absolute";
			arrow.style.width = "1100px";
			arrow.style.height = "10px";
			arrow.style.top = "22px";
			arrow.style.left = "10px";
			arrow.style.overflow = "hidden";*/
			//div.appendChild(arrow);

			map.getPanes().labelPane.appendChild(div);
			
			return div;
		}
		ComplexCustomOverlay.prototype.draw = function(){
			var mapTemp = this._map;
			var pixel = mapTemp.pointToOverlayPixel(this._point);
			this._div.style.left = pixel.x;
			this._div.style.top  = pixel.y;
		}

		for(var i=0;i<ipe.length;i++){
			
			// var point_color = "blue";
			//设置marker图标为水滴
			// var location = ipe[i][0].CompanyLocation;

			var lng = ipe[i][0].Longitude, lat = ipe[i][0].Latitude;

			var cityFound = false;
			var curCity = ipe[i][0].CompanyLocation;
			var cityAndBoolean = [];
			cityAndBoolean.push(curCity);
			cityAndBoolean.push(true);
			allCities.push(cityAndBoolean);


			/*var pCompany = new BMap.Marker(new BMap.Point(lng,lat), {
			  // 指定Marker的icon属性为Symbol
			  icon: new BMap.Symbol(BMap_Symbol_SHAPE_BACKWARD_CLOSED_ARROW, {
				scale: 1.6,//图标缩放大小
				fillColor: "blue",//填充颜色
				fillOpacity: 0.8//填充透明度
			  })
			});*/
			
			///////////////////////////////////////////////////////////////////////////////
			//For building label-markers

			
			var color;
			var entries = ipe[i].length;
			if(entries <= 10){
				color = "#66ff33";
			}
			else if(entries <= 20 && entries > 10){
				color = "#ccff33";//greenish yellow
			}
			else if(entries <= 50 && entries > 20){
				color = "#ffcc00";
			}
			else if(entries <= 100 && entries > 50){
				color = "#ff3300";
			}
			else if(entries <= 200 && entries > 100 ){
				color = "#993333";
			}
			else if(entries <= 300 && entries > 200){
				color = "#660066";
			}
			else if(entries > 300){
				color = "black";
			}


				//var point = new BMap.Point(lng, lat);
				//var infoWindow = new BMap.InfoWindow(this._content, opts);  // 创建信息窗口对象
				//map.openInfoWindow(infoWindow, point); //开启信息窗口
			///////////////////////////////////////////////////////////////////////////////




					
			// var content = "<h4 onclick='win_com_click("+lng+","+lat+")' style='margin:0 0 5px 0;padding:0.2em 0; cursor: pointer; text-decoration: underline;'>"+ipe[i].CompanyName+"</h4>" + "<p style='margin:0;line-height:1.5;font-size:13px;text-indent:2em;'>"+ipe[i].CompanyLocation+"</p>";

			var content = "<div style='height:130px;overflow-y:scroll'>";
			for (var j = 0; j < ipe[i].length; j++) {
				var link = "http://www.ipe.org.cn/IndustryRecord/regulatory-record.aspx?companyId=" + ipe[i][j].CompanyID + "&dataType=0&isyh=0";
				content += "<a id='clink' href="+link+" onclick='win_com_click("+lng+","+lat+")' style='margin:0 0 5px 0;padding:0.2em 0; cursor: pointer; text-decoration: underline;'>"+ipe[i][j].CompanyName+"</a>" + "<p style='margin:0;line-height:1.5;font-size:13px;text-indent:2em;'>"+ curCity +"</p>";
				
			}
			content += "</div>";

			var pCompany = new ComplexCustomOverlay(new BMap.Point(lng,lat), entries, color, content, curCity);	
			//var icon = new BMap.Marker(new BMap.Point(lng,lat),{icon:pCompany}); 
		
			map.addOverlay(pCompany);               // 将标注添加到地图中
			//addClickHandler(content,pCompany);
			//addOverHandler_ipe(content,pCompany,curCity);
			
		}
	}


	function win_com_click(lng,lat){
			var mydiv = document.getElementById('getMap_point').innerHTML = '<form id="map_point"  action="map_point.php"><input name="lng" type="hidden" value="'+ lng +'" /><input name="lat" type="hidden" value="'+ lat +'" /></form>';
	        var f=document.getElementById('map_point');
	        if(f){
	        f.submit();
	    	}
	}	

	function openInfo(content,e){	
		var p = e.target;
		//alert(p.getPosition().lng);
		//alert(p.getPosition().lat);
		var point = new BMap.Point(p.getPosition().lng, p.getPosition().lat);
		var infoWindow = new BMap.InfoWindow(content,opts);  // 创建信息窗口对象
		map.openInfoWindow(infoWindow,point); //开启信息窗口


	}
	
	function pointInfo(e){
		
		var pi = e.target;
		
			var mydiv = document.getElementById('getMap_point').innerHTML = '<form id="map_point"  action="map_point.php"><input name="lng" type="hidden" value="'+ pi.getPosition().lng +'" /><input name="lat" type="hidden" value="'+ pi.getPosition().lat +'" /></form>';
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
				openInfo(content,e);	
			}		
		);
	}

	function addOverHandler_ipe(content,marker,curCity) {
		marker.addEventListener("mouseover", function(e) {
			openInfo(content,e),
			getBoundary(curCity);
		});
	}

	/*function getBoundary(city){       
		var bdary = new BMap.Boundary();
		bdary.get(city, function(rs){       //获取行政区域  
			var count = rs.boundaries.length; //行政区域的点有多少个
			if (count === 0) {
				alert('未能获取当前输入行政区域');
				return ;
			}
          	var pointArray = [];
			for (var i = 0; i < count; i++) {
				var ply = new BMap.Polygon(rs.boundaries[i], {strokeWeight: 2, strokeColor: "#ff0000"}); //建立多边形覆盖物//new BMap.label("Hello");//
				//ply.setStyle({ borderColor: "#999" });
				map.addOverlay(ply);  //添加覆盖物
				// pointArray = pointArray.concat(ply.getPath());

				ply.addEventListener('mouseout', function(clickEvent) {
					map.removeOverlay(ply);
				});
			}    
			// map.setViewport(pointArray);    //调整视野                
		});   
	}*/

	function isCityAvailable(city){
		for(var i = 0; i < allCities.length; i++){
			if(city == allCities[i][0]){
				return allCities[i][1];
			}
		}
		return false;
	}

	function setCityStatus(city, status){
		for(var i = 0; i < allCities.length; i++){
			if(city == allCities[i][0]){
				allCities[i][1] = status;
			}
		}
	}

	function removeBoundary(city, ply){
		map.removeOverlay(ply);
		setCityStatus(city, true);
	}

	function getBoundary(city){
		//var ply;
		//map.removeOverlay(ply);   
		//ply="";

		var bdary = new BMap.Boundary();
		bdary.get(city, function(rs){       //获取行政区域  
			var count = rs.boundaries.length; //行政区域的点有多少个
			if (count === 0) {
				alert('未能获取当前输入行政区域');
				return;
			}
			
			var pointArray = [];
			if(isCityAvailable(city)){
				setCityStatus(city, false);
				for (var i = 0; i < count; i++) {
					var ply = new BMap.Polygon(rs.boundaries[i], {strokeWeight: 2, strokeColor: "#ff0000"}); //建立多边形覆盖物
					map.addOverlay(ply);  //添加覆盖物
					// pointArray = pointArray.concat(ply.getPath());	
					ply.addEventListener('mouseout', function (clickEvent) {
						removeBoundary(city, ply);
					});				
				}  				
			}
 
			//map.addOverlay(ply);  //添加覆盖物 
			// map.setViewport(pointArray);    //调整视野               
		});   
	}

	/*
	function pInfoGet() {
		//alert("3");
		toggleLoadingMessage();
		if (this.status == 200) {
			//alert("4");
			//alert(this.responseText);
			var json = JSON.parse(this.responseText);
			// set page list
			setPage(json.review_count, json.company, json.lng, json.lat);
			setList(json);
		} else {
			alert("HTTP error " + this.status + ": " + this.statusText + "\n" + this.responseText);
		}
	}
	*/

	/*
	function setPage(){
				//alert(count);
				var squareArea=document.getElementById("page");

				while (squareArea.hasChildNodes()) {   
					squareArea.removeChild(squareArea.firstChild);
				}

				for(var i=0; i<r_count/10;i++){
					//alert(i);
					var square = document.createElement("div");
					square.className="page_note";

		
					square.innerHTML=i+1;
					square.onmouseover=squareOver;
					square.onclick=squareClick;
					square.onmouseout=squareOut;

					squareArea.appendChild(square);
				}
	}
*/


function setPage(count, company, lng, lat, page){
				//alert(count);
				var squareArea=document.getElementById("page");

				while (squareArea.hasChildNodes()) {   
					squareArea.removeChild(squareArea.firstChild);
				}

				var next=false;
				var pre=false;
				var first = 1;
				var page_num = Math.ceil(count/10);
				var last = page_num;
				if (page_num>5)
				{
					if(parseInt(page)-3>0){
						first=parseInt(page)-2;
						if(parseInt(page)+2>page_num){
							last = page_num;
						}
						else{
							last=parseInt(page)+2;
						}
					}
					else{
						first=1;
						last=5;
					}		
				}

				if (parseInt(page)==parseInt(page_num)){
					next=false;
				}
				else{
					next=true;
				}

				if (parseInt(page)>1){
					pre=true;
				}


				if (pre){
					var square = document.createElement("div");
					square.className="page_note";
					square.data = {	
								count:count,
								company:company,			
								lng:lng,
								lat:lat,
								item:parseInt(page)-1	
						}
					
					square.innerHTML="pre";
					square.onmouseover=squareOver;
					square.onclick=itemClick;
					square.onmouseout=squareOut;

					squareArea.appendChild(square);
				}


				for(var i=first-1; i<last;i++){
					//alert(i);
					var square = document.createElement("div");
					square.className="page_note";
					square.data = {
								count:count,	
								company:company,			
								lng:lng,
								lat:lat	
						}
					
					square.innerHTML=i+1;
					square.onmouseover=squareOver;
					square.onclick=squareClick;
					square.onmouseout=squareOut;

					squareArea.appendChild(square);
				}

				/*
				var pagination = document.createElement("ul");
				pagination.className="pagination";
				for(var i=first-1; i<last;i++){
					//alert(i);
					
					var square = document.createElement("a");
					//square.className="page_note";
					square.data = {
								count:count,	
								company:company,			
								lng:lng,
								lat:lat	
						}
					
					square.innerHTML=i+1;
					square.onmouseover=squareOver;
					square.onclick=squareClick;
					square.onmouseout=squareOut;

					var a = document.createElement("li");

					a.appendChild(square);

					pagination.appendChild(a);
				}
				squareArea.appendChild(pagination);
				*/

				if (next){
					var square = document.createElement("div");
					square.className="page_note";
					square.data = {
								count:count,	
								company:company,			
								lng:lng,
								lat:lat,
								item:parseInt(page)+1	
						}
					
					square.innerHTML="next";
					square.onmouseover=squareOver;
					square.onclick=itemClick;
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
				/*
				toggleLoadingMessage();
				var ajax = new XMLHttpRequest();
				ajax.onload = showList;
				ajax.open("GET", "ajax_com.php?company="+this.data.company+"&lng="+ this.data.lng +"&lat="+ this.data.lat+ "&page="+this.innerHTML, true);
				ajax.send();
				*/
				//setList(this.innerHTML);


				toggleLoadingMessage();

				setPage(this.data.count,this.data.company, this.data.lng, this.data.lat, this.innerHTML);

				var ajax = new XMLHttpRequest();
				ajax.onload = showList;
				//console.log("ajax.php?company="+encodeURI(this.data.company)+"&lng="+ this.data.lng +"&lat="+ this.data.lat+ "&page="+this.data.item);

				ajax.open("GET", "ajax_test_from_Aaron.php?company="+encodeURI(this.data.company)+"&lng="+ this.data.lng +"&lat="+ this.data.lat+ "&page="+this.innerHTML, true);
				ajax.send();
			}


			function itemClick(){
				toggleLoadingMessage();

				setPage(this.data.count,this.data.company, this.data.lng, this.data.lat, this.data.item);

				var ajax = new XMLHttpRequest();
				ajax.onload = showList;
				//alert("ajax.php?company="+encodeURI(this.data.company)+"&lng="+ this.data.lng +"&lat="+ this.data.lat+ "&page="+this.data.item);
				ajax.open("GET", "ajax_test_from_Aaron.php?company="+encodeURI(this.data.company)+"&lng="+ this.data.lng +"&lat="+ this.data.lat+ "&page="+this.data.item, true);
				ajax.send();
			}

			
			function showList(){
				toggleLoadingMessage();
				if (this.status == 200) {
					//alert("showlist at showlist");
					//alert(this.responseText);
					var json = JSON.parse(this.responseText);
					setList_ajax(json);	
				}
				else {
					alert("HTTP error " + this.status + ": " + this.statusText + "\n" + this.responseText);
				}
			}



	function setList(page){
			console.log(all_page);

			var all_page = all.slice((page-1)*10, page*10);

			setBorder(page);

			var list=document.getElementById("list");
			while (list.hasChildNodes()) {   
				list.removeChild(list.firstChild);
			}

			var ratingList = [0, 0, 0, 0, 0, 0, 0];
			for(var i = 0; i < all_ratings.length; i++){
				var currentRating = parseInt(all_ratings[i].rating);
				ratingList[currentRating + 3]++;

			}
			
			for (var i = 0; i < all_page.length; i++) {
				
				
				var container = document.createElement("div");
				container.className="container-fluid";
				container.style.borderBottom = "1px solid silver";
				container.style.paddingTop = "15px";

				var info = document.createElement("div");
				info.className="row no-space-horizontal";

				var p = document.createElement("div");
				//p.className = "info_p";	
				p.className = "col-sm-12";
				p.style.marginTop = "0px";
				//p.style.fontFamily = "Calibri,black,sans-serif";
				//p.style.backgroundColor = "lavender";
				
				var pic=["_3.png","_2.png","_1.png","0.png","1.png","2.png","3.png"];
				var pic_rate= parseInt(all_page[i].review.rating)+3;
				
				//company name: all_page[i].review.company
				//address: all_page[i].review.address
				//city: all_page[i].review.city
				//industry: all_page[i].review.industry
				//product: all_page[i].review.product

				
				var r_info= "<h3 style=\"margin_top:0px;\">Rating: "+all_page[i].review.rating+"</h3>" + "<img src='"+ pic[pic_rate] +"' width='228' height='30' /><br/>"+ "Reviews (Related News, EPA Data, Measurement Data): "+ "<br/>"+all_page[i].review.review +"<br/>"+ "Environment Type: "+all_page[i].review.water+" " +all_page[i].review.air+" "+all_page[i].review.waste+" "+all_page[i].review.land+" "+all_page[i].review.living+" "+all_page[i].review.other+"<br/>"+"Time: "+all_page[i].review.time+"<br/>";
				

				var reviewData = "----- Water - general data----- </br>"+"Water Type: "+all_page[i].water.WaterType+"<br/>"+"Water Color: "+all_page[i].water.WaterColor+"<br/>"+"Turb Score: "+all_page[i].water.TurbScore+"<br/>"+"Odor: "+all_page[i].water.Odor+"<br/>"+"Any Floats: "+all_page[i].water.CheckFloat+"<br/>"+"Floats: "+all_page[i].water.Floats+"<br/>"
				+"</br>----- Water - measurement data----- </br>"+"DO: "+all_page[i].water.DO+"<br/>"+"pH: "+all_page[i].water.pH+"<br/>"+"Turb Params: "+all_page[i].water.TurbParams+"<br/>"+"BOD: "+all_page[i].water.BOD+"<br/>"+"COD: "+all_page[i].water.COD+"<br/>"
				+"TOC: "+all_page[i].water.TOC+"<br/>"+"TS: "+all_page[i].water.TS+"<br/>"+"NH4: "+all_page[i].water.NH4+"<br/>"+"TP: "+all_page[i].water.TP+"<br/>"+"Hg: "+all_page[i].water.Hg+"<br/>"
				+"</br>----- Air - general data----- </br>"+"Visibility: "+all_page[i].air.Visibility+"<br/>"+"Odor: "+all_page[i].air.Odor+"<br/>"+"Any Smoke: "+all_page[i].air.Smoke_Check+"<br/>"+"Smoke Color: "+all_page[i].air.SmokeColor+"<br/>"+"Symptom: "+all_page[i].air.Symptom+"<br/>"+"Description: "+all_page[i].air.symptomDescr+"<br/>"
				+"</br>----- Air - measurement data----- </br>"+"PM2_5: "+all_page[i].air.PM2_5+"<br/>"+"PM10: "+all_page[i].air.PM10+"<br/>"+"O3: "+all_page[i].air.O3+"<br/>"+"SOx: "+all_page[i].air.SOx+"<br/>"+"NOx: "+all_page[i].air.NOx+"<br/>"+"CO: "+all_page[i].air.CO+"<br/>"
				+"</br>----- Waste - general data----- </br>"+"Waste Type: "+all_page[i].solid.WasteType+"<br/>"+"Amount(0 = Small Amount, 10 = Large Amount): "+all_page[i].solid.Amount+"<br/>"+"Odor: "+all_page[i].solid.Odor+"<br/>"
				+"</br>----- Waste - measurement data----- </br>"+"Measurements: "+all_page[i].solid.Measurements+"<br/>"
				;

				//This section for testing collapsible for review data
				var reviewDataArray = new Array(6);
				for(var k = 0; k < 6; k++){
					reviewDataArray[k] = new Array(10);
				}

				reviewDataArray[0][0] = "Water - general data";
				reviewDataArray[1][0] = "Water - measurement data ";
				reviewDataArray[2][0] = "Air - general data";
				reviewDataArray[3][0] = "Air - measurement data";
				reviewDataArray[4][0] = "Waste - general data";
				reviewDataArray[5][0] = "Waste - measurement data";

				for(var k = 0; k < 6; k++){
					for(var j = 1; j < 10; j++){
						reviewDataArray[k][j] = "Hello<br/>";
					}
				}

				r_info += "<div class=\"panel-group\"><div class=\"panel panel-default\"><div class=\"panel-heading\">";
				r_info += "<h4 class=\"panel-title\"><a data-toggle=\"collapse\" href=\"#collapse" + i + "\">Raw Data</a></h4></div>";
				r_info += "<div id=\"collapse" + i + "\" class=\"panel-collapse collapse\"><div class=\"panel-body\">" + reviewData + "</div></div>";
				r_info += "</div></div>";

				
				p.innerHTML = r_info;			
															
				info.appendChild(p);

				var image_div = document.createElement("div");
				//image_div.className="image_div";
				image_div.className="col-sm-12";
				//image_div.style.backgroundColor = "lavenderblush";
				image_div.data = {				
						id: all_page[i].review.id				  	  
				}
		    	image_div.setAttribute("id", "image_div"+all_page[i].review.id);
		    	info.appendChild(image_div);


		    	if (all_page[i].img_count>0){
						var btn_v = document.createElement("BUTTON");				
				    	btn_v.innerHTML="View Image";   			
				    	btn_v.className="btn btn-default";
				    	btn_v.style.display = "inline-block";
				    	btn_v.data = {				
								id: all_page[i].review.id				  	  
						}
				    	btn_v.onclick=btnClick_v;
				    	btn_v.setAttribute("id", "btn_v"+all_page[i].review.id);
				}

				//alert(json.all[i].img_count);
				var img_c_div = document.createElement("p");
				img_c_div.innerHTML="Image Count: "+ all_page[i].img_count;
				//img_c_div.style.display = "inline-block";
				//img_c_div.className="p";

				
	
				var ask=document.createElement("p");
				ask.innerHTML="Was this review …?";
				//ask.className="p";
				


				var btn = document.createElement("BUTTON");
				if(all_page[i].review.help){
    				btn.innerHTML="Helpful : "+ all_page[i].review.help;
    			}
    			else{
    				btn.innerHTML="Helpful";
    			}
    			
    			btn.className="btn btn-default";
    			//alert(json.all[i].review.id);
    			//alert(json.all[i].review.help);
    			btn.data = {				
				  id: all_page[i].review.id,	
				  vote: all_page[i].review.help		  
				}
    			btn.onclick=btnClick;
    			btn.setAttribute("id", "btn"+all_page[i].review.id );
    			//info.appendChild(btn);


    			var report = document.createElement("BUTTON");
    			var r = document.createTextNode("Report as Inappropriate");
    			
    			report.appendChild(r);
    			report.className="btn btn-default";
    			report.data = {				
				  id: all_page[i].review.id		  
				}
    			report.onclick=rClick;
    			//info.appendChild(report);

    			var btn_c = document.createElement("div");
    			//btn_c.className="btn_mc";
    			btn_c.className="row";

    			var btn_l = document.createElement("div");
    			btn_l.className="col-sm-6 col-sm-pull-6";

    			btn_l.appendChild(ask);
    			btn_l.appendChild(btn);
    			btn_l.appendChild(report);


    			var btn_r = document.createElement("div");
    			btn_r.className="col-sm-6 col-sm-push-6";

    			btn_r.appendChild(img_c_div);
    			if (all_page[i].img_count>0){
		    		btn_r.appendChild(btn_v);
		    	}
		    	

		    	btn_c.appendChild(btn_r);
		    	btn_c.appendChild(btn_l);
	

    			var text = document.createElement("p");
    			text.setAttribute("id", all_page[i].review.id );
    			text.className="text-primary";

    			//info.appendChild(text);
    			container.appendChild(info);
    			container.appendChild(btn_c);
    			container.appendChild(text);

				list.appendChild(container);	

			}

			//alert(ratingList);
			var largest = ratingList[0];
			for(var i = 1; i < 7; i++){
				if(largest < ratingList[i]){
					largest = ratingList[i];
				}
			}

			var widthList = [100, 100, 100, 100, 100, 100, 100];

			for(var i = 0; i < 7; i++){
				widthList[i] = ratingList[i]/largest * 100;
				if(widthList[i] == 0){
					widthList[i] = 1;
				}

				var hashTag = i - 3;
				if(hashTag > 0){
					hashTag = "#+" + hashTag;
				}
				else{
					hashTag = "#" + hashTag;
				}

				var bar = document.getElementById(hashTag);
				bar.style.width = widthList[i] + "%";
			}
	
	}




function setBorder(page){
	var b_color = document.querySelectorAll("#page div");
			
			for (var c=0; c<b_color.length; c++){	
					b_color[c].style.border="";
			}
			//for (var c=0; c<json.review_count/2; c++){
			for (var c=0; c<b_color.length; c++){
						//alert("c"+c);
						//alert("innerHTML"+b_color[c].innerHTML);
						//alert("page"+json.page);
					if (b_color[c].innerHTML==page)
					{
						b_color[c].style.border="1px solid blue";
					}
			}
}


function setList_ajax(json){
			//alert("ajax is running");


			setBorder(json.page);
			//alert(json.all.length);

			var list=document.getElementById("list");
			while (list.hasChildNodes()) {   
				list.removeChild(list.firstChild);
			}

			
			for (var i = 0; i < json.all.length; i++) {
				
				var container = document.createElement("div");
				container.className="container-fluid";
				container.style.borderBottom = "1px solid silver";
				container.style.paddingTop = "15px";

				var info = document.createElement("div");
				info.className="row";
				var p = document.createElement("div");
				p.className = "col-sm-12";	
				
				var pic=["_3.png","_2.png","_1.png","0.png","1.png","2.png","3.png"];
				var pic_rate= parseInt(json.all[i].review.rating)+3;
				
				//var r_info= "Company Name: " + json.all[i].review.company +"<br/>" + "Company Address: "+ json.all[i].review.address +"<br/>" + "City: "+json.all[i].review.city+"<br/>" + "Company Industry: "+json.all[i].review.industry+"<br/>" + "Company Product: "+json.all[i].review.product+"<br/>" + "Rating: "+json.all[i].review.rating+"<br/>" + "<img src='"+ pic[pic_rate] +"' width='228' height='30' /><br/>"+ "Reviews: "+json.all[i].review.review +"<br/>"+ "Environment Type: "+json.all[i].review.water+" " +json.all[i].review.air+" "+json.all[i].review.waste+" "+json.all[i].review.land+" "+json.all[i].review.living+" "+json.all[i].review.other+"<br/>"+"Related News, Videos, or links: "+json.all[i].review.news+"<br/>"+"EPA Data: "+json.all[i].review.epa+"<br/>"+"Measurement Data: "+json.all[i].review.measure+"<br/>"+"Time: "+json.all[i].review.time+"<br/>";
				//var r_info= "Company Name: " + all_page[i].review.company +"<br/>" + "Company Address: "+ all_page[i].review.address +"<br/>" + "City: "+all_page[i].review.city+"<br/>" + "Company Industry:" +all_page[i].review.industry+ "<br/>" + "Company Product:" +all_page[i].review.product+ "<br/>"+"Rating: "+all_page[i].review.rating+"<br/>" + "<img src='"+ pic[pic_rate] +"' width='228' height='30' /><br/>"+ "Reviews: "+all_page[i].review.review +"<br/>"+ "Environment Type: "+all_page[i].review.water+" " +all_page[i].review.air+" "+all_page[i].review.waste+" "+all_page[i].review.land+" "+all_page[i].review.living+" "+all_page[i].review.other+"<br/>"+"Related News, Videos, or links: "+all_page[i].review.news+"<br/>"+ "EPA Data:" +all_page[i].review.epa+ "<br/>"+ "Measurement Data:" +all_page[i].review.measure+ "<br/>"+"Time: "+all_page[i].review.time+"<br/>";

				var r_info= "<h3 style=\"margin_top:0px;\">Rating: "+json.all[i].review.rating+"</h3>" + "<img src='"+ pic[pic_rate] +"' width='228' height='30' /><br/>"+ "Reviews (Related News, EPA Data, Measurement Data): "+ "<br/>"+json.all[i].review.review +"<br/>"+ "Environment Type: "+json.all[i].review.water+" " +json.all[i].review.air+" "+json.all[i].review.waste+" "+json.all[i].review.land+" "+json.all[i].review.living+" "+json.all[i].review.other+"<br/>"+"Time: "+json.all[i].review.time+"<br/>";
				//console.log(json.all[i]);

				var reviewData = "----- Water - general data----- </br>"+"Water Type: "+json.all[i].water.WaterType+"<br/>"+"Water Color: "+json.all[i].water.WaterColor+"<br/>"+"Turb Score: "+json.all[i].water.TurbScore+"<br/>"+"Odor: "+json.all[i].water.Odor+"<br/>"+"Any Floats: "+json.all[i].water.CheckFloat+"<br/>"+"Floats: "+json.all[i].water.Floats+"<br/>"
				+"</br>----- Water - measurement data----- </br>"+"DO: "+json.all[i].water.DO+"<br/>"+"pH: "+json.all[i].water.pH+"<br/>"+"Turb Params: "+json.all[i].water.TurbParams+"<br/>"+"BOD: "+json.all[i].water.BOD+"<br/>"+"COD: "+json.all[i].water.COD+"<br/>"
				+"TOC: "+json.all[i].water.TOC+"<br/>"+"TS: "+json.all[i].water.TS+"<br/>"+"NH4: "+json.all[i].water.NH4+"<br/>"+"TP: "+json.all[i].water.TP+"<br/>"+"Hg: "+json.all[i].water.Hg+"<br/>"
				+"</br>----- Air - general data----- </br>"+"Visibility: "+json.all[i].air.Visibility+"<br/>"+"Odor: "+json.all[i].air.Odor+"<br/>"+"Any Smoke: "+json.all[i].air.Smoke_Check+"<br/>"+"Smoke Color: "+json.all[i].air.SmokeColor+"<br/>"+"Symptom: "+json.all[i].air.Symptom+"<br/>"+"Description: "+json.all[i].air.symptomDescr+"<br/>"
				+"</br>----- Air - measurement data----- </br>"+"PM2_5: "+json.all[i].air.PM2_5+"<br/>"+"PM10: "+json.all[i].air.PM10+"<br/>"+"O3: "+json.all[i].air.O3+"<br/>"+"SOx: "+json.all[i].air.SOx+"<br/>"+"NOx: "+json.all[i].air.NOx+"<br/>"+"CO: "+json.all[i].air.CO+"<br/>"
				+"</br>----- Waste - general data----- </br>"+"Waste Type: "+json.all[i].solid.WasteType+"<br/>"+"Amount(0 = Small Amount, 10 = Large Amount): "+json.all[i].solid.Amount+"<br/>"+"Odor: "+json.all[i].solid.Odor+"<br/>"
				+"</br>----- Waste - measurement data----- </br>"+"Measurements: "+json.all[i].solid.Measurements+"<br/>"
				;
				//alert("working");
				//This section for testing collapsible for review data
				var reviewDataArray = new Array(6);
				for(var k = 0; k < 6; k++){
					reviewDataArray[k] = new Array(10);
				}

				reviewDataArray[0][0] = "Water - general data";
				reviewDataArray[1][0] = "Water - measurement data ";
				reviewDataArray[2][0] = "Air - general data";
				reviewDataArray[3][0] = "Air - measurement data";
				reviewDataArray[4][0] = "Waste - general data";
				reviewDataArray[5][0] = "Waste - measurement data";

				for(var k = 0; k < 6; k++){
					for(var j = 1; j < 10; j++){
						reviewDataArray[k][j] = "Hello<br/>";
					}
				}

				r_info += "<div class=\"panel-group\"><div class=\"panel panel-default\"><div class=\"panel-heading\">";
				r_info += "<h4 class=\"panel-title\"><a data-toggle=\"collapse\" href=\"#collapse" + i + "\">Raw Data</a></h4></div>";
				r_info += "<div id=\"collapse" + i + "\" class=\"panel-collapse collapse\"><div class=\"panel-body\">" + reviewData + "</div></div>";
				r_info += "</div></div>";

				p.innerHTML = r_info;			
															
				info.appendChild(p);


				var image_div = document.createElement("div");
				image_div.className="col-sm-12";
				image_div.data = {				
						id: json.all[i].review.id				  	  
				}
		    	image_div.setAttribute("id", "image_div"+json.all[i].review.id);
		    	info.appendChild(image_div);


		    	if (json.all[i].img_count>0){
						var btn_v = document.createElement("BUTTON");				
				    	btn_v.innerHTML="View Image";   			
				    	btn_v.className="btn btn-default";
				    	btn_v.style.display = "inline-block";
				    	btn_v.data = {				
								id: json.all[i].review.id				  	  
						}
				    	btn_v.onclick=btnClick_v;
				    	btn_v.setAttribute("id", "btn_v"+json.all[i].review.id);
				}

				//alert(json.all[i].img_count);
				var img_c_div = document.createElement("p");
				img_c_div.innerHTML="Image Count: "+ json.all[i].img_count;
				//img_c_div.className="img_c";

				/*			
					for (var j = 0; j < json.all[i].all_image.length; j++) {											
						var img = document.createElement("img");						
						img.setAttribute("src", "data:image/jpg;base64,"+json.all[i].all_image[j].image);						
						img.className="img";
						img.onmouseover=imgOver;
											
						image_div.appendChild(img);												
					}
				*/				
				
	
				var ask=document.createElement("p");
				ask.innerHTML="Was this review …?";
				//ask.className="ask";
				//info.appendChild(ask);


				var btn = document.createElement("BUTTON");
				if(json.all[i].review.help){
    				btn.innerHTML="Helpful : "+ json.all[i].review.help;
    			}
    			else{
    				btn.innerHTML="Helpful";
    			}
    			
    			btn.className="btn btn-default";
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
    			report.className="btn btn-default";
    			report.data = {				
				  id: json.all[i].review.id		  
				}
    			report.onclick=rClick;
    			//info.appendChild(report);

    			var btn_c = document.createElement("div");
    			btn_c.className="row";

    			var btn_l = document.createElement("div");
    			btn_l.className="col-sm-6 col-sm-pull-6";

    			btn_l.appendChild(ask);
    			btn_l.appendChild(btn);
    			btn_l.appendChild(report);


    			var btn_r = document.createElement("div");
    			btn_r.className="col-sm-6 col-sm-push-6";

    			btn_r.appendChild(img_c_div);

    			if (json.all[i].img_count>0){
		    		btn_r.appendChild(btn_v);
		    	}
		    	

		    	btn_c.appendChild(btn_r);
		    	btn_c.appendChild(btn_l);
	

    			var text = document.createElement("p");
    			text.setAttribute("id", json.all[i].review.id );
    			text.className="text-primary";

    			//info.appendChild(text);
    			container.appendChild(info);
    			container.appendChild(btn_c);
    			container.appendChild(text);

				list.appendChild(container);				
			}
			$('#infoDiv').scrollTop(0);
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

								var carousel = document.createElement('div');
								carousel.id = "myCarousel";
								carousel.setAttribute("class", "carousel slide");
								carousel.setAttribute("data-interval", "false");
								
								var carouselHTML = "<ol class='carousel-indicators'>";
								for(var j = 0; j < json.all_image.length; j++){
									if(j === 0){
										carouselHTML += "<li data-target='#myCarousel' data-slide-to='" + j + "' class='active'></li>";										
									}
									else{
										carouselHTML += "<li data-target='#myCarousel' data-slide-to='" + j + "'></li>";
									}

								}	
								carouselHTML += "</ol>";
								carouselHTML += "<div class='carousel-inner'>";

								//NEW CODE
								for(var j = 0; j < json.all_image.length; j++){
									var imgSourceString = "";
									if(j === 0){

										carouselHTML += "<div class='item active'>";
										if(json.inupfile==0){
											carouselHTML += "<img src='data:image/jpg;base64," + json.all.image[j] + "'>";
										}
										else{		
											carouselHTML += "<img src='" + json.all_image[j]+"'>";
										}
										carouselHTML += "</div>";
									}
									else{
										carouselHTML += "<div class='item'>";
										if(json.inupfile==0){
											carouselHTML += "<img src='data:image/jpg;base64," + json.all.image[j] + "'>";
										}
										else{		
											carouselHTML += "<img src='" + json.all_image[j]+"'>";
										}
										carouselHTML += "</div>";
									}

								}
								carouselHTML += '</div><a class="left carousel-control" href="#myCarousel" data-slide="prev"><span class="glyphicon glyphicon-chevron-left"></span><span class="sr-only">Previous</span></a><a class="right carousel-control" href="#myCarousel" data-slide="next"><span class="glyphicon glyphicon-chevron-right"></span><span class="sr-only">Next</span></a>';


								carousel.innerHTML = carouselHTML;
								/*
								//OLD CODE
								for (var j = 0; j < json.all_image.length; j++) {	
									alert("hello");																			
									var img = document.createElement("img");						
									//img.setAttribute("src", "data:image/jpg;base64,"+json.all_image[j].image);
									if(json.inupfile==0){
											img.setAttribute("src", "data:image/jpg;base64,"+json.all_image[j]);
									}
									else{		
											img.setAttribute("src", json.all_image[j]);		
									}			
									img.className="img";
									img.onmouseover=imgOver;	
									//img.onmouseout=imgOut;					
									image_div.appendChild(img);												
								}	*/
								image_div.append(carousel);
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

				var reason = prompt("Please enter the report reason. Only the report with the reason will be accepted.", "");
    
			    if (reason) {
			        var id=this.data.id;
				
			        var params = new FormData();
					params.append("id", id);
					params.append("reason", reason);				
					
					var ajax = new XMLHttpRequest();
					ajax.onload = getr;
					ajax.open("POST", "report.php", true);
					ajax.send(params);
			    }

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
	/*
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
		//alert("hello");
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
	});*/

	
	
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
	/*
  var navigationControl = new BMap.NavigationControl({
    // 靠左上角位置
    anchor: BMAP_ANCHOR_TOP_LEFT,
    // LARGE类型
    type: BMAP_NAVIGATION_CONTROL_LARGE,
    // 启用显示定位
    enableGeolocation: true
  });
  map.addControl(navigationControl);
  */

  // 添加定位控件
  //map.addControl(new BMap.NavigationControl());// Map panning and zooming controls

  //map.disableScrollWheelZoom();
  //禁用滚轮放大缩小


  // 百度地图API功能
	
	/*var ac_2 = new BMap.Autocomplete(    //建立一个自动完成的对象
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


	// 定义一个控件类,即function
	function ZoomControl(){
	  // 默认停靠位置和偏移量
	  this.defaultAnchor = BMAP_ANCHOR_TOP_RIGHT;
	  this.defaultOffset = new BMap.Size(10, 10);
	}*/

	// 通过JavaScript的prototype属性继承于BMap.Control
	//ZoomControl.prototype = new BMap.Control();

	// 自定义控件必须实现自己的initialize方法,并且将控件的DOM元素返回
	// 在本方法中创建个div元素作为控件的容器,并将其添加到地图容器中
	/*
	ZoomControl.prototype.initialize = function(map){
	  // 创建一个DOM元素
	  
	  var div = document.createElement("div");
	  // 添加文字说明
	  div.appendChild(document.createTextNode("More Map"));
	  // 设置样式
	  div.style.cursor = "pointer";
	  div.style.border = "1px solid #337ab7";
	  div.style.backgroundColor = "white";
	  div.style.padding="3px";
	  div.style.borderRadius="3px";
	  div.style.color="#337ab7";
	  //div.style.fontWeight= "bold";
	  // 绑定事件,点击一次放大两级
	  div.onclick = function(e){
		//map.setZoom(map.getZoom() + 2);
		var map_height=document.getElementById("newmap");
		
		if (map_height.classList.contains('map_s')) {

		    map_height.style.height = "450px";
		    map_height.className="map_b";

			while (div.firstChild) {
			    div.removeChild(div.firstChild);
			}
			div.appendChild(document.createTextNode("Less Map"));
			

		}else if (map_height.classList.contains('map_b')){

		    map_height.style.height = "250px";
		    map_height.className="map_s";

		    while (div.firstChild) {
			    div.removeChild(div.firstChild);
			}
			div.appendChild(document.createTextNode("More Map"));
			
		}
	  }
	  // 添加DOM元素到地图中
	  map.getContainer().appendChild(div);
	  // 将DOM元素返回
	  return div;
	}*/


	// 创建控件
	//var myZoomCtrl = new ZoomControl();
	// 添加到地图当中
	//map.addControl(myZoomCtrl);

</script>