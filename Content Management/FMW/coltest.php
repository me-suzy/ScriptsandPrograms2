<?php
require('db_connect.php');
$dbQuery = "SELECT rights "; 

$dbQuery .= "FROM users WHERE username = ('$_SESSION[username]')"; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");
while($row = mysql_fetch_array($result))

{ 
$permission = "$row[rights]";      // get access level
    $_SESSION["perm"] = "$permission";      // make session variables 


}
session_start();
if (($_SESSION['perm'] < "5")) {
echo "<font color='#$col_text'>"; die ('You are not authorised to access this section');
}





$dbQuery = "SELECT * "; 
$dbQuery .= "FROM admin WHERE username = 'admin' "; 
$result = mysql_query($dbQuery) or die("Couldn't get file list");



while($row = mysql_fetch_array($result))
{ 

?> 

<HEAD>
<TITLE>Configuration test page</TITLE>
</HEAD>


<br><br> 
<?php
$link = $row["col_link"];
$text = $row["col_text"];
$background = $row["col_back"];
$logo_pos = $row["logo_pos"];

$col_back = $row['col_back'];
$col_text = $row['col_text'];
$col_link = $row['col_link'];
$col_table_border = $row['col_table_border'];
$col_table_border_2 = $row['col_table_border_2'];
$col_table_row = $row['col_table_row'];
$col_table_row2 = $row['col_table_row2'];
$col_table_header = $row['col_table_header'];
$col_table_header_2 = $row['col_table_header_2'];
$col_table_row_text = $row['col_table_row_text'];
$col_table_header_text = $row['col_table_header_text'];
$currency = $row['currency'];
$logo_pos = $row['logo_pos'];
$texture = $row['texture'];

if ("$col_text" == "$col_back") {
?>
<font color='#ffffff'>
<center> The background and main text are set the same, please click <a href="configuration.php"><font color="ffffff"> HERE  </font></a> to return to configuration page.	
<br><br>
<font color='#000000'>
<center> The background and main text are set the same, please click <a href="configuration.php"><font color="000000"> HERE  </font></a> to return to configuration page.
<br><br>

<?php
}

?>


<HTML>
<BODY>
<body  background="textures/<?php echo "$texture" ?>.jpg">
		


<body bgcolor="#<?php echo "$col_back"; ?>">

<p align="<?php echo "$logo_pos" ?>"><img src="images/logo.gif"> 
<center>
<?php echo "<font color='#$col_text'>Logo as seen "; echo "$logo_pos"; echo " aligned";
?> <br><br> <?php
echo "<center>"; echo "<font color='#$col_text'>This is main text colour"; 


?>

  </center>
</div>

</body>

<br>




<table align="center" border="2" cellpadding="1" cellspacing="0" bordercolorlight="#<?php echo "$col_table_border" ?>" bordercolordark="#<?php echo "$col_table_border_2" ?>"  width="90%">

<tr>

<td width="16%" bgcolor="#<?php echo "$col_table_header" ?>" height="21"> 

<p style="margin-left: 10"><b><font size="2" face="Verdana" color="#<?php echo "$col_table_header_text" ?>"> 

<center> 'Table Header' text on 'Table Header' background</font></b></td>


</tr> 


<tr>

<td width="16%" bgcolor="#<?php echo "$col_table_header_2" ?>" height="21"> 

<p style="margin-left: 10"><b><font size="2" face="Verdana" color="#<?php echo "$col_table_header_text" ?>"> 

<center> 'Table Header' text on 'Table Header 2' background</font></b></td>


</tr> 



<tr> 
<td width="16%" bgcolor="#<?php echo "$col_table_row" ?>" height="21"> 

<p style="margin-left: 10; margin-right: 10"> 

<font face="Verdana" size="2"> 

<center> <?php echo "<font color='#$col_table_row_text'> This is 'Table Row' text on 'Table Row' background";
?>
</font> 
</tr> 

<tr> 
<td width="16%" bgcolor="#<?php echo "$col_table_row2" ?>" height="21"> 

<p style="margin-left: 10; margin-right: 10"> 

<font face="Verdana" size="2"> 

<center> <?php echo "<font color='#$col_table_row_text'> This is 'Table Row' text on 'Table Row 2' background";
?>
</font> 
</tr> 


<tr> 
<td width="16%" bgcolor="#<?php echo "$col_table_row" ?>" height="21"> 

<p style="margin-left: 10; margin-right: 10"> 

<font face="Verdana" size="2"> 

<center> <?php echo "<font color='#$col_table_row_text'> This is 'Table Row' text on 'Table Row' background";
?>
</font> 
</tr> 


<tr> 
<td width="16%" bgcolor="#<?php echo "$col_table_row2" ?>" height="21"> 

<p style="margin-left: 10; margin-right: 10"> 

<font face="Verdana" size="2"> 

<center> <?php echo "<font color='#$col_table_row_text'> This is 'Table Row' text on 'Table Row 2' background";
?>
</font> 
</tr> 



<?php
echo "</table>"; 

echo "<center>"; echo "<font color='#$col_text'>The table border is made from 'Table Border Outline 1 and Outline 2'"; 

?>
<br><br>

<a href="configuration.php"><font color="<?php echo "$col_link" ?>">Back To Configuration</font></a>

<?php
}
?>
</BODY> 
</HTML>