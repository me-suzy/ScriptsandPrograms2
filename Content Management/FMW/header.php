<?php
require('db_connect.php');


$dbQuery = "SELECT * "; 

$dbQuery .= "FROM admin "; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");
while($row = mysql_fetch_array($result))

{ 



$col_back = $row["col_back"];
$col_text = $row["col_text"];
$col_link = $row["col_link"];
$col_table_row = $row["col_table_row"];
$col_table_row2 = $row["col_table_row2"];
$col_table_header = $row["col_table_header"];
$col_table_header_2 = $row["col_table_header_2"];
$col_table_border = $row["col_table_border"];
$col_table_border_2 = $row["col_table_border_2"];
$col_table_row_text= $row["col_table_row_text"];
$col_table_header_text = $row["col_table_header_text"];
$currency = $row["currency"];
$logo_pos = $row["logo_pos"];
$texture = $row["texture"];
$admin_message = $row["admin_message"];
$title_message = $row["title_message"];
$theme_col = $row["theme_col"];
$site_url = $row["site_url"];
$admin_email = $row["admin_email"];



?>

<html>
<head>
<body  background="textures/<?php echo "$texture" ?>.jpg">
<body bgcolor="#<?php echo "$col_back"; ?>">
<p align="<?php echo "$logo_pos" ?>"> <img src="images/logo.gif"> 
<br><br>
<center>
<H4>
<?php

if ($logged_in == 0) {
echo "<center>"; echo "<font color='#$col_text'>Welcome Guest"; 
}
else{
echo "<center>"; echo "<font color='#$col_text'>Welcome "; echo (ucfirst("$_SESSION[username]"));?> &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp <? echo date(" d M Y");
}
?>
<br>
<?php


$dbQuery = "SELECT rights "; 

$dbQuery .= "FROM users WHERE username = ('$_SESSION[username]')"; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");
while($row = mysql_fetch_array($result))
{
 
 $permission = "$row[rights]";      // get access level
    $_SESSION["perm"] = "$permission";      // make session variables 


session_start();
if (($_SESSION['perm'] >= "3")) { 
?>	<a href="admin.php"><font color="#<?php echo $col_link ?>">Administration Screen</font></a></font>
<?php
}
}
?>
  
        <table border="1" cellpadding="0" cellspacing="0" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>" width="100%" id="topborder" height="1">
          <tr>
            <td width="100%" height="1" bgcolor="#<?php echo "$col_table_header" ?>">
            
          </tr>
        </table>
        </center>



<a href="login.php"><font color="#<?php echo $col_link ?>">Login</font></a>&nbsp;&nbsp;&nbsp;&nbsp
</font>
<a href="editprofile.php"><font color="#<?php echo $col_link ?>">Edit Profile</font></a>&nbsp;&nbsp;&nbsp;&nbsp
</font>
<a href="team.php"><font color="#<?php echo $col_link ?>">Team</font></a>&nbsp;&nbsp;&nbsp;&nbsp
</font>
<a href="viewfixtures.php"><font color="#<?php echo $col_link ?>">Fixtures</font></a>&nbsp;&nbsp;&nbsp;&nbsp
</font>
<a href="leaguetable.php"><font color="#<?php echo $col_link ?>">League Table</font></a>&nbsp;&nbsp;&nbsp;&nbsp
<a href="viewresults.php"><font color="#<?php echo $col_link ?>">View Results</font></a>&nbsp;&nbsp;&nbsp;&nbsp
</font>
<a href="main.php"><font color="#<?php echo $col_link ?>">Main Page</font></a>&nbsp;&nbsp;&nbsp;&nbsp
</font>


<a href="logout.php"><font color="#<?php echo $col_link ?>">Logout</font></a>&nbsp;&nbsp;&nbsp;&nbsp
</font>


<table border="1" cellpadding="0" cellspacing="0" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>" width="100%" id="topborder" height="1">
          <tr>
            <td width="100%" height="1" bgcolor="#<?php echo "$col_table_header" ?>">
            
          </tr>
        </table>


 </H4>
<?php

}
?>
</head>

</html>