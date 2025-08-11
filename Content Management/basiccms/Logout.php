<?php  
session_start(); 

	$_SESSION["UserID"]="";
	$_SESSION["UserName"]="";
	$_SESSION["Admin"]="";
//	Destroy session data
	session_destroy();
	print "<HTML><HEAD><TITLE> CMS Logged out </TITLE>\n";
	print "<SCRIPT LANGUAGE=\"JAVASCRIPT\">\n";
	print "<!--\n" ;
	print "function onLoad1()\n";	
	print "{\t\n";
	print  "setTimeout(\"parent.location='Logon.php'\",10)\n";
	
	print "}\n";
	print "// -->\n";
	print "</SCRIPT>\n";
	include ("Includes/Styles.php");
	print "</HEAD>\n<BODY onLoad=\"JavaScript:onLoad1();\">\n";


?>
