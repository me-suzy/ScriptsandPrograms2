<?php

$where = "<a href=\"index.php\">Home</a> &gt; <a href=\"view_forum.php?fid=$_GET[fid]\">View Forum</a> &gt; View Post";

include("header.php");

$id = $_GET["fid"];
$tid = $_GET["tid"];
$rowid = $db->query("topics", "7", $tid);
$postpp = "2";
$rowid2 = $db->query("forums", "7", $id);
$mods = explode(", ", $db->data["_DB"]["forums"]["$rowid2"][4]); //explode the mods table


//begin algorithm for topics per page
if (!isset($_GET["page"])){
  $_GET["page"] = "1"; //set the page to 1 if its not set
};
$end = $_GET["page"] * $postpp;
$start = $end - $postpp;

$rrrowid = $db->query("forums", "7", $id);

$musthave = trim($db->data["_DB"]["forums"]["$rrrowid"][8]); //the forum requirement level for viewing
$mustid = array_search("$musthave", $status);
$create = trim($db->data["_DB"]["forums"]["$rrrowid"][9]); //the forum requirement level for viewing
$reply = trim($db->data["_DB"]["forums"]["$rrrowid"][10]); //the forum requirement level for viewing

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


if (@$status["$mustid"] == "$musthave"){


if (is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){
echo ("
<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"$fwidth\" align=\"center\">
   <tr align=\"right\">
      <td width=\"100%\" colspan=\"1\">
       <a href=\"newreply.php?fid=$id&tid=$tid\"><img src=\"$skins/buttons/newpost.gif\" alt=\"New Reply\" border=\"0\" /></a>
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




table_header("$_LANG[51]: </b><u>".$db->data["_DB"]["topics"]["$rowid"][1]."</u><a name=\"0\"></a>");

if ($forumid != null && @$_SESSION["mpw"] != $forumpw){
echo ("<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" width=\"$fwidth\" align=\"center\">
   <tr bgcolor=\"$tbackground1\" align=\"center\" bgcolor=\"$tbackground1\">
      <td width=\"10%\" colspan=\"1\"><input type=\"text\" name=\"pw\" value=\"\"> <input type=\"submit\" name=\"pws\" value=\"Submit\"></td>
   </tr>
</table>");

};


if ($forumid == null or @$_SESSION["mpw"] == $forumpw){
 echo ("
<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" width=\"$fwidth\" align=\"center\" bgcolor=\"$tborder_color2\">
     <tr class=\"background\">
        <td colspan=\"1\" width=\"20%\" align=\"center\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>$_LANG[52]</b></font></td>
        <td colspan=\"1\" width=\"80%\" align=\"left\"><font size=\"$fsmall\" color=\"$fsubtitle\" face=\"$fface\"><b>$_LANG[53]</b></font></td>
     </tr>");

     $avatar = "avatars/noavatar.gif";

     $starter_string = $db->data["_DB"]["topics"]["$rowid"][2]; //username
    $starter_id = $db->query("users", "0", "$starter_string");
    $starter_array = $db->data["_DB"]["users"]["$starter_id"];
    
    $poststats = "";
          foreach ($db->data["_DB"]["postcount"] as $division){
         if ($starter_array[4] >= $division[0]){
          @$poststats = $division["1"];
         };
      }; //end division
    
    if ($starter_array["3"] == "1"){
      $mtitle = "<font color=\"$cadmin\">Admin</font>";
      } else {
      $mtitle = "<font color=\"$cmember\">Member</font>";
      }; //end if
      
      if (isset($starter_array["8"])){
        $bonus = $starter_array["8"];
        $bonus .= "<br>";
        } else {
        $bonus = "";
        };

      
    if ($starter_array["6"] != ""){
      $starter_name = "<i>".$starter_array["6"]."</i>";
    } else {
      $starter_name = $starter_array["0"];
    }; //end if
     
      $starter_user = $starter_array["0"];


     echo ("<tr height=\"50\" valign=\"top\">
        <td bgcolor=\"$tbackground1\" colspan=\"1\" width=\"20%\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><label title=\"$starter_user\">$starter_name</label><br />$mtitle<br />
        <br /><div align=\"center\"><img src=\"$starter_array[5]\" alt=\"Avatar\" width=\"60\" height=\"60\"></div><br />
        $bonus Posts: $starter_array[4]<br />$poststats<br />

        <label title=\"$starter_array[12]\"><img src=\"icon/msn.gif\" alt=\"$starter_array[12]\" /></label>
        <label title=\"$starter_array[10]\"> <img src=\"icon/aim.gif\" alt=\"$starter_array[10]\" /></label>
        <label title=\"$starter_array[11]\"> <img src=\"icon/icq.gif\" alt=\"$starter_array[11]\" /></label></font></td>
        <td bgcolor=\"$tbackground2\" colspan=\"1\" width=\"80%\" align=\"left\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">
        <font color=\"$fcfade\"><img src=\"".$db->data["_DB"]["topics"]["$rowid"][0]."\" alt=\"icon\"> $_LANG[54]: ".$db->data["_DB"]["topics"]["$rowid"][3]."</font><hr color=\"$hrcolor\" size=\"1\" width=\"100%\" />
        </font><font color=\"$fcolor\" size=\"$fmedium\" face=\"$fface\"> ".@message($db->data["_DB"]["topics"]["$rowid"][4], $badinput)."</font></td>
     </tr>
     </table>");
     table_footer();
$reply_count = "0";


if (!isset($_GET["page"])){
  $_GET["page"] = "1";
}; //end if
$display = "15";
$start = $display * $_GET["page"] - $display;
$finish = $start + $display;

     for ($posts = 2; $posts < count($db->data["_DB"]["posts"]); $posts++){
     

     $poster_string = @$db->data["_DB"]["posts"]["$posts"][2]; //username
    $poster_id = $db->query("users", "0", "$poster_string");
    $poster_array = @$db->data["_DB"]["users"]["$poster_id"];
    if ($poster_array["3"] == "1"){
      $mtitle = "<font color=\"$cadmin\">Admin</font>";
      } else {
      $mtitle = "<font color=\"$cmember\">Member</font>";
      }; //end if
    
     if ($poster_array["6"] != ""){
      $poster_name = "<i>".$poster_array["6"]."</i>";
    } else {
      $poster_name = $poster_array["0"];
    }; //end if
    
      $poster_user = $poster_array["0"];

     if (@$db->data["_DB"]["posts"]["$posts"][5] == $tid){
      $reply_count++;

if ($reply_count >= $start and $reply_count <= $finish){

      $poststats = "";
      foreach ($db->data["_DB"]["postcount"] as $division){
         if ($poster_array[4] > $division[0]){
          @$poststats = $division["1"];
         };
      }; //end division
      
            if (isset($poster_array["8"])){
        $bonus = $poster_array["8"];
        $bonus .= "<br>";
        } else {
        $bonus = "";
        };
        
        $attributes = "";
        
        if (is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){
            if ($_SESSION["user"] == $poster_user){
              $attributes = "[ <a href=\"edit.php?fid=$_GET[fid]&tid=$_GET[tid]&reply=$posts\">$_LANG[56]</a> ] - [ <a href=\"dele.php?fid=$_GET[fid]&tid=$_GET[tid]&reply=$posts\">$_LANG[57]</a> ]";

            }; //end if
        }; //end if
        
        if ($user_power == "1" or $modit == "can"){
              $attributes = "[ <a href=\"edit.php?fid=$_GET[fid]&tid=$_GET[tid]&reply=$posts\">$_LANG[56]</a> ] - [ <a href=\"dele.php?fid=$_GET[fid]&tid=$_GET[tid]&reply=$posts\">$_LANG[57]</a> ]";

        }; //end if
      
##########if ($reply_count < $end){
table_header("$_LANG[55]: $reply_count <a name=\"$reply_count\"></a>");
        echo ("<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" width=\"$fwidth\" align=\"center\" bgcolor=\"$tborder_color2\">
<tr height=\"50\" valign=\"top\">
        <td bgcolor=\"$tbackground1\" colspan=\"1\" width=\"20%\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><label title=\"$poster_user\">$poster_name</label><br />$mtitle<br />
        <br /><div align=\"center\"><img src=\"$poster_array[5]\" alt=\"Avatar\" width=\"60\" height=\"60\"></div><br />
        $bonus Posts: $poster_array[4]<br />$poststats<br />

        <label title=\"$poster_array[12]\"><img src=\"icon/msn.gif\" alt=\"$poster_array[12]\" /></label>
        <label title=\"$poster_array[10]\"> <img src=\"icon/aim.gif\" alt=\"$poster_array[10]\" /></label>
        <label title=\"$poster_array[11]\"> <img src=\"icon/icq.gif\" alt=\"$poster_array[11]\" /></label></font></td>
        </font></td>
        <td bgcolor=\"$tbackground2\" colspan=\"1\" width=\"80%\" align=\"left\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">
        <font color=\"$fcfade\">
           <table border=\"0\" width=\"100%\">
              <tr>
                 <td width=\"50%\"><img src=\"".$db->data["_DB"]["posts"]["$posts"][0]."\" alt=\"icon\"> Posted On: ".$db->data["_DB"]["posts"]["$posts"][3]."</font></font>
                 <td width=\"50%\" align=\"right\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$attributes</font>
                 </td>
              </tr>
           </table>
           <hr color=\"$hrcolor\" size=\"1\" width=\"100%\" />
         <font color=\"$fcolor\" size=\"$fmedium\" face=\"$fface\"> ".@message($db->data["_DB"]["posts"]["$posts"][4], $badinput)."</font>
        </td>
     </tr>
     </table>");
table_footer();
############}; //working but needs to disable showing from previous pages.
     }; //end if


}; //end if

     }; //end $posts


if (is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){


echo ("
<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"$fwidth\" align=\"center\">
   <tr align=\"right\">
      <td width=\"100%\" colspan=\"1\">
       <a href=\"newreply.php?fid=$id&tid=$tid\"><img src=\"$skins/buttons/newpost.gif\" alt=\"New Reply\" border=\"0\" /></a>
       <a href=\"newtopic.php?fid=$id\"><img src=\"$skins/buttons/newtopic.gif\" alt=\"New Topic\" border=\"0\" /></a>
      </td>
   </tr>
</table><br />");


  if ($user_power == "1" or in_array($_SESSION["user"], $mods)){
echo ("<div align=\"center\">
        <a href=\"delete.php?fid=$id&tid=$tid\"><img src=\"$skins/buttons/delete.gif\" alt=\"Delete Thread\" border=\"0\" /></a>
        ");
        
        if (!isset($db->data["_DB"]["topics"]["$rowid"][8])){
        $db->data["_DB"]["topics"]["$rowid"][8] = "false";
        }; //end if


        if (trim($db->data["_DB"]["topics"]["$rowid"][6]) == "true"){
          echo ("<a href=\"open.php?fid=$id&tid=$tid\"><img src=\"$skins/buttons/open_topic.gif\" alt=\"Un-Lock Thread\" border=\"0\" /></a> ");
        } else {
          echo ("<a href=\"close.php?fid=$id&tid=$tid\"><img src=\"$skins/buttons/close_topic.gif\" alt=\"Lock Thread\" border=\"0\" /></a> ");
        }; //end if

        if (@$db->data["_DB"]["topics"]["$rowid"][8] == "true"){
          echo ("<a href=\"unsticky.php?fid=$id&tid=$tid\"><img src=\"$skins/buttons/unsticky.gif\" alt=\"Un-Sticky Thread\" border=\"0\" /></a> ");
        } else {
          echo ("<a href=\"sticky.php?fid=$id&tid=$tid\"><img src=\"$skins/buttons/sticky.gif\" alt=\"Sticky Thread\" border=\"0\" /></a> ");
        }; //end if

        echo ("</div><br />");
        
  }; //end if
}; //end is_logged_in


}; //end if

 if ($forumid != null && @$_SESSION["mpw"] != $forumpw){
table_footer();
}; //end

} else {

  echo "<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">".$_LANG["FORBID"]."<br /></font></div>";

  }; //end requirement check


$p_p = $_GET["page"] - 1;
$p_n = $_GET["page"] + 1;

echo "<center>";

 if ($_GET["page"] > "1"){
  echo ("[ <a href=\"view_thread.php?fid=$id&amp;tid=$tid&amp;page=$p_p\">&lt;--</a> ] ");
}; //end if

echo "<b>".$_GET["page"]."</b>";

  echo (" [ <a href=\"view_thread.php?fid=$id&amp;tid=$tid&amp;page=$p_n\">--&gt;</a> ] ");

  echo "</center><br />";
  
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
