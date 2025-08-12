<?php
##############################################################################
# \-\-\-\-\-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/-/-/-/-/-/ #
##############################################################################
# AzDGDatingLite                Version 1.1.0                                 #
# Writed by                     AzDG (support@azdg.com)                      #
# Created 25/05/02              Last Modified 12/09/02                       #
# Scripts Home:                 http://www.azdg.com                          #
##############################################################################
include "config.inc.php";
include "templates/secure.php";
if ($page == "login") {
$passwd = md5(stripslashes($password));
Header("Location: $url/members/index.php?l=$l&username=$username&password=$passwd");
} 
else 
{
include "templates/header.php";
echo "<form action=login.php?l=".$l."&page=login method=post><center><span class=head>".$lang[32]."</span></center><Table Border=\"1\" CellSpacing=\"0\" CellPadding=\"4\" bordercolor=black><tr class=desc align=center><td width=100>".$lang[9]."</td><td><input class=input type=text name=username></td></tr><tr class=desc align=center><td width=100>".$lang[10]."</td><td><input class=input type=password name=password></td></tr><tr><td colspan=2 align=right><input class=input type=submit value=\"".$lang[32]."\"></td></tr><tr><td colspan=2 align=center><a href=remind.php?l=".$l.">".$lang[77]."</a></td></tr></table></form>";
include "templates/footer.php";

}
//echo $page;
?>