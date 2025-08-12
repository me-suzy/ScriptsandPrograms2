<?
set_time_limit(0);
include "./config.php";
if ($header) include "$header";
print "<center><font face='$fontface' size='$fontsize'><center><br><br><FORM method=get action='$mainmod'>Search: <input type=text name=search value='$search'><input type=submit value='Search'><br></FORM></center>";
if($search) {
	include "./mysql.php";
	$sql = "select engine,cache from $enginestable where engorder != '0' and engorder != '' order by engorder";
	$engreslt = mysql_query($sql) or die("Failed: $sql");
	$numengs = mysql_num_rows($engreslt);
	$search = urlencode($search);

	include "./ad.inc";
	print textad($search);
	print "<center><table width='$tablewidth' border='0' cellspacing='0' cellpadding='0'>";
	for($z=0;$z<$numengs;$z++){
		$resrow = mysql_fetch_row($engreslt);
		$engine = $resrow[0];
		$cacheentry = $resrow[1];




		if ($engine=="dmoz"){
			$reg = '/<li><a href=\"(.*?)\">(.*?)<\/a>.*?- (.*?)<br><small>/i';
			$page = join("", file("http://search.dmoz.org/cgi-bin/search?search=$search"));
			$page = ereg_replace("\n", "", $page);
			preg_match_all($reg, $page, $matches);
			$cnt = count($matches[0]);
			for($x=0;$x<$cnt;$x++){
				$url = $matches[1][$x];
				$title = $matches[2][$x];
				$descr = $matches[3][$x];
				$title = strip_tags($title);
				$descr = strip_tags($descr);
				$extrastuff = "";
				if ($cacheentry=="1"){
					$sql = "select * from $table where url='$url'";
					$result = mysql_query($sql);
					$numrows = mysql_fetch_row($result);
					if ($numrows==0){
						$slashtitle = addslashes($title);
						$slashdescr = addslashes($descr);
						$sql = "insert into $table values('', '$url', '$slashtitle', '$slashdescr', '0', now())";
						$result = mysql_query($sql);
						$extrastuff = "[Added to $engtitle]";
					}
				}
				if ($newwin=="1") $targetwin = " target='_$url'";
				print "<tr><td bgcolor='$color1'>&nbsp;&nbsp;<a href='$url'$targetwin><b><font face='$fontface' size='$fontsize'>$title</font></b></a><font face='$fontface' size='$fontsize'> <font color='#000000'>$extrastuff</font></font></td></tr><tr> <td bgcolor='$color2'><blockquote><p><font face='$fontface' size='$fontsize' color='$textcolor'>$descr</font></p></blockquote></td></tr>";
			}
		}





		if ($engine=="google"){
			$reg = "/a class=yschttl.*?3A\/\/(.*?)\">(.*?)<\/a>.*?class=yschabstr>(.*?)<\/div>/i";
			$page = join("", file("http://search.yahoo.com/search?p=$search"));
			$page = ereg_replace("\n", "", $page);
			preg_match_all($reg, $page, $matches);
			$cnt = count($matches[0]);

			for($x=0;$x<$cnt;$x++){
				$url = $matches[1][$x];
				$title = $matches[2][$x];
				$descr = $matches[3][$x];
				$title = strip_tags($title);
				$descr = strip_tags($descr);
				$url = strip_tags($url);
				$url = "http://".$url;
				if (stristr($title, "search within this site")) $title = "";
				$extrastuff = "";
				if ($cacheentry=="1" && $title){
					$sql = "select * from $table where url='$url'";
					$result = mysql_query($sql);
					$numrows = mysql_fetch_row($result);
					if ($numrows==0){
						$slashtitle = addslashes($title);
						$slashdescr = addslashes($descr);
						$sql = "insert into $table values('', '$url', '$slashtitle', '$slashdescr', '0', now())";
						$result = mysql_query($sql);
						$extrastuff = "[Added to $engtitle]";
					}
				}
				if ($newwin=="1") $targetwin = " target='_$url'";
				if ($title) print "<tr><td bgcolor='$color1'>&nbsp;&nbsp;<a href='$url'$targetwin><b><font face='$fontface' size='$fontsize'>$title</font></b></a><font face='$fontface' size='$fontsize'> <font color='#000000'>$extrastuff</font></font></td></tr><tr> <td bgcolor='$color2'><blockquote><p><font face='$fontface' size='$fontsize' color='$textcolor'>$descr</font></p></blockquote></td></tr>";
			}
		}





		if ($engine=="msn"){
			$reg = "/<h2>Results<\/h2>(.*?)<h2>SPONSORED SITES<\/h2>/i";
			$page = join("", file("http://search.msn.com/results.asp?RS=CHECKED&FORM=MSNH&v=1&q=$search"));
			$page = ereg_replace("\n", "", $page);
			preg_match_all($reg, $page, $matches);
			$page = $matches[1][0];
			$reg = "/<h3><a href=\"(.*?)\">(.*?)<\/a>.*?<p>(.*?)<\/p>/i";
			preg_match_all($reg, $page, $matches);
			$cnt = count($matches[0]);
			for($x=0;$x<$cnt;$x++){
				$url = $matches[1][$x];
				$title = $matches[2][$x];
				$descr = $matches[3][$x];
				$title = strip_tags($title);
				$descr = strip_tags($descr);
				$url = strip_tags($url);
				$url = urldecode($url);
				if (stristr($title, "search within this site")) $title = "";
				$extrastuff = "";
				if ($cacheentry=="1" && $title){
					$sql = "select * from $table where url='$url'";
					$result = mysql_query($sql);
					$numrows = mysql_fetch_row($result);
					if ($numrows==0){
						$slashtitle = addslashes($title);
						$slashdescr = addslashes($descr);
						$sql = "insert into $table values('', '$url', '$slashtitle', '$slashdescr', '0', now())";
						$result = mysql_query($sql);
						$extrastuff = "[Added to $engtitle]";
					}
				}
				if ($newwin=="1") $targetwin = " target='_$url'";
				if ($title) print "<tr><td bgcolor='$color1'>&nbsp;&nbsp;<a href='$url'$targetwin><b><font face='$fontface' size='$fontsize'>$title</font></b></a><font face='$fontface' size='$fontsize'> <font color='#000000'>$extrastuff</font></font></td></tr><tr> <td bgcolor='$color2'><blockquote><p><font face='$fontface' size='$fontsize' color='$textcolor'>$descr</font></p></blockquote></td></tr>";
			}
		}





	

		if ($engine=="altavista"){
			$reg = "/AltaVista found(.*?)<\/table>/i";
			$page = join("", file("http://www.altavista.com/web/results?avkw=rf&q=$search"));
			$page = ereg_replace("\n", "", $page);
			preg_match_all($reg, $page, $matches);
			$page = $matches[1][0];
			$reg = "/<a class='res' href='(.*?)'>(.*?)<\/a>.*?<span class=s>(.*?)<br>/i";
			preg_match_all($reg, $page, $matches);
			$cnt = count($matches[0]);
			for($x=0;$x<$cnt;$x++){
				$url = $matches[1][$x];
				$title = $matches[2][$x];
				$descr = $matches[3][$x];
				$url = urldecode($url);
				$title = strip_tags($title);
				$descr = strip_tags($descr);
				if (stristr($title, "News on $search")) $title = "";
				$extrastuff = "";
				if ($cacheentry=="1" && $title){
					$sql = "select * from $table where url='$url'";
					$result = mysql_query($sql);
					$numrows = mysql_fetch_row($result);
					if ($numrows==0){
						$slashtitle = addslashes($title);
						$slashdescr = addslashes($descr);
						$sql = "insert into $table values('', '$url', '$slashtitle', '$slashdescr', '0', now())";
						$result = mysql_query($sql);
						$extrastuff = "[Added to $engtitle]";
					}
				}
				if ($newwin=="1") $targetwin = " target='_$url'";
				if ($title) print "<tr><td bgcolor='$color1'>&nbsp;&nbsp;<a href='$url'$targetwin><b><font face='$fontface' size='$fontsize'>$title</font></b></a><font face='$fontface' size='$fontsize'> <font color='#000000'>$extrastuff</font></font></td></tr><tr> <td bgcolor='$color2'><blockquote><p><font face='$fontface' size='$fontsize' color='$textcolor'>$descr</font></p></blockquote></td></tr>";
			}
		}







		if ($engine=="askjeeves"){
			#$reg = "/onmouseout=\"cs\(\)\">(.*?)<\/a>.*?<div>(.*?)<\/div>.*?From:(.*?)<\/span>/i";
			$page = join("", file("http://www.ask.com/main/askjeeves.asp?o=0&ask=$search"));
			$reg = "/<span class=\"hb\">Web Results<\/span>(.*?)<span class=\"hb\">Related Topics<\/span>/i";
			$page = ereg_replace("\n", "", $page);
			preg_match_all($reg, $page, $matches);
			$page = $matches[1][0];
			$reg = "/redir\?u=(.*?)\".*?_top\">(.*?)<\/a>.*?id=\"ab.*?\">(.*?)<\/div>/i";
			preg_match_all($reg, $page, $matches);
			$cnt = count($matches[0]);
			for($x=0;$x<$cnt;$x++){
				$url = $matches[1][$x];
				$title = $matches[2][$x];
				$descr = $matches[3][$x];
				$title = strip_tags($title);
				$descr = strip_tags($descr);
				$url = strip_tags($url);
				$url = urldecode($url);
				if (stristr($title, "News on $search")) $title = "";
				$extrastuff = "";
				if ($cacheentry=="1" && $title){
					$sql = "select * from $table where url='$url'";
					$result = mysql_query($sql);
					$numrows = mysql_fetch_row($result);
					if ($numrows==0){
						$slashtitle = addslashes($title);
						$slashdescr = addslashes($descr);
						$sql = "insert into $table values('', '$url', '$slashtitle', '$slashdescr', '0', now())";
						$result = mysql_query($sql);
						$extrastuff = "[Added to $engtitle]";
					}
				}
				if ($newwin=="1") $targetwin = " target='_$url'";
				if ($title) print "<tr><td bgcolor='$color1'>&nbsp;&nbsp;<a href='$url'$targetwin><b><font face='$fontface' size='$fontsize'>$title</font></b></a><font face='$fontface' size='$fontsize'> <font color='#000000'>$extrastuff</font></font></td></tr><tr> <td bgcolor='$color2'><blockquote><p><font face='$fontface' size='$fontsize' color='$textcolor'>$descr</font></p></blockquote></td></tr>";
			}
		}





		if ($engine=="searchfeed"){
			$reg = "/<Title><!\[CDATA\[(.*?)\]\]><\/Title>.*?<URI>(.*?)<\/URI>.*?<Description><!\[CDATA\[(.*?)\]\]><\/Description>/i";
			$page = join("", file("http://www.searchfeed.com/rd/feed/XMLFeed.jsp?cat=$search&pID=$searchfeedid&nl=$numsearchfeed&page=1&ip=$REMOTE_ADDR"));
			$page = ereg_replace("\n", "", $page);
			preg_match_all($reg, $page, $matches);
			$cnt = count($matches[0]);
			for($x=0;$x<$cnt;$x++){
				$title = $matches[1][$x];
				$url = $matches[2][$x];
				$descr = $matches[3][$x];
				$title = strip_tags($title);
				$descr = strip_tags($descr);
				$extrastuff = "";
				if ($newwin=="1") $targetwin = " target='_$url'";
				print "<tr><td bgcolor='$color1'>&nbsp;&nbsp;<a href='$url'$targetwin><b><font face='$fontface' size='$fontsize'>$title</font></b></a><font face='$fontface' size='$fontsize'> <font color='#000000'>$extrastuff</font></font></td></tr><tr> <td bgcolor='$color2'><blockquote><p><font face='$fontface' size='$fontsize' color='$textcolor'>$descr</font></p></blockquote></td></tr>";
			}
		}




		if ($engine=="revenuepilot"){
			$reg = "/<LISTING LINK=\"(.*?)\" TITLE=\"(.*?)\".*?DESCRIPTION=\"(.*?)\"/i";
			$page = join("", file("http://search.revenuepilot.com/servlet/search?mode=xml&id=$revenuepilotid&filter=$rpfamfilt&perpage=$numrevenuepilot&ip=$REMOTE_ADDR&skip=0&keyword=$search"));
			
			$page = ereg_replace("\n", "", $page);
			preg_match_all($reg, $page, $matches);
			$cnt = count($matches[0]);
			for($x=0;$x<$cnt;$x++){
				$url = $matches[1][$x];
				$title = $matches[2][$x];
				$descr = $matches[3][$x];
				$url = urldecode($url);
				$extrastuff = "";
				if ($newwin=="1") $targetwin = " target='_$url'";
				print "<tr><td bgcolor='$color1'>&nbsp;&nbsp;<a href='$url'$targetwin><b><font face='$fontface' size='$fontsize'>$title</font></b></a><font face='$fontface' size='$fontsize'> <font color='#000000'>$extrastuff</font></font></td></tr><tr> <td bgcolor='$color2'><blockquote><p><font face='$fontface' size='$fontsize' color='$textcolor'>$descr</font></p></blockquote></td></tr>";
			}
		}





	}

}
print "</table><font face='$fontface' size='$fontsize' color='$textcolor'><a href='$mainmod'>Back to $engtitle</a></center><center>Powered by <a href='http://nukedweb.memebot.com/' target='_nukedweb'>MyOwnSearch</a></center>";
if ($footer) include "$footer";
?>