<?
include("checkFunctions.php");
include("../libs/xx/class.Error.php");

$Error = new Error();

$Error->silence();

$filename = "config.ini";
$descr = $names = $values = array();
$pr_id = $pr_pas = "";
if(!getParameters($filename,&$descr,&$names,&$values)){
	$msg = "Cant Read File $filename!!<br>";
}

if(!isset($cmd)) $cmd = "form";

?>
<html>
<head>
	<title>Setup</title>
</head>

<body>
<?
	if($cmd == "form") {
		$warn_message="";
		show_form($descr,$names,$values,$warn_message);
	}

if ($cmd == "createconfig") {
?>
<table align=center width=300 cellpadding=0 cellspacing=0 border=1 bgcolor=#cccccc bordercolor=#999999>
<tr>
	<td align=center>
<?		
while (list($key, $val) = each($result)){
	for($i=0; $i<sizeof($names); $i++){
		if($names[$i] == $key) $values[$i]=$val;
		if($names[$i] == "__CFG_HOSTNAME") 		 $db_host = $values[$i];//for source type mysql
		if($names[$i] == "__CFG_USERNAME")		 $db_login = $values[$i];//for source type mysql
		if($names[$i] == "__CFG_PASSWORD")		 $db_passw = $values[$i];//for source type mysql
		if($names[$i] == "__CFG_DATABASE")		 $db_name = $values[$i];//for source type mysql
	}
}

$flag = 1;
$warn_message = "";
$good_message = "";
$flag=1;

//check config.php is writeble?
$f = "../config.php";
if(!is_writable($f)) {
	$flag=0;
	$warn_message = $warn_message."File $f is not Writeable!!<br>";
}
	
//check defaultconfig.ini is readable?
$f = "defaultconfig.ini";
if(!is_readable($f)) {
	$flag=0;
	$warn_message = $warn_message."File $f is not Readable!!<br>";
} else {
	$defaultConfig = file($f);
}

//check compile dir is writeble?
$f = "../resources/compile/";
/*echo(decbin(fileperms($f)));
$isWr = substr(decbin(fileperms($f)),strlen(decbin(fileperms($f)))-2,1);
echo "<br>".$isWr;*/

if(!substr(decbin(fileperms($f)),strlen(decbin(fileperms($f)))-2,1)) {
	$flag=0;
	$warn_message = $warn_message."Dir $f is not Writeable!!<br>";
}

//clear compile dir	
if (clearDir($f)==0){
	$flag=0;
	$warn_message = $warn_message."Directory compile cannot be cleared!<br>";
}

$f = "../resources/banners/";
if(!substr(decbin(fileperms($f)),strlen(decbin(fileperms($f)))-2,1)) {
	$flag=0;
	$warn_message = $warn_message."Dir $f is not Writeable!!<br>";
}

$errors = array();
$f="dump.txt";
if(makeBase($db_host,$db_login,$db_passw,$db_name,$f,&$errors)){
	$flag = 0;
	while (list ($key, $val) = each ($errors)){
		$warn_message = $warn_message.$val;
	}
}

//rewriting config.php
$f="../config.php";
if($flag){
	chmod($f,755);
	$fp = fopen ($f, "w");
	if(!$fp) {
		$flag=0;
		$warn_message = $warn_message."Can't write file $f!!<br>";
	} else {
		fputs($fp,"<?php\n",16);
		//write default data
		//fputs($fp,"//default settings\n",255);
		for($i=0; $i<sizeof($defaultConfig); $i++) {
			fputs($fp,$defaultConfig[$i],4096);
		}
		fputs($fp,"\n",16);
		fputs($fp,"//user settings\n",255);
		//write users data
		for($i=0; $i<sizeof($names); $i++) {
			$str = "define(\"".$names[$i]."\",\"".$values[$i]."\");\n";
			fputs($fp,$str,4096);
		}
		fputs($fp,"?>",16);
		fclose($fp);
	}
}

if($flag){
	echo "
	<p style='color: #ff0000'>All Right!!</p>
	<hr width=90%>
	<p>Go to <a href='../admin.php?mode=login'>admin</a> area</p>
 	";
} else {
	show_form($descr,$names,$values,$warn_message);
}

}
?>
	</td>
</tr>
</table>

</body>
</html>