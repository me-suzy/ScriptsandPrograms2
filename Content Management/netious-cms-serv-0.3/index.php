<?

/* **************************************************************
Inclusion of the 'include' file containing the functions' definitions
and the db file which stores the DB parameters. Afterwards: 
Call of the DBInfo function which makes the DB pars accessible
for the script and connection to the DB.
************************************************************** */

require("include.php");

if (!file_exists("db.php")) {echo "The basic service configuration has not been finished yet. <br /> If all the Netious.com Service files are on the server <a href=\"./config/\">click here to start the configuration script.</a>";}
else {

require("db.php");

DBInfo();
mysql_connect("$DBHost","$DBUser","$DBPass");
mysql_select_db("$DBName");

/* ************************************************************
Tests whether there are any sections (pages) in the DB. If so, defines the 
default section (with the lowest Id). If the page DB is empty the page id 
is set to -1 which blocks the menus.
**************************************************************** */


if (!isset($pageid))
	{$result=mysql_query("SELECT PageId, Name FROM pages WHERE Active='1' order by PageId limit 0,1");
	$num_rows=mysql_num_rows($result);
	if ($num_rows!=0)
		{$row=mysql_fetch_row($result);
		$pageid=$row[0];
		$name=$row[1];
		$forcedid="yes";
		}
	else {$pageid="-1";}
	} else {$forcedid="no";}


/* Create the page */

commonheader($pageid,$title,$keywords,$description,$forcedid);
bodybegin($width, $bodyposition);
logobar($logoname,$textlogo);
thedocument($pageid,$model,$start);
bodyend($thisurl);
commonfooter();

}

?>