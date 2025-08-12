<?php
/*
BN Soft bMachine 2.7

Written by Kailash Nadh
http://bnsoft.net, support@bnsoft.net

bMachine - http://boastology.com
*/
//######################################################################

//######################################
if($valid_flag != "true") {
echo "ACCESS DENIED!"; exit();
}


function getPostList($hdn="") {
global $my_host,$my_user,$my_pass,$my_db,$my_prefix;

$link = mysql_connect("$my_host","$my_user","$my_pass") or errd("MySQL Error!","Connection to MySQL server : $my_host failed!");
mysql_select_db($my_db , $link) or errd("MySQL Error!","Couldn't open database $my_db !");

$pstb=$my_prefix."posts";

if(!$hdn) {
$result = mysql_query( "SELECT id,auth_name,auth_email,auth_url,title,date,file,format,keyws,summary,data,ext1,ext2 FROM $pstb ORDER by date DESC" ) or errd("MySQL Error!","Couldn't read data from table $pstb !<br>".mysql_error());
} else {
$result = mysql_query( "SELECT id,auth_name,auth_email,auth_url,title,date,file,format,keyws,summary,data,ext1,ext2 FROM $pstb WHERE ext1!='hide' ORDER by date DESC" ) or errd("MySQL Error!","Couldn't read data from table $pstb !<br>".mysql_error());
}

$num_rows = mysql_num_rows($result);

	$ar=array();
	$n=0;

	while ($dat = mysql_fetch_array($result)) { 
	$ar[id][$n]=$dat[id];
	$ar[title][$n]=$dat[title];
	$ar[a_name][$n]=$dat[auth_name];
	$ar[a_email][$n]=$dat[auth_email];
	$ar[a_url][$n]=$dat[auth_url];
	$ar[date][$n]=$dat[date];
	$ar[summary][$n]=$dat[summary];
	$ar[data][$n]=$dat[data];
	$ar[format][$n]=$dat[format];
	$ar[file][$n]=$dat[file];
	$ar[keyws][$n]=$dat[keyws];
	$ar[ext1][$n]=$dat[ext1];
	$ar[ext2][$n]=$dat[ext2];
	$n=$n+1;
	}

mysql_close($link);

return $ar;
}

//######################################################################
function getFieldList($hd="",$fld="id") {
global $my_host,$my_user,$my_pass,$my_db,$my_prefix;

$link = mysql_connect("$my_host","$my_user","$my_pass") or errd("MySQL Error!","Connection to MySQL server : $my_host failed!");
mysql_select_db($my_db , $link) or errd("MySQL Error!","Couldn't open database $my_db !");

$pstb=$my_prefix."posts";

if(!$hd) {
$result = mysql_query( "SELECT $fld FROM $pstb ORDER by date DESC" ) or errd("MySQL Error!","Couldn't read data from table $pstb !<br>".mysql_error());
}
else {
$result = mysql_query( "SELECT $fld FROM $pstb WHERE ext1!='hide' ORDER by date DESC" ) or errd("MySQL Error!","Couldn't read data from table $pstb !<br>".mysql_error());
}

$num_rows = mysql_num_rows($result);

	$ar=array();
	$n=0;

	while ($dat = mysql_fetch_array($result)) { 
	$ar[$n]=$dat[$fld];
	$n=$n+1;
	}

mysql_close($link);

return $ar;
}

//######################################################################

function getDateList() {
global $my_host,$my_user,$my_pass,$my_db,$my_prefix;

$link = mysql_connect("$my_host","$my_user","$my_pass") or errd("MySQL Error!","Connection to MySQL server : $my_host failed!");
mysql_select_db($my_db , $link) or errd("MySQL Error!","Couldn't open database $my_db !");

$pstb=$my_prefix."posts";

$result = mysql_query( "SELECT date FROM $pstb ORDER by date DESC" ) or errd("MySQL Error!","Couldn't read data from table $pstb !<br>".mysql_error());
$num_rows = mysql_num_rows($result);

	$ar=array();

	while ($dat = mysql_fetch_array($result)) { 

		if($dat[date]) {
		$tmp_date=date("j_M_Y",$dat[date]);
		$ar["$tmp_date"]="true";
		}

	}

mysql_close($link);

return $ar;
}

//################################################################
function getSpost($id,$adm="hide") {
global $my_host,$my_user,$my_pass,$my_db,$my_prefix;

$link = mysql_connect("$my_host","$my_user","$my_pass") or errd("MySQL Error!","Connection to MySQL server : $my_host failed!");
mysql_select_db($my_db , $link) or errd("MySQL Error!","Couldn't open database $my_db !");

$pstb=$my_prefix."posts";

$result = mysql_query( "SELECT auth_name,auth_email,auth_url,title,date,file,format,keyws,summary,data,ext1,ext2 FROM $pstb WHERE id='$id' AND ext1!='$adm'" ) or errd("MySQL Error!","Couldn't read data from table $pstb !<br><br>".mysql_error());
$num_rows = mysql_num_rows($result);

	$ar=array();

	$dat = mysql_fetch_array($result);
	$ar[id]=$dat[id];
	$ar[title]=$dat[title];
	$ar[a_name]=$dat[auth_name];
	$ar[a_email]=$dat[auth_email];
	$ar[a_url]=$dat[auth_url];
	$ar[date]=$dat[date];
	$ar[summary]=$dat[summary];
	$ar[data]=$dat[data];
	$ar[format]=$dat[format];
	$ar[file]=$dat[file];
	$ar[keyws]=$dat[keyws];
	$ar[ext1]=$dat[ext1];
	$ar[ext2]=$dat[ext2];

mysql_close($link);

if($ar[ext1]=="hide" && $adm=="hide") { return "hide"; }

return $ar;
}

//######################################################################


function myUpdate($id, $auth_name, $auth_email, $auth_url, $title, $date, $file, $format, $keyws, $summary, $data, $ext1, $ext2) {
global $my_db,$my_host,$my_user,$my_pass,$my_prefix;

if(!$id) { return; }

$link = mysql_connect("$my_host","$my_user","$my_pass") or errd("MySQL Error!","Connection to MySQL server : $my_host failed!");
mysql_select_db($my_db , $link) or errd("MySQL Error!","Couldn't open database $my_db !");

$tbl=$my_prefix."posts";

$auth_name=str_replace("'","\\'",$auth_name);
$auth_email=str_replace("'","\\'",$auth_email);
$auth_url=str_replace("'","\\'",$auth_url);
$title=str_replace("'","\\'",$title);
$file=str_replace("'","\\'",$file);
$format=str_replace("'","\\'",$format);
$keyws=str_replace("'","\\'",$keyws);
$summary=str_replace("'","\\'",$summary);
$data=str_replace("'","\\'",$data);
$ext1=str_replace("'","\\'",$ext1);
$ext2=str_replace("'","\\'",$ext2);

if($auth_name) { mysql_query(" UPDATE $tbl SET auth_name='$auth_name' WHERE id='$id'") or errd("MySQL error!","Cant write to table $tbl !"); }
if($auth_email) { mysql_query(" UPDATE $tbl SET auth_email='$auth_email' WHERE id='$id'") or errd("MySQL error!","Cant write to table $tbl !"); }
if($auth_url) { mysql_query(" UPDATE $tbl SET auth_url='$auth_url' WHERE id='$id'") or errd("MySQL error!","Cant write to table $tbl !"); }
if($title) { mysql_query(" UPDATE $tbl SET title='$title' WHERE id='$id'") or errd("MySQL error!","Cant write to table $tbl !"); }
if($date) { mysql_query(" UPDATE $tbl SET date='$date' WHERE id='$id'") or errd("MySQL error!","Cant write to table $tbl !"); }
if($file) { mysql_query(" UPDATE $tbl SET file='$file' WHERE id='$id'") or errd("MySQL error!","Cant write to table $tbl !"); }
if($format) { mysql_query(" UPDATE $tbl SET format='$format' WHERE id='$id'") or errd("MySQL error!","Cant write to table $tbl !"); }
if($keyws) { mysql_query(" UPDATE $tbl SET keyws='$keyws' WHERE id='$id'") or errd("MySQL error!","Cant write to table $tbl !"); }
if($summary) { mysql_query(" UPDATE $tbl SET summary='$summary' WHERE id='$id'") or errd("MySQL error!","Cant write to table $tbl !"); }
if($data) { mysql_query(" UPDATE $tbl SET data='$data' WHERE id='$id'") or errd("MySQL error!","Cant write to table $tbl !"); }
if($ext1) { mysql_query(" UPDATE $tbl SET ext1='$ext1' WHERE id='$id'") or errd("MySQL error!","Cant write to table $tbl !"); }
if($ext2)  { mysql_query(" UPDATE $tbl SET ext2='$ext2' WHERE id='$id'") or errd("MySQL error!","Cant write to table $tbl !"); }

mysql_close($link);

}

//######################################################

function delId($id,$all) {
global $my_db,$my_host,$my_user,$my_pass,$my_prefix;
if(!$id && !$all) { return; }

$tbl=$my_prefix."posts";
$tbl2=$my_prefix."comments";

$link = mysql_connect("$my_host","$my_user","$my_pass") or errd("MySQL Error!","Connection to MySQL server : $my_host failed!");
mysql_select_db($my_db , $link) or errd("MySQL Error!","Couldn't open database $my_db !");

if(!$all) {
mysql_query("DELETE FROM $tbl WHERE id='$id'") or errd("MySQL error!","Cant write to table $tbl !");
mysql_query("DELETE FROM $tbl2 WHERE ext1='$id'") or errd("MySQL error!","Cant write to table $tbl2 !");
}

else {
mysql_query("DELETE FROM $tbl") or errd("MySQL error!","Cant write to table $tbl !");
mysql_query("DELETE FROM $tbl2") or errd("MySQL error!","Cant write to table $tbl !");

}

mysql_close($link);
}

//######################################################

function putPost($auth_name, $auth_email, $auth_url, $title, $date, $file, $format, $keyws, $summary, $data, $ext1, $ext2) {
global $my_db,$my_host,$my_user,$my_pass,$my_prefix;

$link = mysql_connect("$my_host","$my_user","$my_pass") or errd("MySQL Error!","Connection to MySQL server : $my_host failed!");
mysql_select_db($my_db , $link) or errd("MySQL Error!","Couldn't open database $my_db !");

$tbl=$my_prefix."posts";

$auth_name=str_replace("'","\\'",$auth_name);
$auth_email=str_replace("'","\\'",$auth_email);
$auth_url=str_replace("'","\\'",$auth_url);
$title=str_replace("'","\\'",$title);
$date=str_replace("'","\\'",$date);
$file=str_replace("'","\\'",$file);
$format=str_replace("'","\\'",$format);
$keyws=str_replace("'","\\'",$keyws);
$summary=str_replace("'","\\'",$summary);
$data=str_replace("'","\\'",$data);
$ext1=str_replace("'","\\'",$ext1);
$ext2=str_replace("'","\\'",$ext2);

mysql_query ("INSERT INTO $tbl (auth_name,auth_email,auth_url,title,date,file,format,keyws,summary,data,ext1,ext2) VALUES ('$auth_name', '$auth_email', '$auth_url', '$title', '$date', '$file', '$format', '$keyws', '$summary', '$data', '$ext1', '$ext2')") or errd("MySQL error!","Cant write to table $tbl !<br>".mysql_error());
mysql_close($link);

}

//############ Get X number of latest posts #########
function getPosts($x,$start,$end) {

global $my_host,$my_user,$my_pass,$my_db,$my_prefix, $x_wrap;

$link = mysql_connect("$my_host","$my_user","$my_pass") or errd("MySQL Error!","Connection to MySQL server : $my_host failed!");
mysql_select_db($my_db , $link) or errd("MySQL Error!","Couldn't open database $my_db !");

$pstb=$my_prefix."posts";

$result = mysql_query( "SELECT id,title FROM $pstb ORDER by date DESC" ) or errd("MySQL Error!","Couldn't read data from table $pstb !");
$num_rows = mysql_num_rows($result);

	$n=0;
	while ($dat = mysql_fetch_array($result)) { 
	$ar[id][$n]=$dat[id];
	$ar[title][$n]=$dat[title];
	$n=$n+1;
	}

mysql_close($link);

for($j=0;$j<=$x-1;$j++) {

	if(trim($ar[id][$j])) {

	if(strlen($ar[title][$j]) >= 15) { $ar[title][$j]=substr($ar[title][$j],0,$x_wrap).".."; }
	$data.="$start<a href=\"index.php?id=".$ar[id][$j]."\"><span class=\"t_small\">".$ar[title][$j]."</span></a>$end";
	}
}

return $data;
}

//####### Get the number of comments for an entry ####

function getCmts($fl) {
global $my_db,$my_host,$my_user,$my_pass,$my_prefix;
if(!$fl) { exit(); }

$link = mysql_connect("$my_host","$my_user","$my_pass") or errd("MySQL Error!","Connection to MySQL server : $my_host failed!");
mysql_select_db($my_db , $link) or errd("MySQL Error!","Couldn't open database $my_db !");

$pstb=$my_prefix."comments";

$result = mysql_query( "SELECT id FROM $pstb WHERE ext1='$fl'" ) or errd("MySQL Error!","Couldn't read data from table $pstb !");
$num_rows = mysql_num_rows($result);
mysql_close($link);

return $num_rows;

}

//######################################################

function getAllCmts($in) {

global $my_db,$my_host,$my_user,$my_pass,$my_prefix;

$link = mysql_connect("$my_host","$my_user","$my_pass") or errd("MySQL Error!","Connection to MySQL server : $my_host failed!");
mysql_select_db($my_db , $link) or errd("MySQL Error!","Couldn't open database $my_db !");

$pstb=$my_prefix."comments";

	if($in) {
	$result = mysql_query( "SELECT id,auth_name,auth_email,auth_url,date,data,ext1 FROM $pstb WHERE ext1='$in' ORDER by date DESC" ) or errd("MySQL Error!","Couldn't read data from table $pstb ! ");
	}

	else {
	$result = mysql_query( "SELECT id,auth_name,auth_email,auth_url,date,data,ext1 FROM $pstb" ) or errd("MySQL Error!","Couldn't read data from table $pstb !");
	}
	$num_rows = mysql_num_rows($result);

	$ar=array();
	$n=0;

	while ($dat = mysql_fetch_array($result)) { 
	$ar[id][$n]=$dat[id];
	$ar[a_name][$n]=$dat[auth_name];
	$ar[a_email][$n]=$dat[auth_email];
	$ar[a_url][$n]=$dat[auth_url];
	$ar[date][$n]=$dat[date];
	$ar[data][$n]=$dat[data];
	$ar[ext1][$n]=$dat[ext1];
	$n=$n+1;
	}

mysql_close($link);
return $ar;

}

//####################################################

function myCmtUpdate($id,$dat,$spr) {
global $my_db,$my_host,$my_user,$my_pass,$my_prefix;

if(!$id) { return; }

$link = mysql_connect("$my_host","$my_user","$my_pass") or errd("MySQL Error!","Connection to MySQL server : $my_host failed!");
mysql_select_db($my_db , $link) or errd("MySQL Error!","Couldn't open database $my_db !");

$tbl=$my_prefix."comments";

mysql_query("DELETE FROM $tbl WHERE ext1='$id'") or errd("MySQL error!","Cant write to table $tbl !");

for($n=0;$n<=count($dat);$n++) {
list($name,$email,$url,$date,$data)=explode($spr,$dat[$n]);

$name=str_replace("'","\\'",$name);
$email=str_replace("'","\\'",$email);
$url=str_replace("'","\\'",$url);
$data=str_replace("'","\\'",$data);

	if(trim($name)) {
	mysql_query ("INSERT INTO $tbl (auth_name,auth_email,auth_url,date,data,ext1) VALUES ('$name','$email','$url','$date','$data','$id')") or errd("MySQL error!","Cant write to table $tbl !<br>".mysql_error());
	}
}

mysql_close($link);

}

//################### DELETE ALL COMMENTS FOR A POST ########
function delCmts($id) {
global $my_db,$my_host,$my_user,$my_pass,$my_prefix;

if(!$id) { return; }

$link = mysql_connect("$my_host","$my_user","$my_pass") or errd("MySQL Error!","Connection to MySQL server : $my_host failed!");
mysql_select_db($my_db , $link) or errd("MySQL Error!","Couldn't open database $my_db !");

$tbl=$my_prefix."comments";

mysql_query("DELETE FROM $tbl WHERE ext1='$id'") or errd("MySQL error!","Cant write to table $tbl !");

mysql_close($link);
}

//#################### Show posts posted on a specific date ##########

function showbyDate($d="",$m="",$y="") {
global $s_title,$m_cmt;

if(!$d || $d > 31 || $d < 1) { $d=date("j"); }
if(!$m || strlen($m) != 3) { $m=date("M"); }
if(!$y || strlen($y) != 4) { $y=date("Y"); }


$ar=getPostList("hd");

hdr("$s_title","");

for($b=0;$b<=count($ar[title])-1;$b++) {

$t_date=date("j_M_Y",$ar[date][$b]);
$tmp_date=$d."_".$m."_".$y;

if(trim($t_date) == trim($tmp_date)) {
echo doDat($ar[id][$b]);
}

}
ftr("","");
exit();
}


?>