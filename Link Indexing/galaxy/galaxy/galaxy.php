<?php
/*************************************************
Galaxy Link Database 2004
By:Richard B Mowatt <ashoka323@lbilocal.com>
http://www.rmowatt.lbilocal.com
Anyone is free to use and modify this script as long as references to my site remain. 
Do not try to sell this script.
************************************************/
include('functions.php');//include our function file

///////////////////////////////////////////////////////////////////////////////////////////////

	if (!empty($_GET['comm'])){//User wants to see comments for a given link
    $postId = $_GET['comm'];//link number
	$itemId = $_GET['comm'];
	//create the SQL call
	$postQry = "select ud.userName, threadId, comment from replyComments 
	inner join userData ud on usrId = ud.id
	where threadId = '$postId'";
	//submit query
	$postQryRes = mysql_query($postQry,$conn) or die (mysql_error());
	if (mysql_num_rows($postQryRes) < 1){//there are no comments for given link
	print "$mainHead$returnLinks";
	print "There are currently no comments for this link. Why not be the first?";
	print "<br /><a href = \"galaxy.php?add=$postId\">click here to add a comment</a>";
	}
	else{//comments exist, get and format
	$currentComment = linkToCommentOn($postId);//get the original info
	print "$mainHead$returnLinks";//begin xhtml
	print '<table width="760" border="1" align="center">';
	print "<tr><td class=\"welcome\" >$welcomeText</td></tr></table>";
	print "$currentComment";
	print "<hr />";
	if(!empty($_GET[thumbsUp])){
	 print'<table width="760" border="1" align="center"><tr><td class="conf">You have successfully entered a comment.</td></tr></table>';
	}
	print '<table width="760" border="1" align="center">
	       <tr><td colspan = "2" class = "descHeaders">Comments</td></tr>';
		   print"<tr><td colspan = \"2\" class = \"commRet\"> <a href = \"galaxy.php?add=$postId\">click here to comment on this link.</a></td></tr>";
		   print'<trclass = "conf"><td class="topRow">User Name</td><td class="topRow">Comment</td></tr>';
	  while ($responses = mysql_fetch_array($postQryRes)){//print each comment to its own table row
	          $usrId = $responses['userName'];
			  $threadId=$responses['threadId'];
			  $comment = $responses['comment'];
			  print"<tr><td class = \"tDs\" width=\"20%\">$usrId</td><td class = \"tDs\" width=\"80%\">$comment</td></tr>";
			  }
			  print "</table>$returnLinks$validXHTML</body></html>";//print end lines
			  exit;
			  }
			  }
			  
////////////////////////////////////////////////////////////////////////////////////////////////////////			  
			  
	  elseif (!empty($_GET['insert'])){//user has submitted a comment on a link
	  if((!empty($_POST[usrName]))&&(!empty($_POST[usrEmail]))&&(!empty($_POST[comment]))){//all fields are complete
                 $verify = "select id from userData where userName = '$_POST[usrName]' AND userEmail='$_POST[usrEmail]'";//see if user has made previous posts
                 $verify_result = mysql_query($verify, $conn) or die (mysql_error());
					   if (mysql_num_rows($verify_result) < 1){//there are no recordes of user name and email appearing together
			                     $doesNameExist = userExists($_POST[usrName]);//check if user name exists
				                  if ($doesNameExist == 1){//it does, user may wanna try again witha different email so give them a heads up
								             print "$mainHead$returnLinks";
				                             $returnData = "This user name has already been taken, If you believe that you are this user please reenter your information and try again";//user name exists                                                                                               //but not with email
			                                 print "$returnData$returnLinks$validXHTML</body></html>";
					                         exit;
                                    }
		                          else{//username does not exist and user can create new user record
					                    $validemail = checkValid($_POST['usrEmail']);//make sure email is valid and email server exists
                                         if (($validemail != "0")){//they do
							                 $insertUserData = "insert into userData values('','$_POST[usrName]','$_POST[usrEmail]')";//inser new user data
		                                     $iUDquery = mysql_query($insertUserData, $conn) or die (mysql_error());   
		                                     $userId = mysql_insert_id();//get the users id number
                                             insertComment($userId,$_GET['insert'],$_POST['comment']);//insert comment into database
											 header("Location: galaxy.php?comm=$_GET[insert]&thumbsUp=1");
											 exit;
						                    }
		                                 else{//there is a problem, prompt again
										      $reId = $_GET['insert'];
											  header("Location: galaxy.php?add=$reId&error=1");
											  exit;
                                             }		
									   }
		                         }
		                  else{//user name and email check out
                                  while($establishedId = mysql_fetch_array($verify_result)){//get users id for insert
			                                $userId = $establishedId['id'];
									    }
                                  insertComment($userId,$_GET['insert'],$_POST['comment']);//insert comment to database
								  header("Location: galaxy.php?comm=$_GET[insert]&thumbsUp=1");
											exit;
				                }
                }			
					else{//form fields are incomplete                   
					     $reId = $_GET['insert'];
						 header("Location: galaxy.php?add=$reId&error=1");
						 exit;}
	}		
	
//////////////////////////////////////////////////////////////////////////////////////////////////////////// 
					 

elseif (!empty($_GET['add'])){//user wants to add a comment
      $addVar = $_GET['add'];
      print "$mainHead$returnLinks";
      if(!empty($_GET['error'])){//the form has been submitted w/ an error so make user aware
           print'<table width="760" border="1" align="center"><tr><td class="error">ERROR!!!<br /> Either we can not verify this email or fields were incomplete.<br />Please try again</td></tr></table>';
          }
       $currentLink = linkToCommentOn($addVar);//get the link to be commented on
//print the form
       print '<table width="760" border="1" align="center">';
       print "<tr><td class=\"welcome\" >$welcomeText</td></tr></table>";
       print "$currentLink";
       print "$instructions";
       print"<td><form action=\"galaxy.php?insert=$addVar\" method=\"post\">";
       print'
            User Name:<br /><input name="usrName" type="text" size="40" maxlength="16" /><br />
            Email<br /><input name="usrEmail" type="text" size="40" maxlength="75" /><br />
            Comments:<br /><textarea name="comment" cols="40" rows="5"></textarea><br />';
       print"<input name=\"thread\" type=\"hidden\" value=\"$addVar\" />
            <input name=\"submit\" type=\"submit\" value=\"Submit Your Comment\"  /></form></td>
            </tr></table>$returnLinks$validXHTML";
			}

///////////////////////////////////////////////////////////////////////////////////////////////
	
else{//no actions have been submitted, show main link sheet
//create the query	
    $sql = "
           SELECT li.id,ud.userName,pageName,link,description
           FROM linkInformation li
           INNER JOIN userData ud 
           ON li.user = ud.id
           WHERE li.switch = '1'
           LIMIT 0, 30 ";
//execute the query	
    $sqlQry = mysql_query($sql, $conn) or die (mysql_error());
	if (mysql_num_rows($sqlQry) < 1){//there are no links in database
	       print "$mainHead$returnLinks";
	       print "there are currently no submitted links";
		   }
	else{//there are links so letes cycle through and put each on its own row
	     print "$mainHead$returnLinks";
	     print '<table width="760" border="1" align="center">';
	     print "<tr><td class=\"welcome\" colspan=\"6\">$welcomeText</td></tr>";
	     print '<tr class ="topRow"><td width="20%">Submitted By</td><td width="20%">Page Name</td><td width="20%">Link</td><td width="20%">Description</td><td colspan = "2" width="20%">Comments</td></tr>';
	            while($properties = mysql_fetch_array($sqlQry)){//parse the array
		                                          $id = $properties['id'];
												  $user = $properties['userName'];
												  $page = $properties['pageName'];
												  $hyper = $properties['link']; 
												  $link = explode("/", $properties['link']);
												  $description = $properties['description'];
												  print"<tr ><td class = \"tDs\" width=\"15%\">$user</td><td class = \"tDs\" width=\"17%\">$page</td><td class = \"tDs\" width=\"13%\"><a href=\"http://www.$hyper\" target = \"_blank\">$link[0]</a></td>\n<td class = \"tDs\" width=\"43%\" >$description</td><td class = \"tDs\" width=\"6%\"><a href = \"galaxy.php?comm=$id\">View</a></td><td class = \"tDs\" width=\"6%\"><a href =\"galaxy.php?add=$id\">Add</a></td></tr>\n";
				       }
		 print " </table>$returnLinks$validXHTML";//print our closing tage
					}
	}
	//were outta here!
?>

</body>
</html>



