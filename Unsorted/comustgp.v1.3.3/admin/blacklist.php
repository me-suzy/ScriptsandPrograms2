<?
#######################################
###         ComusTGP version 1.3.3  ###
###         nibbi@nibbi.net         ###
###         Copyright 2002          ###
#######################################
?>
<html>
<head>
<title>Comus TGP BlackList Page</title>
</head>
<body bgcolor="#FFFFFF">
<?
// Include Configuration file
include($DOCUMENT_ROOT . "/includes/config.inc.php");

/* Delete From Blacklist */
   
   if (isset($zap)) {

      $Query = "DELETE FROM tblBlacklist WHERE id='$id'";
         $result = mysql_query($Query, $conn);
   }
   
/* ADD SOMEONE TO THE BLACKLIST  */

if (isset($BlackSubmit)) {
   mysql_query("INSERT into tblBlacklist (email) VALUES ('$BlackEmail')");
         $message2 = "$BlackEmail has been added to Blacklist";
}

echo "<center><a href=\"index.php\"><b><font size=-1 face=arial>Return to main page</font></b></a></center>
<br>
<center><table width=650 border=0 cellspacing=2 cellpadding=2 align=center>
  <tr> 
    <td colspan=2 bgcolor=#$bgcolor> 
      <div align=center><b><font face=Arial size=-1 color=white>Add To Blacklist</font></b></div>
    </td>
  </tr>
</table></center>
<form method=post action=blacklist.php><center><br><font color=red><h3>$message2</h3></font> 
  <center><table width=600 border=0 cellspacing=3 cellpadding=3>
    <tr>
      <td>Email to Blacklist: <input type=text name=\"BlackEmail\">
        <input type=\"submit\" name=\"BlackSubmit\" value=\"Black List 'Em\">
      </td>
    </tr>
  </table></center>
      
  </form>";

/* View all of Blacklist */

   $query = "SELECT * FROM tblBlacklist order by id DESC";
   $result = mysql_query ($query)
        or die ("Query failed");
   
   if ($result) {
      echo "<table width=400 border=0 cellspacing=1 cellpadding=1 align=center>
<tr bgcolor=#$bgcolor>";

   while ($r = mysql_fetch_array($result)) { 

   $id = $r["id"];
   $email1 = $r["email"];

   echo "<tr> 
    <td width=61> 
      <div align=left><a href=\"blacklist.php?zap=yes&id=$id\">Delete</a></div>
    </td>
    <td colspan=2>$email1</td>
    <td width=90>&nbsp;</td>
      </tr>";
      
      } //end of while loop
   echo "</table>";
   
   }  // end of result


?>
</body>
</html>
