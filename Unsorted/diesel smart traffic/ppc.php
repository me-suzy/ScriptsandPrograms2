<?
   require "conf/sys.conf";
   require "lib/mysql.lib";
   require "lib/group.lib";
   
   include "tpl/top.ihtml";
   require "lib/bann.lib";
?>

<?php
   if($keys != ""){
	$db = c();
	
	echo "<FONT FACE=VERDANA SIZE=2>Keywords: <b> $keys </b><br><br><b>Advertiser sites :</b></FONT>";
	
	$keys = ereg_replace("\+"," ",$keys);
	$keys = ereg_replace("'","''",$keys);
	$keys_array = split(" ",$keys);

	$query = "select cm.url as url, cm.title as title, kw.id as id, kw.ppc as cost, kw.keyword as keys1, cm.ikeys as keys2 from campaigns cm, keywords kw where cm.status='1' and (1=1 and";
	
	for($i=0;$i<sizeof($keys_array);$i++) if (strlen($keys_array[$i])>1){
		$query .= " kw.keyword LIKE '%$keys_array[$i]%' ";
		if($i<sizeof($keys_array)-1) $query .= "or";
		}
	$query .= ") and kw.cid=cm.id order by kw.ppc desc";
	$records = q($query);

	$i1 = 0;

	echo "<table border=0 width=100%>\n";
	if (!e($records))
	while($record = f($records)){
		$i1++;
		echo "<tr><td width=20>".($i1).".</td><td><B><a href='ppcclick.php?uid=$uid&kid=$record[id]' target=_blank>$record[title]</a></B> ($record[url])<br><font color=404040>Bid Keywords: $record[keys1] ($record[cost] credits)<br> <font color=808080>Other keywords: $record[keys2]</font></font></td></tr>\n";
	}
	echo "</table>\n";
if ($i1==0) echo "<FONT FACE=VERDANA SIZE=2>None matched the search keywords.</FONT>";
echo "<br><br><FONT FACE=VERDANA SIZE=2><b>Sites in the system: </b></FONT><br>";
$i1=0;
$query = "select * from campaigns where status='1' and";

	for($i=0;$i<sizeof($keys_array);$i++){
		$query .= " ikeys LIKE '%$keys_array[$i]%' ";
		if($i < sizeof($keys_array)-1) $query .= "or";
	}
	$query .= "order by title";
	$records = q($query);

	echo "<table border=0 width=100%>\n";
	while($record = f($records)){
		$i1++;
		$url1=(strstr($record[url],"http://")!=""?"":"http://").$record[url];

		if ($uid) $url1=$ROOT_HOST."target.php?topframe=1&uid=".$uid."&cmid=".$record[id];

		echo "<tr><td width=20>".($i1).".</td><td><B><a href='$url1' target=_blank><FONT FACE=VERDANA SIZE=2 COLOR=#0000FF>$record[title]</FONT></a></B><FONT FACE=VERDANA SIZE=2>($record[url])<br>Keywords: $record[ikeys]</font></td></tr>\n";

	}
	echo "</table>\n";
	d($db);
  
   if ($rpage==0)  $rpage=1;
  $rp=($rpage-1)/10 +1;
   if ($other) {echo "<br><FONT FACE=VERDANA SIZE=2><b>Results from other search engines ( page $rp ):</b></FONT><BR><br>"; include("lib/searchinc.lib"); echo "<br>";}
   if ($rpage>1) echo "<br><b><a href=ppc.php?rpage=".($rpage-10)."&keys=$keys&other=1><FONT FACE=VERDANA SIZE=2 COLOR=#0000FF>&lt; PAGE ".($rp-1)."</FONT></a></b> | ";
    echo " <b><a href=ppc.php?rpage=".($rpage+10)."&keys=$keys&other=1><FONT FACE=VERDANA SIZE=2 COLOR=#FF0000>PAGE ".($rp+1)." &gt;</FONT></a></b>";

   } else echo "<FONT FACE=VERDANA SIZE=2>No keywords specified.</FONT>";

    include "tpl/bottom.ihtml";
?>