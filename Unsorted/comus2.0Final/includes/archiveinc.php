<?

include_once($DOCUMENT_ROOT . "/includes/config.inc.php");

if ($cjultra == Yes){
   $cjstring = $cjstring;
   $cjstring2 = $cjstring2;
}else{
   $cjstring = "";
   $cjstring2 = "";
}

/* GET AND DISPLAY ALL POSTS ACCORDING TO CATEGORY SELECTED */

   $query = "SELECT * FROM tblCategories ORDER BY Category";
   $result = mysql_query ($query) or die ("Query failed");

if ($result)
{
	while ($r = mysql_fetch_array($result))
	{
	$Category = $r["Category"];
	echo "<b><a href=\"archive.php?choice=$Category\">$Category</a> - </b>";
   }
}


/* See if they selected anything */

if ($choice)
{
	$query = "select * from tblTgp WHERE category='$choice' AND accept='yes' ORDER BY id DESC LIMIT $lim";
	$result = mysql_query ($query) or die ("Query failed");
	if ($result)
	{
	echo "<table width=95% border=1 cellpadding=10>
                <tr bgcolor=$bgcolor valign=top> 
                  <td height=100%> 
                    <div align=left> 
                      <table width=100% border=0 cellspacing=0 cellpadding=0>
                        <tr>";
while ($r = mysql_fetch_array($result)) { 

$cat = $r["category"]; 
$url = $r["url"]; 
$desc = $r["description"]; 
$date = $r["date"]; 

echo "<a href=\"$cjstring$url$cjstring2\"><b>$cat</b></font>&nbsp&nbsp<font color=#0033CC>$desc</font></a><BR>"; 
      } 
   }
echo "</table>                    
            </div>
               </td>
              </tr>
             </table>"; 
} else { 
echo "<p>&nbsp;</p><p>&nbsp;</p><font color=red><h3><b>Please select the catagory above that you would like to view.</b></h3></font>"; 
} 
?>