<?php

$where = "<a href=\"index.php\">Home</a> &gt; <a href=\"view_forum.php?fid=$_GET[fid]\">View Forum</a> &gt; <a href=\"view_thread.php?fid=$_GET[fid]&tid=$_GET[tid]\">View Post</a> > Delete Thread";

include("header.php");

$id = $_GET["fid"];

if (is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){

$rowid = @$db->query("topics", "7", $tid);
$mods = @explode(", ", $db->data["_DB"]["forums"]["$rowid"][4]); //explode the mods table
$to_delete = array(); //define this as an array

  if ($user_power == "1" or in_array($_SESSION["user"], $mods)){
$deleted_posts = "1";


  for ($psts = 2; $psts < count($db->data["_DB"]["posts"]); $psts++){

      if ($db->data["_DB"]["posts"]["$psts"][5] == $_GET["tid"]){
        $deleted_posts += "1"; //add 1 post onto the count for each found post
        $to_delete[] = $psts; //put all rows matching into an array
      }; //end if
  }; //end for $psts
  

  foreach ($to_delete as $row){
    $db->deleteRow("posts", $row); //delete the row of corresponding posts
  }; //end foreach

  //every post_id that us > the deleted topic id needs decrementing by 1
  //otherwise posts will occur in wrong objects.
  
  $rowid = $db->query("forums", "7", $id);

    $post_total = $db->data["_DB"]["forums"]["$rowid"][3] - $deleted_posts;
 $db->data["_DB"]["forums"]["$rowid"][3] = $post_total;
 
 $topic_total = $db->data["_DB"]["forums"]["$rowid"][2] - 1;
 $db->data["_DB"]["forums"]["$rowid"][2] = $topic_total;




  
$rowid = $db->query("topics", "7", $_GET["tid"]);

$db->deleteRow("topics", $rowid);
$db->reBuild();



   echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$_LANG[82]</font></div>\r\n");
  echo ("<meta http-equiv=\"refresh\" content=\"2;url=view_forum.php?fid=$_GET[fid]\">");

  }; //end if
}; //end if is_logged_in

include("footer.php");

?>
