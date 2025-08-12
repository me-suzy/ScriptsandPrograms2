<?php

include 'config.php';


$connection = mysql_connect($hostname, $user, $pass)
or die(mysql_error());
$db = mysql_select_db($database, $connection)
        or die(mysql_error());


list($cookie, $tmpname) =
   split("!", $_COOKIE[auth], 2);

if($cookie == "fook"){
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

$realname = strip_tags($_POST[realname]);
$realage = strip_tags($_POST[realage]);
$gender = strip_tags($_POST[gender]);
$location = strip_tags($_POST[location]);
$favourtiecolour = strip_tags($_POST[favourtiecolour]);
$homepage = strip_tags($_POST[homepage]);
$homepage = str_replace ("http://", "", "$homepage");
$link = strip_tags($_POST[link]);
$link = str_replace ("http://", "", "$link");



                $tmpsql = "SELECT realname FROM $userstable
                WHERE username = '$tmpname'";
                $tmpresult = mysql_query($tmpsql)
                or die ("Couldn't execute query.");
                $tmprealname = strip_tags(mysql_result($tmpresult,0));

                $tmpsql2 = "SELECT realage FROM $userstable
                WHERE username = '$tmpname'";
                $tmpresult2 = mysql_query($tmpsql2)
                or die ("Couldn't execute query.");
                $tmprealage = strip_tags(mysql_result($tmpresult2,0));

                $tmpsql3 = "SELECT gender FROM $userstable
                WHERE username = '$tmpname'";
                $tmpresult3 = mysql_query($tmpsql3)
                or die ("Couldn't execute query.");
                $tmpgender = strip_tags(mysql_result($tmpresult3,0));

                $tmpsql4 = "SELECT location FROM $userstable
                WHERE username = '$tmpname'";
                $tmpresult4 = mysql_query($tmpsql4)
                or die ("Couldn't execute query.");
                $tmplocation = strip_tags(mysql_result($tmpresult4,0));

                $tmpsql5 = "SELECT favouritecolour FROM $userstable
                WHERE username = '$tmpname'";
                $tmpresult5 = mysql_query($tmpsql5)
                or die ("Couldn't execute query.");
                $tmpfavouritecolour = strip_tags(mysql_result($tmpresult5,0));

                $tmpsql6 = "SELECT homepage FROM $userstable
                WHERE username = '$tmpname'";
                $tmpresult6 = mysql_query($tmpsql6)
                or die ("Couldn't execute query.");
                $tmphomepage = strip_tags(mysql_result($tmpresult6,0));

                $tmpsql7 = "SELECT link FROM $userstable
                WHERE username = '$tmpname'";
                $tmpresult7 = mysql_query($tmpsql7)
                or die ("Couldn't execute query.");
                $tmplink = strip_tags(mysql_result($tmpresult7,0));

if($_POST['realname'] == ""){
        } else {
$query = "UPDATE $userstable SET realname = '$realname'
           WHERE username = '$tmpname'";
$result = mysql_query($query)
           or die ("Couldn't execute query.");
}

if($_POST['realage'] == ""){
        } else {
$query2 = "UPDATE $userstable SET realage = '$realage'
           WHERE username = '$tmpname'";
$result2 = mysql_query($query2)
           or die ("Couldn't execute query.");
}

if($_POST['gender'] == ""){
        } else {
$query2 = "UPDATE $userstable SET gender = '$gender'
           WHERE username = '$tmpname'";
$result2 = mysql_query($query2)
           or die ("Couldn't execute query.");
}

if($_POST['location'] == ""){
        } else {
$query4 = "UPDATE $userstable SET location = '$location'
           WHERE username = '$tmpname'";
$result4 = mysql_query($query4)
           or die ("Couldn't execute query.");
}

if($_POST['favouritecolour'] == ""){
        } else {
$query5 = "UPDATE $userstable SET favouritecolour = '$favouritecolour'
           WHERE username = '$tmpname'";
$result5 = mysql_query($query5)
           or die ("Couldn't execute query.");
}

if($_POST['homepage'] == ""){
        } else {
$query6 = "UPDATE $userstable SET homepage = '$homepage'
           WHERE username = '$tmpname'";
$result6 = mysql_query($query6)
           or die ("Couldn't execute query.");
}

if($_POST['link'] == ""){
        } else {
$query7 = "UPDATE $userstable SET link = '$link'
           WHERE username = '$tmpname'";
$result7 = mysql_query($query7)
           or die ("Couldn't execute query.");

}

echo "<font face=\"Arial\" size=\"2\"><b>$tmpname</b>, Please update your profile.<br>";
echo "<font face=\"Arial\" size=\"2\">Fill out your profile information below, All fields are optional.</font><br><br>";
echo "<form method=\"POST\" action=\"profile.php\">";
echo "            <font face=\"Arial\"><font size=\"2\">";
echo "            Real Name:";
echo "                <br>";
echo "            </font>";
echo "            <input type=\"text\" name=\"realname\" size=\"20\" value=\"$tmprealname\"><font size=\"2\">";
echo "    <br>";
echo "            ";
echo "            Real Age:";
echo "                <br>";
echo "        </font>";
echo "        <input type=\"text\" name=\"realage\" size=\"20\" value=\"$tmprealage\"><font size=\"2\">";
echo "    <br>";
echo "            ";
echo "            Gender:";
echo "                <br>";
echo "        </font>";
echo "        <input type=\"text\" name=\"gender\" size=\"20\" value=\"$tmpgender\"><font size=\"2\">";
echo "        <br>";
echo "            ";
echo "            Location:";
echo "                <br>";
echo "        </font>";
echo "        <input type=\"text\" name=\"location\" size=\"20\" value=\"$tmplocation\"><font size=\"2\">";
echo "    <br>";
echo "            ";
echo "            Favourite Colour:";
echo "                <br>";
echo "        </font>";
echo "        <input type=\"text\" name=\"favouritecolour\" size=\"20\" value=\"$tmpfavouritecolour\"><font size=\"2\">";
echo "    <br>";
echo "            ";
echo "            Homepage:";
echo "                <br>";
echo "        </font>";
echo "        <input type=\"text\" name=\"homepage\" size=\"20\" value=\"$tmphomepage\"><font size=\"2\">";
echo "    <br>";
echo "            ";
echo "            Cool Link: </font></font>";
echo "                <br>";
echo "        <input type=\"text\" name=\"link\" size=\"20\" value=\"$tmplink\">";
echo "    <br>";
echo "  <input type=\"submit\" value=\"Save\" name=\"B1\"></p>";
echo "</form>";
} else {
        echo "<font face=\"Arial\" size=\"2\">Error, Must be logged in to edit your profile.";
        echo "<br>Click <a href=\"login.php\">here!</a> to login.";
        }
?>