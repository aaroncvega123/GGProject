<?php
			include("db.php");
			header("Content-type: application/json");

			$id=htmlspecialchars($_POST["id"]);

			try{
					
				  //$con=new PDO("mysql:dbname=myGreenGuide;host=us-cdbr-azure-west-b.cleardb.com","b4f6ad8be99b99","0ba0581c");
				  //$con->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);				  
				  
				 
						$p_image= array();


						$inupfile=0;


						$upimg=$db->prepare("select id from image where review_id=:id ");
			            $upimg->bindParam(':id', $id);
			            $upimg->execute();

			            if($upimg){

			             		foreach($upimg as $img_id)
						 		{
									  $img_name="uploads/".$img_id[0].".*";
									  //echo $img_name;
									  foreach(glob("$img_name") as $image){
									  		//$image=file_get_contents($image);
											//$image=base64_encode($image);
										    $p_image[]=$image;
										    $inupfile=1;
									  }				  
						 		}
						}	


						/*
						if($inupfile==0){
							//$images=$con->query("select * from image where review_id= '$id' ");
							$images=$db->prepare("select image from image where review_id=:id ");
				            $images->bindParam(':id', $id);
				            $images->execute();
							
							if($images){ 
								  foreach($images as $image)	
								  {
									  $p_image[]=$image[0];
									 
								  }
							}
						}
						//$all[]=array("review"=>$review,"all_image"=>$p_image);	
						*/
				  
				
				  
				  $json = array(
				  		  	
				  	"all_image"=>$p_image,
				  	"id"=>$id,
				  	"inupfile"=>$inupfile,

				  );
				  
				  
				  print json_encode($json);
				  				  
			  }
			  catch(Exception $e){
				  //die(print_r($e));
			  		die("Sorry. Error occurred. Please try again.");
			  }

?>