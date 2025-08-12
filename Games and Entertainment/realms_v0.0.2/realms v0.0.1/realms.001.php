<?php
$myrealm=mysql_fetch_array(mysql_query("select * from realms where world='$stat[realm]'"));
$realm=$myrealm[name];
$realmdes = str_replace("'" , "&#39;" , $myrealm[description]);
$realmdes = str_replace("
" , "" , $realmdes);
if(!$realm){
$realm="Unknown";
$realmdes="An error occurred";
}
$ucrealm=ucfirst($realm);
print"<a href=\"javascript:;\" onmouseover=\"return escape('$realmdes')\">$ucrealm</a>";
?>