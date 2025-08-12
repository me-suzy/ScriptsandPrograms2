<?php
include 'config.php';

$tmp = $_GET['action'];
if($tmp == "signout"){
$cookie_name = "downloadauth";
$cookie_value = "";
$cookie_expire = "0";
$cookie_domain = $domain;
setcookie($cookie_name, $cookie_value, $cookie_expire, "/", $cookie_domain, 0);
header ("Location: http://" . $domain . $directory . "admin.php");
}

if($_POST['name'] == ""){
        $loggedin = "false";
        }else {

$sql = "SELECT * FROM $admintable WHERE user = '$_POST[name]' AND pass = '$_POST[pass]'";
$result = mysql_query($sql)
        or die ("Couldn't execute query.");
$num = mysql_num_rows($result);
if($num >= 1){

$cookie_name = "downloadauth";
$cookie_value = "fook!";
$cookie_expire = "0";
$cookie_domain = $domain;

setcookie($cookie_name, $cookie_value, $cookie_expire, "/", $cookie_domain, 0);
header ("Location: http://" . $domain . $directory . "admin.php");
}
}
if($_COOKIE['downloadauth'] == "fook!"){
$loggedin = "true";
}
?>
<html>
<body bgcolor="#FFFFFF" leftmargin="4" topmargin="0" marginwidth="0" marginheight="0">
<?php
include 'config.php';
echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"685\" background=\"images/download_02.jpg\" height=\"74\">\n";
echo "  <tr>\n";
echo "    <td width=\"683\" height=\"73\">&nbsp;</td>\n";
echo "  </tr>\n";
echo "</table>\n";
echo "<table border=\"0\" cellpadding=\"3\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"685\">\n";
echo "  <tr>\n";
echo "    <td width=\"140\" background=\"images/download_04.jpg\" valign=\"top\">\n";
if($loggedin == "true"){
echo "    <font size=\"2\" face=\"Arial\">&nbsp;Category</font><br>\n";
echo "    <font size=\"2\" face=\"Arial\">&nbsp; <a href=\"?option=categoryadd\">Add</a></font><br>\n";
echo "    <font size=\"2\" face=\"Arial\">&nbsp; <a href=\"?option=categoryedit\">Edit</a></font><br>\n";
echo "    <font size=\"2\" face=\"Arial\">&nbsp; <a href=\"?option=categorydelete\">Delete</a></font><br>\n";
echo "    <br>\n";
echo "    <font size=\"2\" face=\"Arial\">&nbsp;Downloads<br>\n";
echo "    <font size=\"2\" face=\"Arial\">&nbsp; <a href=\"?option=downloadadd\">Add</a></font><br>\n";
echo "    <font size=\"2\" face=\"Arial\">&nbsp; <a href=\"?option=downloadedit\">Edit</a></font><br>\n";
echo "    <font size=\"2\" face=\"Arial\">&nbsp; <a href=\"?option=downloaddelete\">Delete</a></font>\n";
echo "    <br><br><font size=\"2\" face=\"Arial\">&nbsp; <a href=\"?action=signout\">Sign out</a></font>\n";
echo "    <br><br><font size=\"2\" face=\"Arial\">&nbsp; <a href=\"http://network-13.com\">Network-13</a></font>\n";
} else {
echo "&nbsp;<font size=\"2\" face=\"Arial\">        Please login.";
}
echo "    </td>\n";
echo "    <td width=\"539\" valign=\"top\">\n";


if($loggedin == "true"){
if($_GET['option'] == "categoryadd"){
          if($_POST['catname'] == ""){
                echo "<form method=\"POST\" action=\"?option=categoryadd\">\n";
                echo "  <font face=\"Arial\" size=\"2\">Add a new category.</font></p>\n";
                echo "  <p><font face=\"Arial\"><font size=\"2\">Name: </font>\n";
                echo "  <input type=\"text\" name=\"catname\" size=\"20\"></font></p>\n";
                echo "  <p><font face=\"Arial\"><input type=\"submit\" value=\"Add\" name=\"B1\"></font></p>\n";
                echo "</form>\n";
          } else {
                $catname = $_POST['catname'];
                $sql = "CREATE TABLE `$catname`(name varchar(255),description TEXT,version varchar(255),demo varchar(255),downloadurl varchar(255),fileid varchar(255),downloads varchar(255))";
                $query = mysql_query($sql) or die ("Couldn't execute query1.");
                echo "Catagory created.";
                $sql2 = "INSERT INTO `$catname` (fileid) VALUES ('1')";
                $query2 = mysql_query($sql2) or die ("Couldn't execute query2.");
        }
}
if($_GET['option'] == "categoryedit"){
                       if($_POST['newtable'] == ""){
                       if($_POST['dropdown'] == ""){
                             $result = mysql_list_tables($database);
                             echo "<form method=\"POST\" action=\"?option=categoryedit\">";
                             echo "<select size=\"1\" name=\"dropdown\">";
                             while ($row = mysql_fetch_row($result)) {
                                     if($row[0] == "admin"){
                                     } elseif ($row[0] == "admin"){
                                               } else {
                                               echo "<option>$row[0]</option>";
                                               echo "<br>";
                             }
                             }
                             echo "</select><input type=\"submit\" value=\"Edit\" name=\"S1\"><br>";
                             echo "</form>";
                             } else {
                             echo "<form method=\"POST\" action=\"?option=categoryedit\">";
                             echo "<input type=\"text\" name=\"newtable\" size=\"20\" value=\"$_POST[dropdown]\">";
                             echo "<input type=\"hidden\" name=\"oldname\" value=\"$_POST[dropdown]\">";
                             echo "<br><input type=\"submit\" value=\"Save\" name=\"source\"><br>";
                             echo "</form>";
                             }
                             }else{
                                     $sql3 = "ALTER TABLE `$_POST[oldname]` RENAME `$_POST[newtable]`";
                                     $query = mysql_query($sql3) or die ("Couldn't execute query.");
                                     echo "Updated!.";
                             }
}
if($_GET['option'] == "categorydelete"){
        echo "Delete category";
                             if($_POST['dropdown'] == ""){
                             $result = mysql_list_tables($database);
                             echo "<br><font color=\"#FF0000\">Warning! if you delete a catgory all downloads within that category will be lost.";
                             echo "<form method=\"POST\" action=\"?option=categorydelete\">";
                             echo "<select size=\"1\" name=\"dropdown\">";
                             while ($row = mysql_fetch_row($result)) {
                                     if($row[0] == "admin"){
                                     } elseif ($row[0] == "admin"){
                                               } else {
                                               echo "<option>$row[0]</option>";
                                               echo "<br>";
                             }
                             }
                             echo "</select><input type=\"submit\" value=\"Delete\" name=\"S1\"><br>";
                             echo "</form>";
                             } else {
                                     $sql = "DROP TABLE $_POST[dropdown]";
                                     $query = mysql_query($sql) or die ("Couldn't execute query.");
                                     echo "<br>Category deleted.";
                                     }

        }
if($_GET['option'] == "downloadadd"){
        echo "<font face=\"Arial\" size=\"2\">Add download";

        if($_POST['name'] == ""){

                echo "<form method=\"POST\" action=\"?option=downloadadd\">\n";
                echo "   <table border=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" height=\"152\">\n";
                echo "    <tr>\n";
                echo "      <td width=\"15%\" height=\"22\"><font face=\"Arial\" size=\"2\">Name: </font></td>\n";
                echo "      <td width=\"85%\" height=\"22\"><font face=\"Arial\">\n";
                echo "      <input type=\"text\" name=\"name\" size=\"28\"></font></td>\n";
                echo "    </tr>\n";
                echo "    <tr>\n";
                echo "      <td width=\"15%\" height=\"22\"><font face=\"Arial\" size=\"2\">Description:\n";
                echo "      </font></td>\n";
                echo "      <td width=\"85%\" height=\"22\"><font face=\"Arial\">\n";
                echo " <textarea rows=\"4\" name=\"description\" cols=\"15\"></textarea>";
                echo "</font></td>\n";
                echo "    </tr>\n";
                echo "    <tr>\n";
                echo "      <td width=\"15%\" height=\"21\"><font face=\"Arial\" size=\"2\">Version: </font>\n";
                echo "      </td>\n";
                echo "      <td width=\"85%\" height=\"21\"><font face=\"Arial\">\n";
                echo "      <input type=\"text\" name=\"version\" size=\"9\"></font></td>\n";
                echo "    </tr>\n";
                echo "    <tr>\n";
                echo "      <td width=\"15%\" height=\"22\"><font face=\"Arial\" size=\"2\">Demo: </font></td>\n";
                echo "      <td width=\"85%\" height=\"22\"><font face=\"Arial\">\n";
                echo "      <input type=\"text\" name=\"demo\" size=\"33\"></font></td>\n";
                echo "    </tr>\n";
                echo "    <tr>\n";
                echo "      <td width=\"15%\" height=\"22\"><font face=\"Arial\" size=\"2\">Download URL:\n";
                echo "      </font></td>\n";
                echo "      <td width=\"85%\" height=\"22\"><font face=\"Arial\">\n";
                echo "      <input type=\"text\" name=\"downloadurl\" size=\"33\"></font></td>\n";
                echo "    </tr>\n";
                echo "    <tr>\n";

                echo "    <tr>\n";
                echo "      <td width=\"15%\" height=\"22\"><font face=\"Arial\" size=\"2\">Category:\n";
                echo "      </font></td>\n";
                echo "      <td width=\"85%\" height=\"22\"><font face=\"Arial\">\n";
                             echo "<select size=\"1\" name=\"dropdown\">";
                             $result = mysql_list_tables($database);
                             while ($row = mysql_fetch_row($result)) {
                                     if($row[0] == "admin"){
                                     } elseif ($row[0] == "admin"){
                                               } else {
                                               echo "<option>$row[0]</option>";
                                               echo "<br>";
                             }
                             }
                             echo "</select>";
                echo "    </tr>\n";
                echo "    <tr>\n";

                echo "      <td width=\"15%\" height=\"26\">&nbsp;</td>\n";
                echo "      <td width=\"85%\" height=\"26\"><font face=\"Arial\">\n";
                echo "      <input type=\"submit\" value=\"Add\" name=\"B1\"></font></td>\n";
                echo "    </tr>\n";
                echo "  </table>\n";
                echo "</form>\n";
        } else {
                $sql5 = "SELECT fileid FROM $_POST[dropdown]";
                $result5 = mysql_query($sql5)
                or die ("Couldn't execute query.");
                $fileid = mysql_result($result5,0);
                $fileid = $fileid;

                $name = $_POST['name'];
                $description = $_POST['description'];
                $version = $_POST['version'];
                $demo = $_POST['demo'];
                $downloadurl = $_POST['downloadurl'];

                $sql = "INSERT INTO `$_POST[dropdown]` (name,description,version,demo,downloadurl,fileid,downloads) VALUES ('$name','$description','$version','$demo','$downloadurl','$fileid','0')";
                $query = mysql_query($sql) or die ("Couldn't execute query.");
                $sql2 = "UPDATE `$_POST[dropdown]` SET fileid = $fileid + 1 WHERE `name` IS NULL LIMIT 1";
                $query2 = mysql_query($sql2) or die ("Couldn't execute query.");
                echo "<br>Download added!";
                }
        }
if($_GET['option'] == "downloadedit"){
        echo "Edit download";

                   if($_POST['dropdown'] == ""){
                             $result = mysql_list_tables($database);
                             echo "<br>Select a category.";
                             echo "<form method=\"POST\" action=\"?option=downloadedit\">";
                             echo "<select size=\"1\" name=\"dropdown\">";
                             while ($row = mysql_fetch_row($result)) {
                                     if($row[0] == "admin"){
                                     } elseif ($row[0] == "admin"){
                                               } else {
                                               echo "<option>$row[0]</option>";
                                               echo "<br>";
                             }
                             }
                             echo "</select><input type=\"submit\" value=\"Edit\" name=\"S1\"><br>";
                             echo "</form>";

                   } else {
                                     if($_POST['R1'] == ""){
                                               $sql =  "SELECT * FROM $_POST[dropdown] ORDER BY fileid DESC";
                                               $result2 = mysql_query($sql);
                                                       echo "<form method=\"POST\" action=\"?option=downloadedit\">\n";
                                                       while($row = mysql_fetch_array($result2)){
                                                             $tmp = $row['name'];
                                                             if($tmp == ""){
                                                                     } else {
                                                       echo "<input type=\"radio\" value=\"$row[fileid]\" name=\"R1\">$row[name]\n";
                                                       echo "<br>";
                                                       }
                                                       }
                                                       echo "<input type=\"hidden\" name=\"dropdown\" value=\"$_POST[dropdown]\">";
                                                       echo "<input type=\"submit\" value=\"Submit\" name=\"B1\">";
                                                       echo "</form>\n";

                                                       } else {

                                                       if($_POST['name2'] == ""){
                $sql2 = "SELECT name FROM $_POST[dropdown] WHERE fileid = $_POST[R1]";
                $result2 = mysql_query($sql2);
                $sql3 = "SELECT description FROM $_POST[dropdown] WHERE fileid =  $_POST[R1]";
                $result3 = mysql_query($sql3);
                $sql4 = "SELECT version FROM $_POST[dropdown] WHERE fileid =  $_POST[R1]";
                $result4 = mysql_query($sql4);
                $sql5 = "SELECT demo FROM $_POST[dropdown] WHERE fileid =  $_POST[R1]";
                $result5 = mysql_query($sql5);
                $sql6 = "SELECT downloadurl FROM $_POST[dropdown] WHERE fileid =  $_POST[R1]";
                $result6 = mysql_query($sql6);
                $sql7 = "SELECT fileid FROM $_POST[dropdown] WHERE fileid =  $_POST[R1]";
                $result7 = mysql_query($sql7);

                $name = mysql_result($result2,0);
                $description = mysql_result($result3,0);
                $version = mysql_result($result4,0);
                $demo = mysql_result($result5,0);
                $downloadurl = mysql_result($result6,0);
                $fileid = mysql_result($result7,0);



                echo "<form method=\"POST\" action=\"?option=downloadedit\">\n";
                echo "   <table border=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" height=\"152\">\n";
                echo "    <tr>\n";
                echo "      <td width=\"15%\" height=\"22\"><font face=\"Arial\" size=\"2\">Name: </font></td>\n";
                echo "      <td width=\"85%\" height=\"22\"><font face=\"Arial\">\n";
                echo "      <input type=\"text\" name=\"name2\" size=\"28\" value=\"$name\"></font></td>\n";
                echo "    </tr>\n";
                echo "    <tr>\n";
                echo "      <td width=\"15%\" height=\"22\"><font face=\"Arial\" size=\"2\">Description:\n";
                echo "      </font></td>\n";
                echo "      <td width=\"85%\" height=\"22\"><font face=\"Arial\">\n";
                echo " <textarea rows=\"4\" name=\"description\" cols=\"15\">$description</textarea>";
                echo "</font></td>\n";
                echo "    </tr>\n";
                echo "    <tr>\n";
                echo "      <td width=\"15%\" height=\"21\"><font face=\"Arial\" size=\"2\">Version: </font>\n";
                echo "      </td>\n";
                echo "      <td width=\"85%\" height=\"21\"><font face=\"Arial\">\n";
                echo "      <input type=\"text\" name=\"version\" size=\"9\" value=\"$version\"></font></td>\n";
                echo "    </tr>\n";
                echo "    <tr>\n";
                echo "      <td width=\"15%\" height=\"22\"><font face=\"Arial\" size=\"2\">Demo: </font></td>\n";
                echo "      <td width=\"85%\" height=\"22\"><font face=\"Arial\">\n";
                echo "      <input type=\"text\" name=\"demo\" size=\"33\" value=\"$demo\"></font></td>\n";
                echo "    </tr>\n";
                echo "    <tr>\n";
                echo "      <td width=\"15%\" height=\"22\"><font face=\"Arial\" size=\"2\">Download URL:\n";
                echo "      </font></td>\n";
                echo "      <td width=\"85%\" height=\"22\"><font face=\"Arial\">\n";
                echo "      <input type=\"text\" name=\"downloadurl\" size=\"33\" value=\"$downloadurl\"></font></td>\n";
                echo "    </tr>\n";
                echo "    <tr>\n";

                echo "    <tr>\n";
                echo "      <td width=\"15%\" height=\"22\"><font face=\"Arial\" size=\"2\">Category:\n";
                echo "      </font></td>\n";
                echo "      <td width=\"85%\" height=\"22\"><font face=\"Arial\">\n";
                             echo "<select size=\"1\" name=\"dropdown\">";
                             $result = mysql_list_tables($database);
                             echo "<option>$_POST[dropdown]</option>";
                             echo "</select>";
                echo "    </tr>\n";
                echo "    <tr>\n";

                echo "      <td width=\"15%\" height=\"26\">&nbsp;</td>\n";
                echo "      <td width=\"85%\" height=\"26\"><font face=\"Arial\">\n";
                echo "<input type=\"hidden\" name=\"R1\" value=\"$_POST[dropdown]\">";
                echo "<input type=\"hidden\" name=\"fileid\" value=\"$fileid\">";
                echo "      <input type=\"submit\" value=\"Save\" name=\"B2\"></font></td>\n";
                echo "    </tr>\n";
                echo "  </table>\n";
                echo "</form>\n";

                           } else {

                                   $sql = "UPDATE `$_POST[R1]` SET `name` = '$_POST[name2]' WHERE fileid = $_POST[fileid]";
                                   $sql2 = "UPDATE `$_POST[R1]` SET `description` = '$_POST[description]' WHERE fileid = $_POST[fileid]";
                                   $sql3 = "UPDATE `$_POST[R1]` SET `version` = '$_POST[version]' WHERE fileid = $_POST[fileid]";
                                   $sql4 = "UPDATE `$_POST[R1]` SET `demo` = '$_POST[demo]' WHERE fileid = $_POST[fileid]";
                                   $sql5 = "UPDATE `$_POST[R1]` SET `downloadurl` = '$_POST[downloadurl]' WHERE fileid = $_POST[fileid]";

                                   $query = mysql_query($sql) or die ("Couldn't execute query");
                                   $query2 = mysql_query($sql2) or die ("Couldn't execute query2");
                                   $query3 = mysql_query($sql3) or die ("Couldn't execute query3");
                                   $query4 = mysql_query($sql4) or die ("Couldn't execute query4");
                                   $query5 = mysql_query($sql5) or die ("Couldn't execute query5");

                                   echo "Updated.";
                                   }
                           }
        }
}
if($_GET['option'] == "downloaddelete"){
        echo "Delete download";


                   if($_POST['dropdown'] == ""){
                             $result = mysql_list_tables($database);
                             echo "<br>Select a category.";
                             echo "<form method=\"POST\" action=\"?option=downloaddelete\">";
                             echo "<select size=\"1\" name=\"dropdown\">";
                             while ($row = mysql_fetch_row($result)) {
                                     if($row[0] == "admin"){
                                     } elseif ($row[0] == "admin"){
                                               } else {
                                               echo "<option>$row[0]</option>";
                                               echo "<br>";
                             }
                             }
                             echo "</select><input type=\"submit\" value=\"Edit\" name=\"S1\"><br>";
                             echo "</form>";

                   } else {
                                     if($_POST['R1'] == ""){
                                               $sql =  "SELECT * FROM $_POST[dropdown] ORDER BY fileid DESC";
                                               $result2 = mysql_query($sql);
                                                       echo "<form method=\"POST\" action=\"?option=downloaddelete\">\n";
                                                       while($row = mysql_fetch_array($result2)){
                                                             $tmp = $row['name'];
                                                             if($tmp == ""){
                                                                     } else {
                                                       echo "<input type=\"radio\" value=\"$row[fileid]\" name=\"R1\">$row[name]\n";
                                                       echo "<br>";
                                                       }
                                                       }
                                                       echo "<input type=\"hidden\" name=\"dropdown\" value=\"$_POST[dropdown]\">";
                                                       echo "<input type=\"submit\" value=\"Delete\" name=\"B1\">";
                                                       echo "</form>\n";
                                                       } else {
                                                       $sql = "DELETE FROM $_POST[dropdown] WHERE fileid = $_POST[R1]";
                                                       $query = mysql_query($sql);
                                                       echo "<br> Deleted";
                                                               }
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



echo "    </td>\n";
echo "  </tr>\n";
echo "</table>\n";
echo "<table border=\"0\" cellspacing=\"0\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"685\" background=\"images/download_06.jpg\" height=\"27\">\n";
echo "  <tr>\n";
echo "    <td width=\"682\" height=\"25\">&nbsp;</td>\n";
echo "  </tr>\n";
echo "</table>\n";







?>
</font>
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