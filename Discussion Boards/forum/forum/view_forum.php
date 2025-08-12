<?php

$where = "<a href=\"index.php\">Home</a> &gt; View Forum";


include("header.php");

$id = $_GET["fid"];



if (is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){
echo ("
<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"$fwidth\" align=\"center\">
   <tr align=\"right\">
      <td width=\"100%\" colspan=\"1\">
       <a href=\"newtopic.php?fid=$id\"><img src=\"$skins/buttons/newtopic.gif\" alt=\"New Topic\" border=\"0\" /></a>
      </td>
   </tr>
</table>");
}; //end is_logged_in


$forumid = $db->query("password", "0", $_GET["fid"]); //query the id of forum in password table.
if ($forumid != null){
$forumpw = $db->data["_DB"]["password"]["$forumid"][1];  //grab the password for the forum.
};

echo ("<form method=\"post\" action=\"\">");
if (isset($_POST["pw"])){
  $_SESSION["mpw"] = $_POST["pw"];
}; //end isset


$rowid = $db->query("forums", "7", $id);
$mods = explode(", ", $db->data["_DB"]["forums"]["$rowid"][4]); //explode the mods table


$musthave = trim($db->data["_DB"]["forums"]["$rowid"][8]); //the forum requirement level for viewing
$create = trim($db->data["_DB"]["forums"]["$rowid"][9]); //the forum requirement level for viewing
$reply = trim($db->data["_DB"]["forums"]["$rowid"][10]); //the forum requirement level for viewing

if (in_array(@$_SESSION["user"], $mods) or ($user_power == "1")){
$modit = "can";
} else {
  $modit = "can not";
};
if (!is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){
  $modit = "can not";
}; //end

if (in_array($create, $status)){
$create = "can";
} else {
  $create = "can not";
};

if (in_array($reply, $status)){
$reply = "can";
} else {
  $reply = "can not";
};

$mustid = array_search("$musthave", $status);

if (@$status["$mustid"] == "$musthave"){

table_header("".$db->data["_DB"]["forums"]["$rowid"][0]);




if ($forumid != null && @$_SESSION["mpw"] != $forumpw){
echo ("<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" width=\"$fwidth\" align=\"center\">
   <tr bgcolor=\"$tbackground1\" align=\"center\" bgcolor=\"$tbackground1\">
      <td width=\"10%\" colspan=\"1\"><input type=\"text\" name=\"pw\" value=\"\"> <input type=\"submit\" name=\"pws\" value=\"Submit\"></td>
   </tr>
</table>");

};

if ($forumid == null or @$_SESSION["mpw"] == $forumpw){
echo ("
<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" width=\"$fwidth\" align=\"center\">
   <tr bgcolor=\"#FFFFFF\" align=\"center\" class=\"background\">
      <td width=\"10%\" colspan=\"1\"><img src=\"$skins/table/title_bg.gif\" alt=\"title_bg\" /><font size=\"$fsmall\" face=\"$fface\" color=\"$fsubtitle\"></font></td>
      <td width=\"5%\" colspan=\"1\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fsubtitle\"></font></td>
      <td width=\"40%\" colspan=\"1\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fsubtitle\"><b>$_LANG[43]</b></font></td>
      <td width=\"10%\" colspan=\"1\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fsubtitle\"><b>$_LANG[44]</b></font></td>
      <td width=\"10%\" colspan=\"1\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fsubtitle\"><b>$_LANG[45]</b></font></td>
      <td width=\"25%\" colspan=\"1\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fsubtitle\"><b>$_LANG[46]</b></font></td>
   </tr>\r\n");
   
#for ($thread = 2; $thread < count($db->data["_DB"]["topics"]); $thread++){  //origional loop

$total_displayed = null; //set the pointer to 0;

for ($thread = count($db->data["_DB"]["topics"]); $thread >= 2; $thread--){

//begin stickified topics to be shown.
if (@$db->data["_DB"]["topics"]["$thread"][5] == $id and @$db->data["_DB"]["topics"]["$thread"][8] == "true"){ //if topics forumid == $_GET["fid"] and != sticky

$total_displayed++;  //add 1 to total displayed.

$img = $db->data["_DB"]["topics"][$thread][0];

  if ($db->data["_DB"]["topics"][$thread][6] == "true"){
    $img = "icon/lock.gif";
  };

$last_post_date = $db->data["_DB"]["topics"][$thread][3];
$last_post_by = $db->data["_DB"]["topics"][$thread][2];

      $replies = "0";

   for ($posts = 2; $posts < count($db->data["_DB"]["posts"]); $posts++){
#   for ($posts = count($db->data["_DB"]["posts"]); $posts >= 2; $posts--){   //origional loop
     if (@$db->data["_DB"]["posts"]["$posts"][5] == $db->data["_DB"]["topics"]["$thread"][7]){
     $replies++;
     $last_post_date = $db->data["_DB"]["posts"]["$posts"][3];
     $last_post_by = $db->data["_DB"]["posts"]["$posts"][2];

     }; //end if

     }; //end $posts

     $last_month = explode(" ", $last_post_date);
     $last_month = $last_month[1];
$verify_new = substr($last_post_date, 0, 2);
if ($verify_new >= $laston and $last_month == $month.","){
  $new_reply = "$_LANG[49]";
} else {
  $new_reply = "";
};


$found_threads = true;
$pk = $db->data["_DB"]["topics"][$thread][7];
echo ("<tr bgcolor=\"#FFFFFF\" class=\"height\">
      <td width=\"10%\" colspan=\"1\" bgcolor=\"$tbackground1\" align=\"center\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fcolor\"><b>$new_reply</b></font></td>
      <td width=\"5%\" colspan=\"1\" bgcolor=\"$tbackground2\" align=\"center\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fsubtitle\"><img src=\"$img\" alt=\"Icon\" /></font></td>
      <td width=\"40%\" colspan=\"1\" bgcolor=\"$tbackground1\">

      <table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" width=\"100%\">
   <tr bgcolor=\"$tbackground2\">
      <td width=\"100%\">
      <img src=\"icon/sticky.gif\" alt=\"sticky!\" />
<font size=\"$fsmall\" face=\"$fface\" color=\"$fcolor\">
<a href=\"view_thread.php?fid=$id&tid=$pk\">".$db->data["_DB"]["topics"][$thread][1]."</a></font>
      </tr>
   </tr>
   </table>

      </td>
      <td width=\"10%\" colspan=\"1\" bgcolor=\"$tbackground2\" align=\"center\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fcolor\">".$db->data["_DB"]["topics"][$thread][2]."</font></td>
      <td width=\"10%\" colspan=\"1\" bgcolor=\"$tbackground1\" align=\"center\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fcolor\">$replies</font></td>
      <td width=\"25%\" colspan=\"1\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fcolor\">$_LANG[48]: <u>$last_post_by</u><br /></font><font size=\"$fsmall\" face=\"$fface\" color=\"$fcfade\">$_LANG[47]: $last_post_date</font></td>
   </tr>\r\n");
}; //end if
//end stickified topics to be shown

}; //end $thread on stickified

if (!isset($_GET["page"])){
  $_GET["page"] = "1";
}; //end if
$display = "15";
$start = $display * $_GET["page"] - $display;
$finish = $start + $display;

for ($thread = count($db->data["_DB"]["topics"]); $thread >= 2; $thread--){

//begin non-stickified topics to be shown.
if (@$db->data["_DB"]["topics"]["$thread"][5] == $id and @$db->data["_DB"]["topics"]["$thread"][8] != "true"){ //if topics forumid == $_GET["fid"] and != sticky

$total_displayed++;  //add 1 to total displayed.

#if ($total_displayed <= $display){
if ($total_displayed >= $start and $total_displayed <= $finish){

$img = $db->data["_DB"]["topics"][$thread][0];

  if ($db->data["_DB"]["topics"][$thread][6] == "true"){
    $img = "icon/lock.gif";
  };

$last_post_date = $db->data["_DB"]["topics"][$thread][3];
$last_post_by = $db->data["_DB"]["topics"][$thread][2];

      $replies = "0";

   for ($posts = 2; $posts < count($db->data["_DB"]["posts"]); $posts++){
#   for ($posts = count($db->data["_DB"]["posts"]); $posts >= 2; $posts--){   //origional loop
     if (@$db->data["_DB"]["posts"]["$posts"][5] == $db->data["_DB"]["topics"]["$thread"][7]){
     $replies++;
     $last_post_date = $db->data["_DB"]["posts"]["$posts"][3];
     $last_post_by = $db->data["_DB"]["posts"]["$posts"][2];

     }; //end if

     }; //end $posts

     $last_month = explode(" ", $last_post_date);
     $last_month = $last_month[1];
$verify_new = substr($last_post_date, 0, 2);
if ($verify_new >= $laston and $last_month == $month.","){
  $new_reply = "$_LANG[49]";
} else {
  $new_reply = "";
};


$found_threads = true;
$pk = $db->data["_DB"]["topics"][$thread][7];
echo ("<tr bgcolor=\"#FFFFFF\" class=\"height\">
      <td width=\"10%\" colspan=\"1\" bgcolor=\"$tbackground1\" align=\"center\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fcolor\">$new_reply</font></td>
      <td width=\"5%\" colspan=\"1\" bgcolor=\"$tbackground2\" align=\"center\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fsubtitle\"><img src=\"$img\" alt=\"Icon\" /></font></td>
      <td width=\"40%\" colspan=\"1\" bgcolor=\"$tbackground1\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fcolor\"><a href=\"view_thread.php?fid=$id&tid=$pk\">".$db->data["_DB"]["topics"][$thread][1]."</a></font></td>
      <td width=\"10%\" colspan=\"1\" bgcolor=\"$tbackground2\" align=\"center\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fcolor\">".$db->data["_DB"]["topics"][$thread][2]."</font></td>
      <td width=\"10%\" colspan=\"1\" bgcolor=\"$tbackground1\" align=\"center\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fcolor\">$replies</font></td>
      <td width=\"25%\" colspan=\"1\" bgcolor=\"$tbackground2\"><font size=\"$fsmall\" face=\"$fface\" color=\"$fcolor\">$_LANG[48]: <u>$last_post_by</u><br /></font><font size=\"$fsmall\" face=\"$fface\" color=\"$fcfade\">$_LANG[47]: $last_post_date</font></td>
   </tr>\r\n");
}; //end new if

}; //end if
//end non-stickified topics to be shown


}; //end for $thread on non stickified


if (!isset($found_threads)){
echo ("<tr bgcolor=\"#FFFFFF\">
      <td width=\"10%\" colspan=\"6\" bgcolor=\"$tbackground2\" align=\"center\"><font size=\"$fmedium\" face=\"$fface\" color=\"$fcfade\"><b>$_LANG[50]</b></font></td>
  </tr>");
}; //end isset
   
   
echo ("</table>");


}; //end if


table_footer();

$p_p = $_GET["page"] - 1;
$p_n = $_GET["page"] + 1;
echo ("
<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"$fwidth\" align=\"center\">");
echo ("   <tr>
      <td width=\"30%\" colspan=\"1\"></td>
      <td width=\"40%\" colspan=\"1\" align=\"center\"><font size=\"1\" face=\"Verdana\" color=\"$fcolor\">");
      
if ($_GET["page"] > "1"){
  echo ("[ <a href=\"view_forum.php?fid=$id&amp;page=$p_p\">&lt;--</a> ] ");
}; //end if

echo "<b>".$_GET["page"]."</b>";

  echo (" [ <a href=\"view_forum.php?fid=$id&amp;page=$p_n\">--&gt;</a> ] ");

   echo ("</font></td>  ");

if (is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){
echo ("
      <td width=\"30%\" colspan=\"1\" align=\"right\">
       <a href=\"newtopic.php?fid=$id\"><img src=\"$skins/buttons/newtopic.gif\" alt=\"New Topic\" border=\"0\" /></a>
      </td>
  ");
}; //end is_logged_in

echo ("</tr></table>");

} else {

  echo "<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">".$_LANG["FORBID"]."<br /></font></div>";

  }; //end requirement check
  

  echo ("<div align=\"center\"><table border=\"0\" cellspacing=\"1\" bgcolor=\"$tborder_color2\" cellpadding=\"3\" width=\"200\">
  <tr bgcolor=\"$tbackground1\">
    <td width=\"100%\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">

                        You <b>$create</b> Create topics<br />
                        You <b>$reply</b> Create Replys<br />
                        You <b>$modit</b> Edit Other Posts<br />
                        You <b>$modit</b> Delete Other Posts<br />
                        </font></td>
 </tr>
 </table></div>");
 


include("footer.php");

?>
