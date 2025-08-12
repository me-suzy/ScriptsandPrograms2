<?
include "./faq-config.php";
setcookie("ckAdminPass", $adminpass, time()+313560000);
print "The cookie has been set in your web browser to give you admin priviledges. <b>Delete setmycookie.php now!</b> <a href='$mainfile'>Back to the FAQ</a>";
?> 