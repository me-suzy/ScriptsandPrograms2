<?php
// Somery, a weblogging script by Robin de Graaf, copyright 2001-2005
// Somery is distributed under the Artistic License (see LICENSE.txt)
//
// ADMIN/SYSTEM/ENGINE.PHP > 03-11-2005

include("config.php");
include("admin/system/error.php");
include("admin/system/functions.php");

if ($comments == "post") {
	if (!$author && !$err) { echo $error[15]; $err = 1; }
	if (!$comment && !$err) { echo $error[16]; $err = 1; }

	if (!$err) {
		$postdate = (date ("Ymd"));
		$posttime = (date ("Hi"));
		$query = "INSERT INTO ".$prefix."comments (parentid,author,date,time,email,url,comment) VALUES
			('$p','$author','$postdate','$posttime','$email','$url','$comment')";
		$result = mysql_query($query);

		include("admin/cookies.php");
		destroy_cookie("mobscommenter");
		destroy_cookie("mobscommentemail");
		destroy_cookie("mobscommenturl");
		setcookie("mobscommenter",$author, time()+30000000);
		setcookie("mobscommentemail",$email, time()+30000000);
		setcookie("mobscommenturl",$url, time()+30000000);
		echo "<meta http-equiv=Refresh content=0;URL=$PHP_SELF?p=$p&amp;c=1#comments>";
	}
}

loadsettings();
$build = " WHERE status = '1'";

if ($p != "") $build .= " AND aid = '$p'";
if ($cat || $user) {
	if (!$p) {
		if ($cat) {$build .= " AND category = '$cat'";}
		if ($user) {$build .= " AND username = '$user'";}
	}
}
if ($offset) {
	$buildpp = " LIMIT $offset,".$settings[noposts];
} else {
	$buildpp = " LIMIT ".$settings[noposts];
}

$query = "SELECT * FROM ".$prefix."articles".$build." ORDER BY aid DESC".$buildpp;
$result = mysql_query($query);
?>
