<?

/*
Safermail for PHP 
Version 0.7 - Released August 6, 2005
Mike DeWolfe - mikedewolfe@gmail.com

*/

require ("safermailconfig.php");
$failed = 0;
$link = mysql_connect($dhhost, $dbuser, $dbpass)	or die("Could not connect : " . mysql_error());
mysql_select_db($dbname) or die("Could not select database");

if ($_SERVER['HTTP_REFERER']."" == "")
	{
	$failed = 1; // This should have come from a parent page.	
	}

if (htmlspecialchars($_GET['m']) != $_GET['m'])
	{
	$failed = 2; // Are special characters coming in?	
	}

if ($failed == 0)
	{
	$query = "SELECT status FROM SaferMailAllow WHERE LEFT('".htmlspecialchars($_SERVER['REMOTE_ADDR'])."', LENGTH(addr)) = addr";
	$status = "";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());	
	if ($row=mysql_fetch_array($result)) 
		{ 
		$status = $row["status"]; 
		} 
	if ($status == "block")
		{
		$failed = 3; // you've manually blocked this user
		}
	mysql_free_result($result);
	}

if ($failed == 0)
	{
	$query = "SELECT lastvisit FROM SaferMailVisits WHERE lastvisit = NOW() AND addr = '".htmlspecialchars($_SERVER['REMOTE_ADDR'])."'";
	$lastivisit = "";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());	
	if ($row=mysql_fetch_array($result)) 
		{ 
		$lastivisit = $row["lastvisit"]; 
		} 
	if ($lastivisit != "")
		{
		$failed = 4; // two visits within the same second? That's not right.
		}
	mysql_free_result($result);
	}

if ($failed == 0)
	{
	if (strpos($_SERVER['HTTP_USER_AGENT'], 'Mozilla') < 0)
		{
		$failed = 5;
		}
	}

if ($failed == 0)
	{
	$TheQuery = "UPDATE SaferMailVisits SET ";
	$TheQuery .= "lastvisit = NOW(), addr = '".htmlspecialchars($_SERVER['REMOTE_ADDR'])."'"; 
	mysql_query ($TheQuery);	

	$query = "SELECT `email` FROM `SaferMailAddresses` WHERE `key` = '".htmlspecialchars($_GET['m'])."'";
	$email = "";
	$result = mysql_query($query) or die("Query failed : " . mysql_error());	
	if ($row=mysql_fetch_array($result)) 
		{ 
		$email = "mailto:".$row["email"]; 
		$failed = 6;
		} 
	mysql_free_result($result);

	if ($email != "")
		{
		// track users -- optional
		$TheQuery = "INSERT INTO `SaferMailLog` (`addr`,`emailkey`,`referer`,`visitdate`) VALUES ('".htmlspecialchars($_SERVER['REMOTE_ADDR'])."','".htmlspecialchars($_GET['m'])."','".htmlspecialchars($_SERVER['HTTP_REFERER'])."',NOW())"; 
		mysql_query ($TheQuery);
		
		header('Location: '.$email);
		}
   }
else
	{
	// track users -- optional
	$TheQuery = "INSERT INTO `SaferMailLog` (`addr`,`emailkey`,`referer`,`visitdate`) VALUES ('".htmlspecialchars($_SERVER['REMOTE_ADDR'])."','".htmlspecialchars($_GET['m'])." : FAILED ".$failed."','".htmlspecialchars($_SERVER['HTTP_REFERER'])."',NOW())"; 
	mysql_query ($TheQuery);
?>
<TITLE> Doh! </TITLE>
<BODY onLoad="javascript:alert('Sorry, Charlie');">
</BODY>
</HTML>
<?
	}

mysql_close ($link);
?>