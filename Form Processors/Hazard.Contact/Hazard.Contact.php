<?php
//Edit the strings below and you're good to go!
$adminemail = 'joe@hazardcell.com';//Your email address
$thankspage = 'thanks.html';//Thanks message here
$contactpage = 'contact.html';//The page with the contact form
$invalidemail = 'The email address you entered is invalid';//The text to display if email addy is invalid
$incomplete = "The fields marked * are empty";//Text to display whenever there's an empty field
$emptyfield = '*';//To be displayed where there's an empty field
//You don't have to edit anything below here.

$id = $_POST["id"];
if (!$id) {
    $id = "main";
    }

###############################
###  This sends the message ###
###############################
if ($id == 'send') {

// get all variables and remove slashes
$name = stripslashes($_REQUEST["name"]);
$email = stripslashes($_REQUEST["emailaddress"]);
$subject = stripslashes($_REQUEST["subject"]);
$message = stripslashes($_REQUEST["message"]);

//checking the fields
if($name == ''){$error1 = "$emptyfield";}//Making sure a name is entered
if($email == ''){$error2 = "$emptyfield";}//Making sure the email addy is entered
//If Email addy is entered, then we proceed to check whether it is valid
//Borrowed this part from php.net
else{
if(eregi("^[_a-z0-9-]+(\\.[_a-z0-9-]+)*@[a-z0-9-]+(\\.[a-z0-9-]+)*(\\.[a-z]{2,4})$", trim($email))) {
$checkemail = 1;//Valid email addy
}
else{
$checkemail = 0;//Invalid email addy
}
if($checkemail == 0){
$error5 = "$invalidemail";
}
}
if($subject == ''){$error3 = "$emptyfield";}
if($message == ''){$error4 = "$emptyfield";}
if($error1 || $error2 || $error3 || $error4){
        $error = "$incomplete";
}
//This will redirect to the main page if there are errors
if ($error1 || $error2 || $error3 || $error4 || $error5) {
        $id = 'main';
        }
else{
//If everything is OK, send message
mail($adminemail,$subject,$message,"From: $name <$email>\nReply-To: $email");

// Show a successpage
$fileopen = fopen($thankspage, "r");
$page = fread($fileopen, filesize($thankspage));
echo $page;
}
}

###########################
#### The contact form #####
###########################
if ($id == 'main') {
//Borrowed this template concept from Bizzar:SSI
$fileopen = fopen($contactpage, "r");
$page = fread($fileopen, filesize($contactpage));
$page = str_replace("{error}", $error, $page);
$page = str_replace("{error1}", $error1, $page);
$page = str_replace("{error2}", $error2, $page);
$page = str_replace("{error3}", $error3, $page);
$page = str_replace("{error4}", $error4, $page);
$page = str_replace("{error5}", $error5, $page);
$page = str_replace("{name}", $name, $page);
$page = str_replace("{email}", $email, $page);
$page = str_replace("{subject}", $subject, $page);
$page = str_replace("{message}", $message, $page);
echo $page;
}
?>