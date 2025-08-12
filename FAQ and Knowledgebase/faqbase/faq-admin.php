<?
include "./faq-config.php";

if ($ckAdminPass!=$adminpass) exit;

if ($editcats){
	$sql = "select * from $faqcats order by id";
	$result = mysql_query($sql) or die("Failed: $sql");
	$numrows = mysql_num_rows($result);
	print "<title>Add/Edit Categories</title>";
	for($x=0;$x<$numrows;$x++){
		$resrow = mysql_fetch_row($result);
		$catid = $resrow[0];
		$cattext = $resrow[1];
		$cattext = stripslashes($cattext);
		$cattext = htmlspecialchars($cattext);
		print "<form name='form1' method='post' action='$adminfile'>$catid: <input type='text' name='cattext' value=\"$cattext\" maxlength='255' size='40'><input type='hidden' name='catid' value='$catid'><input type='submit' value='Save'> [<a href='".$adminfile."?deletecat=$catid'>Delete</a>]</form>";
	}
	print "<form name='form1' method='post' action='$adminfile'>New Category: <input type='text' name='newcat' maxlength='255' size='40'><input type='submit' value='Add'></form>";
	exit;
}

if ($catid && $cattext){
	$cattext = addslashes($cattext);
	$sql = "update $faqcats set cat='$cattext' where id='$catid'";
	$result = mysql_query($sql) or die("Failed: $sql");
	Header("Location: ".$adminfile."?editcats=1");
	exit;
}

if ($newcat){
	$newcat = addslashes($newcat);
	$sql = "insert into $faqcats values('', '$newcat')";
	$result = mysql_query($sql) or die("Failed: $sql");
	Header("Location: ".$adminfile."?editcats=1");
	exit;
}

if ($deletecat){
	$sql = "delete from $faqcats where id='$deletecat'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$sql = "delete from $table where catid='$deletecat'";
	$result = mysql_query($sql) or die("Failed: $sql");
	Header("Location: ".$adminfile."?editcats=1");
	exit;
}

if ($newentry){
	print "<title>Add New FAQ Entry</title>";
	print "<form name='form1' method='post' action='$adminfile'>
	  Question:<br>
	  <input type='text' name='question' maxlength='255' size='40'>
	  <br>
	  Answer:<br>
	  <textarea name='answer' cols='40' rows='5' wrap='VIRTUAL'></textarea>
	  <br>
	  <input type='hidden' name='catid' value='$newentry'>
	  <input type='hidden' name='addentry' value='1'>
	  <input type='submit' value='Add Entry'>
	</form>";
	exit;
}

if ($addentry && $question && $answer && $catid){
	$question = addslashes($question);
	$answer = addslashes($answer);
	$sql = "insert into $table values('', '$catid', '$question', '$answer', now())";
	$result = mysql_query($sql) or die("Failed: $sql");
	print "<b>Entry added!</b> <a href='javascript:window.close();'>Close</a>";
	exit;
}

if ($deleteentry){
	$sql = "delete from $table where id='$deleteentry'";
	$result = mysql_query($sql) or die("Failed: $sql");
	print "<b>Entry deleted!</b> <a href='javascript:window.close();'>Close</a>";
	exit;
}

if ($editentry){
	$sql = "select question,answer from $table where id='$editentry'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$resrow = mysql_fetch_row($result);
	$question = htmlspecialchars($resrow[0]);
	$answer = htmlspecialchars($resrow[1]);
	print "<title>Edit FAQ Entry</title>";
	print "<form name='form1' method='post' action='$adminfile'>
	  Question:<br>
	  <input type='text' name='question' maxlength='255' size='40' value=\"$question\">
	  <br>
	  Answer:<br>
	  <textarea name='answer' cols='40' rows='5' wrap='VIRTUAL'>".stripslashes($answer)."</textarea>
	  <br>
	  <input type='hidden' name='faqid' value='$editentry'>
	  <input type='hidden' name='saveentry' value='1'>
	  <input type='submit' value='Save Changes'>
	</form>";
	exit;
}

if ($saveentry && $question && $answer && $faqid){
	$question = addslashes($question);
	$answer = addslashes($answer);
	$sql = "update $table set question='$question', answer='$answer' where id='$faqid'";
	$result = mysql_query($sql) or die("Failed: $sql");
	print "<b>Changes saved!</b> <a href='javascript:window.close();'>Close</a>";
}

?>