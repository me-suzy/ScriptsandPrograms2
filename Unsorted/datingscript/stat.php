<?php
############################################################
# \-\-\-\-\-\-\     AzDG  - S C R I P T S    /-/-/-/-/-/-/ #
############################################################
# AzDGDatingLite          Version 2.1.1                    #
# Writed by               AzDG (support@azdg.com)          #
# Created 03/01/03        Last Modified 04/05/03           #
# Scripts Home:           http://www.azdg.com              #
############################################################
# File name               stat.php                         #
# File purpose            Show statistic                   #
# File created by         AzDG <support@azdg.com>          #
############################################################
include_once 'include/config.inc.php';
include_once 'include/options.inc.php';
include_once 'include/security.inc.php';
include_once 'include/functions.inc.php';
include_once 'templates/'.C_TEMP.'/config.php';
include_once 'templates/'.C_TEMP.'/header.php';

$sql = "SELECT count(id) as total FROM ".C_MYSQL_MEMBERS;
$result = mysql_query($sql) or die(mysql_error());
$trows = mysql_fetch_array($result);
$num = $trows['total'];
if ($num == 0) printm($w[204]);

echo "<center>";
    		$handle=opendir(C_PATH.'/stat');
            $fn = 0;
			while (false!==($file = readdir($handle))) { 
			    if ($file != "." && $file != "..") {
			    $statfile[$fn] = $file;
                $fn++;
                } 
			}
			closedir($handle); 
if ($fn == 0) printm($w[205]);
elseif ($fn >= 1) {
    $j = 0;
    for ($j = 0; $j < $fn; $j++) {
         include_once C_PATH.'/stat/'.$statfile[$j];
    }
}
include_once 'templates/'.C_TEMP.'/footer.php';
?>
