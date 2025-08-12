<?
include("searchbot_config.php");
if($_GET['action'] == "sort"){
	if(!isset($_COOKIE['SORT'])){ // if the cookie has not been set yet, it must need ordering descending
		$sort = "Descending";
		$desc = "The log will now show the most\\nrecent crawl at the START of its output";
		setcookie("SORT", $sort, time()+60*60*24*30,"/");
	}
	else if($_COOKIE['SORT'] == "Ascending"){ // if the cookie is actually set at ascending, make it descend
		$sort = "Descending";
		$desc = "The log will now show the most\\nrecent crawl at the START of its output";
		setcookie("SORT", $sort, time()+60*60*24*30,"/");
	}
	else{ // otherwise it must be set at descending, so ascend it
		$sort = "Ascending";
		$desc = "The log will now show the most\\nrecent crawl at the END of its output";
		setcookie("SORT", $sort, time()+60*60*24*30,"/");
	}
	print "<script>\n";
	print "alert(\"Sorted File: $sort\\n\\n$desc\");\n";
	print "</script>\n\n";
	print "<META HTTP-EQUIV=Refresh CONTENT=\"0; URL=searchactivity.php\">\n";
}
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>Latest SearchBot Crawling Activity</TITLE>
<META NAME="Generator" CONTENT="EditPlus">
<META NAME="Author" CONTENT="James Crooke">
<META NAME="Keywords" CONTENT="searchbot, google, search engine, bot, crawler, google crawl, crawl, crawling, spider, spidering web, spidering site">
<META NAME="Description" CONTENT="Searchbot spidering activity - this script logs all of Google crawling activity on your webpage">
<style>
A:link      	{text-decoration: none; color: #11116E;}
A:visited	{text-decoration: none; color: #11116E;}
A:hover		{font-style: normal; color: #000000; text-decoration: underline;}
BODY 		{font-family: Verdana; font-size: 10pt;}
TD 			{font-family: Verdana; color: #000000; font-size: 10pt;}
hr				{color: #11116E; background-color: maroon; height: 1px;}
</style>
<?
$url = "http://".$_SERVER['SERVER_NAME'].$_SERVER['PHP_SELF'];
echo "<script language=\"JavaScript1.2\">\n";
echo "var bookmarkurl=\"$url\"\n";
echo "var bookmarktitle=\"SearchBot Crawling Activity (".$_SERVER['SERVER_NAME'].")\"\n";
echo "function addbookmark(){\n";
echo "if (document.all) window.external.AddFavorite(bookmarkurl,bookmarktitle)\n";
echo "}\n";
echo "</script>\n";
?>
</HEAD>

<BODY>
<?
 $date = date("l, F j\<\s\u\p\>S\<\/\s\u\p\> Y"); 
 $time = date("g:i a");
 $mode = $_COOKIE['SORT'];
 if(!isset($mode)){
	 $mode = "Ascending";
 }
?>
<DIV ID="overDiv" STYLE="position:absolute; visibility:hide; z-index: 1;"></DIV>
<SCRIPT LANGUAGE="JavaScript" SRC="overlib.js"></SCRIPT>
  <table border="0" cellpadding="0" cellspacing="5" width="100%">
    <tr>
      <td width="99%"><font color="darkblue" size="3"><b>Latest SearchBot
        Crawling Activity</b></font></td>
    </center>
    <td width="1%" nowrap>
      <div align="right"><FONT COLOR="#AA0000"><? echo $date; ?></FONT></div></td>
  </tr>
  <center>
  <tr>
    <td width="99%"><a href="?action=refresh" onMouseOver="drs('<B>Refreshing</B> the page will allow you to see the latest on SearchBot crawling your page.'); return true;" onMouseOut="nd(); return true;">Refresh</a> | <a href="?action=sort" onMouseOver="drs('<B>Toggle</B> the sorting mode (ascending or descending)<br>Current mode:  <B><? echo $mode; ?></B> '); return true;" onMouseOut="nd(); return true;">Toggle Sort</a> | <a href="?action=reset" onMouseOver="drs('<B>Clear</B> the log file so you can start a fresh.  You will be asked to input the administrator password.'); return true;" onMouseOut="nd(); return true;">Clear File</a> | <a href="export_log.php" onMouseOver="drs('<B>Exporting</B> the log will allow you to save a textual representation of the log file.'); return true;" onMouseOut="nd(); return true;">Export
      Log</a> | <a href="javascript:addbookmark();" onMouseOver="drs('<B>Bookmark</B> this page in your favourites (IE Only) for easier access when you want to return.'); return true;" onMouseOut="nd(); return true;">Bookmark</a></td>
  </center>
  <td width="1%" nowrap>
    <div align="right"><FONT COLOR="#AA0000"><? echo $time; ?></FONT></div></td>
  </tr>
  </table>
<hr><br>
<?
if($_GET['action'] == "reset"){
	print "Enter Password (<A HREF=\"searchactivity.php\">Cancel</A>):<br>\n";
	print "<form method=POST>\n";
	print "<input type=\"hidden\" name=\"submit\" value=\"true\">\n";
	print "<input type=\"password\" name=\"password\">\n";
	print " <input type=\"submit\" value=\"Submit\">\n";
	print "</form>\n";
}
if(isset($_POST['submit'])){
	if($_POST['password'] == $password){
		$fd = fopen ($logfile, "w");
		$write = "";
		fwrite ($fd, $write);
		print "<script>\n";
		print "alert('Cleared File: $logfile');\n";
		print "</script>\n\n";
		print "<META HTTP-EQUIV=Refresh CONTENT=\"0; URL=searchactivity.php\">\n";

	}
	else{
		print "<FONT COLOR=\"red\">Invalid Password</FONT><br><br>";
	}
}
$logfilesize = filesize($logfile);
$size = 0;
if($logfilesize < 100){ 
	print "No activity recorded yet....";
}
else{
	$log = file($logfile);
	if(!$log){
		print "Could not load log file ($logfile)";
	}
	$size = count($log);

	if($_COOKIE['SORT'] == "Descending"){
		$temp = array();
		$j = 1;
		for($i=0;$i<$size;$i++){
			$temp[$i] = $log[$size-$j];
			$j++;
		}
		$log = $temp;
	//	var_dump($log);
//		exit;
	}

	$start = $_GET['start'];
	if(!isset($start)){
		$start = 0;
	}
	if(!isset($perpage)){
		$perpage = 10;
	}
	print "<div align=\"center\">";
	printNextPrev($start, $perpage, $size);
	print "</div>\n<br>";
	print "<ol>";
	for($i=$start; $i<$start+$perpage;$i++){
		if($i < $size){
			$j = $i + 1;
			print "<li value=\"".$j."\">".$log[$i];
		}
		else{
			break;
		}
	}
	print "</ol>";
	print "<br><br><div align=\"center\">";
	printNextPrev($start, $perpage, $size);
	print "</div>";
}
function printNextPrev($start, $perpage, $size){
	if($start > 0){
		$prev = $start - $perpage;
		print "<a href=\"?start=$prev\"><< Prev</a>";
	}
	else{
		print "<font color=\"#CCCCCC\"><< Prev</font>";
	}
	printbar($size, $start, $perpage);
	$next = $start + $perpage;
	if($next < $size){
		print "&nbsp;<a href=\"?start=$next\">Next >></a>";
	}
	else{
		print "&nbsp;<font color=\"#CCCCCC\">Next >></font>";
	}
}

function printbar($size, $current, $perpage){
	$i = 1;
	for($count = 0; $count < $size; $count += $perpage){
		if($count == $current && isset($current)){
			print "<font color=\"#CCCCCC\">&nbsp;($i)</font>";
		}
		else{
			print "&nbsp;<a href=\"?start=$count\">$i</a>";
		}
		$i ++;
	}

}
function formatsize($file_size){
    if($file_size >= 1048576) $file_size = round($file_size / 1048576 * 100) / 100 . " MBs!";
    else if($file_size >= 1024) $file_size = round($file_size / 1024 * 100) / 100 . " KBs";
    else $file_size = $file_size . " Bytes";
	return $file_size;
}
?>
<hr>
  <table border="0" cellpadding="5" cellspacing="0" width="100%">
    <tr>
      <td width="1%" nowrap>Log File Size:  <FONT COLOR="darkblue"><? echo formatsize($logfilesize); ?></FONT>  |  Records:  <FONT COLOR="darkblue"><? echo $size; ?></FONT></td>
      <td width="99%"><div align="right"><small>Powered by</small> <A HREF="http://jonathan.charpie.com/?dir=342727009JCSALT-02">SearchBot Activity</A></div></td>
    </tr>
  </table>
</BODY>
</HTML>
