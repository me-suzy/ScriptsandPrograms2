<?
include("config.php");
include("connect.php");
$result = mysql_query("SELECT * FROM news ORDER BY id DESC") 
or die(mysql_error()); 
// keeps getting the next row until there are no more to get
while($row = mysql_fetch_array( $result )) {
// Print out the contents of each row into a table
echo "<a name='";
echo $row['id'];
echo "'><b>"; 
echo $row['title'];
echo "</b></a><br /><br />"; 
echo $row['body'];
echo "<br /><br /><i>Posted on: "; 
echo $row['date'];
echo "</i><hr noshade color='black' width='99%'>"; 
}
?>
