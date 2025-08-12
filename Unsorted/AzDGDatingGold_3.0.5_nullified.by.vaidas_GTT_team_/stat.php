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
include "templates/header.php";
$t = new Template;
$t->set_file("templates/".$template_name."/error.html");

$sql = "SELECT count(*) as total FROM ".$mysql_table;
$result = mysql_query($sql) or die(mysql_error());
$trows = mysql_fetch_array($result);
$num = $trows[total];
if ($num == 0)
{
$t->set_var("ERROR", W_NO_USER_IN_DB);
$t->pparse();
include "templates/footer.php";
die;
}
    		$handle=opendir("stat");
            $filenumber = 0;
			while (false!==($file = readdir($handle))) { 
			    if ($file != "." && $file != "..") {
			    $statfile[$filenumber] = $file;
                $filenumber++;
                } 
			}
			closedir($handle); 
if ($filenumber == 0)
{
$t->set_var("ERROR", W_NO_USER_IN_DB);
$t->pparse();
include "templates/footer.php";
die;
}
elseif ($filenumber > 1)
{
$j = 0;
for ($j = 0; $j < $filenumber; $j++)
{
include "stat/$statfile[$j]";
}
}
include "templates/footer.php";
?>
</body>
</html>