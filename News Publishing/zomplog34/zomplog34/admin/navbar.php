<?

$paging = ceil ($numrows / $limit);
?>
<table width="100%"  border="0" cellspacing="0" cellpadding="0" class="text">
  <tr>
    <td width="28%"><?
// first, previous
if ($display > 1) {
	$previous = $display - 1;
	echo "<a href=\"$_SERVER[PHP_SELF]?show=1&catid=$_GET[catid]&username=$_GET[username]\">First</a> |";
	echo " <a href=\"$_SERVER[PHP_SELF]?show=$previous&catid=$_GET[catid]&username=$_GET[username]\">Previous</a> ";

}

?></td>
    <td width="43%" align="center"><?
	// page numbers

if ($numrows != $limit) {
	if ($paging > $scroll) {
		$first = $_GET[show];
		$last = ($scroll - 1) + $_GET[show];
	} else {
		$first = 1;
		$last = $paging;
	}
		if ($last > $paging ) {
			$first = $paging - ($scroll - 1);
			$last = $paging;
	}
	for ($i = $first;$i <= $last;$i++){
		if ($display == $i) {
			echo "<b>$i</b>";
		} else {
			echo " <a href=\"$_SERVER[PHP_SELF]?show=$i&catid=$_GET[catid]&username=$_GET[username]\">$i</a> ";
		}
	}
}
?>
	</td>
    <td width="29%" align="right">      <?




// next, last
if ($display < $paging) {
	$next = $display + 1;
	echo " <a href=\"$_SERVER[PHP_SELF]?show=$next&catid=$_GET[catid]&username=$_GET[username]\">Next</a> ";
	echo "| <a href=\"$_SERVER[PHP_SELF]?show=$last&catid=$_GET[catid]&username=$_GET[username]\">Last</a> ";
}

?></td>
  </tr>
</table>
