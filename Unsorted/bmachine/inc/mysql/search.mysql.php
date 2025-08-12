<?php
/*
BN Soft bMachine 2.7

Written by Kailash Nadh
http://bnsoft.net, support@bnsoft.net

bMachine - http://boastology.com
*/

//######################################
if($valid_flag != "true") {
echo "ACCESS DENIED!"; exit();
}


function doSearch($wht,$key) {

$posts=getPostList("hd");

if(!count($posts[title])) {
echo <<<EOF
<center><span class="title"><font color="red">
<b>No results were found for the keyword &quot;$key&quot;!</b></font></span>
</center>
EOF;
return;
}

//############################################################

if($wht=="author") { $q="a_name"; }
if($wht=="title") { $q="title"; }
if($wht=="content") { $q="data"; }

$key=trim($key);
$keys=explode(" ",$key);

for($k=0;$k<=count($posts[title])-1;$k++) {
$p=$posts[$q][$k];
if(!trim($p)) { return; }

if(srchString($p,$keys)) {

$id=$posts[id][$k];
$title=$posts[title][$k];
$date=$posts[date][$k];
$date=bmcdate($date);
$a_name=$posts[a_name][$k];

$done="true";
echo <<<EOF
<li>
<a href="index.php?id=$id"><span class="title"><b>$title</b></span></a>
<br><span class="date">$date</span>&nbsp;<span class="aname"> 
&nbsp;by $a_name</span>
</li>

EOF;
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

?>