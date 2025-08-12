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
//include required files----------------
require_once 'header_footer.php';
require_once 'post_view_function.php';
//--------------------------------------

 class Pager 
   { 
       function getPagerData($numHits, $limit, $page) 
       { 
           $numHits  = (int) $numHits; 
           $limit    = max((int) $limit, 1); 
           $page     = (int) $page; 
           $numPages = ceil(($numHits - 1) / $limit);

           $page = max($page, 1); 
           $page = min($page, $numPages); 


           $offset = max((($page - 1) * $limit),0);

           $ret = new stdClass; 

           $ret->offset   = $offset + 1;
           $ret->limit    = $limit; 
           $ret->numPages = $numPages; 
           $ret->page     = $page; 

           return $ret; 
       } 
   }


function list_posts() {
         //get language variables
         global $lang;
         $dlink = "";
         //make the sysvars available:
         global $sysvars;
         // get the pager input values 
         $page = $_GET['page'];
         $limit = $sysvars['post_count'];
         //if ($page <= 1) {
         //   $limit--;
         //}

         //get correct query to determine total posts to show:
         //if a zone is selected:
         if (isset($_GET['zone'])) {
            //if a zone is selected,
            //get zone name from Get variable:
            $zone = $_GET['zone'];
            $dlink = "&amp;zone=$zone";
            //query to determine total:
            $result = mysql_query("select count(*) from og_post WHERE zone = '$zone'");

         }
         //if download section is selected:
         elseif ($_GET['dl'] == 1) {
                $dl = $_GET['dl'];
                $dlink = "&amp;dl=1";
                $result = mysql_query("select count(*) from og_post WHERE LENGTH(file_name) > 3");
         }
         else {
              //if a zone is not selected,
              $result = mysql_query("select count(*) from og_post");
         }
         $total = mysql_result($result, 0, 0);
         //$total_pager = $total - 1;
         // work out the pager values 
         $pager  = Pager::getPagerData($total, $limit, $page);
         $offset = $pager->offset;
         $page   = $pager->page;
         // use pager values to fetch data

         //if a specific zone:
         if (isset($zone)) {
            //perform this query:
            $query = "SELECT * FROM og_post WHERE zone = '$zone'
                   ORDER BY date DESC LIMIT $offset, $limit";
            $zquery = "SELECT * FROM og_zones WHERE id = '$zone' LIMIT 1";
            $zquery_result = mysql_query($zquery) or die (mysql_error());
            $zone_name = mysql_fetch_array($zquery_result);
            //print a zone heading:
            print "<div class=\"post_big_heading\">{$zone_name['zone']}</div><br><br>";
         }
         //if dowload section:
         elseif ($dl == 1) {
            $query = "SELECT * FROM og_post WHERE LENGTH(file_name) > 3
                ORDER BY date DESC LIMIT $offset, $limit";
                print "<div class=\"post_big_heading\">{$lang[post_downloads_title]}</div><br><br>";
                }
         else {
              $query = "SELECT * FROM og_post ORDER BY date DESC limit $offset, $limit";
         }


         //show the entire fisrt post in its full, if no page is selected:
         if ($page <= 1) {
            if (isset($zone)) {
               //perform this query:
               $one_query = "SELECT id FROM og_post WHERE zone = '$zone'
               ORDER BY date DESC LIMIT 1";
            }
            elseif ($dl == 1) {
                   $one_query = "SELECT id FROM og_post WHERE LENGTH(file_name) > 3
                   ORDER BY date DESC LIMIT 1";
            }
            else {
              $one_query = "SELECT id FROM og_post ORDER BY date DESC limit 1";
            }
         //perform query to pull id from querys
         $one_result = mysql_query($one_query) or die (mysql_error());
         $one_row = mysql_fetch_array($one_result);
         $one_id = $one_row['id'];
         show_post($one_id);
         }

         //perform query to select posts to show
         $query_result = mysql_query($query)
                       or die ("No posts selected");
         //page counter reset:
         $i = 0;
          //initlialising of aligning variable
          $align = "right";
          $brk = 0;
          //list posts selected:
          while ($row = mysql_fetch_array($query_result)) {
                  $i++;
                  //align swithcing
                  switch ($align) {
                         case "right":
                              $align = "left";
                              $brk = 0;
                              break;
                         case "left":
                              $align = "right";
                              $brk = 1;
                              break;
                         default:
                             $align = "left";
                  }
                  //post list conatiner
                  print "<div id=\"list_container_$align\">";
                  //print the date:
                  print "<div class=\"post_date\">" . strftime("%c", $row['date']) . "</div>";
                  print "<img src=\"images/invisible.gif\" width=\"1\" height=\"100\" border=\"0\" />";
                  //if downlaod section is selected, show only this:
                  if ($dl == 1) {
                     if (!$row['image_name'] == null) {
                        print "<image src=\"images/small_{$row['image_name']}\"/>";
                     }
                       print "<div class=\"download\"><a href=\"post_view.php?id={$row['id']}\">{$row['title']}</a>:<br>
                       {$lang[post_download_prompt]} <a href=\"download.php?id={$row['id']}\">{$row['file_name']}</a></div>
                       <br>" ;
                       //if logged in as admin, show last time downloaded, and download count:
                       if ($_SESSION['authorized']) {
                          $timestamp  = $row['last_dl'];
                          $time = strftime("%c", $timestamp);
                          print "<div class=\"admin_dl\">{$lang[post_downloaded]} {$row['downloads']} {$lang[post_times_since]} $time</div>";
                       }
                  }
                  else {
                  print "<div class=\"post_heading\">";
                  //if no zone is selected print the zone of the post in front
                  //of the title
                  if (!isset($zone)) {
                     $zone_id = $row['zone'];
                     $zquery = "SELECT * FROM og_zones WHERE id = '$zone_id' LIMIT 1";
                     $zquery_result = mysql_query($zquery) or die (mysql_error());
                     $zone_name = mysql_fetch_array($zquery_result);
                     print $zone_name['zone'];
                     print ": ";
                  }
                  //print headline, with link to the post
                  print "<a href=\"post_view.php?id={$row['id']}\">{$row['title']}</a>
                  <br></div>";
                  //if the post conatains an image, shjow the thumbnail
                  if (!$row['image_name'] == null) {
                     print "<image src=\"images/small_{$row['image_name']}\"/>";
                     }
                  //print the introduction:
                  print "<div class=\"ingress\">{$row['ingress']}</div>";
                  }
                  //if authiruzed, print edit and delete links:
                  if ($_SESSION['authorized']) {
                     print "<div class=\"del_ed\">[<a href=\"admin_edit_post.php?id={$row['id']}\">{$lang[post_edit_link]}</a>]
                           [<a href=\"admin_delete_post.php?id={$row['id']}\">{$lang[post_delete_link]}</a>]
                           </div><br>";
                  }
                  print "</div>";
                  if ($brk == 1) {
                     print "<div class=\"invisible\"></div>";
                    }
         }
         print "<br><div id=\"pagination_container\">";
         //Output what posts are showing
         $start = $offset + 1;
         if ($page <= 1) {
            $start--;
         }
         $end = $offset + $i;
         print "<p>{$lang[post_showing_posts]} $start {$lang[post_through]} $end {$lang[post_of]} $total</p> <br>";

         //pagination:
         if ($page <= 1) // this is the first page - there is no previous page
            echo "{$lang[post_previous]}";
         else            // not the first page, link to the previous page
            echo "<a href=\"" . $_SERVER['PHP_SELF'] . "?page=" . ($page - 1) . $dlink . "\">{$lang[post_previous]}</a>";

         for ($i = 1; $i <= $pager->numPages; $i++) {
             echo " | ";
             if ($i == $pager->page)
                echo "{$lang[post_page]} $i";
             else
                echo "<a href=\"" . $_SERVER['PHP_SELF'] . "?page=$i" . $dlink . "\">{$lang[post_page]} $i</a>";
         }

         if ($page == $pager->numPages) // this is the last page - there is no next page
            echo " | {$lang[post_next]}";
         else            // not the last page, link to the next page
            echo " | <a href=\"" . $_SERVER['PHP_SELF'] . "?page=" . ($page + 1) . $dlink . "\">{$lang[post_next]}</a>";
print "</div>";
}

//get system variables from db
$sysvars = GetSysVar();
show_header ();
list_posts();
show_footer ();
?>




