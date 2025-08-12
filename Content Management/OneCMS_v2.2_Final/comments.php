<?php
$la = "a";
$z = "b";
include ("config.php");

if ($_GET['j']) {
echo "<link rel='stylesheet' type='text/css' href='ta3.css'><table cellspacing=\"0\" cellpadding=\"1\" border=\"1\">";

$sql = mysql_query("SELECT * FROM onecms_comments2 WHERE aid = '".$_GET['j']."' ORDER BY `id` DESC");
while($r = mysql_fetch_array($sql)) {

$sql2 = mysql_query("SELECT id FROM onecms_profile WHERE username = '".$r[name]."'");
$mr = mysql_num_rows($sql2);
$ma = mysql_fetch_row($sql2);

	if ($mr > "0") {
		$user = "<a href='members.php?action=profile&id=".$ma[0]."'>".$r[name]."</a>";
	} else {
		$user = "Visitor";
	}

	echo "<tr><td><center><b>Profile</b></center></td><td><b>".$r[subject]."</b> posted by ".$user." at <i>".date($dformat, $r['date'])."</i></td></tr><tr><td>".comments2("".$r[name]."")."</td><td>".comments4("<p>".stripslashes($r[comment])."</p>")."".comments3("".$r[name]."")."</td></tr>";
}
echo "</table>";
}

if ($_GET['aid']) {
echo "<SCRIPT LANGUAGE='JavaScript'>
  function smiles(which) {
  document.form1.text1.value = document.form1.text1.value + which;
  }
</SCRIPT>
	<script language='javascript'>
function awindow(towhere, newwinname, properties) {
window.open(towhere,newwinname,properties);
}
</script><link rel='stylesheet' type='text/css' href='ta3.css'><form name='form1' method='post' action='comments.php?view=add'>
<input type='hidden' name='aid' value='".$_GET['aid']."'>
<input type='hidden' name='name' value='".$_COOKIE[username]."'>
<table cellspacing=\"0\" cellpadding=\"1\" border=\"0\" align=\"center\">
<tr><td><b>Name</b></td><td>".$_COOKIE[username]."</td></tr>
<tr><td><b>E-mail</b></td><td>".$email."</td></tr>
<tr><td><b>Subject of Comment</b></td><td><input type='text' name='title' width='18'></td></tr>
<tr><td><b>Comments</b></td><td><textarea name=\"text1\" cols='18' rows='7'></textarea></td><td width='75'><b><center>Smilies</center></b><center>";

$query2 = mysql_query("SELECT * FROM onecms_comments1 WHERE type = 'smiley'");
$limit = mysql_num_rows($query2);
$query="SELECT * FROM onecms_comments1 WHERE type = 'smiley' LIMIT 9";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {

$tag = "$row[field]";
$name = "$row[name]";
list($width, $height, $type, $attr) = getimagesize("".$images."/".$tag."");
echo "<a href=\"javascript:smiles('".$name." ')\"><img src='".$images."/".$tag."' border='0' width='";
if ($width > "20") {
echo "20";
} else {
echo "$width";
}
echo "'></a>";
if (($limit/3) == (int)($limit/3)) {
	echo "<br>";
}
}
echo "<br><br><a href='javascript:awindow(\"comments.php?view=smilies\", \"\", \"width=200,height=200,scroll=yes\")'>View All</a>";
echo "</center></td></tr><tr><td><input type='submit' name='submit' value='Submit Comment'></td><td><input type='reset' name='reset' value='Reset'></td></tr></table></form>";
}

if ($_GET['view'] == "add") {
if ($_POST["name"] == "") {
$name = "Visitor";
} else {
$name = "".$_COOKIE[username]."";
}

$comment = mysql_query("INSERT INTO onecms_comments2 VALUES ('null', '".$name."', '".$_POST["title"]."', '".$_POST["text1"]."', '".$_POST["aid"]."', '".$email."', '".time()."')") or die(mysql_error());

if ($comment == TRUE) {
	echo "Comment has been posted. <a href='javascript:window.close();'><b>Close this Window</b></a>";
}
}

if ($_GET['view'] == "smilies") {
echo "<SCRIPT LANGUAGE='JavaScript'>
function smiles2(text) {
	text = '' + text + '';
	opener.document.form1.text1.value  += text;
	opener.document.form1.text1.focus();
  }
</SCRIPT><title>Smilies > View All</title>";

$query2 = mysql_query("SELECT * FROM onecms_comments1 WHERE type = 'smiley'");
$limit = mysql_num_rows($query2);
$query="SELECT * FROM onecms_comments1 WHERE type = 'smiley'";
$result=mysql_query($query);
while($row = mysql_fetch_array($result)) {
$name = "$row[name]";
$tag = "$row[field]";
echo "<a href=\"javascript:smiles2('".$name." ')\"><img src='".$images."/".$tag."' border='0'></a>";
if (($limit/3) == (int)($limit/3)) {
	echo "<br>";
}
}
}
?>