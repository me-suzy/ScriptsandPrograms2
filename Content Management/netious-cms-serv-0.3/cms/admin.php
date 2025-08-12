<?
require("../db.php");
require("include.php");
DBinfo();

mysql_connect("$DBHost","$DBUser","$DBPass");
mysql_select_db("$DBName");



$SUID=f_ip2dec($REMOTE_ADDR);
if (!session_id($SUID))
session_start();

$username=$_SESSION['uname'];
$password=$_SESSION['pass'];

$result=mysql_query("SELECT AdminId FROM mycmsadmin WHERE username='$username' and password='".sha1($password)."'");
$row=mysql_fetch_row($result);
$num_rows = mysql_num_rows($result);
$id=$row[0];



if ($_SESSION['signed_in']!='indeed' || $num_rows!=1 || $id!=1){
Header( "Location: index.php?action=2");
}else{

/* in case the function is not specified... */
if (!isset($f)) $f="";
if (!isset($sf)) $sf="";

/* The text that appears in the page depending on the function */
if ($f=="") {$header="Control Panel"; $info="From the navigation menu choose the action. After the administrative task is finished, remember to sign out!";}
elseif ($f=="structure") {$header="Control Panel - structure management"; $info="Using this function you can add/modify/delete the sections (pages) as they appear in the menu. If you want to edit the page content choose the Content Management from the main menu.
";} 
elseif ($f=="style") {$header="Control Panel - Style Management"; 
$info = "Using this function you can modify the style of your service: colors, fonts, borders, background images etc. Below you can see a schematic structure of a page. The tools can be used to modify the following:
<ul>
<li>General Properties - default service properties: title, keywords, description; the wide background, document position (left, center), document width, Menu model (horizontal/vertical)</li>
<li>Dominant font - the font that should be used in the pages.</li>
<li>Header Style - Define (upload or compose text) logo, and the header style</li>
<li>Document Style - Background, borders</li>
<li>Main Menu Style - colors, borders</li>
<li>Submenu Style - colors, borders</li>
<li>Link Style - colors</li>
</ul>
<br />
<center>
<img src=\"images/layout.jpg\" border=\"1\" alt=\"layout\" />
</center>
";
} elseif ($f=="rss") 
{$header="RSS creator"; $info="From the navigation menu choose the action. After the administrative task is finished, remember to sign out! <br /> Below you can find the list of currently existing channels <br /><br />";


if ($result=mysql_query("SELECT Name, title FROM rsschannel order by RssId DESC")){
while ($row=mysql_fetch_row($result))
	{$name=$row[0];
	$title=$row[1];
	if (file_exists("../rss/$name/rss.xml"))
		$info.= "<a href=\"$thisurl/rss/$name/rss.xml\" target=\"blank\">$title</a> <br />";
	}

} else {$info.="There are no channels in the DB";}

} elseif ($f=="news") {$header="News Management"; $info="Before you can add any news you need to create a section with the \"News (headers)\" type. The news headers added here will then appear in that page. If you haven't done it yet, go to the Structure Management. <br /><br />Note: the news headers added here can appear automatically in your RSS feeds with appropriate links. If you want to use this option - make sure there exists an RSS channel for this purpose.";}

/* Create the page */
commonheader();
bodybegin();
logobar($logoname,$textlogo);
mainmenu($f);
echo "
<br><br>
<table>
<tr>
	<td valign=\"top\" width=\"20%\">";

submenu($f,$sf);

echo "
	</td>
	<td align=\"left\">
	<center><h2>$header</h2></center><br /><br />
	<b>$info</b><br>
<br><br>
<br>
	<center>
	</center>

	</td>
</tr>
</table>
 ";

bodyend();
commonfooter();

}
?>
