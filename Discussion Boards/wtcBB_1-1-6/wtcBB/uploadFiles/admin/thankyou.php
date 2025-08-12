<?php

// ########################################################### \\
// ########################################################### \\
// ########################################################### \\
// ################### //THANK YOU PAGE\\ #################### \\
// ########################################################### \\
// ########################################################### \\
// ########################################################### \\

// include files
include("./../includes/config.php");
include("./../includes/functions.php");
include("./../includes/global_admin.php");
include("./../includes/functions_admin.php");

$_GET['uri'] = str_replace("STEVE","&",$_GET['uri']);

// form meta...
$meta_tag = "<meta http-equiv=\"refresh\" content=\"3; url=".htmlspecialchars($_GET['uri'])."\">\n";

// do header
admin_header("wtcBB Admin Panel - Options","",$meta_tag);

print("<h1 style=\"width: 60%;\">Thank You</h1>\n\n");
print("<div align=\"center\"><blockquote style=\"width: 60%; text-align: left;\">\n\n");
print("\t\t\t".stripslashes(htmlspecialchars($_GET['message']))."\n");
print("<br /><br />\n");
print("If you are not redirected, please <a href=\"".htmlspecialchars($_GET['uri'])."\">click here</a>.\n");
print("</blockquote></div>\n\n");

admin_footer();

?>