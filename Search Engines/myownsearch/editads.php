<?
include "./config.php";
if ($pass!=$adminpass){
	echo "<center><form name='form1' method='get'>Enter Administrator's Password: <input type='text' name='pass'><input type=submit value='Submit'><br></form></center>";
	exit;
}

include "./mysql.php";
if ($keyword && !$id){
	$sql = "insert into $adstable (id,keyword,ad,url,clicks,impressions) values ('', '$keyword', '$ad', '$url', '$clicks', '$impressions')";
	$result = mysql_query($sql) or die("Query failed");
}
If ($keyword && $id){
	$sql = "update $adstable set keyword='$keyword', ad='$ad', url='$url', impressions='$impressions', clicks='$clicks' where id='$id'";
	$result = mysql_query($sql) or die("Query failed");
}
If ($action=="delete"){
	$sql = "delete from $adstable where id='$id'";
	$result = mysql_query($sql) or die("Query failed deleting the advertisement.");
}
print "[<a href='admin.php?pass=$pass'>$engtitle Admin Home</a>]<br><br>";
$sql = "select * from $adstable";
$result = mysql_query($sql) or die("Failed: $sql");
$numrows = mysql_num_rows($result);
If ($numrows==0) print "The advertisements database is currently empty.";
for($i = 0; $i < $numrows; $i++) {
$result_row = mysql_fetch_row($result);
$id = $result_row[0];
$keyword = $result_row[1];
$ad = $result_row[2];
$url = $result_row[3];
$clicks = $result_row[4];
$impressions = $result_row[5];
print "<form name='form1' method='post' action='editads.php'>  <table width='100%' border='1' cellspacing='1'>    <tr>       <td width='11%' height='30'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>ID:         $id         <input type='hidden' name='id' value='$id'>        </font></td>      <td width='23%' height='30'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Keyword:         <input type='text' name='keyword' size='15' value='$keyword'>        </font></td>      <td width='26%' height='30'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>URL:         <input type='text' name='url' size='20' value='$url'>        </font></td>      <td width='24%' height='30'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Impressions:         <input type='text' name='impressions' size='10' value='$impressions'>        </font></td>      <td width='16%' height='30'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Clicks:         <input type='text' name='clicks' size='10' value='$clicks'>        </font></td>    </tr>    <tr>       <td colspan='5' height='27'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Text         Ad:         <input type='text' name='ad' size='60' value='$ad'> [<a href='editads.php?pass=$pass&action=delete&id=$id'>Delete This Ad</a>]        <input type='hidden' name='pass' value='$pass'>        <input type='submit' value='Save Changes'>        </font></td>    </tr>  </table></form><br>";}print "<br><br><form name='form1' method='post' action='editads.php'>  <table width='100%' border='1' cellspacing='1'>    <tr>      <td width='11%' height='30'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'><b>New Ad:</b>        </font></td>      <td width='23%' height='30'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Keyword:         <input type='text' name='keyword' size='15'>        </font></td>      <td width='26%' height='30'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>URL:         <input type='text' name='url' size='20'>        </font></td>      <td width='24%' height='30'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Impressions:         <input type='text' name='impressions' size='10'>        </font></td>      <td width='16%' height='30'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Clicks:         <input type='text' name='clicks' size='10'>        </font></td>    </tr>    <tr>       <td colspan='5' height='27'><font size='-1' face='Verdana, Arial, Helvetica, sans-serif'>Text         Ad:         <input type='text' name='ad' size='80'>        <input type='hidden' name='pass' value='$pass'>        <input type='submit' value='Save Changes'>        </font></td>    </tr>  </table></form>";
?>