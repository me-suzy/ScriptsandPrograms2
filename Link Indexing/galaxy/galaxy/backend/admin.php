<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<title>Galaxy Link Database</title>
<meta http-equiv="Content-Type" content="text/html; charset=iso-8859-1" />
<link href="../galaxy.css" rel="stylesheet" type="text/css" />
</head>
<body>
<?php

include('../functions.php');
$options = array();
$zero = 0;
$options[1] = "where switch = '$zero'";
$sql = "select * from linkInformation ";
if(empty($_GET[commit])){
if (!empty($_GET[choice])){
$arrayNom = $_GET[choice];
if($arrayNom == "1"){
print "$backHead";
print '<p><span class="topRow">Use this form to make chosen links viewable</span></p>';
$sql .= "where switch = '$zero'";
}
else{
print "$backHead";
print '<p><span class="topRow">Use this form to hide links from view without erasing them</span></p>';
$sql .= "where switch = '1'";
}

}
$sqlRes = mysql_query($sql, $conn) or die (mysql_error());
if(mysql_num_rows($sqlRes)<1){Print '<p><span class="error">There are no links for this operation to be applied to</span></p>';}
else{
print "<form action=\"admin.php?choice=$_GET[choice]&commit=1\" method=\"post\">";
print '<select name="jump">';
while($sqlArray = mysql_fetch_array($sqlRes)){
        $id = $sqlArray['id'];
		$user = $sqlArray['user'];
		$link = $sqlArray['link'];
		$pageName = $sqlArray['pageName'];
		$description = $sqlArray['description'];
		$switch = $sqlArray['switch'];
        print " <option value=\"$id\">$link</option>";
		}
		print'</select><br /><input name="submit" type="submit" value="submit"/>';
		print'</form>';
		}}
		else{
		$id = $_POST[jump];
        $var = $_GET[choice];
		if($var == "2"){
		$var = "0";}
        $updateSql =  "update linkInformation set switch = $var where id = '$id' ";
        $updateSqlRes = mysql_query($updateSql, $conn) or die($updateSql . mysql_error());
        print "$backHead";
		print"<span class=\"conf\">You have changed the permissions on this link<br />Please select another option from the left hand side.</span>";
         } 

?>
</td></tr></table>
</body>
</html>

