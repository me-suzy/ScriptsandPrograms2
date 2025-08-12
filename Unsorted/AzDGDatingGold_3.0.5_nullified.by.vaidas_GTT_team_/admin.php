<?php
##############################################################################
# \-\-\-\-\-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/-/-/-/-/-/ #
##############################################################################
# AzDGDatingGold                Version 3.0.5                                 #
# Writed by                     AzDG (support@azdg.com)                      #
# Created 25/05/02              Last Modified 25/05/02                       #
# Scripts Home:                 http://www.azdg.com                          #
##############################################################################
include "config.inc.php";
include "templates/secure.admin.php";
if ($page == "admin") {
//    session_start();
    if(($adminlogin == $login) && ($adminpass == $password)) {
$log = md5(stripslashes($login));
$passwd = md5(stripslashes($password));
//    $s = md5(ip().port());
//    $_SESSION['s']= $s; 
//    unset($s);  
Header("Location: $url/admin/index.php?l=$l&page=admin&login=$log&password=$passwd");
}
else echo "Error";
} 
else 
{
include "templates/header.php";
?>
<h1 style=color:red><?php echo $lang[175]; ?></h1>
<form action="admin.php?l=<?php echo $l; ?>&page=admin" method=post>
<Table Border="1" CellSpacing="0" CellPadding="4" bordercolor=black>
<tr><td colspan=2 class=head><center><?php echo $lang[176]; ?>
<tr><td class=desc><?php echo $lang[177]; ?></td><td><input class=input type=text name=login></td></tr>
<tr><td class=desc><?php echo $lang[178]; ?></td><td><input class=input type=password name=password></td></tr>
<tr><td colspan=2 align=right><input class=input type=submit value="Enter"></td></tr>
</table>
</form>
<?php
include "templates/footer.php";

}
//echo $page;
?>