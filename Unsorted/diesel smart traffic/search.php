<?
   require "conf/sys.conf";
   require "lib/mysql.lib";
   require "lib/group.lib";

   include "tpl/top.ihtml";

   echo "<b>Search</b><br>";

   if($keys != ""){
	$db = c();
	
	echo "Search for: $keys<br><br>";
	
	$keys = ereg_replace("\+"," ",$keys);
	$keys = ereg_replace("'","''",$keys);
	$keys_array = split(" ",$keys);

	$query = "select * from campaigns where status='1' and";
	
	for($i=0;$i<sizeof($keys_array);$i++){
		$query .= " ikeys LIKE '%$keys_array[$i]%' ";
		if($i < sizeof($keys_array)-1) $query .= "or";
	}
	$query .= "order by title";
	$records = q($query);

	$i = 0;

	echo "<table border=0 width=100%>\n";
	while($record = f($records)){
		echo "<tr><td width=20>".($i+1).".</td><td><a href='".(strstr($record[url],"http://")!=""?"":"http://")."$record[url]' target=_blank>$record[title]</a><br><font color=C0C0C0>Keyword: $record[ikeys]</font></td></tr>\n";


		$i++;
	}
	echo "</table>\n";
	
	echo "<br>Total: ".nr($records);

	d($db);
   } else echo "Search query is empty.";

   include "tpl/bottom.ihtml";
?>