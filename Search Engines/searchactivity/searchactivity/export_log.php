<?
include("searchbot_config.php");
?>
<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.0 Transitional//EN">
<HTML>
<HEAD>
<TITLE>SearchBot Crawling Activity Log Export</TITLE>
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
TEXTAREA	{font-family:Verdana;font-size:8pt;background:#FFFFFF;color:#000000;}
</style>
</head>
<script language="Javascript">
<!--
function selectAll(theField) {
	var tempval=eval("document."+theField)
	tempval.focus()
	tempval.select()
}
//-->
</script>
<font color="darkblue" size="3"><b>SearchBot Crawling Activity Export</b></font><br><br><hr>
<div align="center">
<form name="test">
<A HREF="javascript:selectAll('test.select1')">Select All</A><br>
<textarea rows="24" name="select1" cols="120">
	<?
	$handle = fopen($logfile, "r");
	$htmltext = fread($handle, filesize($logfile));
	fclose($handle);
	$htmlnolinebreaks = preg_replace("/\r\n|\r|\n/", "", $htmltext);
	$plaintext = eregi_replace('<br[[:space:]]*/?[[:space:]]*>',"<br>\n", $htmlnolinebreaks);
	$plaintext = strip_tags($plaintext);
	$date = date("F jS Y"); 
	echo "\n<!-- SearchBot Activity Log Export - $date -->\n\n";
	echo ($plaintext);
	?>
</textarea>
<A HREF="searchactivity.php"><< Back</A>
</div>
</form>
<hr>
  <table border="0" cellpadding="5" cellspacing="0" width="100%">
    <tr>
      <td width="100%"><div align="right"><small>Powered by</small> <A HREF="http://jonathan.charpie.com">SearchBot Activity</A></div></td>
    </tr>
  </table>
</BODY>
</HTML>