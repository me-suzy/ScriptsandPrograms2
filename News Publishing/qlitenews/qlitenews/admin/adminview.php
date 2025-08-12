<?php include("config.php"); ?>
<div class="title">Current News in <a href="http://<?php echo $news_include; ?>"><?php echo $news_include; ?></a></div>
<p id="headtext">Welcome to qliteNews Admin Panel!<br/>
           You are connecting from <?php echo $_SERVER["REMOTE_ADDR"]; ?><br/>
           Today's date is <?php echo date("F j, Y"); ?>
</p>
  <?php

    $db = mysql_connect($dbhost,$dbuser,$dbpass); 
    mysql_select_db($dbname) or die("Cannot connect to database");
    $query = "SELECT * FROM qlitenews ORDER BY id $news_format LIMIT $news_limit"; 
    $result = mysql_query($query);
    
    while ($r = mysql_fetch_array($result)) {
      echo "
        <p><strong>$r[title]</strong><br/>
           $r[news]
        </p>
        <p align=\"right\"><a href=\"index.php?page=modifynews&amp;id=$r[id]\"><strong>Modify News!</strong></a></p>";
        if ($news_info == 1) {
          echo "<div class=\"newsborder\">Posted by $r[author] / $r[date]</div>\n";
        }
    }
  mysql_close($db);
  ?>
<p>* Please note that news theme is not in effect when viewing in the admin panel but the news format is the same as in normal view.</p>