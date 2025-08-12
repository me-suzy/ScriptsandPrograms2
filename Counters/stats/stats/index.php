
<?php

/*
version 0.1
Build by chris mccabe- under the gpl license
for updates and news or if you have feedback
http://scripts.maxersmix.com
*/

$EX_page = $_GET['page'];
$IN_date = date("Y-m-d");
$YEST_date = date('Y-m-d', mktime(0, 0, 0, date("m") , date("d") - 1, date("Y")));

include("dbinfo.php");

if(!empty($EX_page)){

     $query = "INSERT INTO `stats` (`id`, `date`, `page`) VALUES ('', '".$IN_date."', '".$EX_page."');";
     mysql_query($query);
}
else
{
   echo "For install information goto <a href=\"http://scripts.maxersmix.com/stats/install.html\">scripts install</a><br>";
   echo "Total impressions on all pages since starting: <b>";
   $sql = "SELECT count(*) FROM stats;";
   $result = mysql_query($sql);
   $page_row = mysql_fetch_row($result);
   echo $page_row['0']."</b><br>";
   $page_query = "SELECT DISTINCT page FROM `stats`";
   $page_rs = mysql_query($page_query);
   echo "<table width=\"70%\" border=\"0\"> \n <tr>";
   echo "<td>Page</td><td>Hits today</td><td>Hits Yesterday</td><td>Total hits</td>";
   echo "</tr>";
   if(!empty($page_rs)){
   while($row = mysql_fetch_row($page_rs)){
      echo "<tr> <td>";
      echo $row['0'];
      echo "</td><td>";
      //todays hits query
      $today_query = "SELECT COUNT(page) FROM stats WHERE date = '".$IN_date."' AND page = '".$row['0']."'";
      $today_rs = mysql_query($today_query);
      $today_row = mysql_fetch_row($today_rs);
      echo $today_row[0];
      echo "</td><td>";
      //yesterdays hits query
      $yesterday_query = "SELECT COUNT(page) FROM stats WHERE date = '".$YEST_date."' AND page = '".$row['0']."'";
      $yest_rs = mysql_query($yesterday_query);
      $yest_row = mysql_fetch_row($yest_rs);
      echo $yest_row[0];
      echo "</td><td>";
      //total hits query
      $total_query = "SELECT COUNT(page) FROM stats WHERE page = '".$row['0']."'";
      $total_rs = mysql_query($total_query);
      $total_row = mysql_fetch_row($total_rs);
      echo $total_row[0];
      echo "</td></tr>";
   }
   }
   else

   echo "</table>";
}
mysql_close();
      ?>

