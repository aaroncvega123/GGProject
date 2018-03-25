<nav class="navbar navbar-default" style="background-color: white; border-top-left-radius: 0; border-top-right-radius: 0; margin-bottom:0px; border: none;">
  <div class="container-fluid">
    <div class="navbar-header">
      <button type="button" class="navbar-toggle" data-toggle="collapse" data-target="#myNavbar" >
        <span class="icon-bar"></span>
        <span class="icon-bar"></span>
        <span class="icon-bar"></span> 
      </button>
      <!--<a class="navbar-brand" href="index.php" style="color:lightgreen; font-size:22px; padding-top:2px;"><span><img src="green-pin_2.png" alt="green" width="25" height="30"/></span> LoveGreenGuide</a>-->


    </div>
    <div class="collapse navbar-collapse" id="myNavbar" >
      <ul class="nav navbar-nav">
        <a class="navbar-brand" href="index.php" style="color:green; font-size:25px; padding-top:10px;"><span><img src="green-pin.png" alt="green" width="28" height="35"/></span> LoveGreenGuide</a>

        <!--<li id="profile"><a href="profile.php">My Reviews</a></li> -->
        <!--<li id="review"><a href="WriteReview.php">Write a Community Review</a></li> --> 
        <!--<li id="map"><a href="map.php">Explore Reviews on a Map</a></li> -->
      </ul>
      <ul class="nav navbar-nav navbar-right">
        <li><a class="navbar-brand" href="index.php" ><span class="glyphicon glyphicon-home"></span></a></li>
        <li id="User_Guide" class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">User Guide
            <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li id="map"><a href="map.php">Explore Reviews on a Map</a></li>
                <li id="measurementGuide"><a href="about.php">Pollution Measurement Guide</a></li>
                <li id="impact"><a href="join.php">Pollution Impact</a></li>
                <li id="success"><a href="contact.php">Successful cases</a></li>
                <li id="companyReviewGuide"><a href="contact.php">Company Review guide</a></li>
            </ul>
        </li>
        <li id="about" class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#">About Us
            <span class="caret"></span></a>
            <ul class="dropdown-menu">
                <li id="about_2"><a href="about.php">About Us</a></li>
                <li id="join"><a href="join.php">Join Us</a></li>
                <li id="contact"><a href="contact.php">Contact Us</a></li>
                <li id="why"><a href="contact.php">Why use LoveGreenGuide</a></li>
            </ul>
        </li>
        <li id="about" class="dropdown">
            <a class="dropdown-toggle" data-toggle="dropdown" href="#"><span class="glyphicon glyphicon-user"></span>
            <span class="caret"></span></a>
            <ul class="dropdown-menu">
              <li><a href="signup.php">Sign Up</a></li>
              <li><a href="user.php">Log In</a></li>
              <li><a href="user.php">My Reviews</a></li>
            </ul>
        </li>
        <li><a href="../ch/index.php">中文</a></li>
      </ul>
    </div>
  </div>
</nav>




<nav class="navbar navbar-default" style="margin-bottom:0px;">
          <div class="container-fluid">


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
                <button type="submit" class="btn btn-default btn-sm">Search for Reviews</button>
              </form>
        
            </div>

            <div id="searchResultPanel" style="border:1px solid #C0C0C0;width:150px;height:auto; display:none;"></div>
            <div id="getCompany"></div>
            
          </div>
</nav>







                 