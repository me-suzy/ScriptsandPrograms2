<center>
<?
#You can remove or edit this. It displays number of URLs in the database.
include "./mysql.php";
$result = mysql_query("select count(*) as num from $table");
$result_row = mysql_fetch_row($result);
$numentries = $result_row[0];
print "Search $numentries pages in the database";
?>
</center>

