<?php

$where = "<a href=\"index.php\">Home</a> &gt; <a href=\"view_forum.php?fid=$_GET[fid]\">View Forum</a> &gt; New Thread";


include("header.php");

$id = $_GET["fid"];
$context = null;
$ccontext = null;
$rowid = $db->query("forums", "7", $id);
     $max_length = "1050";  //maximum chars allowed per post

$musthave = trim($db->data["_DB"]["forums"]["$rowid"][9]); //the forum requirement level for viewing
$mustid = array_search("$musthave", $status);

if (@$status["$mustid"] == "$musthave"){

if (!isset($_SESSION["flood"])){

if (!is_logged_in(@$_SESSION["user"], @$_SESSION["pass"])){
     echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">In order to create topics or post replies you must be logged in!<br />Register now, <a href=\"register.php\">here</a>, its FREE! and easy.</font></div>\r\n");
} else {

echo ("<form method=\"post\" action=\"\" name=\"login\">");


echo "

<head><script>

/*
Form field Limiter script- By Dynamic Drive
For full source code and more DHTML scripts, visit http://www.dynamicdrive.com
This credit MUST stay intact for use
*/

var ns6=document.getElementById&&!document.all

function restrictinput(maxlength,e,placeholder){
if (window.event&&event.srcElement.value.length>=maxlength)
return false
else if (e.target&&e.target==eval(placeholder)&&e.target.value.length>=maxlength){
var pressedkey=/[a-zA-Z0-9\.\,\/]/ //detect alphanumeric keys
if (pressedkey.test(String.fromCharCode(e.which)))
e.stopPropagation()
}
}

function countlimit(maxlength,e,placeholder){
var theform=eval(placeholder)
var lengthleft=maxlength-theform.value.length
var placeholderobj=document.all? document.all[placeholder] : document.getElementById(placeholder)
if (window.event||e.target&&e.target==eval(placeholder)){
if (lengthleft<0)
theform.value=theform.value.substring(0,maxlength)
placeholderobj.innerHTML=lengthleft
}
}


function displaylimit(theform,thelimit){
var limit_text='<b><span id=\"'+theform.toString()+'\">'+thelimit+'</span></b>'
if (document.all||ns6)
document.write(limit_text)
if (document.all){
eval(theform).onkeypress=function(){ return restrictinput(thelimit,event,theform)}
eval(theform).onkeyup=function(){ countlimit(thelimit,event,theform)}
}
else if (ns6){
document.body.addEventListener('keypress', function(event) { restrictinput(thelimit,event,theform) }, true);
document.body.addEventListener('keyup', function(event) { countlimit(thelimit,event,theform) }, true);
}
}

</script></head>";


if (isset($_POST["a"]) and $_POST["subj"] != ""){
  if ($_POST["a"]){
  
  $_SESSION["flood"] = $stamp_p;

     //add a post count onto the users profile
     $uid = $db->query("users", "0", $_SESSION["user"]);
     $array = $db->data["_DB"]["users"]["$uid"];
     $posts = $array["4"]++;
     $db->editRow("users", "$uid", array("$array[0]", "$array[1]", "$array[2]", "$array[3]", "$array[4]", "$array[5]", "$array[6]", "$array[7]"));

     //add the nescesary onto the forums db
     $rowid = $db->query("forums", "7", $id);
     $db->data["_DB"]["forums"]["$rowid"][2]++; //add topic
     $db->data["_DB"]["forums"]["$rowid"][3]++; //add post
    $db->data["_DB"]["key"]["2"]["0"]++;


     //add this into the database
     $pkey = $db->data["_DB"]["key"]["2"]["0"];
     
$string = $_POST["msg"];
if (strlen($string) > "$max_length"){
  $string = substr("$string", "0", "$max_length");
}; //end if

      echo ("<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">$_LANG[81]<br /><br /></font></div>\r\n");
     $db->addRow("topics", array($_POST["icon"], $_POST["subj"], $_SESSION["user"], date("$pfdate"), "$string", "$id", "false", "$pkey"));
     $db->reBuild();
     echo ("<meta http-equiv=\"refresh\" content=\"2;url=view_forum.php?fid=$id\">");


  }; //end $a
}; //end isset $a

if (isset($_POST["view"])){
     $db->createTable("VIEW");
     $db->addRow("VIEW", array($_POST["icon"], $_POST["subj"], $_SESSION["user"], date("$date"), $_POST["msg"]));
     table_header("View Post");
     $context2 = $db->data["_DB"]["VIEW"]["1"][1];
     $context3 = $db->data["_DB"]["VIEW"]["1"][4];
     $ccontext = stripslashes($_POST["subj"]);
     $context = stripslashes($_POST["msg"]);
echo ("<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" width=\"$fwidth\" align=\"center\">
   <tr bgcolor=\"$tbackground1\">
      <td width=\"30%\" colspan=\"1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b><u>$context2</u></b><br />$context3</font></td>
   </tr>
</table>");

table_footer();
}; //end if

table_header("$_LANG[75]");

$forumid = $db->query("password", "0", $_GET["fid"]); //query the id of forum in password table.
if ($forumid != null){
$forumpw = $db->data["_DB"]["password"]["$forumid"][1];  //grab the password for the forum.
};

echo ("<form method=\"post\" action=\"\">");
if (isset($_POST["pw"])){
  $_SESSION["mpw"] = $_POST["pw"];
}; //end isset

if ($forumid != null && @$_SESSION["mpw"] != $forumpw){
echo ("<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" width=\"$fwidth\" align=\"center\">
   <tr bgcolor=\"#FFFFFF\" align=\"center\" bgcolor=\"$tbackground1\">
      <td width=\"10%\" colspan=\"1\"><input type=\"text\" name=\"pw\" value=\"\"> <input type=\"submit\" name=\"pws\" value=\"Submit\"></td>
   </tr>
</table>");

};



$align = "left";


if ($forumid == null or @$_SESSION["mpw"] == $forumpw){
echo ("
<table border=\"0\" cellspacing=\"1\" cellpadding=\"3\" bgcolor=\"$tborder_color2\" width=\"$fwidth\" align=\"center\">
   <tr bgcolor=\"$tbackground2\" align=\"$align\">
      <td width=\"30%\" colspan=\"1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>$_LANG[76]:</b></font></td>
      <td width=\"70%\" colspan=\"1\"><input type=\"text\" name=\"subj\" size=\"50\" value=\"$ccontext\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">&nbsp;<script>displaylimit(\"document.login.subj\",40);</script> / 40</font>
 </tr>
   <tr bgcolor=\"$tbackground1\" align=\"$align\">
      <td width=\"30%\" colspan=\"1\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>$_LANG[77]:</b></font></td>
      <td width=\"70%\" colspan=\"1\">

         <table width=\"100%\" align=\"$align\" border=\"0\">
            <tr>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon1.gif\" checked> <img src=\"icon/icon1.gif\" alt=\"icon1.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon2.gif\"> <img src=\"icon/icon2.gif\" alt=\"icon2.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon3.gif\"> <img src=\"icon/icon3.gif\" alt=\"icon3.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon4.gif\"> <img src=\"icon/icon4.gif\" alt=\"icon4.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon5.gif\"> <img src=\"icon/icon5.gif\" alt=\"icon5.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon6.gif\"> <img src=\"icon/icon6.gif\" alt=\"icon6.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon7.gif\"> <img src=\"icon/icon7.gif\" alt=\"icon7.gif\"></td>
            </tr>
            <tr>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon8.gif\"> <img src=\"icon/icon8.gif\" alt=\"icon8.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon9.gif\"> <img src=\"icon/icon9.gif\" alt=\"icon9.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon10.gif\"> <img src=\"icon/icon10.gif\" alt=\"icon10.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon11.gif\"> <img src=\"icon/icon11.gif\" alt=\"icon11.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon12.gif\"> <img src=\"icon/icon12.gif\" alt=\"icon12.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon13.gif\"> <img src=\"icon/icon13.gif\" alt=\"icon13.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon14.gif\"> <img src=\"icon/icon14.gif\" alt=\"icon14.gif\"></td>
           </tr>
           <tr>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon31.gif\"> <img src=\"icon/icon31.gif\" alt=\"icon31.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon16.gif\"> <img src=\"icon/icon16.gif\" alt=\"icon16.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon17.gif\"> <img src=\"icon/icon17.gif\" alt=\"icon17.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon18.gif\"> <img src=\"icon/icon18.gif\" alt=\"icon18.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon19.gif\"> <img src=\"icon/icon19.gif\" alt=\"icon19.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon20.gif\"> <img src=\"icon/icon20.gif\" alt=\"icon20.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon21.gif\"> <img src=\"icon/icon21.gif\" alt=\"icon21.gif\"></td>
           </tr>
           <tr>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon22.gif\"> <img src=\"icon/icon22.gif\" alt=\"icon22.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon23.gif\"> <img src=\"icon/icon23.gif\" alt=\"icon23.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon24.gif\"> <img src=\"icon/icon24.gif\" alt=\"icon24.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon25.gif\"> <img src=\"icon/icon25.gif\" alt=\"icon25.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon26.gif\"> <img src=\"icon/icon26.gif\" alt=\"icon26.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon27.gif\"> <img src=\"icon/icon27.gif\" alt=\"icon27.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon28.gif\"> <img src=\"icon/icon28.gif\" alt=\"icon28.gif\"></td>
          </tr>
          <tr>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon29.gif\"> <img src=\"icon/icon29.gif\" alt=\"icon29.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon30.gif\"> <img src=\"icon/icon30.gif\" alt=\"icon30.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon32.gif\"> <img src=\"icon/icon32.gif\" alt=\"icon32.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon33.gif\"> <img src=\"icon/icon33.gif\" alt=\"icon33.gif\"></td>
               <td width=\"14%\" colspan=\"1\"><input type=\"radio\" name=\"icon\" value=\"icon/icon34.gif\"> <img src=\"icon/icon34.gif\" alt=\"icon34.gif\"></td>
               <td width=\"14%\" colspan=\"1\">&nbsp;</td>
               <td width=\"14%\" colspan=\"1\">&nbsp;</td>
          </td>
         </table>

      </td>
   </tr>
   <tr bgcolor=\"$tbackground2\" align=\"$align\">
      <td width=\"30%\" colspan=\"1\" valign=\"top\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><b>$_LANG[78]:</b><br />$max_length Chars Allowd</font></td>
      <td width=\"70%\" colspan=\"1\"><textarea name=\"msg\" rows=\"8\" cols=\"60\">$context</textarea><br /><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\"><script>displaylimit(\"document.login.msg\",$max_length);</script> / 1050</font></td>
   </tr>
   <tr bgcolor=\"$tbackground1\" align=\"$align\">
      <td width=\"100%\" colspan=\"2\" valign=\"top\"><input type=\"submit\" name=\"a\" value=\"$_LANG[79]\"> <input type=\"submit\" name=\"view\" value=\"$_LANG[80]\"></td>
   </tr>
</table>");
}; //end

table_footer();

echo ("</form>");

}; //end !is_logged_in

} else {

  echo "<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">".$_LANG["FLOOD"]."<br /></font></div>";
  }; //end flood controll.

} else {

  echo "<div align=\"center\"><font size=\"$fsmall\" color=\"$fcolor\" face=\"$fface\">".$_LANG["FORBID"]."<br /></font></div>";

  }; //end requirement check

include("footer.php");

?>
