<?
include "./config.php";
$sql = "select siteimpressions,cat from $table where id='$id'";
$result = mysql_query($sql) or die("Failed: $sql");
$resrow = mysql_fetch_row($result);
$siteimpressions = $resrow[0];
$cat = $resrow[1];
$siteimpressions++;
$sql = "update $table set siteimpressions='$siteimpressions' where id='$id'";
$result = mysql_query($sql) or die("Failed: $sql");
$from = $id;

$secondsago = $hours_must_be_active * 60 * 60;
$hoursago = strftime("%Y-%m-%d %H:%M:%S", time() - $secondsago);

#First Run
$sql = "select id,title,banner,textad,tablebg,tablebdr,tableclr,myimpressions from $table where (textad != '' or banner != '') and (lastclickin > '$hoursago' or exempt='1') and cat='$cat' and id != '$id' order by rand() limit 1";
$result = mysql_query($sql) or die("Failed: $sql");

if (mysql_num_rows($result)==0){
	#Second Run
	if (stristr($cat, " - ")) {
		$arcat = split(" - ", $cat);
		$cat = $arcat[0];
	}
	$sql = "select id,title,banner,textad,tablebg,tablebdr,tableclr,myimpressions from $table where (textad != '' or banner != '') and (lastclickin > '$hoursago' or exempt='1') and cat like '".$cat."%' and id != '$id' order by rand() limit 1";
	$result = mysql_query($sql) or die("Failed: $sql");
}

if (mysql_num_rows($result)==0){
	$sql = "select id,title,banner,textad,tablebg,tablebdr,tableclr,myimpressions from $table where (textad != '' or banner != '') and (lastclickin > '$hoursago' or exempt='1')  and id != '$id' order by rand() limit 1";
	$result = mysql_query($sql) or die("Failed: $sql");
}





$resrow = mysql_fetch_row($result);
$id = $resrow[0];
$title = $resrow[1];
$banner = $resrow[2];
$textad = $resrow[3];
$tablebg = $resrow[4];
$tablebdr = $resrow[5];
$tableclr = $resrow[6];
$myimpressions = $resrow[7];
$myimpressions++;
$sql = "update $table set myimpressions='$myimpressions' where id='$id'";
$result = mysql_query($sql) or die("Failed: $sql");

if ($banner){
	print "document.write('<a href=\"".$bx_url."out.php?id=$id&from=$from\" target=\"other\"><img src=\"$banner\" border=\"0\"></a>');";
	exit;
}
print "document.write('<table width=\"440\" border=\"1\" cellspacing=\"0\" cellpadding=\"4\" height=\"60\" bordercolor=\"$tablebdr\"><tr><td align=\"left\" valign=\"top\" bgcolor=\"$tablebg\"><font size=\"-1\" face=\"Verdana, Arial, Helvetica, sans-serif\" color=\"$tableclr\">$textad <a href=\"".$bx_url."out.php?id=$id&from=$from\"><font color=\"$tableclr\">$title</font></a></font></td></tr></table>');";
exit;
?>