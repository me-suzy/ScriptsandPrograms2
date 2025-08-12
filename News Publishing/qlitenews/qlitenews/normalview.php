<?php 

  include("admin/config.php");
  
  $db = mysql_connect($dbhost,$dbuser,$dbpass); 
  mysql_select_db($dbname) or die("Cannot connect to database");
  $query = "SELECT * FROM qlitenews ORDER BY id $news_format LIMIT $news_limit"; 
  $result = mysql_query($query);

  echo "<table>";
  while ($r = mysql_fetch_array($result)) {
    echo "
      <tr><td><span style=\"color: $head_color; font-weight: bold;\">$r[title]</span></td></tr>
      <tr><td><span style=\"color: $body_color\">$r[news]</span></td></tr>\n";
    if ($news_info == 1) {
      echo "<tr><td><div style=\"color: $info_color; border-top: 1px dashed $border_color; text-align: right;\">Posted by $r[author] / $r[date]</div></td></tr>\n";
    }
  }
  //PLEASE DO NOT REMOVE THE LINK BELOW! A LINK TO OUR WEBSITE IS ALL WE ASK FOR AND WE HOPE THAT YOU RESPECT THAT!
  echo "
      <tr><td align=\"center\"><strong>Powered by qliteNews / <a href=\"http://www.r2xDesign.net\" title=\"Web Scripting Resources - PHP Scripts, PHP Snippets, PHP Tutorials and Free Templates\">r2xDesign.net</a></strong></td></tr>
    </table>\n";

?>
