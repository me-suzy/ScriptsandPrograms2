<?
#######################################
###         ComusTGP version 1.3.3  ###
###         nibbi@nibbi.net         ###
###         Copyright 2002          ###
#######################################
?>
<body bgcolor="#FFFFFF" topmargin="0" onMouseOver="window.status=' ';return true" onMouseOut="self.status='';return true">
<?php

// Include Configuration file
include($DOCUMENT_ROOT . "/includes/config.inc.php"); 

if ($cjultra == Yes){
   $cjstring = $cjstring;
   $cjstring2 = $cjstring2;	
}else{
   $cjstring = "";
   $cjstring2 = "";
}

// See if they selected anything

$query = "select * from tblTgp WHERE date <='$dnow' AND date >= '$then' AND accept='yes' ORDER BY date DESC, vote ASC";

$result = mysql_query ($query)
        or die ("Query failed");

if ($result) {  
echo "<table width=100% border=0 cellpadding=10>
                <tr bgcolor=$bgcolor valign=top> 
                  <td height=100%> 
                    <div align=left> 
                      <table width=100% border=0 cellspacing=0 cellpadding=0>";
while ($r = mysql_fetch_array($result)) { 

$id = $r["id"];
$cat = $r["category"]; 
$url = $r["url"]; 
$desc = $r["description"]; 
$date = $r["date"]; 

echo "<tr><td><font size=2 face=Verdana, Arial, Helvetica, sans-serif><a href=\"$cjstring$url$cjstring2\"><b>$cat</b></a></font></td><td><font size=2 face=Verdana, Arial, Helvetica, sans-serif color=#0033CC><a href=\"$cjstring$url$cjstring2\">$desc</font></a></td>"; 
} 
echo "</table>                    
            </div>
               </td>
              </tr>
             </table>"; 
} else { 

} 
?>
</body>

