<?php

/*******************************************************************************
----READ ME PLEASE - July 24 2005  - A simple guestbook-comment script v 1.2d---
********************************************************************************
 **IMMEDIATELY BELOW THIS README ARE SOME VARIABLES THAT NEED TO BE SPECIFIED.**
********************************************************************************

Package contents of the version 1.2 distribution:

- readme.txt - a read me telling you to you read what you're reading right now
- comments.php - the main file that you will display on your website (this file)
- commadmin.php - an administration module that allows simple editing of posts
- comminstall.php -An installer that creates the table needed to run your script
  You should remove this from your server once the script(s) is installed.
  
  In the docs folder - No need to upload this to your server: 
  
- docs.html - Documentation that you can read in your browser. Includes 
  installation instructions, as well as implementation and customization guides
- several gifs used by the documentation
 
You must have a database existing that you are able to create a table in ( the 
installer will create the table, but not a database ) Ask your webhost.
 
********************************************************************************
***************************       INSTALLATION    ******************************

1. Modify these items in 'SET VARIABLES BELOW' section, following this readme.
   ONLY CHANGE THE VALUES BETWEEN THE QUOTE MARKS, DON'T REMOVE THE QUOTES
   This section is explained more fully in the documentation, 'docs.html',
   which is a part of this distribution and which you can read in a web browser
   
The first 4 items are database connection details:   
   
-$dbserver - the name of your database server. 'localhost' is a common value for
 many webhosts, but some use named servers, so put that value here.
 Ask your webhost if you don't know.

-$dbuser - the user name for your database. Ask your webhost if you don't know.

-$dbpassword - the password for your database. Ask your webhost if unsure.

-$dbname - the database available to you, must exist already. Check with your 
 web host if you don't know what this is.
 
The next 2 items are specific to your running of this comment script. It's kind
of important to understand the relationship between these 2 items:
   
-$admin_name - this is your administrator PASSWORD for this script ONLY - It is
 not related to your database or webserver passwords and SHOULD be set to 
 something completely unrelated to any other passwords you use - keep this
 PRIVATE. You will post comments to your guestbook using this name, and also use
 this to access the administration window (see step 4 below).
 
 That said,as an example, if your name is Bob, you might set your $admin_name to
 'Bob_xyz' (or whatever), then....

-$display_name - Set this to 'Bob' in this example. When you, as admin, post
 a comment, use the admin_name you created above, 'Bob_xyz', it will display in 
 the comments as 'Bob', and have a distinguishing highlight. Other posters will
 be unable to use the name 'Bob' to post. 

This next item sets the table name that will be created when you run the 
installer script. Comments posted to this script will be saved in this table:

-$tablename - default is 'comments', change to whatever you like if you need to.

That is the end of required edits. The last item is optional. To change the 
display of flags on or off:

-$flags - set to 1 to turn on, 0 (zero) to turn off

This file,'comments.php' is the only file requiring these modifications. 
You may, if you wish, rename the file. The installer will ask you for the name.

********************************************************************************
2. Upload the 3 files - comments.php (or your renamed version of it), 
comminstall.php, and commadmin.php to your webserver. They can be in any 
directory, but all need to be in the same directory.
Direct your web browser to the installer script, ie.: 
http://www.yourwebsite.com/comminstall.php - Follow the instructions.
After a successful install you will be able to go directly to your guestbook. 

********************************************************************************
3. The script runs nicely in an iframe (not absolutely required) - 
this works pretty good for a start, modify to suit your site:
<iframe src="comments.php" width=482 height=140 vspace=3></iframe>
It also works well in it's own window or in CSS layouts, but you will need to 
modify the CSS on your own. See docs.html for customization guidelines.
In this current version, it doesn't work well in narrow layouts without some
hacking to the input area. Depending on browser, the minimum width is 
around 420px before things get ugly.

********************************************************************************
4. To access the Administration Panel, enter your adminstrator password as your
name, and the word 'admin' (without quotes) as a comment, and press 'SEND'.
The status bar below the input area will change to a link to the admin panel.
Click it for a popup where you can edit/delete posts.

********************************************************************************
A 'Bad Words' filter is incorporated in this version. Scroll down a bit, you 
can't miss it.**CAUTION-THERE ARE BAD WORDS THERE**. You can add additional 
words following the pattern shown. You can also change the characters that 
replace the bad words.

********************************************************************************
Note that it would be easy to have multiple instances of this script, simply
rename a copy of it and use a different table for it, allowing you to have
different comment areas on different pages of your site. To do this:
-save a copy of 'comments.php' as 'comments2.php'
-in 'comments2.php', change '$tablename' in the variables area to 'comments2'
-run comminstall.php, and change the default name to 'comments2.php' in the form
provided. See docs.html for details.

Freely distributable with this read me and credits left intact. If you do any 
interesting mods I would luv to know about them.

Support  bryhal@rogers.com or http://bry.kicks-ass.org or www.bryancentral.com

A refresh version of this script is available there, works sort of like a chat
room, but I have ended development of that version.

Changelog:
Aug 20/05  - fixed a bug with sessions and multiple guestbook installs
July 24/05 - minor rev to add copyright and 'pad' bottom so new installs don't 
			 look stupid. Fixed 'register_globals on' bug.
July 15/05 - Finally got admin mode working properly, albeit a bit cludgy, but a
			 single admin script now supports any number of comment scripts
			 To Do - provide simple bbcode type formatting 
July 09/05 - Added Administration Mode, currently it only deletes entries
		   - Significantly cleaner code as I get better at this PHP stuff
June 25/05 - Created a 'bad word' filter
           - Fixed a common problem with idiots typing very long words, breaking
             table layout. Words are automatically broken after 40 characters
           - Created a simplistic installer, it just creates the table needed.
           - To Do - soon I will add a simple admin capability for editing posts
June 11/05 - First 'public' realease, I've been using it for quite a while.

********************************END OF README**********************************/

//*************************SET THE VARIABLES BELOW******************************

$dbserver = "localhost";			//leave quotes intact for all
$dbuser = "your_db_username";
$dbpassword = "your_db_password";
$dbname = "your_db_name";

$admin_name = "admin_xyz";			//This is your 'private' admin name/password
$display_name = "Admin";	//This is what it displays as

$tablename = "comments";

//OPTIONAL - set to 0 (zero) to turn flags off, 1 to turn on:

$flags = 1;							//note there are no qoutes on this one

//***********************END OF REQUIRED VARIABLES******************************
$thistablename = $tablename;
$thisdisplay_name = $display_name;
$thisadmin_name = $admin_name;
//***************BAD WORDS FILTER - CAUTION - BAD WORDS BELOW ******************

//badwords filter
function check($string)
{
$replace = array();
$with = "xxxxxx";   //These characters replace the bad word. Change if desired.

$replace[] = "shit";
$replace[] = "fucking";
$replace[] = "fuckin";
$replace[] = "fucks";
$replace[] = "fuck";
$replace[] = "cocksucker";
$replace[] = "cock";
$replace[] = "faggot";
$replace[] = "nigger";
$replace[] = "cum";
$replace[] = "cunt";
$replace[] = "asshole";
$replace[] = "nigger";
$replace[] = "masturbate";
$replace[] = "jackoff";
$replace[] = "jerkoff";
$replace[] = "wank";
$replace[] = "tits";

/* just keep adding them following that pattern */

//*****NO EDITS BELOW THIS LINE - Or..the CSS is further down if you want*******

return str_replace($replace, $with, $string);
}

//FUNCTIONS*********************************************************************

function dbconnect() {
	global $dbserver, $dbuser, $dbpassword, $dbname;
	$dbcnx = @mysql_connect("$dbserver","$dbuser","$dbpassword");
	if (!$dbcnx) {
	die( '<p>Unable to connect to the database server at this time.</p>' );
	}
	if (! @mysql_select_db($dbname) ) {
	die( '<p>Unable to locate the comments database at this time.</p>' );
	}
}

function getcomments() {
	global $tablename, $row, $result;
	$result = @mysql_query("SELECT name, comment, date, ip, id FROM $tablename order by date desc"); 
	if (!$result) {
	die('<p>Error performing query: ' . mysql_error() . '</p>');
	}
}

function pad_bottom() {
	global $repeat;
	while ( $repeat < 10 ) {
	echo('<table><td>&nbsp;</td></table>');
	$repeat++;
	}
	echo('<h2>comment-guestbook script &copy; bryan h. - bry.kicks-ass.org</h2>');
	}


function listcomments() {
	global $row, $result, $name, $comment, $admin_name, $display_name;
	while ( $row = mysql_fetch_array($result) ) {
  	$name = htmlspecialchars(stripslashes($row['name']));
  	$comment = htmlspecialchars(stripslashes($row['comment']));
//  If the comment is from the site owner
  	if ($name == "$admin_name") {
	$name = "$display_name";
  	echo('<table><tr><td title = "' . $row['date']  . ' ' . $row['ip'] . '"><font color = "#cc0000">' . $display_name . ':: </font>' . $comment . '</td></tr></table>') ;
	}
	else {
//  If the comment is from a  visitor 	
    echo('<table><tr><td title = "' . $row['date']  . ' ' . $row['ip'] . '"><font color = "#0066FF">' . '<IMG SRC="http://www.hostip.info/api/flag.GIF?ip=' . $row['ip'] . ' " width=18 height =10>' . ' ' .$name . ':: </font>' . $comment . '</td></tr></table>') ;
}
}
$name = $_POST['name'];
}

function listcomments_noflag() {
	global $row, $result, $name, $comment, $admin_name, $display_name;
	while ( $row = mysql_fetch_array($result) ) {
  	$name = htmlspecialchars(stripslashes($row['name']));
  	$comment = htmlspecialchars(stripslashes($row['comment']));
//  If the comment is from the site owner
  	if ($name == "$admin_name") {
	$name = "$display_name";
  	echo('<table><tr><td title = "' . $row['date']  . ' ' . $row['ip'] . '"><font color = "#cc0000">' . $display_name . ':: </font>' . $comment . '</td></tr></table>') ;
	}
	else {
//  If the comment is from a  visitor 	
    echo('<table><tr><td title = "' . $row['date']  . ' ' . $row['ip'] . '"><font color = "#0066FF">' . $name . ':: </font>' . $comment . '</td></tr></table>') ;
}
}
}


//END OF FUNCTIONS**************************************************************

// start the session 
session_start(); 
header("Cache-control: private"); //IE 6 Fix
header('P3P: CP="NOI DSP COR NID NOR"');
 
// required only for comminstall.php
if (isset($_POST['createtable'])) {
	return; }
	
//default values needed for input fields 
if ( ! isset($_POST['name'])) { 
$_POST['name'] = "name";  } 
if ( ! isset($_POST['comment'])) { 
$_POST['comment'] = "comment";  }

// Get the user's input from the form 
$name = $_POST['name']; 

// Register session keys  
$_SESSION['name'] = $name;
$_SESSION['dbserver'] = $dbserver;
$_SESSION['dbuser'] = $dbuser;
$_SESSION['dbpassword'] = $dbpassword;
$_SESSION['admin_name'] = $thisadmin_name;
$_SESSION['dbname'] = $dbname;
$_SESSION['tablename'] = $thistablename;
$_SESSION['auth'] = 1;
?>
<html>
<head>
<title>comments-guestbook</title>  <!--You can change this to your name -->

<!-- *********START OF CSS - As is, this has been tested with all major browsers 
and displays as expected, modify at your own risk and test extensively. Changing
colors is very safe, but changing sizes may have unexpected results . 
See the docs.html included with this script for explanations of theses areas -->

<style type="text/css">
<!--
table {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 9px;
	background-color: #DDDDDD;
	margin-top: 1px;
	width: 100%;
	padding-left: 8px;
}

h2 {
	font-family: Verdana, Arial, Helvetica, sans-serif;
	font-size: 9px;
	font-weight: bold;
	color: #CCCCCC;
	background-color: #554499;
	text-align: center;
	padding: 0px;
	margin: 0px;
}

.inputa {
	color: maroon;
	font-size: 9px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	margin: 0px;
}

body {
	SCROLLBAR-3DLIGHT- COLOR: #aaaaaa;
    SCROLLBAR-ARROW- COLOR: #FFFFFF;
	SCROLLBAR-BASE-COLOR: #555555;
	SCROLLBAR-TRACK-COLOR: #888888;
	SCROLLBAR-DARKSHADOW-COLOR: #111111;
	SCROLLBAR-FACE-COLOR: #999999;
	SCROLLBAR-HIGHTLIGHT-COLOR: #eeeeee;
	SCROLLBAR-SHADOW-COLOR: #333333;
	BORDER-COLOR: #FFFFFF;
	margin: 0;
	background-color: #666666;
}
.submita {
	
	text-align: center;
	font-family: Arial, Helvetica, sans-serif;
	font-size: 9px;
	font-weight: bold;
	color: white;
	background-color: #A0A0A0;
	vertical-align: middle;
	width: 10%;
	margin: 0;
	float: none;
	height: 16px;
}
.inputaname {
	color: maroon;
	font-size: 9px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	margin: 0px;
	width: 12%;
	clear: none;
	border: 1px solid #dddddd;
}
.countdown {
	color: maroon;
	font-size: 9px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	color: #BBBBBB;
	margin: 0px;
	width: 5%;
	clear: none;
	background-color: #666666;
	border: 0px;
	vertical-align: middle;
}

.inputacomment {
	color: maroon;
	font-size: 9px;
	font-family: Verdana, Arial, Helvetica, sans-serif;
	margin: 0px;
	width: 70%;
	clear: none;
	border: 1px solid #dddddd;
}
a:link {
	color: #ffffff;	
}
a:active {
	color: #aaaa00;
}
a:hover {
	color: #aaaa00;
}

a:visited {
	color: #ffffff;
}

-->
</style>

<!-- ******** END OF CSS - THERE'S REALLY NOTHING MORE YOU SHOULD DO :-) *** -->

<script>
<!--clears default values from input boxes
function clearText(thefield){
if (thefield.defaultValue==thefield.value)
thefield.value = ""
}
//-->
</script>
<script language="javascript" type="text/javascript">

function adminwindow() 
{ 
window.open('commadmin.php?table=<?php echo $tablename; ?>','admin','width=400,height=400,resizable=yes,scrollbars=yes'); 
} 

</script>

<script language="javascript" type="text/javascript">
<!--from www.mediacollege.com-text countdown for input field
function limitText(limitField, limitCount, limitNum) {
	if (limitField.value.length > limitNum) {
		limitField.value = limitField.value.substring(0, limitNum);
	} else {
		limitCount.value = limitNum - limitField.value.length;
	}
}
//--> 
</script>
 
</head>
<body>

<div style="display: inline;"><center>
<form style="display: inline; margin: 0;" action="<?=$_SERVER['PHP_SELF']?>" method="post" >
<input type="text" name="name" value="<? echo($_SESSION['name']); ?> " size="14" maxlength="20" onFocus="clearText(this)" class="inputaname">
<input type="text" name="comment" value="comment" size="50" onFocus="clearText(this)" onKeyDown="limitText(this.form.comment,this.form.countdown,250);" onKeyUp="limitText(this.form.comment,this.form.countdown,250);" maxlength="250" class="inputacomment">
<input readonly type="text" name="countdown" value="250" class="countdown"> 
<input type="submit" name="addcomment" value="SEND" class="submita"/ >
</form></center></div>

<?
// Connect to the database server and select database
dbconnect();

// remove possible pain in the ass white space
$nametext = trim($_SESSION['name']);
$commenttext = trim($_POST['comment']);

if ($flags == 1) {

// check name - can't be the same as the site owners
if ($nametext == $display_name) {
echo('<h2>The name you used is reserved by the site owner. Please use another name.</h2>');
getcomments();
listcomments();
pad_bottom();
exit();} 
// check visitor isn't simply hitting submit button 
if ($nametext == 'name' or $commenttext == 'comment') {
echo ('<h2>Enter your name and a comment. Thanks!</h2>');
getcomments();
listcomments();
pad_bottom();
exit();}
// check that name and comment aren't blank
if ($nametext == '' or $commenttext == '') {
echo ('<h2>Enter your name and a comment. Thanks!</h2>');
getcomments();
listcomments();
pad_bottom();
exit();}
// admin is an invalid comment if not in combination with password
if ($nametext != "$admin_name" && $commenttext == 'admin') {
echo ('<h2>Enter your name and a comment. Thanks!</h2>');
getcomments();
listcomments();
pad_bottom();
exit();}
// check for admin login
if (($nametext == "$admin_name") && ($commenttext == "admin")) {
echo ('<h2><a href="javascript:adminwindow();">Click Here To Open The Admin Window</a></h2>');
getcomments();
listcomments();
pad_bottom();
exit();}

} else if ($flags == 0) {
	
// check name - can't be the same as the site owners
if ($nametext == $display_name) {
echo('<h2>The name you used is reserved by the site owner. Please use another name.</h2>');
getcomments();
listcomments_noflag();
pad_bottom();
exit();} 
// check visitor isn't simply hitting submit button 
if ($nametext == 'name' or $commenttext == 'comment') {
echo ('<h2>Enter your name and a comment. Thanks!</h2>');
getcomments();
listcomments_noflag();
pad_bottom();
exit();}
// check that name and comment aren't blank
if ($nametext == '' or $commenttext == '') {
echo ('<h2>Enter your name and a comment. Thanks!</h2>');
getcomments();
listcomments_noflag();
pad_bottom();
exit();}
// admin is an invalid comment if not in combination with password
if ($nametext != "$admin_name" && $commenttext == 'admin') {
echo ('<h2>Enter your name and a comment. Thanks!</h2>');
getcomments();
listcomments();
pad_bottom();
exit();}
// check for admin login
if (($nametext == "$admin_name") && ($commenttext == "admin")) {
echo ('<h2><a href="javascript:adminwindow();">Click Here To Open The Admin Window</a></h2>');
getcomments();
listcomments_noflag();
pad_bottom();
exit();}

}

// If a comment has been submitted,
// add it to the database.

if (isset($_POST['addcomment'])) {
$ip = $_SERVER['REMOTE_ADDR'];
$commenttext = check($_POST['comment']);
$commenttext = wordwrap($commenttext, 40, ' ', 1);
$sql = "INSERT INTO $tablename SET
name='$nametext',
comment='$commenttext',
date=NOW(),
ip='$ip' " ;
if (@mysql_query($sql)) {
echo('<h2>Your comment has been added, ' . $nametext . '.  Thank You!</h2>'); 
} else {
echo('<p>Error adding submitted comment: ' .
mysql_error() . '</p>');
}
}

if ($flags == 1) {
getcomments();
listcomments();
pad_bottom();
} else if ($flags == 0) {
getcomments();
listcomments_noflag();
pad_bottom();
}

?>
</body>
</html>

