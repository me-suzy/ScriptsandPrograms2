<?php

$where = "<a href=\"index.php\">Home</a> &gt; <a href=\"view_forum.php?fid=$_GET[fid]\">View Forum</a> &gt; <a href=\"view_thread.php?fid=$_GET[fid]&tid=$_GET[tid]\">View Post</a> > Open Thread";

include("header.php");

$id = $_GET["fid"];
$tid = $_GET["tid"];
$fid = $_GET["fid"];

if (is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){

$rowid = $db->query("topics", "7", $tid);
$mods = $db->query("forums", "7", $fid);
$mods = explode(", ", $db->data["_DB"]["forums"]["$mods"][4]); //explode the mods table


  if ($user_power == "1" or in_array($_SESSION["user"], $mods)){

$db->data["_DB"]["topics"]["$rowid"][6] = "false";
$db->reBuild();
   echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$_LANG[83]</font></div>\r\n");
   echo ("<meta http-equiv=\"refresh\" content=\"2;url=view_forum.php?fid=$_GET[fid]\">");

  }; //end if
}; //end if is_logged_in

include("footer.php");

?>
