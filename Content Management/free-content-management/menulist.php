<?php 
session_start();

include("Includes/PortalConection.php");
include("Includes/Database.php");

$strRootpath= "";
include_once ("Includes/validsession.php");

print "<HTML><HEAD><TITLE>Menu List </TITLE>";
include ("Includes/Styles.php");
?>
<p><img src="Includes/admin.gif" width="36" height="36" alt="Instant-CMS Admin">
<br><strong>CMS Admin</strong></p>
<?
print "</HEAD>";


$strTable="";
if ($_SESSION['Admin'] == "Y")
{
	$strTable.= "<TR ><TD ><A target=\"Pages\" HREF=\"Admin/Menus/List.php\">Menu List</A></TD></TR>";
	$strTable.="<TR ><TD ><A target=\"Pages\" HREF=\"Admin/Users/List.php\">User List</A></TD></TR>";
	$strTable.="<TR ><TD ><A target=\"Pages\" HREF=\"UploadFilesHtml.php\">Upload HTML Files</A></TD></TR>";
	$strTable.= "<TR ><TD ><A target=\"Pages\" HREF=\"UploadFilesImage.php\">Upload Image Files</A></TD></TR>";

	$strTable.= "<TR ><TD ><A target=\"Pages\" HREF=\"Editor/ListFiles.php\">List Files</A></TD></TR>";
	$strTable.= "<TR ><TD ><A target=\"Pages\" HREF=\"Editor/ListImages.php\">List Images</A></TD></TR>";
	$strTable.= "<TR ><TD ><A target=\"\" HREF=\"Logout.php\">Logout</A></TD>";
	$strTable.= "<TR ><TD ><br><A href='http://www.free-content-management.com' target='_blank'><small>Free Content Management</small></A></TD></TR>";
}
print "<body style='background-color: Silver;'>";
print "<FORM action=\"\" method=POST id=frmForm1 name=frmForm1>";
print "<TABLE border=0 width=\"100%\">";
print $strTable;
print "</TABLE></FORM></BODY></HTML>";
?>
