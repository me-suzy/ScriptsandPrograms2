<?
require("db.php");
require("include.php");
DBinfo();



if ($email != ""){
   $email=trim($email);
   $s=substr_count($email,"@");
   $d=substr_count($email,".");
   $m=substr_count($email," ");
   if ($s==1 && $d>=1 && $m==0) {
          $email_ok = "ok";
         }
}


if ($email=="") {
Header( "Location: contact.php?action=2&name=$name&message=$message");
} elseif ($name==""){
Header( "Location: contact.php?action=1&email=$email&message=$message");
} elseif ($message==""){
Header( "Location: contact.php?action=4&name=$name&email=$email");
} elseif ($email_ok!="ok") {
Header( "Location: contact.php?action=3&name=$name&email=$email&message=$message");
} else {

mysql_connect("$DBHost","$DBUser","$DBPass");
mysql_select_db("$DBName");

$result=mysql_query("SELECT adminMail FROM mycmsadmin WHERE AdminId='1'");
$row=mysql_fetch_row($result);
$adminMail=$row[0];

 	$header="MIME-Version: 1.0\r\n";
	$header.="Content-type: text/html; charset=utf-8\r\n";

        $optional_subject="A message from a guest in your service!";

$message=nl2br($message);

$body_email = "Name: $name <br /> email: $email <br /> Message: <p>$message</p>";

$results = mail ($adminMail, $optional_subject, $body_email, $header);

Header("Location:index.php");

}

?>