<?php
$z = "no";
include ("config.php");

$query = mysql_query("SELECT * FROM onecms_templates WHERE name = 'top10-1'");
while($row = mysql_fetch_array($query)) {
$tem1 = stripslashes($row[template]);
}
$query2 = mysql_query("SELECT * FROM onecms_templates WHERE name = 'top10-2'");
while($row2 = mysql_fetch_array($query2)) {
$tem2 = stripslashes($row2[template]);
}

$sql = mysql_query("SELECT * FROM onecms_games ORDER BY RAND() LIMIT ".$top10."");
for($i = 1; $r = mysql_fetch_assoc($sql); $i++) {
$r2 = explode("|", $r['release']);
$release = "".$r2[0]." ".$r2[1]." ".$r2[2]."";

$vate[0] = "{name}";
$vate[1] = "{stats}";
$vate[2] = "{username}";
$vate[3] = "{publisher}";
$vate[4] = "{developer}";
$vate[5] = "{genre}";
$vate[6] = "{release}";
$vate[7] = "{esrb}";
$vate[8] = "{boxart}";
$vate[9] = "{des}";
$vate[10] = "{id}";
if ($_COOKIE[username]) {
$vate[11] = "{favorites}";
$vate[12] = "{playing}";
$vate[13] = "{tracked}";
$vate[14] = "{collection}";
$vate[15] = "{wishlist}";
$tate[0] = "<a href='game".$r[id].".html'>".$r[name]."</a>";
$tate[1] = $r[stats];
$tate[2] = $r[username];
$tate[3] = $r[publisher];
$tate[4] = $r[developer];
$tate[5] = $r[genre];
$tate[6] = $release;
$tate[7] = $r[esrb];
if ($r['boxart']) {
$tate[8] = "<img src='".$images."/".$r['boxart']."' border='1'>";
} else {
$tate[8] = "<img src='".$siteurl."/a_images/noboxart.jpg'>";
}
$tate[9] = stripslashes($r[des]);
$tate[10] = $r[id];
$tate[11] = "<a href='javascript:awindow(\"elite.php?view=elitef&id=".$r[id]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_f.jpg' border='1'></a>";
$tate[12] = "<a href='javascript:awindow(\"elite.php?view=elitep&id=".$r[id]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_p.jpg' border='1'></a>";
$tate[13] = "<a href='javascript:awindow(\"elite.php?view=elitet&id=".$r[id]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_t.jpg' border='1'></a>";
$tate[14] = "<a href='javascript:awindow(\"elite.php?view=elitec&id=".$r[id]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_c.jpg' border='1'></a>";
$tate[15] = "<a href='javascript:awindow(\"elite.php?view=elitew&id=".$r[id]."\", \"\", \"width=20,height=10,scroll=yes\")'><img src='".$siteurl."/a_images/elite_w.jpg' border='1'></a>";
} else {
$vate[0] = "{name}";
$vate[1] = "{stats}";
$vate[2] = "{username}";
$vate[3] = "{publisher}";
$vate[4] = "{developer}";
$vate[5] = "{genre}";
$vate[6] = "{release}";
$vate[7] = "{esrb}";
$vate[8] = "{boxart}";
$vate[9] = "{des}";
$vate[10] = "{id}";
$vate[11] = "{favorites}";
$vate[12] = "{playing}";
$vate[13] = "{tracked}";
$vate[14] = "{collection}";
$vate[15] = "{wishlist}";
$tate[0] = $r[name];
$tate[1] = $r[stats];
$tate[2] = $r[username];
$tate[3] = $r[publisher];
$tate[4] = $r[developer];
$tate[5] = $r[genre];
$tate[6] = $release;
$tate[7] = $r[esrb];
if ($r['boxart']) {
$tate[8] = "<img src='".$images."/".$r['boxart']."' border='1'>";
} else {
$tate[8] = "<img src='".$siteurl."/a_images/noboxart.jpg'>";
}
$tate[9] = stripslashes($r[des]);
$tate[10] = $r[id];
$tate[11] = "";
$tate[12] = "";
$tate[13] = "";
$tate[14] = "";
$tate[15] = "";
}
if ($i == "1") {
echo str_replace($vate, $tate, $tem1);
} else {
echo str_replace($vate, $tate, $tem2);
}
}
?>