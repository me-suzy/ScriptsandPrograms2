<?
##########################################
###         ComusTGP version 1.3.3     ###
###         nibbi@nibbi.net            ###
###         Copyright 2002             ###
##########################################
?>
<html>
<head><title>Comus TGP Gallery Review Page</title></head>
<BODY bgcolor="#FFFFFF">
<form method="post" action="admin.php">
<?
// Include Configuration file
include($DOCUMENT_ROOT . "/includes/config.inc.php");

/* Update database with changes you might have made */
if (isset($refresh)) {

      if ($accept) {
         foreach ($accept as $key => $value) {
            $Query = "UPDATE tblTgp SET accept='$value', category = '$category[$key]', description = '$newdescription[$key]', newpost='no', vote = '$vote[$key]' WHERE id = $key";       
            $result = mysql_query($Query, $conn);
            // echo $Query . "<BR>";
         }
      }
      
   }
/* GET AND DISPLAY NEW POSTS */
   $query = "SELECT * FROM tblTgp WHERE newpost='yes' order by date";
   $result = mysql_query ($query)
        or die ("Query failed");

if ($result) {
echo "<center><a href=\"index.html\"><b><font size=-1 face=arial>Return to main page</font></b></a></center><br><table width=100% border=0 cellspacing=0 cellpadding=0 align=center>
    <tr valign=top> 
      <td height=32> 
        <table width=100% cellpadding=0 cellspacing=0 border=1>
          <tr bgcolor=BLACK> 
            <td width=280 bgcolor=#0099CC><font face=Arial size=-1 color=WHITE> 
              <b>Link</b></font></td>

            <td width=25 bgcolor=#0099CC><font face=Arial size=-1 color=WHITE> 
              <b>Recip</b></font></td>
            <td width=25 bgcolor=#0099CC><font face=Arial size=-1 color=WHITE> 
              <b>Yes</b></font></td>
            <td width=26 bgcolor=#0099CC><font face=Arial size=-1 color=WHITE> 
              <b>No</b></font></td>
            <td width=131 bgcolor=#0099CC><font face=Arial size=-1 color=WHITE> 
              <b>Category</b></font></td>
            <td width=176 bgcolor=#0099CC><font face=Arial size=-1 color=WHITE> 
              <b>Desc</b></font></td>
         <td width=26 bgcolor=#0099CC><font face=Arial size=-1 color=WHITE> 
            <b>Vote</b></font></td>
         <tr><td height=10 colspan=7 bgcolor=#CCCCCC><font color=red>$message</font>&nbsp;</td></tr>

          </tr>";

while ($r = mysql_fetch_array($result)) {
   $id = $r["id"];
   $nickname = $r["nickname"]; 
   $recip = $r["recip"];
   $url = $r["url"]; 
   $email = $r["email"];
   $category = $r["category"];
   $description = $r["description"];
   $vote = $r["vote"];
   $date = $f["date"];    

echo "<tr bgcolor=#CCCCCC>
         <input type=hidden name=\"tempid\" value=\"$id\">
            <td><font face=Verdana, Arial, Helvetica, sans-serif size=1><a href=\"ck.gallery.php?send=$url\" target=\"check\">$url</a></td>

         <td width=25> 
              $recip
            </td>
            <td width=25> 
              <input type=radio name=accept[$id] value=\"yes\">
            </td>
            <td width=26> 
              <input type=radio name=accept[$id] value=\"no\">
            </td>
         <td width=100>            
                     <select name=category[$id]>
                     <option selected>$category";
   $query2 = "SELECT * FROM tblCategories ORDER BY Category";
   $result2 = mysql_query ($query2)
        or die ("Query failed");

   if ($result) {

   while ($r = mysql_fetch_array($result2)) { 

   $Category = $r["Category"];
                              
      echo"<option>$Category";

      }
   } 
echo"</select> 
            </td>
            <td width=200> 
              <input type=text size=35 name=\"newdescription[$id]\" value=\"$description\">
            </td>
         <td width=50> 
         <select name=\"vote[$id]\">
         <option value=\"1\">1</option>
         <option value=\"2\">2</option>
         <option value=\"3\">3</option>
         <option value=\"4\">4</option>
         <option value=\"5\" selected>5</option>
         <option value=\"6\">6</option>
         <option value=\"7\">7</option>
         <option value=\"8\">8</option>
         <option value=\"9\">9</option>
         <option value=\"10\">10</option>
         </select>
            </td>
          </tr>"; 
} 
echo "<tr> 
         <td colspan=7> 
             <div align=center>
                <input type=\"submit\" name=\"refresh\" value=\"Refresh Update\">
              </div>
            </td>
          </tr>
        </table>
      </td>
    </tr>
  </table>"; 

} else { 
echo "No data."; 
}
?>
      </form>

   </body>
</html>
