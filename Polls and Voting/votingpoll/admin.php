<?php
include 'config.php';

$tmp = $_GET['action'];
if($tmp == "signout"){
$cookie_name = "voteauth";
$cookie_value = "";
$cookie_expire = "0";
$cookie_domain = $domain;
setcookie($cookie_name, $cookie_value, $cookie_expire, "/", $cookie_domain, 0);
header ("Location: http://" . $domain . $directory . "admin.php");
}

if($_POST['name'] == ""){
        $loggedin = "false";
        }else {

$sql = "SELECT * FROM $admintable WHERE name = '$_POST[name]' AND pass = '$_POST[pass]'";
$result = mysql_query($sql)
        or die ("Couldn't execute query.");
$num = mysql_num_rows($result);
if($num >= 1){

$cookie_name = "voteauth";
$cookie_value = "fook!";
$cookie_expire = "0";
$cookie_domain = $domain;

setcookie($cookie_name, $cookie_value, $cookie_expire, "/", $cookie_domain, 0);
header ("Location: http://" . $domain . $directory . "admin.php");
}
}
if($_COOKIE['voteauth'] == "fook!"){
$loggedin = "true";
}
?>
<html>
<head>
<meta http-equiv="Content-Language" content="en-gb">

<title>N-13 Voting System</title>

<script language="JavaScript">
function openNewWindow() {
  popupWin =
}
</script>
</head>
<body bgcolor="#FFFFFF" leftmargin="4" topmargin="0" marginwidth="0" marginheight="0">

<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="593" height="74">
  <tr>
    <td width="100%" background="images/index_02.jpg">&nbsp;</td>
  </tr>
</table>
<table border="0" cellpadding="0" cellspacing="0" style="border-collapse: collapse" bordercolor="#111111" width="593">
  <tr>
    <td width="145" background="images/index_04.jpg" valign="top">
    <font face="Tahoma" size="2">
    <?php
    if($loggedin == "true"){
      echo "<font face=\"Tahoma\" size=\"2\">&nbsp;  <a href=\"admin.php?action=create\">Create new poll</a></font>";
      echo "<br>";
      echo "<font face=\"Tahoma\" size=\"2\">&nbsp;  <a href=\"admin.php?action=edit\">Edit existing poll</a></font>";
      echo "<br>";
      echo "<font face=\"Tahoma\" size=\"2\">&nbsp;  <a href=\"admin.php?action=delete\">Delete existing poll</a></font>";
      echo "<br>";
      echo "<font face=\"Tahoma\" size=\"2\">&nbsp;  <a href=\"admin.php?action=code\">Code Generator</a></font>";
      echo "<br><font face=\"Tahoma\" size=\"2\">&nbsp;  <a href=\"admin.php?action=signout\">Sign out</a></font>";
      echo "<br><br><br><font face=\"Tahoma\" size=\"2\">&nbsp;  <a href=\"http://network-13.com\">Network-13</a></font>";
    } else {
      echo "&nbsp;  Please login.";
    }
    ?>
    </font>
        </td>
    <td width="9"></td>
    <td width="430" valign="top">
<font face="Tahoma" size="2">
    <?php
    if($loggedin == "true"){
                     echo "Logged in<br><br>";

                     if($_GET['action'] == "create"){


                                          if($_POST['pollname'] == ""){
                                                  } else {
                                                          $new_pollname = ereg_replace("[^A-Za-z0-9]", "", $_POST['pollname']);
                                                          $pollname = "poll_" . $new_pollname;
                                                          $question = $_POST['question'];
                                                          $option1 = $_POST['option1'];
                                                          $option2 = $_POST['option2'];
                                                          $option3 = $_POST['option3'];
                                                          $option4 = $_POST['option4'];
                                                          $option5 = $_POST['option5'];
                                                          $option6 = $_POST['option6'];
                                                          $option7 = $_POST['option7'];
                                                          $option8 = $_POST['option8'];
                                                          $num_rows = $_POST['num_rows'];
                                                          $sql2 = "CREATE TABLE `$pollname`(vote varchar(255),ip varchar(255),multiple_votes varchar(255),question varchar(255),num_options varchar(255),showpercent varchar(255),showvotes varchar(255),showtotalvotes varchar(255),option1 varchar(255),option2 varchar(255),option3 varchar(255),option4 varchar(255),option5 varchar(255),option6 varchar(255),option7 varchar(255),option8 varchar(255))";
                                                          $result2 = mysql_query($sql2)
                                                          or die ("Couldn't execute query.");
                                                          $sql3 = "INSERT INTO `$pollname`(multiple_votes,showpercent,showvotes,showtotalvotes,question,num_options,option1,option2,option3,option4,option5,option6,option7,option8)
                                                           VALUES('No','Yes','Yes','Yes','$question','$num_options','$option1','$option2','$option3','$option4','$option5','$option6','$option7','$option8')";
                                                          $result3 = mysql_query($sql3);
                                                          echo "Success! <b>$new_pollname</b>, Poll has been created!<br><br>.";
                                                          }
                             if($_POST['dropdown'] == ""){
                                     echo "<font face=\"Tahoma\" size=\"2\">Select the amount of options this poll will have.<br><br>";
                                     echo "<form method=\"POST\" action=\"admin.php?action=create\">";
                                     echo "  <select size=\"1\" name=\"dropdown\">";
                                     echo "  <option>1</option>";
                                     echo "  <option>2</option>";
                                     echo "  <option>3</option>";
                                     echo "  <option>4</option>";
                                     echo "  <option>5</option>";
                                     echo "  <option>6</option>";
                                     echo "  <option>7</option>";
                                     echo "  <option>8</option>";
                                     echo "  </select>";
                                     echo "  <input type=\"submit\" value=\"Submit\" name=\"B1\"></p>";
                                     echo "</form>";
                                     }else{


                             echo "<font face=\"Tahoma\" size=\"2\">Create new. <font color=\"FF0000\">(Do NOT! enter any spaces or symbols for the poll name.)</font><br>    ";
                             echo "<form method=\"POST\" action=\"admin.php?action=create\">";
                             echo "  <font size=\"2\" face=\"Tahoma\"> Poll name </font>";
                             echo "  <input type=\"text\" name=\"pollname\" size=\"20\">";
                             echo "  <hr>  ";
                             echo "  <font size=\"2\" face=\"Tahoma\"> Poll Question </font>";
                             echo "<input type=\"text\" name=\"question\" size=\"20\">";
                             echo "<hr>";
                             $i = 1;
                             $num_options = $_POST['dropdown'];
                             while ($i <= $num_options) {

                             echo "<font face=\"Tahoma\"><font size=\"2\"> Option $i </font>";
                             echo "<input type=\"text\" name=\"option$i\" size=\"20\"><font size=\"2\">";
                             echo "<br>                ";

                             $i++;
                             }
                             echo "<input type=\"hidden\" value=\"$num_options\" name=\"num_options\">";
                             echo "  <br><input type=\"submit\" value=\"Submit\" name=\"B1\">";
                             echo "</form>";


                                     }
                     }elseif($_GET[action] == "edit"){
                                   if($_POST['poll'] == ""){
                             $result = mysql_list_tables($database);
                                       echo "Select a poll to edit.";
                                       echo "<form method=\"POST\" action=\"admin.php?action=edit\">";
                             while ($row = mysql_fetch_row($result)) {
                                     if($row[0] == "$admintable"){
                                     } elseif ($row[0] == "poll"){
                                               } else {
                             list($asdasdsa, $table) = split("_",$row[0],2);
                             if($asdasdsa == "poll"){
                             echo "<input type=\"radio\" value=\"$table\" name=\"poll\"> <a href=\"javascript:void(window.open('index.php?poll=$table','open_window','status, scrollbars, resizable, dependent, width=200, height=250, left=0, top=0'));\">$table</a>";
                             echo "<br>";
                             }
                             }
                             }
                             echo "<input type=\"submit\" value=\"Edit\" name=\"B1\">";
                             echo "</form>";
                             echo "<br>";
                             mysql_free_result($result);
                                               }else{

                                                        if($_POST['tmppoll'] == ""){

                                                                echo "<form method=\"POST\" action=\"admin.php?action=edit\">";
                                                                echo "<input type=\"hidden\" name=\"tmppoll\" value=\"$_POST[poll]\">";
                                                                echo "<input type=\"hidden\" name=\"poll\" value=\"$_POST[poll]\">";
                                                                $sql = "SELECT showpercent FROM poll_" . $_POST[poll];
                                                                $result = mysql_query($sql)
                                                                        or die ("Couldn't execute query.");
                                                                $sql2 = "SELECT showvotes FROM poll_" . $_POST[poll];
                                                                $result2 = mysql_query($sql2)
                                                                        or die ("Couldn't execute query.");
                                                                $sql3 = "SELECT showtotalvotes FROM poll_" . $_POST[poll];
                                                                $result3 = mysql_query($sql3)
                                                                        or die ("Couldn't execute query.");
                                                                $sql4 = "SELECT question FROM poll_" . $_POST[poll];
                                                                $result4 = mysql_query($sql4)
                                                                        or die ("Couldn't execute query.");
                                                                $sql5 = "SELECT num_options FROM poll_" . $_POST[poll];
                                                                $result5 = mysql_query($sql5)
                                                                        or die ("Couldn't execute query.");
                                                                $sql6 = "SELECT multiple_votes FROM poll_" . $_POST[poll];
                                                                $result6 = mysql_query($sql6)
                                                                        or die ("Couldn't execute query.");

                                                                $optionsql1 = "SELECT option1 FROM poll_" . $_POST[poll];
                                                                $optionresult1 = mysql_query($optionsql1)
                                                                        or die ("Couldn't execute query.");

                                                                $optionsql2 = "SELECT option2 FROM poll_" . $_POST[poll];
                                                                $optionresult2 = mysql_query($optionsql2)
                                                                        or die ("Couldn't execute query.");

                                                                $optionsql3 = "SELECT option3 FROM poll_" . $_POST[poll];
                                                                $optionresult3 = mysql_query($optionsql3)
                                                                        or die ("Couldn't execute query.");

                                                                $optionsql4 = "SELECT option4 FROM poll_" . $_POST[poll];
                                                                $optionresult4 = mysql_query($optionsql4)
                                                                        or die ("Couldn't execute query.");

                                                                $optionsql5 = "SELECT option5 FROM poll_" . $_POST[poll];
                                                                $optionresult5 = mysql_query($optionsql5)
                                                                        or die ("Couldn't execute query.");

                                                                $optionsql6 = "SELECT option6 FROM poll_" . $_POST[poll];
                                                                $optionresult6 = mysql_query($optionsql6)
                                                                        or die ("Couldn't execute query.");

                                                                $optionsql7 = "SELECT option7 FROM poll_" . $_POST[poll];
                                                                $optionresult7 = mysql_query($optionsql7)
                                                                        or die ("Couldn't execute query.");

                                                                $optionsql8 = "SELECT option8 FROM poll_" . $_POST[poll];
                                                                $optionresult8 = mysql_query($optionsql8)
                                                                        or die ("Couldn't execute query.");

                                                                $showpercent = mysql_result($result,0);
                                                                $showvotes = mysql_result($result2,0);
                                                                $showtotalvotes = mysql_result($result3,0);
                                                                $question = mysql_result($result4,0);
                                                                $num_options = mysql_result($result5,0);
                                                                $multiple_votes = mysql_result($result6,0);

                                                                $option[1] = mysql_result($optionresult1,0);
                                                                $option[2] = mysql_result($optionresult2,0);
                                                                $option[3] = mysql_result($optionresult3,0);
                                                                $option[4] = mysql_result($optionresult4,0);
                                                                $option[5] = mysql_result($optionresult5,0);
                                                                $option[6] = mysql_result($optionresult6,0);
                                                                $option[7] = mysql_result($optionresult7,0);
                                                                $option[8] = mysql_result($optionresult8,0);

                                                                echo "Poll: <b>$_POST[poll]</b><br>";
                                                                if($multiple_votes == "Yes"){
                                                                echo "<select size=\"1\" name=\"multiple_votes\">";
                                                                echo "<option>Yes</option>";
                                                                echo "<option>No</option>";
                                                                echo "</select> Allow multiple votes<br>";
                                                                } else {
                                                                echo "<select size=\"1\" name=\"multiple_votes\">";
                                                                echo "<option>No</option>";
                                                                echo "<option>Yes</option>";
                                                                echo "</select> Allow multiple votes<br>";
                                                                }
                                                                if($showpercent == "Yes"){
                                                                echo "<select size=\"1\" name=\"showpercent\">";
                                                                echo "<option>Yes</option>";
                                                                echo "<option>No</option>";
                                                                echo "</select> Show vote percentage<br>";
                                                                } else {
                                                                echo "<select size=\"1\" name=\"showpercent\">";
                                                                echo "<option>No</option>";
                                                                echo "<option>Yes</option>";
                                                                echo "</select> Show vote percentage<br>";
                                                                }
                                                                if($showvotes == "Yes"){
                                                                echo "<select size=\"1\" name=\"showvotes\">";
                                                                echo "<option>Yes</option>";
                                                                echo "<option>No</option>";
                                                                echo "</select> Show votes per result";
                                                                }else{
                                                                echo "<select size=\"1\" name=\"showvotes\">";
                                                                echo "<option>No</option>";
                                                                echo "<option>Yes</option>";
                                                                echo "</select> Show votes per result";
                                                                }
                                                                echo "<br>";
                                                                if($showtotalvotes == "Yes"){
                                                                echo "<select size=\"1\" name=\"showtotalvotes\">";
                                                                echo "<option>Yes</option>";
                                                                echo "<option>No</option>";
                                                                echo "</select> Show total votes";
                                                                }else{
                                                                echo "<select size=\"1\" name=\"showtotalvotes\">";
                                                                echo "<option>No</option>";
                                                                echo "<option>Yes</option>";
                                                                echo "</select> Show total votes";
                                                                }
                                                                echo "<br><hr>";
                                                                echo "Question: <input type=\"text\" name=\"question\" value=\"$question\"><br><hr>";
                                                                $i = 1;
                                                                while ($i <= $num_options) {
                                                                $sql3 = "SELECT option" . $i . " FROM poll_" . $poll;
                                                                $result3 = mysql_query($sql3)
                                                                           or die ("Couldn't execute query.");
                                                                $blah3 = mysql_result($result3,0);
                                                                echo "Option$i: <input type=\"text\" name=\"option$i\" value=\"$blah3\">";
                                                                echo "<br>";
                                                                $i++;
                                                                }
                                                                echo "<br><input type=\"submit\" value=\"Save\" name=\"B1\">";
                                                                echo "</form>";
                                                                }else{
                                                                $query = "UPDATE poll_" . $poll . " SET showpercent = '$_POST[showpercent]'
                                                                WHERE 1";
                                                                $query2 = "UPDATE poll_" . $poll . " SET showvotes = '$_POST[showvotes]'
                                                                WHERE 1";
                                                                $query3 = "UPDATE poll_" . $poll . " SET showtotalvotes = '$_POST[showtotalvotes]'
                                                                WHERE 1";
                                                                $query4 = "UPDATE poll_" . $poll . " SET multiple_votes = '$_POST[multiple_votes]'
                                                                WHERE 1";
                                                                $Qquery = "UPDATE poll_" . $poll . " SET question = '$_POST[question]'
                                                                WHERE 1";
                                                                $op1 = "UPDATE poll_" . $poll . " SET option1 = '$_POST[option1]'
                                                                WHERE 1";
                                                                $op2 = "UPDATE poll_" . $poll . " SET option2 = '$_POST[option2]'
                                                                WHERE 1";
                                                                $op3 = "UPDATE poll_" . $poll . " SET option3 = '$_POST[option3]'
                                                                WHERE 1";
                                                                $op4 = "UPDATE poll_" . $poll . " SET option4 = '$_POST[option4]'
                                                                WHERE 1";
                                                                $op5 = "UPDATE poll_" . $poll . " SET option5 = '$_POST[option5]'
                                                                WHERE 1";
                                                                $op6 = "UPDATE poll_" . $poll . " SET option6 = '$_POST[option6]'
                                                                WHERE 1";
                                                                $op7 = "UPDATE poll_" . $poll . " SET option7 = '$_POST[option7]'
                                                                WHERE 1";
                                                                $op8 = "UPDATE poll_" . $poll . " SET option8 = '$_POST[option8]'
                                                                WHERE 1";
                                                                $result = mysql_query($query,$connection)
                                                                          or die ("Coundn't execute query.");
                                                                $result2 = mysql_query($query2,$connection)
                                                                          or die ("Coundn't execute query.");
                                                                $result3 = mysql_query($query3,$connection)
                                                                          or die ("Coundn't execute query.");
                                                                $result4 = mysql_query($query4,$connection)
                                                                          or die ("Coundn't execute query.");
                                                                $result5 = mysql_query($Qquery,$connection)
                                                                          or die ("Coundn't execute query.");
                                                                $opresult1 = mysql_query($op1,$connection)
                                                                          or die ("Coundn't execute query.");
                                                                $opresult2 = mysql_query($op2,$connection)
                                                                          or die ("Coundn't execute query.");
                                                                $opresult3 = mysql_query($op3,$connection)
                                                                          or die ("Coundn't execute query.");
                                                                $opresult4 = mysql_query($op4,$connection)
                                                                          or die ("Coundn't execute query.");
                                                                $opresult5 = mysql_query($op5,$connection)
                                                                          or die ("Coundn't execute query.");
                                                                $opresult6 = mysql_query($op6,$connection)
                                                                          or die ("Coundn't execute query.");
                                                                $opresult7 = mysql_query($op7,$connection)
                                                                          or die ("Coundn't execute query.");
                                                                $opresult8 = mysql_query($op8,$connection)
                                                                          or die ("Coundn't execute query.");

                                                                          echo "Poll updated.";
                                                                        }
                                                       }

                     }elseif($_GET[action] == "delete"){
                                   if($_POST['poll'] == ""){
                                   echo "Delete existing.<font color=\"#FF0000\"> (Caution poll can NOT! be recovered.)<br></font>";
                                       echo "<form method=\"POST\" action=\"admin.php?action=delete\">";
                                   $result = mysql_list_tables($database);
                                   while ($row = mysql_fetch_row($result)) {
                                   if($row[0] == "$admintable"){
                                      } elseif ($row[0] == "poll"){
                                             } else {
                                   list($asdasdsa, $table) = split("_",$row[0],2);
                                   if($asdasdsa == "poll"){
                                   echo "<input type=\"radio\" value=\"$table\" name=\"poll\"> <a href=\"\" onclick=\"window.open('index.php?poll=$table', 'newwindow','width=200,height=250','resizable=yes')\"> $table</a>";
                                   echo "<br>";
                                   }
                                               }
                                   }
                                   echo "<input type=\"submit\" value=\"Delete\" name=\"B1\">";
                                   echo "</form>";
                                   } else {
                                   $sql = "DROP TABLE poll_" . $_POST['poll'];
                                   $result = mysql_query($sql)
                                   or die ("Couldn't execute query.");
                                   echo "Poll deleted!.";
                                           }
                             }elseif($_GET[action] == "code"){
                                                          echo "<font size=\"2\" face=\"Times New Roman\"> </font><font face=\"Arial\" size=\"2\">Select a poll from the dropdown list.";
                                                          echo "<br>";
                                                          echo "</font>";
                                                          echo "<br>";
                                                          echo "<form method=\"POST\" action=\"admin.php?action=code\">";
                                                          echo "  <select size=\"1\" name=\"dropdown\">";
                             $result = mysql_list_tables($database);
                                                          while ($row = mysql_fetch_row($result)) {
                                     if($row[0] == "admin"){
                                     } elseif ($row[0] == "poll"){
                                               } else {
                             list($asdasdsa, $table) = split("_",$row[0],2);
                             if($asdasdsa == "poll"){
                             echo "<option>$table</option>";
                             }
                             }
                             }

                                                          echo "  </select>";
                                                          echo "  <input type=\"submit\" value=\"Generate\" name=\"source\"><br>";
                                                          if($_POST['dropdown'] == ""){
                                                                  } else {

                                                          echo "  <hr>";
                                                          echo "  <font face=\"Arial\" size=\"2\">";
                                                          echo "<br>  ";
                                                          echo "  <textarea rows=\"6\" name=\"S1\" cols=\"29\">";
                                                          echo "<iframe src=\"http://" . $domain . $directory . "index.php?poll=$_POST[dropdown]\" width=\"148\" height=\"192\" name=\"poll\" border=\"0\" frameborder=\"0\">";
                                                          echo "Your browser sucks! get a new one!";
                                                          echo "</iframe>";
                                                          echo "  </textarea>";
                                                          echo "  <br>";
                                                          echo "<br><font size=\"2\" face=\"Times New Roman\"> </font><font face=\"Arial\" size=\"2\">Paste ";
                                                          echo "  the code below into your website.";
                                                          echo "<br><font size=\"2\" face=\"Times New Roman\"> </font> Alternatively you could create your own, ";
                                                          echo "  simply create an iframe and point it to Yourdomain.com/votingpoll/?action=POLLNAME. ";
                                                          echo "  <b>POLLNAME</b> being the name of the poll you wish to show.</font></form>";
                                                                   }
                             }

            } else {
            echo "<b><font face=\"Tahoma\" size=\"2\">Administration Login: </font></b>";
            echo "<br><br>";
            echo "<form method=\"POST\" action=\"admin.php\">";
            echo "  <font face=\"Tahoma\" size=\"2\"> Username";
            echo "  </font>";
            echo "  <font face=\"Times New Roman\">";
            echo "  <br>";
            echo "  </font><input type=\"text\" name=\"name\" size=\"20\">";
            echo "  <br>";
            echo "  <font face=\"Tahoma\" size=\"2\"> Password</font>";
            echo "  <br>";
            echo "  <input type=\"password\" name=\"pass\" size=\"20\">";
            echo "  <br>";
            echo "  <input type=\"submit\" value=\"Submit\" name=\"B1\">";
            echo "</form>";
            }
    ?>
</font>
    <p></p>
        </td>
    <td width="9">&nbsp;</td>
  </tr>
</table>
<table border="0" cellpadding="0" cellspacing="1" style="border-collapse: collapse" bordercolor="#111111" width="594" height="27" background="images/index_06.jpg">
  <tr>
    <td width="592">&nbsp;</td>
  </tr>
</table>
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
</body>
</html>