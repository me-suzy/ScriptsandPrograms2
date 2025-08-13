<html>
<head></head>
<?php
include("config.php");
include("identity.php");
if ($refok == "yes")
	{
/*  E5DDDD */

$dn = getenv(REMOTE_HOST);
$hd = getenv(HOME);
$browser = getenv(HTTP_USER_AGENT);
$previous = getenv(HTTP_REFERER);
$computer_email = getenv(HTTP_FROM);
$server = getenv(HTTP_HOST);
$coloration = "#E5DDDD";
$altcolor = "#EEEEEE";
?>

<?php
$appheaderstring='News';
include("header.php");
if ($action == 'deleteadminmsg')
	{
	dbconnect($dbusername,$dbuserpasswd);
        mysql_query("delete from sysadminmsg where id = '$target'");
	}
if ($setting[perm_admin] == 'y' and $setting[want_admin_bar] == 'y')
	{
	echo "<table width='100%'";
	if($setting[adminlbbg] != 'xxxxxx') { echo " bgcolor='", $setting[adminlbbg], "'"; }
	echo " border='0' cellpadding='5' cellspacing='0'>";
	dbconnect($dbusername,$dbuserpasswd);
	$result9 = mysql_query( "select * from linkbar where allusers = 'a'");
	while($iconbar = mysql_fetch_array($result9))
		{
	        if(($setting[perm_admin] == 'y' and $setting[want_admin_news]=='y') or $iconbar[allusers] == 'y')
			{
                        echo "<td align='center'><a href='", $iconbar[url], "' target='", $iconbar[target], "'><img src='icons/", $iconbar[icon], "' border='0'><font size='", $setting[menu_fontsize], "' face='", $setting[menu_fontface], "' color='", $setting[ab_fontcolor], "'><br>", $iconbar[title], "</a></td>";
			}
		}	
	echo "</tr></table>";
	}
if ($setting[utilitybar] == 'true')
	{
        echo "<table width='100%'";
	if($setting[linkbarbg] != 'xxxxxx') { echo " bgcolor='", $setting[linkbarbg], "'"; }
	echo " border='0' cellpadding='5' cellspacing='0'><tr>";
	dbconnect($dbusername,$dbuserpasswd);
	$result9 = mysql_query( "select * from linkbar where allusers = 'y' order by id");
	while($iconbar = mysql_fetch_array($result9))
		{
                echo "<td align='center'><a href='", $iconbar[url], "' target='", $iconbar[target], "'><img src='icons/", $iconbar[icon], "' border='0'><font size='", $setting[menu_fontsize], "' face='", $setting[menu_fontface], "' color='", $setting[lb_fontcolor], "'><br>", $iconbar[title], "</a></td>";
		}
	echo "</tr></table>";
	}
if ($setting[utilitybar] == 'true' and ($setting[perm_admin] == 'y' and $setting[want_admin_news]=='y'))
	{
        echo "<table width='100%'";
	if($setting[linkbarbg] != 'xxxxxx') { echo " bgcolor='", $setting[linkbarbg], "'"; }
	echo " border='0' cellpadding='5' cellspacing='0'><tr>";
	dbconnect($dbusername,$dbuserpasswd);
	$result9 = mysql_query( "select * from linkbar where allusers = 'n' order by id");
	while($iconbar = mysql_fetch_array($result9))
		{
                echo "<td align='center'><a href='", $iconbar[url], "' target='", $iconbar[target], "'><img src='icons/", $iconbar[icon], "' border='0'><font size='", $setting[menu_fontsize], "' face='", $setting[menu_fontface], "' color='", $setting[lb_fontcolor], "'><br>", $iconbar[title], "</a></td>";
		}
	echo "</tr></table>";
	}
echo "<p><blockquote>";
// Here's the survey request part...
	dbconnect($dbusername,$dbuserpasswd);
	$resultj=mysql_query("select * from surveyquestions");
	$num=mysql_num_rows($resultj);
	if ($num > 0)
		{

		while($rowx=mysql_fetch_array($resultj))
			{
			$surveyid=$rowx[id];
			dbconnect($dbusername,$dbuserpasswd);
			$resultk=mysql_query("select * from surveyanswers where questionid='$surveyid' and user='$setting[login]'");
			$completedsurvey = mysql_num_rows($resultk);
			if ($completedsurvey < 1)
				{
                    	    echo "<br><a href='survey.php?action=complete&target=", $surveyid, "'>Please complete survey #", $surveyid, " (", $rowx[question], ")</a>";
				}
			}
		}
// End of survey part
// Here's the system administrator message part:
	if($setting[perm_admin] == 'y' and $setting[want_admin_msg] == 'y')
		{
		dbconnect($dbusername,$dbuserpasswd);
		$resultj=mysql_query("select * from sysadminmsg");
		$num=mysql_num_rows($resultj);
		if ($num > 0)
			{
			while($rowx=mysql_fetch_array($resultj))
				{
                 		echo "<br>", $rowx[id], ". ", $rowx[message], " <i>(", $rowx[login], ", ", $rowx[date_time], ") ";
				echo "<a href='news.php?action=deleteadminmsg&target=", $rowx[id], "'></i><img src='icons/delete.gif' border='0' alt='Delete!'></a>";
				}
			}
		}
	echo "</blockquote>";	
// end sysadminmsg part
echo "<center><table width='95%' border='0' cellpadding='0' cellspacing='0'><tr><td valign='top'>";
echo "<table width='100%' border='0' cellpadding='0' cellspacing='0'><tr><td valign='top'><font face='", $setting[default_fontface], "'>";
$currenttime = time();
$timedata = getdate( $currenttime );
if ($ipaddy != "192.168.1.69")
	{
if ($timedata[weekday] == "Saturday") { echo "It's Saturday!<br>You should be out frolicking!<br>"; }
	elseif ($timedata[weekday] == "Sunday")
		{ echo "It's Sunday!<br>Go home and come back tomorrow!<br>"; }
			else {
				if ($timedata[hours] < 9) { echo "Good morning!<br>"; }
				if ($timedata[hours] > 16) { echo "It's late! Go home!<br>"; }
				}
	}
echo $timedata[weekday], ", ";
echo $timedata[month], " ";
echo $timedata[mday], ", ";
$adjhours = $timedata[hours];
$adjminutes = $timedata[minutes];
$ampm = "am";
if ($adjhours >12) { $adjhours = $adjhours -12; $ampm = "pm"; }
if ($adjminutes < 10){ $adjminutes = "0" . $adjminutes; }
echo $adjhours, ":", $adjminutes . $ampm;
echo "</font></td></tr><tr><td valign='bottom'>&nbsp;<br><font size='", $setting[heading_fontsize], "' face='", $setting[heading_fontface], "'><b>Recent News:</b><p></td></tr></table></td>";
echo "</td><td align='right'>";

// QUOTE SECTION!!!!
if ($showquotes=='yes')
	{
if ($setting[quotes_dark]=='y' or $setting[quotes_happy]=='y' or $setting[quotes_joke]=='y' or $setting[quotes_fortune]=='y' or $setting[quotes_odd]=='y' or $setting[quotes_crude]=='y')
	{
	$quotequery = "select * from quotes where quotetype='x' ";
	if ($setting[quotes_dark] == 'y') { $quotequery = $quotequery . "or quotetype='d' "; }
	if ($setting[quotes_happy] == 'y') { $quotequery = $quotequery . "or quotetype='h' "; }
	if ($setting[quotes_fortune] == 'y') { $quotequery = $quotequery . "or quotetype='f' "; }
	if ($setting[quotes_joke] == 'y') { $quotequery = $quotequery . "or quotetype='j' "; }
	if ($setting[quotes_crude] == 'y') { $quotequery = $quotequery . "or quotetype='c' "; }
	if ($setting[quotes_odd] == 'y') { $quotequery = $quotequery . "or quotetype='o' "; }
	$quotequery = $quotequery . "ORDER BY RAND()";
	dbconnect($dbusername,$dbuserpasswd);
	$result = mysql_query($quotequery);
	$row = mysql_fetch_array($result);
	echo "<table border='2' cellpadding='1' cellspacing='3'><tr><td><font size='2'>";
	echo "<a href='quotes.php'><img src='icons/circle.gif' border='0' alt='Quotes'></a> ";
	if ($row[quotetype] == 'f') { echo "Fortune: "; }
	echo stripslashes($row[quotetext]), "<br> ";
	if ($row[quotetype] == 'd') { echo "&nbsp; <i> - ", stripslashes($row[quoteauthor]), "</i>"; }
	if ($row[quotetype] == 'h') { echo "&nbsp; <i> - ", stripslashes($row[quoteauthor]), "</i>"; }
	if ($row[quotetype] == 'o') { echo "&nbsp; <i> - ", stripslashes($row[quoteauthor]), "</i>"; }
	echo "</font></center></td></tr></table>";
	}
	}
// END OF QUOTE SECTION!!!

if ($action == 'edit') { echo "<tr><td colspan='2'>The edit function has not been implemented.</td></tr>"; }
if ($action == 'delete')
	{
	mysql_query( "delete from news where id = '$newsitem'");
	}
if ($action == 'add')
	{
	$atitle = addslashes($atitle);
	$aarticlebody = addslashes($aarticlebody);
	mysql_query( "insert into news (title, author, createdate, articlebody) values('$atitle', '$setting[firstname]', now(), '$aarticlebody')" );
	}
$result = mysql_query( "select title, author, createdate, articlebody, id from news order by id desc" );
while ($news = mysql_fetch_row($result))
	{
		$news[3] = stripslashes($news[3]);
		$news[0] = stripslashes($news[0]);
	echo "<tr><td bgcolor='", $setting[headinghighlight], "'> &nbsp; <font color='", $setting[default_fontcolor], "' face='", $setting[default_fontface], "'> <b> ", $news[0], "</b></font></td><td bgcolor='", $setting[headinghighlight], "' align='right'><font color='", $setting[headinghighlight], "'>.";
        if ($news[1] == $setting[firstname] or ($setting[perm_admin] == 'y' and $admindeletenews == 'yes') or $deleteanynews == 'yes')
		{
		echo "<a href='news.php?action=delete&newsitem=", $news[4], "'><img src='icons/delete.gif' alt='Delete!' border='0'></a>";
		}
	echo "</font></td></tr>";
	echo "<tr><td colspan='2'><i>Posted by ", $news[1], " at ", $news[2], "</i></td></tr>";
	echo "<tr><td colspan='2'><font face='", $setting[default_fontface], "'>", $news[3], "</font></td></tr>";

	echo "<tr><td colspan='2'>&nbsp;</td></tr>";
	}
echo "<tr><td colspan='2' bgcolor='", $setting[headinghighlight], "'> &nbsp; <font color='", $setting[default_fontcolor], "' face='", $setting[default_fontface], "'> <b> Add News Item </b></font></td></tr>";
echo "<form action='news.php' method='post'>";
echo "<tr><td colspan='2'><input type='text' size='50' name='atitle' value='TITLE'></td></tr>";
echo "<tr><td align='right'><textarea name='aarticlebody' cols='30' rows='5' wrap='virtual'>TEXT</textarea></td><td valign='top' align='center'>";
echo "<input type='hidden' name='action' value='add'>";
echo "<p><input type='submit' value='Add It'></formt></td></tr>";

echo "</table></center>";
if ($sharepw == 'yes' or ($sharepw == 'restrict' and $setting[perm_sharepw] == 'y'))
	{
	echo "<p align='right'>
	<a href='humptydumpty.php'><img src='icons/wrench.gif' border='0'></a> &nbsp; &nbsp;";
	}
?>
</body></html>
<?php
	} else { echo $gend; } ?>
