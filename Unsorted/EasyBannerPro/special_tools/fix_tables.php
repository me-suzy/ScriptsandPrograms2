<?PHP

#################################################
##                                             ##
##              Easy Banner Pro                ##
##       http://www.phpwebscripts.com/         ##
##       e-mail: info@phpwebscripts.com        ##
##                                             ##
##                 Version 2.8                 ##
##             copyright (c) 2003              ##
##                                             ##
##  This script is not freeware nor shareware  ##
##    Please do no distribute it by any way    ##
##                                             ##
#################################################

include("./common.php");

if (!$HTTP_GET_VARS) form();
fix_table($HTTP_GET_VARS[table]);

function fix_table($table) {
global $s;
$q = dq("REPAIR TABLE $table",1);
$s[info] = eot('The script tried to fix the selected table','It does not mean that this action was successful however it should help in most cases.');
form();
}

function form() {
global $s;
include('./_head.txt');
echo $s[info];
echo eot('Fix Tables Tool','The following tables have perfix '.$s[pr].', so they are probably used by Easy Banner Pro.<br>Click on name of the table which you want to fix.');
$q = mysql_list_tables($s[dbname]);
while ($table = mysql_fetch_row($q))
{ if (eregi("^$s[pr].*",$table[0])) echo '<a href="fix_tables.php?table='.$table[0].'">'.$table[0].'</a><br>'; }
include('./_footer.txt'); exit;
}

?>