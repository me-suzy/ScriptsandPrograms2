<?php 

/* uDi - You Direct It, written by, and copyright Mike Cheesman.
** WHOIS.PHP submitted by Reggie Goldman of TheMudd.org
** Modified by Mike Cheesman as he saw fit. 
*/

require "config.php";
include "$header";
if ( isset($username)) {
if ( is_dir("$username")) {
print "<i>$username</i> is already in use. please try another username<br><br><form action=\"whois.php\" method=\"POST\">$website/<input type=\"text\" name=\"username\">&nbsp;<input type=\"submit\" value=\"Check It\"></form>";
} else if ($username == "") {
print "You didn't specify a username.<br><br><form action=\"whois.php\" method=\"POST\">$website/<input type=\"text\" name=\"username\">&nbsp;<input type=\"submit\" value=\"Check It\"></form>";
} else if (strlen($username) > 20) {
print "<font color=\"Red\">Username cannot be more than 20 characters</font><br><br><form action=\"whois.php\" method=\"POST\">$website/<input type=\"text\" name=\"username\">&nbsp;<input type=\"submit\" value=\"Check It\"></form>";
} else {
print "$website/<b>$username</b> is available, <a href=\"signup.php?username=$username\">sign up</a>."; 
}
} else {
print "<b>Please enter your desired username below, to see if it is available:</b><br><form action=\"whois.php\" method=\"POST\">$website/<input type=\"text\" name=\"username\">&nbsp;<input type=\"submit\" value=\"Check It\"></form>";
}
include "$footer"; ?>