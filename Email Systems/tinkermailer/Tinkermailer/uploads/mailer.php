<?php

$email = trim($_POST['email']);
$name = trim($_POST['name']);
$siteurl = trim($_POST['siteurl']);
$sitename = trim($_POST['sitename']);
$comments = trim($_POST['comments']);

if ($email=="" || $comments=="" || $name=="" || $siteurl=="" || $sitename=="")
{
    echo "Blank submission or you didn't fill in some fields. Go back.";
}
else {

if(!eregi('^([._a-z0-9-]+[._a-z0-9-]*)@(([a-z0-9-]+\.)*([a-z0-9-]+)(\.[a-z]{2,3})?)$', $email)) {

    echo 'That does not look like a valid email address, please re-enter.';
    exit;

}

include ('config.php'); 

    mail("$youremail", "$subject", $message, $headers); 

    echo "Thank you $name for your e-mail. It have now been sent. You should hear back from me in the next 24-48 hours. Have a wonderful day :-)";

} 
?>
