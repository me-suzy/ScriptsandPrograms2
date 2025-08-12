<?
include "./config.php";
if ($pass!=$adminpass){
	echo "<center><form name='form1' method='get'>Enter Administrator's Password: <input type='text' name='pass'><input type=submit value='Submit'><br></form></center>";
	exit;
}
If ($deletefrom && $search){
	include "./mysql.php";
	$sql="DELETE FROM $table WHERE $deletefrom RLIKE '$search'";
	$result = mysql_query($sql) or die("Failed: $sql");
	print "Records matching <b>$search</b> in the <b>$deletefrom</b> have been removed.";
}
If ($searchby && $search){
	include "./mysql.php";
	$sql = "SELECT * from $table where $searchby rlike '$search'";
	$result = mysql_query($sql) or die("Failed: $sql");
	$numrows = mysql_num_rows($result);
	print "Found $numrows results matching <b>$search</b><br><br>";
	print "<center><table width='$tablewidth' border='0' cellspacing='0' cellpadding='0'>";
	for($i = 0; $i < $numrows; $i++) {
       		$result_row = mysql_fetch_row($result);
		$id = $result_row[0];
		$url = $result_row[1];
		$title = $result_row[2];
		$descr = $result_row[3];
		$clicks = $result_row[4];
		$lastaccess = $result_row[5];
		$extrastuff = "<i>Clicks: $clicks - Last Accessed: $lastaccess</i> [<a href='#' onClick=\"window.open('admin.php?pass=$pass&deleteid=$id', '_delete_entry', 'width=175,height=120'); return true\">Delete This Entry</a>]";
		print "<tr>
		<td bgcolor='$color1'>&nbsp;&nbsp;<a href='$url'$targetwin><b><font face='$fontface' size='$fontsize'>$title</font></b></a><font face='$fontface' size='$fontsize'> 
      		<font color='#000000'>$extrastuff</font></font></td>
		</tr>
		<tr> 
		<td bgcolor='$color2'>
		<blockquote>
       		<p><font face='$fontface' size='$fontsize' color='$textcolor'>$descr</font></p>
      		</blockquote>
		</td>
		</tr>";	}
	print "</table></center>";
}
If ($deleteid){
	include "./mysql.php";
	$sql = "DELETE from $table where id=$deleteid";
	$result = mysql_query($sql) or die("Failed: $sql");
	echo "Record $id deleted.";
	exit;
}
print "<html><head><title>$engtitle Administration</title></head><body><form name='form1' method='get'><b>Search Database:</b><br>Show entries from <SELECT NAME='searchby'><OPTION VALUE='title' SELECTED>Title<OPTION VALUE='url'>URL<OPTION VALUE='descr'>Description</SELECT> Matching Keyword <input type='text' name='search'><input type='hidden' name='pass' value='$pass'><input type=submit value='Submit'><br></form><form name='form1' method='get'><b>Delete Entries by Keyword:</b><br>Delete from <SELECT NAME='deletefrom'><OPTION VALUE='title' SELECTED>Title<OPTION VALUE='url'>URL<OPTION VALUE='descr'>Description</SELECT> Matching Keyword <input type='text' name='search'><input type='hidden' name='pass' value='$pass'><input type=submit value='Submit'><br></form><a href='editads.php?pass=$pass'>Add/Edit Advertisements</a><br><a href='engconfig.php?pass=$pass'>Configure Meta Engines</a>
</body></html>";?>