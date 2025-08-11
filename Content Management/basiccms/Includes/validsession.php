<?php
if (!isset($_SESSION['UserID']))
{
	$strUser="";
}
else
{
	$strUser=$_SESSION['UserID'];
}
if ($strUser== "")
{	print "<HTML><HEAD><TITLE> CMS Session Expired </TITLE>";
	print "<SCRIPT LANGUAGE=\"JAVASCRIPT\">\n" ;
	print "<!--\n" ;
	print "function onLoad1()\n";
	print "{\n\t";
	print  "alert('Your session is expired. Please logon again')\n\t" ;
	
	print  "setTimeout(\"parent.location='" . $strRootpath . "Logon.php'\",1000)\n" ;
	print "}";
	print "// -->\n" ;
	print "</SCRIPT>\n</HEAD><BODY onLoad=\"JavaScript:onLoad1();\"></BODY></HTML>";
end;
}
?>