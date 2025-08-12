<?php
include ("Includes/PortalConection.php");
include ("Includes/Database.php");

print "<HTML><HEAD><TITLE>Menu List</TITLE>";
include ("Includes/Styles.php");

print "</HEAD>";

$strTable="";
$strsql = "SELECT Title as TITLE,HyperLink as URL FROM cms_t_menus" ;
$strsql .= " WHERE Active='Y' ORDER BY DisplaySequence ASC";

$strTable=TableMenuList($strsql,"","","","");

?>

<BODY>

<FORM action="" method=POST id=frmForm1 name=frmForm1>
<TABLE border=0 width="100%">


<?php 
print $strTable;
?>
	
</TABLE>
</FORM>

</BODY>
</HTML>
