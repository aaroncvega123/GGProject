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
				  
?>		  


<!DOCTYPE html>
<html>
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
      
      			<?php
					include("header_b.php");
				?>

				 <div class="container-fluid"> 
                  		  <form class="form-inline" role="form">
              							  <div class="form-group">
              							    <label for="go_location">Please search the city or company in the dropdown menu and click it. System'll bring the city or company location on the map:</label>
              							    <input type="text" class="form-control input-sm" id="suggestId_2" size="50" placeholder="Company Name or (Location + Company Name)">
              							  </div>
      							          <div id="searchResultPanel_2" style="border:1px solid #C0C0C0;width:150px;height:auto; display:none;"></div>
						            </form>

          						  <form action="map_go.php" class="form-inline" role="form">
              							  <div class="form-group">
                  							    <label for="industry">Please key in the industry or product you'd like to search on the map and click "Search for Reviews".</label>
                  							    <input name="industry" type="text" class="form-control input-sm" size="30" placeholder="Industry">
              							  </div>
              							  <div class="form-group">
                  							  	<label class="sr-only" for="product">product</label>
                  							    <input name="product" type="text" class="form-control input-sm" size="30" placeholder="Product">
              							  </div>
          							  
          							      <button type="submit" class="btn btn-default btn-sm">Search for Reviews</button>
          						  </form>	  
		         </div>    
      			
                  <div id="newmap" class="map_s"></div>
                  
                  <div class="container-fluid"><h3 id="count_c"></h3></div>
                  <div id="loading" style="display: none">
						<img src="loading.gif" />
						Loading ...
				  </div>
				  <div id="list"></div>
				  <div class="container-fluid"><div id="page"></div></div>
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
                  <footer class="text-center">
					  <br><br><br>
					  <p>Share your feelings about the environment to the world! <a href="http://www.lovegreenguide.com" data-toggle="tooltip" title="LoveGreenGuide">- LoveGreenGuide </a></p>
					  <p>All pages and content &copy; Copyright Green Guide Inc.</p> 
				  </footer>
			
      
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

	//alert(all[0].water.Water_Issue_ID);
	//alert(all[0].review.company);
	
	//var all_page1 = all.slice(all.length-10-1, all.length);
	var rate;
	for(var c=0;c<data.length;c++){
		if(data[c].company==all[0].review.company){
			rate = data[c].avg_r;
		}
	}

	var count_c=document.getElementById("count_c");
	count_c.innerHTML=r_count+" Reviews - " + all[0].review.company + "  - Rating: "+ parseFloat(rate).toFixed(2);
	//count.className="count";
	count_c.className="text-warning";
	count_c.style.borderBottom="1px solid silver";
	count_c.style.marginTop = "3px";

	alert("It works");
	
	document.getElementById("map").className="active";
	
	var map = new BMap.Map("newmap");

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

					/*
					toggleLoadingMessage();
					var ajax = new XMLHttpRequest();
					ajax.onload = pInfoGet;
					ajax.open("GET", "ajax_com.php?company="+data[i].company+"&lng="+ data[i].lng +"&lat="+ data[i].lat+"&page="+"1", true);
					ajax.send();
					*/
				}
				setPage(r_count, all[0].review.company, lng, lat, 1);
				setList(1);
				
		} else {
					//map.centerAndZoom(new BMap.Point(116.417854,39.921988), 15);
					map.centerAndZoom(new BMap.Point(114, 32), 5);
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

	function openInfo(content,e){
		
		var p = e.target;
		var point = new BMap.Point(p.getPosition().lng, p.getPosition().lat);
		var infoWindow = new BMap.InfoWindow(content,opts);  // 创建信息窗口对象 
		map.openInfoWindow(infoWindow,point); //开启信息窗口
		//alert(content);
	}
	
	function pointInfo(e){
		
		var pi = e.target;
		
			var mydiv = document.getElementById('getMap_point').innerHTML = '<form id="map_point"  action="map_point.php"><input name="lng" type="hidden" value="'+ pi.getPosition().lng +'" /><input name="lat" type="hidden" value="'+ pi.getPosition().lat +'" /></form>';
	        var f=document.getElementById('map_point');
	        if(f){
	        f.submit();
	    	}

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
				ajax.open("GET", "ajax.php?company="+encodeURI(this.data.company)+"&lng="+ this.data.lng +"&lat="+ this.data.lat+ "&page="+this.innerHTML, true);
				ajax.send();
			}


			function itemClick(){
				toggleLoadingMessage();

				setPage(this.data.count,this.data.company, this.data.lng, this.data.lat, this.data.item);

				var ajax = new XMLHttpRequest();
				ajax.onload = showList;
				ajax.open("GET", "ajax.php?company="+encodeURI(this.data.company)+"&lng="+ this.data.lng +"&lat="+ this.data.lat+ "&page="+this.data.item, true);
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
			var all_page = all.slice((page-1)*10, page*10);

			setBorder(page);

			var list=document.getElementById("list");
			while (list.hasChildNodes()) {   
				list.removeChild(list.firstChild);
			}

			
			for (var i = 0; i < all_page.length; i++) {
				
				
				var container = document.createElement("div");
				container.className="container-fluid";
				container.style.borderBottom = "1px solid silver";

				var info = document.createElement("div");
				info.className="row";

				var p = document.createElement("div");
				//p.className = "info_p";	
				p.className = "col-sm-6";
				//p.style.backgroundColor = "lavender";
				
				var pic=["_3.png","_2.png","_1.png","0.png","1.png","2.png","3.png"];
				var pic_rate= parseInt(all_page[i].review.rating)+3;
				
				var r_info= "Company Name: " + all_page[i].review.company +"<br/>" + "Company Address: "+ all_page[i].review.address +"<br/>" + "City: "+all_page[i].review.city+"<br/>" + "Company Industry: " +all_page[i].review.industry+ "<br/>" + "Company Product: " +all_page[i].review.product+ "<br/>"+"Rating: "+all_page[i].review.rating+"<br/>" + "<img src='"+ pic[pic_rate] +"' width='228' height='30' /><br/>"+ "Reviews (Related News, EPA Data, Measurement Data): "+ "<br/>"+all_page[i].review.review +"<br/>"+ "Environment Type: "+all_page[i].review.water+" " +all_page[i].review.air+" "+all_page[i].review.waste+" "+all_page[i].review.land+" "+all_page[i].review.living+" "+all_page[i].review.other+"<br/>"+"Time: "+all_page[i].review.time+"<br/>"
				+"----- Water - general data----- "+"<br/>"+"Water Type: "+all_page[i].water.WaterType+"<br/>"+"Water Color: "+all_page[i].water.WaterColor+"<br/>"+"Turb Score: "+all_page[i].water.TurbScore+"<br/>"+"Odor: "+all_page[i].water.Odor+"<br/>"+"Any Floats: "+all_page[i].water.CheckFloat+"<br/>"+"Floats: "+all_page[i].water.Floats+"<br/>"
				+"----- Water - measurement data----- "+"<br/>"+"DO: "+all_page[i].water.DO+"<br/>"+"pH: "+all_page[i].water.pH+"<br/>"+"Turb Params: "+all_page[i].water.TurbParams+"<br/>"+"BOD: "+all_page[i].water.BOD+"<br/>"+"COD: "+all_page[i].water.COD+"<br/>"
				+"TOC: "+all_page[i].water.TOC+"<br/>"+"TS: "+all_page[i].water.TS+"<br/>"+"NH4: "+all_page[i].water.NH4+"<br/>"+"TP: "+all_page[i].water.TP+"<br/>"+"Hg: "+all_page[i].water.Hg+"<br/>"
				+"----- Air - general data----- "+"<br/>"+"Visibility: "+all_page[i].air.Visibility+"<br/>"+"Odor: "+all_page[i].air.Odor+"<br/>"+"Any Smoke: "+all_page[i].air.Smoke_Check+"<br/>"+"Smoke Color: "+all_page[i].air.SmokeColor+"<br/>"+"Symptom: "+all_page[i].air.Symptom+"<br/>"+"Description: "+all_page[i].air.symptomDescr+"<br/>"
				+"----- Air - measurement data----- "+"<br/>"+"PM2_5: "+all_page[i].air.PM2_5+"<br/>"+"PM10: "+all_page[i].air.PM10+"<br/>"+"O3: "+all_page[i].air.O3+"<br/>"+"SOx: "+all_page[i].air.SOx+"<br/>"+"NOx: "+all_page[i].air.NOx+"<br/>"+"CO: "+all_page[i].air.CO+"<br/>"
				+"----- Waste - general data----- "+"<br/>"+"Waste Type: "+all_page[i].solid.WasteType+"<br/>"+"Amount(0 = Small Amount, 10 = Large Amount): "+all_page[i].solid.Amount+"<br/>"+"Odor: "+all_page[i].solid.Odor+"<br/>"
				+"----- Waste - measurement data----- "+"<br/>"+"Measurements: "+all_page[i].solid.Measurements+"<br/>"
				;
				
				p.innerHTML = r_info;			
															
				info.appendChild(p);


				var image_div = document.createElement("div");
				//image_div.className="image_div";
				image_div.className="col-sm-6";
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

			setBorder(json.page);

			var list=document.getElementById("list");
			while (list.hasChildNodes()) {   
				list.removeChild(list.firstChild);
			}

			
			for (var i = 0; i < json.all.length; i++) {
				
				var container = document.createElement("div");
				container.className="container-fluid";
				container.style.borderBottom = "1px solid silver";

				var info = document.createElement("div");
				info.className="row";
				var p = document.createElement("div");
				p.className = "col-sm-6";	
				
				var pic=["_3.png","_2.png","_1.png","0.png","1.png","2.png","3.png"];
				var pic_rate= parseInt(json.all[i].review.rating)+3;
				
				var r_info= "Company Name: " + json.all[i].review.company +"<br/>" + "Company Address: "+ json.all[i].review.address +"<br/>" + "City: "+json.all[i].review.city+"<br/>" + "Company Industry: "+json.all[i].review.industry+"<br/>" + "Company Product: "+json.all[i].review.product+"<br/>" + "Rating: "+json.all[i].review.rating+"<br/>" + "<img src='"+ pic[pic_rate] +"' width='228' height='30' /><br/>"+ "Reviews: "+json.all[i].review.review +"<br/>"+ "Environment Type: "+json.all[i].review.water+" " +json.all[i].review.air+" "+json.all[i].review.waste+" "+json.all[i].review.land+" "+json.all[i].review.living+" "+json.all[i].review.other+"<br/>"+"Related News, Videos, or links: "+json.all[i].review.news+"<br/>"+"EPA Data: "+json.all[i].review.epa+"<br/>"+"Measurement Data: "+json.all[i].review.measure+"<br/>"+"Time: "+json.all[i].review.time+"<br/>";
				//var r_info= "Company Name: " + all_page[i].review.company +"<br/>" + "Company Address: "+ all_page[i].review.address +"<br/>" + "City: "+all_page[i].review.city+"<br/>" + "Company Industry:" +all_page[i].review.industry+ "<br/>" + "Company Product:" +all_page[i].review.product+ "<br/>"+"Rating: "+all_page[i].review.rating+"<br/>" + "<img src='"+ pic[pic_rate] +"' width='228' height='30' /><br/>"+ "Reviews: "+all_page[i].review.review +"<br/>"+ "Environment Type: "+all_page[i].review.water+" " +all_page[i].review.air+" "+all_page[i].review.waste+" "+all_page[i].review.land+" "+all_page[i].review.living+" "+all_page[i].review.other+"<br/>"+"Related News, Videos, or links: "+all_page[i].review.news+"<br/>"+ "EPA Data:" +all_page[i].review.epa+ "<br/>"+ "Measurement Data:" +all_page[i].review.measure+ "<br/>"+"Time: "+all_page[i].review.time+"<br/>";

				p.innerHTML = r_info;			
															
				info.appendChild(p);


				var image_div = document.createElement("div");
				image_div.className="col-sm-6";
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
	}

	// 通过JavaScript的prototype属性继承于BMap.Control
	ZoomControl.prototype = new BMap.Control();

	// 自定义控件必须实现自己的initialize方法,并且将控件的DOM元素返回
	// 在本方法中创建个div元素作为控件的容器,并将其添加到地图容器中
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
	}
	// 创建控件
	var myZoomCtrl = new ZoomControl();
	// 添加到地图当中
	map.addControl(myZoomCtrl);



</script>
