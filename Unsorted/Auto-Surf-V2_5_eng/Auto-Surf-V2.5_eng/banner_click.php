<?php
	include("var.inc.php");
	$conn = @mysql_connect($dbserver,$dbuser,$dbpass);
	if (!$conn) 
		{
			die("Sorry, Datenbank nicht gefunden !");
		}
	mysql_select_db($dbname,$conn);
	
		{
			
			$ip = $userid;
			$zeit = time ();
			$nichtmehrgueltig = $zeit-$stehenlassen;
			$query = "DELETE FROM demo_a_iptestb WHERE timefeld <= ".$nichtmehrgueltig;
			mysql_query($query,$conn);
			$query = "SELECT * FROM demo_a_iptestb WHERE ip = '".$ip."'";
			$result = mysql_query($query,$conn);
			$rows = mysql_num_rows($result);
			if ($rows >= 1)
				{

require('./prepend.inc.php');

banner_clickb();
}
			else
				{
	
require('./prepend.inc.php');

banner_click();
					$query = "INSERT INTO demo_a_iptestb VALUES (\"$ip\", $zeit)";
					mysql_query($query,$conn);			
				}
				
		}

				
mysql_close($conn);
?>