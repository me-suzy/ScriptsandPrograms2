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

$from = (($page * $max_results) - $max_results);


if ($_GET['view'] == "") {

	echo "<form action='a_elite.php?view=delete' name='form1' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>Username</b></td><td><b>Game/System</b></td><td><b>Type</b><?td><td><b>Delete</b></td></tr>";

$query="SELECT * FROM onecms_elite ORDER BY `id` DESC LIMIT $from, $max_results";
$result=mysql_query($query);
while($r = mysql_fetch_array($result)) {

		$fetch = mysql_fetch_row(mysql_query("SELECT username FROM onecms_profile WHERE id = '".$r[pid]."'"));

		$fetc2 = mysql_fetch_row(mysql_query("SELECT name FROM onecms_games WHERE id = '".$r[game]."'"));
		$fetc1 = mysql_fetch_row(mysql_query("SELECT name FROM onecms_systems WHERE id = '".$r[game]."'"));

		if ($r[type] == "systems") {
		$item = $fetc1[0];
		} else {
		$item = $fetc2[0];
		}

echo "<tr><td><a href='elite.php?user=".$r[pid]."'>".$fetch[0]."</a></td><td>";
if ($r[type] == "systems") {
echo $item;
} else {
echo "<a href='".$gamepart1."".intval($r[id])."".$gamepart2."'>".$item."</a>";
}
echo "</td><td>".$r[type]."</td><td><input type=\"checkbox\" name=\"delete[]\" value=\"$r[id]\"></td></tr>";
    }

echo "<tr><td><div align='right'><input type='submit' name='submit' value='Submit'></td><td><input type=button value='Check All' onClick='this.value=check(this.form)'></td></tr></form></table><br><br>";
$total_results = mysql_result(mysql_query("SELECT COUNT(*) as Num FROM onecms_elite"),0);

$total_pages = ceil($total_results / $max_results);

echo "<center>Select a Page<br />";

// Build Previous Link
if($page > 1){
    $prev = ($page - 1);
    echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."?page=$prev\"><<Previous</a>&nbsp;";
}

for($i = 1; $i <= $total_pages; $i++){
    if(($page) == $i){
        echo "$i&nbsp;";
        } else {
            echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."?page=$i\">$i</a>&nbsp;";if (($i/25) == (int)($i/25)) {echo "<br>";}
    }
}

// Build Next Link
if($page < $total_pages){
    $next = ($page + 1);
    echo "<a href=\"".$HTTP_SERVER_VARS['REQUEST_URI']."?page=$next\">Next>></a>";
}
echo "</center>
    </span>
  </div></div></center>";

}

if ($_GET['view'] == "delete") {

echo '<SCRIPT LANGUAGE="JavaScript">
var agree=confirm("Confirm Deletion?");
if (agree)
document.write("");
else
history.go(-1);
// End -->
</SCRIPT>';

while (list(, $val) = each ($_POST['delete'])) {
	$delete = mysql_query("DELETE FROM onecms_elite WHERE id = '$val'") or die(mysql_error());
}
if ($delete == TRUE) {
	echo "The elite data have been deleted. <a href='a_elite.php'>Manage Elite Data</a>";
}
}

}
}
}
?>