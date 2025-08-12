<html>
<head>
</head>
<body bgcolor="#FFFFFF" text="#000000">
<?php
include 'config.php';

if($poll == ""){
        $poll = $_GET['poll'];
        }



$totalsql = mysql_query("SELECT * FROM poll_" . $poll)
            or die ("Couldn't execute query.");
$vote1sql = mysql_query("SELECT vote FROM poll_" . $poll . " WHERE vote = '1'")
            or die ("Couldn't execute query.");
$vote2sql = mysql_query("SELECT vote FROM poll_" . $poll . " WHERE vote = '2'")
            or die ("Couldn't execute query.");
$vote3sql = mysql_query("SELECT vote FROM poll_" . $poll . " WHERE vote = '3'")
            or die ("Couldn't execute query.");
$vote4sql = mysql_query("SELECT vote FROM poll_" . $poll . " WHERE vote = '4'")
            or die ("Couldn't execute query.");
$vote5sql = mysql_query("SELECT vote FROM poll_" . $poll . " WHERE vote = '5'")
            or die ("Couldn't execute query.");
$vote6sql = mysql_query("SELECT vote FROM poll_" . $poll . " WHERE vote = '6'")
            or die ("Couldn't execute query.");
$vote7sql = mysql_query("SELECT vote FROM poll_" . $poll . " WHERE vote = '7'")
            or die ("Couldn't execute query.");
$vote8sql = mysql_query("SELECT vote FROM poll_" . $poll . " WHERE vote = '8'")
            or die ("Couldn't execute query.");
$sql = "SELECT showpercent FROM poll_" . $poll;
$result = mysql_query($sql)
or die ("Couldn't execute query.");
$sql2 = "SELECT showvotes FROM poll_" . $poll;
$result2 = mysql_query($sql2)
or die ("Couldn't execute query.");
$sql3 = "SELECT showtotalvotes FROM poll_" . $poll;
$result3 = mysql_query($sql3);




$showpercent = mysql_result($result,0);
$showvotes = mysql_result($result2,0);
$showtotalvotes = mysql_result($result3,0);

$totalvotes = mysql_num_rows($totalsql) - 1;
$totalvote[1] = mysql_num_rows($vote1sql);
$totalvote[2] = mysql_num_rows($vote2sql);
$totalvote[3] = mysql_num_rows($vote3sql);
$totalvote[4] = mysql_num_rows($vote4sql);
$totalvote[5] = mysql_num_rows($vote5sql);
$totalvote[6] = mysql_num_rows($vote6sql);
$totalvote[7] = mysql_num_rows($vote7sql);
$totalvote[8] = mysql_num_rows($vote8sql);

if($totalvotes == 0){

$votepercent[1] = 0;
$votepercent[2] = 0;
$votepercent[3] = 0;
$votepercent[4] = 0;
$votepercent[5] = 0;
$votepercent[6] = 0;
$votepercent[7] = 0;
$votepercent[8] = 0;

$imagepercent[1] = 0;
$imagepercent[2] = 0;
$imagepercent[3] = 0;
$imagepercent[4] = 0;
$imagepercent[5] = 0;
$imagepercent[6] = 0;
$imagepercent[7] = 0;
$imagepercent[8] = 0;
        } else {

$votepercent[1] = (100 / $totalvotes * $totalvote[1]);
$votepercent[2] = (100 / $totalvotes * $totalvote[2]);
$votepercent[3] = (100 / $totalvotes * $totalvote[3]);
$votepercent[4] = (100 / $totalvotes * $totalvote[4]);
$votepercent[5] = (100 / $totalvotes * $totalvote[5]);
$votepercent[6] = (100 / $totalvotes * $totalvote[6]);
$votepercent[7] = (100 / $totalvotes * $totalvote[7]);
$votepercent[8] = (100 / $totalvotes * $totalvote[8]);

/*resized slightly so that the graph fits  */
$imagepercent[1] = (98 / $totalvotes * $totalvote[1]);
$imagepercent[2] = (98 / $totalvotes * $totalvote[2]);
$imagepercent[3] = (98 / $totalvotes * $totalvote[3]);
$imagepercent[4] = (98 / $totalvotes * $totalvote[4]);
$imagepercent[5] = (98 / $totalvotes * $totalvote[5]);
$imagepercent[6] = (98 / $totalvotes * $totalvote[6]);
$imagepercent[7] = (98 / $totalvotes * $totalvote[7]);
$imagepercent[8] = (98 / $totalvotes * $totalvote[8]);
}

$sql = "SELECT num_options FROM poll_" . $poll;
$result = mysql_query($sql)
        or die ("Poll not found.");
$blah = mysql_result($result,0);

$sq = "SELECT question FROM poll_". $poll;
$re = mysql_query($sq)
      or die ("Couldn't execute query.");
$question = mysql_result($re,0);

echo "<font size=\"2\" face=\"Tahoma\">$question<br><br>";
echo "<table border=\"0\" cellspacing=\"0\" width=\"100%=\"border-collapse: collapse\" bordercolor=\"#111111\" cellpadding=\"0\">";


$i = 1;
while ($i <= $blah) {
$sql3 = "SELECT option" . $i . " FROM poll_" . $poll;
$result3 = mysql_query($sql3)
           or die ("Couldn't execute query.");
$blah3 = mysql_result($result3,0);


echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"1\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\">";
echo "  <tr>";
echo "    <td width=\"100%\"><font size=\"1\" face=\"Tahoma\">$blah3";
if($showpercent == "Yes"){
       echo " - " . round($votepercent[$i],2) . "%";
}
echo "    </td>";
echo "  </tr>";
echo "</table>";
echo "<table border=\"0\" cellpadding=\"0\" cellspacing=\"1\" style=\"border-collapse: collapse\" bordercolor=\"#111111\" width=\"100%\">";
echo "  <tr>";
echo "    <td width=\"90%\">";
echo "    <img src=\"leftend.bmp\"><img src=\"center.bmp\" width=\"$imagepercent[$i]%\" height=\"12\"><img src=\"rightend.bmp\">";
echo "    </td>";
echo "    <td width=\"8%\">";
echo "    <p align=\"right\">";
echo "    <font size=\"1\" face=\"Tahoma\">";
if($showvotes == "Yes"){
        echo $totalvote[$i];
}
echo "</td>";
echo "  </tr>";
echo "</table>";



$i++;
}
echo "</table>";
if($showtotalvotes == "Yes"){
echo "<p align=\"right\"><i><font size=\"2\" face=\"Tahoma\">Total Votes:</i> $totalvotes</p></font>";
}
?>
</body>
</html>