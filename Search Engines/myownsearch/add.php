<?
include "./config.php";
If ($action=="add"){
	include "./mysql.php";
	$sql = "select url from $table where url='$url'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$numrows = mysql_num_rows($result);
  	$result_row = mysql_fetch_row($result);
	If ($numrows==0){
		If (!$description) $status="Error: No Website Description Entered<br>";
		If (!$url) $status="Error: No Website URL Entered<br>";
		If (!$title) $status="Error: No Website Title Entered<br>";
		$insert_sql = "insert into $table values ('', '$url', '$title', '$description', '0', '0000-00-00 00:00:00')";
		If (!$status) $result2 = mysql_query($insert_sql) or die("Query failed");
		If (!$status) $status = "Your website has been added to the database.<br>";
	}
	else
	{
	$status = "URL is already in the database.\n";
	}
}
if ($header) include $header;
echo "$status";
?>
Insert text or HTML for the Add Your Site page here. Inserting here will put theAdd form at the bottom. To keep the form at the top, add the text or HTML underthe following line instead, and delete these lines. If you're viewing this pagefrom your browser, view the bottom of ADD.PHP to edit this text.<form name="form1" method="get">Title:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="title"><br>URL:&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;<input type="text" name="url"><br>Description: <input type="text" name="description"><br><input type="hidden" name="action" value="add"><input type="submit" value="Submit"><br></form>
<?
if ($footer) include $footer;
?>