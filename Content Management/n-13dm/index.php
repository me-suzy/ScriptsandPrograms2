
<?php
include 'config.php';

$category = $_GET['cat'];
$file = $_GET['file'];

echo "<html>\n";
echo "<head>\n";
echo "<title>\n";
echo "Downloads - $category\n";
echo "</title>\n";
echo "</head>\n";
echo "<body bgcolor=\"FFFFFF\" text=\"000000\">\n";



if($category == ""){
                    $result = mysql_list_tables($database);
                    echo "<font size=\"2\" face=\"Tahoma\">Category:<br>";
                    echo "<center><table border=\"0\" width=\"90%\"><td>";
                             while ($row = mysql_fetch_row($result)) {
                                     if($row[0] == "$admintable"){
                                     } elseif ($row[0] == "admin"){
                                               } else {
                                               echo "<img src=\"images/bullet.gif\">   <font size=\"2\" face=\"Tahoma\"><a href=\"?action=downloads&cat=$row[0]\">$row[0]</a>";
                                               echo "<br>";
                             }
                             }
                                     echo "<td></table></center>";

} else {
        if($file == ""){
         $sql =  "SELECT * FROM $_GET[cat] ORDER BY fileid DESC";
         $result = mysql_query($sql)
         or die ("Couldn't execute query.");
echo "\n";
echo "<font size=\"2\" face=\"Tahoma\">Downloads:";
echo "<center><table border=\"0\" width=\"90%\"><td>";
        while($row = mysql_fetch_array($result)) {
              $tmp = $row['name'];
              if($tmp == ""){
                      } else {
        echo "<img src=\"images/bullet.gif\">   <font size=\"2\" face=\"Tahoma\"><a href=\"?action=downloads&cat=$category&file=$row[fileid]\" style=\"text-decoration: none\">$row[name]</a>";
        echo "<br>";
        }
        }
        echo "<td></table></center>";
        } else {
                $sql2 = "SELECT name FROM $_GET[cat] WHERE fileid = $file";
                $result2 = mysql_query($sql2);
                $sql3 = "SELECT description FROM $_GET[cat] WHERE fileid = $file";
                $result3 = mysql_query($sql3);
                $sql4 = "SELECT version FROM $_GET[cat] WHERE fileid = $file";
                $result4 = mysql_query($sql4);
                $sql5 = "SELECT demo FROM $_GET[cat] WHERE fileid = $file";
                $result5 = mysql_query($sql5);
                $sql6 = "SELECT downloadurl FROM $_GET[cat] WHERE fileid = $file";
                $result6 = mysql_query($sql6);
                $sql7 = "SELECT downloads FROM $_GET[cat] WHERE fileid = $file";
                $result7 = mysql_query($sql7);

                $name = mysql_result($result2,0);
                $description = mysql_result($result3,0);
                $version = mysql_result($result4,0);
                $demo = mysql_result($result5,0);
                $downloadurl = mysql_result($result6,0);
                $totaldownloads = mysql_result($result7,0);

echo "\n";
echo "<table border=\"0\" cellspacing=\"0\" bordercolor=\"#C0C0C0\" width=\"100%\" bordercolorlight=\"#C0C0C0\" bordercolordark=\"#FFFFFF\" cellpadding=\"0\" style=\"border-collapse: collapse\">\n";
echo "  <tr>\n";
echo "    <td width=\"30%\">\n";
echo "    <p align=\"center\">\n";
echo "    <font size=\"2\" face=\"Tahoma\">$name\n";
echo "    </td>\n";
echo "  </tr>\n";
echo "</table>\n";
echo "<table border=\"0\" cellspacing=\"1\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\">\n";
echo "  <tr>\n";
echo "    <td width=\"100%\"></td>\n";
echo "  </tr>\n";
echo "</table>\n";
echo "<table border=\"0\" cellspacing=\"0\" bordercolor=\"#C0C0C0\" width=\"100%\" bordercolorlight=\"#C0C0C0\" bordercolordark=\"#FFFFFF\" cellpadding=\"0\" style=\"border-collapse: collapse\">\n";
echo "  <tr>\n";
echo "    <td width=\"12%\"><font size=\"2\" face=\"Tahoma\">Description</font></td>\n";
echo "    <td width=\"88%\">\n";
echo "    <font size=\"2\" face=\"Tahoma\">$description\n";
echo "    </td>\n";
echo "  </tr>\n";
echo "</table>\n";
echo "<table border=\"0\" cellspacing=\"1\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\">\n";
echo "  <tr>\n";
echo "    <td width=\"100%\"></td>\n";
echo "  </tr>\n";
echo "</table>\n";
echo "<br>\n";

echo "<table border=\"0\" cellspacing=\"0\" bordercolor=\"#C0C0C0\" width=\"100%\" bordercolorlight=\"#C0C0C0\" bordercolordark=\"#FFFFFF\" cellpadding=\"0\" style=\"border-collapse: collapse\">\n";
echo "  <tr>\n";
echo "    <td width=\"12%\" align=\"center\">\n";
echo "    <p align=\"left\"><font size=\"2\" face=\"Tahoma\">Version</font></td>\n";
echo "    <td width=\"88%\" align=\"center\">\n";
echo "    <p align=\"left\"><font face=\"Tahoma\" size=\"2\">\n";
echo "    $version\n";
echo "    </font></td>\n";
echo "  </tr>\n";
echo "</table>\n";
echo "<br>";
echo "<table border=\"0\" cellspacing=\"0\" bordercolor=\"#C0C0C0\" width=\"100%\" bordercolorlight=\"#C0C0C0\" bordercolordark=\"#FFFFFF\" cellpadding=\"0\" style=\"border-collapse: collapse\">\n";
echo "  <tr>\n";
echo "    <td width=\"12%\" align=\"center\">\n";
echo "    <p align=\"left\"><font size=\"2\" face=\"Tahoma\">Downloaded</font></td>\n";
echo "    <td width=\"88%\" align=\"center\">\n";
echo "    <p align=\"left\"><font face=\"Tahoma\" size=\"2\">\n";
echo "    $totaldownloads times\n";
echo "    </font></td>\n";
echo "  </tr>\n";
echo "</table>\n";
echo "<table border=\"0\" cellspacing=\"1\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\">\n";
echo "  <tr>\n";
echo "    <td width=\"107\">\n";
echo "    <td width=\"786\">\n";
echo "<a target=\"_NEW\" href=\"$demo\" style=\"text-decoration: none\"><img src=\"images/demo.gif\" border=\"0\"></a><a href=\"http://" . $domain . $directory . "download.php?cat=$_GET[cat]&download=$file\" style=\"text-decoration: none\"><img src=\"images/download.gif\" border=\"0\"></a></td>    </td>\n";
echo "  </tr>\n";
echo "</table>\n";
}
}
?>
</body>
</html>