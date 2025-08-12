<?
include 'config.php';

    $headers .= "MIME-Version: 1.0 \n";
    $headers .= "Content-type: text/html; charset=iso-8859-1 \n";
    $headers .= "from:AccountRecover@$domain\r\nCc:\r\nBcc:";

if($_POST[email] == ""){
echo "Username & Password recovery.<br>";
echo "<form action=\"./reset.php\" method=\"POST\">";
echo "Your email: <br><input type=\"text\" name=\"email\"><br>";
echo "<input type=\"submit\" value=\"Submit!\">";
echo "</form>";
} else {

$connection = mysql_connect($hostname, $user, $pass)
or die(mysql_error());
$db = mysql_select_db($database, $connection)
        or die(mysql_error());


$sql = "SELECT password FROM $userstable
        WHERE email = '$_POST[email]'";

$result = mysql_query($sql)
        or die ("Couldn't execute query.");

$num = mysql_num_rows($result);
if ($num == 1) {

$tmppass = mysql_result($result,0);

$sql2 = "SELECT username FROM $userstable
        WHERE email = '$_POST[email]'";

$result2 = mysql_query($sql2)
        or die ("Couldn't execute query.");

$tmpname = mysql_result($result2,0);


mail ($_POST['email'], "Username & Password request", "Your username and password for " . $domain . " is : \n" . "Username = " . $tmpname . "\n" . "Password = " . $tmppass, $headers);
echo "Success, An email has been sent to you with your requested username & password!";
} else {
echo "Error, The email address doesnt exist";
}

}
?>