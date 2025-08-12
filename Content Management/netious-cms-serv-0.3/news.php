<?

/* **************************************************************
Inclusion of the 'include' file containing the functions' definitions
and the db file which stores the DB parameters. Afterwards: 
Call of the DBInfo function which makes the DB pars accessible
for the script and connection to the DB.
************************************************************** */

require("include.php");
require("db.php");

DBInfo();
mysql_connect("$DBHost","$DBUser","$DBPass");
mysql_select_db("$DBName");

/* ************************************************************
Tests whether there are any sections (pages) in the DB. If so, defines the 
default section (with the lowest Id). If the page DB is empty the page id 
is set to -1 which blocks the menus.
**************************************************************** */

$pageid=$refid;

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

/* The structure of the document */



if ($model!="vv") {mainmenu($pageid,$model);}
if ($model=="hh") {submenu($pageid,$model);}

if ($pageid!="-1")
	{$result=mysql_query("SELECT Name, RefId FROM pages WHERE PageId='$pageid'");
	$row=mysql_fetch_row($result);
	$name=$row[0];
	$refid=$row[1];
	}
else 	{$content="<h1>No news corresponding to this section</h1>";
	}

$result=mysql_query("SELECT Text FROM news WHERE NewsId='$newsid'");
$row=mysql_fetch_row($result);
$content=$row[0];

$content.="<br /><hr /><a href=\"index.php?pageid=$pageid&amp;start=$start\" title=\"Go back to the list\">Go back to the list</a>";


if ($model!="hh")
{
$contwidth="80%";
$side="yes";
} else {$contwidth="100%"; 
	$side="no";}


if ($refid=="0")
{
$result_test=mysql_query("SELECT PageId, Name FROM pages WHERE RefId='$pageid' AND Active='1' order by PageId");
$num_rows_test=mysql_num_rows($result_test);
if ($num_rows_test=="0" && $model!="vv")
	$side="none";
	$contwidth="100%";
}

echo "
<table width=\"100%\">
	<tr>";
	
if ($side=="yes")
	{echo "
		<td width=\"20%\" align=\"center\" valign=\"top\">";
		if ($model=="vv") mainmenu($pageid,$model);
		echo "<br />";
		submenu ($pageid,$model);
		echo"
		</td>";
	}
	

echo " 
		<td width=\"$contwidth\" align=\"center\" valign=\"top\">
		<table width=\"90%\">
			<tr>
				<td valign=\"top\" align=\"left\">
				<br/>
				$content
				<br /><br />
				</td>
			</tr>
		</table>
		</td>
	</tr>
</table>
";

bodyend($thisurl);
commonfooter();



?>