<?php 
//session_start();


$strTable1="";
if ($_SESSION['Admin'] == "Y")
{
	$strTable1.= "<TR>";
	$strTable1.= "<TD nowrap>&nbsp;<A HREF=\"../../Admin/Pages/List.php\">Page List</A>&nbsp;</TD>";
	$strTable1.="<TD nowrap>&nbsp;<A HREF=\"../../Admin/Users/List.php\">User List</A>&nbsp;</TD>";
	$strTable1.="<TD nowrap>&nbsp;<A HREF=\"../../Admin/Images/ListImages.php\">File Management</A>&nbsp;</TD>";
	$strTable1.= "<TD nowrap >&nbsp;<A HREF=\"../../Logout.php\">Logout</A>&nbsp;</TD>";
	$strTable1.= "</TR>";

}
print "<TABLE border=0 width=\"20%\" VALIGN=TOP>";
print $strTable1;
print "</TABLE>";

?>
