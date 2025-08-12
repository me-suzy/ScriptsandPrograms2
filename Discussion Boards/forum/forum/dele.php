<?php

$where = "<a href=\"index.php\">Home</a> &gt; <a href=\"view_forum.php?fid=$_GET[fid]\">View Forum</a> &gt; New Thread";


include("header.php");

$id = $_GET["fid"];
$tid = $_GET["tid"];
$line = $_GET["reply"];
$rowid2 = $db->query("forums", "7", $id);
$mods = explode(", ", $db->data["_DB"]["forums"]["$rowid2"][4]); //explode the mods table
$r = $_GET["reply"];

if (!is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){
     echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">In order to Delete replies you must be logged in!<br />Register now, <a href=\"register.php\">here</a>, its FREE! and easy.</font></div>\r\n");
} else {

//get the row number that the topic id is on


if ($db->data["_DB"]["posts"]["$r"]["2"] == @$_SESSION["user"] or $user_power == "1" or (in_array(@$_SESSION["user"], $mods))){


$rowid = $db->query("topics", "7", $tid);
if ($db->data["_DB"]["topics"]["$rowid"][6] == "true"){
   echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$_LANG[66]</font></div>\r\n");
} else {


     echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$_LANG[67].<br /><br /></font></div>\r\n");
     $db->deleteRow("posts", $line);




     $db->reBuild();
     echo ("<meta http-equiv=\"refresh\" content=\"2;url=view_forum.php?fid=$id\">");


}; //end isset locked


} else {

  echo ("<center><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$_LANG[65]</font></center>");

  }; //end if
  
}; //end !is_logged_in

include("footer.php");

?>
