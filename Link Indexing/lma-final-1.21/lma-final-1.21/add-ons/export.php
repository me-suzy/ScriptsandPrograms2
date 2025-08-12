<?

header("Content-type: text/plain");

error_reporting(E_ALL ^ E_NOTICE);

ignore_user_abort(0);

require("settings.php");

import_request_variables("cgp");

if ($mysqlserver) {

	$lank = mysql_pconnect($mysqlserver, $mysqllogin, $mysqlpassword);
	mysql_select_db($mysqldb);

}

function getindex($cat) {

	global $pagenames, $pext;


	if ($pagenames == "1") {
		return str_replace(" ", "_", $cat);
	}
	elseif ($pagenames == "2") {
		return "index";
	}

}

$pole = mysql_query("select category, url from lma_links");

$baseurl = "http://".$_SERVER["SERVER_NAME"].str_replace("export.php", "", $_SERVER["SCRIPT_NAME"]).$basedir;

$fp = fopen("export.txt", "w");

while ($link = mysql_fetch_object($pole)) {

	$goo = 1;
	$lacat = $link->category;

	unset($x);

	//if ($link->url == "http://www.parrotsandprops.com/") {

	while ($goo == 1 and $x < 100) {

		$x++;

		$pol = mysql_fetch_object(mysql_query("select * from lma_categories where id = '$lacat'"));

		if ($lacat == $link->category) $caturl = "/". str_replace(" ", "_", $pol->name) ."/". getindex($pol->name) . ".$pext";
		else $caturl = "/".str_replace(" ", "_", $pol->name).$caturl;

		$lacat = $pol->parent;

		if ($pol->parent == "0") $goo = 0;

	}

	fputs($fp, "".$link->url.";$baseurl$caturl
");

	//}

}

fclose($fp);


if ($mysqlserver) mysql_close($lank);


?> ALL DONE