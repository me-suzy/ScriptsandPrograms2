<?php
include ("config.php");
if ($ipbancheck3 == "0") {if ($numv == "0"){
	if ($warn == $naum) {
	echo "You are banned from the Admin CP...now go away!";
} else {

if(!isset($_GET['page'])){
    $page = 1;
} else {
    $page = $_GET['page'];
}
$from = (($page * $max_results) - $max_results);echo '<SCRIPT LANGUAGE="JavaScript">var checkflag = "false";function check(field) {if (checkflag == "false") {for (i = 0; i < field.length; i++) {field[i].checked = true;}checkflag = "true";return "Uncheck All"; }else {for (i = 0; i < field.length; i++) {field[i].checked = false; }checkflag = "false";return "Check All"; }}</script>';

if(!isset($_GET['page2'])){
    $page2 = 1;
} else {
    $page2 = $_GET['page2'];
}
$from2 = (($page2 * $max_results) - $max_results);

if(!isset($_GET['page3'])){
    $page3 = 1;
} else {
    $page3 = $_GET['page3'];
}
$from3 = (($page3 * $max_results) - $max_results);

if(!isset($_GET['page4'])){
    $page4 = 1;
} else {
    $page4 = $_GET['page4'];
}
$from4 = (($page4 * $max_results) - $max_results);

if(!isset($_GET['page5'])){
    $page5 = 1;
} else {
    $page5 = $_GET['page5'];
}
$from5 = (($page5 * $max_results) - $max_results);

if(!isset($_GET['page6'])){
    $page6 = 1;
} else {
    $page6 = $_GET['page6'];
}
$from6 = (($page6 * $max_results) - $max_results);

if (($userlevel == "4") or ($userlevel == "5")) {
	echo "Sorry $username, but you do not have permission to view this page. You are only a $level.";
} else {

	if ($_GET['view'] == "") {

        echo "<title>OneCMS - www.insanevisions.com/onecms > Counter 2.0</title>";
echo "<center><a href='a_stats.php#content'>Content Stats</a> | <a href='a_stats.php?#games'>Game Stats</a> | <a href='a_stats.php#cat'>Category Stats</a> | <a href='a_stats.php#systems'>System Stats</a> | <a href='a_stats.php#af'>Affiliate Stats</a> | <a href='a_stats.php#forums'>Forum Stats</a></center><br><br>";

	echo "<a name='content'><center><u>Content Stats</u></center><br><br><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name of Article</b></td><td><b>Views</b></td><td><b>Writer</b></td></tr>";

	$query="SELECT * FROM onecms_content ORDER BY `id` DESC LIMIT $from, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
	$name = stripslashes($row[name]);
    $stats = "$row[stats]";

	echo "<tr><td><a href='".$part1."".$row[id]."".$part2."'>$name</a></td><td>$stats</td><td>$row[username]</td></tr>";

	}
	
	echo "</table><br>";

	$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_content"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br />";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"".$_SERVER['PHP_SELF']."?page=$next\">Next>></a>";
}
echo "</center>
    </span>
  </div></div></center><br>";

	echo "<a name='forums'><center><u>Forum Stats</u></center><br><br><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Subject</b></td><td><b>Views</b></td></tr>";

	$query="SELECT * FROM onecms_posts ORDER BY `id` DESC LIMIT $from2, $max_results";
	$result=mysql_query($query);
	while($r = mysql_fetch_array($result)) {

    echo "<tr><td><a href='".$f2part1."".$r[tid]."".$f2part2."";
    
    if ($r[type] == "post") {
    echo "#".$r[id]."";
    }
    echo "'>".stripslashes($r[subject])."</a></td><td>".$r[stats]."</td></tr>";

	}
	
	echo "</table><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_posts"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a page<br />";

// Build Previous Link
if($page5 > 1){
    $prev = ($page5 - 1);
    echo "<a href=\"".$_SERVER['PHP_SELF']."?page5=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page5) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"".$_SERVER['PHP_SELF']."?page5=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page5 < $total_pages){
    $next = ($page5 + 1);
    echo "<a href=\"".$_SERVER['PHP_SELF']."?page5=$next\">Next>></a>";
}
echo "</center>
    </span>
  </div></div></center><br>";

	echo "<a name='games'><center><u>Game Stats</u></center><br><br><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name of Game</b></td><td><b>Views</b></td></tr>";

	$query="SELECT * FROM onecms_games ORDER BY `id` DESC LIMIT $from2, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
	$name = stripslashes($row[name]);

    echo "<tr><td><a href='".$gamepart1."".$row[id]."".$gamepart2."'>$name</a></td><td>".$row[stats]."</td></tr>";

	}
	
	echo "</table><br>";
	$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_games"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br />";

// Build Previous Link
if($page2 > 1){
    $prev = ($page2 - 1);
    echo "<a href=\"".$_SERVER['PHP_SELF']."?page2=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page2) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"".$_SERVER['PHP_SELF']."?page2=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page2 < $total_pages){
    $next = ($page2 + 1);
    echo "<a href=\"".$_SERVER['PHP_SELF']."?page2=$next\">Next>></a>";
}
echo "</center>
    </span>
  </div></div></center><br>";

	echo "<a name='af'><center><u>Affiliate Stats</u></center><br><br><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Site Name</b></td><td><b>Clicks</b></td></tr>";

	$query="SELECT * FROM af_manager WHERE verified = 'yes' ORDER BY `id` DESC LIMIT $from2, $max_results";
	$result=mysql_query($query);
	while($r = mysql_fetch_array($result)) {

    echo "<tr><td><a href='af.php?view=click&id=".$r[id]."'>".$r[sitename]."</a></td><td>".$r[clicks]."</td></tr>";

	}
	
	echo "</table><br>";
	$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM af_manager"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a page<br />";

// Build Previous Link
if($page6 > 1){
    $prev = ($page6 - 1);
    echo "<a href=\"".$_SERVER['PHP_SELF']."?page6=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page6) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"".$_SERVER['PHP_SELF']."?page6=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page6 < $total_pages){
    $next = ($page6 + 1);
    echo "<a href=\"".$_SERVER['PHP_SELF']."?page6=$next\">Next>></a>";
}
echo "</center>
    </span>
  </div></div></center><br>";

		echo "<a name='cat'><center><u>Category Stats</u></center><br><br><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name of Category</b></td><td><b>Views</b></td></tr>";

	$query="SELECT * FROM onecms_cat ORDER BY `id` DESC LIMIT $from3, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
	$name = stripslashes($row[name]);

    $viewsb = mysql_fetch_array(mysql_query("SELECT SUM(stats) AS stats FROM onecms_content WHERE cat = '".$name."'"));
	if ($viewsb[0]) {
	$views = $viewsb[0];
	} else {
	$views = "0";
	}

	echo "<tr><td><a href='index.php?list=".$name."'>".$name."</a></td><td>".$views."</td></tr>";

	}
	
	echo "</table><br>";
	$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_cat"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br />";

// Build Previous Link
if($page3 > 1){
    $prev = ($page3 - 1);
    echo "<a href=\"".$_SERVER['PHP_SELF']."?page3=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page3) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"".$_SERVER['PHP_SELF']."?page3=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page3 < $total_pages){
    $next = ($page3 + 1);
    echo "<a href=\"".$_SERVER['PHP_SELF']."?page3=$next\">Next>></a>";
}
echo "</center>
    </span>
  </div></div></center><br>";

		echo "<a name='systems'><center><u>System Stats</u></center><br><br><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Name of Systems</b></td><td><b>Views</b></td></tr>";

	$query="SELECT * FROM onecms_systems ORDER BY `id` DESC LIMIT $from4, $max_results";
	$result=mysql_query($query);
	while($row = mysql_fetch_array($result)) {
	$id = "$row[id]";
    $name = stripslashes($row[name]);

    $viewsb = mysql_fetch_array(mysql_query("SELECT SUM(stats) AS stats FROM onecms_content WHERE systems = '".$id."'"));
	if ($viewsb[0]) {
	$views = $viewsb[0];
	} else {
	$views = "0";
	}

	echo "<tr><td><a href='index.php?id=systems&sid=".$row[abr]."'>".$name."</a></td><td>".$views."</td></tr>";

	}
	
	echo "</table><br>";
	$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_systems"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br />";

// Build Previous Link
if($page4 > 1){
    $prev = ($page4 - 1);
    echo "<a href=\"".$_SERVER['PHP_SELF']."?page4=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page4) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"".$_SERVER['PHP_SELF']."?page4=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page4 < $total_pages){
    $next = ($page4 + 1);
    echo "<a href=\"".$_SERVER['PHP_SELF']."?page4=$next\">Next>></a>";
}
echo "</center>
    </span>
  </div></div></center><br>";

	}

	}
	}
	}
	}
	include ("a_footer.inc");
	?>