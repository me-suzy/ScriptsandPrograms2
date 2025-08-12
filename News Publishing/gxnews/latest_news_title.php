<?
include("config.php");
include("connect.php");
$result = mysql_query("SELECT * FROM news ORDER BY id DESC LIMIT 3") 
or die(mysql_error()); 

// keeps getting the next row until there are no more to get
while($row = mysql_fetch_array( $result )) {
// Print out the contents of each row into a table
echo "<b><a href='news.php#";
echo $row['id'];
echo "' title='Site News'>";
echo $row['title'];
echo "</a></b><br />";
}
?>








