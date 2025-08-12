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
function show_post ($id) {
         //get language variables
         global $lang;
         //query to get post from db
         $query = "SELECT * FROM og_post WHERE id = '$id' LIMIT 1";
         $query_result = mysql_query($query) or die ("Horrible db fault, please evacuate");
         //view container
         print "<div id=\"view_container\">";
         //now print the post
         $row = mysql_fetch_array($query_result);
                //formating---------------------------------
                print "<div class=\"post_view_heading\">";
                //------------------------------------------
                print $row['title'];
                print "</div>";
                if (!$row['image_name'] == null) {
                      print "<image src=\"images/";
                      print $row['image_name'];
                      print "\"/>";
                      }
                //formating---------------------------------
                print "<div class=\"ingress\">";
                //------------------------------------------
                print $row['ingress'];
                print "</div><br>";
                if (!$row['main_text'] == null) {
                   //formating---------------------------------
                   print "<div class=\"main\">";
                   //------------------------------------------
                   print $row['main_text'];
                   print "</div>";
                   }
                //if there is a file attached show download link
                if (!$row['file_name'] == null) {
                   print "<div class=\"download\">
                         {$lang[post_download_prompt]} <a href=\"download.php?id={$row['id']}\">{$row['file_name']}
                         </a></div>";
                   //if yourte admin, whow download count
                    if ($_SESSION['authorized']) {
                          $timestamp  = $row['last_dl'];
                          $time = strftime("%c", $timestamp);
                          print "<div class=\"admin_dl\">{$lang[post_downloaded]} {$row['downloads']} {$lang[post_times_since]} $time</div>";
                       }
                   }
                //formating---------------------------------
                print "<div class=\"post_date\">Posted: ";
                //------------------------------------------
                print strftime("%c", $row['date']);
                print "</div>";
                print "<br>";
                //show comments, allowed and if any
                if ($row['comment'] == 1) {
                   print "<div class=\"comments\">";
                   $cquery = "SELECT * FROM og_comments WHERE id = '$id'";
                   $cquery_result = mysql_query($cquery);
                   //if there where no comments yet:
                   if (!$cquery_result) {
                      print "{$lang[post_no_comments]}";
                   }
                   else {
                       while ($crow = mysql_fetch_array($cquery_result)) {
                              print "<hr />";
                              print "{$crow['comment']}
                                    <br><br>
                                    {$lang[post_comment_posted_by]} {$crow['name']} $lang[post_comment_posted_at] ";
                              print strftime("%c", $crow['time']);
                              //if your the admin, you'll allso get to see this:
                              if ($_SESSION['authorized']) {
                                 print "<br><br>
                                       {$lang[post_comment_author_mail]} <a href=\"mailto:{$crow['mail']}\">{$crow['mail']}</a><br><br>
                                       [<a href=\"comment.php?edit={$crow['cid']}\">{$lang[post_comment_edit_link]}</a>]
                                        [<a href=\"comment.php?delete={$crow['cid']}\">{$lang[post_comment_delete_link]}</a>]<br><br>";
                              }
                        }
                   }
                   //print add comment link:
                   print "<br>[<a href=\"comment.php?add={$row['id']}\">{$lang[post_comment_add_link]}</a>]<br>
                   </div>";
                   }
                //if authorized print edit and delete links:
                if ($_SESSION['authorized']) {
                    print "<div class=\"del_ed\">[<a href=\"admin_edit_post.php?id=";
                    print $row['id'];
                    print "\">{$lang[post_edit_link]}</a>] [<a href=\"admin_delete_post.php?id=";
                    print $row['id'];
                    print "\">{$lang[post_delete_link]}</a>]</div>";
               }
print "</div><br><br>";
}
?>