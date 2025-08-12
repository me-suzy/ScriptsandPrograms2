<?php
$file=file("$url");

$i=0;
while($file[$i]){

	$urlt[$i]=substr(strrchr($url,"/"),0);
	$url2=str_replace("$urlt[$i]","/",$url);

	$file[$i]=str_replace("href=\"/","href=\"$url2",$file[$i]);
	$file[$i]=str_replace("$url2","http://realms.tcgames.net/test.001.php?url=$url2",$file[$i]);
	print "$file[$i]";
	$i=$i+1;
}
?>