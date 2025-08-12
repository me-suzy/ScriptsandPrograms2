<?php


include("bunnycheck.001.php");

if(!$charlookup){
$charlookup=$stat[id];
}

$charclass = mysql_fetch_array(mysql_query("select * from `charclass` where `character`='$charlookup' limit 1"));

if($charclass[id]){

	$oldvalue="0";
	foreach ($charclass as $key => $value) {
		if (!preg_match("/\d/",$key)) {
			if($charclassprint&&$staff=="yes"&&$key!="id"&&$key!="character"){ 
				print"$key : $value<br>"; 
			}
			if($key!="id"&&$key!="character"){
				if($value>$oldvalue){
					$oldvalue="$value";
					$classish="$key";
				}
			}
		}
	}

	$classses = mysql_fetch_array(mysql_query("select * from `classes` where `classname`='$classish' limit 1"));
	//$classdes = addslashes("$classses[classdes]");

	$classdes = str_replace("'" , "" , $classses[classdes]);
	$ucname=ucfirst($classses[classname]);

	print"<a href=\"javascript:;\" onmouseover=\"return escape('$classdes')\">$ucname</a>";

}else{

	mysql_query("INSERT INTO `charclass` (`character`)
					VALUES
					('$charlookup')") or die("<br>Could not register charclass.");

	print"Nomad";

}