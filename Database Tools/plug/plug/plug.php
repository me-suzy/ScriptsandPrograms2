<center><body bgcolor="#EEEEF9"><?php
include("config.php");
?>

<?php


$ip = $_SERVER['REMOTE_ADDR'];
$query = mysql_query("SELECT * FROM banned WHERE ip='$ip'");
$select_banned = mysql_num_rows($query);
if($select_banned == 1) {
die("You have been banned from this Plug-Board.");
exit;
}


if($action=="plug"){

$query = "SELECT id FROM pb WHERE button='$button'";
$result = mysql_query($query);
$plug_two = mysql_num_rows($result);


// See if the person has plugged more than three times (BETA TEST) - If you have any problems with this please contact us

$query = "SELECT ip FROM pb WHERE ip='$ip'";
$result = mysql_query($query);
$flood = mysql_num_rows($result);
if($flood == 3) {			
die(" <font size='2' face='$face'>You may only plug 3 times on this Plug-Board. <br><a href='http://$website/plug.php'>Go Back</a></font>"); }



// See if the button is already on the Plug-Board

if($plug_two != 0) {			
die("<font size='2' face='$face'>That button is already on the Plug-Board.<br><a href='http://$website/plug.php'>Go Back</a></font>"); }


// See if all fields have been filled
if($url == "" || $url == "http://" ||  $button == "" || $button == "http://") { die("<font size='2' face='$face'>You forgot to fill in one of the fields!<br><a href='http://$website/plug.php'>Go Back</a> </font>"); }

// See if Button is broken 
$button_check = @getimagesize($button);
if (!$button_check) {
die("<font size='2' face='$face'>The button you just submitted is broken.<br><a href='http://$website/plug.php'>Go Back</a> </font>"); }


// Adding the Plug to the Database
$ip = $_SERVER['REMOTE_ADDR']; 
$url = htmlentities(strip_tags($url)); 
$button = htmlentities(strip_tags($button)); 

include("banned.php");


		$query = "INSERT INTO pb VALUES ('','$url','$button','$ip')";
		mysql_query($query); 


    $query = "SELECT id FROM pb ORDER BY id DESC LIMIT $maxdata";
		$result = mysql_query($query); 
		
		while($this = mysql_fetch_array($result)) { 
			$list[] = $this[id]; 
		}
		$maxID = $maxdata - 1;
		$query = "DELETE FROM pb WHERE id < '$list[$maxID]'";
                                mysql_query($query);


             
		}


// Show the Plugs

$query = "SELECT * FROM pb ORDER BY id DESC LIMIT $maxdata";
$result = mysql_query($query);
$num = mysql_num_rows($result);

for ($i=0;$i<$num;$i++)
{
	$row = mysql_fetch_array($result);
	extract($row);
	echo "<a href='$url' target='_blank'><img src='$button' border='0' width='88' height='31' /></a>\n";
}






echo '<CENTER><FORM ACTION="plug.php?action=plug" METHOD="post" NAME="plug">
<font size="2" face="Tahoma">Button URL:<br>
</font>   
<INPUT NAME="button" size="20" style="font-family: Tahoma; font-size: 10pt; border-style: solid; border-width: 1; padding-left: 4; padding-right: 4; padding-top: 1; padding-bottom: 1; background-color:#EEEEF9" value="http://"><BR>
<font face="Tahoma" size="2">Website URL:<br>
</font> 
<INPUT NAME="url" size="20" style="font-family: Tahoma; font-size: 10pt; border-style: solid; border-width: 1; padding-left: 4; padding-right: 4; padding-top: 1; padding-bottom: 1; background-color:#EEEEF9" value="http://"><BR>
<INPUT TYPE=submit VALUE=" Plug! " style="font-family: Tahoma; font-size: 10pt; border-style: solid; border-width: 1; padding-left: 4; padding-right: 4; padding-top: 1; padding-bottom: 1;  size=40; background-color:#EEEEF9"> 
</FORM></CENTER>';
?>  