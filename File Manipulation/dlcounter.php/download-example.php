<html>
<body>

<?
/* set up mysql connection */
include("inc.mysql.php");

/* query database */
$result = mysql_query("SELECT * FROM download");

/* output available downloads if found */
if (mysql_num_rows($result) < 1) {
  echo "<center><b>no downloads available!</b></center><br>\n";
} else {
  echo "<table border=1 align=center>\n";
  while ($row = mysql_fetch_assoc($result)) {
    echo "  <tr>\n";
    echo "    <td>" . $row["title"] . "</td>\n";
    echo "    <td><a href=\"download.php?file=" . $row["filename"] . "\">download!</a> (" . $row["downloads"] . ")</td>\n";
    echo "  </tr>\n";
  }
  echo "</table>\n";
}
?>

</body>
</html>