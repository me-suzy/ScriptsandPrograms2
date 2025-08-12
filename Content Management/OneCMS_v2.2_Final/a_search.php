<?php 
$la = "a";
$z = "b";
include ("config.php");
if ($_GET['view'] == "types") {

		echo "<SCRIPT LANGUAGE='JavaScript'>
function file(text) {
	text = '' + text + '';
	opener.document.form1.".intval($_GET['id']).".value  += text;
	opener.document.form1.".intval($_GET['id']).".focus();
  }
function file2(text) {
	text = '' + text + '';
	opener.document.form1.".$_GET['id2'].".value  += text;
	opener.document.form1.".$_GET['id2'].".focus();
  }
</SCRIPT>";
		echo "<LINK REL='stylesheet' HREF='ta3.css' TYPE='text/css'><form action='".$REQUEST_URI."' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for ".$_GET['type']."</td><td><input type='text' name='keyword'></td><td><input type='submit' name='submit' value='Search'></td></tr></table></form>";

		echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>".$_GET['type']." Name</b></td><td>ID</td></tr>";

	$queryed="SELECT * FROM onecms_".$_GET['type']."";
	if ($_GET['type'] == "albums") {
		$queryed .= " WHERE type = 'album'";
	}
	$resulted=mysql_query($queryed);
	while($row2 = mysql_fetch_array($resulted)) {
		echo "<tr><td><a href=\"javascript:file2('".$row2[id]."')\" onClick=\"javascript:file('".$row2[name]."')\">".$row2[name]."</a></td><td>".$row2[id]."</td></tr>";
	}

	echo "</table><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><br><br><center><a href='javascript:window.close();'><b>Close Window</b></a></center></td></tr></table>";

if ($_POST['submit']) {

	echo "<SCRIPT LANGUAGE='JavaScript'>
function file(text) {
	text = '' + text + '';
	opener.document.form1.".intval($_GET['id']).".value  += text;
	opener.document.form1.".intval($_GET['id']).".focus();
  }
function file2(text) {
	text = '' + text + '';
	opener.document.form1.".$_GET['id2'].".value  += text;
	opener.document.form1.".$_GET['id2'].".focus();
  }
</SCRIPT>";

echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>".$_GET['type']." Name</b></td><td>ID</td></tr>";

	$queryed="SELECT * FROM onecms_".$_GET['type']." WHERE name LIKE '%".$_POST["keyword"]."%'";
	if ($_GET['type'] == "albums") {
		$queryed .= " AND type = 'album'";
	}
	$resulted=mysql_query($queryed);
	while($row2 = mysql_fetch_array($resulted)) {
		$a = "/".$_POST['keyword']."/";
		$b = "<b>".$_POST['keyword']."</b>";
		$key = preg_replace($a, $b, $row2[name]);
		echo "<tr><td><a href=\"javascript:file2('".$row2[id]."')\" onClick=\"javascript:file('".$row2[name]."')\">".$key."</a></td><td>".$row2[id]."</td></tr>";
	}

	echo "</table><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><br><br><center><a href='javascript:window.close();'><b>Close Window</b></a></center></td></tr></table>";
}
}
/////////////////////////////////////////////////////
if ($_GET['view'] == "files") {

	echo "<LINK REL='stylesheet' HREF='ta3.css' TYPE='text/css'><form action='".$REQUEST_URI."' method='post'><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td>Search for file</td><td><input type='text' name='keyword'></td><td><input type='submit' name='submit' value='Search'></td></tr></table></form>";

if ($_POST['submit']) {

	echo "<SCRIPT LANGUAGE='JavaScript'>
function file(text) {
	text = '' + text + '';
	opener.document.form1.file_".intval($_GET['id']).".value  += text;
	opener.document.form1.file_".intval($_GET['id']).".focus();
  }
function file2(text) {
	text = '' + text + '';
	opener.document.form1.file2_".intval($_GET['id']).".value  += text;
	opener.document.form1.file2_".intval($_GET['id']).".focus();
  }
</SCRIPT>";

echo "<table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><b>File Name</b></td><td><b>Date</b></td></tr>";

	$queryed="SELECT * FROM onecms_images WHERE name LIKE '%".$_POST["keyword"]."%'";
	$resulted=mysql_query($queryed);
	while($row2 = mysql_fetch_array($resulted)) {
		$a = "/".$_POST['keyword']."/";
		$b = "<b>".$_POST['keyword']."</b>";
		$key = preg_replace($a, $b, $row2[name]);
		echo "<tr><td><a href=\"javascript:file('".$row2[name]."')\" onClick=\"javascript:file2('".$row2[id]."')\">".$key."</a></td><td>".date($dformat, $row2[date])."</td></tr>";
	}

	echo "</table><table cellspacing=\"0\" cellpadding=\"3\" border=\"0\" align=\"center\"><tr><td><br><br><center><a href='javascript:window.close();'><b>Close Window</b></a></center></td></tr></table>";
}
}
?>