<?php

if (!isset($where)){
  $where = "<a href=\"index.php\">$_LANG[1]</a> > $_LANG[0]";
};

if (isset($_SESSION["forum"])){
if ($_SESSION["forum"] != $dbfile_true){
  $main = "<< <a href=\"index.php?forum=$dbfile_true\">$_LANG[2]</a> >>";
} else {
  $main = "";
  };
} else {
  $main = "";
}; //end isset


      echo ("<table border=\"0\" cellspacing=\"0\" cellpadding=\"0\" width=\"$width\" align=\"center\">
         <tr class=\"subnav1\">
             <td width=\"30%\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">");
      
               if (!is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){

               echo ("$_LANG[3] ".$db->data["_CLIENT"]["IP"]."");
               
               } else {
               
               $string = $db->query("users", "0", $_SESSION["user"]);
               $user_array = $db->data["_DB"]["users"]["$string"];
               if ($user_array["3"] == "1"){
                echo ("<a href=\"admin.php\">$_LANG[5]</a>, ");
               };

               echo ("$_LANG[3] <b>".$_SESSION["user"]."!</b>");
               
               }; //end if !is_logged_in
               
               
               echo ("</font></td>
             <td width=\"45%\" align=\"left\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$main $where</font></td>
             <td width=\"25%\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">");

             if (is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){

                echo ("<b>$_LANG[4]</b> ".$_SESSION["last"]."");

            } else {

              echo (" $_LANG[6]");

              }; //end if !is_logged_in


             echo ("</font></td>
         </tr>
      </table>");




?>
