<?php

include("Includes/PortalConection.php");
include("Includes/Database.php");

$strTable="";
$conclass =new DataBase();
$strsql = "SELECT Title as TITLE ,HyperLink as URL FROM cms_t_menus";
$strsql .= " WHERE Active='Y' ORDER BY DisplaySequence ASC";
$strTempResults="";
$rst= $conclass->Execute ($strsql,$strTempResults);
$strFirsepage="firstpage.php";
if ($rst) {	
	$myrow = mysql_fetch_array($rst,MYSQL_ASSOC);
	$strFirsepage=$myrow["URL"];
}
print "<HTML><HEAD><TITLE>";
print PortalTitle;

?>


</TITLE>
</HEAD>
<BODY>
   <iframe src="PublicMenu.php" name="Menu" width="19%" height="97%">
      <div style="text-align: center;">
        [Your Browser Does Not Handle IFRAMES, Sorry!] 
      </div>
    </iframe>
       <iframe src="<?php print $strFirsepage; ?>" name="Pages" width="79%" height="97%">
      <div style="text-align: center;">
        [Your Browser Does Not Handle IFRAMES, Sorry!] 
      </div>
    </iframe>
    <p align="right"><small><a href="http://www.free-content-management.com" target="_blank" title="free-content-management">powered by free-content-management</a></small></p>
</BODY>
</HTML>
