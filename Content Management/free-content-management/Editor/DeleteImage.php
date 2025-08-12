<?php
session_start(); 
include("../Includes/PortalConection.php");
include("../Includes/Database.php");

$strRootpath= "../";
include_once ("../Includes/validsession.php");

$strFileName= QuerySafeString($_REQUEST["txtFile"]);

$strAction= QuerySafeString($_REQUEST["txtAction"]);
$strErrorMessages="";
if ($strAction== "DEL")
{
// check to see if file exists
  // if so, erase it 
  $strTemp=ImageUploadPath.$strFileName;
  if (file_exists($strTemp))
  {
	  unlink ($strTemp) or die ("Could not delete file");
	{	Redirect("ListImages.php");}
  }
}
print "<HTML><HEAD>";
include ("../Includes/Styles.php");
print "</HEAD><BODY>";
print "<A HREF=\"ListImages.php\"> Back to List</A>";
print "</BODY></HTML>";
?>
