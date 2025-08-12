<?php
$pagetitle="Generate text";
$sel_tab="2.3";
require("functions.php");
sidehoved($s2);
vaelgListe($PHP_SELF);

$skrivSend=$_REQUEST["skrivSend"];
$skrivLinjebrydning=$_REQUEST["skrivLinjebrydning"];

if ($_REQUEST["liste"] && $_REQUEST["itemarray"]=='') {

	$dr= mysql_prefix_query("SELECT UNIX_TIMESTAMP(date) as udate FROM $mainTable WHERE liste='".$_REQUEST["liste"]."'");
	if ($narray=mysql_fetch_array($dr)) {
		$date= date("Y-m-d",$narray[udate]);
	}

	listitems($_REQUEST["liste"], $date);
	jetstream_footer();
	exit;
}
elseif($_REQUEST["liste"] && $_REQUEST["itemarray"]<>''){
	$dr= mysql_prefix_query("SELECT UNIX_TIMESTAMP(date) as udate FROM $mainTable WHERE liste='".$_REQUEST["liste"]."'");
	if ($narray=mysql_fetch_array($dr)) {
		$date= date("Y-m-d",$narray["udate"]);
	}
	generate_mail_txt($_REQUEST["itemarray"], $date, $_REQUEST["liste"]);
}

if ($skrivSend) {
	if ($skrivLinjebrydning == 1) {
		$skrivEbrev = ereg_replace("(.{1,71}) ", "\\1\n", $skrivEbrev." ");
	}
	mysql_prefix_query("update $mainTable
		set afsender = '".addslashes($skrivAfsender)."',
		emne = '".addslashes($skrivEmne)."',
		date = '".date("Y-m-d")."',
		ebrev = '".addslashes($skrivEbrev)."'
		where liste = '".addslashes($_REQUEST["liste"])."'");
	echo "<table border=0 cellspacing=0 cellpadding=1>\n";
	echo "<tr><td style=\"background: #dddddd\">\n";
	echo "<table border=0 cellspacing=0 cellpadding=5>\n";
	echo "<tr><td style=\"background: #eeeeee; vertical-align: top\">\n";
	echo "<b>$s52</b><br>\n";
	echo "<b>$s66</b><br>\n";
	echo "<b>$s53</b>\n";
	echo "</td>\n";
	echo "<td style=\"background: #eeeeee; vertical-align: top\">\n";
	echo "".htmlspecialchars(stripslashes($skrivAfsender))."<br>\n";
	echo "[RCPT_EMAIL]<br>\n";
	echo "".htmlspecialchars(stripslashes($skrivEmne))."\n";
	echo "</td></tr>\n";
	echo "<tr><td colspan=2 style=\"background: white; vertical-align: top\">\n";
	echo "<pre>\n";
	echo "".htmlspecialchars(stripslashes($skrivEbrev))."<br>\n";
	echo "</pre>\n";
	echo "</td></tr>\n";
	echo "</table>\n";
	echo "</td></tr>\n";
	echo "</table>\n";
	# Getting the number of confirmed email addresses in the table:
	if ($_REQUEST["liste"]=='news') {
		$cl='newsmail';
	}
	elseif ($_REQUEST["liste"]=='events') {
		$cl='eventmail';
	}
	elseif ($_REQUEST["liste"]=='internalnews') {
		$cl='internalmail';
	}
	$iAlt = mysql_num_rows(mysql_prefix_query("select count(*) from webuser where newsmail = '1'"));
	echo "<form action=\"send.php\" method=get>\n";
	echo "<input type=hidden name=liste value=\"".$_REQUEST["liste"]."\">\n";
	echo "<input type=hidden name=start value=0>\n";
	echo "<input type=hidden name=iAlt value=$iAlt>\n";
	echo "<input type=submit value=\"$s67\">\n";
	echo "<input type=button value=\"$s68\" onClick=history.back()>\n";
	echo "</form>\n";
	exit;
}
?>