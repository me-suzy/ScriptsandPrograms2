<?php
function hilite($var) {
return "<span style='background-color: #FFFF00'>".$var."</span>";
}

function points() {
global $useridn;
global $points1;
global $points2;
global $points3;
global $points4;
global $points5;
global $points6;
global $points7;
global $points8;
global $points9;
global $points10;
global $points11;
global $points12;

if ($_COOKIE[username]) {

$p1 = mysql_num_rows(mysql_query("SELECT * FROM onecms_posts WHERE type = 'post' AND uid = '".$useridn."'"));
$p11 = $p1 * $points1;

$p2 = mysql_num_rows(mysql_query("SELECT * FROM onecms_posts WHERE type = 'topic' OR type = 'Sticky' OR type = 'Announcement' AND uid = '".$useridn."'"));
$p22 = $p2 * $points2;

$p3 = mysql_num_rows(mysql_query("SELECT * FROM onecms_userreviews WHERE user = '".$_COOKIE[username]."'"));
$p33 = $p3 * $points3;

$p5 = mysql_num_rows(mysql_query("SELECT * FROM onecms_comments2 WHERE name = '".$_COOKIE[username]."'"));
$p55 = $p5 * $points5;

$p6 = mysql_num_rows(mysql_query("SELECT * FROM onecms_friends WHERE pid = '".$useridn."' OR pid2 = '".$useridn."' AND ver = 'yes'"));
$p66 = $p6 * $points6;

$p7 = mysql_num_rows(mysql_query("SELECT * FROM onecms_elite WHERE pid = '".$useridn."' AND type = 'tracked'"));
$p77 = $p7 * $points7;

$p8 = mysql_num_rows(mysql_query("SELECT * FROM onecms_elite WHERE pid = '".$useridn."' AND type = 'collection'"));
$p88 = $p8 * $points8;

$p9 = mysql_num_rows(mysql_query("SELECT * FROM onecms_elite WHERE pid = '".$useridn."' AND type = 'wishlist'"));
$p99 = $p9 * $points9;

$p10 = mysql_num_rows(mysql_query("SELECT * FROM onecms_elite WHERE pid = '".$useridn."' AND type = 'playing'"));
$p101 = $p10 * $points10;

$p11 = mysql_num_rows(mysql_query("SELECT * FROM onecms_elite WHERE pid = '".$useridn."' AND type = 'favorites'"));
$p111 = $p11 * $points11;

$p12 = mysql_num_rows(mysql_query("SELECT * FROM onecms_elite WHERE pid = '".$useridn."' AND type = 'systems'"));
$p121 = $p12 * $points12;

$points = $p11 + $p22 + $p33 + $p44 + $p55 + $p66 + $p77 + $p88 + $p99 + $p101 + $p111 + $p121;

return $points;
} else {
$points = "0";
return $points;
}
}

function cfields_check1() {
$query = mysql_query("SELECT * FROM onecms_fields WHERE cat = 'games' ORDER BY `id` DESC") or die(mysql_error());
while($row = mysql_fetch_array($query)) {
$name = "$row[name]";

$query2 = mysql_query("SELECT ".$name.",id FROM onecms_games ORDER BY `id` DESC") or die(mysql_error());
while($r = mysql_fetch_array($query2)) {
$query3 = mysql_num_rows(mysql_query("SELECT * FROM onecms_fielddata WHERE name = '".$name."' AND id2 = '".$r["id"]."' AND cat = 'games' AND data = '".$r["$name"]."'"));
if (($query3 == "0") && ($r["$name"])) {
mysql_query("INSERT INTO onecms_fielddata VALUES ('null', '".$name."', '".$r["$name"]."', '".$r["id"]."', 'games')");
}
}
}
}

function cfields_check2() {
$query = mysql_query("SELECT * FROM onecms_fields WHERE cat = '' ORDER BY `id` DESC") or die(mysql_error());
while($row = mysql_fetch_array($query)) {
$name = "$row[name]";

$query2 = mysql_query("SELECT ".$name.",id FROM onecms_content ORDER BY `id` DESC") or die(mysql_error());
while($r = mysql_fetch_array($query2)) {
$query3 = mysql_num_rows(mysql_query("SELECT * FROM onecms_fielddata WHERE name = '".$name."' AND id2 = '".$r["id"]."' AND cat = 'content' AND data = '".$r["$name"]."'"));
if (($query3 == "0") && ($r["$name"])) {
mysql_query("INSERT INTO onecms_fielddata VALUES ('null', '".$name."', '".$r["$name"]."', '".$r["id"]."', 'content')");
}
}
}
}

function cfields_check3() {
$query5 = mysql_query("SELECT * FROM onecms_cat ORDER BY `id` DESC") or die(mysql_error());
while($rowa = mysql_fetch_array($query5)) {

$query = mysql_query("SELECT * FROM onecms_fields WHERE cat = '".$rowa[name]."' ORDER BY `id` DESC") or die(mysql_error());
while($row = mysql_fetch_array($query)) {
$name = "$row[name]";

$query2 = mysql_query("SELECT ".$name.",id FROM onecms_content ORDER BY `id` DESC") or die(mysql_error());
while($r = mysql_fetch_array($query2)) {
$query3 = mysql_num_rows(mysql_query("SELECT * FROM onecms_fielddata WHERE name = '".$name."' AND id2 = '".$r["id"]."' AND cat = 'content' AND data = '".$r["$name"]."'"));
if (($query3 == "0") && ($r["$name"])) {
mysql_query("INSERT INTO onecms_fielddata VALUES ('null', '".$name."', '".$r["$name"]."', '".$r["id"]."', 'content')");
}
}
}
}
mysql_query("ALTER TABLE `onecms_content` ADD `games` TEXT NOT NULL");
mysql_query("ALTER TABLE `onecms_content` ADD `systems` TEXT NOT NULL");

$sql = mysql_query("SELECT * FROM onecms_content");
while($row = mysql_fetch_array($sql)) {

$fet1 = mysql_fetch_row(mysql_query("SELECT data FROM onecms_fielddata WHERE id2 = '".$row[id]."' AND cat = 'content' AND name = 'systems'"));
mysql_query("UPDATE onecms_content SET systems = '".$fet1[0]."' WHERE id = '".$row[id]."'");
$fet2 = mysql_fetch_row(mysql_query("SELECT data FROM onecms_fielddata WHERE id2 = '".$row[id]."' AND cat = 'content' AND name = 'games'"));
mysql_query("UPDATE onecms_content SET games = '".$fet2[0]."' WHERE id = '".$row[id]."'");
}

$sql2 = mysql_query("SELECT * FROM onecms_fielddata WHERE name = 'games' OR name = 'systems'");
while($r = mysql_fetch_array($sql2)) {
mysql_query("DELETE FROM onecms_fielddata WHERE id = '".$r[id]."'");
}
}

function cfields_check4 () {
// content check, any custom fields or extra fields will be deleted
$sql = mysql_query("SHOW COLUMNS FROM onecms_content");
   while ($row = mysql_fetch_assoc($sql)) {
	   $name = "$row[Field]";
	   if ((((((((((($name == "id") or ($name == "name") or ($name == "cat") or ($name == "username") or ($name == "postpone") or ($name == "lev") or ($name == "stats") or ($name == "date") or ($name == "ver") or ($name == "games") or ($name == "systems"))))))))))) {
	   } else {
       mysql_query("ALTER TABLE onecms_content DROP `$name`");
	   }
   }
// games check, any custom fields or extra fields will be deleted
$sql = mysql_query("SHOW COLUMNS FROM onecms_games");
   while ($row = mysql_fetch_assoc($sql)) {
	   $name = "$row[Field]";
	   if (((((((((((((($name == "id") or ($name == "name") or ($name == "stats") or ($name == "username") or ($name == "publisher") or ($name == "developer") or ($name == "genre") or ($name == "release") or ($name == "esrb") or ($name == "boxart") or ($name == "des") or ($name == "skin") or ($name == "system") or ($name == "album")))))))))))))) {
	   } else {
       mysql_query("ALTER TABLE onecms_games DROP `$name`");
	   }
   }
}

function ss_type_check() {
$abcd = "http://www";
$query = mysql_query("SELECT * FROM onecms_images WHERE name LIKE '%" . $abcd . "%' ORDER BY `id`");
$iy = mysql_num_rows($query);
for($i = 0; $r = mysql_fetch_assoc($query); $i++) {
if ($r[type2] == "ss") {
mysql_query("UPDATE onecms_images SET type2 = 'ss2' WHERE id = '".$r[id]."'");
}
}
}

function install_footer() {
return "</td></tr></td></tr></table></td></tr></table>";
}

function install_header() {
return "<link rel='stylesheet' type='text/css' href='ta3.css'><table width='743' cellspacing='0' cellpadding='0' border='0' align='center'><tr><td><table cellspacing='0' cellpadding='0' border='0'><tr><td><img src='a_images/install_banner.jpg'></td></tr><tr><td align='center'>";
}

function abclist($url, $type) {
if ($type == "") {
$type = "index.php";
$url2 = "".$url."&";
$url3 = $url;
} else {
$url2 = "".$url."?";
$url3 = $url;
}

return "<center><a href='$type".$url3."'><b>All</b></a>&nbsp;&nbsp;<a href='$type".$url2."abc=A'>A</a>&nbsp;&nbsp;<a href='$type".$url2."abc=B'>B</a>&nbsp;&nbsp;<a href='$type".$url2."abc=C'>C</a>&nbsp;&nbsp;<a href='$type".$url2."abc=D'>D</a>&nbsp;&nbsp;<a href='$type".$url2."abc=E'>E</a>&nbsp;&nbsp;<a href='$type".$url2."abc=F'>F</a>&nbsp;&nbsp;<a href='$type".$url2."abc=G'>G</a>&nbsp;&nbsp;<a href='$type".$url2."abc=H'>H</a>&nbsp;&nbsp;<a href='$type".$url2."abc=I'>I</a>&nbsp;&nbsp;<a href='$type".$url2."abc=J'>J</a>&nbsp;&nbsp;<a href='$type".$url2."abc=K'>K</a>&nbsp;&nbsp;<a href='$type".$url2."abc=L'>L</a>&nbsp;&nbsp;<a href='$type".$url2."abc=M'>M</a>&nbsp;&nbsp;<a href='$type".$url2."abc=N'>N</a>&nbsp;&nbsp;<a href='$type".$url2."abc=O'>O</a>&nbsp;&nbsp;<a href='$type".$url2."abc=P'>P</a>&nbsp;&nbsp;<a href='$type".$url2."abc=Q'>Q</a>&nbsp;&nbsp;<a href='$type".$url2."abc=R'>R</a>&nbsp;&nbsp;<a href='$type".$url2."abc=S'>S</a>&nbsp;&nbsp;<a href='$type".$url2."abc=T'>T</a>&nbsp;&nbsp;<a href='$type".$url2."abc=U'>U</a>&nbsp;&nbsp;<a href='$type".$url2."abc=V'>V</a>&nbsp;&nbsp;<a href='$type".$url2."abc=W'>W</a>&nbsp;&nbsp;<a href='$type".$url2."abc=X'>X</a>&nbsp;&nbsp;<a href='$type".$url2."abc=Y'>Y</a>&nbsp;&nbsp;<a href='$type".$url2."abc=Z'>Z</a></center><br>";
}

function table($tablename) {
global $dbname;

   $result = mysql_list_tables($dbname);
   $rcount = mysql_num_rows($result);

   for ($i=0;$i<$rcount;$i++) {
       if (mysql_tablename($result, $i)==$tablename) return true;
   }
   return false;
}

function esc($str) {
    $arr = array();
    if (is_array($str)) {
        foreach($str as $k => $v)
            // mmm, recursion
            $arr[$k] = escape($v);
        return $arr;
    } else {
        return mysql_escape_string($str);
    }
}

function abc($srcfilename, $watermark, $quality) {
global $path;
global $watermark2;

$imageInfo = getimagesize($srcfilename); 
$width = $imageInfo[0]; 
$height = $imageInfo[1]; 
$logoinfo = getimagesize($watermark);
$logowidth = $logoinfo[0];
$logoheight = $logoinfo[1];
$horizextra =$width - $logowidth;
$vertextra =$height - $logoheight;
$horizmargin =  round($horizextra / 2);
$vertmargin =  round($vertextra / 2);
$photoImage = ImageCreateFromJPEG($srcfilename);
ImageAlphaBlending($photoImage, true);
if ($watermark2 == "png") {
$logoImage = imagecreatefrompng($watermark);  
}
if ($watermark2 == "gif") {
$logoImage = imagecreatefromgif($watermark);  
}
if (($watermark2 == "jpg") or ($watermark2 == "jpeg")) {
$logoImage = imagecreatefromjpeg($watermark); 
}
$logoW = ImageSX($logoImage);
$logoH = ImageSY($logoImage);
ImageCopy($photoImage, $logoImage, $horizmargin, $vertmargin, 0, 0, $logoW, $logoH);
ImageJPEG($photoImage,'', $quality);
ImageDestroy($photoImage);
ImageDestroy($logoImage);
return $photoImage;
}

function watermark($image2) {
global $watermark;
global $watermark2;
global $quality;
global $path;

$aimage = "$path/$image2";

if ($watermark2 == "png") {
$watermark = imagecreatefrompng($watermark);
}
if ($watermark2 == "gif") {
$watermark = imagecreatefromgif($watermark);
}
if (($watermark2 == "jpg") or ($watermark2 == "jpeg")) {
$watermark = imagecreatefromjpeg($watermark);
}
$watermark_width = imagesx($watermark);
$watermark_height = imagesy($watermark);
$image = imagecreatetruecolor($watermark_width, $watermark_height);
$image = imagecreatefromjpeg($aimage);
$size = getimagesize($image);
$dest_x = $size[0] - $watermark_width - 5;
$dest_y = $size[1] - $watermark_height - 5;
imagecopymerge($image, $watermark, $dest_x, $dest_y, 0, 0, $watermark_width, $watermark_height, 100);
imagejpeg($image);
imagedestroy($image);
imagedestroy($watermark);
}

function lcontent($cat, $temp, $gid) {
	global $dformat;

	$res = mysql_query("SELECT * FROM onecms_templates WHERE name = '".$temp."'");
    while($rwa = mysql_fetch_array($res)) {
    $tem = stripslashes($rwa[template]);
    }
    
	if ($cat) {
	$lcontent = mysql_query("SELECT * FROM onecms_content WHERE games = '".$gid."' AND cat = '".$cat."' ORDER BY `date` DESC");
	} else {
	$lcontent = mysql_query("SELECT * FROM onecms_content WHERE games = '".$gid."' ORDER BY `date` DESC");
	}
	while($lc = mysql_fetch_array($lcontent)) {

	$systems = $lc[systems];

	$squery = mysql_query("SELECT icon,name FROM onecms_systems WHERE id = '".$systems."'");
	$systemsa = mysql_fetch_row($squery);

	    $pry[0] = "/{name}/";
		$pry[1] = "/{link}/";
		$pry[2] = "/{date}/";
		$pry[3] = "/{id}/";
		$pry[4] = "/{icon}/";
		$pry[5] = "/{cat}/";
		$ary[0] = "".stripslashes($lc[name])."";
		$ary[1] = "".$siteurl."/".$part1."".$lc[id]."".$part2."";
		$ary[2] = "".date($dformat, $lc[date])."";
		$ary[3] = "".$lc[id]."";
		$ary[4] = "<img src='".stripslashes($systemsa[0])."'>";
		$ary[5] = $lc[cat];

	echo preg_replace($pry, $ary, $tem);
	}
	}

function footera() {
$query="SELECT * FROM onecms_templates WHERE name = 'footer'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {

    $skin1[] = "/{version}/";
	$skin1[] = "/{users}/"; // amount of users online
	$skin1[] = "/{online}/"; // the users that are online
	$skin1[] = "/{pms}/"; // amount of private messages
	$skin1[] = "/{new}/"; // amount of new private messages
	$skin1[] = "/{pm}/"; // display new private messages
	$skin1[] = "/{welcome}/"; // Displays "Welcome username" if person is logged in, other wise "Welcome Guest" and a link to login
	$skin1[] = "/{chooseskin}/";
	$skin1[] = "/{skinname}/"; // the name of the current skin being used
	$skin2[] = "".$version."";
    $skin2[] = online();
	$skin2[] = usersonline();
	$skin2[] = totalpms();
	$skin2[] = newpms();
	$skin2[] = pms();
	$skin2[] = welcome();
	$skin2[] = "<form action='".$REQUEST_URI."' method='post' name='skin'><select name='skin' onchange='this.form.submit()'><option value='1' selected>-----</option><?php include ('".$siteurl."/a_skins.php?view=skin'); ?></select></form>";
	$skin2[] = skinname();

	 eval (" ?>" . preg_replace($skin1, $skin2, stripslashes($row[template])) . " <?php ");

}
}

function headera() {
$query="SELECT * FROM onecms_templates WHERE name = 'header'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {

    $skin1[] = "/{version}/";
	$skin1[] = "/{users}/"; // amount of users online
	$skin1[] = "/{online}/"; // the users that are online
	$skin1[] = "/{pms}/"; // amount of private messages
	$skin1[] = "/{new}/"; // amount of new private messages
	$skin1[] = "/{pm}/"; // display new private messages
	$skin1[] = "/{welcome}/"; // Displays "Welcome username" if person is logged in, other wise "Welcome Guest" and a link to login
	$skin1[] = "/{chooseskin}/";
	$skin1[] = "/{skinname}/"; // the name of the current skin being used
	$skin2[] = "".$version."";
    $skin2[] = online();
	$skin2[] = usersonline();
	$skin2[] = totalpms();
	$skin2[] = newpms();
	$skin2[] = pms();
	$skin2[] = welcome();
	$skin2[] = "<form action='".$REQUEST_URI."' method='post' name='skin'><select name='skin' onchange='this.form.submit()'><option value='1' selected>-----</option><?php include ('".$siteurl."/a_skins.php?view=skin'); ?></select></form>";
	$skin2[] = skinname();

	 eval (" ?>" . preg_replace($skin1, $skin2, stripslashes($row[template])) . " <?php ");

}
}

function checkemail($email){ 
return preg_match("/^[^\s()<>@,;:\"\/\[\]?=]+@\w[\w-]*(\.\w[\w-]*)*\.[a-z]{2,}$/i",$email); 
}

function top10() {
include ("top10.php");
}

function showtemplate($name) {
$query="SELECT * FROM onecms_templates WHERE name = '".$name."'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {

    $skin1[] = "/{version}/";
	$skin1[] = "/{users}/"; // amount of users online
	$skin1[] = "/{online}/"; // the users that are online
	$skin1[] = "/{pms}/"; // amount of private messages
	$skin1[] = "/{new}/"; // amount of new private messages
	$skin1[] = "/{pm}/"; // display new private messages
	$skin1[] = "/{welcome}/"; // Displays "Welcome username" if person is logged in, other wise "Welcome Guest" and a link to login
	$skin1[] = "/{chooseskin}/";
	$skin1[] = "/{skinname}/"; // the name of the current skin being used
	$skin2[] = "".$version."";
    $skin2[] = online();
	$skin2[] = usersonline();
	$skin2[] = totalpms();
	$skin2[] = newpms();
	$skin2[] = pms();
	$skin2[] = welcome();
	$skin2[] = "<form action='".$REQUEST_URI."' method='post' name='skin'><select name='skin' onchange='this.form.submit()'><option value='1' selected>-----</option><?php include ('".$siteurl."/a_skins.php?view=skin'); ?></select></form>";
	$skin2[] = skinname();

	 eval (" ?>" . preg_replace($skin1, $skin2, stripslashes($row[template])) . " <?php ");

}
}

function copyright() {
return "Powered by <a href='http://www.insanevisions.com/onecms'><b>OneCMS</b></a>";
}

function comments2($id) {
global $avat1;
global $avat2;

$sql = mysql_query("SELECT * FROM onecms_profile WHERE username = '".$id."'");
$m = mysql_num_rows($sql);
while($r = mysql_fetch_array($sql)) {

	$page = @file_get_contents("$r[avatar]");
	if ($page == NULL) {
		$avatar = "";
	} else {
	$avatar = "<img src='".$r[avatar]."'";

	list($widtha, $heighta) = getimagesize("".$r[avatar]."");

	if ($widtha > $avat1) {
	$avatar .= " width='".$avat1."'";
	}
	if ($heighta > $avat2) {
	$avatar .= " height='".$avat1."'";
	}

	$avatar .= " border='1'>";
	}

	if ($m > "0") {
		$user = "<a href='members.php?action=profile&id=".$r[id]."'>".$id."</a>";
	} else {
		$user = "Visitor";
	}
    
	if ($m > "0") {
	$to = mysql_query("SELECT * FROM onecms_posts WHERE type = 'post' AND uid = '".$r[id]."'");
	$posts = mysql_num_rows($to);
	} else {
	$post = "N/A";
	}

    if ($m > "0") {
	$tot = mysql_query("SELECT * FROM onecms_posts WHERE type = 'topic' OR type = 'Announcement' OR type = 'Sticky' AND uid = '".$r[id]."'");
	$topics = mysql_num_rows($tot);
	} else {
	$topics = "N/A";
	}

	return "<center>".$user."<br><br>".$avatar."<br><b>Posts:</b> $posts<br><b>Topics:</b> $topics</center>";
}
}

function comments4($text) {
global $images;

$sm = mysql_query("SELECT * FROM onecms_comments1 WHERE type = 'smiley'");
while ($row = mysql_fetch_array($sm)) {
	  
	  $ty[] = "".$row[name]."";
	  $np[] = "<img src='".$images."/".$row[field]."'>";
}

$sm2 = mysql_query("SELECT * FROM onecms_comments1 WHERE type = 'badword'");
while ($row2 = mysql_fetch_array($sm2)) {
	  
	  $ty[] = "".$row2[name]."";
	  $np[] = "".$row2[field]."";
}
	$signaturey = preg_replace("/<br>\n/","\n",$text);
	$signaturey = preg_replace("/<br\>\n/","\n",$text);
	$signaturey = preg_replace("/(\015\012)|(\015)|(\012)/","<br>\n",$text);

	return str_replace($ty, $np, $signaturey);
}

function comments3($id) {
global $images;

$sql = mysql_query("SELECT * FROM onecms_profile WHERE username = '".$id."'");
$m = mysql_num_rows($sql);

if ($m > "0") {
while($r = mysql_fetch_array($sql)) {
	
$sm = mysql_query("SELECT * FROM onecms_comments1 WHERE type = 'smiley'");
while ($row = mysql_fetch_array($sm)) {
	  
	  $ty[] = "".$row[name]."";
	  $np[] = "<img src='".$images."/".$row[field]."'>";
}

$sm2 = mysql_query("SELECT * FROM onecms_comments1 WHERE type = 'badword'");
while ($row2 = mysql_fetch_array($sm2)) {
	  
	  $ty[] = "".$row2[name]."";
	  $np[] = "".$row2[field]."";
}

$sig = "<br>---------------<br>".$r[sig]."";

	$signature2 = stripslashes($sig);
	$signaturey = preg_replace("/<br>\n/","\n",$signature2);
	$signaturey = preg_replace("/<br\>\n/","\n",$signature2);
	$signaturey = preg_replace("/(\015\012)|(\015)|(\012)/","<br>\n",$signature2);

	return str_replace($ty, $np, $signaturey);
}
}
}

function comments($cid) {
$sql = mysql_query("SELECT * FROM onecms_comments2 WHERE aid = '".$cid."' ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {

$sql2 = mysql_query("SELECT id FROM onecms_profile WHERE username = '".$r[name]."'");
$amountid = mysql_num_rows($sql2);
$numberid = mysql_fetch_row($sql2);

	if ($amountid > "0") {
		$user = "<a href='members.php?action=profile&id=".$numberid[0]."'>".$r[name]."</a>";
	} else {
		$user = "Visitor";
	}

	return "<tr><td><center><b>Profile</b></center></td><td><b>".$r[subject]."</b> posted by ".$user." at <i>".date($dformat, $r['date'])."</i></td></tr><tr><td>".comments2("".$r[name]."")."</td><td>".comments4("<p>".stripslashes($r[comment])."</p>")."".comments3("".$r[name]."")."</td></tr>";
}
}

function ad($name, $type) {
global $siteurl;

if (($type == "group") or ($type == "Group")) {

$fetch = mysql_query("SELECT id FROM onecms_ad WHERE name = '".$name."' AND type = 'group'");
$yeah = mysql_fetch_row($fetch);

$sql = mysql_query("SELECT * FROM onecms_ad WHERE grp = '".$yeah[0]."' ORDER BY RAND() DESC LIMIT 1");
} else {
$sql = mysql_query("SELECT * FROM onecms_ad WHERE name = '".$name."' LIMIT 1");
}
while($row = mysql_fetch_array($sql)) {
$type2 = "$row[type]";

$val = $row[views] + 1;

mysql_query("UPDATE onecms_ad SET views = '".$val."' WHERE id = '".$row[id]."'");

$ex = explode("/", $row[dim]);

if ($type2 == "coding") {
if (($_COOKIE[username]) && ($row[user] == "Yes")) {
return stripslashes($row[coding]);
}

if ($_COOKIE[username] == "") {
return stripslashes($row[coding]);
}
}

if ($type2 == "image") {
if (($_COOKIE[username]) && ($row[user] == "Yes")) {
$img = "<img src='".stripslashes($row[coding])."'";
if ($ex[0]) {
$img .= " width='".$ex[0]."'";
}
if ($ex[1]) {
$img .= " height='".$ex[1]."'";
}
$img .= ">";
return $img;
}
if ($_COOKIE[username] == "") {
$img = "<img src='".stripslashes($row[coding])."'";
if ($ex[0]) {
$img .= " width='".$ex[0]."'";
}
if ($ex[1]) {
$img .= " height='".$ex[1]."'";
}
$img .= ">";
return $img;
}
}

if ($type2 == "flash") {

	if (($_COOKIE[username]) && ($row[user] == "Yes")) {
	
	return "<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0'><param name='movie' value='".stripslashes($row[coding])."'><param name='quality' value='high'><embed src='".stripslashes($row[coding])."' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'></embed></object>";
	}

	if ($_COOKIE[username] == "") {

	return "<object classid='clsid:D27CDB6E-AE6D-11cf-96B8-444553540000' codebase='http://download.macromedia.com/pub/shockwave/cabs/flash/swflash.cab#version=6,0,29,0'><param name='movie' value='".stripslashes($row[coding])."'><param name='quality' value='high'><embed src='".stripslashes($row[coding])."' quality='high' pluginspage='http://www.macromedia.com/go/getflashplayer' type='application/x-shockwave-flash'></embed></object>";
	}
}

}
}

function af($limit) {
global $siteurl;

if ($limit) {
@include ("".$siteurl."/af.php?limit=".$limit."");
} else {
@include ("".$siteurl."/af.php");
}
}

function gallery($limit, $template) {
global $gpart1;
global $gpart2;

$sqla = mysql_query("SELECT * FROM onecms_templates WHERE name = '".$template."'");
while($user = mysql_fetch_array($sqla)) {
	$temp = "".stripslashes($user[template])."";
}

if ($limit == "") {
$sql = mysql_query("SELECT * FROM onecms_albums ORDER BY `id` DESC");
} else {
$sql = mysql_query("SELECT * FROM onecms_albums ORDER BY `id` DESC LIMIT ".$limit."");
}
while($row = mysql_fetch_array($sql)) {

	$systems = $row[systems];

	$squery = mysql_query("SELECT icon,name FROM onecms_systems WHERE id = '".$systems."'");
	$systemsa = mysql_fetch_row($squery);

        $pryr[0] = "/{name}/";
		$pryr[1] = "/{link}/";
		$pryr[2] = "/{views}/";
		$pryr[3] = "/{system}/";
		$pryr[4] = "/{icon}/";
		$pryr[5] = "/{id}/";
		$aryr[0] = "".stripslashes($row[name])."";
		$aryr[1] = "".$gpart1."".$row[id]."".$gpart2."";
		$aryr[2] = "".$row[views]."";
		$aryr[3] = "".$systems[1]."";
		if ($systems[0]) {
		$aryr[4] = "<img src='".stripslashes($systemsa[0])."'>";
		} else {
		$aryr[4] = "";
		}
		$aryr[5] = $row[id];

eval (" ?>" . preg_replace($pryr, $aryr, $temp) . " <?php ");
}
}

function systemslist($template, $limit) {
global $siteurl;

$sql = mysql_query("SELECT * FROM onecms_templates WHERE name = '".$template."'");
while($user = mysql_fetch_array($sql)) {
	$temp = "".stripslashes($user[template])."";
}

if ($limit == "") {
$query = mysql_query("SELECT * FROM onecms_systems");
} else {
$query = mysql_query("SELECT * FROM onecms_systems LIMIT ".$limit."");
}
    while ($row = mysql_fetch_array($query)) {

        $pryr[0] = "/{name}/";
		$pryr[1] = "/{abr}/";
		$pryr[2] = "/{link}/";
		$pryr[3] = "/{icon}/";
		$aryr[0] = "".$row[name]."";
		$aryr[1] = "".$row[abr]."";
		$aryr[2] = "".$siteurl."/index.php?id=systems&sid=".$row[abr]."";
		$aryr[3] = "<img src='".$row[icon]."'>";

		echo preg_replace($pryr, $aryr, $temp);
    }
}

function games($num, $template) {
global $siteurl;
global $dformat;
global $gamepart1;
global $gamepart2;

$sql = mysql_query("SELECT * FROM onecms_templates WHERE name = '".$template."'");
while($user = mysql_fetch_array($sql)) {
	$temp = "".stripslashes($user[template])."";
}

if ($num) {
$query = mysql_query("SELECT * FROM onecms_games ORDER BY `id` DESC LIMIT ".$num."");
} else {
$query = mysql_query("SELECT * FROM onecms_games ORDER BY `id` DESC LIMIT 3");
}
while ($row = mysql_fetch_array($query)) {

        $pryr[0] = "/{name}/";
		$pryr[1] = "/{link}/";
		$pryr[2] = "/{date}/";
		$pryr[3] = "/{genre}/";
		$aryr[0] = "".$row[name]."";
		$aryr[1] = "".$gamepart1."".$row[id]."".$gamepart2."";
		$aryr[2] = "".date($dformat, $row[date])."";
		$aryr[3] = "".$row[genre]."";

		echo preg_replace($pryr, $aryr, $temp);
    }
}

function cgames($cat, $num, $template, $game, $system) {
global $siteurl;
global $dformat;
global $gamepart1;
global $gamepart2;

$sql = mysql_query("SELECT * FROM onecms_templates WHERE name = '".$template."'");
while($user = mysql_fetch_array($sql)) {
	$temp = "".stripslashes($user[template])."";
}

    if ($cat == "") {
    $queryrra = mysql_query("SELECT * FROM onecms_content WHERE games = '".$game."' ORDER BY `id` DESC LIMIT ".$num."");
	} else {
	$queryrra = mysql_query("SELECT * FROM onecms_content WHERE cat = '".$cat."' AND games = '".$game."' ORDER BY `id` DESC LIMIT ".$num."");
	}
    while ($row = mysql_fetch_array($queryrra)) {

        $pryr[0] = "/{name}/";
		$pryr[1] = "/{link}/";
		$pryr[2] = "/{date}/";
		$pryr[3] = "/{cat}/";
		$aryr[0] = "".$row[name]."";
		$aryr[1] = "".$part1."".$row[id]."".$part2."";
		$aryr[2] = "".date($dformat, $row[date])."";
		$aryr[3] = "".$row[cat]."";

		echo preg_replace($pryr, $aryr, $temp);
    }
}

function content($cat, $num, $template) {
global $siteurl;
global $dformat;
global $part1;
global $part2;

$sql = mysql_query("SELECT * FROM onecms_templates WHERE name = '".$template."'");
while($user = mysql_fetch_array($sql)) {
	$temp = "".stripslashes($user[template])."";
}

    if ($cat == "") {
    $queryrra = mysql_query("SELECT * FROM onecms_content ORDER BY `id` DESC LIMIT ".$num."");
	} else {
	$queryrra = mysql_query("SELECT * FROM onecms_content WHERE cat = '".$cat."' ORDER BY `id` DESC LIMIT ".$num."");
	}
    while ($row = mysql_fetch_array($queryrra)) {
	$cat = "$row[cat]";

	$systems = $row[systems];

	$shortquery = mysql_fetch_row(mysql_query("SELECT data FROM onecms_fielddata WHERE id2 = '".$row[id]."' AND name = 'Short' AND cat = 'content'"));
	$short = stripslashes($shortquery[0]);

    $mcount = mysql_num_rows(mysql_query("SELECT * FROM onecms_fields WHERE name = 'newsicon1'"));
    
	if ($mcount > "0") {
	$niconquery = mysql_fetch_row(mysql_query("SELECT data FROM onecms_fielddata WHERE id2 = '".$row[id]."' AND name = 'newsicon1' AND cat = 'content'"));
	$nicon = $niconquery[0];
	}

	$squery = mysql_query("SELECT icon,name FROM onecms_systems WHERE id = '".$systems."'");
	$systemsa = mysql_fetch_row($squery);

		$query2 = mysql_query("SELECT id FROM onecms_profile WHERE username = '".$row[username]."'");
		$systems2 = mysql_fetch_row($query2);

        $pryr[0] = "/{name}/";
		$pryr[1] = "/{link}/";
		$pryr[2] = "/{date}/";
		$pryr[3] = "/{cat}/";
		$pryr[4] = "/{icon}/";
		$pryr[5] = "/{username}/";
		$pryr[6] = "/{userid}/";
		if ($row[cat] == "news") {
		$pryr[7] = "/{Short}/";
		}
		if ($mcount > "0") {
		$pryr[8] = "/{newsicon1}/";
		}
		$aryr[0] = "".stripslashes($row[name])."";
		$aryr[1] = "".$part1."".$row[id]."".$part2."";
		$aryr[2] = "".date($dformat, $row[date])."";
		$aryr[3] = "".$row[cat]."";
		if ($systems[0]) {
		$aryr[4] = "<img src='".stripslashes($systemsa[0])."'>";
		} else {
		$aryr[4] = "";
		}
		$aryr[5] = "<a href='members.php?action=profile&id=".$systems2[0]."'>".$row[username]."</a>";
		$aryr[6] = $systems2[0];
		if ($row[cat] == "news") {
		$aryr[7] = $short;
		}
		if ($mcount > "0") {
		$aryr[8] = $nicon;
		}

eval (" ?>" . preg_replace($pryr, $aryr, $temp) . " <?php ");
    }
}

function systems($cat, $system, $num, $template) {
global $siteurl;
global $dformat;

$sql = mysql_query("SELECT * FROM onecms_templates WHERE name = '".$template."'");
while($user = mysql_fetch_array($sql)) {
	$temp = "".stripslashes($user[template])."";
}
if ($cat) {
$queryrra = mysql_query("SELECT * FROM onecms_content WHERE cat = '".$cat."' AND systems = '".$system."'  ORDER BY `id` DESC LIMIT ".$num."");
} else {
$queryrra = mysql_query("SELECT * FROM onecms_content WHERE systems = '".$system."'  ORDER BY `id` DESC LIMIT ".$num."");
}
while ($row = mysql_fetch_array($queryrra)) {

	$systems = $row[systems];

	$squery = mysql_query("SELECT icon,name FROM onecms_systems WHERE id = '".$systems."'");
	$systemsa = mysql_fetch_row($squery);

        $pryr[0] = "/{name}/";
		$pryr[1] = "/{link}/";
		$pryr[2] = "/{date}/";
		$pryr[3] = "/{cat}/";
        $pryr[4] = "/{icon}/";
		$aryr[0] = "".$row[name]."";
		$aryr[1] = "".$part1."".$row[id]."".$part2."";
		$aryr[2] = "".date($dformat, $row[date])."";
		$aryr[3] = "".$row[cat]."";
        $aryr[4] = "<img src='".stripslashes($systemsa[0])."'>";

		echo preg_replace($pryr, $aryr, $temp);
    }
}

function posts($forum, $num, $template) {
global $siteurl;
global $dformat;

$sql = mysql_query("SELECT * FROM onecms_templates WHERE name = '".$template."'");
while($user = mysql_fetch_array($sql)) {
	$temp = "".stripslashes($user[template])."";
}

    if ($forum == "") {
    $queryrra = mysql_query("SELECT * FROM onecms_posts WHERE type = 'post' ORDER BY `id` DESC LIMIT ".$num."");
	} else {
    $queryrra = mysql_query("SELECT * FROM onecms_posts WHERE type = 'post' AND fid = '".$forum."' ORDER BY `id` DESC LIMIT ".$num."");
	}
    while ($row = mysql_fetch_array($queryrra)) {
		if ($row[subject] == "") {
			$subject = "No Subject";
		} else {
			$subject = $row[subject];
		}

        $pryr[0] = "/{subject}/";
		$pryr[1] = "/{username}/";
		$pryr[2] = "/{userid}/";
		$pryr[3] = "/{plink}/";
		$pryr[4] = "/{link}/";
		$pryr[5] = "/{date}/";
		$aryr[0] = "".$subject."";
		$use = mysql_query("SELECT * FROM onecms_profile WHERE id = '".$row[uid]."'");
		while ($row2 = mysql_fetch_array($use)) {
		$aryr[1] = "".$row2[username]."";
		}
		$aryr[2] = "".$row[uid]."";
		$aryr[3] = "".$siteurl."/elite.php?user=".$row[uid]."";
		$aryr[4] = "".$siteurl."/boards.php?t=".$row[tid]."#".$row[id]."";
		$aryr[5] = "".date($dformat, $row[date])."";

		echo preg_replace($pryr, $aryr, $temp);
    }
}

function topics($forum1, $num2, $template3) {
global $siteurl;
global $dformat;

$sql = mysql_query("SELECT * FROM onecms_templates WHERE name = '".$template3."'");
while($user = mysql_fetch_array($sql)) {
	$tem = "".stripslashes($user[template])."";
}

    if ($forum1 == "") {
    $queryrra = mysql_query("SELECT * FROM onecms_posts WHERE type = 'topic' ORDER BY `id` DESC LIMIT ".$num2."");
	} else {
	$queryrra = mysql_query("SELECT * FROM onecms_posts WHERE type = 'topic' AND fid = '".$forum1."' ORDER BY `id` DESC LIMIT ".$num2."");
	}
    while ($row = mysql_fetch_array($queryrra)) {

		$qu = mysql_query("SELECT * FROM onecms_posts WHERE type = 'post' AND tid = '".$row[id]."'");
		$row2 = mysql_num_rows($qu);

        $pry[0] = "/{subject}/";
		$pry[1] = "/{username}/";
		$pry[2] = "/{userid}/";
		$pry[3] = "/{link}/";
		$pry[4] = "/{date}/";
		$pry[5] = "/{replies}/";
		$ary[0] = "".$row[subject]."";
		$use = mysql_query("SELECT * FROM onecms_profile WHERE id = '".$row[uid]."'");
		while ($row2 = mysql_fetch_array($use)) {
		$ary[1] = "".$row2[username]."";
		}
		$ary[2] = "".$row[uid]."";
		$ary[3] = "".$siteurl."/boards.php?t=".$row[tid]."#".$row[id]."";
		$ary[4] = "".date($dformat, $row[date])."";
		$ary[5] = "".$row2."";

		echo preg_replace($pry, $ary, $tem);
    }
}

function online() {
$sqlt = mysql_query("SELECT * FROM onecms_users WHERE logged = '".time()."'");
$numbera = mysql_num_rows($sqlt);
return $numbera;
}

function skinname() {
global $skins;

$skinab = mysql_query("SELECT * FROM onecms_skins WHERE id = '".$skins."'");
while($ske = mysql_fetch_array($skinab)) {
	return "".$ske[name]."";
}
}

function usersonline() {
$sql = mysql_query("SELECT * FROM onecms_users WHERE logged = '".time()."'");
while($user = mysql_fetch_array($sql)) {
$loggedd = "$user[logged]";
$l = "$user[level]";

$sqlbb = mysql_query("SELECT * FROM onecms_profile WHERE username = '".$user[username]."'");
while($bb = mysql_fetch_array($sqlbb)) {
return "&raquo; <a href='members.php?action=profile&id=".$bb[id]."'>$user[username]</a><br>";
}
}
}

function totalpms() {
$result2 = mysql_query("SELECT * FROM onecms_pm WHERE jo = '".$_COOKIE[username]."'") or die(mysql_error());
$number = mysql_num_rows($result2);
return $number;
}

function newpms() {
$result2b = mysql_query("SELECT * FROM onecms_pm WHERE jo = '".$_COOKIE[username]."' AND viewed = '1'") or die(mysql_error());
$numberb = mysql_num_rows($result2b);
return $numberb;
}

function pms() {
$query="SELECT * FROM onecms_pm WHERE jo = '".$_COOKIE[username]."' ORDER BY `id` DESC LIMIT 5";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {

if ($row[viewed] == "1") {

return "+</font> <i><a href=\"inbox.php?msg=$row[id]\">$row[subject]</a></i><br><br>";

} else {
return "+ <a href=\"inbox.php?msg=$row[id]\">$row[subject]</a><br><br>";
}
	}
}

function welcome() {
global $siteurl;

if ($_COOKIE[username] == "") {
	return "Welcome Guest, please <a href='members.php?action=login&step=1'>login</a> or <a href='members.php?action=register'>register</a>.";
} else {
	return "Welcome back <a href='".$siteurl."/members.php?action=profile'><font color='silver'>".$_COOKIE[username]."</font></a>! <a href='elite.php'><img src='".$siteurl."/a_images/profile.jpg' border='0' width='74' height='9'></a>";
}
}

function movie($id, $url) {

$query = mysql_query("SELECT * FROM onecms_images WHERE album = '".$id."'");
while ($row = mysql_fetch_array($query)) {
echo "<embed src='".$url."' autostart='true' showstatusbar='1' showtracking='1' loop='false' save='true' border='0'>";
}
}

//album id, url to video

function members($num, $template) {
global $siteurl;

$sql = mysql_query("SELECT * FROM onecms_templates WHERE name = '".$template."'");
while($user = mysql_fetch_array($sql)) {
	$temp = "".stripslashes($user[template])."";
}

if ($num) {
$sql = mysql_query("SELECT * FROM onecms_users LIMIT ".$num."");
} else {
$sql = mysql_query("SELECT * FROM onecms_users LIMIT ".$num."");
}

    while ($row = mysql_fetch_array($sql)) {

        $pry[0] = "/{username}/";
		$pry[1] = "/{email}/";
		$pry[2] = "/{userid}/";
		$pry[3] = "/{plink}/";
		$pry[4] = "/{posts}/";
		$pry[5] = "/{topics}/";
		$ary[0] = "".$row[username]."";
		$ary[1] = "".$row[email]."";
		$query = mysql_query("SELECT id FROM onecms_profile WHERE username = '".$row[username]."'");
		$fetch = mysql_fetch_row($query);
		$ary[2] = "".$fetch[0]."";
		$ary[3] = "".$siteurl."/members.php?action=profile&id=".$fetch[0]."";

		$pos = mysql_query("SELECT * FROM onecms_posts WHERE uid = '".$fetch[0]."' AND type = 'post'");
		$posts = mysql_num_rows($pos);

		$top = mysql_query("SELECT * FROM onecms_posts WHERE uid = '".$fetch[0]."' AND type = 'topic'");
		$topics = mysql_num_rows($top);

		$ary[4] = "".$posts."";
		$ary[5] = "".$topics."";

		echo preg_replace($pry, $ary, $temp);
    }
}
?>