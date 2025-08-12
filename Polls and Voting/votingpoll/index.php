<html>
<head>
</head>
<body bgcolor="#FFFFFF" text="#000000">
<?php
include 'config.php';

$ip = $_SERVER['REMOTE_ADDR'];

$sql = "SELECT ip FROM poll_" . $poll . " WHERE ip = '$ip'";
$result = mysql_query($sql)
        or die ("Poll not found");
$num = mysql_num_rows($result);

$sql2 = "SELECT multiple_votes FROM poll_" . $poll;
$result2 = mysql_query($sql2)
        or die ("Couldn't execute query.");
$multiple_votes = mysql_result($result2,0);

function dovote() {
$poll = $_GET['poll'];
if($_POST['R1'] == ""){

$sql = "SELECT num_options FROM poll_" . $poll;
$result = mysql_query($sql)
        or die ("Poll not found.");
$blah = mysql_result($result,0);

$sq = "SELECT question FROM poll_". $poll;
$re = mysql_query($sq)
      or die ("Couldn't execute query.");
$question = mysql_result($re,0);
$checked = "checked";
echo $question;
echo "<br>";

echo "<form method=\"POST\" action=\"index.php?poll=$poll\">";
$i = 1;
while ($i <= $blah) {
$sql2 = "SELECT option" . $i . " FROM poll_" . $poll;
$result2 = mysql_query($sql2)
           or die ("Couldn't execute query.");
$blah2 = mysql_result($result2,0);
echo "<input type=\"radio\" ";
if($checked == "checked"){
        echo "checked";
        $checked = "";
        }
echo " name=\"R1\" value=\"$i\">";
echo $blah2;
echo "<br>";
$i++;
}
echo "<input type=\"submit\" value=\"Vote\" name=\"B1\">";
echo "</form>";
echo "<br><a href=\"result.php?poll=$poll\">View results.</a>";

        } else {
$vote = $_POST['R1'];
$ip = $_SERVER['REMOTE_ADDR'];



$query = "INSERT INTO poll_" . $poll . "(vote,ip)
          VALUES ('$vote','$ip')";
$result = mysql_query($query) or die ("Coundn't execute query.");
$sq = "SELECT question FROM poll_". $poll;
$re = mysql_query($sq)
      or die ("Couldn't execute query.");
$question = mysql_result($re,0);
include 'result.php';
}
}


if ($num >= 1) {
    if($multiple_votes == "Yes"){
       dovote();
       } else {
               include 'result.php';
       }

} else {
dovote();
}

echo "<link rel=\"stylesheet\" href=\"css/login.css\" type=\"text/css\"";
?>
</body>
</html>