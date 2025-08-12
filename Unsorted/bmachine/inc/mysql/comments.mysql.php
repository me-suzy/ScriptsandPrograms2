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

function getSmry($in) {
global $m_cnv,$c_wrap;

if(!$in) { return; }

$ar=getSpost($in);

$smr=$ar[summary];
$title=$ar[title];
$a_name=$ar[a_name];
$a_email=$ar[a_email];
$date=$ar[date];

$date=bmcDate($date);

if($m_cnv == "1") {
$r=rand(1520,9982);
$smr=str_replace("[img src=\"http://","[$r]",$smr);
$smr=str_replace("[img src=http://","[$r]",$smr);
$smr=cnvall($smr);
$smr=str_replace("[$r]","[img src=http://",$smr);
}

$smr=str_replace("[img ","<img width=65 height=72 border=1 bordercolor=black ",$smr);
$smr=str_replace("][/img]",">",$smr);

if(!trim($title)) { @header("Location: index.php?comment=false") or scrpt("index.php"); exit(); }

$smr=wordwrap($smr,$c_wrap,"\n",1);
$smr=smilify($smr);

echo <<<EOF
<table border="0" cellpadding="0" cellspacing="0" width="100%">
<tr><td width="449" valign="center">
<a href="index.php?id=$in"><span class="title">$title</span></a><span class="date"> [ $date ] 
[ by: <a href="mailto:$a_email">$a_name</a> ]</span>
</td></tr><tr><td width="100%">
<pre><span class="content">$smr
</span></pre></td></tr></table><hr width="50%" align="left" size="1" color="gray">
EOF;

$arr=getAllCmts($in);

// Loop through each line in the file and print out the contents

for($n=0;$n<=count($arr[a_name]);$n++) {

if(trim($arr[a_name][$n])) {

$name=$arr[a_name][$n];
$email=$arr[a_email][$n];
$url=$arr[a_url][$n];
$date=$arr[date][$n];
$cm=$arr[data][$n];

$date=bmcDate($date);

	$cm=str_replace("<","&lt;",$cm); // Clear off the HTML tags
	$cm=str_replace(">","&gt;",$cm);

	$name=str_replace("<","&lt;",$name); // Clear off the HTML tags
	$name=str_replace(">","&gt;",$name);

	$email=str_replace("<","&lt;",$email); // Clear off the HTML tags
	$email=str_replace(">","&gt;",$email);

	$url=str_replace("<","&lt;",$url); // Clear off the HTML tags
	$url=str_replace(">","&gt;",$url);


$cm=wordwrap($cm,$c_wrap,"\n",1);
if($m_cnv == "1") { $cm=cnvall($cm); }
$cm=smilify($cm);

echo <<<EOF
<table border="0" cellpadding="0" cellspacing="0">
<tr>
<td width="532">
<span class="t_small">Posted by </span><a href="mailto:$email"><span class="t_small">$name</span></a>&nbsp;
<span class="t_small">on $date</span>
EOF;

if(trim($url) && $url !="http://") { echo "&nbsp;&nbsp;<a href=\"$url\"><span class=\"t_small\">[www]</span></a>"; }

echo <<<EOF
</td>
</tr>
<tr>
<td width="532">
<pre><span class="t_small">$cm</span></pre>
</td>
</tr>
</table>
<hr width="50%" align="left" size="1" color="gray">
EOF;
}
}
echo <<<EOF
<br><span class="content"><b>Post a comment</b></span>
EOF;

/////////////////////////////////


return $ar;
}

//###########################################

function saveData() {
global $my_db,$my_host,$my_user,$my_pass,$my_prefix;

	if(!trim($_POST[name])) { errd("Empty fields!","Empty \"name\" field!<br>Please go back and correct!"); }
	if(!trim($_POST[email])) { errd("Empty fields!","Empty \"email\" field!<br>Please go back and correct!"); }
	if(!trim($_POST[comments])) { errd("Empty fields!","Empty \"comments\" field!<br>Please go back and correct!"); }
	if(!strpos(trim($_POST[email]),"@")) { errd("Empty fields!","Invalid email!<br>Please go back and correct!"); }
	if(!strpos(trim($_POST[email]),".")) { errd("Empty fields!","Invalid email!<br>Please go back and correct!"); }

$dat=$_POST[comments];

$date=time();

$link = mysql_connect("$my_host","$my_user","$my_pass") or errd("MySQL Error!","Connection to MySQL server : $my_host failed!");
mysql_select_db($my_db , $link) or errd("MySQL Error!","Couldn't open database $my_db !");

$tbl=$my_prefix."comments";

mysql_query ("INSERT INTO $tbl (auth_name,auth_email,auth_url,date,data,ext1) VALUES ('$_POST[name]','$_POST[email]','$_POST[url]','$date','$_POST[comments]','$_POST[fn]')") or errd("MySQL error!","Cant write to table $tbl !");

mysql_close($link);

}

?>