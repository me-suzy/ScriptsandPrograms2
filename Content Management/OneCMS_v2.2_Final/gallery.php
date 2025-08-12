<?php
$la = "a";
$z = "b";
@include ("config.php");

if(!isset($_GET['p'])){
    $page = 1;
} else {
    $page = $_GET['p'];
}

$from = (($page * $albpage) - $albpage);

headera();

echo '<script language="javascript">
function awindow(towhere, newwinname, properties) {
window.open(towhere,newwinname,properties);
}
</script>';

if ($_GET['id'] == "") {
if ($_GET['title']) {
$title = str_replace("-", " ", $_GET['title']);
echo "<title>".$title."</title>";
}
echo "<table align='center' border='0' cellpadding='3' cellspacing='2'><tr>";
if ($_GET['s'] == "") {
$query = "SELECT * FROM onecms_albums ORDER BY `id` DESC";
} else {
$query = "SELECT * FROM onecms_albums WHERE systems = '".$_GET['s']."' ORDER BY `id` DESC";
}

$query .= " LIMIT $from, $albpage";

$query2 = mysql_query($query);
$iy = mysql_num_rows($query2);
for($i = 0; $row = mysql_fetch_assoc($query2); $i++) {

	$id = $row[id];

	if (!is_numeric($id)) {
	echo "ID not a number";
	die;
	} else {
	$val[0] = "a_images/nopreview.jpg";
	$val[1] = "";
	$val[2] = "ss2";

	$fetchn = mysql_num_rows(mysql_query("SELECT * FROM onecms_images WHERE album = '".$id."'"));

	if ($fetchn == "0") {
    $fetch7 = implode(",", $val);
	$fetch = explode(",", $fetch7);
	}
    
	if ($fetchn > "0") {
	$fetchy = mysql_fetch_row(mysql_query("SELECT name,caption,type2 FROM onecms_images WHERE album = '".$id."'"));

	if ($fetchy[2] == "ss2") {
	$ya = "".$fetchy[0]."";
	} else {
	$ya = "".$images."/".$fetchy[0]."";
	}

	@list($widtha, $heighta) = @getimagesize($ya);
	if ($widtha == "") {
    $fetch7 = implode(",", $val);
	$fetch = explode(",", $fetch7);
	} else {
	$fetch = mysql_fetch_row(mysql_query("SELECT name,caption,type2 FROM onecms_images WHERE album = '".$id."'"));
	}
	}
	

    $system13 = mysql_fetch_row(mysql_query("SELECT data FROM onecms_fielddata WHERE id2 = '".$row[id]."' AND name = 'systems' AND cat = 'content'"));
	$systems = $system13[0];

	$squery = mysql_query("SELECT name,icon FROM onecms_systems WHERE id = '".$systems."'");
	$icon = mysql_fetch_row($squery);

	$imagenum = mysql_num_rows(mysql_query("SELECT * FROM onecms_images WHERE album = '".$id."'"));

	$f1[0] = "/{image}/";
	$f1[1] = "/{thumb}/";
	$f1[2] = "/{caption}/";
	$f1[3] = "/{name}/";
	$f1[4] = "/{url}/";
	$f1[5] = "/{system}/";
	$f1[6] = "/{icon}/";
	$f1[7] = "/{imagenum}/";

	if ($fetch[2] == "ss2") {
	$r1[0] = "".$fetch[0]."";
	} else {
	$r1[0] = "".$images."/".$fetch[0]."";
	}

	if ($fetch[2] == "ss2") {
	$r1[1] = "thumb.php?p=".$fetch[0]."";
	} else {
	$r1[1] = "thumb.php?p=".$images."/".$fetch[0]."";
	}

	$r1[2] = "".stripslashes($fetch[1])."";
	$r1[3] = stripslashes($row[name]);
	$r1[4] = "".$gpart1."".$id."".$gpart2."";
	if ($row[systems]) {
	$r1[5] = stripslashes($icon[0]);
	$r1[6] = "<img src='".stripslashes($icon[1])."'>";
	} else {
	$r1[5] = "";
	$r1[6] = "";
	}
	$r1[7] = $imagenum;
    
	if (!$i == "0") {
	if (($i % $albrow) === 0) {
		print $albsep;
	}
	}

	echo preg_replace($f1, $r1, $galtemplate);
}
}

echo "</tr></table>";

if ($_GET['s'] == "") {
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_albums"),0);
} else {
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_albums WHERE systems = '".$_GET['s']."'"),0);
}

$total_pages = ceil($total_results / $albpage);

if ($total_pages > "1") {

echo "<br><br><center>Page:<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
	if ($_GET['s'] == "") {
    echo "<a href=\"gallery.php?p=$prev\"><<Previous</a>&nbsp;";
	} else {
	echo "<a href=\"gallery.php?s=".$_GET['s']."&p=$i\">$i</a>&nbsp;";
	}
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
			if ($_GET['s'] == "") {
            echo "<a href=\"gallery.php?p=$i\">$i</a>&nbsp;";
			} else {
			echo "<a href=\"gallery.php?s=".$_GET['s']."&p=$i\">$i</a>&nbsp;";
			}if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
	if ($_GET['s'] == "") {
    echo "<a href=\"gallery.php?p=$next\">Next>></a>";
	} else {
	echo "<a href=\"gallery.php?s=".$_GET['s']."&p=$next\">Next>></a>";
	}
}
echo "</center>";
}
}

if ($_GET['id']) {
if (is_numeric($_GET['id'])) {
$id = $_GET['id'];
$galleryid = $_GET['id'];
} else {
echo "ID number invalid";
die;
}

$imagenum = mysql_num_rows(mysql_query("SELECT * FROM onecms_images WHERE album = '".$id."'"));
$title = mysql_fetch_row(mysql_query("SELECT name,views,systems FROM onecms_albums WHERE id = '".$id."'"));

$query = mysql_query("SELECT name,icon FROM onecms_systems WHERE id = '".$title[2]."'");
$systems = mysql_fetch_row($query);

if ($title[1] == "") {
$views = "1";
} else {
$views = $title[1] + 1;
}

mysql_query("UPDATE onecms_albums SET views = ".$views." WHERE id = '".$id."'");

$newviews = $title[1] + 1;

$alb21[0] = "/{title}/";
$alb21[1] = "/{imagenum}/";
$alb21[2] = "/{views}/";
$alb21[3] = "/{system}/";
$alb21[4] = "/{icon}/";
$alb21[5] = "/{slide}/";
$alb22[0] = $title[0];
$alb22[1] = $imagenum;
$alb22[2] = $newviews;
if ($title[2]) {
$alb22[3] = stripslashes($systems[0]);
$alb22[4] = "<img src='".stripslashes($systems[1])."'>";
} else {
$alb22[3] = "";
$alb22[4] = "";
}
$alb22[5] = "javascript:awindow(\"slideshow.php?id=$id\", \"\", \"width=".$swidth.",scroll=yes\")";

echo preg_replace($alb21, $alb22, $albtemplate2);

$query = "SELECT * FROM onecms_images WHERE album = '".$id."' ORDER BY `id`";

if (($albpage) && ($albpages == "No")) {
	$query .= " LIMIT ".$albpage."";
}
if ($albpages == "Yes") {
    $query .= " LIMIT $from, $albpage";
}

$query2 = mysql_query($query);
$iy = mysql_num_rows($query2);
for($i = 0; $row = mysql_fetch_assoc($query2); $i++) {

	$f1[0] = "/{image}/";
	$f1[1] = "/{thumb}/";
	$f1[2] = "/{caption}/";
	$f1[3] = "/{slide}/";
	$f1[4] = "/{date}/";

	if ($row[type2] == "ss2") {
	$r1[0] = "".$row[name]."";
	} else {
	$r1[0] = "".$images."/".$row[name]."";
	}

	if ($row[type2] == "ss2") {
	$r1[1] = "thumb.php?p=".$row[name]."";
	} else {
	$r1[1] = "thumb.php?p=".$images."/".$row[name]."";
	}

	$r1[2] = "".stripslashes($row[caption])."";
	$r1[3] = "javascript:awindow(\"slideshow.php?id=$row[album]\", \"\", \"width=".$swidth.",height=".$sheight.",scroll=yes\")";
	$r1[4] = date($dformat, $row[date]);
    
	if (!$i == "0") {
	if (($i % $albrow) === 0) {
		print $albsep;
	}
	}

	echo preg_replace($f1, $r1, $albtemplate);
}
echo preg_replace($alb21, $alb22, $albtemplate3);

$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_images WHERE album = '".$id."'"),0);

$total_pages = ceil($total_results / $max_results);

if ($total_pages > "1") {

echo "<br><br><center>Page:<br>";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"gallery.php?id=".$galleryid."&p=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"gallery.php?id=".$galleryid."&p=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"gallery.php?id=".$galleryid."&p=$next\">Next>></a>";
}
echo "</center>";
}
}
footera();
?>