<?
ob_start();

session_start();
header("Cache-control: private");



include("conn.php");





// get info from form
$luser = $_POST['Username'];
$lpass = $_POST['Password'];



//query database
$sql = "SELECT * FROM account WHERE Usern = '$luser' and Passn = '$lpass'";
$query = mysql_query($sql) or die("Cannot query the database.<br>" . mysql_error());


// look and find match and place cookies and redirect


while($result = mysql_fetch_array($query)) {

$ID = stripslashes($result["AID"]);
setcookie("ID",$ID,time()+3600);


header("Location: mainf.php");




}


// if pass incorrect redirect

if (! mysql_num_rows($query)) {

header("Location: index.php?error=invalidpass");

}

 ?>
