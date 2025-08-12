<?php
include 'config.php';

list($cookie, $tmpname) =
   split("!", $_COOKIE[auth], 2);

if($cookie == "fook"){
$connection = mysql_connect($hostname, $user, $pass)
or die(mysql_error());
$db = mysql_select_db($database, $connection)
        or die(mysql_error());

        $sqlcheckaccount = "SELECT username FROM $userstable
        WHERE username = '$tmpname'";

        $resultcheckaccount = mysql_query($sqlcheckaccount)
        or die ("Couldn't execute query.");
        $numcheckaccount = mysql_num_rows($resultcheckaccount);
        if($numcheckaccount == 0){
                                  echo "Error, Username doesnt exist.";
                                  die;
                                  }        
echo "<font face=\"Arial\" size=\"2\">Welcome <b>$tmpname</b>, to the members section </font>";
echo "<br><a href=\"index.php?action=signout\"><font face=\"Arial\" size=\"2\">Sign Out.</a> | <a href=\"members.php\">View all members.</a> | <a href=\"profile.php\">Edit your profile.</a></font><br><br>";
}else{
header ("Location: http://" . $domain . $directory . "login.php");
exit;
}

$name = $_GET['name'];

$sql = "SELECT username FROM $userstable
        WHERE username = '$name'";
$result = mysql_query($sql)
        or die ("Couldn't execute query.");
$num = mysql_num_rows($result);
if ($num == 1) {
                $tmpsql = "SELECT realname FROM $userstable
                WHERE username = '$name'";
                $tmpresult = mysql_query($tmpsql)
                or die ("Couldn't execute query.");
                $tmprealname = mysql_result($tmpresult,0);
                $tmprealname = strip_tags($tmprealname);

                $tmpsql2 = "SELECT realage FROM $userstable
                WHERE username = '$name'";
                $tmpresult2 = mysql_query($tmpsql2)
                or die ("Couldn't execute query.");
                $tmprealage = mysql_result($tmpresult2,0);
                $tmprealage = strip_tags($tmprealage);

                $tmpsql3 = "SELECT gender FROM $userstable
                WHERE username = '$name'";
                $tmpresult3 = mysql_query($tmpsql3)
                or die ("Couldn't execute query.");
                $tmpgender = mysql_result($tmpresult3,0);
                $tmpgender = strip_tags($tmpgender);

                $tmpsql4 = "SELECT location FROM $userstable
                WHERE username = '$name'";
                $tmpresult4 = mysql_query($tmpsql4)
                or die ("Couldn't execute query.");
                $tmplocation = mysql_result($tmpresult4,0);
                $tmplocation = strip_tags($tmplocation);

                $tmpsql5 = "SELECT favouritecolour FROM $userstable
                WHERE username = '$name'";
                $tmpresult5 = mysql_query($tmpsql5)
                or die ("Couldn't execute query.");
                $tmpfavouritecolour = mysql_result($tmpresult5,0);
                $tmpfavouritecolour = strip_tags($tmpfavouritecolour);

                $tmpsql6 = "SELECT homepage FROM $userstable
                WHERE username = '$name'";
                $tmpresult6 = mysql_query($tmpsql6)
                or die ("Couldn't execute query.");
                $tmphomepage = mysql_result($tmpresult6,0);
                $tmphomepage = strip_tags($tmphomepage);

                $tmpsql7 = "SELECT link FROM $userstable
                WHERE username = '$name'";
                $tmpresult7 = mysql_query($tmpsql7)
                or die ("Couldn't execute query.");
                $tmplink = mysql_result($tmpresult7,0);
                $tmplink = strip_tags($tmplink);
echo "<p><font face=\"Arial\" size=\"2\">Profile for $name</font></p>";
echo "<table border=\"0\" cellspacing=\"1\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\" height=\"133\">";
echo "  <tr>";
echo "    <td width=\"25%\" height=\"65\" valign=\"top\"><font size=\"2\" face=\"Arial\"> <b>Real ";
echo "    Name:</b> $tmprealname</font></td>";
echo "    <td width=\"25%\" height=\"65\" valign=\"top\"><font size=\"2\" face=\"Arial\"> <b>Age:</b> ";
echo "    $tmprealage</font></td>";
echo "    <td width=\"50%\" height=\"65\" valign=\"top\"><font size=\"2\" face=\"Arial\"> ";
echo "    <b>Gender:</b> $tmpgender</font></td>";
echo "  </tr>";
echo "  <tr>";
echo "    <td width=\"25%\" height=\"65\" valign=\"top\"><font size=\"2\" face=\"Arial\"> ";
echo "    <b>Location:</b> $tmplocation</font></td>";
echo "    <td width=\"25%\" height=\"65\" valign=\"top\"><font size=\"2\" face=\"Arial\"> ";
echo "    <b>Favourite Colour:</b> $tmpfavouritecolour</font></td>";
echo "    <td width=\"50%\" height=\"65\" valign=\"top\"><font size=\"2\" face=\"Arial\"> ";
echo "    <b>Homepage:</b> <a href=\"http://$tmphomepage\" target=\"_NEW\">http://$tmphomepage</a></font><p><font size=\"2\" face=\"Arial\"> <b>Cool Link:</b> <a href=\"http://$tmplink\" target=\"_NEW\">http://$tmplink</a></font></td>";
echo "  </tr>";
echo "</table>";
} else {
        echo "<font face=\"Arial\" size=\"2\">Error, username not found.";
        }
?>