<?php
$error = array();
$error[1] = "This user name has already been taken, If you believe that you are this user please reenter your information and try again";//user name exists but not with email
$error[2] = "It appears that you have entered an invalid link.<br /> Please try again<br />Tip: Do not include http://www. in your link";//link did not check out
$error[3] = "email is not correctly formatted or email server does not exist, please try again";//email did not check out
$error[4] = "please fill out all form elements completely";

include('functions.php');
if ($_POST[linkSub]){//script is being submitted
     if (!empty($_POST[uName])&&!empty($_POST[uEmail])&&!empty($_POST[pageName])&&!empty($_POST[link])&&!empty($_POST[desc])){//make sure all fields are complete
              $verify = "select id from userData where userName = '$_POST[uName]' AND userEmail='$_POST[uEmail]'";//see if user has made previous posts
		       $verify_result = mysql_query($verify,$conn) or die(mysql_error());
			   $emailAddress = $_POST['uEmail'];
               if (mysql_num_rows($verify_result) < 1){		//there are no recordes of user name and email appearing together
			               $doesNameExist = userExists($_POST[uName]);
				            if ($doesNameExist == 1){
				                     $returnData = "This user name has already been taken, If you believe that you are this user please reenter your information and try again";//user name exists but not with email
			                           print "$returnData";
									    //header("Location: linkSub.php?error=1");
					                    exit;
								}
					         else{$insertUserData = "insert into userData values('','$_POST[uName]','$_POST[uEmail]')";//inser new user data
		                          $iUDquery = mysql_query($insertUserData, $conn) or die (mysql_error());   
								  $userId = mysql_insert_id();//ge the users id number}
								  $userName = $_POST[uName];
								  confMail($emailAddress, $userName );
									}
					}
				   else{//user name and email check out
				         while($establishedId = mysql_fetch_array($verify_result)){
								$userId = $establishedId['id'];
							}
				     }
				//////////////////////////
				   
			        $emailValidity = checkValid($_POST[uEmail]);//use regex to make sure email is formatted right and email server exists
		            if($emailValidity != "0"){//email checks out
						  $linkValidity = checkValidLink($_POST[link]);//check to make sure that link is formatted correctly and corresponds to an actual server
						  if($linkValidity != "0"){//link checks out
							  $mailLink = $_POST[link];
							  $mailDesc = $_POST[desc];
							  $pageName=  $_POST[pageName];
		                      $insertLink = "insert into linkInformation values('', '$userId', '$_POST[pageName]','$_POST[link]', '$_POST[desc]','')";
		                      $iLquery =  $iUDquery = mysql_query($insertLink, $conn) or die ("your entry could not be completed for the following reason:<br /><b>" . mysql_error() . "</b><br />Please make sure that this link hasn't been previously submitted.<br />Use the back button on your browser to avoid losing information.");//insert information into table
		                      adminMail($mailLink,$mailDesc);
							  header("Location: linkSub.php?confirm=$pageName");
							}
						else{
						    header("Location: linkSub.php?error=2");
							exit;
							//print "It appears that you have entered an invalid link. Please try again";//link did not check out
							}
					}
	         else{
			      header("Location: linkSub.php?error=3");
				  exit;
			      //print "email is not correctly formatted or email server does not exist, please try again";//email did not check out
	          }//closes ifempty statements
		       } 
		else{
		        header("Location: linkSub.php?error=4");
				exit;
			    //print "please fill out all form elements completely";
			}
			}//closes POST check statement
									   
		           
else{//user is seeing form for first time or has committed an error
print "$mainHead$returnLinks";
if(!empty($_GET[error])){
$n = $_GET[error];
print"<table width=\"760\" border=\"0\" align=\"center\">
<tr><td class=\"error\">$error[$n]<br />
Use your browsers back button to avoid having to retype data.
</td></tr></table>";
}
if(!empty($_GET[confirm])){
print "<table width=\"760\" border=\"0\" align=\"center\">
<tr><td class=\"conf\">
You have sucessfully added $_GET[confirm] to our links page<br />
This link will not appear until it has been confirmed by the sites administrator<br />
<a href = \"galaxy.php\">Click here to view all links</a>
</td></tr></table>";
}
print"<table width=\"760\" border=\"0\" align=\"center\">
<tr><td class = \"welcome\">$welcomeText</td></tr></table>";
print"$instructions";
print'
<td>
<form action="linkSub.php" method="post" >
<span class="topRow">User Name:</span><br /><input name="uName" type="text" maxlength="16" size="32" /><br />
<span class="topRow">User Email: </span><br /><input name="uEmail" type="text" size="32" /><br />
<span class="topRow">Name of Page:</span><br /><input name="pageName" type="text" maxlength="25" size="32" /><br />
<span class="topRow">Page Link:</span><br /><span class="httpSpan">http://www.</span><input name="link" type="text" maxlength="75" /><br />
<span class="topRow">Page Description</span><br /><textarea name="desc" cols="25" rows="8"></textarea><br />
<input name="linkSub" type="hidden" value="1" />
<input type ="submit" name = "submit" value = "Submit Link" />
</form></td></tr></table>';
print "$returnLinks$validXHTML";}

?>
</body>
</html>
