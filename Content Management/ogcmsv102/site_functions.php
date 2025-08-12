<?php
/*-----------------------------------------------------------------------------+
|                            OG CMS v1.02                         |
+------------------------------------------------------------------------------+
| This file is component of OG CMS :: web management system                    |
|                                                                              |
|                    Please, send any comments, suggestions and bug reports to |
|                                                      olegu@soemme.no         |
| Original Author: Vidar Løvbrekke Sømme                                       |
| Author email   : olegu@soemme.no                                             |
| Project website: http://www.soemme.no/                                       |
| Licence Type   : FREE                                                        |
+------------------------------------------------------------------------------+ */
//functions for ekstra stuff on the front page
function top_dl () {
         //get language variables
         global $lang;
         //print header:
         print "<div class=\"post_heading\">{$lang[aux_top_five_dl]}</div><br>";
         //check for downloads:
         $count_result = mysql_query("select count(*) from og_post WHERE downloads >= 1");
         $total = mysql_result($count_result, 0, 0);
         if ($total < 1) {
            print "{$lang[aux_no_downloads_yet_text]}";
            }
         else {
              //get the top 5 downloads from the db
              $query = "SELECT * FROM og_post WHERE downloads >= 1 ORDER BY downloads desc LIMIT 5";
              $query_result = mysql_query($query) or DIE (mysql_error());
              $i = 1;
              while ($row = mysql_fetch_array($query_result)) {
                    print "$i: <a href=\"post_view.php?id={$row['id']}\">{$row['file_name']}</a><br>";
                    $i++;
              }
          }
}

function last_comment () {
         //get language variables
         global $lang;
         //functions to get the last 5 comments
         //headline
         print "<div class=\"post_heading\">{$lang[aux_five_last_comments]}</div><br>";
         //check if there are any comments yet:
         $count_result = mysql_query("select count(*) from og_comments");
         $total = mysql_result($count_result, 0, 0);
         if ($total < 1) {
            print "{$lang[aux_no_comments_yet_text]}";
            }
         else {
              $query = "SELECT * FROM og_comments ORDER BY time DESC LIMIT 5";
              $query_result = mysql_query($query);
              $i = 1;
              while ($row = mysql_fetch_array($query_result)) {
                    $id = $row['id'];
                    $pquery = "SELECT * FROM og_post WHERE id = '$id' LIMIT 1";
                    $pquery_result = mysql_query($pquery) or die (mysql_error());
                    $prow = mysql_fetch_array($pquery_result);
                    print "$i: <a href=\"post_view.php?id=$id\">{$prow['title']}</a><br>";
                    $i++;
              }
         }
}






