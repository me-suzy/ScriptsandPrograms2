<?php
require "../conf/sys.conf";
require "../lib/mysql.lib";
echo "<font size=1>$db_host,$db_login,$db_pswd,$db_name";
$db = c();

function execute($filename)
{
global $db_name;
$text = join ('', file ($filename)); 
$words= explode (";",$text);
$max=count($words);

for ($i=0;$i<$max;$i++) 
	{
	$words[$i]=str_replace("\n","",$words[$i]);
	$words[$i]=str_replace("\r","",$words[$i]);
	for ($z=0;$z<10;$z++) $words[$i]=str_replace("  "," ",$words[$i]);
	echo "<br>$db_name + '".$words[$i];$r =  mysql($db_name,$words[$i]); echo"'";
	}
}

execute("clear.sql");
execute("structure.sql");
execute("groups.sql");
execute("menus.sql");
execute("vars.sql");
execute("optimize.sql");
d($db);
?>
