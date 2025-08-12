<?
####################################
#        PhotoRate v2.0
#      Nuked Web Services
#    http://www.nukedweb.com/
####################################

include "./config.php";

if ($voteid && $voteval){
	if ($voteval=="1") $vfield = "vote_a";
	if ($voteval=="2") $vfield = "vote_b";
	if ($voteval=="3") $vfield = "vote_c";
	if ($voteval=="4") $vfield = "vote_d";
	if ($voteval=="5") $vfield = "vote_e";
	$sql = "select $vfield from $table where id='$voteid'";
	$result = mysql_query($sql);
	$resrow = mysql_fetch_row($result);
	$curval = $resrow[0];
	$curval++;
	$sql = "update $table set $vfield = '$curval' where id='$voteid'";
	$result = mysql_query($sql);
	$sql = "select id from $table where id < '$voteid' order by id desc LIMIT 0,1";
	$result = mysql_query($sql);
	if (mysql_num_rows($result)==0){
		print "You've reached the end of the pictures. <a href='index.php'>Click here</a> to go back to the beginning.";
		exit;
	}
	$resrow = mysql_fetch_row($result);
	$id = $resrow[0];
}

if (!$id){
	$sql = "select max(id) from $table";
	$result = mysql_query($sql) or die("Failed: $sql - ".mysql_error());
	$resrow = mysql_fetch_row($result);
	$id = $resrow[0];
	if (!$id){
		print "No pictures have yet been submitted. <a href='new.php'>Click here</a> to submit one.";
		exit;
	}
}

$sql = "select email,aim,icq,yahoo,homepage,vote_a,vote_b,vote_c,vote_d,vote_e,picfile,dt from $table where id='$id'";
$result = mysql_query($sql) or die("Failed: $sql - ".mysql_error());
$resrow = mysql_fetch_row($result);
$email = $resrow[0];
$aim = $resrow[1];
$icq = $resrow[2];
$yahoo = $resrow[3];
$homepage = $resrow[4];
$vote_a = $resrow[5];
$vote_b = $resrow[6];
$vote_c = $resrow[7];
$vote_d = $resrow[8];
$vote_e = $resrow[9];
$picfile = $resrow[10];
$dtf = $resrow[11];
$dta = split(" ", $dtf);
$created = $dta[0];

if ($email) $btns .= "<a href='mailto:$email'><img src='images/icon-email.gif' border='0'></a> ";
if ($aim) $btns .= "<a href='aim:goim?screenname=$aim'><img src='images/icon-aim.gif' border='0'></a> ";
if ($icq) $btns .= "<a href='http://wwp.icq.com/scripts/contact.dll?msgto=$icq'><img src='images/icon-icq.gif' border='0'></a> ";
if ($yahoo) $btns .= "<a href='ymsgr:sendim?".$yahoo."'><img src='images/icon-yahoo.gif' border='0'></a> ";
if ($homepage) $btns .= "<a href='$homepage' target='pr_".$id."'><img src='images/icon-home.gif' border='0'></a> ";

if ($vote_a > $biggestnum) $biggestnum = $vote_a;
if ($vote_b > $biggestnum) $biggestnum = $vote_b;
if ($vote_c > $biggestnum) $biggestnum = $vote_c;
if ($vote_d > $biggestnum) $biggestnum = $vote_d;
if ($vote_e > $biggestnum) $biggestnum = $vote_e;
if (!$biggestnum) $biggestnum = $vote_a;
$vp_a = 0;
$vp_b = 0;
$vp_c = 0;
$vp_d = 0;
$vp_e = 0;
if ($vote_a!=0) $vp_a = intval(($vote_a/$biggestnum)*100);
if ($vote_b!=0) $vp_b = intval(($vote_b/$biggestnum)*100);
if ($vote_c!=0) $vp_c = intval(($vote_c/$biggestnum)*100);
if ($vote_d!=0) $vp_d = intval(($vote_d/$biggestnum)*100);
if ($vote_e!=0) $vp_e = intval(($vote_e/$biggestnum)*100);

$template = join("", file("./template.html"));
$template = str_replace("[contactbuttons]", $btns, $template);
$template = str_replace("[picture]", "<img src='pics/".$picfile."'>", $template);
$template = str_replace("[voteoptions]", "<form name='form1' method='post' action=''><input type='radio' name='voteval' value='1'>$option_a <br><input type='radio' name='voteval' value='2'>$option_b<br><input type='radio' name='voteval' value='3'>$option_c<br><input type='radio' name='voteval' value='4'>$option_d<br><input type='radio' name='voteval' value='5'>$option_e<br><input type='hidden' name='voteid' value='$id'><input type='submit' value='Vote!'></form>", $template);
$template = str_replace("[voteresults]", "<table width='100%' border='0' cellspacing='0' cellpadding='0'><tr><td width='500' align='right' valign='top'><font size='-3' face='Verdana, Arial, Helvetica, sans-serif'>$option_a ($vote_a Votes)<br>$option_b ($vote_b Votes)<br>$option_c ($vote_c Votes)<br>$option_d ($vote_d Votes)<br>$option_e ($vote_e Votes)</font></td><td width='503'> <table width='$vp_a' border='0' cellspacing='0' cellpadding='0' height='13'><tr> <td bgcolor='$optcolor_a'><font size='-3' face='Verdana'>&nbsp;</font></td></tr></table><table width='$vp_b' border='0' cellspacing='0' cellpadding='0' height='13'><tr> <td bgcolor='$optcolor_b'><font size='-3' face='Verdana'>&nbsp;</font></td></tr></table><table width='$vp_c' border='0' cellspacing='0' cellpadding='0' height='13'><tr> <td bgcolor='$optcolor_c'><font size='-3' face='Verdana'>&nbsp;</font></td></tr></table><table width='$vp_d' border='0' cellspacing='0' cellpadding='0' height='13'><tr> <td bgcolor='$optcolor_d'><font size='-3' face='Verdana'>&nbsp;</font></td></tr></table><table width='$vp_e' border='0' cellspacing='0' cellpadding='0' height='13'><tr> <td bgcolor='$optcolor_e'><font size='-3' face='Verdana'>&nbsp;</font></td></tr></table></td></tr></table><font size='-3' face='Verdana, Arial, Helvetica, sans-serif'>Powered By <a href='http://nukedweb.memebot.com/' target='_nukedweb'>PhotoRate</a></font>", $template);

print $template;
?>