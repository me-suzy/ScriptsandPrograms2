<?php
// Somery, a weblogging script by Robin de Graaf, copyright 2001-2005
// Somery is distributed under the Artistic License (see LICENSE.txt)
//
// ADMIN/SYSTEM/FUNCTIONS.PHP > 03-11-2005

// execute some stuff
extract($_POST);
extract($_GET);
mysql_connect($sqlhost, $sqluser, $sqlpass);
mysql_select_db($sqldb);
skinset();
loaduser();

$localver = "0.4.4";

// functions for the admin system, gathering data
function format_date($raw_date, $format="m.d.Y",$tz) {
	ereg("(....)-(..)-(..) (..):(..):(..)",$raw_date,$reg);
	return date($format, mktime($reg[4],$reg[5],$reg[6],$reg[2],$reg[3],$reg[1])+($tz*3600));
}

function skinset() {
	global $prefix, $skindir;
	$result = mysql_query("SELECT * FROM ".$prefix."settings");
	while($row=mysql_fetch_object($result)) {
		$skindir = $row->skindir;
	}
	$skindir = "skins/".$skindir;
	return;
}

function loadsettings() {
	global $prefix, $settings;
	$result = mysql_query("SELECT * FROM ".$prefix."settings");
	while($row=mysql_fetch_object($result)) {
		$settings['setskin'] = $row->skindir;
		$settings['startlevel'] = $row->startlevel;
		$settings['gmt'] = $row->gmt;
		$settings['startstatus'] = $row->startstatus;
		$settings['registration'] = $row->registration;
		$settings['comments'] = $row->comments;
		$settings['noposts'] = $row->noposts;
		$settings['archive'] = $row->archive;
		$settings['more'] = $row->more;
		$settings['nocomments'] = $row->nocomments;
	}
	return;
}

function loaduser($username="") {
	global $userdata, $prefix;
	if ($username) {
		$result = mysql_query("SELECT * FROM ".$prefix."users WHERE username = '$username'");
		while($row=mysql_fetch_object($result)) {
			$userdata['username'] = $row->username;
			$userdata['password'] = $row->password;
			$userdata['level'] = $row->level;
		}
	}
	return;
}

function loadprofile($username="",$profile) {
	global $prefix;
	if ($username && $profile) {
		$result = mysql_query("SELECT $profile FROM ".$prefix."profile WHERE username = '$username'");
		while($row=mysql_fetch_object($result)) {
			$text = $row->$profile;
		}
	}
	return $text;
}

// functions for the skin system
function menu() {
	global $checkauth,$prefix,$userdata;
	if ($checkauth) {
		echo "<a href='index.php'>index</a> ";
		echo "<a href='profile.php'>profile</a> ";
		if ($userdata['level'] >= 1) echo "<a href='articles.php'>articles</a> ";
		echo "<a href='team.php'>team</a> ";
		if ($userdata['level'] >= 3) echo "<a href='settings.php'>settings</a> ";
		if ($userdata['level'] >= 2) echo "<a href='categories.php'>categories</a> ";
		echo "<a href='login.php?a=logout'>logout</a> ";
	} elseif (!$checkauth) {
		$result = mysql_query("SELECT * FROM ".$prefix."settings");
		while($row=mysql_fetch_object($result)) {
			$settings['registration'] = $row->registration;
		}
		if ($settings['registration'] == 0) echo "you're not logged in";
		if ($settings['registration'] == 1) echo "<a href='register.php'>register</a>";
	}
}

// functions for formatting
function debbcode($text) {
	$text = ereg_replace("\[b\]|\[/b\]|\[i\]|\[/i\]|\[u\]|\[/u\]|\[img\]|\[/img\]|\[quote\]|\[/quote\]|\[email\]|\[/email\]|\[url\]|\[/url\]","", $text);
	return $text;
}

function bbcode($text,$type=0) {
	$text = ereg_replace("\r\r", "</p><p>", $text);
	$text = ereg_replace("\r\n\r\n", "</p><p>", $text);
	$text = ereg_replace("\n\n", "</p><p>", $text);
	$text = ereg_replace("\n", "<br />", $text);

	$text = preg_replace("/\[quote\](.+?)\[\/quote\]/is", "<blockquote><b>Quote:</b><br />\\1</blockquote>", $text);
	$text = preg_replace("/\[center\](.+?)\[\/center\]/is", "</p><center>\\1</center><p>", $text);
	$text = preg_replace("/\[b\](.+?)\[\/b\]/is", "<b>\\1</b>", $text);
	$text = preg_replace("/\[i\](.+?)\[\/i\]/is", "<i>\\1</i>", $text);
	$text = preg_replace("/\[u\](.+?)\[\/u\]/is", "<u>\\1</u>", $text);
	if ($type != 1) $text = preg_replace("/\[img\](.+?)\[\/img\]{1}/is", "<img src='\\1' border='0' alt=''>", $text);
	$text = preg_replace("/\[email\](.+?)\[\/email\]{1}/is", "<a href='mailto:\\1'>\\1</a>", $text);

	$text = preg_replace("/\[url=([^<]+?)\](.+?)\[\/url\]{1}/is", "<a href='\\1' target='_blank'>\\2</a>", $text);
	$text = preg_replace("/\[url\](.+?)\[\/url\]{1}/is", "<a href='\\1' target='_blank'>\\1</a>", $text);
	return $text;
}

function cleanstring($text) {
	$text = ereg_replace("[\x27]","&#39;",trim($text));
	$text = ereg_replace("[\x22]","&quot;",trim($text));
	strip_tags($text);
	$text = ereg_replace(">","&gt;",$text);
	$text = ereg_replace("<","&lt;",$text);
	//$text = ereg_replace("\<b\>|\</b\>|\<i\>|\</i\>|\<u\>|\</u\>|\<img\>|\</img\>|\<quote\>|\</quote\>|\<email\>|\</email\>|\<url\>|\</url\>","", $text);
	$text = preg_replace("[\x5c\]","",$text);
	return $text;
}

function datetime($dt) {
	global $dtr;
	if (strlen($dt) == 8) {
		$dtr['ye'] = substr($dt,0,4);
		$dtr['mo'] = substr($dt,4,2);
		$dtr['da'] = substr($dt,6,2);
	}
	if (strlen($dt) == 4) {
		$dtr['ho'] = substr($dt,0,2);
		$dtr['mi'] = substr($dt,2,2);
	}
	return $dtr;
}


// functions for the output engine
function archive($output="%",$type="title",$dtype="d/m/Y") {
	global $total,$arow,$prefix,$p,$settings,$PHP_SELF;
	if ($settings['archive'] != "0") { $limit = " LIMIT ".$settings['archive']; }
	$result = mysql_query("SELECT * FROM ".$prefix."articles WHERE status = '1' ORDER BY aid DESC$limit");
	while($row=mysql_fetch_object($result)) {
		if ($type == "date") $more = "<a href='".$PHP_SELF."?p=".$row->aid."&c=1'>".strtolower(debbcode(date($dtype,strtotime($row->date))))."</a>";
		if ($type == "title") $more = "<a href='".$PHP_SELF."?p=".$row->aid."&c=1'>".strtolower(debbcode($row->title))."</a>";
		if ($type == "td") $more = "<a href='".$PHP_SELF."?p=".$row->aid."&c=1'>".strtolower(debbcode(date($dtype,strtotime($row->date))))." - ".strtolower(debbcode($row->title))."</a>";
		$info=eregi_replace("\%",$more,$output);
		echo $info;
	}
	if (!$result) echo "empty";
}

function prevnext($next="newer posts",$divider=" - ",$previous="older posts") {
	global $total,$arow,$prefix,$p,$settings,$PHP_SELF,$offset;

	if (!$offset) { $offset = 0; }

	$offsetnext = $offset - $settings['noposts'];
	$offsetprev = $offset + $settings['noposts'];

      if ($offsetnext < 0) {
		$next = "no newer posts";
	} else {
		$next = "<a href='index.php?offset=$offsetnext'>$next</a>";
	}

	$resultz = mysql_query("SELECT * FROM ".$prefix."articles WHERE status = '1'");
	$totalz = mysql_num_rows($resultz);

	$change = $totalz - $settings['noposts'];

	if ($offset >= $change) {
		$prev = "no older posts";
	} else {
		$prev = "<a href='index.php?offset=$offsetprev'>$previous</a>";
	}

	echo $next.$divider.$prev;
}

function getauthor($item) {
	global $settings, $row, $prefix;
	$result2 = mysql_query("SELECT * FROM ".$prefix."profile WHERE username = '".$row->username."'");
	while($row2=mysql_fetch_object($result2)) {
		echo $row2->$item;
	}
}

function getadate($dtype="d/m/Y") {
	global $dtr, $row, $prefix, $settings;
	$tempdate = date("Y-m-d H:i:s", strtotime($row->date));
	echo format_date($tempdate, $dtype, $settings['gmt']);
}

function getatime($dtype="H:i") {
	global $dtr, $row, $prefix, $settings;
	$tempdate = date("Y-m-d H:i:s", strtotime($row->time));
	echo format_date($tempdate, $dtype, $settings['gmt']);
}

function format($dt) {
	global $dtr;
	if (strlen($dt) == 8) {
		$dtr['ye'] = substr($dt,0,4);
		$dtr['mo'] = substr($dt,4,2);
		$dtr['da'] = substr($dt,6,2);
		echo $dtr['da']."/".$dtr['mo']."/".$dtr['ye'];
	}
	if (strlen($dt) == 4) {
		$dtr['ho'] = substr($dt,0,2);
		$dtr['mi'] = substr($dt,2,2);
		echo $dtr['ho'].":".$dtr['mi'];
	}
}

function permalink() {
	global $settings, $row, $prefix, $PHP_SELF;
	echo "<a href=\"$PHP_SELF?p=$row->aid&amp;c=1\">$row->title</a>";
}

function body() {
	global $settings, $row, $prefix, $p, $PHP_SELF;
	$body = cleanstring($row->body);
	$body = bbcode($body);
	if (!$p) {
		echo $body."</p>";
	} elseif ($p && $row->show_body == 1) {
		echo $body."</p>";
	}
	if ($row->more && !$p) {
		echo "<p><a href=\"$PHP_SELF?p=$row->aid&amp;c=1\">".$settings[more]."</a></p>";
	} elseif ($row->more && $p) {
		$more = cleanstring($row->more);
		$more = bbcode($more);
		echo "<p>".$more."</p>";
	}
}

function commentlink ($none="no comment",$single="1 comment",$more="% comments") {
	global $row, $prefix, $settings, $PHP_SELF;
	if ($settings[comments] == 1 && $row->show_comments == 1) {
	$count = mysql_query("SELECT * FROM ".$prefix."comments WHERE parentid = $row->aid");
	$total = mysql_num_rows($count);
	if ($total == 0) echo "<a href=\"$PHP_SELF?p=$row->aid&amp;c=1#comments\">$none</a>";
	if ($total == 1) echo "<a href=\"$PHP_SELF?p=$row->aid&amp;c=1#comments\">$single</a>";
	if ($total > 1) {
		$t="$total";
		$more=eregi_replace("\%",$t,$more);
		echo "<a href=\"$PHP_SELF?p=$row->aid&amp;c=1#comments\">$more</a>";
	}
	} else {
	echo $settings[nocomments];
	}
}

function getarticle($item) {
	global $settings, $row, $prefix;
	$result2 = mysql_query("SELECT * FROM ".$prefix."articles WHERE aid = '".$row->aid."'");
	while($row2=mysql_fetch_object($result2)) {
		echo $row2->$item;
	}
}

function getcomment($item) {
	global $settings, $c_row, $prefix, $dtr;
	$result2 = mysql_query("SELECT * FROM ".$prefix."comments WHERE coid = '".$c_row->coid."'");
	while($row2=mysql_fetch_object($result2)) {
		datetime($row2->date);
		datetime($row2->time);

		if ($item == "date") {
			$tempdate = date("Y-m-d H:i:s", strtotime($row2->date));
			echo format_date($tempdate, "d/m/Y", $settings['gmt']);
		}
		if ($item == "time") {
			$tempdate = date("Y-m-d H:i:s", strtotime($row2->time));
			echo format_date($tempdate, "H:i", $settings['gmt']);
		}
		if ($item != "date" && $item != "time") echo $row2->$item;
	}
}

function category () {
	global $row, $prefix;
	$result2 = mysql_query("SELECT * FROM ".$prefix."categories WHERE cid = '".$row->category."'");
	while($row2=mysql_fetch_object($result2)) {
		echo $row2->category;
	}
}

function catlink () {
	global $row, $PHP_SELF;
	echo "$PHP_SELF?cat=$row->category";
}

function userlink () {
	global $row, $PHP_SELF;
	echo "$PHP_SELF?user=$row->username";
}

function comment() {
	global $settings, $c_row, $prefix, $p;
	$result2 = mysql_query("SELECT * FROM ".$prefix."comments WHERE coid = '".$c_row->coid."'");
	while($row2=mysql_fetch_object($result2)) {
		$comment = cleanstring($row2->comment);
		$comment = bbcode($comment,1);
		echo $comment;
	}
}

?>