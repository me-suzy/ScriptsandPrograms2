<?php
if (file_exists("install.php")) {
   echo "<font face=\"FF0000\">Please delete install.php before you can continue.</font>";
   die;
}


include 'config.php';
session_start();
session_register("userlogged");
session_register("name");
$tmp = $_GET['action'];
if($tmp == "logout"){
$_SESSION['userlogged'] = "";
header ("Location: admin.php");
}

if($_POST['name'] == ""){
        $loggedin = "false";
        }else {
        $tmpname = $_POST['name'];
        $pass = md5($_POST['pass']);
        $sql = "SELECT * FROM $newsadmin WHERE user = '$_POST[name]' AND pass = '$pass'";
$result = mysql_query($sql)
        or die ("Couldn't execute query.");
$num = mysql_num_rows($result);
if($num >= 1){
$number = $_POST['key'];
if(md5($number) == $_SESSION['image_random_value']) {
$_SESSION['userlogged'] = "true";

$_SESSION['name'] = $tmpname;
$sql = "SELECT user FROM $newsadmin WHERE user = '$_SESSION[name]'";
$query = mysql_query($sql);
$result = mysql_result($query,0);
$_SESSION['name'] = $result;

}
}
}


if($_SESSION['userlogged'] == "true"){
        $sqlcheckaccount = "SELECT user FROM $newsadmin
        WHERE user = '$_SESSION[name]'";

        $resultcheckaccount = mysql_query($sqlcheckaccount)
        or die ("Couldn't execute query.");
        $numcheckaccount = mysql_num_rows($resultcheckaccount);
        if($numcheckaccount == 0){
                                  echo "<div class=error>Error, Either your account has been deleted or an unknown error has occurred.<div>";
                                  echo "<div class=success><a href=\"?action=logout\">Relogin.</a></div>";
                                  die;
                                  }


   $_SESSION['userlogged'] = "true";

}

?>


<!DOCTYPE HTML PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN">
<html>
<head>
<meta http-equiv="Content-Language" content="en-gb">
<style type="text/css">
<!--
select , option , textarea , input {
border-right : 1px solid #808080;
border-top : 1px solid #808080;
border-bottom : 1px solid #808080;
border-left : 1px solid #808080;
color : #000000;
font-size : 11px;
font-family : Verdana, Arial, Helvetica, sans-serif;
background-color : #ffffff;
}
a:active , a:visited , a:link {
color : #666666;
text-decoration : none;
font-family : Verdana, Arial, Helvetica, sans-serif;
font-size : 8pt;
}
a:hover {
color : #000066;
text-decoration : none;
font-family : Verdana, Arial, Helvetica, sans-serif;
font-size : 8pt;
}
a.nav:active , a.nav:visited , a.nav:link {
color : #000000;
font-size : 10px;
font-weight : bold;
font-family : verdana, Arial, Helvetica, sans-serif;
text-decoration : none;
}
a.nav:hover {
font-size : 10px;
font-weight : bold;
color : black;
font-family : verdana, Arial, Helvetica, sans-serif;
text-decoration : underline;
}
.header {
font-size : 14px;
font-weight : bold;
color : #808080;
font-family : verdana, Arial, Helvetica, sans-serif;
text-decoration : none;
}
.subheader {
font-size : 12px;
font-weight : bold;
color : #505050;
font-family : verdana, Arial, Helvetica, sans-serif;
text-decoration : none;
}
.error {
font-size : 11px;
color : #FF0000;
font-family : verdana, Arial, Helvetica, sans-serif;
text-decoration : none;
}
.success {
font-size : 11px;
color : #008800;
font-family : verdana, Arial, Helvetica, sans-serif;
text-decoration : none;
}
.panel {
-moz-border-radius:6px;
border: 1px dotted silver; background-color: #F7F7F7;
}
.bborder {
background-color : #ffffff;
border : 1px solid #a7a6b4;
}
BODY , TD , TR {
text-decoration : none;
font-family : Verdana, Arial, Helvetica, sans-serif;
font-size : 8pt;
cursor : default;

}
-->
</style>
<script LANGUAGE="JavaScript" TYPE="text/javascript">
  function insertsmiley(text, element_id) {
   var item = null;
   if (document.getElementById) {
     item = document.getElementById(element_id);
   } else if (document.all) {
     item = document.all[element_id];
   } else if (document.layers){
     item = document.layers[element_id];
    }
   if (item) {
     item.focus();
     item.value = item.value + " " + text;
     item.focus();
   }
}
</script>
    <script type="text/javascript" src="CollapsibleRows.js"></script>
<title>N-13 News</title>
<meta http-equiv="Content-Type" content="text/html; charset=windows-1252">
</head>
<body bgcolor="#FFFFFF">
<div align="center">

<table id="Table_01" width="730" border="0" cellpadding="0" cellspacing="0">
        <tr>
                <td style="text-decoration: none; font-family: Verdana, Arial, Helvetica, sans-serif; font-size: 8pt; cursor: default" height="1">
                        <img src="images/index_01.gif" width="730" height="33" alt=""></td>
        </tr>
        <tr>

                <td style="background-image:url(images/index_02.gif)" height="40">
                        <?php

                                if($_SESSION['userlogged'] == "true"){
                        echo "&nbsp;&nbsp;&nbsp;&nbsp; <a class=\"nav\" href=\"?\">Home</a>&nbsp;&nbsp; |&nbsp;\n";
                        echo "&nbsp; <a class=\"nav\" href=\"?action=addnews\">Add News</a>&nbsp;&nbsp; |&nbsp;\n";
                        echo "&nbsp; <a class=\"nav\" href=\"?action=editnews\">Edit News</a>&nbsp;&nbsp; |&nbsp;\n";
                        echo "&nbsp; <a class=\"nav\" href=\"?action=options\">Options</a>&nbsp;&nbsp; |&nbsp;\n";
                        echo "&nbsp; <a class=\"nav\" target=\"_NEW\" href=\"Readme.html\">Help</a>&nbsp;&nbsp; |&nbsp;\n";
                        echo "&nbsp; <a class=\"nav\" href=\"?action=logout\">Logout</a>\n";

                        }else{
                                echo "&nbsp;&nbsp;&nbsp;&nbsp;Administration login.";
                        }
                        ?>
                        </td>
        </tr>

</table>
<table border="0" cellpadding="20" cellspacing="0" width="730" style="background-image:url(images/index_03.gif)">
  <tr>
    <td width="100%">

    <?php


function limit_text($text,$limit)
{
  if( strlen($text)>$limit )
  {
    $text = substr( $text,0,$limit );
    $text = substr( $text,0,-(strlen(strrchr($text,' '))) );
  }

  return $text;
}

if($_SESSION['userlogged'] == "true"){
    $action = $_GET['action'];
    if($action == ""){
            $sql = "SELECT * FROM $newstable";
            $query = mysql_query($sql);
            $totalnews = mysql_num_rows($query);
            $sql = "SELECT * FROM `$newscomments`";
            $query = mysql_query($sql);
            $totalcomments = mysql_num_rows($query);
            $sql = "SELECT * FROM `$newsadmin`";
            $query = mysql_query($sql);
            $totalusers = mysql_num_rows($query);
            $sql = "SELECT * FROM `$newssmilies`";
            $query = mysql_query($sql);
            $totalsmilies = mysql_num_rows($query);
            $sql = "SELECT * FROM `$newsfilter`";
            $query = mysql_query($sql);
            $totalfilters = mysql_num_rows($query);
            echo "<div class=header>Welcome $_SESSION[name]</div>";
            echo "<br><div align=\"right\"><table class=panel width=\"84%\"><tr><td>Some news stats below.</td></tr></table></div>";
            echo "<br><div align=\"right\">";
            echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"84%\">\n";
            echo "  <tr>\n";
            echo "    <td width=\"20%\">\n";
            echo "    Total News:</td>\n";
            echo "    <td width=\"28%\">\n";
            echo "    <div class=success>$totalnews</div></td>\n";
            echo "    <td width=\"27%\">\n";
            echo "    Connecting to database:</td>\n";
            echo "    <td width=\"25%\">\n";
            echo "    <div class=success>OK</div></td>\n";
            echo "  </tr>\n";
            echo "  <tr>\n";
            echo "    <td width=\"20%\">\n";
            echo "    Total Comments:</td>\n";
            echo "    <td width=\"28%\">\n";
            echo "    <div class=success>$totalcomments</div></td>\n";
            echo "    <td width=\"27%\">\n";
            echo "    Optimizing all tables:</td>\n";
            echo "    <td width=\"25%\">\n";
            function getmicrotime(){
            list($usec, $sec) = explode(" ",microtime());
            return ((float)$usec + (float)$sec);
            }

            $time_start = getmicrotime();

            $query = "OPTIMIZE TABLE `$newstable`";
            mysql_query($query);
            $query = "OPTIMIZE TABLE `$newsadmin`";
            mysql_query($query);
            $query = "OPTIMIZE TABLE `$newscomments`";
            mysql_query($query);
            $query = "OPTIMIZE TABLE `$newsoptions`";
            mysql_query($query);
            $query = "OPTIMIZE TABLE `$newssmilies`";
            mysql_query($query);
            $query = "OPTIMIZE TABLE `$newsfilter`";
            mysql_query($query);
            $time_end = getmicrotime();
            $time = $time_end - $time_start;
            $time = round($time,5);

                    echo "<div class=success>OK</div>";

            echo "$tablestatus</td>\n";
            echo "  </tr>\n";
            echo "  <tr>\n";
            echo "    <td width=\"20%\">Total Users:</td>\n";
            echo "    <td width=\"28%\"><div class=success>$totalusers</div></td>\n";
            echo "    <td width=\"27%\">Query time:</td>\n";

            echo "    <td width=\"25%\"><div class=success>$time seconds</div></td>\n";
            echo "  </tr>\n";
            echo "<tr>\n";
            echo "<td width=\"20%\">Total Smilies:</td>\n";
            echo "<td width=\"28%\"><div class=success>$totalsmilies</div></td>\n";
            echo "</tr>";
            echo "<tr>";
            echo "<td width=\"20%\">Total Filters:</td>\n";
            echo "<td width=\"28%\"><div class=success>$totalfilters</div></td>\n";
            echo "</tr>";
            echo "</table>\n";
            }

    if($action == "addnews"){
            echo "<div class=header>Add News</div><br><br>";

            if($_POST['title'] == ""){

            $sql = "SELECT avatar FROM $newsadmin WHERE user = '$_SESSION[name]'";
            $result = mysql_query($sql);
            $avatar = mysql_result($result,0);

            echo "<form method=\"POST\" action=\"admin.php?action=addnews\">\n";
            echo "<table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" width=\"100%\">\n";
            echo "  <tr>\n";
            echo "    <td width=\"25%\" valign=\"top\">Title: </td>\n";
            echo "    <td width=\"83%\" colspan=\"2\">\n";
            echo "  <input type=\"text\" name=\"title\" size=\"54\" value=\"$_POST[title]\"></td>\n";
            echo "  </tr>\n";
            echo "<tr><td width=\"25%\" valign=\"top\">Avatar Url: </td>\n";
            echo "<td width=\"83%\" colspan=\"2\">\n";
            echo "  <input type=\"text\" name=\"avatar2\" size=\"54\" value=\"$avatar\"></td>\n";
            echo "</tr>\n";
            echo "  <tr>\n";
            echo "    <td width=\"25%\" valign=\"top\">Story: </td>\n";
            echo "    <td width=\"42%\" valign=\"top\">\n";
            echo "  <textarea rows=\"13\" name=\"story\" cols=\"58\" id=\"story\">$_POST[story]</textarea><p>\n";
            echo "  <input type=\"submit\" value=\"Submit\" name=\"B1\"></td>\n";
            echo "    <td width=\"130\" valign=\"top\">\n";

            echo "\n";

            $sql = "SELECT * FROM $newssmilies";
            $query = mysql_query($sql);
            while($row = mysql_fetch_array( $query )) {
            echo "<a href=\"javascript:insertsmiley('$row[keycode]','story')\"><img src=\"$row[path]\" border=\"0\" alt=\"$row[keycode]\"></a>&nbsp;";
            }
            echo "</td>\n";
            echo "  </tr>\n";
            echo "</table>\n";
            echo "</form>\n";
            } else {


                    $title = $_POST['title'];
                    $avatar2 = $_POST['avatar2'];
                    $story = $_POST['story'];
                    $story = str_replace("\r\n", "\n", $story);
                    $story = str_replace("<br />\n", "\n", $story);
                    $story = nl2br($story);


$sql2 = "SELECT * FROM `$newstable`";
$result2 = mysql_query($sql2);
$id = mysql_num_rows($result2);
$sql4 = "SELECT email FROM $newsadmin WHERE user = '$_SESSION[name]'";
$result4 = mysql_query($sql4);
$email = mysql_result($result4,0);
$id = $id + 1;

                    $sql = "SELECT newstime FROM `$newsoptions` WHERE 1";
                    $query = mysql_query($sql);
                    $date2 = mysql_result($query,0);
                    $date = gmdate($date2);
                    $sql = "INSERT INTO `$newstable` (title,story,author,date,email,id,avatar)
                            VALUES ('$title','$story','$_SESSION[name]','$date','$email','$id','$avatar2')" or die ("Coudn't execute query.");
                    mysql_query($sql) or die ("Couldn't execute query.");

                            echo "<div align=\"right\"><table width=\"78%\"><tr><td><div class=success>News added.</div></td></tr></table></div>";
                   }
            }
    if($action == "editnews"){
            echo "<div class=header>Edit News</div><br><br>";
            if($_POST['R1'] == ""){
            $sql =  "SELECT * FROM $newstable";
            $result = mysql_query($sql)
                    or die ("Couldn't execute query.");


                    if($_GET['delete'] == "true"){
                            if($_POST['B1'] == ""){
                            $sql = "SELECT title FROM $newstable WHERE id=$_GET[id]";
                            $result = mysql_query($sql);
                            $title = mysql_result($result,0);
                                        echo "<form method=\"POST\" action=\"?action=editnews&delete=true&id=$_GET[id]&status=done\">\n";
                                        echo "<div align=\"right\">\n";
                                        echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"84%\">\n";
                                        echo "    <tr>\n";
                                        echo "      <td width=\"100%\">\n";
                                        echo "      <div align=\"center\" class=error>Are you sure you want to delete this news\n";
                                        echo "        story?</div>\n";
                                        echo "          <br>\n";
                                        echo "          <div align=\"center\">$title</div><br>\n";
                                        echo "      <p align=\"center\"></td>\n";
                                        echo "    </tr>\n";
                                        echo "    <tr>\n";
                                        echo "      <td width=\"100%\"><div align=\"center\"><input type=\"submit\" value=\"Yes\" name=\"B1\">&nbsp;<input type=\"submit\" value=\"No\" name=\"B1\"></div></td>\n";
                                        echo "    </tr>\n";
                                        echo "</table>\n";
                                        echo "</div>\n";
                                        echo "</form>\n";
                              }elseif($_POST['B1'] == "Yes"){
                                      $sql2 = "DELETE FROM `$newstable` WHERE id='$_GET[id]'";
                                      $query = mysql_query($sql2) or die ("couldn't execute query.");
                                      $sql2 = "DELETE FROM `$newscomments` WHERE pid='$_GET[id]'";
                                      $query = mysql_query($sql2) or die ("Couldn't execute query.");

                                      $sql = "SELECT * FROM `$newstable`";
                                      $query = mysql_query($sql);
                                      $numrows = mysql_num_rows($query);
                                      $i = $_GET['id'];
                                      $s = ($i + 1);
                                      while($i <= $numrows){
                                            $sql = "UPDATE `$newstable` SET id = '$i' WHERE id = '$s'";
                                            $query = mysql_query($sql);
                                            $sql = "UPDATe `$newscomments` SET pid = '$i' WHERE pid = '$s'";
                                            $query = mysql_query($sql);
                                              $i++;
                                              $s++;
                                      }



                                      echo "<div align=\"right\"><table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" width=\"84%\"><tr><td><div class=success>News story deleted.</div></td></td></table></div>";
                                      }

                            }
            $sql2 = "SELECT god FROM `$newsadmin` WHERE user='$_SESSION[name]'";
            $result2 = mysql_query($sql2);
            $permission = mysql_result($result2,0);
            if($permission == "1"){
            $sql = "SELECT * FROM $newstable";
            $query = mysql_query($sql);
            $totalnews = mysql_num_rows($query);
            }else{
            $sql = "SELECT * FROM $newstable WHERE author = '$_SESSION[name]'";
            $query = mysql_query($sql);
            $totalnews = mysql_num_rows($query);
            }
                    echo "<div align=\"right\"><table class=panel width=\"84%\"><tr><td>Showing a total of <b>$totalnews</b> news stories.</td></tr></table><br></div><div align=\"right\"><form method=\"POST\" action=\"?action=editnews\">\n";
                    echo "<table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" width=\"84%\">\n";
echo "<tr><td width=\"40%\">Title</td><td width=\"82\">Comments</td><td width=\"141\">Date</td><td width=\"56\">Author</td><td width=\"112\"><div align=\"center\">Delete</div></td><td width=\"56\"><div align=\"right\">Select</div></td></tr>\n";


                    $sql =  "SELECT * FROM $newstable ORDER BY 0+ID DESC";
                    $result = mysql_query($sql)
                            or die ("Couldn't execute query.");
                    $tmpcolor = "1";
                    $tmpcolor2 = "1";
                    $tmpcolor3 = "1";
                    $tmpcolor4 = "1";


                                        while($row = mysql_fetch_array( $result )) {

                                        echo "  <tr>\n";
                                        $sql = "SELECT * FROM `$newscomments` WHERE pid = '$row[id]'";
                                        $query = mysql_query($sql);
                                        $numcomments = mysql_num_rows($query);

                                        if(strlen($row[title]) >= 25){
                                        $title = limit_text($row[title],25);
                                        $title = strip_tags($title);
                                        $blah = $title . ".....";
                                        }else{
                                        $blah = $row[title];
                                        }

                                        if($tmpcolor == "1"){
                                                if($permission == "1"){
                                                echo "    <td bgcolor=\"#F7F7F7\">$blah</td><td bgcolor=\"#F7F7F7\"><div align=\"center\"><a href=\"?action=editcomments&pid=$row[id]\">$numcomments</a></div></td><td bgcolor=\"#F7F7F7\">$row[date]<td bgcolor=\"#F7F7F7\">$row[author]</td><td bgcolor=\"#F7F7F7\"><a href=\"?action=editnews&delete=true&id=$row[id]\"><div class=error>DELETE</div></a></td><td bgcolor=\"#F7F7F7\"><div align=\"right\"><input type=\"radio\" value=\"$row[id]\" name=\"R1\"></div>\n";
                                                }else{
                                                if($row['author'] == $_SESSION['name']){
                                                echo "    <td bgcolor=\"#F7F7F7\">$blah</td><td bgcolor=\"#F7F7F7\"><div align=\"center\"><a href=\"?action=editcomments&pid=$row[id]\">$numcomments</a></div></td><td bgcolor=\"#F7F7F7\">$row[date]<td bgcolor=\"#F7F7F7\">$row[author]</td><td bgcolor=\"#F7F7F7\"><a href=\"?action=editnews&delete=true&id=$row[id]\"><div class=error>DELETE</div></a></td><td bgcolor=\"#F7F7F7\"><div align=\"right\"><input type=\"radio\" value=\"$row[id]\" name=\"R1\"></div>\n";
                                                }
                                                }
                                        $tmpcolor = "2";
                                        } else {
                                                if($permission == "1"){
                                                echo "    <td bgcolor=\"#FFFFFF\">$blah</td><td bgcolor=\"#FFFFFF\"><div align=\"center\"><a href=\"?action=editcomments&pid=$row[id]\">$numcomments</a></div></td><td width=\"30%\" bgcolor=\"#FFFFFF\">$row[date]<td bgcolor=\"#FFFFFF\">$row[author]</td><td bgcolor=\"#FFFFFF\"><a href=\"?action=editnews&delete=true&id=$row[id]\"><div class=error>DELETE</div></a></td><td bgcolor=\"#FFFFFF\"><div align=\"right\"><input type=\"radio\" value=\"$row[id]\" name=\"R1\"></div>\n";
                                                }else{
                                                if($row[author] == $_SESSION[name]){
                                                echo "    <td bgcolor=\"#FFFFFF\">$blah</td><td bgcolor=\"#FFFFFF\"><div align=\"center\"><a href=\"?action=editcomments&pid=$row[id]\">$numcomments</a></div></td><td width=\"30%\" bgcolor=\"#FFFFFF\">$row[date]<td bgcolor=\"#FFFFFF\">$row[author]</td><td bgcolor=\"#FFFFFF\"><a href=\"?action=editnews&delete=true&id=$row[id]\"><div class=error>DELETE</div></a></td><td bgcolor=\"#FFFFFF\"><div align=\"right\"><input type=\"radio\" value=\"$row[id]\" name=\"R1\"></div>\n";
                                                }
                                                }
                                        $tmpcolor = "1";
                                        }



                                        echo "</td>\n";
                                        echo "  </tr>\n";
                                        }

                    echo "  <tr>\n";
                    echo "    <td>\n";
                    echo "  <p>\n";
                    echo "</td><td></td><td></td><td></td><td></td><td><div align=\"right\"><input type=\"submit\" value=\"Edit\" name=\"B1\"></div></td><td width=\"180\"></td>\n";
                    echo "  </tr>\n";
                    echo "</table>\n";
                    echo "</form></div>\n";
                    } else {
                            if($_POST['title'] == ""){
                      $sql = "SELECT story FROM $newstable WHERE id= '$_POST[R1]'";
                      $result = mysql_query($sql);
                      $story = mysql_result($result,0);
                      $sql2 = "SELECT title FROM $newstable WHERE id= '$_POST[R1]'";
                      $result2 = mysql_query($sql2);
                      $sql3 = "SELECT avatar FROM $newstable WHERE id= '$_POST[R1]'";
                      $result3 = mysql_query($sql3);
                      $title = mysql_result($result2,0);
                      $avatar = mysql_result($result3,0);
                      $story = str_replace("\r\n", "\n", $story);
                      $story = str_replace("<br />\n", "\n", $story);
                      echo "<form method=\"POST\" action=\"?action=editnews\">\n";
                      echo "<table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" width=\"100%\">\n";
                      echo "  <tr>\n";
                      echo "    <td width=\"25%\">Title: </td>\n";
                      echo "    <td width=\"83%\" colspan=\"2\">\n";
                      echo "  <input type=\"text\" name=\"title\" size=\"54\" value=\"$title\"></td>\n";
                      echo "  </tr>\n";
                      echo "<tr><td width=\"25%\" valign=\"top\">Avatar Url: </td>\n";
                      echo "<td width=\"83%\" colspan=\"2\">\n";
                      echo "\n";
                      echo "<input type=\"hidden\" value=\"$_POST[R1]\" name=\"R1\" size=\"20\"><input type=\"text\" name=\"avatar2\" size=\"54\" value=\"$avatar\"></td>\n";
                      echo "</tr>\n";
                      echo "  <tr>\n";
                      echo "    <td width=\"25%\" valign=\"top\">Story: </td>\n";
                      echo "    <td width=\"42%\" valign=\"top\">\n";
                      function br2nl($str) {
                      return preg_replace('=<br */?>=i', "\n", $str);
                      }
                      echo "\n";
                      echo "<textarea rows=\"13\" name=\"story\" cols=\"58\" id=\"story\">$story</textarea> </td>\n";
                      echo "    <td width=\"130\" valign=\"top\">\n";
            $sql = "SELECT * FROM $newssmilies";
            $query = mysql_query($sql);
            while($row = mysql_fetch_array( $query )) {
            echo "<a href=\"javascript:insertsmiley('$row[keycode]','story')\"><img src=\"$row[path]\" border=\"0\" alt=\"$row[keycode]\"></a>&nbsp;";
            }
                      echo "  </td>\n";
                      echo "  </tr>\n";
                      echo "  <tr>\n";
                      echo "    <td width=\"17%\" valign=\"top\">&nbsp;</td>\n";
                      echo "    <td width=\"83%\" colspan=\"2\">\n";
                      echo "\n";
                      echo "                            <input type=\"submit\" value=\"Submit\" name=\"B1\" size=\"20\"></td>\n";
                      echo "  </tr>\n";
                      echo "</table>\n";
                      echo "</form>\n";
                      }else{
                       $query = "UPDATE $newstable SET title = '$_POST[title]' WHERE id = '$_POST[R1]' ";
                       $query2 = "UPDATE $newstable SET avatar = '$_POST[avatar2]' WHERE id = '$_POST[R1]' ";
                    $story = $_POST['story'];
                    $story = str_replace("\r\n", "\n", $story);
                    $story = str_replace("<br />\n", "\n", $story);
                    $story = nl2br($story);
                       $query3 = "UPDATE $newstable SET story = '$story' WHERE id = '$_POST[R1]' ";
                       $result = mysql_query($query,$connection) or die ("Coundn't execute query2.");
                       $result2 = mysql_query($query2,$connection) or die ("Coundn't execute query3.");
                       $result3 = mysql_query($query3,$connection) or die ("Coundn't execute query4.");
                       echo "<div align=\"right\"><table width=\"78%\"><tr><td><div class=success>News updated.</div></td></tr></table></div>";

                       }
            }
            }

if($action == "editcomments"){
$sql = "SELECT * FROM `$newscomments` WHERE pid = '$_GET[pid]'";
$result = mysql_query($sql);
$numcomments = mysql_num_rows($result);
echo "<div class=header>Edit Comments</div><br><br>";
       if($_POST['B1'] == ""){

              if($_GET['delete'] == "true"){
                        if($_POST['B2'] == ""){
                                        $sql = "SELECT message FROM `$newscomments` WHERE pid='$_GET[pid]' AND id='$_GET[id]'";
                                        $query = mysql_query($sql);
                                        $message = mysql_result($query,0);
                                        echo "<form method=\"POST\" action=\"?action=editcomments&delete=true&pid=$_GET[pid]&id=$_GET[id]&status=done\">\n";
                                        echo "<div align=\"right\">\n";
                                        echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"84%\">\n";
                                        echo "    <tr>\n";
                                        echo "      <td width=\"100%\">\n";
                                        echo "      <div align=\"center\" class=error>Are you sure you want to delete this comment\n";
                                        echo "        story?</div>\n";
                                        echo "          <br>\n";
                                        echo "          <div align=\"center\">$message</div><br>\n";
                                        echo "      <p align=\"center\"></td>\n";
                                        echo "    </tr>\n";
                                        echo "    <tr>\n";
                                        echo "      <td width=\"100%\"><div align=\"center\"><input type=\"submit\" value=\"Yes\" name=\"B2\">&nbsp;<input type=\"submit\" value=\"No\" name=\"B2\"></div></td>\n";
                                        echo "    </tr>\n";
                                        echo "</table>\n";
                                        echo "</div>\n";
                                        echo "</form>\n";
                                               }elseif($_POST['B2'] == "Yes"){
                                      $pid = $_GET['pid'];
                                      $id = $_GET['id'];
                                      $sql = "DELETE FROM `$newscomments` WHERE pid = '$pid' AND id = '$id'";
                                      $query = mysql_query($sql);

                                      $sql2 = "SELECT * FROM `$newscomments` WHERE pid = '$_GET[pid]'";
                                      $query2 = mysql_query($sql2);
                                      $numrows = mysql_num_rows($query2);
                                      $i = $_GET['id'];
                                      $s = ($i + 1);
                                      while($i <= $numrows){
                                            $sql = "UPDATE `$newscomments` SET id = '$i' WHERE pid = '$_GET[pid]' AND id = '$s'";
                                            $query = mysql_query($sql);
                                              $i++;
                                              $s++;
                                      }
                                      echo "<SCRIPT LANGUAGE=\"JavaScript\">\n";
                                      echo "window.location=\"?action=editcomments&pid=$_GET[pid]\";\n";
                                      echo "</script>\n";
                                      }



              }

              echo "<div align=\"right\"><table class=panel width=\"84%\"><tr><td>Showing a total of <b>$numcomments</b> comments.</td></tr></table><br></div>";
              echo "<form method=\"POST\" action=\"?action=editcomments&pid=$_GET[pid]\">";
              echo "<div align=\"right\"><table width=\"84%\" border=\"0\" cellpadding=\"1\" cellspacing=\"0\" ><tr><td width=\"50%\">Message</td><td width=\"30%\">Author</td><td width=\"20%\">Delete</td><td width=\"20%\"><div align=\"right\">Select</div></td></tr><tr>";


              $tmpcolor = 1;
              while($row = mysql_fetch_array($result)) {
              if($tmpcolor == "1"){

              echo "<tr><td bgcolor=\"#F7F7F7\">";
              if(strlen($row[message]) >= 40){
                $message = limit_text($row[message],40);
                echo $message . ".....";
                }else{
                echo $row[message];
              }
      echo "</td><td bgcolor=\"#F7F7F7\">$row[user]</td><td bgcolor=\"#F7F7F7\"><a href=\"?action=editcomments&delete=true&pid=$row[pid]&id=$row[id]\"><div class=error align=\"cener\">DELETE</div></a></td><td bgcolor=\"#F7F7F7\"><div align=\"right\"><input type=\"radio\" value=\"$row[id]\" name=\"R1\"></div></td></tr>\n";
      $tmpcolor = "2";
      } else {
          echo "<tr><td bgcolor=\"#FFFFFF\">";
          if(strlen($row[message]) >= 40){
                $message = limit_text($row[message],40);
                echo $message . ".....";
                }else{
                echo $row[message];
      }
      echo "</td><td bgcolor=\"#FFFFFF\">$row[user]</td><td bgcolor=\"#FFFFFF\"><a href=\"?action=editcomments&delete=true&pid=$row[pid]&id=$row[id]\"><div class=error align=\"cener\">DELETE</div></a></td><td bgcolor=\"#FFFFFF\"><div align=\"right\"><input type=\"radio\" value=\"$row[id]\" name=\"R1\"></div></td></tr>\n";
      $tmpcolor = "1";
      }
}

echo "<tr><td></td><td></td><td></td><td><input type=\"submit\" value=\"Edit\" name=\"B1\"></td></tr></table></form>";
}else{

           if($_POST['B2'] == ""){

                  $sql = "SELECT message FROM `$newscomments` WHERE pid = '$_GET[pid]' AND id = '$_POST[R1]'";
                  $query = mysql_query($sql);
                  $message = mysql_result($query,0);
                  $sql = "SELECT user FROM `$newscomments` WHERE pid = '$_GET[pid]' AND id = '$_POST[R1]'";
                  $query = mysql_query($sql);
                  $user = mysql_result($query,0);
                  $sql = "SELECT email FROM `$newscomments` WHERE pid = '$_GET[pid]' AND id = '$_POST[R1]'";
                  $query = mysql_query($sql);
                  $email = mysql_result($query,0);

                  echo "<form method=\"POST\" action=\"?action=editcomments&pid=$_GET[pid]\">\n";
                  echo "<table border=\"0\" cellpadding=\"1\" cellspacing=\"1\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"84%\">\n";
                  echo "    <tr>\n";
                  echo "      <td width=\"25%\">Author:</td>\n";
                  echo "      <td width=\"75%\"><input type=\"text\" name=\"T1\" size=\"54\" value=\"$user\"></td>\n";
                  echo "    </tr>\n";
                  echo "    <tr>\n";
                  echo "      <td width=\"25%\">Email:</td>\n";
                  echo "      <td width=\"75%\"><input type=\"text\" name=\"T2\" size=\"54\" value=\"$email\"></td>\n";
                  echo "    </tr>\n";
                  echo "    <tr>\n";
                  echo "      <td width=\"25%\" valign=\"top\">Message:</td>\n";
                  echo "      <td width=\"75%\"><textarea rows=\"13\" name=\"S1\" cols=\"58\">$message</textarea></td>\n";
                  echo "    </tr>\n";
                  echo "    <tr>\n";
                  echo "      <td width=\"25%\" valign=\"top\"></td>\n";
                  echo "      <td width=\"75%\">\n";
                  echo "  <input type=\"hidden\" name=\"B1\" value=\"$_POST[R1]\"><br><input type=\"submit\" value=\"Save\" name=\"B2\"></td>\n";
                  echo "    </tr>\n";
                  echo "  </table>\n";
                  echo "</form>\n";

           }else{
                  $sql = "UPDATE `$newscomments` SET user = '$_POST[T1]' , email = '$_POST[T2]', message = '$_POST[S1]' WHERE pid = '$_GET[pid]' AND id = '$_POST[B1]'";
                  $query = mysql_query($sql) or die ("Couldn't execute query.");
                  echo "<div align=\"right\"><table width=\"78%\"><tr><td><div class=success>Comment updated.</div></td></tr></table></div>";
           }

        }
}
    if($action == "options"){
            echo "<div class=header>Options</div><br><br>";

if($_GET['mod'] == ""){
echo "<div align=\"right\"><table width=\"74%\"><tr>";
echo "<td width=\"50%\"><a class=subheader href=\"?action=options&mod=accounts\">Manage Accounts</a></td>";
echo "<td width=\"50%\"><a class=subheader href=\"?action=options&mod=template\">Templates</a></td>";
echo "</tr><tr>";
echo "<td width=\"50%\"><br><a class=subheader href=\"?action=options&mod=system\">System Configuration</a></td>";
echo "<td width=\"50%\"><br><a class=subheader href=\"?action=options&mod=personal\">Personal Options</a></td>";
echo "</tr><tr>";
echo "<td width=\"50%\"><br><a class=subheader href=\"?action=options&mod=smilies\">Manage Smilies</a></td>";
echo "<td width=\"50%\"><br><a class=subheader href=\"?action=options&mod=filter\">Word Filters</a></td>";
echo "</tr>";
echo "</table></div>";
}
if($_GET['mod'] == "template"){
echo "<div class=header>Edit Template</div>";
if($_POST['B1'] == ""){

$sql2 = "SELECT header FROM $newsoptions WHERE 1";
$result2 = mysql_query($sql2);
$header = mysql_result($result2,0);

$sql3 = "SELECT template FROM $newsoptions WHERE 1";
$result3 = mysql_query($sql3);
$template = mysql_result($result3,0);

$sql4 = "SELECT footer FROM $newsoptions WHERE 1";
$result4 = mysql_query($sql4);
$footer = mysql_result($result4,0);

$sql5 = "SELECT comments FROM $newsoptions WHERE 1";
$result5 = mysql_query($sql5);
$comments = mysql_result($result5,0);

$sql6 = "SELECT commentsform FROM $newsoptions WHERE 1";
$result6 = mysql_query($sql6);
$commentsform = mysql_result($result6,0);

$sql7 = "SELECT npagintation FROM $newsoptions WHERE 1";
$result7 = mysql_query($sql7);
$npagintation = mysql_result($result7,0);

$sql8 = "SELECT cpagintation FROM $newsoptions WHERE 1";
$result8 = mysql_query($sql8);
$cpagintation = mysql_result($result8,0);

echo "<div align=\"right\"><table class=panel width=\"84%\"><tr><td>Edit the templates below to change how your news and comments are displayed.</td></tr></table><br></div>";
echo "<div align=\"right\">\n";
echo "<form method=\"POST\" action=\"?action=options&mod=template\">\n";
echo "<table class=\"collapsible\" border=\"0\" width=\"77%\">\n";
echo "<tbody>\n";
echo "    <tr>\n";
echo "        <td bgcolor=\"#F7F7F7\">News Header</td>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "        <td><textarea rows=\"9\" name=\"S1\" cols=\"79\">$header</textarea></td>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "        <td bgcolor=\"#F7F7F7\">News Body</td>\n";
echo "        </tr>\n";
echo "    <tr>\n";
echo "        <td>";
echo "Changes how your news is displayed.<br><br>";
echo "<b>{title}</b> - Displays the title of the story.<br>";
echo "<b>{story}</b> - Shows the story of the news.<br>";
echo "<b>{date}</b> - Shows the date the story was published on.<br>";
echo "<b>{author}</b> - Shows the author the story.<br>";
echo "<b>{avatar}</b> - Shows the users avatar if they have one.<br>";
echo "<b>{id}</b> - Shows the unique ID each story has.<br>";
echo "<b>{email}</b> - Will show your email address.<br>";
echo "<b>[email] and [/email]</b> - Will create a link to your email address.<br>";
echo "<b>{comments}</b> - Shows the amount of comments for each news post if any.<br>";
echo "<b>[comments] and [/comments]</b> - Will create a link to the comments.<br><br>";
echo "<textarea rows=\"9\" name=\"S2\" cols=\"79\">$template</textarea></td>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "        <td bgcolor=\"#F7F7F7\">News Footer</td>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "        <td><textarea rows=\"9\" name=\"S3\" cols=\"79\">$footer</textarea></td>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "        <td bgcolor=\"#F7F7F7\">Comments</td>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "        <td>";
echo "Changes how the comments are displayed.<br><br>";
echo "<b>{author}</b> - Displays the author of the comment.<br>";
echo "<b>{message}</b> - Shows the story of the comment.<br>";
echo "<b>{email}</b> - Shows the email address of the user who commented.<br>";
echo "<b>[email] and [/email]</b> - Will create a link to the users email.<br>";
echo "<b>{date}</b> - Shows the date the comment was published on.<br><br>";
echo "<textarea rows=\"9\" name=\"S4\" cols=\"79\">$comments</textarea></td>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "        <td bgcolor=\"#F7F7F7\">Comments Form</td>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "        <td>";
echo "Do not edit this unless you have a fair understanding of html.<br><br><b>{id}</b> - Displays the ID of the news post.<br><b>{name}</b> - Will show your username if logged in.<br><b>{email}</b> - Will show your email if logged in.<br><br><textarea rows=\"9\" name=\"S5\" cols=\"79\">$commentsform</textarea></td>\n";
echo "    </tr>\n";

echo "    <tr>\n";
echo "        <td bgcolor=\"#F7F7F7\">News Pagination</td>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "        <td>";
echo "<b>[prev-link] and [/prev-link]</b> - Will show the previous link if there is any.<br>";
echo "<b>[next-link] and [/next-link]</b> - Will show the next link if there is any.<br>";
echo "<b>{pages}</b> - Will show the amount of pages, Ex: 1 2 3 4.<br>";
echo "<br><textarea rows=\"9\" name=\"S6\" cols=\"79\">$npagintation</textarea></td>\n";
echo "    </tr>\n";

echo "    <tr>\n";
echo "        <td bgcolor=\"#F7F7F7\">Comments Pagination</td>\n";
echo "    </tr>\n";
echo "    <tr>\n";
echo "        <td>";
echo "<b>[prev-link] and [/prev-link]</b> - Will show the previous link if there is any.<br>";
echo "<b>[next-link] and [/next-link]</b> - Will show the next link if there is any.<br>";
echo "<b>{pages}</b> - Will show the amount of pages, Ex: 1 2 3 4.<br>";
echo "<br><textarea rows=\"9\" name=\"S7\" cols=\"79\">$cpagintation</textarea></td>\n";
echo "    </tr>\n";

echo "    <tr>\n";
echo "    <td>\n";
echo "    <input type=\"submit\" value=\"Save\" name=\"B1\"></td>\n";
echo "    </tr>\n";
echo "</tbody>\n";
echo "</table>\n";
echo "</form>\n";
echo "</div>\n";
} else {
        $header = $_POST['S1'];
        $template = $_POST['S2'];
        $footer = $_POST['S3'];
        $comments = $_POST['S4'];
        $commentsform = $_POST['S5'];
        $commentsform = str_replace("<","&lt;",$commentsform);
        $commentsform = str_replace(">","&gt;",$commentsform);
        $npagintation = $_POST['S6'];
        $cpagintation = $_POST['S7'];

                       $query = "UPDATE `$newsoptions` SET header = '$header'
                                 WHERE 1";
                       $query2 = "UPDATE `$newsoptions` SET template = '$template'
                                 WHERE 1";
                       $query3 = "UPDATE `$newsoptions` SET footer = '$footer'
                                 WHERE 1";
                       $query4 = "UPDATE `$newsoptions` SET comments = '$comments'
                                 WHERE 1";
                       $query5 = "UPDATE `$newsoptions` SET commentsform = '$commentsform'
                                 WHERE 1";
                       $query6 = "UPDATE `$newsoptions` SET npagintation = '$npagintation'
                                 WHERE 1";
                       $query7 = "UPDATE `$newsoptions` SET cpagintation = '$cpagintation'
                                 WHERE 1";
                       $result = mysql_query($query,$connection) or die ("Coundn't execute query.");
                       $result2 = mysql_query($query2,$connection) or die ("Coundn't execute query.");
                       $result3 = mysql_query($query3,$connection) or die ("Coundn't execute query.");
                       $result4 = mysql_query($query4,$connection) or die ("Coundn't execute query.");
                       $result5 = mysql_query($query5,$connection) or die ("Coundn't execute query.");
                       $result6 = mysql_query($query6,$connection) or die ("Coundn't execute query.");
                       $result7 = mysql_query($query7,$connection) or die ("Coundn't execute query.");
                       echo "<div align=\"right\"><table width=\"78%\"><tr><td><div class=success>Settings updated.</div></td></tr></table></div>";
}
}


if($_GET['mod'] == "system"){
$sql = "SELECT god FROM `$newsadmin` WHERE user='$_SESSION[name]'";
$result = mysql_query($sql);
$permission = mysql_result($result,0);
if($permission == "1"){
echo "<div align=\"right\"><table class=panel width=\"84%\"><tr><td>Edit the system configurations.</td></tr></table><br></div>";

$sql = "SELECT nppage FROM `$newsoptions` WHERE 1";
$query = mysql_query($sql);
$nppage = mysql_result($query,0);
$sql = "SELECT newsorder FROM `$newsoptions` WHERE 1";
$query = mysql_query($sql);
$newsorder = mysql_result($query,0);
$sql = "SELECT newstime FROM `$newsoptions` WHERE 1";
$query = mysql_query($sql);
$newstime = mysql_result($query,0);
$sql = "SELECT showavatars FROM `$newsoptions` WHERE 1";
$query = mysql_query($sql);
$showavatars = mysql_result($query,0);
$sql = "SELECT commentsorder FROM `$newsoptions` WHERE 1";
$query = mysql_query($sql);
$commentsorder = mysql_result($query,0);
$sql = "SELECT commentstime FROM `$newsoptions` WHERE 1";
$query = mysql_query($sql);
$commentstime = mysql_result($query,0);
$sql = "SELECT commentslength FROM `$newsoptions` WHERE 1";
$query = mysql_query($sql);
$commentslength = mysql_result($query,0);
$sql = "SELECT cppage FROM `$newsoptions` WHERE 1";
$query = mysql_query($sql);
$cppage = mysql_result($query,0);

          if($_POST['B1'] == ""){
       echo "<form method=\"POST\" action=\"?action=options&mod=system\">\n";
       echo "<div align=\"right\">\n";
       echo "<table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"84%\">\n";

       echo "  <tr>\n";
       echo "    <td width=\"55%\" bgcolor=\"#F7F7F7\">Amount of news per page:</td>\n";
       echo "    <td width=\"45%\" bgcolor=\"#F7F7F7\"><input type=\"text\" name=\"T1\" size=\"5\" value=\"$nppage\"></td>\n";
       echo "  </tr>\n";
       echo "  <tr>\n";
       echo "    <td width=\"55%\">What order the news is displayed in:</td>\n";
       echo "    <td width=\"45%\"><select size=\"1\" name=\"D1\">\n";
       if($newsorder == "DESC"){
       echo "    <option>DESC</option>\n";
       echo "    <option>ASC</option>\n";
       }else{
       echo "    <option>ASC</option>\n";
       echo "    <option>DESC</option>\n";
       }
       echo "    </select></td>\n";
       echo "  </tr>\n";
       echo "</table>\n";
       echo "<table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"84%\">\n";
       echo "  <tr>\n";
       echo "    <td width=\"55%\" bgcolor=\"#F7F7F7\">Date format for news:</td>\n";
       echo "    <td width=\"62%\" bgcolor=\"#F7F7F7\"><input type=\"text\" name=\"T2\" size=\"12\" value=\"$newstime\">  See here for help <a href=\"http://php.net/date\" target=\"_NEW\">Date()</a></td>\n";
       echo "  </tr>\n";
       echo "  <tr>\n";
       echo "    <td width=\"38%\">Show avatars:</td>\n";
       echo "    <td width=\"62%\"><select size=\"1\" name=\"D2\">\n";
       if($showavatars == "YES"){
       echo "    <option>YES</option>\n";
       echo "    <option>NO</option>\n";
       }else{
       echo "    <option>NO</option>\n";
       echo "    <option>YES</option>\n";
       }
       echo "    </select></td>\n";
       echo "  </tr>\n";
       echo "  <tr>\n";
       echo "    <td width=\"38%\" bgcolor=\"#F7F7F7\">What order comments are displayed in:</td>\n";
       echo "    <td width=\"62%\" bgcolor=\"#F7F7F7\"><select size=\"1\" name=\"D3\">\n";
       if($commentsorder == "DESC"){
       echo "    <option>DESC</option>\n";
       echo "    <option>ASC</option>\n";
       }else{
       echo "    <option>ASC</option>\n";
       echo "    <option>DESC</option>\n";
       }
       echo "    </select></td>\n";
       echo "  </tr>\n";
       echo "  <tr>\n";
       echo "    <td width=\"38%\">Date format for comments:</td>\n";
       echo "    <td width=\"62%\"><input type=\"text\" name=\"T3\" size=\"12\" value=\"$commentstime\">  See here for help <a href=\"http://php.net/date\" target=\"_NEW\">Date()</a></td>\n";
       echo "  </tr>\n";
       echo "  <tr>\n";
       echo "    <td width=\"38%\" bgcolor=\"#F7F7F7\">Maximum length of comments:</td>\n";
       echo "    <td width=\"62%\" bgcolor=\"#F7F7F7\"><input type=\"text\" name=\"T4\" size=\"12\" value=\"$commentslength\"></td>\n";
       echo "  </tr>\n";
       echo "  <tr>\n";
       echo "    <td width=\"38%\">Amount of comments per page:</td>\n";
       echo "    <td width=\"62%\"><input type=\"text\" name=\"T5\" size=\"6\" value=\"$cppage\"></td>\n";
       echo "  </tr>\n";
       echo "  <tr>\n";
       echo "    <td width=\"38%\">&nbsp;</td>\n";
       echo "    <td width=\"62%\"><input type=\"submit\" value=\"Save\" name=\"B1\"></td>\n";
       echo "  </tr>\n";
       echo "</table>\n";
       echo "</div>\n";
       echo "</form>\n";
               }else{
               $nppage = $_POST['T1'];
               if($nppage == "0"){ $nppage = "1";}
               $newsorder = $_POST['D1'];
               $newstime = $_POST['T2'];
               $showavatars = $_POST['D2'];
               $commentsorder = $_POST['D3'];
               $commentstime = $_POST['T3'];
               $commentslength = $_POST['T4'];
               if($commentslength <= "9"){ $commentslength = "10";}
               $cppage = $_POST['T5'];
               if($cppage == "0"){ $cppage = "1";}
               $sql = "UPDATE `$newsoptions` SET nppage = '$nppage' WHERE 1";
               $query = mysql_query($sql);
               $sql = "UPDATE `$newsoptions` SET newsorder = '$newsorder' WHERE 1";
               $query = mysql_query($sql);
               $sql = "UPDATE `$newsoptions` SET newstime = '$newstime' WHERE 1";
               $query = mysql_query($sql);
               $sql = "UPDATE `$newsoptions` SET showavatars = '$showavatars' WHERE 1";
               $query = mysql_query($sql);
               $sql = "UPDATE `$newsoptions` SET commentsorder = '$commentsorder' WHERE 1";
               $query = mysql_query($sql);
               $sql = "UPDATE `$newsoptions` SET commentstime = '$commentstime' WHERE 1";
               $query = mysql_query($sql);
               $sql = "UPDATE `$newsoptions` SET commentslength = '$commentslength' WHERE 1";
               $query = mysql_query($sql);
               $sql = "UPDATE `$newsoptions` SET cppage = '$cppage' WHERE 1";
               $query = mysql_query($sql);
               echo "<div align=\"right\"><table width=\"84%\"><tr><td><div class=success>Settings updated.</div></td></tr></table></div>";
               }
}else{
        echo "<div class=error>You do not have the needed privileges to access this section.</div>";
}
}
if($_GET['mod'] == "accounts"){
$sql = "SELECT god FROM `$newsadmin` WHERE user='$_SESSION[name]'";
$result = mysql_query($sql);
$permission = mysql_result($result,0);
if($permission == "1"){
echo "<div class=header>Accounts</div>";
           if($_POST['R1'] == ""){

                      if($_GET['create'] == ""){
                              } else {

                              function showcreate(){
                              $name = $_POST['Tname'];
                              $email = $_POST['T1'];
                              $AccountType = $_POST['D1'];
                              echo "<div align=\"right\">\n";
                              echo "<form method=\"POST\" action=\"?action=options&mod=accounts&create=new\">\n";
                              echo "    <table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"84%\">\n";
                              echo "    <tr>\n";
                              echo "      <td width=\"28%\">Account Name:</td>\n";
                              echo "      <td width=\"72%\"><input type=\"text\" name=\"Tname\" size=\"24\" value=\"$name\"></td>\n";
                              echo "    </tr>\n";
                              echo "    <tr>\n";
                              echo "      <td width=\"28%\">Email:</td>\n";
                              echo "      <td width=\"72%\"><input type=\"text\" name=\"T1\" size=\"24\" value=\"$email\"></td>\n";
                              echo "    </tr>\n";
                              echo "    <tr>\n";
                              echo "      <td width=\"28%\">New Password:</td>\n";
                              echo "      <td width=\"72%\"><input type=\"password\" name=\"T2\" size=\"24\">\n";
                              echo "      </td>\n";
                              echo "    </tr>\n";
                              echo "    <tr>\n";
                              echo "      <td width=\"28%\">Confirm Password:</td>\n";
                              echo "      <td width=\"72%\"><input type=\"password\" name=\"T3\" size=\"24\"></td>\n";
                              echo "    </tr>\n";
                              echo "    <tr>\n";
                              echo "      <td width=\"28%\">Account Type:</td>\n";
                              echo "      <td width=\"72%\"><select size=\"1\" name=\"D1\">\n";
                              if($AccountType == "God"){
                              echo "      <option>God</option>\n";
                              echo "      <option>Normal</option>\n";
                              }else{
                              echo "      <option>Normal</option>\n";
                              echo "      <option>God</option>\n";
                              }
                              echo "      </select></td>\n";
                              echo "    </tr>\n";
                              echo "    <tr>\n";
                              echo "      <td width=\"28%\">&nbsp;</td>\n";
                              echo "      <td width=\"72%\"><input type=\"submit\" value=\"Create\" name=\"B1\"></td>\n";
                              echo "<input type=\"hidden\" value=\"$_POST[R1]\" name=\"R1\">\n";
                              echo "    </tr>\n";
                              echo "  </table>\n";
                              echo "</form>\n";
                              echo "</div>\n";
                              }

                               $sql = "SELECT * FROM `$newsadmin` WHERE user = '$_POST[Tname]'";
                               $result = mysql_query($sql,$connection) or die(mysql_error());
                               $num = mysql_num_rows($result);
                               if($_POST['B1'] == ""){
                                       showcreate();
                               }elseif($_POST['Tname'] == ""){
                                       echo "<div align=\"right\"><table width=\"84%\"><tr><td><div class=error>An account name must be entered.</div></td></tr></table></div>";
                                       showcreate();
                               }elseif($num >= 1){
                                  echo "<div align=\"right\"><table width=\"84%\"><tr><td><div class=error>Account name already exists. Please choose another.</div></td></tr></table></div>";
                                  showcreate();
                                  }elseif($_POST['T1'] == ""){
                                  echo "<div align=\"right\"><table width=\"84%\"><tr><td><div class=error>An email address must be entered.</div></td></tr></table></div>";
                                  showcreate();
                                  }elseif($_POST['T2'] == ""){
                                  echo "<div align=\"right\"><table width=\"84%\"><tr><td><div class=error>A password must be entered.</div></td></tr></table></div>";
                                  showcreate();
                                  }elseif($_POST['T2'] == $_POST['T3']){
                                          if($_POST['D1'] == "God"){
                                                  $God = "1";
                                                  }else{
                                                  $God = "0";
                                          }
                                          $newpass = md5($_POST['T2']);
                                       $sql2 = "INSERT INTO `$newsadmin` (user,pass,email,god) VALUES ('$_POST[Tname]','$newpass','$_POST[T1]','$God')";
                                       $query2 = mysql_query($sql2) or die ("couldn't execute query.");
                                       echo "<div align=\"right\"><table width=\"84%\"><tr><td><div class=success>Account Created.</div></td></tr></table></div>";
                                       }else{
                                       echo "<div align=\"right\"><table width=\"84%\"><tr><td><div class=error>Passwords don't match.</div></td></tr></table></div>";
                                       showcreate();
                                       }

                                      }


                    if($_GET['user'] == ""){
                            }else{
                                  if($_POST['B1'] == "Yes"){
                                          $sql = "DELETE FROM `$newsadmin` WHERE user = '$_GET[user]'";
                                          $query = mysql_query($sql) or die ("Couldn't execute query.");

                                          }
                                  if($_GET['status'] == ""){
                            echo "<form method=\"POST\" action=\"?action=options&mod=accounts&user=$_GET[user]&status=done\">\n";
                            echo "<div align=\"right\">\n";
                            echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"84%\">\n";
                            echo "    <tr>\n";
                            echo "      <td width=\"100%\">\n";
                            echo "      <div align=\"center\" class=error>Are you sure you want to delete this account?</div>\n";
                            echo "          <br>\n";
                            echo "          <div align=\"center\">$_GET[user]</div><br>\n";
                            echo "      <p align=\"center\"></td>\n";
                            echo "    </tr>\n";
                            echo "    <tr>\n";
                            echo "      <td width=\"100%\"><div align=\"center\"><input type=\"submit\" value=\"Yes\" name=\"B1\">&nbsp;<input type=\"submit\" value=\"No\" name=\"B1\"></div></td>\n";
                            echo "    </tr>\n";
                            echo "</table>\n";
                            echo "</div>\n";
                            echo "</form>\n";
                            }
                                    }
                    $sql =  "SELECT * FROM $newsadmin ORDER BY user ASC";
                    $result = mysql_query($sql)
                            or die ("Couldn't execute query.");
                    $tmpcolor = "1";
                    $sql2 = "SELECT * FROM `$newsadmin";
                    $query2 = mysql_query($sql2);
                    $totalaccounts = mysql_num_rows($query2);
                    echo "<br><div align=\"right\"><table class=panel width=\"84%\"><tr><td>Showing a total of <b>$totalaccounts</b> accounts.</td></tr></table></div><br>";
                    echo "<div align=\"right\"><form method=\"POST\" action=\"?action=options&mod=accounts\">";
                    echo "<table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" width=\"84%\">\n";
                    echo "<tr><td width=\"30%\">Username</td><td width=\"20%\">Account type</td><td width=\"20%\"><div align=\"right\">Number of posts</div></td></td><td width=\"25%\"><div align=\"center\">Delete</div></td><td><div align=\"right\">Select<div></td></tr>";
                           while($row = mysql_fetch_array( $result )) {
                    $sql2 = "SELECT * FROM $newstable WHERE author = '$row[user]'";
                    $query2 = mysql_query($sql2);
                    $numposts = mysql_num_rows($query2);


                                        if($tmpcolor == "1"){
                                        echo "<tr><td bgcolor=\"#F7F7F7\">$row[user]</td><td bgcolor=\"#F7F7F7\">"; if($row[god] == "1"){ echo "God";}else{echo "Normal";}echo "</td><td bgcolor=\"#F7F7F7\"><div align=\"right\">$numposts</div></td><td bgcolor=\"#F7F7F7\"><a href=\"?action=options&mod=accounts&user=$row[user]\"><div class=error align=\"center\">DELETE</div></a></td><td bgcolor=\"#F7F7F7\"><div align=\"right\"><input type=\"radio\" name=\"R1\" value=\"$row[user]\"></div></td></tr>\n";
                                        $tmpcolor = "2";
                                        } else {
                                        echo "<tr><td bgcolor=\"#FFFFFF\">$row[user]</td><td bgcolor=\"#FFFFFF\">"; if($row[god] == "1"){ echo "God";}else{echo "Normal";}echo "</td><td bgcolor=\"#FFFFFF\"><div align=\"right\">$numposts</div></td><td bgcolor=\"#FFFFFF\"><a href=\"?action=options&mod=accounts&user=$row[user]\"><div class=error align=\"center\">DELETE</div></a></td><td bgcolor=\"#FFFFFF\"><div align=\"right\"><input type=\"radio\" name=\"R1\" value=\"$row[user]\"></div></td></tr>\n";
                                        $tmpcolor = "1";
                                        }
                    }
                    echo "<td></td><td></td><td></td><td></td><td><div align=\"right\"><input type=\"submit\" name=\"S1\" value=\"Edit\"></div></td><tr><td><u><a href=\"?action=options&mod=accounts&create=new\">Create new account.</u></td></tr></table></form></div>";
           } else {
function showform($newsadmin){
                           $sql = "SELECT email FROM `$newsadmin` WHERE user = '$_POST[R1]'";
                   $query = mysql_query($sql) or die ("Couldn't execute query.");
                   $email = mysql_result($query,0);

                   $sql2 = "SELECT god FROM `$newsadmin` WHERE user = '$_POST[R1]'";
                   $query2 = mysql_query($sql2) or die ("Couldn't execute query.");
                   $AccountType = mysql_result($query2,0);

           echo "<div align=\"right\">\n";
           echo "<form method=\"POST\" action=\"?action=options&mod=accounts\">\n";
           echo "    <table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"84%\">\n";
           echo "    <tr>\n";
           echo "      <td width=\"28%\">Account Name:</td>\n";
           echo "      <td width=\"72%\">$_POST[R1]</td>\n";
           echo "    </tr>\n";
           echo "    <tr>\n";
           echo "      <td width=\"28%\">Email:</td>\n";
           echo "      <td width=\"72%\"><input type=\"text\" name=\"T1\" size=\"24\" value=\"$email\"></td>\n";
           echo "    </tr>\n";
           echo "    <tr>\n";
           echo "      <td width=\"28%\">New Password:</td>\n";
           echo "      <td width=\"72%\"><input type=\"password\" name=\"T2\" size=\"24\"> (Leave blank\n";
           echo "      to keep current)</td>\n";
           echo "    </tr>\n";
           echo "    <tr>\n";
           echo "      <td width=\"28%\">Confirm Password:</td>\n";
           echo "      <td width=\"72%\"><input type=\"password\" name=\"T3\" size=\"24\"></td>\n";
           echo "    </tr>\n";
           echo "    <tr>\n";
           echo "      <td width=\"28%\">Account Type:</td>\n";
           echo "      <td width=\"72%\"><select size=\"1\" name=\"D1\">\n";
if($AccountType == "1"){
           echo "      <option>God</option>\n";
           echo "      <option>Normal</option>\n";
           } else {
           echo "      <option>Normal</option>\n";
           echo "      <option>God</option>\n";
}
           echo "      </select></td>\n";
           echo "    </tr>\n";
           echo "    <tr>\n";
           echo "      <td width=\"28%\">&nbsp;</td>\n";
           echo "      <td width=\"72%\"><input type=\"submit\" value=\"Save\" name=\"B1\"></td>\n";
           echo "<input type=\"hidden\" value=\"$_POST[R1]\" name=\"R1\">";
           echo "    </tr>\n";
           echo "  </table>\n";
           echo "  <p>&nbsp;</p>\n";
           echo "</form>\n";
           echo "</div>\n";
}

if($_POST['B1'] == ""){
showform($newsadmin);
}elseif($_POST['T1'] == ""){
        echo "<div align=\"right\"><table width=\"84%\"><tr><td><div class=error>Email address must be filled in.</div></td></tr></table></div>";
        showform($newsadmin);
}elseif($_POST['T2'] == $_POST['T3']){
                        $email = $_POST['T1'];
                        if($_POST['T2'] == ""){
                         $pass = "";
                                }else{
                         $pass = md5($_POST['T2']);
                        }

                        $name = $_POST['R1'];
                        if($_POST['D1'] == "God"){
                        $god = "1";
                        } else {
                        $god = "0";
                        }
                       $query = "UPDATE `$newsadmin` SET email = '$email'
                                 WHERE user = '$name'";
                       if($pass == ""){
                               }else{
                       $query2 = "UPDATE `$newsadmin` SET pass = '$pass'
                                 WHERE user = '$name'";
                       $result2 = mysql_query($query2,$connection) or die ("Coundn't execute query.");
                             }
                       $query3 = "UPDATE `$newsadmin` SET god = '$god'
                                 WHERE user = '$name'";
                       $result = mysql_query($query,$connection) or die ("Coundn't execute query.");
                       $result3 = mysql_query($query3,$connection) or die ("Coundn't execute query.");
        echo "<div align=\"right\"><table width=\"84%\"><tr><td><div class=success>Account Updated.</div></td></tr></table></div>";
        }else{
        echo "<div align=\"right\"><table width=\"84%\"><tr><td><div class=error>Passwords don't match.</div></td></tr></table></div>";
                showform($newsadmin);
}

}
}else{
        echo "<div class=error>You do not have the needed privileges to access this section.</div>";
        }
/*end of accounts mod */
}
if($_GET['mod'] == "filter"){
/*start of filter mod */
echo "<div class=header>Word Filters</div>";

if($_POST['filter'] == ""){
        if($_POST['alt'] == ""){
        }
}else{
$sql = "SELECT * FROM $newsfilter";
$query = mysql_query($sql);
$id = mysql_num_rows($query);
$id = $id +1;
$sql = "INSERT INTO $newsfilter (filter,alt,id) VALUES ('$_POST[filter]','$_POST[alt]','$id')";
$query = mysql_query($sql) or die ("couldn't execute query.");
echo "<div align=\"right\"><table width=\"84%\"><tr><td><div class=success>Filter added.</div></td></tr></table></div>";
}
if($_GET['delete'] == "true"){
$sql = "DELETE FROM $newsfilter WHERE id = '$_GET[id]'";
$query = mysql_query($sql) or die ("couldn't execute query.");

  $sql2 = "SELECT * FROM `$newsfilter`";
  $query2 = mysql_query($sql2);
  $numrows = mysql_num_rows($query2);
  $i = $_GET['id'];
  $s = $i + 1;

  while($i <= $numrows){
        $sql = "UPDATE `$newsfilter` SET id = '$i' WHERE id = '$s'";
        $query = mysql_query($sql);
          $i++;
          $s++;
  }

echo "<div align=\"right\"><table width=\"84%\"><tr><td><div class=success>Filter removed.</div></td></tr></table></div>";
}
echo "<div align=\"right\"><table class=panel width=\"84%\"><tr><td>All words below will be filtered from all comments.</td></tr></table><br></div>";

echo "<div align=\"right\"><table width=\"84%\" border=\"0\" cellpadding=\"1\" cellspacing=\"0\"><tr><td width=\"40%\">Filtered Word</td><td width=\"40%\">Replace With</td><td width=\"20%\"><div align=\"right\">Delete</div></td></tr>";
$sql = "SELECT * FROM $newsfilter";
$query = mysql_query($sql);
$tmpcolor = "1";

            while($row = mysql_fetch_array( $query )) {
                      if($tmpcolor == "1"){
                      echo "<tr><td bgcolor=\"#F7F7F7\">$row[filter]</td><td bgcolor=\"#F7F7F7\">$row[alt]</td><td bgcolor=\"#F7F7F7\"><a href=\"?action=options&mod=filter&delete=true&id=$row[id]\"><div class=error align=\"right\">DELETE</div></a></td></tr>";
                      $tmpcolor = "2";
                      } else {
                      echo "<tr><td bgcolor=\"#FFFFFF\">$row[filter]</td><td bgcolor=\"#FFFFFF\">$row[alt]</td><td bgcolor=\"#FFFFFF\"><a href=\"?action=options&mod=filter&delete=true&id=$row[id]\"><div class=error align=\"right\">DELETE</div></a></td></tr>";
                      $tmpcolor = "1";
                      }
            }
echo "</table></div>";
echo "<br>";
echo "<div align=\"right\"><table width=\"84%\"><tr><td>";
echo "<form method=\"post\" action=\"?action=options&mod=filter&new=true\">";
echo "Filtered word: <input type=\"text\" name=\"filter\">  Replace with: <input type=\"text\" name=\"alt\">";
echo "&nbsp;&nbsp;<input type=\"submit\" value=\"Add\">";
echo "</form>";
echo "</td></tr></table></div>";
/* end of filter mod */
}
if($_GET['mod'] == "personal"){
/*start of personal options */
echo "<div class=header>Personal Options.</div>";

        $sql = "SELECT avatar FROM `$newsadmin` WHERE user='$_SESSION[name]'";
        $result = mysql_query($sql);
        $avatar = mysql_result($result,0);
        $sql = "SELECT email FROM `$newsadmin` WHERE user='$_SESSION[name]'";
        $result = mysql_query($sql);
        $email = mysql_result($result,0);
        $name = $_SESSION[name];
        function showpersonal($name,$avatar,$email){
        echo "<form method=\"POST\" action=\"?action=options&mod=personal\">\n";
        echo "<div align=\"right\"><table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"84%\">\n";
        echo "    <tr>\n";
        echo "      <td width=\"22%\">Personal settings for</td>\n";
        echo "      <td width=\"78%\"><div class=success>$name</div></td>\n";
        echo "    </tr>\n";
        echo "    <tr>\n";
        echo "      <td width=\"22%\">Avatar url:</td>\n";
        echo "      <td width=\"78%\"><input type=\"text\" name=\"T1\" size=\"25\" value=\"$avatar\"></td>\n";
        echo "    </tr>\n";
        echo "    <tr>\n";
        echo "      <td width=\"22%\">Email address:</td>\n";
        echo "      <td width=\"78%\"><input type=\"text\" name=\"T2\" size=\"25\" value=\"$email\"></td>\n";
        echo "    </tr>\n";
        echo "    <tr>\n";
        echo "      <td width=\"22%\">New password:</td>\n";
        echo "      <td width=\"78%\"><input type=\"password\" name=\"T3\" size=\"20\"> (leave blank to\n";
        echo "      keep current)</td>\n";
        echo "    </tr>\n";
        echo "    <tr>\n";
        echo "      <td width=\"22%\">Confirm password:</td>\n";
        echo "      <td width=\"78%\"><input type=\"password\" name=\"T4\" size=\"20\"></td>\n";
        echo "    </tr>\n";
        echo "    <tr>\n";
        echo "      <td width=\"22%\">&nbsp;</td>\n";
        echo "      <td width=\"78%\"><input type=\"submit\" value=\"Save\" name=\"B1\"></td>\n";
        echo "    </tr>\n";
        echo "  </table>\n";
        echo "</div>\n";
        echo "</form>\n";
        }
        if($_POST['B1'] == ""){
        showpersonal($name,$avatar,$email);
        }elseif($_POST['T2'] == ""){
                echo "<div align=\"right\"><table width=\"84%\"><tr><td><div class=error>An email address must be entered.</div></td></tr></table></div>";
                showpersonal($name,$avatar,$email);
        }elseif($_POST['T3'] == $_POST['T4']){
                if($_POST['T3'] == ""){
                $sql = "UPDATE `$newsadmin` SET avatar = '$_POST[T1]' WHERE user = '$_SESSION[name]'";
                $query = mysql_query($sql) or die ("Couldnt execute query.");
                $sql = "UPDATE `$newsadmin` SET email = '$_POST[T2]' WHERE user = '$_SESSION[name]'";
                $query = mysql_query($sql) or die ("Couldnt execute query.");
                }else{
                $sql = "UPDATE `$newsadmin` SET avatar = '$_POST[T1]' WHERE user = '$_SESSION[name]'";
                $query = mysql_query($sql) or die ("Couldnt execute query.");
                $sql = "UPDATE `$newsadmin` SET email = '$_POST[T2]' WHERE user = '$_SESSION[name]'";
                $query = mysql_query($sql) or die ("Couldnt execute query.");
                $pass = md5($_POST['T3']);
                $sql = "UPDATE `$newsadmin` SET pass = '$pass' WHERE user = '$_SESSION[name]'";
                $query = mysql_query($sql) or die ("Couldnt execute query.");
                }
                echo "<div align=\"right\"><table width=\"84%\"><tr><td><div class=error><div class=success>Settings updated.</div></td></tr></table></div>";
                }else{
                echo "<div align=\"right\"><table width=\"84%\"><tr><td><div class=error><div class=error>Passwords don't match.</div></td></tr></table></div>";
                showpersonal($name,$avatar,$email);
                }



/*end of personal options */
}

/* start of smilies mod */
if($_GET['mod'] == "smilies"){
echo "<div class=header>Smilies.</div>";

if($_GET['create'] == "new"){

               function smileyform(){
               echo "<form method=\"POST\" action=\"?action=options&mod=smilies&create=new\">\n";
               echo "<div align=\"right\">\n";
               echo "<table border=\"0\" cellspacing=\"1\" width=\"84%\">\n";
               echo "    <tr>\n";
               echo "      <td width=\"19%\">\n";
               echo "      Path:</td>\n";
               echo "      <td width=\"81%\">\n";
               echo "      <input type=\"text\" name=\"T1\" size=\"20\" value=\"$_POST[T1]\"></td>\n";
               echo "    </tr>\n";
               echo "    <tr>\n";
               echo "      <td width=\"19%\">\n";
               echo "      Keycode:</td>\n";
               echo "      <td width=\"81%\">\n";
               echo "      <input type=\"text\" name=\"T2\" size=\"20\" value=\"$_POST[T2]\"></td>\n";
               echo "    </tr>\n";
               echo "    <tr>\n";
               echo "      <td width=\"19%\">\n";
               echo "      &nbsp;</td>\n";
               echo "      <td width=\"81%\">\n";
               echo "      <input type=\"submit\" value=\"Insert\" name=\"B1\"></td>\n";
               echo "    </tr>\n";
               echo "  </table>\n";
               echo "</div>\n";
               echo "</form><br>\n";
               }
               $sql = "SELECT * FROM $newssmilies WHERE keycode = '$_POST[T2]'";
               $query = mysql_query($sql);
               $exists = mysql_num_rows($query);

               if($_POST['B1'] == ""){
                       smileyform();
               }elseif($_POST['T1'] == ""){
                echo "<div align=\"right\"><table width=\"84%\"><tr><td><div class=error>Please enter the path to the smiley.</div></td></tr></table></div>";
                smileyform();
               }elseif($_POST['T2'] == ""){
                echo "<div align=\"right\"><table width=\"84%\"><tr><td><div class=error>Please enter a keycode for this smiley.</div></td></tr></table></div>";
                smileyform();
               }elseif($exists >= 1){
                echo "<div align=\"right\"><table width=\"84%\"><tr><td><div class=error>Keycode already in use. Please choose a different keycode.</div></td></tr></table></div>";
                smileyform();
               }else{
                $sql = "SELECT * FROM $newssmilies";
                $query = mysql_query($sql);
                $num = mysql_num_rows($query);
                $num = $num + 1;
                $sql = "INSERT INTO $newssmilies (path,keycode,type,id) VALUES ('$_POST[T1]','$_POST[T2]','news','$num')";
                $query = mysql_query($sql) or die ("Couldn't execute query.");
                echo "<div align=\"right\"><table width=\"84%\"><tr><td><div class=success>Smiley added.</div></td></tr></table></div>";
               }


        }
if($_GET['delete'] == "true"){
                                     $sql = "SELECT path FROM $newssmilies WHERE id = '$_GET[id]'";
                                     $query = mysql_query($sql);
                                     $path = mysql_result($query,0);
                                        if($_POST['B2'] == ""){
                                        echo "<form method=\"POST\" action=\"?action=options&mod=smilies&delete=true&id=$_GET[id]\">\n";
                                        echo "<div align=\"right\">\n";
                                        echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"84%\">\n";
                                        echo "    <tr>\n";
                                        echo "      <td width=\"100%\">\n";
                                        echo "      <div align=\"center\" class=error>Are you sure you want to delete this smiley?";
                                        echo "<br><br><img src=\"$path\" alt=\"$_GET[id]\">";
                                        echo "      </div>\n";
                                        echo "          <br>\n";
                                        echo "</td>\n";
                                        echo "    </tr>\n";
                                        echo "    <tr>\n";
                                        echo "      <td width=\"100%\"><div align=\"center\"><input type=\"submit\" value=\"Yes\" name=\"B2\">&nbsp;<input type=\"submit\" value=\"No\" name=\"B2\"></div></td>\n";
                                        echo "    </tr>\n";
                                        echo "</table>\n";
                                        echo "</div>\n";
                                        echo "</form>\n";
                                        }else{
                                                if($_POST['B2'] == "Yes"){
                                                $sql2 = "DELETE FROM `$newssmilies` WHERE id='$_GET[id]'";
                                                $query = mysql_query($sql2) or die ("Couldn't execute query.");
                                                $sql = "SELECT * FROM `$newssmilies`";
                                                $query = mysql_query($sql);
                                                $numrows = mysql_num_rows($query);
                                                $i = $_GET['id'];
                                                $s = ($i - 1);
                                                while($i <= $numrows){
                                                $sql = "UPDATE `$newssmilies` SET id = '$s' WHERE id = '$i'";
                                                $query = mysql_query($sql);
                                                $i++;
                                                $s++;
                                                }
                                                echo "<div align=\"right\"><table width\"84%\"><tr><td><div class=success>Smiley deleted.</div></td></tr></table></div>";
                                                        }else{
                                                        }
                                                }
}
if($_POST['smilies'] == ""){
        }else{

                if($_POST['S1'] == ""){
                $sql = "SELECT path FROM $newssmilies WHERE id = '$_POST[R1]'";
                $query = mysql_query($sql);
                $path = mysql_result($query,0);
                $sql = "SELECT keycode FROM $newssmilies WHERE id = '$_POST[R1]'";
                $query = mysql_query($sql);
                $keycode = mysql_result($query,0);
                echo "<form method=\"POST\" action=\"admin.php?action=options&mod=smilies\">\n";
                echo "<div align=\"right\">\n";
                echo "<table border=\"0\" cellspacing=\"1\" width=\"84%\">\n";
                echo "<tr>\n";
                echo "<td width=\"23%\">Smiley:</td>\n";
                echo "<td width=\"77%\"><img src=\"$path\"></td>\n";
                echo "</tr>\n";
                echo "<tr>\n";
                echo "<td width=\"23%\">Keycode:</td>\n";
                echo "<td width=\"77%\"><input type=\"text\" name=\"T2\" size=\"20\" value=\"$keycode\"></td>\n";
                echo "</tr>\n";
                echo "<tr>\n";
                echo "<td width=\"23%\">Path:</td>\n";
                echo "<td width=\"77%\"><input type=\"text\" name=\"T3\" size=\"20\" value=\"$path\"></td>\n";
                echo "</tr>\n";
                echo "<tr>\n";
                echo "<td width=\"23%\">&nbsp;</td>\n";
                echo "<td width=\"77%\"><input type=\"hidden\" value=\"$_POST[R1]\" name=\"R1\"><input type=\"hidden\" name=\"smilies\" value=\"blah\"><input type=\"submit\" value=\"Save\" name=\"S1\"></td>\n";
                echo "</tr>\n";
                echo "</table>\n";
                echo "</div>\n";
                echo "</form>\n";
                }else{
                      $sql = "UPDATE $newssmilies SET `path` = '$_POST[T3]', `keycode` = '$_POST[T2]' WHERE id = '$_POST[R1]'";
                      $query = mysql_query($sql) or die ("Couldn't execute query.");
                       echo "<div align=\"right\"><table width=\"84%\"><tr><td><div class=success>Smilie updated.</div></td></tr></table></div><br>";
                      }
              }

echo "<div align=\"right\"><table class=panel width=\"84%\"><tr><td>Please select which <b>News</b> smiley you would like to edit.</td></tr></table></div><br>";

echo "<div align=\"right\">";
echo "<table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" width=\"84%\">";
echo "<tr><td width=\"10%\"><div align=\"center\">Smiley</div></td><td width=\"15%\">Keycode</td><td width=\"55%\">Path</td><td width=\"10%\"><div align=\"center\">Delete</div></td><td width=\"10%\"><div align=\"right\">Edit</div></td></tr>";
echo "<form method=\"POST\" action=\"?action=options&mod=smilies\">";
                    $tmpcolor = "1";
                    $sql =  "SELECT * FROM $newssmilies WHERE type = 'news' ORDER BY keycode ASC";
                    $result = mysql_query($sql)
                            or die ("Couldn't execute query.");
                    while($row = mysql_fetch_array( $result )) {
                                        if($tmpcolor == "1"){
                                        echo "<tr><td bgcolor=\"#F7F7F7\"><div align=\"center\"><img src=\"$row[path]\"></div></td><td bgcolor=\"#F7F7F7\">$row[keycode]</td><td bgcolor=\"#F7F7F7\">$row[path]</td><td bgcolor=\"#F7F7F7\"><a href=\"?action=options&mod=smilies&delete=true&id=$row[id]\"><div class=\"error\" align=\"center\">DELETE</div></a></td><td bgcolor=\"#F7F7F7\"><div align=\"right\"><input type=\"radio\" value=\"$row[id]\" name=\"R1\"></div></td></tr>";
                                        $tmpcolor = "2";
                                        } else {
                                        echo "<tr><td bgcolor=\"#FFFFFF\"><div align=\"center\"><img src=\"$row[path]\"></div></td><td bgcolor=\"#FFFFFF\">$row[keycode]</td><td bgcolor=\"#FFFFFF\">$row[path]</td><td bgcolor=\"#FFFFFF\"><a href=\"?action=options&mod=smilies&delete=true&id=$row[id]\"><div class=\"error\" align=\"center\">DELETE</div></a></td><td bgcolor=\"#FFFFFF\"><div align=\"right\"><input type=\"radio\" value=\"$row[id]\" name=\"R1\"></div></td></tr>";
                                        $tmpcolor = "1";
                                        }
                    }

echo "<tr><td></td><td></td><td></td><td></td><td><div align=\"right\"><input type=\"submit\" value=\"Edit\" name=\"smilies\"></div></td></tr>";
echo "</table>";
echo "</div>";
echo "</form>";
echo "<br><div align=\"right\"><table width=\"84%\"><tr><td><u><a href=\"?action=options&mod=smilies&create=new\">Insert a new smiley.</u></td></tr></table></div>";
/* end of smilies mod */
}

}

    if($action == "logout"){
            echo "Logged out<br><br>";
            }

}else{
            echo "<div class=header>Please Login:</div>";
            echo "<br><br>";
echo "            <form method=\"POST\" action=\"admin.php\">\n";
echo "            <div align=\"right\"><table border=\"0\" cellpadding=\"1\" cellspacing=\"0\" width=\"77%\">\n";
echo "              <tr>\n";
echo "                <td width=\"17%\">Name: </td>\n";
echo "                <td width=\"83%\">\n";
echo "              <input type=\"text\" name=\"name\" size=\"20\"></td>\n";
echo "              </tr>\n";
echo "              <tr>\n";
echo "                <td width=\"17%\" valign=\"top\">Password: </td>\n";
echo "                <td width=\"83%\">\n";
echo "              <input type=\"password\" name=\"pass\" size=\"20\"><br>\n";
echo "              </td>";

echo "                <tr>";
echo "                <td width=\"17%\" valign=\"bottom\">Security Key: </td>\n";
echo "                <td width=\"100%\" valign=\"bottom\">\n";
echo "              <img src=\"image.php\" alt=\"key\">Type this code below</tr><tr><td></td><td><input type=\"text\" name=\"key\" size=\"20\"><br>\n";

echo "<tr><td></td><td>";
echo "<input type=submit name=B3 style=\"width:134px; background-color: #F3F3F3;\" value='      Login...      '>";
if($_POST['B3'] == ""){
        }else{
                        $sql = "SELECT * FROM $newsadmin WHERE user = '$_POST[name]' AND pass = '$pass'";
                        $result = mysql_query($sql)
                        or die ("Couldn't execute query.");
                        $num = mysql_num_rows($result);
                        if($num >= 1){
                                if(md5($number) == $_SESSION['image_random_value']) {
                                        }else{
                                                echo "<div class=error>Invalid key</div>";
                                                }

                                        }else{
                                                echo "<div class=error>Invalid name or password.</div>";
                                        }


                }
echo "</td>\n";
echo "</tr>\n";
echo "</table></div>\n";
echo "</form>\n";
}
    ?>
    </td>
  </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" width="730">
  <tr>
    <td width="100%">
                        <img src="images/index_04.gif" width="730" height="19" alt=""></td>
  </tr>
</table>
Powered by <a href="http://network-13.com">N-13 News 1.2 </a>&copy; 2005
<br><br>
<a href="http://validator.w3.org/check?uri=referer"><img border="0" src="images/logo-html.png" alt="Valid HTML 4.01!"></a>
<a href="http://www.spreadfirefox.com/?q=user/register&amp;r=122321"><img style="border:0" src="images/logo-firefox.png" alt="Get FireDox!"></a>
<br><br>
<div align="center">
<script type="text/javascript"><!--
google_ad_client = "pub-1154236308095858";
google_ad_width = 468;
google_ad_height = 15;
google_ad_format = "468x15_0ads_al";
google_ad_channel ="";
google_color_border = "FFFFFF";
google_color_bg = "FFFFFF";
google_color_link = "000000";
google_color_url = "AAAAAA";
google_color_text = "cccccc";
//--></script>
<script type="text/javascript"
  src="http://pagead2.googlesyndication.com/pagead/show_ads.js">
</script>
</div>
</div>
</body>
</html>