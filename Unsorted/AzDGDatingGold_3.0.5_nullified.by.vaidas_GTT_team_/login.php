<?php
##################################################################
# \-\-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/-/-/ #
##################################################################
# AzDGDatingGold                Version 3.0.5                     #
# Status                        Paid                             #
# Writed by                     AzDG (support@azdg.com)          #
# Created 21/09/02              Last Modified 21/09/02           #
# Scripts Home:                 http://www.azdg.com              #
##################################################################
include "config.inc.php";
include "templates/secure.php";
if ($page == "login") {
$passwd = md5(stripslashes($password));
    session_start();
    $s = md5(ip().port());
    $_SESSION['s']= $s; 
    unset($s);  
Header("Location: $url/members/index.php?l=$l&username=$username&password=$passwd");
} 
else 
{
include "templates/header.php";
$t = new Template;
$t->set_file("templates/".$template_name."/login.html");
$t->set_var("W_MEMBERS_AREA", W_MEMBERS_AREA);
$t->set_var("W_USERNAME", W_USERNAME);
$t->set_var("W_PASSWORD", W_PASSWORD);
$t->set_var("W_ENTER", W_ENTER);
$t->set_var("W_FORGET", W_FORGET);
$t->set_var("LANGUAGE", $l);
$t->pparse("login");

include "templates/footer.php";
}
?>