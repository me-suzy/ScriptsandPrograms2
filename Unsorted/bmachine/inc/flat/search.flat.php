<?php

/*
********************************************

BN Soft bMachine 2.7

Written by Kailash Nadh
http://bnsoft.net, support@bnsoft.net

bMachine - http://boastology.com

********************************************
*/

//######################################
if($valid_flag != "true") {
echo "ACCESS DENIED!"; exit();
}

// Do the search

function dosearch($wht,$key) {
global $c_dir,$key;
$ary=getAll(); // Get the file list in the data dir

$key=trim($key);
$keys=explode(" ",$key);

for($n=0;$n<=count($ary)-1;$n=$n+1) {
	if(trim($ary[$n])) {
	$dat=@fread(fopen("$c_dir/$ary[$n]", "r"), 100000);
	// Open the file, explode the contents
	list($title,$date,$na,$n1,$a_name,$n3,$n8,$n5,$n6,$msg)=explode("||",$dat);

if($wht=="author") { $q=$a_name; }
if($wht=="title") { $q=$title; }
if($wht=="content") { $q=$msg; }

// String search
if(srchString($q,$keys)) {

$id=$ary[$n];
$done="true";
$date=bmcdate($date);
echo <<<EOF
<li>
<a href="index.php?id=$id"><span class="title"><b>$title</b></span></a>
<br><span class="date">$date</span>&nbsp;<span class="aname"> 
&nbsp;by $a_name</span>
</li>

EOF;
	}

}
}

if(!$done) {
echo <<<EOF
<center><span class="title"><font color="red">
<b>No results were found for the keyword &quot;$key&quot;!</b></font></span>
</center>
EOF;
return;
}


}




// Read all the files in the DATA folder
function getAll() {
global $c_dir;

	$handle = opendir("$c_dir") or errd("$c_dir directory not found!", "$c_dir directory not found or is not writable!");
	$i=0;
$ar=array();
while($filename = readdir($handle)) 
{
	if(!strpos("--$filename","[]") && $filename != "." && $filename != "..") {
	array_push($ar,$filename);
	} 
} 
	closedir($handle);

		if(!count($ar)) {
echo <<<EOF
<center><span class="title"><font color="red">
<b>There aren't any articles in the Database to search!</b></font></span>
</center>
EOF;
		}

return $ar;
}
?>