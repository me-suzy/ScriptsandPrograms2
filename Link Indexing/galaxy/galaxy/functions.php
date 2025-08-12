<?php
/*
This is the function sheet. Script will not work if it cant find this page, make sure that it is in the same folder as galaxy.php
*/
/*************************************************
           !!!!!!!!!!READ ME!!!!!!!!!!!!!!!
Galaxy Link Database 2004
By:Richard B Mowatt <ashoka323@lbilocal.com>
http://www.rmowatt.lbilocal.com
Anyone is free to use and modify this script as long as references to my site remain and this header remains on each script. 
(you can change page title located on line 45 if you wish)
Please do not try to sell this script.
I would also please ask that you do not share any email addresses obtained by use of this script.
But since I cant force ya, please change line 71 If you do.
************************************************/
///////////////////////////////////////////////
///Here are the variables for admin to define//
// do not remove quotes(") or semi-colons(;) //
//////////////////////////////////////////////
$usrName=databaseusername;//database user name
$dataBase=databasename;//name of database
$pwd=databasepassword;//database user password
$yourEmail = "youremail@yourserver.com";//the email that you want confirmation notices sent to
$nametoAppearOnEmail = "your name";//the name to appear in email headers
$welcomeText = "Welcome to the Galaxy 2004 Link database";//this is the text that will appear at the top of the page welcoming visitors
//////////////////////////////////////////////
////END VARIABLE DEFINITIONS//////////////////
///////////////////////////////////////////////

/*************************************************************************************************************
**************************************************************************************************************
                           Do not edit below this point unless you know how!!!!!!!
***************************************************************************************************************
*************************************************************************************************************/
////VARIABLES
////////////
$conn = mysql_connect ("localhost", $usrName, $pwd)//connect function
or die(mysql_error());
mysql_select_db($dataBase, $conn) or die(mysql_error());
/////////////////////////////
//The head of our pages
$mainHead='<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Galaxy by Richard Mowatt::http://www.rmowatt.lbilocal.com::</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="galaxy.css" rel="stylesheet" type="text/css" />
</head>
<body>
';
//////////////////////////////////////////////////////
/////////Denotes Valid XHTML 
//Has been tested (if it doesn't check out then it's something you did!)
$validXHTML = "
<table width=\"760\" border=\"0\" align = \"center\">
<tr><td align = \"right\"><a href=\"http://validator.w3.org/check?uri=referer\">
<img src=\"http://www.w3.org/Icons/valid-xhtml10\"
alt=\"Valid XHTML 1.0!\" height=\"31\" width=\"88\" /></a></td></tr>
<tr><td align = \"right\" class=\"commRet\">Galaxy 2004 Link Database by:<td></tr>
<tr><td align = \"right\" class=\"commRet\"><a href = \"http://www.rmowatt.lbilocal.com\" target = \"_blank\">Richard Mowatt</a><td></tr></table>";
////////////////////////////////////////////
////////Our Footer
$returnLinks = "
<table width=\"760\" border=\"0\" align = \"center\">
<tr><td>
<table width=\"210\" border=\"0\" align = \"right\">
<tr >
<td class=\"commRet\"><a href = \"linkSub.php\">Add a Link</a></td>
<td class=\"commRet\"> <a href = \"galaxy.php\">main page</a></td>
<td class=\"commRet\"><a href = \"backend/backend_index.htm\" >admin</a></td>
</tr>
</table>
</td></tr>
</table>";
////////////////////////
//User Instructions
$instructions = '
<table width="760" border="0" align="center">
<tr><td class="directions"><span class="topRow">IMPORTANT: PLEASE READ</span><br />
Your email address is for confirmation purposes only and will not be shared<br />
You do not need a registered account<br /> 
<span class="topRow">!!!HOWEVER!!!</span>, it is recommended that you use the same user name and email each time you make a post<br />
Doing so will allow others to view all your comments and suggestions.<br />
Links will not become visible until approved<br />Please complete this form in its entirety.<br /></td>
';
////////////////////////
//Backend Head
$backHead = '
<table width="760" border="1" align="center">
<tr><td class="welcome">Galaxy 2004 Database<br />Administration Panel</td></tr></table>
<hr>
<table width="760" border="0" align="center">
<tr><td align = "center" >';

	
	
//////////////////////////
//BEGIN FUNCTIONS
///////////////////////
function insertComment($usrId,$threadId,$comment){//function to insert comments into database
global $conn;
$comInfo = "insert into replyComments values ('', '$usrId','$threadId','$comment')";//structure insert
$subComment = mysql_query($comInfo, $conn) or die (mysql_error());//execute insert
}	

function checkValid($email){//make sure that users email is correctly formatted and email server exists
$regexp = "^[0-9a-z_\.-]+@+([0-9a-z][0-9a-z-]*[0-9a-z]\.)+[a-z]{2,3}$";
if (eregi( $regexp, $email )){//address is properly formatted, check domain
list($handle, $domain) = split("@", $email); //get the email domain
if (checkdnsrr($domain, "MX")) {
$rval = 1;}//domain exists
else{$rval =0;}}//domain does not exist
else{$rval = 0;}//address is not properly formatted, prompt to reenter info
return $rval;
}

function checkValidLink($linkck){//check to make sure that the link submitted exists
$domain = explode("/",$linkck);//take off any path references
$regexp = "^[0-9a-z_\.-]+\.+[a-z]{2,3}$";
if (eregi( $regexp, $domain[0] )){//url is properly formatted
if (checkdnsrr($domain[0], "MX")) {
return 1;}//domain is valid
else {return 0;}}//domain is not valid
else{
return 0;//url not properly formatted
}
}


function userExists($a){	//check to see if the user name already exists
global $conn;
$verifyName = "select userName from userData where userName = '$a'";//check to see if user name exists
$verify_result2 = mysql_query($verifyName, $conn) or die (mysql_error());
    if (mysql_num_rows($verify_result2) < 1){//user name does not exist
			return 0;
		}
	else{return 1;}//user name does exist
					}
							  

function confMail($recipientsEmail, $usrName){//send an email to confirm that a link or comment has been submitted
global $yourEmail;//return address
global $nametoAppearOnEmail;
$recipient = $recipientsEmail;
$path = getServerPath();
$subject = "Thank you for submitting a link to $_ENV[SERVER_NAME]!";
$msg = "Thank you for using our link boards\n
        You can view our boards at $pathgalaxy.php\n
	    Your user name is $usrName\n
		Please use this user name and the email that this message has been sent to for all future posts\n
		Doing so will allow others to view all your posts and comments\n
		We do not share your email address.\n
		This is an automatically generated message\n
		Galaxy 2004 Link Database by Richard Mowatt\n
		http://www.rmowatt.lbilocal.com";
$mailheaders = "From: $nametoAppearOnEmail <$yourEmail> \n";
$mailheaders .= "Reply-To: $yourEmail";
mail($recipient, $subject, $msg, $mailheaders);//send the mail
}

function adminMail($link,$desc){//send an email to tell you that a link has been posted
global $yourEmail;
global $nametoAppearOnEmail;
$path = getServerPath();
$path .= "backend/backend_index.htm";
$subject = "A link has been submitted to your site";
$msg = "A user has submitted a link to your site\n
	    This link will not be visible until you confirm its appearance\n
	    You can visit the link at  http://www.$link \n
		The users description was as follows:\n
		$desc\n
		To confirm this page go to $path \n
		(Instructions: sign in, choose show links, select this link and submit.)\n
		This is an automatically generated message\n
		Galaxy 2004 Link Database by Richard Mowatt\n
		http://www.rmowatt.lbilocal.com";
$mailheaders = "From: $nametoAppearOnEmail <$yourEmail> \n";
$mailheaders .= "Reply-To: $yourEmail";
mail($yourEmail, $subject, $msg, $mailheaders);//send the mail
}

function linkToCommentOn($addVar){//show user the link for which they are submitting a comment on
global $conn;
$sql = "select * from linkInformation where id = '$addVar'";//get link info
$sqlRes = mysql_query($sql,$conn);
if (mysql_num_rows($sqlRes) <1){//link does not exist
$rVal = "the link you are refering to does not exist";
}
else{//link exists, get info
while($parseRes = mysql_fetch_array($sqlRes)){
           $pageName = $parseRes['pageName'];
           $link = $parseRes['link'];
            $desc = $parseRes['description'];
			}
//now create a table with info
$rVal = "
<table width=\"760\" border=\"1\" align=\"center\">
<tr><td colspan=\"3\" class=\"descHeaders\">Original Information</td></tr>
<tr><td class=\"tDs\"><span class=\"topRow\">Page Name:</span><br />$pageName</td>
<td class=\"tDs\"><span class=\"topRow\">link:</span><br /><a href = \"http://www.$link\" target = \"_blank\">$link</a> </td>
<td class=\"tDs\"><span class=\"topRow\">Description:</span><br />$desc </td></tr>
</table>
";
}
return $rVal;
}
	
function getServerPath(){//gets the absolute URL to the folder in which scripts subside
$output = "http://www.";
$output .= "$_ENV[SERVER_NAME]";
$output .= "$_ENV[REQUEST_URI]";
//print"$output";
$pieces = explode("/", $output);
$mod = count($pieces);
unset($pieces[($mod -1)]);
foreach($pieces as $pi){
$newOut .= "$pi/";
}
return $newOut;
}							  
?>
