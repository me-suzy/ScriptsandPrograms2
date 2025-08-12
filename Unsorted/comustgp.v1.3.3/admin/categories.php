<?
#######################################
###         ComusTGP version 1.3.3  ###
###         nibbi@nibbi.net         ###
###         Copyright 2002          ###
#######################################
?>
<html>
<head>
<title>Comus TGP Category Edit Page</title>
</head>
<body bgcolor="#FFFFFF">
<?
// Include Configuration file
include($DOCUMENT_ROOT . "/includes/config.inc.php");


  
  /* Delete a Category */
  
if (isset($zap)) {

  $Query = "DELETE FROM tblCategories WHERE id='$id'";
     $result = mysql_query($Query, $conn);
}

/* ADD a Category */

if (isset($AddCategory)) {
   mysql_query("INSERT into tblCategories (category) VALUES ('$category')");
         $message2 = "$category has been added to Categories";
}

/* Display Add Category Form */
echo "<table width=650 border=0 cellspacing=2 cellpadding=2 align=center>
  <tr align=center valign=middle> 
      <div align=center>
<form method=post action=categories.php><center><br><font color=red><h2>$message2</h2></font><br>  
  <table width=600 border=0 cellspacing=3 cellpadding=3>
    <tr>
      <td><center>Add New Category: <input type=text name=\"category\"> <input type=\"submit\" name=\"AddCategory\" value=\"Add Category\"></center></td>
    </tr>
  </table>
      
  </form><br></center>";
  
/* View all Categories */

   $query = "SELECT * FROM tblCategories order by category";
   $result = mysql_query ($query)
        or die ("Query failed");
   
   if ($result) {
      echo "<center><a href=\"index.php\"><b><font size=-1 face=arial>Return to main page</font></b></a></center><br><table width=400 border=0 cellspacing=1 cellpadding=1 align=center>
  <tr bgcolor=#$bgcolor> 
    <td colspan=4> 
      <div align=center><font face=Arial size=-1><b><font color=white>All Categories</font></b></font></div>
    </td>
  </tr>";

   while ($r = mysql_fetch_array($result)) { 

   $id = $r["id"];
   $cat = $r["Category"];

   echo "<tr> 
    <td width=61> 
      <div align=left><a href=\"categories.php?zap=yes&id=$id\">Delete</a></div>
    </td>
    <td colspan=2>$cat</td>
    <td width=90>&nbsp;</td>
  </tr>";
      
      } //end of while loop
   echo "</table>";
   
   }  // end of result




?>

</body>
</html>
