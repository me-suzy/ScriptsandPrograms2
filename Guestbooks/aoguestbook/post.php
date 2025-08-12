<?php
require "config.php";

if (isset($name)) {

$name = trim($name);
$message = trim($message);

$namelength = strlen($name);
$messagelength = strlen($message);

if ($namelength < 3) {
$msg = "Your name must be at least 3 characters.";
} elseif ($namelength > 20) {
$msg = "Your name must be less than 20 characters.";
} elseif ($messagelength > 500 ) {
$msg = "Your message must be no more than 500 characters.";
} elseif ($messagelength < 5 ) {
$msg = "Your message must be more than 5 characters.";
}

if (isset($msg)) {
echo "$msg";
exit();
} else {

$ipi = getenv("REMOTE_ADDR");

$name = mysql_real_escape_string(htmlentities($name));
$email = mysql_real_escape_string(htmlentities($email));
$message = mysql_real_escape_string(htmlentities($message));
$location = mysql_real_escape_string(htmlentities($location));
$website = mysql_real_escape_string(htmlentities($website));
$message3 = $message;

$patterns = array( 
           "/\[link\](.*?)\[\/link\]/", 
           "/\[url\](.*?)\[\/url\]/", 
           "/\[b\](.*?)\[\/b\]/", 
           "/\[u\](.*?)\[\/u\]/", 
           "/\[i\](.*?)\[\/i\]/" 
       ); 
       $replacements = array( 
           "<a href=\"\\1\">\\1</a>", 
           "<a href=\"\\1\">\\1</a>", 
           "<strong>\\1</strong>", 
           "<u>\\1</u>", 
           "<i>\\1</i>" 
            
       ); 
        
      $message = preg_replace($patterns,$replacements, $message);
	  
$patterns4 = array( 
           "/\[link\](.*?)\[\/link\]/", 
           "/\[url\](.*?)\[\/url\]/", 
           "/\[img\](.*?)\[\/img\]/", 
           "/\[b\](.*?)\[\/b\]/", 
           "/\[u\](.*?)\[\/u\]/", 
           "/\[i\](.*?)\[\/i\]/" 
       ); 
       $replacements4 = array( 
           "", 
           "", 
           "", 
           "", 
           "", 
           "" 
            
       ); 

$message3 = preg_replace($patterns4,$replacements4, $message3);
$messagelength2 = strlen($message3);
if ($messagelength2 >= 5) {

$time = gmdate("M jS - g:i A");

mysql_query("INSERT INTO entries SET name = '$name', message ='$message', time = '$time', website = '$website', email = '$email', location = '$location', ip = '$ip'") or die(mysql_error());

echo "Thank you for signing the guestbook! Go back to the <a href=index.php>index</a> to view it.";

} else { 
echo "Messages cannot consist of just BB Code";
}

}

} else {

echo "
<form action=\"post.php\" method=\"post\">
<br /><br /><strong>Sign Guestbook</strong>
<h6>Name: </h6><input type=\"text\" name=\"name\" maxlength=\"20\" size=\"21\" /><br />
<h6>Website: </h6><input type=\"text\" name=\"website\" maxlength=\"80\" size=\"21\" /><br />
<h6>Email Address:</h6><input type=\"text\" name=\"email\" maxlength=\"80\" size=\"21\" /><br />
<h6>Location:</h6><input type=\"text\" name=\"location\" maxlength=\"80\" size=\"21\" /><br />
<h6>Message:</h6><textarea name=\"message\" rows=\"5\" cols=\"30\"></textarea><br />
<input type=\"submit\" value=\"Sign Guestbook\" name=s\"end\" class=\"submit\" />
</form>";
}

?>