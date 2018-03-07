<?php  
          include("db.php");
          header("Content-type: application/json");
		  $company=htmlspecialchars($_GET["company"]);
		  $lng=htmlspecialchars($_GET["lng"]);
          $lat=htmlspecialchars($_GET["lat"]);
		  $page=htmlspecialchars($_GET["page"]);

		  $offset=($page-1)*10;
          
          try{
             
			  
			  //$con=new PDO("mysql:dbname=myGreenGuide;host=us-cdbr-azure-west-b.cleardb.com","b4f6ad8be99b99","0ba0581c");
			  //$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
			  
			  //$reviews=$con->query("select * from review where company='$company' and lng='$lng' and lat='$lat' and status=1 order by id DESC LIMIT 10 OFFSET $offset");
			  
			  $reviews = $db->prepare("select * from review where company= :company and lng=:lng and lat=:lat and status=1 order by id DESC LIMIT 10 OFFSET $offset ");
			  $reviews->bindParam(':company', $company);
			  $reviews->bindParam(':lng', $lng);
			  $reviews->bindParam(':lat', $lat);
			  $reviews->execute();


			  //$reviews=$con->query("select * from review where company='$company' and lng='$lng' and lat='$lat' order by id DESC");
			  //$r_count=$con->query("select COUNT(id) from review where company='$company' and lng='$lng' and lat='$lat' and status=1 ");

			  $r_count = $db->prepare("select COUNT(id) from review where company= :company and lng=:lng and lat=:lat and status=1 ");
			  $r_count->bindParam(':company', $company);
			  $r_count->bindParam(':lng', $lng);
			  $r_count->bindParam(':lat', $lat);
			  $r_count->execute();
			  //$reviews_c=$con->query("select COUNT(id) from review where lng='$lng' and lat='$lat' ");
			  
			  $all= array();
			  if(reviews){
					foreach($reviews as $review)
					{					 
						
						//$p_image= array();
						//$images=$con->query("select id from image where review_id=$review[0] ");
						$images_c=$db->query("select COUNT(id) from image where review_id=$review[0] ");

						$i_water;
						$waters=$db->query("select * from water_issue where review_id=$review[0] ");
						if($waters){	
							  foreach($waters as $water)	
							  {
								  $i_water=$water;
								 
							  }
						}
						//print_r("pass3");
						$i_air;
						$airs=$db->query("select * from air_issue where review_id=$review[0] ");
						if($airs){	
							  foreach($airs as $air)	
							  {
								  $i_air=$air;
								 
							  }
						}
						//print_r("pass4");
						$i_solid;
						$solids=$db->query("select * from solid_issue where review_id=$review[0] ");
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
						$all[]=array("review"=>$review, "img_count" => $images_c -> fetchColumn(), "water"=> $i_water, "air"=>$i_air, "solid"=> $i_solid);
						//print_r($all);			  
					}
			  
			  $json = array(
			  		"company"=>$company,			  	    
				    "lng"=> $lng,
				    "lat" => $lat,
				    "page" => $page,
				  	"review_count" => $r_count -> fetchColumn(),	  	
				  	//"all" => array_slice($all, ($page-1)*10, 10),
				  	"all" => $all,
			  );
			  
			  
			  print json_encode($json);
			  }
					 
			
          }
          catch(Exception $e){
              die(print_r($e));
          	  //die("Sorry. Error occurred. Please try again.");
          }
                   
  ?>
