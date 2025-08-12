<?php
        include("var.inc.php");
        $conn = @mysql_connect($dbserver,$dbuser,$dbpass);
        if (!$conn)
                {
                        die("Sorry, database not found !");
                }
        mysql_select_db($dbname,$conn);

                {

                        $ip = $email;
                        $zeit = time ();
                        $nichtmehrgueltig = $zeit-$stehenlassen;
                        $query = "DELETE FROM demo_a_iptest WHERE timefeld <= ".$nichtmehrgueltig;
                        mysql_query($query,$conn);
                        $query = "SELECT * FROM demo_a_iptest WHERE ip = '".$ip."'";
                        $result = mysql_query($query,$conn);
                        $rows = mysql_num_rows($result);
                        if ($rows >= 1)
                                {

require('./prepend.inc.php');
if($email && $password)
        loginb();

}
                        else
                                {

require('./prepend.inc.php');
if($email && $password)
        login();


$query = "INSERT INTO demo_a_iptest VALUES (\"$ip\", $zeit)";
                                        mysql_query($query,$conn);
                                }

                }


mysql_close($conn);
?>
<?
include("./templates/main-header.txt");
?>

<br><font size="3"><center>Login failed</center><br><br>
<center><a href="passwort.php"><font size="2" color="red">Forgot password??</a></font></center>

<?
include("./templates/main-footer.txt");
?>