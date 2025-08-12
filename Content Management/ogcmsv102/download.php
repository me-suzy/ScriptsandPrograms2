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
//download script
//connect to db
require_once 'connect.php';

//get file
if (isset($_GET['id'])) {
   $id = $_GET['id'];
   $query = "SELECT downloads, file_name FROM og_post WHERE id = '$id' LIMIT 1";
   $query_result = mysql_query($query) or DIE(mysql_error());
   $dl_array = mysql_fetch_array($query_result);
   $dl = $dl_array['downloads'];
   $file_name = $dl_array['file_name'];
   //increase download counter
   ++$dl;
   //get timstamp for last time downloaded
   $time = strtotime("now");
   //insert new values into db
   $query ="UPDATE og_post SET downloads = '$dl', last_dl = '$time' WHERE id = '$id'";
   $query_result = mysql_query($query) or DIE(mysql_error());
   $dl_loc = "files/" . $file_name;
   //print $dl_loc;
   //die();
   Header("Location: $dl_loc");
   }
//if no id is provided, go to post_list.php, showing available downloads.
else {
     $loc = "post_list.php?dl=1";
     Header("Location: $loc");
}
?>


