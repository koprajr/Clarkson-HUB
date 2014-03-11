<html lang="en" class="no-js">
	<?php 
		function bgPhotoList(){
			//Open images directory
			$dir = opendir("../HUB/BGimages");
			
			//List files in images directoryb
			while (($file = readdir($dir)) !== false) {
			   if(substr( $file, -3 ) == "jpg" ) { 
				 echo "<li><img src='BGimages/" . $file . "' alt='Clarkson University HUB " . $file . "'/></li>";
			   }
			}	
			closedir($dir);
		} 
		function printNextEvent()
		{    
			if( $events = simplexml_load_file('https://knightlife.clarkson.edu/EventRss/EventsRss') ) 
			{
				echo "	<img src='" . $events->channel->item[0]->enclosure['url'] . "'><div class='date'><div class='month'>" . "???" . "</div><div class='day'>" . "00" . "</div></div><div class='details'><div class='title'>" . $events->channel->item[0]->title . "</div><div class='time'>" . "00:00 ??" . "</div><div class='location'> @ " . "LOCATION" . "</div></div>";
			} 
		}
	?>
    <?php /*?><?php require_once('../HUB/xmlParseTest.php'); ?><?php */?>
    <head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1"> 
		<meta name="viewport" content="width=device-width, initial-scale=1.0"> 
		<title></title>
		<meta name="description" content="" />
		<meta name="keywords" content="" />
		<meta name="author" content="" />
		<link rel="shortcut icon" href="../favicon.ico"> 
		<link rel="stylesheet" type="text/css" href="css/component.css" />
        <link rel="stylesheet" type="text/css" href="css/component2.css" />
        <link rel="stylesheet" type="text/css" href="css/transitions.css" />
		<script src="js/modernizr.custom.js"></script>
	</head>
	<body>
    <div class="container">
    <div id="overlay" style="display: none;">
    	<div id="modal">
            <a class="modalClose" onClick="modal('0');">x</a>
            <h4> Clarkson University Hub </h4>
            Created by the following Seniors of the Class of 2014:
            <ul>
                <li>Jeffrey Kopra</li>
                <li>Dan Deloff</li>
                <li>Kevin Chapman</li>
    	</div>
    </div>
    <div id="pt-main" class="pt-perspective">
    	<div id="main" class="pt-page pt-page-current">
          <?php
		  	error_reporting(-1);
       	  	if (isset($_POST["submit"])){			
			
		  	  $from = "noreply@clarksonHUB.edu";
              $subject = "Anonymous Tip from Clarkson HUB";
			  if (isset($_POST["callBackNumber"]) && !empty($_POST["callBackNumber"])) { $callback = $_POST["callBackNumber"]; } else{ $callback = "(no number was provided)"; }
			  $datetime = $_POST["dateTime"];
			  $day = substr($datetime, 8, 2);
			  $month = substr($datetime, 5, 2);
			  $year = substr($datetime, 0, 4);
			  $hour = substr($datetime, 11, 2);
			  $minute = substr($datetime, 14, 2);
			  if($hour >= 12) { $nightDay = "PM"; $hour = $hour - 12; } else { $nightDay = "AM"; } 
			  $body = "<p>Please see below for tip details: </p><div style='font-family: Helvetica, Arial, sans-serif;'><table style='border: 5px solid #f0f0f0; background-color:#fff;  margin: 20px auto; padding: 0 5px 20px 5px;' ><tr><td><h3 style='text-align: center; border-bottom: 3px solid #f0f0f0; font-size: 22px; line-height: 1.1; margin-bottom:15px; color:#008000; line-height: 1.2; margin-left:0; margin-right:0; margin-top:5px; margin-bottom:10px'>Clarkson University HUB Anonymous Tip</h3><p style='margin-top: 20px;'><font size='4'>Incident Type: </font>" . $_POST["incidentType"] . "</p><p><font size='4'>Location: </font>" . $_POST["location"] . "</p><p><font size='4'>Date and time: </font>" . $month . '-' . $day . '-' . $year . '  @ ' . $hour . ':' . $minute . ' ' . $nightDay . "</p><p style='margin-bottom: 0px;'><font size='4'>Additional Info: </font></p><div style='background-color: #f0f0f0; color: #111; line-height: 1.1; margin-bottom:15px; line-height: 1.2; font-weight:200; margin:5px 15px; padding:10px;'>" . $_POST["additionalInfo"] . "</div><p><font size='4'>Call-Back Number: </font>" . $callback . "</p></td></tr></table></div>";
              // message lines should not exceed 70 characters (PHP rule), so wrap it
              $message = wordwrap($message, 70);
			 
			  if ($_POST["receiver"] == "all" ) { 
			  	$to = "koprajr@clarkson.edu, koprajr@gmail.com";
			  }elseif($_POST["receiver"] == "resLife" ) {
			  	$to = "koprajr@gmail.com";
			  }else { $to = "koprajr@clarkson.edu"; }
			  
			  //----------------------------------------------------------------------------
			  	$ssFile=$_POST["ssFile"];
				
				
				for($i=0;$i<count($_FILES['ssFile']['size']);$i++)
				{
					if(strstr($_FILES['ssFile']['type'][$i], 'image/png')!==false
						 || strstr($_FILES['ssFile']['type'][$i], 'image/jpg')!==false
						|| strstr($_FILES['ssFile']['type'][$i], 'image/jpeg')!==false
						|| strstr($_FILES['ssFile']['type'][$i], 'image/pjpeg')!==false
					  )
							{
							$fileAllow="true";
							}
						else
							{
							$fileAllow="false";
							break;
							}
				}
				
				if ($fileAllow!='false') {
			  	// generate a random string to use as boundary marker
				$mime_boundary="==Multipart_Boundary_x".md5(mt_rand())."x";
				// email headers
				$headers = "From: $from\r\n" .
				  "Reply-To: $from\r\n" .
				  "Return-Path: $from\r\n" .
				  "MIME-Version: 1.0\r\n" .
					 "Content-Type: multipart/mixed;\r\n" .
					 " boundary=\"{$mime_boundary}\"";
					 
				// text message to display in email
				$message=$body;
				// MIME boundary for email message
				$message = "This is a multi-part message in MIME format.\n\n" .
					 "--{$mime_boundary}\n" .
					 "Content-Type: text/html; charset=\"iso-8859-1\"\n" .
					 "Content-Transfer-Encoding: 7bit\n\n" .$message . "\n\n";	 
				
				
				 // get uploaded files from form in loop
				function reArrayFiles($ssFile)
				{
system.out.print('file array function');
					$file_ary = array();
					$file_count = count($ssFile['name']);
					$file_keys = array_keys($ssFile);
						for ($i=0; $i<$file_count; $i++)
						{
system.out.print(' filecount ');
							foreach ($file_keys as $key)
							  {
system.out.print(' filekey ');
								$file_ary[$i][$key] = $ssFile[$key][$i];
							  }
						}
				   return $file_ary;
				 }
				 
					  $file_ary = reArrayFiles($_FILES['ssFile']);
					  // process files
					  foreach($file_ary as $file)
					  {
					  
						 // store file information in variables
						 $tmp_name = $_FILES['ssFile']['tmp_name'];
						 $type = $_FILES['ssFile']['type'];
						 $name = $_FILES['ssFile']['name'];
						 $size = $_FILES['ssFile']['size'];
						  echo $tmp_name."\n\n";
						 // if file exists
						 if (file_exists($tmp_name))
						 {
						 system.out.print('1');
							// check to make sure it is uploaded file - not a system file
							if(is_uploaded_file($tmp_name))
							{
							system.out.print('2');
							   // open file for a binary read
							   $file = fopen($tmp_name,'rb');
							   // read file content into a variable
							   $data = fread($file,filesize($tmp_name));
							   // close file
							   fclose($file);
							   // encode it and split it into acceptable length lines
							   $data = chunk_split(base64_encode($data));
							}
			
							// insert a boundary to start the attachment
							// specify the content type, file name, and disposition
							// boundary between each file
							$message .= "--{$mime_boundary}\n" .
							   "Content-Type: {$type};\n" .
							   " name=\"{$name}\"\n" .
							   "Content-Disposition: attachment;\n" .
							   " filename=\"{$name}\"\n" .
							   "Content-Transfer-Encoding: base64\n\n" .
							$data . "\n\n";
							system.out.print('3');
						 }
					  }
					  // closing mime boundary - end of message
					  
						system.out.print('done');
					  
					  $message.="--{$mime_boundary}--\n";
					  
			//----------------------------------------------------------------------------
				
			  
        	  if ( @mail($to,$subject,$message,$headers) ) {  //mail($to,$subject,$message,"From: $from\n")?>  
        		<div id="alert" class="success"> Your tip has been submitted. Thank You!</div>
            <?php }} else { ?>
            	<div id="alert" class="error"> There was an error sending your tip. Please try again! </div>
            <?php } ?>
          <?php } ?>
            
            <div class="container">
                <ul class="grid effect-1" id="grid">
                    <li class="logo"><a onClick="modal('1');"><img src="images/logo.png"></a></li>
                    <li class="bgContorls">
                    	<div id="cbp-bicontrols" class="cbp-bicontrols">
                                <span class="cbp-biprev"></span>
                                <span class="cbp-bipause"></span>
                                <span class="cbp-binext"></span>
                         </div>
                    </li>
                    <li class="login"><a href="#">Login Here <img class="icon" src="images/loginIcon.png"/></a></li>
                    <li>
                        <a id="eventsLink" onClick="showdiv('eventsSection');"><span>Campus Events</span></span><img class="icon" src="images/eventIcon.png"/></a>
                        <div class="nextEvent">
                            <div class="label">Next Event:</div>
							<!-- 
                            //EVENT HTML FORMAT                           
							<img src="images/2.jpg">
                            <div class="date">
                                <div class="month">Mar</div>
                                <div class="day">10</div>
                            </div>
                            <div class="details">
                                <div class="title">Event Title</div>
                                <div class="time">9:00 pm</div><div class="location">@ Student Center</div>
                            </div>
                            -->
                            <?php printNextEvent(); ?>
                        </div>
                    </li>
                    <li><a id="balancesLink" onClick="showdiv('balancesSection');"><span>CU Balances</span><img class="icon" src="images/balancesIcon.png"/></a></li>
                    <li>
                        <a id="contactLink" onClick="showdiv('campusContactsSection');"><span>Campus Contacts</span><img class="icon" src="images/contactIcon.png"/></a>
                        <div class="importantNumbers">
                            <a href="tel:+3152686666">Campus Safety & Security: <span class="number">(315) 268-6666</span></a>
                        </div>
                    </li>
                    <li><a id="tipsLink" onClick="showdiv('tipsSection');"><span>Anonymous Tips</span><img class="icon" src="images/tipsIcon.png"/></a></li>
                    <li><a id="placesLink" onClick="showdiv('placesSection');"><span>Postdam Resturants & Points of Interest</span><img class="icon" src="images/placesIcon.png"/></a></li>
                    <!--			
                    <li><a href="#"><img src="images/11.png"></a></li>
                    -->
                </ul>
            </div>       
        </div>

        <div id="campusContactsSection" class="pt-page">
            <div class="container grid">
                <a class="back" onClick="back('campusContactsSection');">Back <img class="icon" src="images/loginIcon.png"/></a>
                <div class="content">
                	<h1><span>Campus Contacts</span></h1>
                </div>
            </div>
        </div>
        <div id="balancesSection" class="pt-page">
            <div class="container grid">
                <a class="back" onClick="back('balancesSection');">Back <img class="icon" src="images/loginIcon.png"/></a>
                <div class="content">
                	<h1><span>Clarkson University Balances</span></h1>
                    <div class="card">
                    	<span id="cardTitle">Knight Card</span><img src="images/card.png" width="250" height="188" /> <span id="cardAmount"> $ 300.26</span>
                    </div>
                    <div class="card">
                    	<span id="cardTitle">Printer</span><img src="images/card.png" width="250" height="188" /> <span id="cardAmount"> $ 20.30</span>
                    </div>
                    <div class="card">
                    	<span id="cardTitle">D.B.</span><img src="images/card.png" width="250" height="188" /> <span id="cardAmount"> $ 99.99</span>
                    </div>
                    <div class="card">
                    	<span id="cardTitle">Meals</span><img src="images/card.png" width="250" height="188" /> <span id="cardAmount"> 1,000</span>
                    </div>
                </div>
            </div>
        </div>
        <div id="eventsSection" class="pt-page">
            <div class="container grid">
                <a class="back" onClick="back('eventsSection');">Back <img class="icon" src="images/loginIcon.png"/></a>
                <div class="content">
                	<h1><span>Campus Events</span></h1>
                </div>
            </div>
        </div>
        <div id="tipsSection" class="pt-page">
            <div class="container grid">
                <a class="back" onClick="back('tipsSection');">Back <img class="icon" src="images/loginIcon.png"/></a>
                <div class="content">
                	<h1><span>Anonymous Tips</span></h1>
                    <div id="tipsForm">
                    	<h3 style="color: #FF0000; text-align: center; text-transform: uppercase;"> If this is an emergency please call 911 </h3>
          				<form id="tipsForm" method="post" action="<?php echo $_SERVER["PHP_SELF"];?>">
                            <label for="receiver"><span id="required">*</span>Who would you like to send this to? </label>
                            <select name="receiver" id="receiver" required>	
                                <option value="campusSafety">Campus Safety & Security</option>	
                                <option value="resLife">Residence Life</option>	
                                <option value="all">both</option>	
                            </select>
                            <label for="dateTime"><span id="required">*</span>Date / Time of Incident: </label>
                            <input type="datetime-local" name="dateTime" id="dateTime" required> 
                            <label for="location">Location:</label>
                            <select name="location" id="location" required>	
                                <option value="Brooks House">Brooks House</option>	
                                <option value="CAMP">CAMP</option>	
                                <option value="Cubley House">Cubley House</option>	
                                <option value="Cheel Arena">Cheel Arena</option>	
                                <option value="ERC">ERC</option>	
                                <option value="Fitness Center">Fitness Center</option>	
                                <option value="Graham Hall">Graham Hall</option>	
                                <option value="Hamlin House">Hamlin House</option>	
                                <option value="Moore House">Moore House</option>	
                                <option value="Powers House">Powers House</option>	
                                <option value="Price Hall">Price Hall</option>	
                                <option value="Reynolds House">Reynolds House</option>	
                                <option value="Riverside Apartments">Riverside Apartments</option>	
                                <option value="Ross House">Ross House</option>	
                                <option value="Science Center">Science Center</option>	
                                <option value="Snell Hall">Snell Hall</option>	
                                <option value="Student Center">Student Center</option>	
                                <option value="Townhouses">Townhouses</option>	
                                <option value="Woodstock">Woodstock</option>	
                                <option value="Other">Other</option>	
                            </select>
							<label for="incidentType"><span id="required">*</span>Incident Type:</label>
                            <select name="incidentType" id="incidentType" required>	
                                <option value="Drug Activity">Drug Activity</option>	
                                <option value="Fight/Assault">Fight/Assault</option>	
                                <option value="Safety Concern">Safety Concern</option>	
                                <option value="Theft">Theft</option>	
                                <option value="Suspicious Individuals">Suspicious Individuals</option>	
                                <option value="Vandalism">Vandalism</option>	
                                <option value="Other">Other</option>	
                            </select>
                            <label for="additionalInfo"><span id="required">*</span>Additional Information:</label>
                            <textarea name="additionalInfo" id="additionalInfo" required></textarea>
                            <div class="additionalInfoInstructions">Please be as detailed as possible about the incident.</div>
                            <label for="callBackNumber">Upload a Picture:</label>
                            <input type="file" name="ssFile" id="ssFile"><br> <!--multiple-->
                            <label for="callBackNumber">Personal Phone Number(optional):</label>
                            <input type="tel" name="callBackNumber" id="callBackNumber"> 
                            <br>
                            <input type="submit" name="submit" value="Submit Feedback">
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div id="placesSection" class="pt-page">
            <div class="container grid">
                <a class="back" onClick="back('placesSection');">Back <img class="icon" src="images/loginIcon.png"/></a>
                <div class="content">
                	<h1><span>Postdam Resturants & Points of Interest</span></h1>
                </div>
            </div>
        </div>
	</div>
    <div class="main">
        <ul id="cbp-bislideshow" class="cbp-bislideshow">
        	<?php bgPhotoList(); ?>
        </ul>
    </div>
    </div>
		<script src="js/masonry.pkgd.min.js"></script>
		<script src="js/imagesloaded.js"></script>
		<script src="js/classie.js"></script>
		<script src="js/AnimOnScroll.js"></script>
		<script>
			new AnimOnScroll( document.getElementById( 'grid' ), {
				minDuration : 0.4,
				maxDuration : 0.7,
				viewportFactor : 0.2
			} );
		</script>
        <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.9.1/jquery.min.js"></script>
		<script>
			function showdiv(id){				
				addCurrentPage(id);
				document.getElementById(id).classList.add('pt-page-scaleUp');
				jQuery('#main').addClass('pt-page-scaleDownUp');
				setTimeout(function(){removeCurrentPage('main', id)},500);
				jQuery('html, body').animate({ scrollTop: 0 }, 5);
			}
			function back(id){	
				<?php if (isset($_POST["submit"])) { ?>
        			redirectToHome();
            	<?php } else { ?>	
					addCurrentPage('main');
					document.getElementById(id).classList.add('pt-page-scaleDownUp');
					jQuery('#main').addClass('pt-page-scaleUp');
					setTimeout(function(){removeCurrentPage(id, 'main')},500);
					jQuery('html, body').animate({ scrollTop: 0 }, 5);
				<?php } ?>	
			}
			function addCurrentPage(id ){				
				document.getElementById(id).classList.add('pt-page-current');
				
			}
			function removeCurrentPage(id1, id2){				
				document.getElementById(id1).classList.remove('pt-page-current');
				document.getElementById(id1).classList.remove('pt-page-scaleDownUp');
				
				document.getElementById(id2).classList.remove('pt-page-scaleUp');
			}
			function removeModalClass(id, bool){				
				document.getElementById('modal').classList.remove(id);
				if (bool == '1') {	
					document.getElementById('overlay').style.display = 'none';
				}
			}
			function modal(id){	
				if (id == '1') {	
					document.getElementById('modal').classList.add('pt-page-scaleUp');		
					document.getElementById('overlay').style.display = 'inherit';
					setTimeout(function(){removeModalClass('pt-page-scaleUp', 0)},400);
				}
				else {	
					document.getElementById('modal').classList.add('pt-page-scaleDown');		
					setTimeout(function(){removeModalClass('pt-page-scaleDown', 1)},400);
				}
			}
			function redirectToHome()
			{
				window.location="index.php";
			}
			jQuery('#overlay').click(function(e) {
				modal('0');
			});
			
			
		</script>
        <script src="js/jquery.imagesloaded.min.js"></script>
		<script src="js/cbpBGSlideshow.min.js"></script>
		<script>
			jQuery(function() {
				cbpBGSlideshow.init();
			});
			
			
		</script>
	</body>
</html>